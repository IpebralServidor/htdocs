<?php
include "../conexaophp.php";
require_once '../App/auth.php';

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<title>Estoque CD3</title>
</head>
<body>
	<div id="loader" style="display: none;">
        <img style=" width: 150px; margin-top: 5%;" src="images/soccer-ball-joypixels.gif">
    </div>

	<!-- pop up LOGIN e CADASTRO -->
	<div class="popup" id="popConfirmar">
		<div class="overlay"></div>
			<div class="content">
				<div style="padding: 20px; width: 100%;">
					<div class="close-btn" onclick="abrir()">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
							<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
						</svg>
					</div>
					
					<div class="div-form">
						<div id="form_confirmar" class="form">
							<span>Esta é uma nota de separação, deseja criar uma nota de abastecimento para ela?</span><br>							
							<button name="confirmar" id="btn-confirmar" onclick="Confirmar()">Confirmar</button>
						</div>
					</div>
				</div>
				
			</div>
		</div>
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
					<label for="referencia" class="label-input">Número da nota:</label>
                    <input type="number" class="form-control margin-top10" id="numeroNota" name="numeroNota">
				</div>

				<button onclick="abrir()" name="aplicar" class="btn btn-primary btn-form margin-top35">Pesquisar</button>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<script>
		var input = document.getElementById('numeroNota')

		function abrir() {
			if(input.value != ""){

				$.ajax
				({
					type: 'POST',//Método que está sendo utilizado.
					dataType: 'html',//É o tipo de dado que a página vai retornar.
					url: 'buscarcodtipoper.php',//Indica a página que está sendo solicitada.
					async: false,
					beforeSend: function () {
						$("#loader").show();
					},
					complete: function(){
						$("#loader").hide();
					},
					data: {numeroNota: input.value},//Dados para consulta
					//função que será executada quando a solicitação for finalizada.
					success: function (msg)
					{
						if(msg.substring(0,2) == 13){
							$.ajax
							({
								type: 'POST',//Método que está sendo utilizado.
								dataType: 'html',//É o tipo de dado que a página vai retornar.
								url: 'buscarvinculo.php',//Indica a página que está sendo solicitada.
								async: false,
								beforeSend: function () {
									$("#loader").show();
								},
								complete: function(){
									$("#loader").hide();
								},
								data: {numeroNota: input.value},//Dados para consulta
								//função que será executada quando a solicitação for finalizada.
								success: function (msg)
								{
									if(msg == 0){
										document.getElementById('popConfirmar').classList.toggle("active");
									}else {
										Confirmar()
									}
								}
							});
						}else{
							alert('Esta nota não é uma transferência')
						}
					}
				});
				
				
			}else{
				alert('Preencha uma nota válida!')
			}
		}

		function Confirmar()
        {
			// if(substr($rowEhTransf[0], 0, -2) == "13"){
				$.ajax
				({
					type: 'POST',//Método que está sendo utilizado.
					dataType: 'html',//É o tipo de dado que a página vai retornar.
					url: 'action.php',//Indica a página que está sendo solicitada.
					beforeSend: function () {
                    	$("#loader").show();
					},
					complete: function(){
						$("#loader").hide();
					},
					data: {numeroNota: input.value},//Dados para consulta
					//função que será executada quando a solicitação for finalizada.
					success: function (msg)
					{
						let res = msg.split('/')

						if(res[0] == "A"){
							if(res[1] == "TRANSF_NOTA"){
								window.location.href = 'reabastecimentotransfA.php?nunota=' +input.value +'&fila=N'
							}else{
								window.location.href = 'menuseparacao.php?nunota=' +input.value
							}
						}else if(res[1] == "TRANSF_CD5"){
							window.location.href = 'reabastecimento.php?nunota=' +input.value +'&fila=N'
						}else{
							window.location.href = 'reabastecimento.php?nunota=' +input.value +'&fila=S'
						}
					}
				});
			// }else{
				
			// }
            
        }
	</script>
</body>
</html>