<?php

include "../../conexaophp.php";
session_start();

$request = $_POST["numeroNota"];

$tsqlTipoper = "SELECT COUNT([sankhya].[AD_FN_VINCULO_NOTA_REABASTECIMENTO]($request))";
$stmtTipoper = sqlsrv_query($conn, $tsqlTipoper);
$rowTipoper = sqlsrv_fetch_array($stmtTipoper, SQLSRV_FETCH_NUMERIC);

echo $rowTipoper[0];
