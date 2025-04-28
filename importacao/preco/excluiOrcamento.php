<?php

    include "../../conexaophp.php";


    if (isset($_POST['nuorcamento'])) {
       
        $nuorcamento = $_POST['nuorcamento'];
        $query = "EXEC AD_STP_EXCLUI_COTACAO_COTACAO_TELEMARKETING ?";
        $params = array($nuorcamento);
        $stmt = sqlsrv_query($conn, $query, $params);

          
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
            $retorno = $row[0];
            echo utf8_encode($retorno);
        }
        

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

    }

?>