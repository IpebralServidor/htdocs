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
	<script type="text/javascript">

		$(document).ready(function(){
		$('#select_all').on('click',function(){
			if(this.checked){
				$('.checkbox').each(function(){
					this.checked = true;
				});
			}else{
				$('.checkbox').each(function(){
					this.checked = false;
				});
			}
		});

		$('.checkbox').on('click',function(){
			if($('.checkbox:checked').length == $('.checkbox').length){
				$('#select_all').prop('checked',true);


			}else{
				$('#select_all').prop('checked',false);
			}
		});
		});
	</script>

	<script>
		function abrirconferentes(){
			document.getElementById('popupconferentes').style.display = 'block';

			// Adicionar evento de clique aos botões do conferente
			var btns = document.getElementsByClassName('conferente-btn');
			for (var i = 0; i < btns.length; i++) {
				btns[i].addEventListener('click', function() {
				//var nota = document.querySelector('input[name="select-all"]:checked').parentNode.nextElementSibling.innerHTML;
				var checkedCheckboxes = document.querySelectorAll('#ListaConferencia tbody .checkbox:checked');

				if(checkedCheckboxes[0] == null){
					alert('Selecione pelo menos uma nota');
				}else{

					var user = this.getAttribute('data-user');

					var notas = "";

					for (var i = 0; i < checkedCheckboxes.length; i++) {
						var nota = checkedCheckboxes[i].getAttribute('data-nota');
						//alert(nota + "/" + user);
						notas += nota;

						if(i < checkedCheckboxes.length -1){
							notas += "/"
						}
					}

					fazerUpdateNoBanco(notas, user);

				};

				window.location.href='listaconferenciaadmin.php';
				$("#aplicar").click();

				});
			}
		}
		function fecharconferentes(){
			document.getElementById('popupconferentes').style.display =  'none';
		}
	</script>


</head>
<body class="background-lista">
	<div id="loader" style="display: none;">
		<img style=" width: 150px;" src="images/soccer-ball-joypixels.gif">
	</div>
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
			</div>
		</div>
	</div>

	<div id="popupconferentes" class="popupconferentes">
		<h4>Lista de conferentes</h4>
		<div >
			<table class="table-conferentes">
				<thead>
					<tr>
						<th>Conferentes</th>
					</tr>
				</thead>

				<?php 
					$tsql3 = "SELECT CODUSU, NOMEUSU FROM TSIUSU ORDER BY NOMEUSU ";

					$stmt3 = sqlsrv_query( $conn, $tsql3);  
					$row_count = sqlsrv_num_rows( $stmt3 ); 
					while( $row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_NUMERIC))
					{
				?>
				<tbody>
					<tr>
						<td>
							<button class="conferente-btn" data-user="<?php echo $row3[0]; ?>">
								<?php echo $row3[1]; ?>
							</button>
						</td>
					</tr>
				</tbody>
				
				<?php
					}
				?>
			</table>
			<button class="fechar" onclick="fecharconferentes();">X</button>
		</div>
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
		<form action="listaconferenciaadmin.php" class="form" method="post">
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
					<option value= "todosnaoconfirmadas">Todos + 172X NÃO confirmadas</option>
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

			$tsql2 = " SELECT * FROM [sankhya].[AD_FNT_LISTANOTAS_ADMIN_CONFERENCIA]($nunota, $numnota, $parceiro, '$status', $usuconf) ORDER BY CODTIPOPER, STATUSSEP, NUNOTA";

			}

			$stmt2 = sqlsrv_query( $conn, $tsql2);  
			$row_count = sqlsrv_num_rows( $stmt2 ); 

		?>

	</div> <!-- Filtro -->
	<div class="listaconferenciatext">
		<p class="text-center col">
			Lista de Conferência ADMINISTRADOR
			<button class="btn btn-admin btn-form col"  onclick="abrirconferentes();" >Atribuir nota</button>
		</p>
		
		
	</div>
	<div id="ListaConferencia" class="listaconferencia">

		<table width="4000">
			<thead>			
				<tr style="color: white;">
					<th ><input type="checkbox" name="select-all" id="select_all" value=""/></font></th>
					<th>Nome (Conferente)</th>
					<th>Cod conferente</th>
					<th>Nro. Único</th>
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
					}else if(utf8_encode($row2[16]) == 'Separação concluída'){
						$color = "#8fffb1";
					}
				?>
				<tr style="background-color:<?php echo $color ?>">
					
					<td style="width: 0.1%;">
						<input type="checkbox" class="checkbox" data-nota="<?php echo $row2[0]; ?>"/>
					</td> 
					<td style="width: 30px;"><?php echo $row2[14]; ?></td>
					<td style="width: 30px;"><?php echo $row2[15]; ?></td>
					<td style="width: 30px;"><?php echo $row2[0]; ?></td>
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
					<td ></td>
				</tr> 
			</tbody>

			
			<?php
	}
	?>
		</table>
		
	
<!-- Button trigger modal -->
		

		<!-- Modal -->
		
</div> 

			
		</div><!-- ListaConferencia -->

		<script>
			function fazerUpdateNoBanco(notas, usuario)
				{
					//O método $.ajax(); é o responsável pela requisição
					$.ajax
					({
						//Configurações
						type: 'POST',//Método que está sendo utilizado.
						dataType: 'html',//É o tipo de dado que a página vai retornar.
						url: 'cadastrarnotas.php',//Indica a página que está sendo solicitada.
						//função que vai ser executada assim que a requisição for enviada
						beforeSend: function () {
						$("#loader").show();
						},
						complete: function(){
							$("#loader").hide();
						},
						data: {notas: notas, usuario: usuario},//Dados para consulta
						//função que será executada quando a solicitação for finalizada.
						success: function (msg)
						{
							alert(msg);
							window.location.href='listaconferenciaadmin.php';
							
						}
					});
			}

		</script>
</body>
</html>