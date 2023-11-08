<?php

    include "../conexaophp.php";
    require_once '../App/auth.php';

    $nunota = $_POST["nunota"];
    $tipoProduto = $_POST["tipoProduto"];
    $tipoNota = $_POST["tipoNota"];

    if($tipoProduto == 'S'){
        $tsqlProdutos = "   SELECT * 
                            FROM [sankhya].[AD_FNT_PRODUTO_NAO_SEPARADO_REABASTECIMENTO] ($nunota) 
                            ORDER BY CODLOCALORIG DESC, SEQUENCIA DESC";
            $stmtProdutos = sqlsrv_query( $conn, $tsqlProdutos);
    }else{
        $tsqlProdutos = "   SELECT * 
                            FROM [sankhya].[AD_FNT_PRODUTO_SEPARADO_REABASTECIMENTO] ($nunota) 
                            ORDER BY CODLOCALORIG DESC, SEQUENCIA DESC";
        $stmtProdutos = sqlsrv_query( $conn, $tsqlProdutos);
    }
    
    
    while( $rowProdutos = sqlsrv_fetch_array($stmtProdutos, SQLSRV_FETCH_ASSOC))
    {
        echo '<tr>';
        echo '<td>'.$rowProdutos['REFERENCIA'] .'</td>';
        echo '<td>'.$rowProdutos['CODLOCALPAD'] .'</td>';
        echo '<td>'.$rowProdutos['QTDNEG'] .'</td>';
        echo '</tr>';
    }

?>