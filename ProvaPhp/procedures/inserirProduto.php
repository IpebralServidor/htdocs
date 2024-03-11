<?php
include "../conexaophp.php";

$produto = $_POST["produto"];
$quantidade = $_POST["quantidade"];

$tsql = "EXEC [INSERIR_PRODUTO] '$produto', $quantidade";
$stmt = sqlsrv_query($conn2, $tsql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo $row[0];

?>