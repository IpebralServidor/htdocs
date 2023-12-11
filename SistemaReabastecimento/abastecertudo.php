<?php

    include "../conexaophp.php";
    require_once '../App/auth.php';

    $nunota2 = $_POST["nunota"];
  
    $tsql = "EXEC AD_STP_ABASTECER_TUDO_REABASTECIMENTO $nunota2";
    $stmt = sqlsrv_query( $conn, $tsql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

    echo $row[0];

?>