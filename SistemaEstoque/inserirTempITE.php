<?php  
include "../conexaophp.php";
session_start();

$nunotaorig = $_POST["nunota"];
$endereco = $_POST['endereco'];
$usuconf = $_SESSION["idUsuario"];

$tsql = "exec AD_STP_INSEREPRODUTO_TEMP_ITE_PROCESSOESTOQUECD5 $nunotaorig, $endereco, $usuconf";

$stmt = sqlsrv_query( $conn, $tsql);

$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);

$retorno = $row[0];
echo $retorno;

// header('Location: insereestoque.php?nunota='.$nunotaorig);

?>