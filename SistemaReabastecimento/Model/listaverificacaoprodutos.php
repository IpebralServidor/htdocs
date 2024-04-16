<?php

include "../../conexaophp.php";

$nunota = $_GET["nunota"];
$params = array($nunota);
$tsql = "SELECT * FROM [sankhya].[AD_FNT_PRODUTO_SEPARADO_REABASTECIMENTO] (?) ORDER BY CODLOCALORIG DESC, SEQUENCIA DESC";
$stmt = sqlsrv_query($conn, $tsql, $params);

$produtosList = "";
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
    $produtosList .=
        "<tr>
            <td>$row[4]</td>
            <td>$row[3]</td>
            <td>$row[5]</td>
            <td>
                <a class='botao-abastecer' data-id='$row[1]'>
                    <button class='btnPendencia' data-toggle='modal' data-target='#buscarUsuario'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-archive-fill' viewBox='0 0 16 16'>
                            <path d='M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15h9.286zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1zM.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8H.8z' />
                        </svg>
                    </button>
                </a>
            </td>
        </tr>";
}
echo $produtosList;
