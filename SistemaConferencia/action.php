<?php

include "../conexaophp.php";


$nunota = $_POST['nunota'];
$codprod = $_POST['codprod'];
$controle = $_POST['controle'];

 // echo $nunota."\n";
 // echo $codprod;

    $tsql2 = "  DELETE
                from TGFIVC 
                where codprod = $codprod
                  and NUCONF = (select NUCONFATUAL FROM TGFCAB WHERE NUNOTA = $nunota)
                  AND CONTROLE = '$controle'
            "; 

    $stmt2 = sqlsrv_query( $conn, $tsql2); 

    $tsql3 = "  DELETE 
                FROM TGFCOI2 
                WHERE TGFCOI2.NUCONF = (select NUCONFATUAL FROM TGFCAB WHERE NUNOTA = $nunota)
                  AND codprod = $codprod
                  AND controle = '$controle'
            "; 
    $stmt3 = sqlsrv_query( $conn, $tsql3);


?>