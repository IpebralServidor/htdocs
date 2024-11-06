<?php

require_once "../../conexaophp.php";
require_once '../../App/auth.php';
require_once '../Model/Index.php';
require_once '../Model/Inventario.php';

// Verifica se foi feita uma requisição GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verifica se o parâmetro 'route' foi enviado
    if (isset($_GET['route'])) {
        $route = $_GET['route'];

        switch ($route) {
            case 'buscaEnderecosInventario':
                if (isset($_GET['codemp']) && isset($_GET['endini']) && isset($_GET['endfim']) && isset($_GET['concluidos'])) {
                    $codemp = $_GET['codemp'];
                    $endini = $_GET['endini'];
                    $endfim = $_GET['endfim'];
                    $concluidos = $_GET['concluidos'];
                    buscaEnderecosInventario($conn, $codemp, $endini, $endfim, $concluidos);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'buscaInformacoesProduto':
                if (isset($_GET['codemp']) && isset($_GET['referencia']) && isset($_GET['codlocal'])) {
                    $codemp = $_GET['codemp'];
                    $referencia = $_GET['referencia'];
                    $codlocal = $_GET['codlocal'];
                    buscaInformacoesProduto($conn, $codemp, $referencia, $codlocal);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'mostraBloqueio':
                if (isset($_GET['codlocal']) && isset($_GET['codemp'])) {
                    $codlocal = $_GET['codlocal'];
                    $codemp = $_GET['codemp'];
                    mostraBloqueio($conn, $codlocal, $codemp);
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
            case 'buscaItensInventario':
                if (isset($_POST['codemp']) && isset($_POST['codlocal'])) {
                    $codemp = $_POST['codemp'];
                    $codlocal = $_POST['codlocal'];
                    buscaItensInventario($conn, $codemp, $codlocal, $idUsuario);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'contaProduto':
                if (isset($_POST['codemp']) && isset($_POST['codlocal']) && isset($_POST['referencia']) && isset($_POST['controle']) && isset($_POST['quantidade'])) {
                    $codemp = $_POST['codemp'];
                    $codlocal = $_POST['codlocal'];
                    $referencia = $_POST['referencia'];
                    $controle = $_POST['controle'];
                    $quantidade = $_POST['quantidade'];
                    contaProduto($conn, $codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'verificaRecontagem':
                if (isset($_POST['codemp']) && isset($_POST['codlocal']) && isset($_POST['referencia']) && isset($_POST['controle']) && isset($_POST['quantidade'])) {
                    $codemp = $_POST['codemp'];
                    $codlocal = $_POST['codlocal'];
                    $referencia = $_POST['referencia'];
                    $controle = $_POST['controle'];
                    $quantidade = $_POST['quantidade'];
                    verificaRecontagem($conn, $codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'finalizaInventario':
                if (isset($_POST['codemp']) && isset($_POST['codlocal'])) {
                    $codemp = $_POST['codemp'];
                    $codlocal = $_POST['codlocal'];
                    finalizaInventario($conn, $codemp, $codlocal, $idUsuario);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'transfereItem':
                if (isset($_POST['codemp']) && isset($_POST['codlocal']) && isset($_POST['referencia']) && isset($_POST['controle']) && isset($_POST['quantidade'])) {
                    $codemp = $_POST['codemp'];
                    $codlocal = $_POST['codlocal'];
                    $referencia = $_POST['referencia'];
                    $controle = $_POST['controle'];
                    $quantidade = $_POST['quantidade'];
                    transfereItem($conn, $codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario);
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
