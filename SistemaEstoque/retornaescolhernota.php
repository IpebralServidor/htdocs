<?php  
include "../conexaophp.php";
session_start();

$nunotaorig = $_POST['nunota'];


$tsql2 = " SELECT * FROM sankhya.AD_FNT_EscolherNota_SistemaEstoque($nunotaorig) "; 

$stmt2 = sqlsrv_query( $conn, $tsql2);  

// if ($stmt2){
	while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC)) 
	{ 
		$notabtn = $row2[0];
		$nota = $row2[1];
		echo "<button class='btn btn-primary btn-form' onclick='abrirNota($nota)'> $notabtn </button><br><br>";
		
	}
// } else {
// 	echo "<script> fecharEscolherNota(); window.alert('Digite uma nota válida!');</script>";
// }
?>