<?php
include "conexaophp.php";
require_once 'App/auth.php';

include 'nav.php';


// session_start();

?>


<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/menu.css">

	<title>Menu</title>
</head>

<body>
	<!--<h1>Menu</h1>-->

	<div class="containerMenu">
		<div class="conteudoMenu">
			<?php 
				$codusu = $_SESSION["idUsuario"];

				$a = array(100, 2, 3274);

				$tsqlAdmin = "SELECT AD_PERMISSAO_CONFERENCIA
							FROM TSIUSU 
							WHERE CODUSU = $codusu";

				$stmtAdmin = sqlsrv_query( $conn, $tsqlAdmin);  
				$row_countAdmin = sqlsrv_fetch_array( $stmtAdmin, SQLSRV_FETCH_NUMERIC);
				
				if($row_countAdmin[0] == 'A'){
					echo "<a href='./SistemaConferencia/listaconferenciaadmin.php'><button class='botao1'>CONFERÊNCIA <br> ADMIN</button></a>";
				}
				if(in_array($codusu, $a, true)){
					echo "<a href='./Manutencao'><button class='botao1'>MANUTENÇÃO</button></a>";
				}
			?>
			
			<a href="./SistemaConferencia/listaconferencia.php"><button class="botao1">CONFERÊNCIA</button></a>
			<a href="./SistemaEstoque"><button class="botao1">COLETOR</button></a>
			<a href="./ConsultaEstoque"><button  class="botao1">CONSULTA EST.</button></a>
			<a href="./SistemaReabastecimento"><button>REABASTECIMENTO.</button></a>
		</div>
	</div>

</body>


</html>