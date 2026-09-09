<?php

session_start();
include "../../conexaophp.php";

$user = $_POST['user'];
$senha = $_POST['senha'];
$usucorte = $_SESSION["idUsuario"];

$params = array($user, $senha, 'A', $usucorte);

$tsqlAutorizaCorte = "SELECT CODUSU FROM TSIUSU WHERE NOMEUSU = ? AND AD_SENHA = ? AND AD_PERMISSAO_CONFERENCIA = ? AND CODUSU <> ?";
$stmtAutorizaCorte = sqlsrv_query($conn, $tsqlAutorizaCorte, $params);

$row = sqlsrv_fetch_array($stmtAutorizaCorte, SQLSRV_FETCH_NUMERIC);

if (isset($row[0])) {
    echo $row[0];
} else {
    echo 'erro';
}
