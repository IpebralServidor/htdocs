<?php
include "../../conexaophp.php";

$nunotadest = $_GET['nunota'];

$tsql = "
        SELECT * FROM sankhya.AD_FNT_RetornaInfoNota_SistemaEstoque($nunotadest)";

$stmt = sqlsrv_query($conn, $tsql);

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

echo json_encode($row);
