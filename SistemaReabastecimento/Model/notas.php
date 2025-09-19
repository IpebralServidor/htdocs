<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$tipoNota = $_POST["tipoNota"];
$tipoTransf = $_POST["tipoTransf"];
$cdTransf = $_POST["cdTransf"];
$referencia = $_POST["referencia"];

$params = array($referencia, $tipoTransf, $cdTransf, $tipoNota);

$tsqlProdutos = "
select * from [AD_FNT_LISTA_TRANSF_SANKHYA_APP](?,?,?,?) ORDER BY NUNOTA";

$stmtProdutos = sqlsrv_query($conn, $tsqlProdutos, $params);

while ($rowProdutos = sqlsrv_fetch_array($stmtProdutos, SQLSRV_FETCH_ASSOC)) {
    $style = ($rowProdutos['TIPOTRANSF'] === 'Pendência balcão' || $rowProdutos['TIPOTRANSF'] === 'Pendência rota') ? 'background-color: #FF4D4D !important; color: white;' : '';

    echo '<tr onclick="atribuirDataBotao(this)" data-id="' . $rowProdutos['NUNOTA'] . '">';
    echo '<td style="' . $style . '">' . $rowProdutos['NOMEUSU'] . '</td>';
    echo '<td style="' . $style . '">' . $rowProdutos['NUNOTA'] . '</td>';
    echo '<td style="' . $style . '">' . $rowProdutos['CODEMP'] . '</td>';
    echo '<td style="' . $style . '">' . $rowProdutos['CODTIPOPER'] . '</td>';
    echo '<td style="' . $style . '">' . $rowProdutos['TIPOTRANSF'] . '</td>';
    echo '<td style="' . $style . '">' . $rowProdutos['QTD_ITENS'] . '</td>';
    echo '<td style="' . $style . '">' . date_format($rowProdutos['DTNEG'], "d/m/Y") . '</td>';
    echo '</tr>';
}
