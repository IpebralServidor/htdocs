<?php
include "../../conexaophp.php";
session_start();

$codbarra = trim($_POST["codbarra"], " ");
$quantidade = str_replace(',', '.', $_POST["quantidade"]);
$controle = $_POST["controle"];
$nunota2 = $_POST["nunota"];
$codusu = $_SESSION['idUsuario'];
$endereco = $_POST["endereco"];

if ($quantidade == '') {
	$quantidade = 0;
}
if ($quantidade != "0") {
	$_SESSION['codbarraselecionado'] = $codbarra;

	$tsql6 = "exec AD_STP_INSEREITEM_CONFERENCIA $nunota2 , '$codbarra' , $quantidade , '$controle', $codusu, $endereco ";

	$stmt6 = sqlsrv_query($conn, $tsql6);
	$row3 = sqlsrv_fetch_array($stmt6, SQLSRV_FETCH_NUMERIC);
	echo $row3[0];
	if ($row3[0] === "") {
		echo "<script> window.location.href='../View/detalhesconferencia.php?nunota=$nunota2' </script>";
	}
}
