<?php
include "../../conexaophp.php";
session_start(); //Iniciando a sessão


$nunota = $_POST["nunota"];
$referencia = $_POST["referencia"];
$codusu = $_POST["codusu"];

$tsql = "($nunota)";
if ($referencia == "N") {
	$tsql = "SELECT * FROM [sankhya].[AD_FNT_PROXIMO_PRODUTO_REABASTECIMENTO] ($nunota, $codusu)";
} else {
	$tsql = "SELECT * FROM [sankhya].[AD_FNT_PROXIMO_PRODUTO_POR_REFERENCIA_REABASTECIMENTO] ($nunota, '$referencia')";
}

$stmt = sqlsrv_query($conn, $tsql);

$returnValue = '';
if ($stmt) {
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$returnValue .= $row["REFERENCIA"] . '/'
			. $row["CODLOCALORIG"] . '/'
			. $row["QTDNEG"] . '/'
			. $row["AGRUPMIN"] . '/'
			. $row["QTDLOCAL"] . '/'
			. $row["AD_QTDMAXLOCAL"] . '/'
			. $row["ESTOQUE"] . '/'
			. $row["MEDIA"] . '/'
			. $row["SEQUENCIA"] . '/'
			. $row["OBSERVACAO"] . '/'
			. $row["CODPROD"] . '/'
			. $row["FORNECEDORES"] . '/'
			. $row["CODEMP"];
	}
	echo $returnValue;
}
