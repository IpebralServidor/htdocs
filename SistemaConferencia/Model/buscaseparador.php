<?php

include "../../conexaophp.php";

$nunota = $_POST['nota'];

$params = array($nunota);

$tsql = "SELECT AD_SEPARADOR, CODTIPOPER FROM TGFCAB WHERE NUNOTA = ?";
$stmt = sqlsrv_query($conn, $tsql, $params);

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

if ($row[0] === NULL && $row[1] == 1720) {
    echo $row[0];
} else {
    echo 'ok';
}
