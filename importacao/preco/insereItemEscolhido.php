<?php

session_start();
include "../../conexaophp.php";

//Recebe as variáveis de sessão para inserir o produto na cotação correta.
$nuorcamento = $_SESSION['nuorcamento'];
$codparc = $_SESSION['codParc'];
$codUsuario = $_SESSION['idUsuario'];

if (!$conn) {
    die("Conexão com o banco de dados falhou.");
}

$id = $_POST['id'];
$codprod = $_POST['codprod'];

if (isset($id) && isset($codprod)) {

    // Insere o produto que foi pesquisado pelo consulta de produtos, no botão adicionar.
    $query = "EXEC AD_STP_INSERE_PRODUTO_TELEMARKETING ?, ?, ?, ?, ?";
    
    $params = array($nuorcamento, $codUsuario, $codparc ,$id, $codprod);
    
    $stmt = sqlsrv_query($conn, $query, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    
} 

// Fechar conexão
sqlsrv_close($conn);
?>