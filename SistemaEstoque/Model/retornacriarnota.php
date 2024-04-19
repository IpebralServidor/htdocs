<?php
include "../../conexaophp.php";
session_start();

$nunotaorig = $_POST['nunota'];
$usuario = $_SESSION["idUsuario"];

$tsql2 = "  SELECT * FROM sankhya.AD_FNT_Lista_CDS_SistemaEstoque($nunotaorig) ";

$stmt2 = sqlsrv_query($conn, $tsql2);

if ($stmt2) {
	while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC)) {
		$escolherCDbtn = $row2[0];
		$quartodigitotop = substr($escolherCDbtn, -1);
		echo "<button class='btn btn-primary btn-form' onclick='criaNota($nunotaorig, $quartodigitotop, $usuario);'> $escolherCDbtn </button><br><br>";
	}
} else {
	echo "<script> fecharCriarNotaTransf(); window.alert('Não há notas para serem criadas para esse número único!');</script>";
}
