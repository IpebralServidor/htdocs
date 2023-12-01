<?php 
    include "../conexaophp.php";
    require_once '../App/auth.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema reabastecimento</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link href="style.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

</head>
<body class="body">
    <div class="page">
        <div class="header">
            <div class="img-voltar">
                <a href="../menu.php">
                    <img src="images/216446_arrow_left_icon.png" />
                </a>
            </div>
            <div>
                <!-- <span>Disparar mensagem de TICKETS</span> -->
            </div>
        </div>
        <div class="page-form">
           <div class="form">
                <form>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Usuário:</label>
                        <input type="text" name="username" id="username" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Digite seu usuário">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Senha:</label>
                        <input type="password" name="password" id="password" class="form-control" id="exampleInputPassword1" placeholder="Senha">
                    </div>
                  
                    <button type="submit" id="enviar" class="btn btn-primary btn-ipb">Entrar</button>
                </form>
           </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script>
        function validarLogin(usuario, senha)
		{
			//O método $.ajax(); é o responsável pela requisição
			$.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'validarLogin.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#iniciarpausa").html("Carregando...");
                },
                data: {usuario: usuario, senha: senha},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    if(msg == "Concluido"){
                        window.location="manutencao.html";
                    }else{
                        alert(msg)
                    }
                }
            });
		}
    </script>
    <script>
        $('#enviar').click(function () {
            validarLogin($("#username").val(), $("#password").val())
        });
    </script>
</body>
</html>

