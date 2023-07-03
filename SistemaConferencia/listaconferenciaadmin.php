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


			      var user = this.getAttribute('data-user');

			      var notas = "";

			      for (var i = 0; i <= checkedCheckboxes.length; i++) {
			      	var nota = checkedCheckboxes[i].getAttribute('data-nota');
			      	//alert(nota + "/" + user);

			      	notas = notas + nota + "/" + user;
			      	//fazerUpdateNoBanco(nota, user);
			      	if(i < checkedCheckboxes.length -1){
			      		notas = notas + "-"
			      	}

			      	if(checkedCheckboxes.length = i){
			      		//alert(notas);
			      		fazerUpdateNoBanco(notas);
			      	}
			      }

			      window.location.href='listaconferenciaadmin.php';
			      window.location.href='listaconferencia.php';
			      //alert(nota + " " + user);
			      // console.log(nota);

			    });
			  }

			
		}
		function fecharconferentes(){
			document.getElementById('popupconferentes').style.display =  'none';
		}
	</script>


</head>
<body class="background-lista">
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
		<h4>Produtos com Divergência</h4>
		<div >
			<!-- <form action="action2.php"> -->
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
			<!-- </form> -->
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
				<input name="aplicar" class="btn btn-form"  type="submit" value="Aplicar">
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

			$tsql2 = " SELECT * FROM [sankhya].[AD_FNT_LISTANOTAS_CONFERENCIA]($nunota, $numnota, $parceiro, '$status', $usuconf)";

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
					<th><input type="checkbox" name="select-all" id="select_all" value=""/></font></th>
					<th>Nro. Único</font></th>
					<th>Tipo Operação</font></th>
					<th>Status Conferência</font></th>
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
					<th>Nome (Conferente)</th>
				</tr>
			</thead>

			<?php
				while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
				{ $NUCONF = $row2[0];
			?>

			<tbody>
				<tr>
					<td style="width: 3%;">
						<input type="checkbox" class="checkbox" data-nota="<?php echo $row2[0]; ?>"/>
					</td> 
					<td><?php echo $row2[0]; ?></td>
					<td ><?php echo $row2[1]; ?></td>
					<td ><?php echo $row2[2]; ?></td>
					<td ><?php echo $row2[3]; ?></td>
					<td ><?php echo $row2[4]; ?></td>
					<td ><?php echo $row2[6]; ?></td>
					<td ><?php echo $row2[7]; ?></td>
					<td ><?php echo $row2[8]; ?></td>
					<td ><?php echo $row2[9]; ?></td>
					<td ><?php echo $row2[10]; ?></td>
					<td ><?php echo $row2[11]; ?></td>
					<td ><?php echo $row2[12]; ?></td>
					<td ><?php echo $row2[13]; ?></td>
					<td ><?php echo $row2[14]; ?></td>
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
			function fazerUpdateNoBanco(notas)
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
									$("#cadastrarnotas").html("Carregando...");
								},
								data: {notas: notas},//Dados para consulta
								//função que será executada quando a solicitação for finalizada.
								success: function (msg)
								{
									//$("#cadastrarnotas").html(msg);
									console.log('UPDATE executado com sucesso!');
									alert(msg);
									alert("Notas inseridas com sucesso!")
			      			window.location.href='listaconferenciaadmin.php';
								}
							});
				}


		</script>
</body>
</html>