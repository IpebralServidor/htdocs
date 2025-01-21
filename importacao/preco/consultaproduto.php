<?php
include "../../conexaophp.php";

//Retorna os parâmetros que vieram do botão de incluir item.
$codprod = $_POST['referencia'];
$params = array($codprod);

//Pesquisa na função para ver quais produtos encontra baseado na referência digitada.
$tsql = "SELECT * FROM [sankhya].[AD_FNT_ConsultaProduto_ConsultaEstoque](?)";

$stmt = sqlsrv_query($conn, $tsql, $params);
$returnArray = '';

while ($row =  sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
    $returnArray .= "
            <tr id='$row[0]'>
                <td style='text-align: center;' data-popup='$row[0]' id-popup='$row[1]'>$row[1]</td>
                <td style='text-align: center;' data-popup='$row[0]' id-popup='$row[1]'>$row[2]</td>
            </tr>
    ";
}

echo $returnArray;
