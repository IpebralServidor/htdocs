<?php

	$stmt ="";

	//Configuracoes do servidor SQL Server 
	$serverName = "10.0.0.229"; 
	$uid = "sankhya";   
	$pwd = "tecsis";  
	$databaseName = "SANKHYA_PROD"; 

	$connectionInfo = array( "UID"=>$uid,                            
	                         "PWD"=>$pwd,                            
	                         "Database"=>$databaseName); 

	/* Conexao com SQL Server usando autenticacao. */  
	$conn = sqlsrv_connect( $serverName, $connectionInfo); 

	sqlsrv_query($conn, 'UPDATE sankhya.tsiulg SET ATUALIZANDO = 0  WHERE spid = @@spid'); // Garanto que o ATUALIZANDO da sessão esteja 0 para que as triggers do Sankhya estejam ativas.
?>