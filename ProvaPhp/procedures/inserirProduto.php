<?php
include "../conexaophp.php";

//Utilize o método POST para preencher as variáveis abaixo conforme os "nomes" especificados no arquivo JavaScript
$produto =
$quantidade =

//Passe as variáveis 'produto' e 'quantidade' (nessa ordem) como parâmetros na execução abaixo
$tsql = "EXEC [INSERIR_PRODUTO] '', ";
$stmt = sqlsrv_query($conn2, $tsql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo $row[0];

?>