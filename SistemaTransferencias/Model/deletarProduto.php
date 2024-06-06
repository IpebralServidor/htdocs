<?php

include "../../conexaophp.php";

$sequencia = $_POST["sequencia"];
$nunota = $_POST["nunota"];

$tsqlDeletProd = "EXEC [sankhya].[AD_STP_DELETAR_PRODUTO_TRANSFERENCIA] $sequencia, $nunota";
$stmtDeletProd = sqlsrv_query($conn, $tsqlDeletProd);
$rowDeletProd = sqlsrv_fetch_array($stmtDeletProd, SQLSRV_FETCH_NUMERIC);

echo utf8_encode($rowDeletProd[0]);
