<?php

include "../../conexaophp.php";
session_start();

$request = $_POST["numeroNota"];

$tsqlTipoper = "SELECT CODTIPOPER FROM TGFCAB WHERE NUNOTA = $request AND STATUSNOTA <> 'L'";
$stmtTipoper = sqlsrv_query($conn, $tsqlTipoper);
$rowTipoper = sqlsrv_fetch_array($stmtTipoper, SQLSRV_FETCH_NUMERIC);
if(!isset($rowTipoper)) {
    echo -1;
} else {
    echo $rowTipoper[0];
}

