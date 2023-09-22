<?php
    include "../conexaophp.php";
    require_once '../App/auth.php';

    $nunota2 = $_POST["nunota"];


    //MUDAR NOME DAS CHAMADAS
    $tsqlAlterarQtd = "EXEC [sankhya].[AD_STP_CONFIRMAR_NOTA_REABASTECIMENTO] $nunota2";
    $stmtAlterarQtd = sqlsrv_query($conn, $tsqlAlterarQtd);
    $rowAlterarQtd = sqlsrv_fetch_array($stmtAlterarQtd, SQLSRV_FETCH_NUMERIC);

    echo $rowAlterarQtd[0];

?>