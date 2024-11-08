<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota = $_POST["nunota"];
$tipoProduto = $_POST["tipoProduto"];
$tipoNota = $_SESSION['tipoNota'];

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
