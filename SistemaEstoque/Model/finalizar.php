<?php
include "../../conexaophp.php";

session_start();

$nunotaorig = $_POST["nunota"];
$usuconf = $_SESSION["idUsuario"];

$tsql = "exec AD_STP_FINALIZAPROCESSOESTOQUECD5 $nunotaorig, $usuconf";
$stmt = sqlsrv_query($conn, $tsql);

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
$retorno = $row[0];
echo $retorno;
