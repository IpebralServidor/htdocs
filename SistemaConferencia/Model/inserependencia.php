<?php
include "../../conexaophp.php";

$nunota = $_POST['nunota'];
$referencia = $_POST['referencia'];
$quantidade = $_POST['quantidade'];

$tsql = "EXEC [sankhya].[AD_STP_INSERIR_PENDENCIA] $nunota, '$referencia', $quantidade";
$stmt = sqlsrv_query($conn, $tsql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo $row[0];
