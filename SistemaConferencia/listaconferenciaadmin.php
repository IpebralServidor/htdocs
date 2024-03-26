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

	<link href="css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
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
		function search_conferente() {
			let input = document.getElementById('searchbar').value
			input=input.toLowerCase();
			let x = document.getElementsByClassName('conferentes');
			
			for (i = 0; i < x.length; i++) { 
				if (!x[i].innerHTML.toLowerCase().includes(input)) {
					x[i].style.display="none";
				}
				else {
					x[i].style.display="list-item";                 
				}
			}
		}

		function abrirconf(){
			document.getElementById('popupconf').style.display = 'block';
		}

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
		function fecharatribuicao(){
			document.getElementById('popupconf').style.display =  'none';
		}
	</script>


</head>
<body class="background-lista">
	<div id="loader" style="display: none;">
		<img style=" width: 150px;" src="images/soccer-ball-joypixels.gif">
	</div>

	<div id="popupconf" class="popupconf">
		<?php 
			$tsqlText = "SELECT TEXTO FROM TSIPAR WHERE CHAVE = 'UsuConferencia'";

			$stmtText = sqlsrv_query( $conn, $tsqlText);  
			$rowText = sqlsrv_fetch_array( $stmtText, SQLSRV_FETCH_NUMERIC );
			
		?>
		<form action="atualizarconferente.php" class="form" method="post">
			<div class="form-group">
				<label for="conferentes" class="form-group">Conferentes:</label>
				<input type="text" class="form-control" name="conferentes" class="text" value="<?php echo $rowText[0]; ?>">
			</div>
			<div class="form-group">
				<input id="atualizar" name="atualizar" class="btn btn-form"  type="submit" value="Atualizar">
			</div>
		</form>
		<button class="fechar" onclick="fecharatribuicao();">X</button>
	</div>

	<div id="popupconferentes" class="popupconferentes">
		<h4>Lista de conferentes</h4>
		<div class="input-busca">
			<input type="text" id="searchbar" onkeyup="search_conferente()" placeholder="Escreva o nome do conferente">
		</div><br>
		<div >
			<table class="table-conferentes">
				
				<div class="conferentes-title">
					<h6>
						Conferentes
					</h6>
				</div><br>

				<?php 
					$tsql3 = "SELECT CODUSU, NOMEUSU 
							  FROM TSIUSU 
							  WHERE CODUSU IN (SELECT ITEM FROM SANKHYA.AD_FN_SPLIT((SELECT TEXTO FROM TSIPAR WHERE CHAVE = 'UsuConferencia'), ','))
							  ORDER BY NOMEUSU";

					$stmt3 = sqlsrv_query( $conn, $tsql3);  
					$row_count = sqlsrv_num_rows( $stmt3 ); 
					while( $row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_NUMERIC))
					{
				?>
				<ul id='list'>
					<li class='conferentes'>
						<button style="width: 100%; height: 100%; background-color:rgba(144,  203,  44,  0); cursor: pointer; " class="conferente-btn" data-user="<?php echo $row3[0]; ?>">
							<?php echo $row3[1]; ?>
						</button>
					</li>
				</ul>
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
				<img src="images/216446_arrow_left_icon.png">
			</a>
		</div>
		<form action="listaconferenciaadmin.php" class="form" method="post">
			<div>
				<input type="text" class="form-control" name="NUMNOTA" class="text" placeholder="Número da Nota:">
			</div>
			<div>
				<input type="text" class="form-control" name="nunota" class="text" placeholder="Número Único:">
			</div>	
			<div>
				<select name="status" class="form-control">
					<option value= "todos">Todas as notas</option>
					<option value= "todosnaoconfirmadas">Todos + 172X NÃO confirmadas</option>
					<option value= "aguardandoconf">Aguardando Conferência</option>
					<option value= "emandamento">Conferência em andamento</option>
					<option value= "aguardandorecont">Aguardando Recontagem</option>
					<option value= "recontemandamento">Recontagem em Andamento</option>
					<option value= "emseparacao">Separação em andamento</option>
				</select>
			</div>	

			<?php 
				$tsqlEmpresa = "SELECT CODEMP FROM TSIUSU WHERE CODUSU = $usuconf"; 
				$stmtEmpresa = sqlsrv_query( $conn, $tsqlEmpresa);  
				$rowEmpresa = sqlsrv_fetch_array( $stmtEmpresa, SQLSRV_FETCH_NUMERIC);

				$tsqlEmpresa1 = "SELECT DISTINCT CODEMP, NOMEFANTASIA FROM TSIEMP UNION SELECT 0, 'Todas as empresas'"; 
				$stmtEmpresa1 = sqlsrv_query( $conn, $tsqlEmpresa1);  
			?>

			<div>
				<select name="empresa" class="form-control">
					<?php  while($rowEmpresa1 = sqlsrv_fetch_array( $stmtEmpresa1, SQLSRV_FETCH_NUMERIC)){ ?>
						<option name="empresas" value="<?= $rowEmpresa1[0] ?>" <?php if($rowEmpresa[0] == $rowEmpresa1[0]){ echo "selected "; } ?> > 
							<?= $rowEmpresa1[1] ?> 
						</option>
					<?php } ?>
				</select>
			</div>
			
			<div>
				<input type="text" onfocus="(this.type='date')" class="form-control" name="dtinicio" placeholder="Data de início" >
				<input type="text" onfocus="(this.type='date')" class="form-control" name="dtfim" placeholder="Data de fim" >
			</div>				
			
			<div style="margin-bottom: 7%;">
				<input type="text" class="form-control" name="parceiro" class="text" placeholder="Parceiro:">
			</div>				
			<div>
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

			$empresa = $_POST["empresa"];

			$dtinicio = str_replace("-","", $_POST["dtinicio"]);

			$dtfim = str_replace("-","",$_POST["dtfim"]);

			$tsql2 = " SELECT * FROM [sankhya].[AD_FNT_LISTANOTAS_ADMIN_CONFERENCIA]($nunota, $numnota, $parceiro, '$status', $usuconf, $empresa, '$dtinicio', '$dtfim') ORDER BY CODTIPOPER, STATUSSEP, NUNOTA";
			
			}

			$stmt2 = sqlsrv_query( $conn, $tsql2);  
			$row_count = sqlsrv_num_rows( $stmt2 ); 

		?>
		
	</div> <!-- Filtro -->

	
	
	<div class="listaconferenciatext">
		<p class="text-center col">
			Lista de Conferência ADMINISTRADOR
			<button class="btn btn-admin btn-form col"  onclick="abrirconferentes();" >Atribuir nota</button>

			<button style="width: 40px !important; height: 36px !important; margin-right: 10px;" class="btn btn-admin btn-form col"  onclick="abrirconf();" >
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
				<path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0Zm-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
				<path d="M8.256 14a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z"/>
				</svg>
			</button>
		</p>
		
		
	</div>
	<div id="ListaConferencia" class="listaconferencia">
		<table width="4000">
			<thead>			
				<tr style="color: white;">
					<th ><input type="checkbox" name="select-all" id="select_all" value=""/></font></th>
					<th>Conferente</th>
					<th>Nro. Único</th>
					<th>Tipo Operação</th>
					<th>Separador</th>
					<th>Status Separação</th>
					<th>Status Conferência</th>
					<th>Dt. do Movimento</th>
					<th>Nro. Nota</th>
					<th>Empresa</th>
					<th>Parceiro</th>
					<th>Descrição (Tipo de Operação)</th>
					<th>Ordem de Carga</th>
					<th>Sequência da Carga</th>
					<th>Qtd. Volumes</th>
					<th>Razão social</th>
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
					}else if(utf8_encode($row2[15]) == 'Separação em andamento'){
						$color = "#FFFF95;";	
					}else if(utf8_encode($row2[15]) == 'Separação não iniciada'){
						$color = "#ff9595;";
					}else if(utf8_encode($row2[15]) == 'Separação em pausa'){
						$color = "#9c95ff;";
					}else if(utf8_encode($row2[15]) == 'Separação concluída'){
						$color = "#8fffb1";
					}
				?>
				<tr style="background-color:<?php echo $color ?>">
					
					<td style="width: 0.1% !important">
						<input type="checkbox" class="checkbox" data-nota="<?php echo $row2[0]; ?>"/>
					</td> 
					<td style="width: 0.1% !important"><?php echo $row2[14] .' - '.$row2[13]; ?></td>
					<td style="width: 0.1% !important"><?php echo $row2[0]; ?></td>
					<td><?php echo $row2[1]; ?></td>
					<td><?php echo $row2[17]; ?></td>
					<td><?php echo utf8_encode($row2[15]); ?></td>
					<td><?php echo $row2[2]; ?></td>
					<td><?php echo $row2[4]; ?></td>
					<td><?php echo $row2[6]; ?></td>
					<td><?php echo $row2[7]; ?></td>
					<td><?php echo $row2[3]. ' - ' .$row2[8]; ?></td>
					<td><?php echo utf8_encode($row2[9]); ?></td>
					<td><?php echo $row2[10]; ?></td>
					<td><?php echo $row2[11]; ?></td>
					<td style="width: 0.1% !important"><?php echo $row2[12]; ?></td>
					<td><?php echo $row2[16]; ?></td>
					<td style="width: 60% !important;"></td>
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