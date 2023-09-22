<?php

    include "../conexaophp.php";
    require_once '../App/auth.php';

    $nunota2 = $_POST["nunota"];
    $sequencia = $_POST["sequencia"];

    $tsqlReabastecimento = "EXEC AD_STP_ABASTECER_GONDOLA_REABASTECIMENTO $nunota2, $sequencia";
    $stmtReabastecimento = sqlsrv_query( $conn, $tsqlReabastecimento);
    $rowReabastecimento = sqlsrv_fetch_array($stmtReabastecimento, SQLSRV_FETCH_NUMERIC);

    echo $rowReabastecimento[0];

?>