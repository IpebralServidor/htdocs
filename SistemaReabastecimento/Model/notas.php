<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$tipoNota = $_POST["tipoNota"];
$tipoTransf = $_POST["tipoTransf"];
$cdTransf = $_POST["cdTransf"];

$params = array($tipoTransf, $cdTransf, $tipoNota);

$tsqlProdutos = "DECLARE @TIPOTRANSF VARCHAR(50) = ?
                DECLARE @CD CHAR(1) = ?
                DECLARE @TIPONOTA VARCHAR(1) = ?
                SELECT 
                TGFCAB.NUNOTA, 
                TGFCAB.CODTIPOPER, 
                TGFCAB.CODEMP,
                CONCAT((SELECT COUNT(1) FROM TGFITE WHERE TGFITE.NUNOTA = TGFCAB.NUNOTA AND SEQUENCIA > 0 AND ATUALESTOQUE <> 0), ' / ', (SELECT COUNT(1) FROM TGFITE WHERE TGFITE.NUNOTA = TGFCAB.NUNOTA AND SEQUENCIA > 0)) AS QTD_ITENS,
                TGFCAB.DTNEG,
                CASE TGFCAB.AD_PEDIDOECOMMERCE 
                WHEN 'TRANSFAPP' THEN 'Abastecimento' 
                WHEN 'TRANSF_NOTA' THEN 'Transferência avulsa'
                WHEN 'TRANSF_CD5' THEN 'Entrada CD5'
                WHEN 'TRANSFPROD_ENTRADA' THEN 'Entrada da produção'
                WHEN 'TRANSFPROD_SAIDA' THEN 'Saída da produção'
                WHEN 'TRANSF_PENDENCIA' THEN CASE
                                                WHEN (SELECT TOP 1 CAB.CODPARCTRANSP
                                                    FROM AD_TGFABSTITE ABSTITE INNER JOIN
                                                        AD_TGFABSTCAB ABSTCAB ON ABSTITE.NUNOTA = ABSTCAB.NUNOTA INNER JOIN
                                                        TGFCAB CAB ON ABSTCAB.NUNOTA_CAB = CAB.NUNOTA
                                                    WHERE ABSTITE.NUNOTATRF = CASE
                                                                                    WHEN @TIPONOTA = 'S' THEN TGFCAB.NUNOTA
                                                                                    WHEN @TIPONOTA = 'A' THEN (SELECT NUNOTA FROM TGFCAB WHERE AD_VINCULONF = TGFCAB.NUNOTA)
                                                                                END) = 11506 THEN 'Pendência balcão'
                                                WHEN (SELECT TOP 1 CAB.AD_ENTREGA
                                                    FROM AD_TGFABSTITE ABSTITE INNER JOIN
                                                        AD_TGFABSTCAB ABSTCAB ON ABSTITE.NUNOTA = ABSTCAB.NUNOTA INNER JOIN
                                                        TGFCAB CAB ON ABSTCAB.NUNOTA_CAB = CAB.NUNOTA
                                                    WHERE ABSTITE.NUNOTATRF = CASE
                                                                                    WHEN @TIPONOTA = 'S' THEN TGFCAB.NUNOTA
                                                                                    WHEN @TIPONOTA = 'A' THEN (SELECT NUNOTA FROM TGFCAB WHERE AD_VINCULONF = TGFCAB.NUNOTA)
                                                                                END) = 'S' THEN 'Pendência rota'
                                                ELSE 'Pendência'
                                            END
                END AS TIPOTRANSF,
                TSIUSU.NOMEUSU
                FROM TGFCAB 
                INNER JOIN TSIUSU 
                ON TGFCAB.CODUSU = TSIUSU.CODUSU
                WHERE (@TIPOTRANSF = 'N' AND TGFCAB.AD_PEDIDOECOMMERCE IN ('TRANSFAPP', 'TRANSF_NOTA', 'TRANSF_CD5', 'TRANSFPROD_ENTRADA', 'TRANSFPROD_SAIDA', 'TRANSF_PENDENCIA')
                        OR TGFCAB.AD_PEDIDOECOMMERCE = @TIPOTRANSF)
                    AND TGFCAB.DTNEG BETWEEN DATEADD(DAY, -70, GETDATE()) AND GETDATE()
                    AND TGFCAB.STATUSNOTA = 'A'
                    AND TGFCAB.AD_GARANTIAVERIFICADA = @TIPONOTA
                    AND (
                        (@TIPONOTA = 'S'
                            AND (
                                @TIPOTRANSF IN ('TRANSF_PENDENCIA', 'TRANSFAPP')
                                AND (@CD = 'N'
                                    OR EXISTS (
                                        SELECT 1 
                                        FROM TGFITE 
                                        WHERE TGFITE.NUNOTA = TGFCAB.NUNOTA 
                                        AND (CODLOCALORIG LIKE @CD + '%' 
                                            OR (CODLOCALORIG = '2018888' AND @CD = '5')) 
                                        AND NOT (CODLOCALORIG = '2018888' AND @CD = '2')
                                        AND SEQUENCIA > 0
                                    )
                                ) OR @TIPOTRANSF NOT IN ('TRANSF_PENDENCIA', 'TRANSFAPP')
                            )
                        )OR @TIPONOTA = 'A')
                ORDER BY DTNEG";

$stmtProdutos = sqlsrv_query($conn, $tsqlProdutos, $params);

while ($rowProdutos = sqlsrv_fetch_array($stmtProdutos, SQLSRV_FETCH_ASSOC)) {
    $style = ($rowProdutos['TIPOTRANSF'] === 'Pendência balcão' || $rowProdutos['TIPOTRANSF'] === 'Pendência rota') ? 'background-color: #FF4D4D !important; color: white;' : '';

    echo '<tr onclick="atribuirDataBotao(this)" data-id="' . $rowProdutos['NUNOTA'] . '">';
    echo '<td style="' . $style . '">' . $rowProdutos['NOMEUSU'] . '</td>';
    echo '<td style="' . $style . '">' . $rowProdutos['NUNOTA'] . '</td>';
    echo '<td style="' . $style . '">' . $rowProdutos['CODTIPOPER'] . '</td>';
    echo '<td style="' . $style . '">' . $rowProdutos['TIPOTRANSF'] . '</td>';
    echo '<td style="' . $style . '">' . $rowProdutos['QTD_ITENS'] . '</td>';
    echo '<td style="' . $style . '">' . date_format($rowProdutos['DTNEG'], "d/m/Y") . '</td>';
    echo '</tr>';
}
