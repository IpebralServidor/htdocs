<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota = $_POST["nunota"];
$params = array($nunota);

$tsql = "UPDATE TGFITE SET AD_CODUSUBIP = NULL WHERE NUNOTA = ? AND ATUALESTOQUE = 0 AND AD_DTBIP IS NULL AND AD_CODUSUBIP IS NOT NULL";
$stmt = sqlsrv_query($conn, $tsql, $params);
$rows_affected = sqlsrv_rows_affected($stmt);

if ($rows_affected == 0) {
    echo "Nenhum produto para liberar.";
} else if ($rows_affected > 0) {
    echo "Produtos liberados.";
}
