<?php

include "../conexaophp.php";

$nunota = $_POST['nunota'];
$referencia = $_POST["referencia"];
$qtdneg = $_POST["qtdneg"];
$endereco = $_POST["endereco"];
$lote = $_POST["lote"];

$tsql = "EXEC AD_STP_PROXIMO_PRODUTO_TRANSFERENCIA $nunota, '$referencia',$endereco, $qtdneg, '$lote'";
$stmt = sqlsrv_query( $conn, $tsql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo $row[0];
?>