<?php
session_start();
include "../../conexaophp.php";

$nunota = $_POST['nunota'];
$referencia = $_POST["referencia"];
$qtdneg = $_POST["qtdneg"];
$endereco = $_POST["endereco"];
$lote = $_POST["lote"];
$codUsu = $_SESSION["idUsuario"];
$qtdMaxLocal = $_POST["qtdMaxLocal"];
$checkboxMarcada = $_POST["checkboxMarcada"];

$tsql = "EXEC [sankhya].[AD_STP_PROXIMO_PRODUTO_TRANSFERENCIA] $nunota, '$referencia',$qtdneg, $endereco, '$lote', $codUsu, $qtdMaxLocal, $checkboxMarcada";
$stmt = sqlsrv_query($conn, $tsql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo utf8_encode($row[0]);
