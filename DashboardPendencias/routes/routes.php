<?php

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0); // Desliga exibição de erros na tela
ini_set('log_errors', 1);     // Loga erros no arquivo de log
set_time_limit(300);        // PHP: 5 minutos
ini_set('max_execution_time', 300);

require_once "../../conexaophp.php";
require_once '../../App/auth.php';
require_once '../Model/Index.php';


// Verifica se foi feita uma requisição GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verifica se o parâmetro 'route' foi enviado
    if (isset($_GET['route'])) {
        $route = $_GET['route'];

        switch ($route) {
            case 'buscaPendencias':
                if (isset($_GET['nunota']) && isset($_GET['codparc']) && isset($_GET['codemp'])) {
                    $nunota = $_GET['nunota'] === '' ? NULL : $_GET['nunota'];
                    $codparc = $_GET['codparc'] === '' ? NULL : $_GET['codparc'];
                    $codemp = $_GET['codemp'] === '' ? NULL : $_GET['codemp'];
                    buscaPendencias($conn, $nunota, $codparc, $codemp);
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


?>