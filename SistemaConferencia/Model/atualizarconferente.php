<?php

session_start();
include "../../conexaophp.php";

if (isset($_POST['conferentes'])) {

    $conferentes = $_POST['conferentes'];

    $tsqlAtualizar = "UPDATE TSIPAR SET TEXTO = '$conferentes' WHERE CHAVE = 'UsuConferencia'";

    $stmtAtualizar = sqlsrv_query($conn, $tsqlAtualizar);
    $rowAtualizar = sqlsrv_fetch_array($stmtAtualizar, SQLSRV_FETCH_NUMERIC);
}
