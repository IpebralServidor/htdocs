<?php
include "../../conexaophp.php";
session_start();

$produto = $_POST["produto"];
$quantidade = $_POST["quantidade"];
$endereco = $_POST["endereco"];
$nunotaorig = $_POST["nunota"];
$checkvariosprodutos = $_POST["checkvariosprodutos"];
$produtoBipado = $_POST["produtoBipado"];
$enderecoBipado = $_POST["enderecoBipado"];
$enderecoTempBipado = $_POST["enderecoTempBipado"];
$usuconf = $_SESSION["idUsuario"];

$observacao = '';
if ($produtoBipado === 'N') {
    $observacao .= '| Produto digitado ';
}
if ($enderecoBipado === 'N') {
    $observacao .= '| Endereco digitado ';
}
if ($enderecoTempBipado === 'N') {
    $observacao .= '| Endereco temporario digitado ';
}
var_dump($observacao);
$tsql = "exec AD_STP_INSEREPRODUTO_PROCESSOESTOQUECD5 $nunotaorig, '$quantidade', '$produto', '$endereco', '$checkvariosprodutos',$usuconf, '$observacao'";
$stmt = sqlsrv_query($conn, $tsql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
$retorno = $row[0];
echo $retorno;
