<?php
include "../../conexaophp.php";
session_start();

$produto = $_POST["produto"];
$quantidade = $_POST["quantidade"];
$endereco = $_POST["endereco"];
$nunotaorig = $_POST["nunota"];
$checkvariosprodutos = $_POST["checkvariosprodutos"];
$usuconf = $_SESSION["idUsuario"];

$tsql = "exec AD_STP_INSEREPRODUTO_PROCESSOESTOQUECD5 $nunotaorig, '$quantidade', '$produto', '$endereco', '$checkvariosprodutos',$usuconf";
$stmt = sqlsrv_query($conn, $tsql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
$retorno = $row[0];
echo $retorno;
