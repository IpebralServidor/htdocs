<?php

session_start();
include "../../conexaophp.php";

//Recebe as varíaveis de sessão para fazer o UPDATE no Banco de Dados.
$nuorcamento = $_SESSION['nuorcamento'];
$codparc = $_SESSION['codParc'];
$codUsuario = $_SESSION['idUsuario'];

if (!$conn) {
    die("Conexão com o banco de dados falhou.");
}

//Armazena as varíaveis que vieram da linha em que foi feita a alteração
$id = $_POST['id'];
$quantidade = $_POST['quantidade'];

if (isset($id) && isset($quantidade)) {

    // Procedure que atualiza a quantidade que é alterada no item.
    $query = "EXEC AD_STP_ATUALIZA_QUANTIDADE_IMPORTACAO_TELEMARKETING ?, ?, ?, ?, ?";
    
    $params = array($nuorcamento, $codUsuario, $codparc, $id, $quantidade);
    
    $stmt = sqlsrv_query($conn, $query, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
  
} 

// Fechar conexão
sqlsrv_close($conn);
?>