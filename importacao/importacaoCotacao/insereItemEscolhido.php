<?php

session_start();
include "../../conexaophp.php";

//Recebe as variáveis de sessão para inserir o produto na cotação correta.
$nuimportacao = $_SESSION['nuimportacao'];
$codparc = $_SESSION['codParc'];
$codUsuario = $_SESSION['idUsuario'];

if (!$conn) {
    die("Conexão com o banco de dados falhou.");
}

$id = $_POST['id'];
$codprod = $_POST['codprod'];

if (isset($id) && isset($codprod)) {

    // Insere o produto que foi pesquisado pelo consulta de produtos, no botão adicionar.
    $query = "EXEC AD_STP_INSERE_PRODUTO_IMPORTACAO_COTACAO ?, ?, ?";
    
    $params = array($nuimportacao, $id, $codprod);
    
    $stmt = sqlsrv_query($conn, $query, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

    echo utf8_encode($row[0]);
    
} 

// Fechar conexão
sqlsrv_close($conn);
?>