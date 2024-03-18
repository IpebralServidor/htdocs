<?php

include "../conexaophp.php";
require_once '../App/auth.php';

$tsql = "SELECT 
            TGFCAB.NUNOTA, 
            CODTIPOPER, 
            (SELECT COUNT(1) FROM TGFITE WHERE TGFITE.NUNOTA = TGFCAB.NUNOTA AND SEQUENCIA > 0) AS QTD_ITENS,
            DTNEG,
            CODUSU
        FROM TGFCAB 
        WHERE CODTIPOPER = 1300 
          AND AD_PEDIDOECOMMERCE = 'TRANSF_NOTA'
          AND STATUSNOTA = 'A'
        ORDER BY DTNEG DESC";
$stmt = sqlsrv_query( $conn, $tsql);

while( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
    echo '<tr onClick="abrirNota(this)" id="nunotaLinha" data-id="'.$row['NUNOTA'] .'">';
    echo '<td>'.$row['NUNOTA'] .'</td>';
    echo '<td>'.$row['CODTIPOPER'] .'</td>';
    echo '<td>'.$row['QTD_ITENS'] .'</td>';
    echo '<td>'.date_format($row['DTNEG'], "d/m/Y") .'</td>';
    echo '<td>'.$row['CODUSU'] .'</td>';
    echo '</tr>';
}

?>