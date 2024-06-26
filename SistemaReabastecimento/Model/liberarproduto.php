<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota = $_POST["nunota"];
$enderecoInit = $_POST["enderecoInit"];
$enderecoFim = $_POST["enderecoFim"];
$codusu = $_POST["codusu"];
$params = array($nunota, $codusu);

$tsqlProdLiberado = "SELECT SEQUENCIA FROM TGFITE WHERE NUNOTA = ? AND ATUALESTOQUE = 0 AND AD_DTBIP IS NULL AND AD_CODUSUBIP = ? AND SEQUENCIA > 0";
$stmtProdLiberado = sqlsrv_query($conn, $tsqlProdLiberado, $params);
$prodLiberado = sqlsrv_fetch_array($stmtProdLiberado, SQLSRV_FETCH_NUMERIC);

$params = array($nunota, $enderecoInit, $enderecoFim);
$tsqlProxProduto = "SELECT ISNULL([sankhya].[AD_FNT_PROXIMO_PRODUTO_SEM_USUARIO_REABASTECIMENTO] (?, ?, ?), 0)";
$stmtProxProduto = sqlsrv_query($conn, $tsqlProxProduto, $params);
$proxSequencia = sqlsrv_fetch_array($stmtProxProduto, SQLSRV_FETCH_NUMERIC);

$params = array($nunota, $prodLiberado[0]);
$tsqlLiberaProd = "UPDATE TGFITE SET AD_CODUSUBIP = NULL WHERE NUNOTA = ? AND ABS(SEQUENCIA) = ?";
sqlsrv_query($conn, $tsqlLiberaProd, $params);
if ($proxSequencia[0] != 0) {
    $params = array($codusu, $nunota, $proxSequencia[0]);
    $tsqlAtualizaProxProd = "UPDATE TGFITE SET AD_CODUSUBIP = ? WHERE NUNOTA = ? AND ABS(SEQUENCIA) = ?";
    sqlsrv_query($conn, $tsqlAtualizaProxProd, $params);
    echo 'ok';
} else {
    echo 'N';
}
