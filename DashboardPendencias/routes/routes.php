<?php

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
                if (isset($_GET['nunota']) && isset($_GET['codparc'])) {
                    $nunota = $_GET['nunota'] === '' ? NULL : $_GET['nunota'];
                    $codparc = $_GET['codparc'] === '' ? NULL : $_GET['codparc'];
                    buscaPendencias($conn, $nunota, $codparc);
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
