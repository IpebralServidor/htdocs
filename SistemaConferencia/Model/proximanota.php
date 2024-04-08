<?php

session_start();
include "../../conexaophp.php";

$usuario = $_POST['usuario'];
// Verificar se os dados foram enviados corretamente
if (isset($_POST['usuario'])) {
    $tsqlPegarProximaNota = "exec AD_STP_PEGAR_PROXIMA_NOTA_CONFERENCIA $usuario";
    $stmtPegarProximaNota = sqlsrv_query($conn, $tsqlPegarProximaNota);
    $rowPegarProximaNota = sqlsrv_fetch_array($stmtPegarProximaNota, SQLSRV_FETCH_NUMERIC);

    echo $rowPegarProximaNota[0];
}
