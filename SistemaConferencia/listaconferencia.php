<?php
	include "../conexaophp.php";
	require_once 'App/auth.php';

	$usuconf = $_SESSION["idUsuario"];
	$_SESSION["codbarraselecionado"] = 0;
	$_SESSION["nmrComplemento"] = "";
	$_SESSION["funcao"] = false;

?>

<html>
<head>
	<meta charset="UTF-8"/>	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lista Conferência - <?php echo $usuconf; ?></title>

	<link href="css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>
		$(document).ready(function() {
		    // Evento de clique na linha da tabela
		    $('#ListaConferencia tr').dblclick(function() {
		      // Obtém o ID da linha clicada
		      var nota = this.getAttribute('data-nota');;

		      //alert(nota);

		      //Enviar o dado via AJAX para o servidor
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
						if(msg.length <= 10){
							window.location.href='detalhesconferencia.php?nunota=' + msg +'&codbarra=0';
						} else {
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
				<img src="images/216446_arrow_left_icon.png">
			</a>
		</div>
		<form action="listaconferencia.php" class="form" method="post">
			<div class="form-group">
				<input type="text" class="form-control" name="NUMNOTA" class="text" placeholder="Número da Nota:">
			</div>
			<div>
				<input type="text" class="form-control" name="nunota" class="text" placeholder="Número Único:">
			</div>	
			<div class="form-group">
				<select name="status" class="form-control">
					<option value= "todos">Todas as notas</option>
					<option value= "aguardandoconf">Aguardando Conferência</option>
					<option value= "emandamento">Conferência em andamento</option>
					<option value= "aguardandorecont">Aguardando Recontagem</option>
					<option value= "recontemandamento">Recontagem em Andamento</option>
				</select>
			</div>		
			<div class="form-group">
				<input type="text" class="form-control" name="parceiro" class="text" placeholder="Parceiro:">
			</div>	
			
			<div class="form-group">
				<input id="aplicar" name="aplicar" class="btn btn-form"  type="submit" value="Aplicar">
			</div>

		</form>
		<div class='my-legend'>
			<div class='legend-title'>Legenda:</div>
			<div class='legend-scale'>
				<ul class='legend-labels'>
					<li><span style='background:#8fffb1;'></span>Separação concluída</li>
					<li><span style='background:#FFFF95;'></span>Separação em andamento</li>
					<li><span style='background:#ff9595;'></span>Separação não iniciada</li>
					<li><span style='background:#9c95ff;'></span>Separação em pausa</li>
				</ul>
			</div>
		</div>
		
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
				<th>Parceiro</th>
				<th>Dt. do Movimento</th>
				<th>Nro. Único</th>
				<th>Nome (Conferente)</th>
				<th>TOP</th>
				<th>Valor Nota</th>
				<th>Status Separação</th>
				<th>Status Conferência</th>
				<th>Nro. Nota</th>
				<th>Empresa</th>
				<th>Nome Fantasia (Empresa)</th>
				<th>Cod conferente</th>
				<th>Descrição (Tipo de Operação)</th>
				<th>Ordem de Carga</th>
				<th>Sequência da Carga</th>
				<th>Qtd. Volumes</th>
				<th>Cód. Conferente</th>
				<th></th>
			</tr>
		</thead>

		<?php
			while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))  
			{ $NUCONF = $row2['NUNOTA'];
		?>

		<tbody>
			<?php
				if($row2['CODTIPOPER'] == 1780 || $row2['CODTIPOPER'] == 1781 || $row2['CODTIPOPER'] == 1782){
					$color = "white";
				}else if(utf8_encode($row2['STATUSSEP']) == 'Separação em andamento'){
					$color = "#FFFF95;";	
				}else if(utf8_encode($row2['STATUSSEP']) == 'Separação não iniciada'){
					$color = "#ff9595;";
				}else if(utf8_encode($row2['STATUSSEP']) == 'Separação em pausa'){
					$color = "#9c95ff;";
				}
				else if(utf8_encode($row2['STATUSSEP']) == 'Separação concluída'){
					$color = "#8fffb1";
				}
			?>
			<tr style="background-color:<?php echo $color ?>" id="linhaSelecionada" data-nota="<?php echo $row2['NUNOTA'] ?>">
				<td style="width: 30px;"><?php echo $row2['CODPARC']; ?></td>
				<td style="width: 30px;"><?php echo $row2['DTMOV']; ?></td>
				<td style="width: 30px;"><?php echo $row2['NUNOTA']; ?></td>
				<td style="width: 30px;"><?php echo $row2['NOMEUSU']; ?></td>
				<td style="width: 30px;"><?php echo $row2['CODTIPOPER']; ?></td>
				<td style="width: 30px;"><?php echo str_replace('.',',',$row2['VLRNOTA']); ?></td>
				<td style="width: 30px;"><?php echo utf8_encode($row2['STATUSSEP']); ?></td>
				<td style="width: 30px;"><?php echo $row2['STATUSCONF']; ?></td>
				<td style="width: 30px;"><?php echo $row2['NUMNOTA']; ?></td>
				<td style="width: 30px;"><?php echo $row2['CODEMP']; ?></td>
				<td style="width: 30px;"><?php echo $row2['NOMEPARC']; ?></td>
				<td style="width: 30px;"><?php echo $row2['CODFUNC']; ?></td>
				<td style="width: 30px;"><?php echo utf8_encode($row2['DESCROPER']); ?></td>
				<td style="width: 30px;"><?php echo $row2['ORDEMCARGA']; ?></td>
				<td style="width: 30px;"><?php echo $row2['SEQCARGA']; ?></td>
				<td style="width: 30px;"><?php echo $row2['QTDVOL']; ?></td>
				<td style="width: 30px;"><?php echo $row2['CODUSUCONF']; ?></td>
				<td></td>
			</tr> 
		</tbody>

		
		<?php
				$notaRetorno = $row2['NUNOTA'];
			}
		?>
	</table>

	</div> 
	<script charset="utf-8">
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