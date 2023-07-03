<?php header('Content-Type: text/html; iso-8859-1');?>
<?php
include "../conexaophp.php";
session_start();

$codbarra = $_POST["codbarra"];
$quantidade = str_replace(',', '.', $_POST["quantidade"]) ;
$controle = $_POST["controle"];
$nunota2 = $_POST["nunota"];

$tsql6 = "EXEC [sankhya].[AD_STP_VALIDA_QTD_MAIOR_CONFERENCIA] $quantidade, $nunota2, '$codbarra'"; //INSERIR CONTROLE 
//var_dump($tsql6);
$stmt6 = sqlsrv_query( $conn, $tsql6);  
//$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC))  
$row3 = sqlsrv_fetch_array( $stmt6, SQLSRV_FETCH_NUMERIC);

$validacaoValorMaior = $row3[0];

if ($validacaoValorMaior > 0) {
	echo "true";
}