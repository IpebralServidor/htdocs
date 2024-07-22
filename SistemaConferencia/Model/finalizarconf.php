<?php

session_start();
include "../../conexaophp.php";

$qtdvol = $_POST['qtdvol'];
$volume = $_POST['volume'];
$pesobruto = $_POST['pesobruto'];
$nunota = $_POST['nunota'];
$observacao = $_POST['observacao'];
$frete = $_POST['frete'];
$codusulib = $_POST['codusulib'];
$params = array($nunota);
// Simula o corte dos itens para saber se algum problema vai ocorrer
$tsqlChecaCorte = "EXEC [sankhya].[AD_STP_CHECA_CORTE_CONFERENCIA] ?";
$stmtChecaCorte = sqlsrv_query($conn, $tsqlChecaCorte, $params);
if ($stmtChecaCorte === false) {
    $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS);

    if ($errors !== null) {
        foreach ($errors as $error) {
            // Remover a parte '[Microsoft][ODBC Driver 17 for SQL Server][SQL Server]' da mensagem
            $errorMessage = $error['message'];
            $errorMessage = preg_replace('/\[[^\]]*\]/', '', $errorMessage);
            echo trim($errorMessage);
            die;
        }
    }
}
$checkCorte = sqlsrv_fetch_array($stmtChecaCorte, SQLSRV_FETCH_NUMERIC);
if ($checkCorte[0] == 'ERRO') {
    echo 'PHP: Ocorreu um erro durante o corte na finalização. Favor procurar a equipe de T.I.';
} else if ($checkCorte[0] == 'OK') {
    $tsqlAtualizaIte = "EXEC [sankhya].[AD_STP_ATUALIZA_ITE_CONFERENCIA] ?";
    $stmtAtualizaIte = sqlsrv_query($conn, $tsqlAtualizaIte, $params);
    $rowAtualizaIte = sqlsrv_fetch_array($stmtAtualizaIte, SQLSRV_FETCH_NUMERIC);
    if ($rowAtualizaIte[0] == 'OK') {
        if ($_POST['mtvdivergencia'] == null) {
            $_POST['mtvdivergencia'] = '';
        }

        $mtvdivergencia = $_POST['mtvdivergencia'];

        $usuconf = $_SESSION["idUsuario"];

        $tsqlPendente = "select count(1) as contador from [sankhya].[AD_FN_pendencias_CONFERENCIA](?)";
        $stmtPendente = sqlsrv_query($conn, $tsqlPendente, $params);
        $rowPendente = sqlsrv_fetch_array($stmtPendente, SQLSRV_FETCH_NUMERIC);
        $linhasPendente = $rowPendente[0];

        $tsql5 = "select count(*) from [sankhya].[AD_FN_PRODUTOS_DIVERGENTES_CONFERENCIA](?)";
        $stmt5 = sqlsrv_query($conn, $tsql5, $params);
        $row2 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_NUMERIC);
        $linhas = $row2[0];
        if ($linhas > 0 || $linhasPendente > 0) {
            $tsql4 = "EXEC [sankhya].[AD_STP_CORTAITENS_CONFERENCIA] $nunota, $usuconf, '$pesobruto', '$qtdvol', '$volume', '$observacao', $frete, '$mtvdivergencia', '$codusulib' ";
        } else {
            $tsql4 = "EXEC [sankhya].[AD_STP_FINALIZAR_CONFERENCIA] $nunota, $usuconf, '$pesobruto', '$qtdvol', '$volume', '$observacao', '', $frete, '$codusulib' ";
        }
        $stmt4 = sqlsrv_query($conn, $tsql4);
        $row = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_NUMERIC);

        echo $row[0];
    } else {
        echo 'PHP: Ocorreu um erro durante o corte na finalização. Favor procurar a equipe de T.I.';
    }
}
