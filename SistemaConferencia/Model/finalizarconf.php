<?php

session_start();
include "../../conexaophp.php";

$qtdvol = $_POST['qtdvol'];
$volume = $_POST['volume'];
$pesobruto = $_POST['pesobruto'];
$nunota = $_POST['nunota'];
$observacao = $_POST['observacao'];
$frete = $_POST['frete'];

if ($_POST['mtvdivergencia'] == null) {
    $_POST['mtvdivergencia'] = '';
}

$mtvdivergencia = $_POST['mtvdivergencia'];

$usuconf = $_SESSION["idUsuario"];

$tsqlPendente = "select count(1) as contador from [sankhya].[AD_FN_pendencias_CONFERENCIA]($nunota)";
$stmtPendente = sqlsrv_query($conn, $tsqlPendente);
$rowPendente = sqlsrv_fetch_array($stmtPendente, SQLSRV_FETCH_NUMERIC);
$linhasPendente = $rowPendente[0];

$tsql5 = "select count(*) from [sankhya].[AD_FN_PRODUTOS_DIVERGENTES_CONFERENCIA]($nunota)";
$stmt5 = sqlsrv_query($conn, $tsql5);
$row2 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_NUMERIC);
$linhas = $row2[0];
if ($linhas > 0 || $linhasPendente > 0) {
    $tsql4 = "EXEC [sankhya].[AD_STP_CORTAITENS_CONFERENCIA] $nunota, $usuconf, '$pesobruto', '$qtdvol', '$volume', '$observacao', $frete, '$mtvdivergencia' ";
} else {
    $tsql4 = "EXEC [sankhya].[AD_STP_FINALIZAR_CONFERENCIA] $nunota, $usuconf, '$pesobruto', '$qtdvol', '$volume', '$observacao', '', $frete ";
}
$stmt4 = sqlsrv_query($conn, $tsql4);
$row = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_NUMERIC);

echo $row[0];
