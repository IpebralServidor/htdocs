<?php
include "../../conexaophp.php";
session_start();

$nunotaorig = $_POST["nunota"];
$endereco = $_POST['endereco'];
$usuconf = $_SESSION["idUsuario"];

$produtoBipado = $_POST["produtoBipado"];
$enderecoBipado = $_POST["enderecoBipado"];
$enderecoTempBipado = $_POST["enderecoTempBipado"];
$observacao = '';
if ($produtoBipado === 'N') {
    $observacao .= '| Produto digitado ';
}
if ($enderecoBipado === 'N') {
    $observacao .= '| Endereco digitado ';
}
if ($enderecoTempBipado === 'N') {
    $observacao .= '| Endereco temporario digitado';
}
var_dump($observacao);
$tsql = "exec AD_STP_INSEREPRODUTO_TEMP_ITE_PROCESSOESTOQUECD5 $nunotaorig, $endereco, $usuconf, '$observacao'";

$stmt = sqlsrv_query($conn, $tsql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

$retorno = $row[0];
echo $retorno;
