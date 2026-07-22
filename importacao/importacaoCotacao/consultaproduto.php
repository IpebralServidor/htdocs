<?php
include "../../conexaophp.php";
session_start();

$codprod = $_POST['referencia'];
$nuorcamento = $_SESSION['nuorcamento'];

$params = array($codprod,$nuorcamento);

$tsql = "SELECT * FROM [sankhya].[AD_FNT_ConsultaProduto_ConsultaEstoque_COTACAO](?,?)";

$stmt = sqlsrv_query($conn, $tsql, $params);
$returnArray = '';

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $returnArray .= "
        <tr id='{$row['CODPROD']}'>
        <td style='text-align: center;' data-popup='{$row['CODPROD']}' id-popup='{$row['REFERENCIA']}'>{$row['REFERENCIA']}</td>
        <td style='text-align: center;' data-popup='{$row['CODPROD']}' id-popup='{$row['REFERENCIA']}'>{$row['DESCRPROD']}</td>
        <td style='text-align: center;' data-popup='{$row['CODPROD']}' id-popup='{$row['REFERENCIA']}'>{$row['ESTOQUE']}</td>
            
        </tr>
    ";
}

echo $returnArray;
