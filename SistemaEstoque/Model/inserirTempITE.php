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

$tsql = "exec AD_STP_INSEREPRODUTO_TEMP_ITE_PROCESSOESTOQUECD5 $nunotaorig, $endereco, $usuconf, '$observacao'";

$stmt = sqlsrv_query($conn, $tsql);

if ($stmt === false) {
    exibirErroFormatado(); // ← aqui você chama a função
    exit; // opcional, para parar o script
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

function exibirErroFormatado() {
    if ($errors = sqlsrv_errors()) {
        echo "<div style='color: red;'><strong>Erro SQL:</strong><br>";
        foreach ($errors as $e) {
            echo "Código: {$e['code']}<br>";
            echo "Mensagem: {$e['message']}<br><br>";
        }
        echo "</div>";
    }
}


$retorno = $row[0];
echo $retorno;