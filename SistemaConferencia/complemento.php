<?php

include "../conexaophp.php";
session_start();

$codparc = $_REQUEST["codparc"];
$nunota2 = $_REQUEST["nunota"];

$tsql = "EXEC [sankhya].[AD_STP_BUSCAR_COMPLEMENTO_CONFERENCIA] $nunota2, $codparc"; 
$stmt = sqlsrv_query( $conn, $tsql); 
$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);

if(empty($row[0])) {
    echo "";
}else{
    echo $row[0];
}


?>