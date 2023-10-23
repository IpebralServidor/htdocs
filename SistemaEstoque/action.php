<?php
include "../conexaophp.php";
session_start(); //Iniciando a sessão

$nunotaorig = $_POST["nunota"]; 
$toporigem   = $_POST["codtipoper"];
// $_SESSION["toporigem"] = $toporigem;
$usuconf = $_SESSION["idUsuario"];



$tsql = " exec AD_STP_INICIAPROCESSOESTOQUECD5 $nunotaorig, '$toporigem', $usuconf";

//var_dump($tsql);

$stmt = sqlsrv_query($conn, $tsql);

if ($stmt){
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC))
		{$nunota = $row[0];
		}  

	$_SESSION["nunotaorig"] = $nunota;

	echo $nunota;
}



?>