<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

$codusu = $_SESSION['idUsuario'];
$nunota2 = $_POST["nunota"];

$tsqlTimer = "SELECT (SUM(DATEDIFF(sECOND, ISNULL(DTFIM,gETDATE()),DTINIC)) *-1) FROM AD_TGFAPONTAMENTOATIVIDADE WHERE NUNOTA = $nunota2 AND CODUSU = $codusu";
$stmtTimer = sqlsrv_query($conn, $tsqlTimer);
$rowTimer = sqlsrv_fetch_array($stmtTimer, SQLSRV_FETCH_NUMERIC);

echo $rowTimer[0];
