<?php

require 'C:\xampp\htdocs\vendor\autoload.php';

use phpseclib3\Net\SFTP;

// Configurações do servidor Linux
$server = '10.0.0.227';
$port = 2285;
$username = 'administrador';
$password = 'R0dr1gu3$'; // Para uma segurança melhor, use autenticação por chave SSH

// Configurações do arquivo no Windows
$localFile = 'C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas\3372583\Vale_novo.jasper.gz';

// Caminho da pasta no servidor Linux
$remotePath = '/etc/sps_sankhya/sps-print-files/';

try {
    // Conecta ao servidor Linux via SFTP
    $sftp = new SFTP($server, $port);

    if (!$sftp->login($username, $password)) {
        throw new Exception('Falha na autenticação.');
    }

    // Transfere o arquivo para o servidor usando SFTP
    $remoteFile = $remotePath . basename($localFile);
    if (!$sftp->put($remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE)) {
        throw new Exception('Falha ao transferir o arquivo.');
    }

    echo "Arquivo transferido com sucesso para $remoteFile\n";

} catch (\phpseclib3\Exception\SSH2Exception $e) {
    echo "Erro SSH: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}

