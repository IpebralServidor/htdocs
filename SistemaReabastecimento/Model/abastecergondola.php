<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota2 = $_POST["nunota"];
$sequencia = $_POST["sequencia"];
$codusu = $_POST["codusu"];
$codusubip = $_SESSION["idUsuario"];

$tsqlReabastecimento = "EXEC AD_STP_ABASTECER_GONDOLA_REABASTECIMENTO $nunota2, $sequencia, $codusu, $codusubip";
$stmtReabastecimento = sqlsrv_query($conn, $tsqlReabastecimento);
$rowReabastecimento = sqlsrv_fetch_array($stmtReabastecimento, SQLSRV_FETCH_NUMERIC);

echo $rowReabastecimento[0];
