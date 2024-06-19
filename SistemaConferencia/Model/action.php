<?php

include "../../conexaophp.php";

$nunota = $_POST['nunota'];
$codprod = $_POST['codprod'];
$controle = $_POST['controle'];

$tsql2 = "  DELETE
                from TGFIVC 
                where codprod = $codprod
                  and NUCONF = (select NUCONFATUAL FROM TGFCAB WHERE NUNOTA = $nunota)
                  AND CONTROLE = '$controle'
            ";

$stmt2 = sqlsrv_query($conn, $tsql2);

$tsql3 = "  DELETE 
                FROM TGFCOI2 
                WHERE TGFCOI2.NUCONF = (select NUCONFATUAL FROM TGFCAB WHERE NUNOTA = $nunota)
                  AND codprod = $codprod
                  AND controle = '$controle'
            ";
$stmt3 = sqlsrv_query($conn, $tsql3);

$tsql4 = "  DELETE 
                FROM AD_TEMP_PRODUTOS_CONFERENCIA_ENDERECO 
                WHERE NUNOTA = $nunota
                  AND CODPROD = $codprod
                  AND CONTROLE = '$controle'
            ";
$stmt4 = sqlsrv_query($conn, $tsql4);
