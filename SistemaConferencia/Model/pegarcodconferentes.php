<?php

include "../../conexaophp.php";

$tsqlText = "SELECT TEXTO FROM TSIPAR WHERE CHAVE = 'UsuConferencia'";
$stmtText = sqlsrv_query($conn, $tsqlText);
$rowText = sqlsrv_fetch_array($stmtText, SQLSRV_FETCH_NUMERIC);

echo json_encode($rowText);
