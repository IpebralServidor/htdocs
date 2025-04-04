<?php
include "../../conexaophp.php";

$nunota = $_GET['nunota'];
$params = array($nunota);

$tsql = "SELECT TOP 1 CAB.AD_PEDIDOECOMMERCE, CAB.AD_GARANTIAVERIFICADA, ITE.AD_VINCULONF 
        FROM TGFCAB CAB INNER JOIN
            TGFITE ITE ON CAB.NUNOTA = ITE.NUNOTA
        WHERE CAB.NUNOTA = ?
        AND ITE.AD_VINCULONF IS NOT NULL";

$stmt = sqlsrv_query($conn, $tsql, $params);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo $row[0] . '~' . $row[1] . '~' . $row[2];
