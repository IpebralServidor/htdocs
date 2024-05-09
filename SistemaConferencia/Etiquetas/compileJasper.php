<?php

require 'C:\xampp\htdocs\vendor\autoload.php';

use PHPJasper\PHPJasper;
use phpseclib3\Net\SSH2;

$funcao = $_POST['funcao'];
if ($funcao === 'compileJasper') {
    compileJasper();
} else if ($funcao === 'fechaJanelaWcpp') {
    usleep(2500000);
    fechaJanelaWcpp();
}


function compileJasper()
{
    $param = $_POST['nunota'];
    $file = $_POST['arquivo'];

    mkdir('C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/' . $param);
    $output = 'C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/' . $param;
    $input = 'C:\xampp\htdocs\Reports/' . $file . '.jrxml';

    $jasper = new PHPJasper;

    $jasper->compile(
        $input,
        $output
    )->execute();

    $input = 'C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/' . $param . '/' . $file . '.jasper';
    $output = 'C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/' . $param;
    $jdbc_dir = 'C:\xampp\htdocs\vendor\geekcom\phpjasper\bin\jasperstarter\jdbc';

    $options = [
        'format' => ['pdf'],
        'locale' => 'en',
        'params' => [
            'PK_NUNOTA' => $param,
        ],
        'db_connection' => [
            'driver' => 'generic',
            'host' => '10.0.0.232',
            'port' => '1433',
            'database' => 'SANKHYA_TESTE',
            'username' => 'sankhya',
            'password' => 'tecsis',
            'jdbc_driver' => 'com.microsoft.sqlserver.jdbc.SQLServerDriver',
            'jdbc_url' => 'jdbc:sqlserver://10.0.0.232:1433;SANKHYA_TESTE=SANKHYA_TESTE',
            'jdbc_dir' => $jdbc_dir
        ]
    ];

    $jasper->process(
        $input,
        $output,
        $options
    )->execute();
}

function fechaJanelaWcpp()
{
    $host = $_SERVER['REMOTE_ADDR'];
    $port = 22;
    $username = 'administrador';
    $password = 'G3r#2oi5';

    $ssh = new SSH2($host, $port);
    if (!$ssh->login($username, $password)) {
        exit('Falha na autenticação');
    }

    $comando = 'echo G3r#2oi5 | sudo -S killall -e wcpp6';
    $output = $ssh->exec($comando);

    echo $output;
}
