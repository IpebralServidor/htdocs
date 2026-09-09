<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota = $_POST["nunota"];
$tipoProduto = $_POST["tipoProduto"];
$tipoNota = $_SESSION['tipoNota'];

$params = array($nunota);
$tsqlCorrecaoLocalPadrao = "
    DECLARE @NUNOTA INT = ?,
            @CODPROD INT,
            @SEQUENCIA INT

    DECLARE ITENS CURSOR FAST_FORWARD READ_ONLY FOR 

    SELECT ITE.CODPROD, ITE.SEQUENCIA
    FROM TGFCAB CAB INNER JOIN
        TGFITE ITE ON CAB.NUNOTA = ITE.NUNOTA INNER JOIN
        TGFPEM PEM ON PEM.CODPROD = ITE.CODPROD
                AND PEM.CODEMP = 1
    WHERE CAB.AD_GARANTIAVERIFICADA = 'A' 
    AND (CAB.AD_PEDIDOECOMMERCE IN ('TRANSFPROD_SAIDA_GONDOLA', 'TRANSFAPP')
        OR (CAB.AD_PEDIDOECOMMERCE LIKE 'TRANSF_ABAST%' AND CAB.AD_PEDIDOECOMMERCE LIKE '%MAX'))
    AND ITE.CODLOCALORIG NOT IN (3990000, 5990000)
    AND ITE.SEQUENCIA < 0
    AND ITE.ATUALESTOQUE = 0
    AND PEM.CODLOCALPAD <> ITE.CODLOCALORIG
    AND CAB.NUNOTA = @NUNOTA

    OPEN ITENS

    FETCH NEXT FROM ITENS INTO @CODPROD, @SEQUENCIA

    WHILE @@FETCH_STATUS = 0
    BEGIN
        UPDATE ITE
        SET ITE.CODLOCALORIG = PEM.CODLOCALPAD
        FROM TGFITE ITE INNER JOIN
            TGFPEM PEM ON ITE.CODPROD = PEM.CODPROD
                    AND PEM.CODEMP = ITE.CODEMP
        WHERE ITE.NUNOTA = @NUNOTA
        AND ITE.CODPROD = @CODPROD
        AND ITE.SEQUENCIA = @SEQUENCIA

        FETCH NEXT FROM ITENS INTO @CODPROD, @SEQUENCIA
    END

    CLOSE ITENS
    DEALLOCATE ITENS
";
sqlsrv_query($conn, $tsqlCorrecaoLocalPadrao, $params);

if ($tipoProduto == 'S') {
    $tsqlProdutos = "   SELECT * 
                            FROM [sankhya].[AD_FNT_PRODUTO_NAO_SEPARADO_REABASTECIMENTO] ($nunota) 
                            ORDER BY SEQUENCIA DESC";
} else {
    $tsqlProdutos = "   SELECT * 
                            FROM [sankhya].[AD_FNT_PRODUTO_SEPARADO_REABASTECIMENTO] ($nunota) 
                            ORDER BY CODLOCALORIG DESC, SEQUENCIA DESC";
}
$stmtProdutos = sqlsrv_query($conn, $tsqlProdutos);

while ($rowProdutos = sqlsrv_fetch_array($stmtProdutos, SQLSRV_FETCH_ASSOC)) {
    echo '<tr>';
    echo '<td>' . $rowProdutos['SEQUENCIA'] . '</td>';
    echo '<td>' . $rowProdutos['REFERENCIA'] . '</td>';
    echo '<td>' . $rowProdutos['CODLOCALPAD'] . '</td>';
    echo '<td>' . $rowProdutos['QTDNEG'] . '</td>';

    if ($tipoNota == "S") {
        if ($tipoProduto == 'S') {
            echo '<td>
                  </td>
            ';
        } else {
            echo '<td>
                        <a class="botao-abastecer" id="botao-abastecer" data-id="' . $rowProdutos['SEQUENCIA'] . '" onClick="atribuirDataBotao(this)">
                            <button class="btnPendencia" style="border-radius: 10%;" data-bs-toggle="modal" data-bs-target="#buscarUsuario">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive-fill" viewBox="0 0 16 16">
                                    <path d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15h9.286zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1zM.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8H.8z"/>
                                </svg>
                            </button>
                        </a>
                    </td>';
        }
    }
}
