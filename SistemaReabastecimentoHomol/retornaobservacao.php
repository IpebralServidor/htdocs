<?php
include "../conexaophp.php";
require_once '../App/auth.php';

$nunota = $_POST["nunota"];
$sequencia = $_POST["sequencia"];


$tsqlObservacao= "SELECT OBSERVACAO FROM TGFITE WHERE NUNOTA = $nunota AND SEQUENCIA =  $sequencia"; 
$stmtObservacao = sqlsrv_query( $conn, $tsqlObservacao);  
$rowObservacao = sqlsrv_fetch_array( $stmtObservacao, SQLSRV_FETCH_NUMERIC);

echo $rowObservacao[0];

?>