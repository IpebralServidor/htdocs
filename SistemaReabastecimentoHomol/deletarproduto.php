<?php
    include "../conexaophp.php";
    require_once '../App/auth.php';

    $nunota2 = $_POST["nunota"];
    $sequencia = $_POST["sequencia"];

    $tsqlZerarEstoque = "EXEC [sankhya].[AD_STP_ZERAR_ATUALESTOQUE_REABASTECIMENTO] $nunota2, $sequencia";
    $stmtZerarEstoque = sqlsrv_query($conn, $tsqlZerarEstoque);
    $rowZerarEstoque = sqlsrv_fetch_array($stmtZerarEstoque, SQLSRV_FETCH_NUMERIC);

    echo $rowZerarEstoque[0];

?>