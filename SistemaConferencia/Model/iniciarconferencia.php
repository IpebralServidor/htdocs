<?php

session_start();
include "../../conexaophp.php";

if (isset($_POST['nota'])) {
    $nunota = $_POST['nota'];
    $usuario = $_SESSION['idUsuario'];
    try {
        $tsqlGera1780 = "EXEC AD_STP_GERA1780_CONFERENCIA $nunota, $usuario";
        $stmGera1780 = sqlsrv_query($conn, $tsqlGera1780);
        $msgGERA1780 = sqlsrv_fetch_array($stmGera1780, SQLSRV_FETCH_NUMERIC);
        echo $msgGERA1780[0];
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
