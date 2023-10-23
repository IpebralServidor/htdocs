<?php
include "../conexaophp.php";
session_start(); //Iniciando a sessão

$nunotaorig = $_POST["nunota"]; 
$cddestino   = $_POST["cddestino"];
// $_SESSION["toporigem"] = $toporigem;
$usuconf = $_POST["usuario"];



$tsql = " exec AD_STP_INICIAPROCESSOESTOQUECD5 $nunotaorig, $cddestino, $usuconf";

//var_dump($tsql);

$stmt = sqlsrv_query($conn, $tsql);

if ($stmt){
	while($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC)){
		$retorno = $row[0];
		echo $retorno;
	}  

	// $_SESSION["nunotaorig"] = $nunota;
}



?>