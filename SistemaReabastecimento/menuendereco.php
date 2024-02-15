<?php
include "../conexaophp.php";
require_once '../App/auth.php';


$_SESSION['semFila'] = 'N';
$nunota2 = $_REQUEST["nunota"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer" >
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

</head>
<body>
    <div class="popup" id="popAlterarSenha">
		<div class="overlay"></div>
			<div class="content">
				<div style="width: 100%;">
					<div class="close-btn" onclick="abrir()">
						<i class="fa-solid fa-xmark"></i>	
						<!-- <i class="fa-solid fa-circle-xmark"></i> -->
					</div>
					
					<div class="div-form">
						<div id="form_alterasenha"  class="form">
							<label> Endereço inicial:</label>
							<input type="number" name="senha_alt" id="senha_alt" required>
							
							<label> Endereço final:</label>
							<input type="number" name="senha_conf" id="senha_conf" required>
							
							<button name="AlteraSenha" id="btn-alterasenha">Confirmar</button>
                        </div>
					</div>
				</div>
				
			</div>
		</div>
	</div>


	<div>
		<div class="img-voltar">
			<a href="index.php">
				<img src="images/216446_arrow_left_icon.png" />
			</a>
		</div>
		<div class="screen">
			<div class="margin-top35" style="width: 80%;">
                <button type="submit" id="aplicar" name="aplicar" class="btn btn-primary btn-form">Separar a nota inteira</button><br><br>
                <button type="submit" id="aplicar-sem-fila" name="aplicar-sem-fila" class="btn btn-primary btn-form">Pegar endereços específicos</button><br><br>
            </div>
		</div>
	</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
<script src="https://kit.fontawesome.com/9c65c9f9d0.js" crossorigin="anonymous"></script>
<script>
    function abrirNota(nunota, fila)
    {
        //O método $.ajax(); é o responsável pela requisição
        $.ajax
        ({
            //Configurações
            type: 'POST',//Método que está sendo utilizado.
            dataType: 'html',//É o tipo de dado que a página vai retornar.
            // url: 'actionabastecimento.php',//Indica a página que está sendo solicitada.
            //função que vai ser executada assim que a requisição for enviada
            data: {fila: fila, nunota: nunota},//Dados para consulta
            success: function (msg)
            {
                if(fila == 'S'){
                    <?php 
                        $_SESSION['enderecoInit'] = 0;
                        $_SESSION['enderecoFim'] = 0;
                    ?>
                    window.location.href='reabastecimento.php?nunota= <?php echo $nunota2 ?>&fila='+fila;
                }else{

                    let enderecoInit = document.getElementById('senha_alt').value
                    let enderecoFim = document.getElementById('senha_conf').value

                    $.ajax
                    ({
                        type: 'POST',//Método que está sendo utilizado.
                        dataType: 'html',//É o tipo de dado que a página vai retornar.
                        url: 'armazenarendereco.php',//Indica a página que está sendo solicitada.
                        data: {enderecoInit: enderecoInit, enderecoFim: enderecoFim},//Dados para consulta
                        //função que será executada quando a solicitação for finalizada.
                        success: function (msg)
                        {
                            window.location.href='reabastecimento.php?nunota= <?php echo $nunota2 ?>&fila=S';
                        }
                    });
                }
            }
        });
    }

    function abrir() {
        document.getElementById('popAlterarSenha').classList.toggle("active");
    }

    $('#aplicar').click(function () {
        abrirNota(<?php echo $nunota2;?>, 'S')
    });

    $('#aplicar-sem-fila').click(function () {
        document.getElementById('popAlterarSenha').classList.toggle("active");
    });

    $('#btn-alterasenha').click(function () {
        abrirNota(<?php echo $nunota2;?>, 'N')
    });

</script>
</html>