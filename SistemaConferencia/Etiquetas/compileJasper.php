<?php
require 'C:\xampp\htdocs\vendor\autoload.php';

use PHPJasper\PHPJasper;
use phpseclib3\Net\SSH2;

$funcao = $_POST['funcao'];
if ($funcao === 'compileJasper') {
    compileJasper();
} else if ($funcao === 'fechaJanelaWcpp') {
    // Fechar a janela do WebClientPrint é tratado de forma diferente no Windows e no Linux.
    // Para o Linux, esperamos x segundos para não matar o processo antes que ele envie os dados para a impressora.
    usleep(2500000);
    fechaJanelaWcpp();
}


function compileJasper()
{
    // A biblioteca externa PHPJasper transforma o arquivo .jrxml gerado pelo iReport para um pdf
    $param = $_POST['nunota'];
    $file = $_POST['arquivo'];

    mkdir('C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/' . $param);
    $output = 'C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/' . $param;
    $input = 'C:\xampp\htdocs\Files/' . $file . '.jrxml';

    $jasper = new PHPJasper;

    // Transforma o arquivo .jrxml em um arquivo .jasper
    $jasper->compile(
        $input,
        $output
    )->execute();

    $input = 'C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/' . $param . '/' . $file . '.jasper';
    $output = 'C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/' . $param;
    $jdbc_dir = 'C:\xampp\htdocs\vendor\geekcom\phpjasper\bin\jasperstarter\jdbc';

    // Opções para a conexão com o banco, para que o relatório seja gerado com dados atualizados.
    // Importante configurar certo o jdbc_dir, no momento é usado o sqljdbc4 para o SQL Server   
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

    // Transforma o arquivo .jasper em um arquivo .pdf, que será usado para a impressão
    $jasper->process(
        $input,
        $output,
        $options
    )->execute();
}

function fechaJanelaWcpp()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    // Verifica se é um cliente Linux fazendo a requisição
    if (strpos($user_agent, 'Linux') !== false) {
        $host = $_SERVER['REMOTE_ADDR'];
        $port = 22;
        $username = 'administrador';
        $password = 'G3r#2oi5';
        // Conecta por SSH com o cliente Linux
        $ssh = new SSH2($host, $port);
        if (!$ssh->login($username, $password)) {
            exit('Falha na autenticação');
        }
        // Executa um comando para matar o processo da janela do WebClientPrint
        $comando = 'echo G3r#2oi5 | sudo -S killall -e wcpp6';
        $output = $ssh->exec($comando);

        echo $output;
        // Para configurar a conexão SSH com o cliente Linux, rodar os seguintes comandos no terminal: "apt install openssh-server" e "systemctl start ssh"
        // Comando opcional para verificar se o OpenSSH Server está funcionando: "systemctl status ssh"
    }
}
