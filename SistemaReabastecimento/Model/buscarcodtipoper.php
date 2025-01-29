<?php

include "../../conexaophp.php";
session_start();

$codusu = $_SESSION["idUsuario"];
$request = $_POST["numeroNota"];

$tsqlTipoper = "SELECT CODTIPOPER, AD_PEDIDOECOMMERCE, AD_GARANTIAVERIFICADA FROM TGFCAB WHERE NUNOTA = $request AND STATUSNOTA <> 'L'";
$stmtTipoper = sqlsrv_query($conn, $tsqlTipoper);
$rowTipoper = sqlsrv_fetch_array($stmtTipoper, SQLSRV_FETCH_ASSOC);
if (!isset($rowTipoper)) {
    echo -1;
} else if (!str_starts_with($rowTipoper['CODTIPOPER'], '13')) {
    echo -2;
} else {
    $paramsTrava = array($rowTipoper['AD_PEDIDOECOMMERCE'], $rowTipoper['AD_GARANTIAVERIFICADA'], $codusu, $request);
    $tsqlTrava = "SELECT sankhya.AD_FN_TRAVA_NOTAS_APP(?, ?, ?, ?)";
    $stmtTrava = sqlsrv_query($conn, $tsqlTrava, $paramsTrava);
    $rowTrava = sqlsrv_fetch_array($stmtTrava, SQLSRV_FETCH_NUMERIC);
    echo $rowTrava[0];
}
