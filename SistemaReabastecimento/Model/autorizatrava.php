<?php

session_start();
include "../../conexaophp.php";

$user = $_POST['user'];
$senha = $_POST['senha'];

$params = array($user, $senha);

$tsqlAutorizaCorte = "SELECT CODUSU FROM TSIUSU WHERE NOMEUSU = ? AND AD_SENHA = ? AND CODUSU IN (1696, 32, 3195, 692, 3266, 42, 4418, 181, 694, 7257, 100, 3, 36, 4067)";
$stmtAutorizaCorte = sqlsrv_query($conn, $tsqlAutorizaCorte, $params);

$row = sqlsrv_fetch_array($stmtAutorizaCorte, SQLSRV_FETCH_NUMERIC);

if (isset($row[0])) {
    echo $row[0];
} else {
    echo 'erro';
}
