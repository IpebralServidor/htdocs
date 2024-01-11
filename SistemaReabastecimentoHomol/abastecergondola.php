<?php

    include "../conexaophp.php";
    require_once '../App/auth.php';

    $nunota2 = $_POST["nunota"];
    $sequencia = $_POST["sequencia"];
    $codusu = $_POST["codusu"];

    $tsqlReabastecimento = "EXEC AD_STP_ABASTECER_GONDOLA_REABASTECIMENTO $nunota2, $sequencia, $codusu";
    $stmtReabastecimento = sqlsrv_query( $conn, $tsqlReabastecimento);
    $rowReabastecimento = sqlsrv_fetch_array($stmtReabastecimento, SQLSRV_FETCH_NUMERIC);

    echo $rowReabastecimento[0];

?>