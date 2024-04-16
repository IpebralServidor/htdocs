<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota2 = $_POST["nunota"];
$codusu = $_POST["codusu"];
$qtdneg = $_POST["qtdneg"];
$sequencia = $_POST["sequencia"];
$referencia = $_POST["referencia"];
$endereco = $_POST["endereco"];
$ocorrencia = $_POST["ocorrencia"];
$fila = $_SESSION["fila"];

if (empty($qtdneg)) {
    $qtdneg = 0;
}

$tsqlReabastecimento = "EXEC [sankhya].[AD_STP_PROCESSO_REABASTECIMENTO] $nunota2, $qtdneg, $codusu, $sequencia, '$referencia', '$endereco', '$ocorrencia', '$fila'";
$stmtReabastecimento = sqlsrv_query($conn, $tsqlReabastecimento);
$rowReabastecimento = sqlsrv_fetch_array($stmtReabastecimento, SQLSRV_FETCH_NUMERIC);

echo $rowReabastecimento[0];
