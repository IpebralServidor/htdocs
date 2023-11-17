<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>Importação pedido Catálogo</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="_css/estilo.css">
</head>
<body>
	<?php
		function test_input($data) {
		      $data = trim($data);
		      $data = stripslashes($data);
		      $data = htmlspecialchars($data);
		      return $data;
			}

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
		    $pedido = test_input($_POST["f-pedido"]); 
		    
		    $textAr = explode("\n", $pedido);
		    $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind

		    $inicio = false;
		    $e_mensagem = false;
		    $fim = 0;
			$qtd_produto = 0;
			$codigo = "";
			$mensagem = "";
			$empresa = "";
		    
			foreach ($textAr as $line) {
				if (strpos($line, "## Catálogo IPEBRAL ##") !== FALSE  ) {
			   		$inicio = true;
				} elseif (strpos($line, "############################") !== FALSE ) {
			    	$fim++;
			    } elseif (strpos($line, "§") !== FALSE ) {
			    	$empresa = substr($line, strpos($line, "§")+2, strlen($line));
			    } elseif (strpos($line, "PEDIDO Nº") !== FALSE ){
			    	$pedido = $line;
			    } elseif (strpos($line, "CÓDIGO:") !== FALSE ){
			    	$letras = str_split($line);
					foreach($letras as $letra){
						if (ctype_digit($letra)) {
							break;	
						}
					}
					$codigo = substr($line, strpos($line, $letra), strlen($line));
			    } elseif (strpos($line, "QUANTIDADE:") !== FALSE ){
			    	$letras = str_split($line);
			    	for ($x = 0; $x <= count($letras); $x++) {
						if (ctype_digit($letras[$x])) {
							$quantidade = $letras[$x];
							$x++;
							while(ctype_digit($letras[$x])) {
								$quantidade = $quantidade . $letras[$x];
								$x++;
							}
							break;	
						}
					}
					$produtos[$qtd_produto] = array(trim($codigo), $quantidade);
					$qtd_produto++;
			    } elseif (strpos($line, "## MENSAGEM:") !== FALSE ){
			    	$e_mensagem = true;
			    } elseif ($e_mensagem) {
			    	$mensagem = $mensagem . $line . " ";
			    } 
			    
			}
			
			if (!($inicio and $fim === 2)) {
				echo "Erro: Não foi copiado todo o conteúdo do email.";
			} elseif ($empresa === "") {
				echo "Erro: Não foi encontrado o código da empresa, favor entrar em contato com o setor de informática.";
			} elseif ($qtd_produto === 0 ) {
				echo "Erro: Não foi encontrado produtos neste pedido.";
			} else {
		    	// $serverName = "SERVER-BD"; 
				// $uid = "sankhya";   
				// $pwd = "tecsis";  
				// $databaseName = "SANKHYA_PROD"; 

				// $connectionInfo = array( "UID"=>$uid,                            
				//                          "PWD"=>$pwd,                            
				//                          "Database"=>$databaseName); 

				// /* Conexao com SQL Server usando autenticacao. */  
				// $conn = sqlsrv_connect( $serverName, $connectionInfo);  

				// //Verifica se a conexao foi estabelecida com sucesso
				// if( $conn ) {
				//     echo "";
				// 	//   echo "Conexão estabelecida.<br/>";
				// } else {
				// 	echo "Não foi possível conectar ao SQL Server"."<br/>";
				//     die( print_r( sqlsrv_errors(), true));
				// }

				$insere_1700 = "EXEC AD_STP_INSERETGFCAB_CATALOGO 1700, " . $empresa . ", 9," . "'" . $pedido . " - " .$mensagem . "', ?";

				$NUNOTA_1700 = 0;

				// $params = array( array(&$NUNOTA_1700, SQLSRV_PARAM_OUT) );
				// $stmt_cab = sqlsrv_query( $conn, $insere_1700, $params);  
				
				// if( $stmt_cab === false ) {  
				//     echo "Erro na execução da procedure cab.\n";  
				//     die( print_r( sqlsrv_errors(), true));  
				// } 
				
				// foreach ($produtos as $produto) {
				// 	$insere_item_1700 = "EXEC AD_STP_INSERETGFITE_CATALOGO " . strval($NUNOTA_1700) . ", '" . $produto[0] . "', 0," . $produto[1] . ", 0" ;
				// 	$stmt_ite = sqlsrv_query( $conn, $insere_item_1700);
				// 	if( $stmt_cab === false ) {  
				// 	    echo "Erro na execução da procedure ite.\n";  
				// 	    die( print_r( sqlsrv_errors(), true));  
				// 	} 
				// }

				// echo "1700 criada número único: " . $NUNOTA_1700;

				// sqlsrv_free_stmt($stmt_cab);  
				// sqlsrv_free_stmt($stmt_ite);  
				// sqlsrv_close($conn);
			}			
		}
	?>

	<div id="interface">
		<header id="cabecalho">
			<figure class="foto-legenda">
				<img src="_img/logo_ipebral.png"/>
			</figure>
			<h1>Importação Pedido Catálogo</h1>
		</header>
	</div>
	<div id="formulario">
		<p>Siga os passos abaixo para importar um pedido recebido pelo email para o sankhya.</p>
		<ul type="disc">
			<li>Copie todo o conteúdo do email e cole no campo texto abaixo</li>
			<li>Clique no botão Gerar 1700</li>
		</ul>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
			<textarea name="f-pedido" id="f-pedido" required></textarea>	
			<br>
			<input class="btn btn-primary" type="submit" value="Gerar 1700">
		</form>
	</div>	
</body>
</html>