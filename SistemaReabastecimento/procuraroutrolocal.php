<?php

    include "../conexaophp.php";
    require_once '../App/auth.php';

    $selectedOption = $_POST['option'];
    $nunota2 = $_POST["nunota"];
    $sequencia = $_POST["sequencia"];
    $codprod = $_POST["codprod"];
    

    $tsqlOutroLocal = "EXEC AD_STP_PEGAR_OUTRO_LOCAL_REABASTECIMENTO $nunota2, $sequencia, '$selectedOption', $codprod";
    $stmtOutroLocal = sqlsrv_query($conn, $tsqlOutroLocal);
    $rowOutroLocal = sqlsrv_fetch_array($stmtOutroLocal, SQLSRV_FETCH_NUMERIC);

    echo $rowOutroLocal[0];


?>