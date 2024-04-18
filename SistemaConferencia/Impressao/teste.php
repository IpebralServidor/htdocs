<?php

require 'vendor/autoload.php';

use phpseclib3\Net\SSH2;

// Configurações do servidor Linux
$server = '10.0.0.221';
$port = 22;
$username = 'mgeweb';
$password = 'mgeweb'; // Para uma segurança melhor, use autenticação por chave SSH

// Configurações do arquivo no Windows
$localFile = 'C:\teste\img_prova.jpeg';

// Caminho da pasta no servidor Linux
$remotePath = '/home/mgeweb/ws/';

try {
    // Conecta ao servidor Linux via SSH
    $ssh = new SSH2($server, $port);

    if (!$ssh->login($username, $password)) {
        throw new Exception('Falha na autenticação.');
    }

    // Transfere o arquivo para o servidor
    $remoteFile = $remotePath . basename($localFile);
    $ssh->write("cat > $remoteFile", file_get_contents($localFile));

    echo "Arquivo transferido com sucesso para $remoteFile\n";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
