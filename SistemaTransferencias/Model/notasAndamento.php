<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$tsql = "SELECT 
            TGFCAB.NUNOTA, 
            DTNEG,
            CONCAT(USU.CODUSU, ' - ', NOMEUSU) AS USU,
            (SELECT COUNT(1) FROM TGFITE WHERE TGFITE.NUNOTA = TGFCAB.NUNOTA AND SEQUENCIA > 0) AS QTD_ITENS,
            TGFCAB.CODEMP
        FROM TGFCAB 
        INNER JOIN TSIUSU USU
        ON TGFCAB.CODUSU = USU.CODUSU
        WHERE CODTIPOPER = 1300 
          AND AD_PEDIDOECOMMERCE = 'TRANSF_NOTA_TRANSFERENCIAS'
          AND STATUSNOTA <> 'L'
        ORDER BY DTNEG DESC";
$stmt = sqlsrv_query($conn, $tsql);

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  echo '<tr onClick="abrirNota(this)" id="nunotaLinha" data-id="' . $row['NUNOTA'] . '">';
  echo '<td>' . $row['NUNOTA'] . '</td>';
  echo '<td>' . date_format($row['DTNEG'], "d/m/Y") . '</td>';
  echo '<td>' . $row['USU'] . '</td>';
  echo '<td>' . $row['QTD_ITENS'] . '</td>';
  echo '<td>' . $row['CODEMP'] . '</td>';
  echo '</tr>';
}
