<?php
include "../../conexaophp.php";
$nuorcamento = $_POST["nuorcamento"];
$referenciaprod = $_POST["referenciaprod"];

$tsql = "SELECT COUNT(1) AS QTD
         FROM AD_FNT_RetornaDadosPromocao_CotacaoTelemarketing ($nuorcamento, '$referenciaprod')";

$stmt = sqlsrv_query($conn, $tsql); 
while ($row2 = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $qtd = $row2['QTD'];
}

if($qtd > 0){



?>

<!-- Tabela de Possíveos Itens, baseado na linha que foi clicada da referência -->
<table id="ItemDesconto">
    <tr>
        <th width="20%">Qtd. Até</th>
        <th width="20%">Preço</th>
        <th width="60%">Perc. Desconto</th>
    </tr>


    <?php						
        $tsql2 = "SELECT QTDATE,
                         PRECO,
                         PERCENTUALDESCONTO
                FROM AD_FNT_RetornaDadosPromocao_CotacaoTelemarketing ($nuorcamento, '$referenciaprod')";

        $stmt2 = sqlsrv_query($conn, $tsql2);
        while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    ?>
        <tr style="cursor: hand; cursor: pointer;" >
            <td width="20%"><?php echo $row2['QTDATE']; ?>&nbsp;</td>
            <td width="20%"><?php echo $row2['PRECO']; ?>&nbsp;</td>
            <td width="60%"><?php echo $row2['PERCENTUALDESCONTO']; ?>&nbsp;</td>
        </tr>
    <?php


    }

}
    ?>
</table> <!-- Parte da da promoção -->


