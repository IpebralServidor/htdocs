<?php
include "../../conexaophp.php";

$arrayItens = $_POST['arrayItens'];
$returnMsg = '';
foreach ($arrayItens as $item) {
    $params = array($item[0], $item[1], $item[2]);
    $tsql = "EXEC [sankhya].[AD_STP_INSERIR_PENDENCIA] ?, ?, ?";
    $stmt = sqlsrv_query($conn, $tsql, $params);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
    $returnMsg .= $row[0] . ' ';
}

echo $returnMsg;
