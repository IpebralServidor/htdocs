<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota2 = $_POST["nunota"];
$codusu = $_SESSION["idUsuario"];

$tsqlAtividade = "EXEC [sankhya].[AD_STP_CHECKOUT_PHP] $codusu ,$nunota2";
$stmtAtividade = sqlsrv_query($conn, $tsqlAtividade);
$rowAtividade = sqlsrv_fetch_array($stmtAtividade, SQLSRV_FETCH_NUMERIC);

echo $rowAtividade[0];
