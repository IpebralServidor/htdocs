<?php

    include "../conexaophp.php";
    require_once '../App/auth.php';

    $nunota = $_POST["nunota"];
    $sequencia = $_POST["sequencia"];

    $tsqlMovimentacoes = "SELECT * FROM [sankhya].[AD_FNT_MOVIMENTACOES_REABASTECIMENTO] ($nunota, $sequencia)";
    $stmtMovimentacoes = sqlsrv_query( $conn, $tsqlMovimentacoes);

    echo "<table class='movTable'>";
    echo "<tr>
            <th>Nota</th>
            <th>TOP</th>
            <th>Emp</th>
            <th>Data</th>
            <th>Qtd</th>
         </tr>";
    
    while( $rowMovimentacoes = sqlsrv_fetch_array($stmtMovimentacoes, SQLSRV_FETCH_ASSOC))
    { 
        echo "<tr>";
        echo "<td>" .$rowMovimentacoes['NUNOTA'] ."</td>"; 
        echo "<td>" .$rowMovimentacoes['CODTIPOPER'] ."</td>"; 
        echo "<td>" .$rowMovimentacoes['CODEMP'] ."</td>"; 
        echo "<td>" .date_format($rowMovimentacoes['DTNEG'], "d/m/Y") ."</td>"; 
        echo "<td>" .$rowMovimentacoes['QTDNEG'] ."</td>"; 
        echo "</tr>";
    }

    echo "</table>";

?>