<?php
include "../conexaophp.php";
session_start(); //Iniciando a sessão


	//echo "teste2";

	$nunota = $_POST["nunota"];
	$referencia = $_POST["referencia"];


	$tsql = "SELECT * FROM [sankhya].[AD_FNT_PROXIMO_PRODUTO_PORREFERENCIA_REABASTECIMENTO] ($nunota, '$referencia')";

	$stmt = sqlsrv_query( $conn, $tsql);

	if ($stmt){
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
			{ 
				echo $row["REFERENCIA"].'/'.$row["CODLOCALORIG"].'/'.$row["QTDNEG"].'/'.$row["AGRUPMIN"].'/'.$row["QTDLOCAL"].'/'.$row["AD_QTDMAXLOCAL"].'/'.$row["ESTOQUE"].'/'.$row["MEDIA"].'/'.$row["SEQUENCIA"];
			}  
		
		
	}
?> 