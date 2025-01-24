<?php

session_start();
include "../../conexaophp.php";

//Pega as variáveis de sessão para atualizar o preço
$nuorcamento = $_SESSION['nuorcamento'];
$codparc = $_SESSION['codParc'];
$codUsuario = $_SESSION['idUsuario'];

if (!$conn) {
    die("Conexão com o banco de dados falhou.");
}

//Recebe os resultados que foram retornados pelo AJAX
$id = $_POST['id'];
$referencia = $_POST['referencia'];
$preco = $_POST['preco'];


if (isset($id) && isset($referencia)) {

    // Atualizar o preço na tabela principal
    $query = "EXEC AD_STP_ATUALIZA_DADOS_IMPORTACAO_TELEMARKETING ?, ?, ?, ?, ?, ?";
    $params = array($nuorcamento, $codUsuario, $codparc, $id, $referencia, $preco);
    $stmt = sqlsrv_query($conn, $query, $params);
       

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    
} 

// Fecha a conexão
sqlsrv_close($conn);
?>