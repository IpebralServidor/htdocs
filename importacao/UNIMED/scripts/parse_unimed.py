import pdfplumber
import re
import json
import sys

def to_float(s):
    if s is None:
        return 0.0
    s = s.strip()
    neg = s.startswith('(') and s.endswith(')')
    s = s.strip('()')
    s = s.replace('.', '').replace(',', '.')
    try:
        v = float(s)
    except ValueError:
        return 0.0
    return -v if neg else v

CODE_RE = re.compile(r'^\d{15,18}$')
CONTRACT_RE = re.compile(r'^\d{6,7}$')

# markers that identify repeated page header/column-header rows (must be skipped,
# they repeat on every page and would otherwise corrupt whichever beneficiary
# record is "open" when the page breaks)
HEADER_MARKERS = [
    'PLVLRFAT', 'Salutaris.com', 'Dados da Fatura', 'Pag.',
    'Nota Fiscal:', 'Competência:', 'Cliente:', 'Fechamento de cadastro',
    'Último fechamento', 'Data Cancelamento', 'Cliente Fatura:',
    'Cliente Repassado:', 'NFS-e:', 'Vlr. Tot. Pre Pagto:',
    'Nº Contrato Aux.:', 'Codigo Cliente', 'Produto Módulo',
]


def group_rows(words, tol=2):
    rows = {}
    for w in words:
        top = round(w['top'] / tol) * tol
        rows.setdefault(top, []).append(w)
    return [sorted(rows[k], key=lambda w: w['x0']) for k in sorted(rows)]

def field(row, x0, x1):
    toks = [w['text'] for w in row if x0 <= w['x0'] < x1]
    return ' '.join(toks).strip()

def parse_invoice(path):
    header = {}
    people = []
    current = None

    with pdfplumber.open(path) as pdf:
        # ---- Page 1: header totals ----
        p1 = pdf.pages[0]
        text1 = p1.extract_text()
        # Numero da nota fiscal vem do campo "NFS-e:"
        m = re.search(r'NFS-e:\s*(\d+)', text1)
        header['nota_fiscal'] = m.group(1) if m else None
        m = re.search(r'Compet[êe]ncia:\s*([\d/]+)', text1)
        header['competencia'] = m.group(1) if m else None
        m = re.search(r'Cliente:\s*(.+)', text1)
        header['cliente'] = m.group(1).strip() if m else None
        m = re.search(r'CNPJ:\s*([\d./-]+)', text1)
        header['cnpj'] = m.group(1) if m else None
        m = re.search(r'Total Relat[óo]rio:\s*([\d.,]+)', text1)
        header['total_relatorio'] = to_float(m.group(1)) if m else None
        m = re.search(r'Total Nota Fiscal:\s*([\d.,]+)', text1)
        header['total_nota_fiscal'] = to_float(m.group(1)) if m else None

        # per-contract subtotal block on page 1 (Num.Auxiliar / Id.Contrato / Acomodacao / Descricao / Valor)
        contratos = []
        for line in text1.split('\n'):
            m = re.match(r'^(\d{7})\s+(\d{7})\s+(Enfermaria|Apartamento)\s+(.+?)\s+([\d.,()-]+)$', line.strip())
            if m:
                contratos.append({
                    'num_auxiliar': m.group(1),
                    'id_contrato': m.group(2),
                    'acomodacao': m.group(3),
                    'descricao': m.group(4).strip(),
                    'valor': to_float(m.group(5)),
                })
        header['contratos'] = contratos

        # ---- Pages 2..N: per-beneficiary detail ----
        for page in pdf.pages[1:]:
            words = page.extract_words(use_text_flow=False, keep_blank_chars=False, x_tolerance=1)
            rows = group_rows(words)
            for row in rows:
                row_text = ' '.join(w['text'] for w in row)
                if row_text.startswith('RESUMO DE CLIENTES') or row_text.startswith('CLIENTES EXCLU'):
                    if current:
                        people.append(current)
                    current = None
                    break  # stop processing rest of this page's rows (summary section)
                if any(marker in row_text for marker in HEADER_MARKERS):
                    continue  # repeated page header / column header row, not data

                contr = field(row, 10, 50)
                codigo_raw = field(row, 50, 133)
                nome_part = field(row, 133, 288)  # coluna do nome vai ate ~x=288 (Data Nasc. comeca em ~x=290)
                data_nasc = field(row, 285, 335)
                faixa = field(row, 512, 560)

                codigo_match = CODE_RE.match(codigo_raw.replace(' ', ''))

                produto = field(row, 10, 100)
                produto_col = produto  # texto na coluna Produto => nao e continuacao de nome
                modulo = field(row, 100, 200)
                data_incl = field(row, 200, 285)  # coluna "Data Incl." das linhas de item
                valor = field(row, 375, 408)
                desconto = field(row, 408, 448)
                credito = field(row, 448, 483)
                debito = field(row, 483, 512)
                total = field(row, 555, 590)

                has_money = any([valor, desconto, credito, debito, total])

                if codigo_match:
                    # start of a new beneficiary
                    if current:
                        people.append(current)
                    current = {
                        'codigo': codigo_match.group(0),
                        'nome': nome_part,
                        'data_nasc': data_nasc,
                        'data_incl': None,
                        'faixa_etaria': faixa,
                        'contrato_ref': contr if CONTRACT_RE.match(contr) else None,
                        'valor': 0.0,
                        'desconto': 0.0,
                        'credito': 0.0,
                        'debito': 0.0,
                        'total': 0.0,
                        'itens': [],
                    }
                    continue

                if current is None:
                    continue

                if has_money and not (produto == '' and modulo == ''):
                    # module/produto line -> accumulate
                    current['itens'].append({
                        'produto': produto,
                        'modulo': modulo,
                        'data_incl': data_incl,
                        'valor': to_float(valor),
                        'desconto': to_float(desconto),
                        'credito': to_float(credito),
                        'debito': to_float(debito),
                        'total': to_float(total) if total else None,
                    })
                    current['valor'] += to_float(valor)
                    current['desconto'] += to_float(desconto)
                    current['credito'] += to_float(credito)
                    current['debito'] += to_float(debito)
                    if total:
                        current['total'] += to_float(total)
                    # data_incl "principal" do beneficiario = a da primeira linha de item
                    if not current.get('data_incl'):
                        current['data_incl'] = data_incl
                elif CONTRACT_RE.match(contr):
                    current['contrato_ref'] = contr
                elif nome_part and not codigo_raw and not has_money and not contr and not produto_col:
                    # wrapped name continuation line (so a coluna do nome, sem valores)
                    current['nome'] = (current['nome'] + ' ' + nome_part).strip()

        if current:
            people.append(current)

    _marcar_titulares(people)

    return header, people


def _marcar_titulares(people):
    """
    Identifica titular x dependente a partir do codigo do beneficiario.
    Os 14 primeiros digitos identificam a familia/matricula; os ultimos
    digitos identificam a pessoa dentro dela - o titular e sempre quem
    tem o menor sufixo (geralmente 000, 001, 002...).
    Preenche em cada pessoa: 'eh_titular' (bool) e 'codigo_titular'.
    """
    grupos = {}
    for p in people:
        prefixo = p['codigo'][:14]
        grupos.setdefault(prefixo, []).append(p)

    for prefixo, membros in grupos.items():
        membros_ordenados = sorted(membros, key=lambda m: int(m['codigo'][14:]))
        titular = membros_ordenados[0]
        for m in membros_ordenados:
            m['eh_titular'] = (m is titular)
            m['codigo_titular'] = titular['codigo']


if __name__ == '__main__':
    # Uso: python3 parse_unimed.py <caminho_do_pdf>
    # Sempre imprime APENAS um JSON em stdout (nada de texto solto),
    # para que o PHP (insereUNIMED.php) consiga fazer json_decode() direto
    # do retorno do shell_exec/proc_open.
    if len(sys.argv) < 2:
        print(json.dumps({'erro': 'Caminho do PDF nao informado.'}))
        sys.exit(1)

    path = sys.argv[1]

    try:
        header, people = parse_invoice(path)
        soma = sum(p['total'] for p in people)
        total_relatorio = header.get('total_relatorio') or 0.0

        resultado = {
            'ok': True,
            'header': header,
            'beneficiarios': people,
            'qtd_beneficiarios': len(people),
            'soma_beneficiarios': round(soma, 2),
            'diferenca': round(soma - total_relatorio, 2),
        }
        print(json.dumps(resultado, ensure_ascii=False))
    except Exception as e:
        print(json.dumps({'ok': False, 'erro': str(e)}, ensure_ascii=False))
        sys.exit(1)
