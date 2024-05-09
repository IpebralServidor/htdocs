<?php

include "../conexaophp.php";
require_once '../App/auth.php';

$empresa = $_POST["empresa"];
$_SESSION['enderecoPad'] = $_POST["endereco"];
$endereco = $_POST["endereco"];
$codUsu = $_SESSION['idUsuario'];

$tsql = "EXEC AD_STP_CRIAR_NOTA_TRANSFERENCIA $empresa, $codUsu, $endereco";
$stmt = sqlsrv_query( $conn, $tsql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo $row[0];
?>