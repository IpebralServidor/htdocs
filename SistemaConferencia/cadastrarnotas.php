<?php

session_start();
include "../conexaophp.php";

//echo $_POST['notas'];
// Verificar se os dados foram enviados corretamente
if (isset($_POST['notas'])) {
  
	$notas = $_POST['notas'];
	$usuario = $_POST['usuario'];

	$notasSeparadas = explode("/", $notas);


	for ($i = 0; $i < count($notasSeparadas); $i++) {
		$nunota = $notasSeparadas[$i];

		$tsql6 = "EXEC AD_STP_ATRIBUINOTA_CONFERENCIA $nunota, $usuario "; 
		$stmt6 = sqlsrv_query( $conn, $tsql6);  
		$row3 = sqlsrv_fetch_array( $stmt6, SQLSRV_FETCH_NUMERIC);
		
	}
	echo $row3[0];
}
?>