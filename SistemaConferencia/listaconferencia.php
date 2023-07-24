<?php
	include "../conexaophp.php";
	require_once 'App/auth.php';

	$usuconf = $_SESSION["idUsuario"];
	$_SESSION["codbarraselecionado"] = 0;
//Verifica se retornou resultado

/*if ( $stmt2 ) 
{  
    //echo "A query foi executada com sucesso..<br>\n";  
	echo "";

}   
else   
{  
     //echo "Houve erro na execução da query.\n";  
     //die( print_r( sqlsrv_errors(), true));  
} */ 
?>

<html>
<head>
	<meta charset="UTF-8"/>	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lista Conferência - <?php echo $usuconf; ?></title>

	<link href="css/main.css" rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>
		$(document).ready(function() {
			// Configurando o evento de clique nas linhas da tabela
			$('table tr').dblclick(function() {
				var nota = $(this).find('td').map(function() {
					return $(this).text();
				}).get();
				
				// Enviar o dado via AJAX para o servidor
				$.ajax
				({
					//Configurações
					type: 'POST',//Método que está sendo utilizado.
					dataType: 'html',//É o tipo de dado que a página vai retornar.
					url: 'iniciarconferencia.php',//Indica a página que está sendo solicitada.
					//função que vai ser executada assim que a requisição for enviada
					beforeSend: function () {
						$("#loader").show();
					},
					complete: function(){
						$("#loader").hide();
					},
					data: {nota: nota},//Dados para consulta
					//função que será executada quando a solicitação for finalizada.
					success: function (msg)
					{
						var msgArray = msg.split("/");

						if(msgArray[0] == 1780 || msgArray[0] == 1781 || msgArray[0] == 1782){
							$("#loader").hide();
							window.location.href='detalhesconferencia.php?nunota=' +msgArray[1] +'&codbarra=0';
						}else{
							alert(msg);
						}
					}
				});
			});
		});
	</script>

</head>
<body class="background-lista">
	<div id="loader" style="display: none;">
		<img style=" width: 150px; margin-top: 5%;" src="images/soccer-ball-joypixels.gif">
	</div>

	<div id="Filtro" class="filtro">
		<div class="img-voltar">
			<a href="../menu.php">
				<aabr title="Voltar para Menu">
					<button class="btn btn-back">
						<img src="images/216446_arrow_left_icon.png">
						<p>Voltar ao menu</p>
					</button>
				</aabr>
			</a>
		</div>
		<form action="listaconferencia.php" class="form" method="post">
			<div class="form-group">
				<label for="numnota" class="form-group">Número da Nota:</label>
				<input type="text" class="form-control" name="NUMNOTA" class="text">
			</div>
			<div>
				<label for="nunota">Número Único:</label>
				<input type="text" class="form-control" name="nunota" class="text">
			</div>	
			<div class="form-group">
				<label for="status">Status:</label>
				<select name="status" class="form-control">
					<option value= "todos">Todos</option>
					<option value= "aguardandoconf">Aguardando Conferência</option>
					<option value= "emandamento">Em Andamento</option>
					<option value= "aguardandorecont">Aguardando Recontagem</option>
					<option value= "recontemandamento">Recontagem em Andamento</option>
				</select>
			</div>		
			<div class="form-group">
				<label for="parceiro">Parceiro:</label>
				<input type="text" class="form-control" name="parceiro" class="text">
			</div>	
			
			<div class="form-group">
				<input id="aplicar" name="aplicar" class="btn btn-form"  type="submit" value="Aplicar">
			</div>

		</form>
		
		<?php 

			$tsql2 = "";

			if(isset($_POST["aplicar"])){

			$nunota =  $_POST["nunota"];
			if($nunota == ''){
				$nunota = -1;
			}

			$numnota =  $_POST["NUMNOTA"];
			if($numnota == ''){
				$numnota = -1;
			}				

			$parceiro =  $_POST["parceiro"];
			if($parceiro == ''){
				$parceiro = -1;
			}

			$status = $_POST["status"];

			$tsql2 = " SELECT * FROM [sankhya].[AD_FNT_LISTANOTAS_CONFERENCIA]($nunota, $numnota, $parceiro, '$status', $usuconf) ORDER BY NUNOTA DESC";

			}

			$stmt2 = sqlsrv_query( $conn, $tsql2);  
			$row_count = sqlsrv_num_rows( $stmt2 ); 

		?>

	</div> <!-- Filtro -->
	<div class="listaconferenciatext">
		<p class="text-center">Lista de Conferência
		<button class="btn btn-admin btn-form col"  onclick="pegarProximaNota(<?php echo $usuconf ?>);" >Pegar próxima nota</button>
		</p>
	</div>
	<div id="ListaConferencia" class="listaconferencia">
		<table width="4000">
		<thead>
			<tr style="color: white;">
				<th>Nro. Único</th>
				<th>Nome (Conferente)</th>
				<th>Cod conferente</th>
				<th>Tipo Operação</th>
				<th>Status Separação</th>
				<th>Status Conferência</th>
				<th>Parceiro</th>
				<th>Dt. do Movimento</th>
				<th>Nro. Nota</th>
				<th>Empresa</th>
				<th>Nome Fantasia (Empresa)</th>
				<th>Descrição (Tipo de Operação)</th>
				<th>Ordem de Carga</th>
				<th>Sequência da Carga</th>
				<th>Qtd. Volumes</th>
				<th>Cód. Conferente</th>
				<th></th>
			</tr>
		</thead>

		<?php
			while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
			{ $NUCONF = $row2[0];
		?>

		<tbody>
			<?php
				if($row2[1] == 1780 || $row2[1] == 1781 || $row2[1] == 1782){
					$color = "white";
				}else if(utf8_encode($row2[16]) == 'Separação em andamento'){
					$color = "#FFFF95;";	
				}else if(utf8_encode($row2[16]) == 'Separação não iniciada'){
					$color = "#ff9595;";
				}else if(utf8_encode($row2[16]) == 'Separação em pausa'){
					$color = "#9c95ff;";
				}
				else if(utf8_encode($row2[16]) == 'Separação concluída'){
					$color = "#8fffb1";
				}
			?>
			<tr style="background-color:<?php echo $color ?>" id="linhaSelecionada" data-nota="<?php echo $row2[0] ?>">
				<td style="width: 30px;"><?php echo $row2[0]; ?></td>
				<td style="width: 30px;"><?php echo $row2[14]; ?></td>
				<td style="width: 30px;"><?php echo $row2[15]; ?></td>
				<td style="width: 30px;"><?php echo $row2[1]; ?></td>
				<td style="width: 30px;"><?php echo utf8_encode($row2[16]); ?></td>
				<td style="width: 30px;"><?php echo $row2[2]; ?></td>
				<td style="width: 30px;"><?php echo $row2[3]; ?></td>
				<td style="width: 30px;"><?php echo $row2[4]; ?></td>
				<td style="width: 30px;"><?php echo $row2[6]; ?></td>
				<td style="width: 30px;"><?php echo $row2[7]; ?></td>
				<td style="width: 30px;"><?php echo $row2[8]; ?></td>
				<td style="width: 30px;"><?php echo utf8_encode($row2[9]); ?></td>
				<td style="width: 30px;"><?php echo $row2[10]; ?></td>
				<td style="width: 30px;"><?php echo $row2[11]; ?></td>
				<td style="width: 30px;"><?php echo $row2[12]; ?></td>
				<td style="width: 30px;"><?php echo $row2[13]; ?></td>
				<td></td>
			</tr> 
		</tbody>

		
		<?php
		$notaRetorno = $row2[0];
		}
		?>
	</table>

	</div> <!-- ListaConferencia -->
	<script>
		function pegarProximaNota(usuario)
			{
				//O método $.ajax(); é o responsável pela requisição
				$.ajax
				({
					//Configurações
					type: 'POST',//Método que está sendo utilizado.
					dataType: 'html',//É o tipo de dado que a página vai retornar.
					url: 'proximanota.php',//Indica a página que está sendo solicitada.
					//função que vai ser executada assim que a requisição for enviada
					beforeSend: function () {
						$("#loader").show();
					},
					complete: function(){
						$("#loader").hide();
					},
					data: {usuario: usuario},//Dados para consulta
					//função que será executada quando a solicitação for finalizada.
					success: function (msg)
					{
						if(msg == 'C'){
							window.location.href='listaconferencia.php';
							$("#aplicar").click();
						}else if(msg == 'N'){
							alert('IPB: Não existe nota para ser pega');
						}else{
							alert('IPB: Existem notas que não foram concluídas. Não é possível pegar uma nova nota');
						}
					}
				});
		}
	</script>

</body>
</html>