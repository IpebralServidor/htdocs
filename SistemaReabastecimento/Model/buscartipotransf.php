<?php
include "../../conexaophp.php";

$nunota = $_GET['nunota'];
$params = array($nunota);

$tsql = "   SELECT AD_PEDIDOECOMMERCE 
                    FROM TGFCAB 
                    WHERE NUNOTA = ?";

$stmt = sqlsrv_query($conn, $tsql, $params);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo $row[0];
