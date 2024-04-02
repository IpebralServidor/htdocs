<?php

include "../../conexaophp.php";

$tsqlText = "SELECT CODUSU, NOMEUSU 
            FROM TSIUSU 
            WHERE CODUSU IN (SELECT ITEM FROM SANKHYA.AD_FN_SPLIT((SELECT TEXTO FROM TSIPAR WHERE CHAVE = 'UsuConferencia'), ','))
            ORDER BY NOMEUSU";

$stmtText = sqlsrv_query($conn, $tsqlText);

$usersList = "";
while ($row = sqlsrv_fetch_array($stmtText, SQLSRV_FETCH_NUMERIC)) {
    $usersList .=
        "<ul>
        <li class='conferentes'>
        <button style='width: 100%; height: 100%; background-color:rgba(144,  203,  44,  0); cursor: pointer; ' class='conferente-btn' data-user='$row[0]'>
        $row[1]
        </button>
    </li>
    </ul>";
}


echo $usersList;
