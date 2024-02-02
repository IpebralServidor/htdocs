<?php
include "../conexaophp.php";
session_start();

$request = $_POST["numeroNota"];

    $codusu = $_SESSION["idUsuario"];

    $tsqlVinculo = "SELECT DISTINCT AD_VINCULONF FROM TGFITE WHERE NUNOTA = $request";
    $stmtVinculo = sqlsrv_query( $conn, $tsqlVinculo);
    $rowVinculo = sqlsrv_fetch_array( $stmtVinculo, SQLSRV_FETCH_NUMERIC);

    if(!isset($rowVinculo[0])){
        $tsqlPrepara = "EXEC [sankhya].[AD_STP_PREPARAR_NOTA_TRANSFERENCIA] $request";
        $stmtPrepara = sqlsrv_query( $conn, $tsqlPrepara);
    }
    
    $tsqlCheckin = "EXEC [sankhya].[AD_STP_CHECKIN_PHP] $codusu, $request";
    $stmtCheckin = sqlsrv_query( $conn, $tsqlCheckin);

    $tsqlTipoNota = "SELECT AD_GARANTIAVERIFICADA, AD_PEDIDOECOMMERCE FROM TGFCAB WHERE NUNOTA = ($request)";
    $stmtTipoNota = sqlsrv_query( $conn, $tsqlTipoNota);
    $rowTipoNota = sqlsrv_fetch_array( $stmtTipoNota, SQLSRV_FETCH_NUMERIC);  
    
    echo $rowTipoNota[0] ."/" .$rowTipoNota[1];
?>