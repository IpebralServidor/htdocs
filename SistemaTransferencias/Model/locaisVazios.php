<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota = $_POST['nunota'];

$tsql = "SELECT CODEMP, CODLOCAL, QTD FROM AD_LOCAIS_VAZIOS WHERE CODEMP = (SELECT CODEMP FROM TGFCAB WHERE NUNOTA = $nunota) ORDER BY CODLOCAL";
$stmt = sqlsrv_query($conn, $tsql);

echo "<thead class='thead-dark'>";
echo "<tr>";
echo "<th scope='col'>Local</th>";
echo "<th scope='col'>Qtd.</th>";
echo "<th scope='col'></th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo '<tr>';
    echo '<td>' . $row['CODLOCAL'] . '</td>';
    echo '<td>' . $row['QTD'] . '</td>';
    echo "<td>
            <button class='btnPendencia' data-toggle='modal' data-target='#deletaLocalVazio' onclick='setRowData(" . $row['CODEMP'] . "," . $row['CODLOCAL'] . ")'>
                <i class='fa-solid fa-trash-can'></i>
            </button>
        </td>";
}
echo "</tbody";
