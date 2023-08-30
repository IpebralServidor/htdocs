<?php
include "../conexaophp.php";
require_once '../App/auth.php';


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/main.css">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


	<title>Estoque CD3</title>


	<script type="text/javascript">

		function abrirObservacao(){
			document.getElementById('popupObservacao').style.display = 'block';
		}
		function fecharObservacao(){
			document.getElementById('popupObservacao').style.display =  'none';
		}

	</script>
</head>
<body>

	<!-- Edição de Produtos -->
		<div id="popupObservacao" class="popupObservacao">
			<button class="fechar" id="fechar" onclick="fecharObservacao();">X</button>
			
			<div style="width: 100%; height:100%;">

				<h3>Endereços de Origem</h3>

				<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
				  <tr> 
					  	<th width="33%" >Loc. Origem</th>
					    <th width="33%" >Destino</th>
					    <th width="33%" >TOP</th>
				   </tr>

				   <tr>
						<td width="33%">3990000</td>
						<td width="33%">CD3</td>
						<td width="33%">1333</td>	    
				   </tr>
				   <tr>
						<td width="33%">3990000</td>
						<td width="33%">CD5</td>
						<td width="33%">1335</td>	    
				   </tr>
				   <tr>
						<td width="33%">3990000</td>
						<td width="33%">CD2</td>
						<td width="33%">1332</td>	    
				   </tr>
				   <tr>
						<td width="33%">3990000</td>
						<td width="33%">CD1</td>
						<td width="33%">1331</td>	    
				   </tr>

				</table>

				<br><br>
				<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
				  <tr> 
					  	<th width="33%" >Loc. Origem</th>
					    <th width="33%" >Destino</th>
					    <th width="33%" >TOP</th>
				   </tr>

				   <tr>
						<td width="33%">5990000</td>
						<td width="33%">CD3</td>
						<td width="33%">1353</td>	    
				   </tr>
				   <tr>
						<td width="33%">5990000</td>
						<td width="33%">CD5</td>
						<td width="33%">1355</td>	    
				   </tr>
				   <tr>
						<td width="33%">5990000</td>
						<td width="33%">CD2</td>
						<td width="33%">1352</td>	    
				   </tr>
				   <tr>
						<td width="33%">5990000</td>
						<td width="33%">CD1</td>
						<td width="33%">1351</td>	    
				   </tr>

				</table>

								<br><br>
				<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
				  <tr> 
					  	<th width="33%" >Loc. Origem</th>
					    <th width="33%" >Destino</th>
					    <th width="33%" >TOP</th>
				   </tr>

				   <tr>
						<td width="33%">2019999</td>
						<td width="33%">CD2</td>
						<td width="33%">1322</td>	    
				   </tr>
				   <tr>
						<td width="33%">2019999</td>
						<td width="33%">CD3</td>
						<td width="33%">1323</td>	    
				   </tr>
				   <tr>
						<td width="33%">2019999</td>
						<td width="33%">CD5</td>
						<td width="33%">1325</td>	    
				   </tr>
				   <tr>
						<td width="33%">2019999</td>
						<td width="33%">CD1</td>
						<td width="33%">1321</td>	    
				   </tr>

				</table>

			</div>
		</div> <!-- POP UP para Editar Produtos -->

	<a href="../menu.php"><aabr title="Voltar para Menu"><img style="width: 40px; margin-top: 10px; margin-left: 10px; position: fixed;" src="images/Seta Voltar.png" /></aabr></a>

	<button class="abrirObservacao" onclick="abrirObservacao();">Abrir Obs.</button>

		<div class="container">
			<div class="conteudo">
				<!-- <form action="index.php" method="post"> -->

					<div style="display: flex; flex-direction: column;">
						
						<label for="nunota">Número Único:</label>
						<input type="text" name="NUNOTA" id="nunota" class="text">
					
						<br>
						<label for="codtipoper">Loc. Origem: <span id="localorigem"></span> 
						</label> 


						<br>
					    <label for="codtipoper">TOP:</label>
						<input type="text" name="CODTIPOPER" id="codtipoper" class="text">		
					
						<br>
						<input name="aplicar" id="aplicar" type="submit" value="Confirmar">

				    </div>

				<!-- </form> -->

			</div>
		</div> <!-- Container -->


		<script>
			function confirmaNumeroUnico(nunota, codtipoper)
				{
					//O método $.ajax(); é o responsável pela requisição
					$.ajax
					({
						//Configurações
						type: 'POST',//Método que está sendo utilizado.
						dataType: 'html',//É o tipo de dado que a página vai retornar.
						url: 'action.php',//Indica a página que está sendo solicitada.
						//função que vai ser executada assim que a requisição for enviada
						beforeSend: function () {
							$("#loader").show();
						},
						complete: function(){
							$("#loader").hide();
						},
						data: {nunota: nunota, codtipoper: codtipoper},//Dados para consulta
						//função que será executada quando a solicitação for finalizada.
						success: function (msg)
						{
							//alert(msg);
							if(msg.length <= 10){
								window.location.href='insereestoque.php?nunota=' + msg;
							} else {
								alert(msg);
							}

							//document.getElementById("localorigem").textContent = "teste";
							// document.getElementById("quantidade").value = "";
							//window.location.href='listaconferenciaadmin.php';
							
						}
					});
			}

			$('#aplicar').click(function () {
                confirmaNumeroUnico($("#nunota").val(), $("#codtipoper").val())
            });

			 document.getElementById("codtipoper").addEventListener("focus", function() {

			 		var campoBusca = document.getElementById("nunota").value;
					var resultadoSpan = $("#resultado");
					var outroCampo = document.getElementById("codtipoper");
					var resultadoVariavel = "";

				    retornalocal(campoBusca);

			     	//document.getElementById("localorigem").textContent = "teste";
			 },{ once: true });


			  function retornalocal(valor)
				{
				    $.ajax
					({
						//Configurações
						type: 'POST',//Método que está sendo utilizado.
						dataType: 'html',//É o tipo de dado que a página vai retornar.
						url: 'retornalocalorigem.php',//Indica a página que está sendo solicitada.
						//função que vai ser executada assim que a requisição for enviada
						beforeSend: function () {
							$("#loader").show();
						},
						complete: function(){
							$("#loader").hide();
						},
						data: {valor: valor},//Dados para consulta
						//função que será executada quando a solicitação for finalizada.
						success: function (msg)
						{
							//alert(msg);
							document.getElementById("localorigem").textContent = msg;
							
						}
					});
				}


		</script>
</body>
</html>