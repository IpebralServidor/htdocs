<?php

session_start();
include "../conexaophp.php";

//echo $_POST['notas'];
// Verificar se os dados foram enviados corretamente
if (isset($_POST['notas'])) {
  
	$notas = $_POST['notas'];

	$notasSeparadas = explode("-", $notas);

	for ($i = 0; $i < count($notasSeparadas); $i++) {
		$nunota = substr($notasSeparadas[$i], 0, stripos($notasSeparadas[$i], "/"));
  		$usuario = substr($notasSeparadas[$i], stripos($notasSeparadas[$i], "/")+1);
  		
    	echo "Nota " . ($i + 1) . ": " . $nunota . " Usuário: " . $usuario . "\n";
	}


  

  // Realizar a operação de UPDATE no banco de dados usando os dados recebidos
  // ... seu código de conexão com o banco de dados e a query UPDATE ...
}






?>