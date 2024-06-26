<?php

include "../../conexaophp.php";

$nunota = $_POST['nunota'];
$codbarra = $_POST['codbarra'];
$controle = $_POST['controle'];
$params = array($nunota, $codbarra, $controle);


$tsql = "SELECT * FROM AD_FNT_VERIFICA_MULTIPLOS_LOCAIS_CONFERENCIA(?, ?, ?)";
$stmt = sqlsrv_query($conn, $tsql, $params);

$countItens = 0;
$produtosList = '';

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
    $countItens++;

    $produtosList .= "<tr>
                        <td><input type='radio' id='$row[1]' name='localproduto'></td>
                        <td>$row[0]</td>
                        <td>$row[1]</td>
                        <td>$row[2]</td>
                        <td>$row[3]</td>
                        <td>$row[4]</td>
                      </tr>";
}
if ($countItens > 1) {
    echo $produtosList;
} else {
    echo 'ok';
}
