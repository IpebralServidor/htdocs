<?php

include "../../conexaophp.php";

$nunota = $_POST['nunota'];
$codbarra = $_POST['codbarra'];
$controle = $_POST['controle'];
$params = array($nunota, $codbarra, $controle);


$tsql = "SELECT PRO.REFERENCIA AS REFERENCIA, 
        ITE.CODLOCALORIG, 
        ITE.QTDNEG AS QTDNOTA,
        ISNULL(TEMP.QTDNEG, 0) AS QTDCONFERIDA,
        ITE.CONTROLE
        FROM TGFITE ITE INNER JOIN 
        TGFPRO PRO ON ITE.CODPROD = PRO.CODPROD LEFT JOIN
        AD_TEMP_PRODUTOS_CONFERENCIA_ENDERECO TEMP 
        ON TEMP.NUNOTA = ITE.NUNOTA
        AND TEMP.CODPROD = ITE.CODPROD
        AND TEMP.CONTROLE = ITE.CONTROLE
        AND ITE.CODLOCALORIG = TEMP.CODLOCAL
        WHERE ITE.NUNOTA = ?
        AND ITE.CODPROD = (SELECT CODPROD FROM TGFBAR WHERE CODBARRA = ?) 
        AND ITE.CONTROLE = ?
";
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
