<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$tipoNota = $_POST["tipoNota"];

$params = array($tipoNota);

$tsqlProdutos = "SELECT 
                    TGFCAB.NUNOTA, 
                    TGFCAB.CODTIPOPER, 
                    TGFCAB.CODEMP,
                    (SELECT COUNT(1) FROM TGFITE WHERE TGFITE.NUNOTA = TGFCAB.NUNOTA AND SEQUENCIA > 0) AS QTD_ITENS,
                    TGFCAB.DTNEG,
                    CASE TGFCAB.AD_PEDIDOECOMMERCE 
                        WHEN 'TRANSFAPP' THEN 'Abastecimento' 
                        WHEN 'TRANSF_NOTA' THEN 'Transferência avulsa'
                        WHEN 'TRANSF_CD5' THEN 'Entrada CD5'
                        WHEN 'TRANSFPROD_ENTRADA' THEN 'Entrada da produção'
                        WHEN 'TRANSFPROD_SAIDA' THEN 'Saída da produção'
                    END AS TIPOTRANSF,
                    TSIUSU.NOMEUSU
                    FROM TGFCAB 
                    INNER JOIN TSIUSU 
                    ON TGFCAB.CODUSU = TSIUSU.CODUSU
                    WHERE TGFCAB.AD_PEDIDOECOMMERCE IN ('TRANSFAPP', 'TRANSF_NOTA', 'TRANSF_CD5', 'TRANSFPROD_ENTRADA', 'TRANSFPROD_SAIDA')
                    AND TGFCAB.DTNEG BETWEEN DATEADD(DAY, -30, GETDATE()) AND GETDATE()
                    AND TGFCAB.STATUSNOTA = 'A'
                    AND TGFCAB.AD_GARANTIAVERIFICADA = ?
                    ORDER BY TGFCAB.DTNEG";

$stmtProdutos = sqlsrv_query($conn, $tsqlProdutos, $params);

while ($rowProdutos = sqlsrv_fetch_array($stmtProdutos, SQLSRV_FETCH_ASSOC)) {
    echo '<tr onclick="atribuirDataBotao(this)" data-id="' . $rowProdutos['NUNOTA'] . '">';
    echo '<td>' . $rowProdutos['NOMEUSU'] . '</td>';
    echo '<td>' . $rowProdutos['NUNOTA'] . '</td>';
    echo '<td>' . $rowProdutos['CODTIPOPER'] . '</td>';
    echo '<td>' . $rowProdutos['TIPOTRANSF'] . '</td>';
    echo '<td>' . $rowProdutos['QTD_ITENS'] . '</td>';
    echo '<td>' . date_format($rowProdutos['DTNEG'], "d/m/Y") . '</td>';
    echo '</tr>';
}
