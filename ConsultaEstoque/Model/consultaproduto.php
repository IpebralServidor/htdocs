<?php
include "../../conexaophp.php";

$codprod = $_POST['referencia'];
$params = array($codprod);

$tsql = "SELECT * FROM [sankhya].[AD_FNT_ConsultaProduto_ConsultaEstoque](?)";

$stmt = sqlsrv_query($conn, $tsql, $params);
$returnArray = '';

while ($row =  sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
    $returnArray .= "
            <tr id='$row[0]'>
                <td style='text-align: center;' onclick='chamaTelaConsulta($row[0])'>$row[1]</td>
                <td style='text-align: center;' onclick='chamaTelaConsulta($row[0])'>$row[2]</td>
            </tr>
    ";
}
echo $returnArray;
