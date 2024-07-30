<?php

require_once "../../conexaophp.php";
require_once '../../App/auth.php';
require_once '../Model/TransfSimples.php';

// Verifica se foi feita uma requisição GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verifica se o parâmetro 'route' foi enviado
    if (isset($_GET['route'])) {
        $route = $_GET['route'];

        switch ($route) {
            case 'buscaInformacoesProduto':
                if (isset($_GET['referencia'])) {
                    $referencia = $_GET['referencia'];
                    buscaInformacoesProduto($conn, $referencia);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'buscaInformacoesLocal':
                if (isset($_GET['referencia']) && isset($_GET['codemp']) && isset($_GET['endsaida']) && isset($_GET['lote'])) {
                    $codemp = $_GET['codemp'];
                    $referencia = $_GET['referencia'];
                    $endsaida = $_GET['endsaida'];
                    $lote = $_GET['lote'];
                    buscaInformacoesLocal($conn, $codemp, $referencia, $endsaida, $lote);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'validaParametros':
                if (isset($_GET['codemp']) && isset($_GET['referencia']) && isset($_GET['lote']) && isset($_GET['endsaida']) && isset($_GET['endchegada']) && isset($_GET['qtdmax'])) {
                    $codemp = $_GET['codemp'];
                    $referencia = $_GET['referencia'];
                    $lote = $_GET['lote'];
                    $endsaida = $_GET['endsaida'];
                    $endchegada = $_GET['endchegada'];
                    $qtdmax = $_GET['qtdmax'];
                    validaParametros($conn, $codemp, $referencia, $lote, $endsaida, $endchegada, $qtdmax);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            default:
                echo json_encode(['error' => 'Rota não reconhecida']);
                break;
        }
    } else {
        echo json_encode(['error' => 'Parâmetro route não foi enviado']);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verifica se foi feita uma requisição POST
    // Verifica se o parâmetro 'route' foi enviado
    if (isset($_POST['route'])) {
        $route = $_POST['route'];

        switch ($route) {
            case 'transferirProduto':
                if (isset($_POST['codemp']) && isset($_POST['referencia']) && isset($_POST['lote']) && isset($_POST['endsaida']) && isset($_POST['endchegada']) && isset($_POST['qtdmax'])) {
                    $codemp = $_POST['codemp'];
                    $referencia = $_POST['referencia'];
                    $lote = $_POST['lote'];
                    $endsaida = $_POST['endsaida'];
                    $endchegada = $_POST['endchegada'];
                    $qtdmax = $_POST['qtdmax'];
                    transferirProduto($conn, $codemp, $referencia, $lote, $endsaida, $endchegada, $qtdmax, $idUsuario);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            default:
                echo json_encode(['error' => 'Rota não reconhecida']);
                break;
        }
    } else {
        echo json_encode(['error' => 'Parâmetro route não foi enviado']);
    }
} else {
    echo json_encode(['error' => 'Método de requisição não suportado']);
}
