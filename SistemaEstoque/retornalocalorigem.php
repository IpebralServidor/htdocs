<?php
include "../conexaophp.php";
session_start(); //Iniciando a sessão


	//echo "teste2";

	$valorBusca = $_POST["valor"];

	$tsql = "SELECT DISTINCT CODLOCALORIG FROM TGFITE WHERE NUNOTA = $valorBusca";

	$stmt = sqlsrv_query( $conn, $tsql);

	if ($stmt){
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC))
			{ $codlocal = $row[0];
			}  
		
		echo $codlocal;
	} else {
		echo "Divergente";
	}
?> 