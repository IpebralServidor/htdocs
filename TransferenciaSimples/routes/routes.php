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
            case 'buscaLocalPadrao':
                if (isset($_GET['referencia']) && isset($_GET['codemp'])) {
                    $referencia = $_GET['referencia'];
                    $codemp = $_GET['codemp'];
                    buscaLocalPadrao($conn, $referencia, $codemp);
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
            case 'buscaQtdMax':
                if (isset($_GET['referencia']) && isset($_GET['codemp']) && isset($_GET['endchegada'])) {
                    $referencia = $_GET['referencia'];
                    $codemp = $_GET['codemp'];
                    $endchegada = $_GET['endchegada'];
                    buscaQtdMax($conn, $referencia, $codemp, $endchegada);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'validaParametros':
                if (isset($_GET['codemp']) && isset($_GET['referencia']) && isset($_GET['lote']) && isset($_GET['endsaida']) && isset($_GET['endchegada']) && isset($_GET['qtdneg']) && isset($_GET['qtdmax'])) {
                    $codemp = $_GET['codemp'];
                    $referencia = $_GET['referencia'];
                    $lote = $_GET['lote'];
                    $endsaida = $_GET['endsaida'];
                    $endchegada = $_GET['endchegada'];
                    $qtdneg = $_GET['qtdneg'];
                    $qtdmax = $_GET['qtdmax'];
                    validaParametros($conn, $codemp, $referencia, $lote, $endsaida, $endchegada, $qtdneg, $qtdmax);
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
                if (isset($_POST['codemp']) && isset($_POST['referencia']) && isset($_POST['lote']) && isset($_POST['endsaida']) && isset($_POST['endchegada']) && isset($_POST['qtdneg']) && isset($_POST['qtdmax']) && isset($_POST['referenciaBipado']) && isset($_POST['enderecoSaidaBipado']) && isset($_POST['enderecoChegadaBipado'])) {
                    $codemp = $_POST['codemp'];
                    $referencia = $_POST['referencia'];
                    $lote = $_POST['lote'];
                    $endsaida = $_POST['endsaida'];
                    $endchegada = $_POST['endchegada'];
                    $qtdneg = $_POST['qtdneg'];
                    $qtdmax = $_POST['qtdmax'];
                    $referenciaBipado = $_POST['referenciaBipado'];
                    $enderecoSaidaBipado = $_POST['enderecoSaidaBipado'];
                    $enderecoChegadaBipado = $_POST['enderecoChegadaBipado'];
                    transferirProduto($conn, $codemp, $referencia, $lote, $endsaida, $endchegada, $qtdneg, $qtdmax, $referenciaBipado, $enderecoSaidaBipado, $enderecoChegadaBipado, $idUsuario);
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
