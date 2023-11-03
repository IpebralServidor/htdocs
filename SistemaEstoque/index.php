<?php
include "../conexaophp.php";
require_once '../App/auth.php';


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/main.css?v=2">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->

	<title>Estoque CD3</title>


	<script type="text/javascript">

		function abrirEscolherNota(){
			document.getElementById('popupEscolherNota').style.display = 'flex';
		}
		function fecharEscolherNota(){
			document.getElementById('popupEscolherNota').style.display =  'none';
		}

		function abrirCriarNotaTransf(){
			document.getElementById('popupCriaNotaTransf').style.display = 'flex';
		}
		function fecharCriarNotaTransf(){
			document.getElementById('popupCriaNotaTransf').style.display =  'none';
		}

	</script>
</head>
<body>

	<div id="loader" style="display: none;">
		<img style=" width: 150px; margin-top: 5%;" src="../images/soccer-ball-joypixels.gif">
	</div>


		<!-- POP UP para Escolher Notas de Transferência que já tiverem sido criadas-->
		<div id="popupEscolherNota" class="popupEscolherNota">
			<button class="fechar" id="fechar" onclick="fecharEscolherNota();">X</button>
			
			<div style="width: 95%; height:95%;" id="escolherNota">


			</div>
		</div> <!-- Fim POPUP para Escolher Nota-->


		<!-- POP UP para gerar nova nota de Transferência-->
		<div id="popupCriaNotaTransf" class="popupCriaNotaTransf">
			<button class="fechar" id="fechar" onclick="fecharCriarNotaTransf();">X</button>
			
			<div style="width: 95%; height:95%;" id="criarNota">


			</div>
		</div> <!-- Fim POPUP que gera nova transferência-->


	<!-- <button class="abrirObservacao" onclick="abrirObservacao();">Abrir Obs.</button> -->

		<div class="container">
			<div class="conteudo">

					<!-- <div style="display: flex; flex-direction: column;"> -->
						
						<!-- Botão de Voltar para o Menu -->
					    <div class="img-voltar btn-reabastecimento">
					        <a href="../menu.php" class="btn btn-back">
					            <aabr title="Voltar para Menu">
					                <img src="images/216446_arrow_left_icon.png" />
					            </aabr>
					        </a>
				        </div>
				        <!-- Fim do botão de Voltar para o Menu -->

						<label for="nunota">Número Único: (Origem)</label>
						<input type="text" name="NUNOTA" id="nunota" class="form-control">
					
		<!-- 				<br>
						<label for="codtipoper">Loc. Origem: <span id="localorigem"></span> 
						</label>  -->


						<br>
					    <!-- <label for="codtipoper">TOP:</label> Lembrar de comentar tambem a parte do input que esta abaixo-->
						<input type="text" name="CODTIPOPER" id="codtipoper" class="form-control" style="display: none;">		 
					
						<br>
						<button id="abrirNotabtn" class="btn btn-primary btn-form" value="Abrir">Abrir</button>
						<br>
						<button class="btn btn-primary btn-form" id="gerarNovaTransf" value="gerarNovaTransf	">Gerar Nova Transf.</button>

				    <!-- </div> -->

			</div> <!-- Conteudo -->
		</div> <!-- Container -->


	<script>

		function criaNotaTransf(nunota, cddestino, usuario)
			{
				//O método $.ajax(); é o responsável pela requisição
				$.ajax
				({
					//Configurações
					type: 'POST',//Método que está sendo utilizado.
					dataType: 'html',//É o tipo de dado que a página vai retornar.
					url: 'crianotatransf.php',//Indica a página que está sendo solicitada.
					//função que vai ser executada assim que a requisição for enviada
					beforeSend: function () {
						$("#loader").show();
					},
					complete: function(){
						$("#loader").hide();
					},
					data: {nunota: nunota, cddestino: cddestino, usuario: usuario},//Dados para consulta
					//função que será executada quando a solicitação for finalizada.
					success: function (msg)
					{
						//alert(msg);
						if(msg.length <= 10){
							abrirNota(msg);
						// 	window.location.href='insereestoque.php?nunota=' + msg;
						} else {
						   alert(msg);
						}

						//document.getElementById("localorigem").textContent = "teste";
						// document.getElementById("quantidade").value = "";
						//window.location.href='listaconferenciaadmin.php';
						
					}
				});
		}

		// $('#criaNotabtn').click(function () {
	    // 	alert('teste');
		// });

		//Recarrega o POP UP de Escolher a nota
		function retornaEscolherNota(nunota)
	    {
	        //O método $.ajax(); é o responsável pela requisição
	        $.ajax
	                ({
	                    //Configurações
	                    type: 'POST',//Método que está sendo utilizado.
	                    dataType: 'html',//É o tipo de dado que a página vai retornar.
	                    url: 'retornaescolhernota.php',//Indica a página que está sendo solicitada.
	                    //função que vai ser executada assim que a requisição for enviada
	                    beforeSend: function () {
	                        $("#escolherNota").html("Carregando...");
	                    },
	                    data: {nunota: nunota},//Dados para consulta
	                    //função que será executada quando a solicitação for finalizada.
	                    success: function (msg)
	                    {
	                    	//alert("teste");
	                        $("#escolherNota").html(msg);
	                    }
	                });
	    }

	    $('#abrirNotabtn').click(function () {
	    	abrirEscolherNota();
	    	retornaEscolherNota($("#nunota").val());
		});



	    //Recarrega o POP UP de Criação de Notas
		function retornaCriarNota(nunota)
	    {
	        //O método $.ajax(); é o responsável pela requisição
	        $.ajax
	                ({
	                    //Configurações
	                    type: 'POST',//Método que está sendo utilizado.
	                    dataType: 'html',//É o tipo de dado que a página vai retornar.
	                    url: 'retornacriarnota.php',//Indica a página que está sendo solicitada.
	                    //função que vai ser executada assim que a requisição for enviada
	                    beforeSend: function () {
	                        $("#criarNota").html("Carregando...");
	                    },
	                    data: {nunota: nunota},//Dados para consulta
	                    //função que será executada quando a solicitação for finalizada.
	                    success: function (msg)
	                    {
	                        $("#criarNota").html(msg);
	                    }
	                });
	    }

	    $('#gerarNovaTransf').click(function () {
	    	abrirCriarNotaTransf();
	    	retornaCriarNota($("#nunota").val());
		});


	</script>

	<!-- Função para abrir a nota -->
	<script type="text/javascript">

		function abrirNota(nota){
			window.location.href='insereestoque.php?nunota=' + nota;

			// alert("teste");
		}

	</script>
	<!-- Fim da função para abrir a nota -->


	<!-- Cria a nota de transferência, baseado no botão do CD que foi criado -->
	<script type="text/javascript">
		function criaNota(nunota, cddestino, usuario){
			result = confirm("Tem certeza que deseja criar a transferência para o CD" + cddestino + "?");
			if(result){
				criaNotaTransf(nunota, cddestino, usuario);	
				//abrirNota(nunota);
			}
			
		}
	</script>
	<!-- Fim cria a nota de transferência, baseado no botão do CD que foi criado -->

</body>
</html>