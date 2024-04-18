<?php
include "../../conexaophp.php";

session_start();

$nunota = $_POST["nunota"];
$sequencia = $_POST["sequencia"];
$tipo = $_POST["tipo"];
$usuconf = $_SESSION["idUsuario"];

$tsql2 = "EXEC AD_STP_DELETARPRODUTO_SISTEMAESTOQUE $nunota, $sequencia, $tipo, $usuconf";

$stmt2 = sqlsrv_query($conn, $tsql2);

$row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC);
$retorno = $row[0];
echo $retorno;
