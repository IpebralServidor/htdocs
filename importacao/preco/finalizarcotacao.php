<?php

session_start();
include "../../conexaophp.php";

//Recebe a varíavel de sessão
$nuorcamento = $_POST['nuorcamento'];

if (!$conn) {
    die("Conexão com o banco de dados falhou.");
}

if (isset($nuorcamento)) {

    // Finaliza a cotação que está posicionada
    $query = "EXEC AD_STP_FINALIZAR_IMPORTACAO_TELEMARKETING ?";

    $params = array($nuorcamento);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

} 

// Fechar conexão
sqlsrv_close($conn);
?>