<?php

    include "../conexaophp.php";
    require_once '../App/auth.php';

    $tipoProduto = $_POST["tipoProduto"];

    if($tipoProduto == 'S'){
        $tsqlProdutos = "SELECT 
                            NUNOTA, 
                            CODTIPOPER, 
                            CODEMP,
                            (SELECT COUNT(1) FROM TGFITE WHERE TGFITE.NUNOTA = TGFCAB.NUNOTA AND SEQUENCIA > 0) AS QTD_ITENS,
                            DTNEG
                        FROM TGFCAB 
                        WHERE AD_PEDIDOECOMMERCE IN ('TRANSFAPP', 'TRANSF_NOTA', 'TRANSF_CD5')
                        AND DTNEG BETWEEN DATEADD(DAY, -5, GETDATE()) AND GETDATE()
                        AND STATUSNOTA = 'A'
                        AND AD_GARANTIAVERIFICADA = 'A' ";
        $stmtProdutos = sqlsrv_query( $conn, $tsqlProdutos);
   
    }else{
        $tsqlProdutos = "SELECT 
                            NUNOTA, 
                            CODTIPOPER, 
                            CODEMP,
                            (SELECT COUNT(1) FROM TGFITE WHERE TGFITE.NUNOTA = TGFCAB.NUNOTA AND SEQUENCIA > 0) AS QTD_ITENS,
                            DTNEG
                        FROM TGFCAB 
                        WHERE AD_PEDIDOECOMMERCE IN ('TRANSFAPP', 'TRANSF_NOTA', 'TRANSF_CD5')
                        AND DTNEG BETWEEN DATEADD(DAY, -5, GETDATE()) AND GETDATE()
                        AND STATUSNOTA = 'A'
                        AND AD_GARANTIAVERIFICADA = 'S' ";
        $stmtProdutos = sqlsrv_query( $conn, $tsqlProdutos);
    }

    

    while( $rowProdutos = sqlsrv_fetch_array($stmtProdutos, SQLSRV_FETCH_ASSOC))
    {
        echo '<tr onclick="atribuirDataBotao(this)" data-id="'.$rowProdutos['NUNOTA'] .'">';
        echo '<td>'.$rowProdutos['NUNOTA'] .'</td>';
        echo '<td>'.$rowProdutos['CODTIPOPER'] .'</td>';
        echo '<td>'.$rowProdutos['QTD_ITENS'] .'</td>';
        echo '<td>'.date_format($rowProdutos['DTNEG'], "d/m/Y") .'</td>';
        echo '</tr>';
    }

?>