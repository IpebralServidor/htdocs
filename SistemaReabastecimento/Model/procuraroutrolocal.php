<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota2 = $_POST["nunota"];
$sequencia = $_POST["sequencia"];
$codprod = $_POST["codprod"];
$qtdneg = $_POST["qtdneg"];
$codusu = $_POST["codusu"];
$ocorrencia = $_POST["ocorrencia"];

if ($qtdneg == '') {
    $qtdneg = 0;
}

$tsqlOutroLocal = "EXEC AD_STP_PEGAR_OUTRO_LOCAL_REABASTECIMENTO $nunota2, $sequencia, $codprod, $qtdneg, $codusu, '$ocorrencia'";
$stmtOutroLocal = sqlsrv_query($conn, $tsqlOutroLocal);
$rowOutroLocal = sqlsrv_fetch_array($stmtOutroLocal, SQLSRV_FETCH_NUMERIC);

echo $rowOutroLocal[0];
