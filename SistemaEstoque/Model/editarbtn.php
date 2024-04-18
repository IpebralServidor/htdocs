<?php
include "../../conexaophp.php";

session_start();

$nunotadest = $_POST["nunota"];
$enderecoeditar = $_POST["localdest"];
$quantidadeeditar = $_POST["quantidade"];
$produto = $_POST["produto"];

$tsql2 = "EXEC AD_STP_EDITARPRODUTONOTA_SISTEMAESTOQUE $nunotadest, $enderecoeditar, $quantidadeeditar, '$produto'";

$stmt2 = sqlsrv_query($conn, $tsql2);

$row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC);
$retorno = $row[0];
echo $retorno;
