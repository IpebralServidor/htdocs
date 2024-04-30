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

$tsql = "EXEC [sankhya].[AD_STP_PROXIMO_PRODUTO_TRANSFERENCIA] $nunota, '$referencia',$qtdneg, $endereco, '$lote', $codUsu, $qtdMaxLocal";
$stmt = sqlsrv_query($conn, $tsql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo utf8_encode($row[0]);
