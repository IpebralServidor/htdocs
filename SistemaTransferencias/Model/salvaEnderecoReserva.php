<?php

include "../../conexaophp.php";

$nunota = $_POST["nunota"];
$endereco = $_POST["endereco"];
$reserva = $_POST["reserva"];

$tsqlLocal = "SELECT CODLOCAL FROM TGFLOC WHERE ATIVO = 'S' AND CODLOCAL = $reserva";
$stmtLocal = sqlsrv_query($conn, $tsqlLocal);
$rowLocal = sqlsrv_fetch_array($stmtLocal, SQLSRV_FETCH_NUMERIC);


if (!isset($rowLocal[0])) {
    echo "Endereço não existe ou não está ativo.";
} else {
    $enderecoReserva = $endereco . "_" . $reserva;

    $tsql = "UPDATE TGFCAB SET AD_PARAMETROS_REABAST = '$enderecoReserva' WHERE NUNOTA = $nunota";

    sqlsrv_query($conn, $tsql);

    echo "OK";
}
