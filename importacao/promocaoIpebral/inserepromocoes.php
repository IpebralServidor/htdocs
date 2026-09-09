<?php

session_start();
set_time_limit(600);

include "../../conexaophp.php";
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Converte UTF-8 para Windows-1252 (padrão SQL Server VARCHAR)
function converteSql($valor) {
    if ($valor === null) return null;
    $valor = (string) $valor;
    return mb_convert_encoding($valor, 'Windows-1252', 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == UPLOAD_ERR_OK) {

        $uploadFilePath = 'uploads/' . basename($_FILES['excelFile']['name']);
        move_uploaded_file($_FILES['excelFile']['tmp_name'], $uploadFilePath);

        $numeroPromocao = $_POST['numeroPromocao'];

        // Ler o arquivo Excel
        $spreadsheet = IOFactory::load($uploadFilePath);
        $sheet = $spreadsheet->getActiveSheet();

        $dados = [];

        // Varre as linhas a partir da linha 3 (linha 1 = cabeçalho promoção, linha 2 = cabeçalho colunas)
        foreach ($sheet->getRowIterator(2) as $linha) {
            $cellIterator = $linha->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $linhaDados = [];
            foreach ($cellIterator as $celula) {
                $linhaDados[] = $celula->getValue();
            }

            // Só adiciona se tiver referência preenchida (coluna D = índice 3)
            if (!empty($linhaDados[3])) {
                $dados[] = [
                    'ORDEMTITULO'  => $linhaDados[0] ?? null,           // Coluna A
                    'TITULO'       => converteSql($linhaDados[1] ?? null),  // Coluna B - texto com acentos
                    'ORDEM'        => $linhaDados[2] ?? null,           // Coluna C
                    'REFERENCIA'   => $linhaDados[3] ?? null,           // Coluna D
                    'DESCONTO'     => $linhaDados[4] ?? null,           // Coluna E
                    'QTDMIN'       => $linhaDados[5] ?? null,           // Coluna F
                ];
            }
        }

        // Chama a procedure passando os dados linha a linha + número da promoção
        $sql = "EXEC SANKHYA.AD_STP_INSERE_TEMP_PROMOCOES_IPEBRAL ?, ?, ?, ?, ?, ?, ?";

        foreach ($dados as $linha) {
            $params = [
                $numeroPromocao,
                $linha['ORDEMTITULO'],
                $linha['TITULO'],
                $linha['ORDEM'],
                $linha['REFERENCIA'],
                $linha['DESCONTO'],
                $linha['QTDMIN']
            ];

            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
        }

        // Chama a procedure de validação e captura a mensagem de retorno
        $sqlValidacao = "EXEC SANKHYA.AD_STP_INSERE_PROMOCOES_IPEBRAL ?";
        $params = [
                $numeroPromocao
            ];
        $stmtValidacao = sqlsrv_query($conn, $sqlValidacao,  $params);

        if ($stmtValidacao === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Pega a mensagem retornada pela procedure (SELECT ou PRINT)
        $mensagem = "";
        if (sqlsrv_has_rows($stmtValidacao)) {
            while ($row = sqlsrv_fetch_array($stmtValidacao, SQLSRV_FETCH_ASSOC)) {
                $mensagem .= reset($row) . "\n";
            }
        }

        // Se a procedure não retornou nada, mensagem padrão
        if (empty(trim($mensagem))) {
            $mensagem = "Promoções inseridas e validadas com sucesso! (" . count($dados) . " registros)";
        }

        echo mb_convert_encoding($mensagem, 'UTF-8', 'Windows-1252');

    } else {
        echo "Erro no upload do arquivo.";
    }
}

?>