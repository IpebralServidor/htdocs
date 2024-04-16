<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota = $_POST["nunota"];
$sequencia = $_POST["sequencia"];

$tsqlMovimentacoes = "SELECT * FROM [sankhya].[AD_FNT_MOVIMENTACOES_REABASTECIMENTO] ($nunota, $sequencia)";
$stmtMovimentacoes = sqlsrv_query($conn, $tsqlMovimentacoes);

while ($rowMovimentacoes = sqlsrv_fetch_array($stmtMovimentacoes, SQLSRV_FETCH_ASSOC)) {
    echo
    '<tr>' .
        '<th>' . $rowMovimentacoes['NUNOTA'] . '</th>' .
        '<th>' . $rowMovimentacoes['CODTIPOPER'] . '</th>' .
        '<th>' . $rowMovimentacoes['CODEMP'] . '</th>' .
        '<th>' . date_format($rowMovimentacoes['DTNEG'], "d/m/Y")  . '</th>' .
        '<th>' . $rowMovimentacoes['QTDNEG'] . '</th>' .
        '</tr>';
}
