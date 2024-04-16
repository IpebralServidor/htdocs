<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota = $_POST["nunota"];
$sequencia = $_POST["sequencia"];
$params = array($nunota, $sequencia);

$tsqlObservacao = "SELECT OBSERVACAO FROM TGFITE WHERE NUNOTA = ? AND SEQUENCIA =  ?";
$stmtObservacao = sqlsrv_query($conn, $tsqlObservacao, $params);
$rowObservacao = sqlsrv_fetch_array($stmtObservacao, SQLSRV_FETCH_NUMERIC);

echo $rowObservacao[0];
