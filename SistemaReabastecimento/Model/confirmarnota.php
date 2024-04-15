<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota2 = $_POST["nunota"];
$codusu = $_SESSION["idUsuario"];

$tsqlAlterarQtd = "EXEC [sankhya].[AD_STP_CONFIRMAR_NOTA_REABASTECIMENTO] $nunota2, $codusu";
$stmtAlterarQtd = sqlsrv_query($conn, $tsqlAlterarQtd);
$rowAlterarQtd = sqlsrv_fetch_array($stmtAlterarQtd, SQLSRV_FETCH_NUMERIC);

echo $rowAlterarQtd[0];
