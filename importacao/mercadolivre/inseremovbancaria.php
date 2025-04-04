<?php

session_start();
set_time_limit(600);

include "../../conexaophp.php";
require '../../vendor/autoload.php';


        $sql = "EXEC AD_STP_EFETUA_BAIXA_MARKETPLACE";

        $stmt = sqlsrv_query($conn, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $sql = "EXEC AD_STP_INSEREMOVIMENTACAOBANCARIA_MARKETPLACE";

        $stmt = sqlsrv_query($conn, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        
        echo "Movimentações inseridas com sucesso!";


?>
