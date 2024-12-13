<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

$codusu = $_SESSION["idUsuario"];
$nunota = $_POST["nunota"];
$params = array($nunota,$codusu);




// A consulta SQL
$tsql = "exec [AD_STP_libera_produtos_zerados] ?,?";
  
// Executando a consulta
$stmt = sqlsrv_query($conn, $tsql, $params);

// Verificar se a consulta foi executada com sucesso
if ($stmt === false) {
    // Se houver um erro, vamos exibir a mensagem de erro
    echo "Erro na execução da consulta SQL.<br>";
    foreach (sqlsrv_errors() as $error) {
        echo "Código do erro: " . $error['code'] . "<br>";
        echo "Mensagem do erro: " . $error['message'] . "<br>";
    }
    exit; // Encerra a execução do script
}

// Se a consulta foi bem-sucedida, verificamos o número de linhas afetadas
$rows_affected = sqlsrv_rows_affected($stmt);

// Verifica se algum registro foi afetado
if ($rows_affected === false) {
    echo "Erro ao obter número de linhas afetadas.<br>";
    exit;
}

if ($rows_affected == 0) {
    echo "Nenhum produto para liberar.";
} else if ($rows_affected > 0) {
    echo "Produtos liberados.";
}
?>
