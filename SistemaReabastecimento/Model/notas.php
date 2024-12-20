<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$tipoNota = $_POST["tipoNota"];
$tipoTransf = $_POST["tipoTransf"];
$cdTransf = $_POST["cdTransf"];
$referencia = $_POST["referencia"];

$params = array($referencia,$tipoTransf, $cdTransf, $tipoNota);

$tsqlProdutos = "
                DECLARE @REFERENCIA VARCHAR(50) = ?
                DECLARE @TIPOTRANSF VARCHAR(50) = ?
                DECLARE @CD CHAR(1) = ?
                DECLARE @TIPONOTA VARCHAR(1) = ?
                DECLARE @CODPROD INT = (SELECT DISTINCT PRO.CODPROD 
						FROM TGFPRO PRO INNER JOIN 
							 TGFBAR BAR ON PRO.CODPROD = BAR.CODPROD 
						WHERE PRO.REFERENCIA = @REFERENCIA
						   OR BAR.CODBARRA = @REFERENCIA)
                SELECT 
                TGFCAB.NUNOTA, 
                TGFCAB.CODTIPOPER, 
                TGFCAB.CODEMP,
                CONCAT((SELECT COUNT(1) FROM TGFITE WHERE TGFITE.NUNOTA = TGFCAB.NUNOTA AND SEQUENCIA > 0 AND ATUALESTOQUE <> 0), ' / ', (SELECT COUNT(1) FROM TGFITE WHERE TGFITE.NUNOTA = TGFCAB.NUNOTA AND SEQUENCIA > 0)) AS QTD_ITENS,
                TGFCAB.DTNEG,
                CASE 
                WHEN TGFCAB.AD_PEDIDOECOMMERCE = 'TRANSFAPP' THEN 'Abastecimento' 
                WHEN TGFCAB.AD_PEDIDOECOMMERCE = 'TRANSF_NOTA' THEN 'Transferência avulsa'
                WHEN TGFCAB.AD_PEDIDOECOMMERCE = 'TRANSF_CD5' THEN 'Entrada CD5'
                WHEN TGFCAB.AD_PEDIDOECOMMERCE = 'TRANSFPROD_ENTRADA' THEN 'Entrada da produção'
                WHEN TGFCAB.AD_PEDIDOECOMMERCE = 'TRANSFPROD_SAIDA' THEN 'Saída da produção'
                WHEN TGFCAB.AD_PEDIDOECOMMERCE LIKE 'TRANSF_ABAST_31%' THEN 'Abastecimento 3/1'
                WHEN TGFCAB.AD_PEDIDOECOMMERCE LIKE 'TRANSF_ABAST_101%' THEN 'Abastecimento 10/1'
                WHEN TGFCAB.AD_PEDIDOECOMMERCE LIKE 'TRANSF_ABAST_103%' THEN 'Abastecimento 10/3'
                WHEN TGFCAB.AD_PEDIDOECOMMERCE = 'TRANSF_ABAST_FILIAL_17' THEN 'Abastecimento 1/7'
                WHEN TGFCAB.AD_PEDIDOECOMMERCE = 'TRANSF_ABAST_FILIAL_36' THEN 'Abastecimento 3/6'
                WHEN TGFCAB.AD_PEDIDOECOMMERCE = 'TRANSF_ABAST_FILIAL_16' THEN 'Abastecimento 1/6'
                WHEN TGFCAB.AD_PEDIDOECOMMERCE = 'TRANSF_PENDENCIA' THEN CASE
                                                                            WHEN (SELECT TOP 1 CAB.CODPARCTRANSP
                                                                                FROM AD_TGFABSTITE ABSTITE INNER JOIN
                                                                                    AD_TGFABSTCAB ABSTCAB ON ABSTITE.NUNOTA = ABSTCAB.NUNOTA INNER JOIN
                                                                                    TGFCAB CAB ON ABSTCAB.NUNOTA_CAB = CAB.NUNOTA
                                                                                WHERE ABSTITE.NUNOTATRF = CASE
                                                                                                                WHEN @TIPONOTA = 'S' THEN TGFCAB.NUNOTA
                                                                                                                WHEN @TIPONOTA = 'A' THEN (SELECT NUNOTA FROM TGFCAB CAB WHERE CAB.AD_VINCULONF = TGFCAB.NUNOTA)
                                                                                                            END) = 11506 THEN 'Pendência balcão'
                                                                            WHEN (SELECT TOP 1 CAB.AD_ENTREGA
                                                                                FROM AD_TGFABSTITE ABSTITE INNER JOIN
                                                                                    AD_TGFABSTCAB ABSTCAB ON ABSTITE.NUNOTA = ABSTCAB.NUNOTA INNER JOIN
                                                                                    TGFCAB CAB ON ABSTCAB.NUNOTA_CAB = CAB.NUNOTA
                                                                                WHERE ABSTITE.NUNOTATRF = CASE
                                                                                                                WHEN @TIPONOTA = 'S' THEN TGFCAB.NUNOTA
                                                                                                                WHEN @TIPONOTA = 'A' THEN (SELECT NUNOTA FROM TGFCAB CAB WHERE CAB.AD_VINCULONF = TGFCAB.NUNOTA)
                                                                                                            END) = 'S' THEN 'Pendência rota'
                                                                            ELSE 'Pendência'
                                                                        END
                END AS TIPOTRANSF,
                TSIUSU.NOMEUSU
                FROM TGFCAB INNER JOIN 
                    TSIUSU ON TGFCAB.CODUSU = TSIUSU.CODUSU INNER JOIN
                    TGFITE ON TGFITE.NUNOTA = TGFCAB.NUNOTA
                WHERE (@TIPOTRANSF = 'N' AND (TGFCAB.AD_PEDIDOECOMMERCE IN ('TRANSFAPP', 'TRANSF_NOTA', 'TRANSF_CD5', 'TRANSFPROD_ENTRADA', 'TRANSFPROD_SAIDA', 'TRANSF_PENDENCIA') OR TGFCAB.AD_PEDIDOECOMMERCE LIKE 'TRANSF_ABAST%')
                        OR TGFCAB.AD_PEDIDOECOMMERCE LIKE @TIPOTRANSF+'%')
                    AND TGFCAB.DTNEG BETWEEN DATEADD(DAY, -30, GETDATE()) AND GETDATE()
                    AND TGFCAB.STATUSNOTA = 'A'
                    AND TGFCAB.AD_GARANTIAVERIFICADA = @TIPONOTA
                    AND ((TGFITE.CODPROD = @CODPROD AND TGFITE.QTDNEG <> 0 AND TGFITE.ATUALESTOQUE = 0) OR @CODPROD IS NULL)
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
                    GROUP BY TGFCAB.NUNOTA,TGFCAB.CODTIPOPER,TGFCAB.CODEMP,TGFCAB.DTNEG,TGFCAB.AD_PEDIDOECOMMERCE,TSIUSU.NOMEUSU";

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
