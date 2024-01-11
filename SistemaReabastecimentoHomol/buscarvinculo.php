<?php

    include "../conexaophp.php";
    session_start();

    $request = $_POST["numeroNota"];

    $tsqlTipoper = "SELECT count(distinct AD_VINCULONF) FROM TGFITE WHERE NUNOTA = $request";
    $stmtTipoper = sqlsrv_query( $conn, $tsqlTipoper);
    $rowTipoper = sqlsrv_fetch_array( $stmtTipoper, SQLSRV_FETCH_NUMERIC);

    echo $rowTipoper[0];

?>