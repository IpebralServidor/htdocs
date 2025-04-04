<?php

require_once "../../conexaophp.php";
require_once '../../App/auth.php';
require_once '../Model/index.php';
require_once '../Model/Contagem.php';

$codusu = $_SESSION["idUsuario"];

// Verifica se foi feita uma requisição GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verifica se o parâmetro 'route' foi enviado
    if (isset($_GET['route'])) {
        $route = $_GET['route'];

        switch ($route) {
            case 'buscaNotasContagem':                                
                    buscaNotasContagem($conn);                
                break;
            case 'buscaInformacoesProduto':
                if (isset($_GET['nunota']) && isset($_GET['referencia']) && isset($_GET['tipo']) ) {
                    $nunota = $_GET['nunota'];
                    $referencia = $_GET['referencia'];
                    $tipo = $_GET['tipo'];
                    buscaInformacoesProduto($conn, $nunota, $referencia,$tipo);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'desabilitaFinalizaCont':
                if (isset($_GET['nunota']) ) {
                    $nunota = $_GET['nunota'];
                    desabilitaFinalizaCont($conn, $nunota);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'mostraContagens':
                if (isset($_GET['nucontite'])) {
                    $nucontite = $_GET['nucontite'];
                    mostraContagens($conn, $nucontite);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'autorizatrava':
                if (isset($_GET['user']) && isset($_GET['senha'])) {
                    $user = $_GET['user'];
                    $senha = $_GET['senha'];

                    autorizatrava($conn, $user,$senha);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'retornaQtdContada':
                if (isset($_GET['nunota']) ) {
                    $nunota = $_GET['nunota'];
                    retornaQtdContada($conn,$nunota);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'verificaEmpresa':
                if (isset($_GET['nunota']) ) {
                    $nunota = $_GET['nunota'];
                    
                    verificaEmpresa($conn, $nunota,$codusu);
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
            case 'buscaItensContagem':
                if (isset($_POST['nunota']) && isset($_POST['tipo'])) {
                    $nunota = $_POST['nunota'];
                    $tipo = $_POST['tipo'];
                    buscaItensContagem($conn, $nunota, $tipo, $codusu);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'atualizarDimensoes':
                if (isset($_POST['referencia']) && isset($_POST['peso']) && isset($_POST['largura']) && isset($_POST['altura']) && isset($_POST['comprimento']) ) {
                    $referencia = $_POST['referencia'];
                    $peso = $_POST['peso'];
                    $largura = $_POST['largura'];
                    $altura = $_POST['altura'];
                    $comprimento = $_POST['comprimento'];

                    atualizarDimensoes($conn, $referencia,$peso,$largura,$altura,$comprimento);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }   
                break;
            case 'verificaRecontagem':
                if (isset($_POST['nunota']) && isset($_POST['referencia']) && isset($_POST['qtdcont']) && isset($_POST['codbalanca']) && isset($_POST['tipo']) && isset($_POST['lote']) && isset($_POST['qtdseparar'])) {
                    $nunota = $_POST['nunota'];
                    $referencia = $_POST['referencia'];
                    $qtdcont = $_POST['qtdcont'];
                    $codbalanca = $_POST['codbalanca'];
                    $tipo = $_POST['tipo'];
                    $lote = $_POST['lote'];
                    $qtdseparar = $_POST['qtdseparar'];
                    verificaRecontagem($conn, $nunota, $referencia,$qtdcont,$codbalanca,$tipo, $lote, $qtdseparar);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'atualizarContagem':
                if (isset($_POST['referencia']) && isset($_POST['nunota']) && isset($_POST['tipo']) && isset($_POST['codbalanca']) && isset($_POST['qtdcont']) && isset($_POST['lote']) && isset($_POST['qtdseparar'])) {
                    $referencia = $_POST['referencia'];
                    $nunota = $_POST['nunota'];
                    $tipo = $_POST['tipo'];
                    $codbalanca = $_POST['codbalanca'];
                    $qtdcont = $_POST['qtdcont'];
                    $lote = $_POST['lote'];
                    $qtdseparar = $_POST['qtdseparar'];

                    atualizarContagem($conn,$referencia,$nunota,$tipo,$codbalanca,$qtdcont, $lote,$qtdseparar);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }   
                break;
            case 'verificaFinalizaContagem':
                if (isset($_POST['nunota']) && isset($_POST['tipo'])) {
                    $nunota = $_POST['nunota'];
                    $tipo = $_POST['tipo'];
                    verificaFinalizaContagem($conn,$nunota,$tipo, $codusu);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }   
                break;
            case 'verificaFinalizaContagem':
                if (isset($_POST['nunota']) && isset($_POST['tipo'])) {
                    $nunota = $_POST['nunota'];
                    $tipo = $_POST['tipo'];
                    verificaFinalizaContagem($conn,$nunota,$tipo, $codusu);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }   
                break;
            case 'finalizarContagem':
                if (isset($_POST['nunota']) && isset($_POST['tipo'])) {
                    $nunota = $_POST['nunota'];
                    $tipo = $_POST['tipo'];
                    finalizarContagem($conn,$nunota,$tipo, $codusu);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }   
                break;
            case 'aplicarOcorrencia':
                if (isset($_POST['nunota']) && isset($_POST['tipo']) && isset($_POST['referencia']) && isset($_POST['lote']) && isset($_POST['ocorrencia'])) {
                    $nunota = $_POST['nunota'];
                    $tipo = $_POST['tipo'];
                    $referencia = $_POST['referencia'];
                    $lote = $_POST['lote'];
                    $ocorrencia = $_POST['ocorrencia'];
                    aplicarOcorrencia($conn, $nunota, $tipo, $referencia, $lote, $ocorrencia);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }   
                break;
            case 'editaSubContagem':
                if (isset($_POST['nucontsub']) && isset($_POST['qtd']) && isset($_POST['nunota']) && isset($_POST['tipo'])) {
                    $nucontsub = $_POST['nucontsub'];
                    $qtd = $_POST['qtd'];
                    $nunota = $_POST['nunota'];
                    $tipo = $_POST['tipo'];
                    editaSubContagem($conn, $nucontsub, $qtd, $nunota, $tipo);
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
