<?php header('Content-Type: text/html; iso-8859-1');?>
<?php
include "../conexaophp.php";
session_start();

$codbarra = $_POST["codbarra"];
$quantidade = str_replace(',', '.', $_POST["quantidade"]) ;
$controle = $_POST["controle"];
$nunota2 = $_POST["nunota"];

// echo $codbarra;
// echo " / ".$quantidade;
// echo " / ".$controle;

if($quantidade == ''){
	$quantidade = 0;
}



if($quantidade != "0"){
	$_SESSION['codbarraselecionado'] = $codbarra;

	$tsql6 = "exec AD_STP_INSEREITEM_CONFERENCIA $nunota2 , '$codbarra' , $quantidade , '$controle' "; 
	$stmt6 = sqlsrv_query( $conn, $tsql6);  
	//$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC))  
	$row3 = sqlsrv_fetch_array( $stmt6, SQLSRV_FETCH_NUMERIC);
	//echo $stmt6;
	echo $row3[0];
	//var_dump($tsql6);
	//$codbarra = 0;
	//$codbarra2 = 0;
	if($row3[0] === ""){
	//header('Location: detalhesconferencia.php?nunota=$nunota2');
		echo "<script> window.location.href='detalhesconferencia.php?nunota=$nunota2' </script>";
	}
}

?>