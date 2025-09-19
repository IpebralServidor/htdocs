<?php
include "../../conexaophp.php";

$nunota = $_GET['nunota'];
$params = array($nunota);

$tsql = "SELECT COUNT(*) 
                FROM TGFITE 
                WHERE NUNOTA = ?
                AND SEQUENCIA > 0
                AND QTDNEG <> 0";

$stmt = sqlsrv_query($conn, $tsql, $params);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo $row[0];
