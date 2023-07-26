<?php

include "../conexaophp.php";


$nunota = $_POST['nunota'];
$codprod = $_POST['codprod'];

 // echo $nunota."\n";
 // echo $codprod;

    $tsql2 = "  DELETE
                from TGFIVC 
                where codprod = $codprod
                  and NUCONF = (select NUCONFATUAL FROM TGFCAB WHERE NUNOTA = $nunota)
            "; 

    $stmt2 = sqlsrv_query( $conn, $tsql2); 

    $tsql3 = "  DELETE 
                FROM TGFCOI2 
                WHERE TGFCOI2.NUCONF = (select NUCONFATUAL FROM TGFCAB WHERE NUNOTA = $nunota)
                  AND codprod = $codprod
            "; 
    $stmt3 = sqlsrv_query( $conn, $tsql3);

    //echo "Itens excluídos com sucesso!";


?>