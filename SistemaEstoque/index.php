<?php
include "../conexaophp.php";
require_once '../App/auth.php';


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/main.css?v=<?php time()?>">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

		<div id="popupEscolherNota" class="popupEscolherNota">
			<button class="fechar" id="fechar" onclick="fecharEscolherNota();">X</button>
			<div style="width: 95%; height:95%;" id="escolherNota"></div>
		</div>

		<div id="popupCriaNotaTransf" class="popupCriaNotaTransf">
			<button class="fechar" id="fechar" onclick="fecharCriarNotaTransf();">X</button>
			<div style="width: 95%; height:95%;" id="criarNota"></div>
		</div> 

		<div>
			<div class="img-voltar">
				<a href="../menu.php">
					<img src="images/216446_arrow_left_icon.png" />
				</a>
			</div>

			<div class="screen">
				<div class="margin-top35" style="width: 80%;">
					<div class="form-group">
						<label for="nunota">Número Único: (Origem)</label>
						<input type="number" name="NUNOTA" id="nunota" class="form-control margin-top10">
						<!-- <input type="text" name="CODTIPOPER" id="codtipoper" class="form-control margin-top10" style="display: none;">		  -->
					</div>

					<button id="abrirNotabtn" class="btn btn-primary btn-form margin-top35" value="Abrir">Abrir</button>
					<button class="btn btn-primary btn-form margin-top35" id="gerarNovaTransf" value="gerarNovaTransf	">Gerar Nova Transf.</button>
				</div>
			</div>
		</div> 


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