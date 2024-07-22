<?php


include "../../conexaophp.php";

$nunota = $_POST['nunota'];
$params = array($nunota);
$tsql = "EXEC [sankhya].[AD_STP_ABRE_VOLUME_CONFERENCIA] ?";
$stmt = sqlsrv_query($conn, $tsql, $params);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo $row[0];
