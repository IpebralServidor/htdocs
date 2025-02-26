<?php

$stmt = "";

//Configuracoes do servidor SQL Server 
$serverName = "10.0.0.232";
$uid = "sankhya";
$pwd = "tecsis";
$databaseName = "SANKHYA_TESTE";

$connectionInfo = array(
	"UID" => $uid,
	"PWD" => $pwd,
	"Database" => $databaseName
);

/* Conexao com SQL Server usando autenticacao. */
$conn = sqlsrv_connect($serverName, $connectionInfo);


sqlsrv_query($conn, 'UPDATE sankhya.tsiulg SET ATUALIZANDO = 0  WHERE spid = @@spid');
