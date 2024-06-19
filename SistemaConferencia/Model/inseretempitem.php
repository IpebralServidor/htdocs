<?php

include "../../conexaophp.php";

$nunota = $_POST['nunota'];
$codbarra = $_POST['codbarra'];
$controle = $_POST['controle'];
$endereco = $_POST['endereco'];
$qtdneg = $_POST['qtdneg'];
$params = array($nunota, $codbarra, $controle, $endereco, $qtdneg);

$tsql = "EXEC AD_STP_INSERE_TEMP_PRODUTOS_CONFERENCIA_ENDERECO ?, ?, ?, ?, ?";
$stmt = sqlsrv_query($conn, $tsql, $params);

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo $row[0];
