<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota = $_POST['nunota'];

$tsql = "SELECT * FROM [sankhya].[AD_FNT_PRODUTO_INSERIDO_TRANSFERENCIA] ($nunota)";
$stmt = sqlsrv_query($conn, $tsql);

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo '<tr>';
    echo '<td>' . $row['REFERENCIA'] . '</td>';
    echo '<td>' . $row['CODLOCALORIG'] . '</td>';
    echo '<td>' . $row['CONTROLE'] . '</td>';
    echo '<td>' . $row['QTDNEG'] . '</td>';
    //    Edson pediu para retirar a função de excluir item no dia 26/03/2024
    //    echo '<td>
    //            <div data-toggle="modal" data-target="#modalDeleteProd" class="divDeleteProd" data-id="'.$row['SEQUENCIA'] .'" onclick="deletarProduto(this, '.$row['REFERENCIA'] .')">
    //                <i class="fa-solid fa-trash"></i>
    //            </div>
    //         </td>';
    //    echo '</tr>';
}
