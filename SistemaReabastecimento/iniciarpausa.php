<?php

session_start();
include "../conexaophp.php";

$status = $_POST['status'];
$nota = $_POST['nota'];
$usuconf = $_SESSION["idUsuario"];

if($status == "A"){
    $tsqlMudaStatus = "EXEC [sankhya].[AD_STP_INICIAPAUSA_PHP] $usuconf , $nota "; 
	
}else if($status == "P"){
    $tsqlMudaStatus = "EXEC [sankhya].[AD_STP_FINALIZAPAUSA_PHP] $usuconf , $nota ";
}

$stmtMudaStatus = sqlsrv_query( $conn, $tsqlMudaStatus);  
$rowMudaStatus = sqlsrv_fetch_array( $stmtMudaStatus, SQLSRV_FETCH_NUMERIC);

echo $rowMudaStatus[0];

?>