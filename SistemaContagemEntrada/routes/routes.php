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
                if ( isset($_GET['tipo']) ) {
                $tipo = $_GET['tipo'];
                    buscaNotasContagem($conn,$tipo);   
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }              
                break;
            case 'buscaLiberaPaletes':                                
                if ( isset($_GET['tipo']) ) {
                $tipo = $_GET['tipo'];
                    buscaLiberaPaletes($conn,$tipo);   
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }              
                break;
            case 'buscaPaletesPendentes':                                
                if ( isset($_GET['tipo']) ) {
                $tipo = $_GET['tipo'];
                    buscaPaletesPendentes($conn,$tipo);   
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }              
                break;
             case 'buscaAtribuirNotas':                                
                if ( isset($_GET['tipo']) ) {
                $tipo = $_GET['tipo'];
                    buscaAtribuirNotas($conn,$tipo);   
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }              
                break;
            case 'buscaInformacoesNota':
                if (isset($_GET['nunota'])) {
                    $nunota = $_GET['nunota'];
                    buscaInformacoesNota($conn, $nunota);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
    
            case 'getReferenciaOp':                                
                if (isset($_GET['nunota']))  {
                    $nunota = $_GET['nunota'];

                    getReferenciaOp($conn,$nunota);   
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }              
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
             case 'buscaInfoCodUsu':
                if (isset($_GET['codusu'])) {
                    
                    $codusu = $_GET['codusu'];
                    buscaInfoCodUsu($conn, $codusu);

                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'buscaInfoNomeUsu':
                if (isset($_GET['nomeusu']) ) {
                    
                    $nomeusu = $_GET['nomeusu'];
                    buscaInfoNomeUsu($conn,$nomeusu);

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
                 case 'autorizatravaEntrada':
                if (isset($_GET['user']) && isset($_GET['senha'])) {
                    $user = $_GET['user'];
                    $senha = $_GET['senha'];

                    autorizatravaEntrada($conn, $user,$senha);
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
                case 'retornaMsgEntrada':
                if (isset($_GET['nunota']) ) {
                    $nunota = $_GET['nunota'];
                    retornaMsgEntrada($conn,$nunota);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
                case 'retornaMsgEstoqueInsuficiente':
                if (isset($_GET['nunota']) ) {
                    $nunota = $_GET['nunota'];
                    retornaMsgEstoqueInsuficiente($conn,$nunota);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
                
            case 'verificaEmpresa':
                if (isset($_GET['nunota']) && isset($_GET['tipo']) ) {
                    $nunota = $_GET['nunota'];
                    $tipo = $_GET['tipo'];
                    verificaEmpresa($conn, $nunota,$codusu,$tipo);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'verificaQtdSeparar':
                if (isset($_GET['nunota']) && isset($_GET['tipo']) ) {
                    $nunota = $_GET['nunota'];
                    $tipo = $_GET['tipo'];
                    verificaQtdSeparar($conn, $nunota,$codusu,$tipo);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }
                break;
            case 'verificaProximo':
               if ( isset($_GET['tipo']) ) {
                    $tipo = $_GET['tipo'];
                    verificaProximo($conn, $tipo,$codusu);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }   
                break;
            case 'pegaProximaNota':
                if (isset($_GET['tipo']) ) {
                    $tipo = $_GET['tipo'];
                
                    pegaProximaNota($conn, $tipo,$codusu);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }   
                break;
            case 'verificaGerente':
                
                verificaGerente($conn,$codusu);
                
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
                if (isset($_POST['referencia']) && isset($_POST['peso']) && isset($_POST['largura']) && isset($_POST['altura']) && isset($_POST['comprimento']) && isset($_POST['tipo'])) {
                    $referencia = $_POST['referencia'];
                    $peso = $_POST['peso'];
                    $largura = $_POST['largura'];
                    $altura = $_POST['altura'];
                    $comprimento = $_POST['comprimento'];
                    $tipo = $_POST['tipo'];
                    atualizarDimensoes($conn, $referencia,$peso,$largura,$altura,$comprimento,$tipo);
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
                    verificaRecontagem($conn, $nunota, $referencia,$qtdcont,$codbalanca,$tipo, $lote, $qtdseparar,$codusu);
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

                    atualizarContagem($conn,$referencia,$nunota,$tipo,$codbalanca,$qtdcont, $lote,$qtdseparar,$codusu);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }   
                break;
            case 'tiraTravaCodbar':
            if (isset($_POST['nunota']) && isset($_POST['tipo'])) {
                $nunota = $_POST['nunota'];
                $tipo = $_POST['tipo'];
                tiraTravaCodbar($conn,$nunota,$tipo, $codusu);
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
                    if (isset($_POST['nunota']) && isset($_POST['tipo']) && isset($_POST['separar'])) {
                        $nunota = $_POST['nunota'];
                        $tipo = $_POST['tipo'];
                        $separar = $_POST['separar'];
                        finalizarContagem($conn,$nunota,$tipo, $codusu,$separar);
                    } else {
                        echo json_encode(['error' => 'Parâmetros não enviados']);
                    }   
                    break;
                case 'finalizarContagemSeparar':
                if (isset($_POST['nunota']) && isset($_POST['tipo'])  && isset($_POST['qtdCD3EMP3'])
                     && isset($_POST['qtdCD5EMP3']) && isset($_POST['QTDGONDOLA']) && isset($_POST['qtdCD5EMP1']) && isset($_POST['qtdCD5EMP10']) && isset($_POST['QTDCONT'])) {
                    $nunota = $_POST['nunota'];
                     $tipo = $_POST['tipo'];
                    $qtdCD3EMP3 = $_POST['qtdCD3EMP3'];
                    $qtdCD5EMP3 = $_POST['qtdCD5EMP3'];
                    $QTDGONDOLA = $_POST['QTDGONDOLA'];
                    $qtdCD5EMP1 = $_POST['qtdCD5EMP1'];
                    $qtdCD5EMP10 = $_POST['qtdCD5EMP10'];
                    $QTDCONT= $_POST['QTDCONT'];

                    finalizarContagemSeparar($conn,$nunota,$tipo, $codusu,$qtdCD3EMP3,$qtdCD5EMP3,$QTDGONDOLA,$qtdCD5EMP1,$qtdCD5EMP10,$QTDCONT);
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
            case 'atribuirNotaUsuario':
                if (isset($_POST['tipo']) && isset($_POST['notas']) && isset($_POST['usuario']) ) {
                    $tipo = $_POST['tipo'];
                    $notas = $_POST['notas'];
                    $usuario = $_POST['usuario'];
                    atribuirNotaUsuario($conn, $tipo, $notas, $usuario);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }   
                break;
            case 'transferirPaletes':
                if ( isset($_POST['notas']) ) {
                    $notas = $_POST['notas'];
                    transferirPaletes($conn, $notas);
                } else {
                    echo json_encode(['error' => 'Parâmetros não enviados']);
                }   
                break;
                case 'enviarFotos':
                    if (isset($_POST['nunota']) && isset($_POST['imagens'])) {
                        $nunota = $_POST['nunota'];
                        $codprod = $_POST['codprod'];    
                        $imagens = $_POST['imagens'];    

                        enviarFotos($conn, $nunota, $codusu,$codprod ,$imagens);                    
                    } else {
                        echo json_encode(['error' => 'Parâmetros não enviados']);
                    }
                    break;
                case 'desatribuirNota':
                    if (isset($_POST['tipo']) && isset($_POST['notas']) && isset($_POST['usuario']) ) {
                        $tipo = $_POST['tipo'];
                        $notas = $_POST['notas'];
                        $usuario = $_POST['usuario'];
                        desatribuirNota($conn, $tipo, $notas, $usuario);
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
