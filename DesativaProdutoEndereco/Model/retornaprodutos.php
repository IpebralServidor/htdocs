<?php
include "../../conexaophp.php";

$codemp = $_POST['codemp'];
$params = array($codemp);


$tsql = "SELECT * FROM [sankhya].[AD_FNT_RetornaProdutos_DesativaProduto](?)";

$stmt = sqlsrv_query($conn, $tsql, $params);
$returnArray = '';

while ($row =  sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
    $returnArray .= "
            <tr id='$row[0]' onclick='openDesativaModal(this)'>
                <td id='$row[0]ref' style='text-align: center;' data-toggle='modal' data-target='#desativaModal'>$row[1]</td>
                <td style='text-align: center;' data-toggle='modal' data-target='#desativaModal'>$row[2]</td>
                <td id='$row[0]end' style='text-align: center;' data-toggle='modal' data-target='#desativaModal'>$row[3]</td>
            </tr>
    ";
}
echo $returnArray;
