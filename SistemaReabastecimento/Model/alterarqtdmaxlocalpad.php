<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$qtd = $_POST["qtd"];
$locpad = $_POST["locpad"];
$codemp = $_POST["codemp"];
$codprod = $_POST["codprod"];

$tsqlMaxlocpad = "EXEC AD_STP_ALTERAR_QTD_MAX_LOCAL_PADRAO_REABASTECIMENTO $qtd, $locpad, $codemp, $codprod";
$stmtMaxlocpad = sqlsrv_query($conn, $tsqlMaxlocpad);
$rowMaxlocpad = sqlsrv_fetch_array($stmtMaxlocpad, SQLSRV_FETCH_NUMERIC);

echo $rowMaxlocpad[0];
