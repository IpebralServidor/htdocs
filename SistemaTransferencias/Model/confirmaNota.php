<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota = $_POST["nunota"];
$usuconf = $_SESSION["idUsuario"];

$tsqlConfirmaNota = "EXEC [sankhya].[AD_STP_CONFIRMA_NOTA_TRANSFERENCIA] $nunota,$usuconf";
$stmtConfirmaNota = sqlsrv_query($conn, $tsqlConfirmaNota);
$rowConfirmaNota = sqlsrv_fetch_array($stmtConfirmaNota, SQLSRV_FETCH_NUMERIC);

echo utf8_encode($rowConfirmaNota[0]);
