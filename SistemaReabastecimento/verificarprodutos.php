<?php
include "../conexaophp.php";
require_once '../App/auth.php';

$nunota2 = $_REQUEST["nunota"];

$tsql2 = "SELECT * FROM [sankhya].[AD_FNT_PRODUTO_SEPARADO_REABASTECIMENTO] ($nunota2) ORDER BY CODLOCALORIG DESC, SEQUENCIA DESC"; 
$stmt2 = sqlsrv_query( $conn, $tsql2); 

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/main.css" rel='stylesheet' type='text/css' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
	<title>Estoque CD3</title>
</head>
<body>

    <div class="modal fade" id="editarQuantidade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar quantidade</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="qtd">Quantidade: </label>
                    <input type="number" id="qtd" name="qtd" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="botao-editar btn btn-primary" id="btnEditarQuantidade">Editar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="buscarUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Buscar usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex flex-wrap">
                    <label for="qtd">Usuário: </label>
                    <input type="number" id="usu" name="usu" value="" style="width: 50px; margin-right: 10px;">
                    <button class="buscar-usuario">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                    <input type="number" id="nomeusu" name="nomeusu" value="" style="width: 140px; margin-left: 10px;" disabled>
                </div>
                <div class="modal-footer">
                    <button type="button" class="botao-entregar btn btn-primary" id="btnEntregar">Entregar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="loader" style="display: none;">
        <img style=" width: 150px; margin-top: 5%;" src="images/soccer-ball-joypixels.gif">
    </div>
    <div class="container">
        
        <div class="content">

            <div class="img-voltar btn-reabastecimento">
                <a href="../menu.php" class="btn btn-back">
                    <aabr title="Voltar para Menu">
                        <img src="images/216446_arrow_left_icon.png" />
                    </aabr>
                </a>
            </div>

            <button type="submit" name="confirmar" id="confirmar" class="btn btn-primary btn-form">Confirmar nota</button><br><br>

            <div class="table d-flex justify-content-center">
                <table>
                    <tr> 
                        <th>Ref.</th>
                        <th>Local</th>
                        <th>Qtde</th>
                        <!-- <th></th> -->
                        <th></th>
                    </tr>

                    <?php 
                        while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))  
                            { 
                    ?>
                    <tr> 
                        <td><?php echo $row2['REFERENCIA'] ?></td>
                        <td><?php echo $row2['CODLOCALPAD'] ?></td>
                        <td><?php echo $row2['QTDNEG'] ?></td>
                        <!-- <td>
                            <a class="botaoAbrirPopUp" data-id="<?php echo $row2['SEQUENCIA'] ?>">
                                <button class="btnPendencia" data-toggle="modal" data-target="#editarQuantidade">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                    </svg>
                                </button>
                            </a>
                        </td> -->
                        <td>
                            <a class='botao-abastecer' data-id="<?php echo $row2['SEQUENCIA'];?>">
                                <button class="btnPendencia" data-toggle="modal" data-target="#buscarUsuario">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive-fill" viewBox="0 0 16 16">
                                        <path d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15h9.286zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1zM.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8H.8z"/>
                                    </svg>
                                </button>
                            </a>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script>
    
        function confrimarNota (nunota)
            {
                //O método $.ajax(); é o responsável pela requisição
                $.ajax
                ({
                    //Configurações
                    type: 'POST',//Método que está sendo utilizado.
                    dataType: 'html',//É o tipo de dado que a página vai retornar.
                    url: 'confirmarnota.php',//Indica a página que está sendo solicitada.
                    //função que vai ser executada assim que a requisição for enviada
                    beforeSend: function () {
                        $("#loader").show();
                    },
                    complete: function(){
                        $("#loader").hide();
                    },
                    data: {nunota: nunota},//Dados para consulta
                    //função que será executada quando a solicitação for finalizada.
                    success: function (msg)
                    {
                        if(msg == 'Concluido'){
                            alert(msg);
                            window.location.href="index.php";
                        }else{
                            alert(msg);
                        }
                        
                    }
                });
            }
            $('#confirmar').click(function () {
                confrimarNota(<?php echo $nunota2; ?>)
            });
    </script>
    <script>
        // Obtém o botão "Abrir Pop-up" e o pop-up
        document.addEventListener('DOMContentLoaded', function() {
            var botoesAbrirPopUp = document.querySelectorAll(".botaoAbrirPopUp");
            var meuPopUp = document.getElementById("editarQuantidade");
            var botaoDentroDoPopUp = meuPopUp.querySelector("#btnEditarQuantidade");

            // Adicione um ouvinte de eventos para cada botão
            botoesAbrirPopUp.forEach(function(botao) {
                botao.addEventListener("click", function() {
                    // Obtém o valor do atributo data-id do botão clicado
                    var dataId = this.getAttribute('data-id');
                    // Define o valor em um atributo personalizado do botão dentro do pop-up
                    botaoDentroDoPopUp.setAttribute('data-id-pop-up', dataId);

                    // Abre o pop-up
                    meuPopUp.style.display = "block";
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var botaoEditar = document.querySelectorAll('.botao-editar');
            var inputTexto = document.getElementById("qtd");
            
            botaoEditar.forEach(function(botao) {
                botao.addEventListener('click', function() {
                    var sequencia = botao.getAttribute('data-id-pop-up');
                    var valorTexto = inputTexto.value;
                    
                    alterarQuantidade(<?php echo $nunota2; ?>, sequencia, valorTexto);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var botoesAbrirPopUp = document.querySelectorAll(".botao-abastecer");
            var meuPopUp = document.getElementById("buscarUsuario");
            var botaoDentroDoPopUp = meuPopUp.querySelector("#btnEntregar");

            // Adicione um ouvinte de eventos para cada botão
            botoesAbrirPopUp.forEach(function(botao) {
                botao.addEventListener("click", function() {
                    // Obtém o valor do atributo data-id do botão clicado
                    var dataId = this.getAttribute('data-id');
                    // Define o valor em um atributo personalizado do botão dentro do pop-up
                    botaoDentroDoPopUp.setAttribute('data-id-pop-up', dataId);

                    // Abre o pop-up
                    meuPopUp.style.display = "block";
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var botaoEditar = document.querySelectorAll('.botao-entregar');
            var codUsu = document.getElementById('usu');

            botaoEditar.forEach(function(botao) {
                botao.addEventListener('click', function() {
                    var sequencia = botao.getAttribute('data-id-pop-up');
                    var codusu = codUsu.value;
                    
                    abastecerGondola(<?php echo $nunota2; ?>, sequencia, codusu);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var botaoBuscar = document.querySelectorAll('.buscar-usuario');
            var inputTexto = document.getElementById("usu");

            botaoBuscar.forEach(function(botao) {
                botao.addEventListener('click', function() {
                    var valorTexto = inputTexto.value;
                    
                    buscarUsuario(valorTexto);
                });
            });
        });

        
    </script>
    <script>
        function buscarUsuario(codusu)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'buscarusuario.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                
                data: {codusu: codusu},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    var retorno = msg.split("|");

                    // alert(retorno[1]);

                    document.getElementById("nomeusu").placeholder = retorno[1];
                }
            });
        }
        $('#buscar-usuario').click(function () {
            buscarUsuario($("#codusuinput").val())
        });
    </script>
    <script>
        function alterarQuantidade(nunota, sequencia, quantidade)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'alterarquantidade.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: {nunota: nunota, sequencia: sequencia, quantidade: quantidade},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    if(msg == 'Concluido'){
                        location.reload();
                    }else{
                        alert(msg);
                    }                 
                }
            });
        }
    </script>
    <script>
        function abastecerGondola(nunota, sequencia, codusu)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'abastecergondola.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: {nunota: nunota, sequencia: sequencia, codusu: codusu},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    if(msg == 'Concluido'){
                        location.reload();
                    }else{
                        alert(msg);
                    }                 
                }
            });
        }
    </script>
</body>

</html>