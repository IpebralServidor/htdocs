<?php

session_start();
set_time_limit(600);

include "../../conexaophp.php";

// Caminho do script Python responsavel por ler o PDF e devolver o JSON.
// Ajuste se o python3 do servidor nao estiver no PATH (ex: '/usr/bin/python3').
$pythonBin    = 'C:\Users\leand\AppData\Local\Programs\Python\Python313\python.exe';//'python3';
$scriptPython = __DIR__ . '/scripts/parse_unimed.py';

// Converte UTF-8 para Windows-1252 (padrão SQL Server VARCHAR)
function converteSql($valor) {
    if ($valor === null) return null;
    $valor = (string) $valor;
    return mb_convert_encoding($valor, 'Windows-1252', 'UTF-8');
}


// Converte data no formato brasileiro (dd/mm/yyyy) para o formato ISO (yyyy-mm-dd),
// que o SQL Server aceita sem ambiguidade em colunas DATE/DATETIME.
// Retorna null se a data vier vazia ou em formato inesperado.
function converteData($dataBr) {
    if (empty($dataBr)) return null;
    $dataBr = trim($dataBr);
    if (!preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $dataBr, $m)) {
        return null;
    }
    // $m[1] = dia, $m[2] = mes, $m[3] = ano
    return $m[3] . '-' . $m[2] . '-' . $m[1];
}

// Formata o array de erros do sqlsrv_errors() de forma legivel (em vez de print_r cru)
function formatarErroSql($erros) {
    if (empty($erros)) return "Erro desconhecido no banco de dados.";
    $linhas = [];
    foreach ($erros as $e) {
        $linhas[] = "[SQLSTATE {$e['SQLSTATE']}] " . $e['message'];
    }
    return "Erro ao gravar no banco de dados:\n" . implode("\n", $linhas);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_FILES['pdfFile']) && $_FILES['pdfFile']['error'] == UPLOAD_ERR_OK) {

        $nomeArquivo   = uniqid('unimed_') . '_' . basename($_FILES['pdfFile']['name']);
        $uploadFilePath = 'uploads/' . $nomeArquivo;
        move_uploaded_file($_FILES['pdfFile']['tmp_name'], $uploadFilePath);

        $tipoArquivo = $_POST['tipoArquivo'] ?? null;

        // ---- 1) Chama o Python para extrair os dados do PDF ----
        $comando = escapeshellcmd($pythonBin) . ' ' . escapeshellarg($scriptPython) . ' ' . escapeshellarg($uploadFilePath);
        $saidaPython = shell_exec($comando . ' 2>&1');

        $dadosExtraidos = json_decode($saidaPython, true);

        if (!$dadosExtraidos || !isset($dadosExtraidos['ok']) || $dadosExtraidos['ok'] !== true) {
            $erro = $dadosExtraidos['erro'] ?? $saidaPython;
            die("Erro ao processar o PDF: " . $erro);
        }

        $header        = $dadosExtraidos['header'];
        $beneficiarios = $dadosExtraidos['beneficiarios'];

        // ---- 2) Grava no banco (SQL Server) ----
        // OBS: nomes de tabela/coluna abaixo sao placeholders - ajustar depois
        // conforme a estrutura definitiva (AD_UNIMED_FATURA / AD_UNIMED_BENEFICIARIO).

        $sqlFatura = "DELETE FROM AD_UNIMED_FATURA
                      DELETE FROM AD_UNIMED_BENEFICIARIO
                      INSERT INTO AD_UNIMED_FATURA (NOTAFISCAL, COMPETENCIA, CNPJ, TOTALFATURA, TIPOARQUIVO) VALUES (?, ?, ?, ?, ?)";
        $paramsFatura = [
            $header['nota_fiscal'],
            $header['competencia'],
            $header['cnpj'],
            $header['total_relatorio'],
            $tipoArquivo,
        ];

        $stmtFatura = sqlsrv_query($conn, $sqlFatura, $paramsFatura);

        if ($stmtFatura === false) {
            die("ERRO: " . formatarErroSql(sqlsrv_errors()));
        }

        $sqlBeneficiario = "INSERT INTO AD_UNIMED_BENEFICIARIO
            (NOTAFISCAL, CODIGO, NOME, DATANASC, DATAINCL, FAIXAETARIA, CONTRATOREF,
             VALOR, DESCONTO, CREDITO, DEBITO, TOTAL, TITULAR, CODIGOTITULAR, TIPOARQUIVO)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        foreach ($beneficiarios as $b) {
            $paramsBeneficiario = [
                $header['nota_fiscal'],
                $b['codigo'],
                converteSql($b['nome']),
                converteData($b['data_nasc']),
                converteData($b['data_incl']),
                $b['faixa_etaria'],
                $b['contrato_ref'],
                $b['valor'],
                $b['desconto'],
                $b['credito'],
                $b['debito'],
                $b['total'],
                $b['eh_titular'] ? 1 : 0,
                $b['codigo_titular'],
                $tipoArquivo,
            ];

            $stmtBeneficiario = sqlsrv_query($conn, $sqlBeneficiario, $paramsBeneficiario);

            if ($stmtBeneficiario === false) {
                die("ERRO: " . formatarErroSql(sqlsrv_errors()) . "\n\nBeneficiario com erro: " . $b['nome'] . " (codigo " . $b['codigo'] . ")");
            }
        }

        $sqlInsereTabUnimed = "EXEC AD_STP_INSERIR_DADOS_FATURA_UNIMED ";

        $sqlInsereTabUnimed = sqlsrv_query($conn, $sqlInsereTabUnimed);

        if ($sqlInsereTabUnimed === false) {
            die(print_r(sqlsrv_errors(), true));
        }


        // ---- 3) Mensagem de retorno ----
        $mensagem = "Fatura importada com sucesso!\n"
            . "Cliente: " . $header['cliente'] . "\n"
            . "NFS-e: " . $header['nota_fiscal'] . " | Competencia: " . $header['competencia'] . "\n"
            . "Beneficiarios gravados: " . count($beneficiarios) . "\n"
            . "Diferenca (soma x total relatorio): " . $dadosExtraidos['diferenca'];

        echo $mensagem;

    } else {
        echo "Erro no upload do arquivo.";
    }
}

?>
