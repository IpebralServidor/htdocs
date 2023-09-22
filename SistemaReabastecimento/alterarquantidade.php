<?php
    include "../conexaophp.php";
    require_once '../App/auth.php';

    $nunota2 = $_POST["nunota"];
    $sequencia = $_POST["sequencia"];
    $quantidade = $_POST["quantidade"];

    $tsqlAlterarQtd = "EXEC [sankhya].[AD_STP_ALTERAR_QUANTIDADE_REABASTECIMENTO] $nunota2, $sequencia, $quantidade";
    $stmtAlterarQtd = sqlsrv_query($conn, $tsqlAlterarQtd);
    $rowAlterarQtd = sqlsrv_fetch_array($stmtAlterarQtd, SQLSRV_FETCH_NUMERIC);

    echo $rowAlterarQtd[0];

?>