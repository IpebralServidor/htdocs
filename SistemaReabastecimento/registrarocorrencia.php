<?php
include "../conexaophp.php";
require_once '../App/auth.php';

$nunota = $_POST["nunota"];
$sequencia = $_POST["sequencia"];
$ocorrencia = $_POST["ocorrencia"];

$tsqlRegistraOcorrencia= "EXEC [sankhya].[AD_STP_REGISTRA_OCORRENCIA_REABASTECIMENTO] $nunota, $sequencia, '$ocorrencia'"; 
$stmtRegistraOcorrencia = sqlsrv_query( $conn, $tsqlRegistraOcorrencia);  
$rowRegistraOcorrencia= sqlsrv_fetch_array( $stmtRegistraOcorrencia, SQLSRV_FETCH_NUMERIC);

echo $rowRegistraOcorrencia[0];
?>