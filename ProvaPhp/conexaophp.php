<?php

	//Configuracoes do servidor SQL Server 
	$serverName = "10.0.0.232"; 
	$uid = "sankhya";
	$pwd = "tecsis";
	$databaseName = "SANKHYA_TESTE";

	$connectionInfo = array( "UID"=>$uid,                            
	                         "PWD"=>$pwd,                            
	                         "Database"=>$databaseName); 

	$conn = sqlsrv_connect( $serverName, $connectionInfo);

    //Conexão com o banco de prova
    $uid2 = "sa";
    $pwd2 = "1p3br@l";
    $databaseName2 = "PROVA";

    $connectionInfo2 = array( "UID"=>$uid2,
        "PWD"=>$pwd2,
        "Database"=>$databaseName2);
    $conn2 = sqlsrv_connect( $serverName, $connectionInfo2);

?>