<?php
include "../../conexaophp.php";

$codprod = $_POST['codprod'];
$codemp = $_POST['codemp'];
$localVazio = $_POST['localVazio'];

$params = array($codprod, $codemp, $localVazio);


$tsql = "EXEC [sankhya].[AD_STP_DESATIVA_LOCALPAD] ?, ?, ?";

$stmt = sqlsrv_query($conn, $tsql, $params);

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo $row[0];
