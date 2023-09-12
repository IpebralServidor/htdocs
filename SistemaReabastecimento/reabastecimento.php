<?php 
    include "../conexaophp.php";
    require_once '../App/auth.php';

    $corTipoNota = '';

    $nunota2 = $_REQUEST["nunota"];
    $codusu = $_SESSION["idUsuario"];

    $tsqlStatus = "SELECT [sankhya].[AD_FN_RETORNA_STATUS_NOTA]($nunota2)";
	$stmtStatus = sqlsrv_query( $conn, $tsqlStatus);
	$rowStatus = sqlsrv_fetch_array( $stmtStatus, SQLSRV_FETCH_NUMERIC);
    $varStatus = $rowStatus[0];

    $tsqlTimer = "SELECT (SUM(DATEDIFF(sECOND, ISNULL(DTFIM,gETDATE()),DTINIC)) *-1) FROM AD_TGFAPONTAMENTOATIVIDADE WHERE NUNOTA = $nunota2";
	$stmtTimer = sqlsrv_query( $conn, $tsqlTimer);
	$rowTimer = sqlsrv_fetch_array( $stmtTimer, SQLSRV_FETCH_NUMERIC);
	$_SESSION['time']= $rowTimer[0];
    
    $tsqlTransferencia = "SELECT * FROM [sankhya].[AD_FNT_PROXIMO_PRODUTO_REABASTECIMENTO] ($nunota2)";
    $stmtTransferencia = sqlsrv_query( $conn, $tsqlTransferencia);
    $rowTransferencia = sqlsrv_fetch_array( $stmtTransferencia, SQLSRV_FETCH_ASSOC);
    $sequencia = $rowTransferencia['SEQUENCIA'];
    $referencia = $rowTransferencia['REFERENCIA'];
    $codprod = $rowTransferencia['CODPROD'];

    $tsqlNota = "SELECT OBSERVACAO FROM TGFCAB WHERE NUNOTA = $nunota2";
    $stmtNota = sqlsrv_query( $conn, $tsqlNota);
    $rowNota = sqlsrv_fetch_array( $stmtNota, SQLSRV_FETCH_ASSOC);

    $tsql2 = "SELECT * FROM [sankhya].[AD_FNT_PRODUTO_SEPARADO_REABASTECIMENTO] ($nunota2) ORDER BY CODLOCALORIG DESC, SEQUENCIA DESC"; 
    $stmt2 = sqlsrv_query( $conn, $tsql2);  



    if($rowStatus[0] == "A"){
        $colorStatus = "green";
        $valueStatus = "Em andamento";
        $valueF = "Iniciar pausa";
        $class ="pause";
        
    }else if($rowStatus[0] == "P"){
        $colorStatus = "yellow";
        $valueStatus = "Em pausa";
        $valueF = "Finalizar pausa";
        $class ="play";
    }

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
    <link href="css/main.css" rel='stylesheet' type='text/css' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

</head>
<body class="body">
    <div id="loader" style="display: none;">
        <img style=" width: 150px; margin-top: 5%;" src="images/soccer-ball-joypixels.gif">
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Observação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo 'Observação da nota: ' .$rowNota['OBSERVACAO'] ?><br><br>
                    <?php echo 'Observação do item: ' .$rowTransferencia['OBSERVACAO'] ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="otroLocalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Qual o motivo para procurar em outro local?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input type="radio" id="nao_encontrado" name="fav_language" value="produto não foi encontrado">
                    <label for="nao_encontrado">Produto não foi encontrado</label><br>
                    
                    <input type="radio" id="nao_existe" name="fav_language" value="produto não existe">
                    <label for="nao_existe">Produto não existe</label><br>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnOutroLocal">Aplicar</button>
                </div>
            </div>
        </div>
    </div>

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

    <div class="bg">
        <div class="collapse" id="navbarToggleExternalContent">
            <div class="table d-flex justify-content-center">
                <table>
                    <tr> 
                        <th>Ref.</th>
                        <th>Local</th>
                        <th>Qtde</th>
                        <th></th>
                        <th></th>
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
                        <td>
                            <a id="botaoAbrirPopUp" data-id="<?php echo $row2['SEQUENCIA'] ?>">
                                <button class="btnPendencia" data-toggle="modal" data-target="#editarQuantidade">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                    </svg>
                                </button>
                            </a>
                        </td>
                        <td>
                            <a class='botao-abastecer' data-id="<?php echo $row2['SEQUENCIA'];?>">
                                <button class="btnPendencia">
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
        
        <div class="d-flex justify-content-between" style="background-color: #3a6070 !important;">
            <nav class="bg navbar">
                <a class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-square" viewBox="0 0 16 16">
                        <path d="M3.626 6.832A.5.5 0 0 1 4 6h8a.5.5 0 0 1 .374.832l-4 4.5a.5.5 0 0 1-.748 0l-4-4.5z"/>
                        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2z"/>
                    </svg>
                </a>
            </nav>

            <!-- TIMER -->
            <div>
                <button class="<?php echo $class ?> btnPendencia" id="btnStatus">
                    <?php echo $valueF ?>
                </button>
            </div>


            <?php 
                if(utf8_encode($rowTransferencia['TIPO_NOTA']) == "Separação"){
                    $corTipoNota = "#9c95ff;";
                }else{
                    $corTipoNota = "#ff9595;";
                }
            ?>

            <button class="statusReabastecimento" style=" background-color: <?php echo $corTipoNota; ?> !important;">
                <?php echo utf8_encode($rowTransferencia['TIPO_NOTA']); ?>
            </button>
        </div>
    </div>

    <div class="container">

        <div class="header-body">

            <div class="header-body-left">

                <div>
                    <span class="timer-color" style="background-color: <?php echo $colorStatus ?>;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <h6 id="result_shops"></h6> 
                </div>
                
                <div class="d-flex justify-content-center align-items-center">
                    <div class="input-h6">
                        <h6>Endereço:</h6> 
                    </div>
                    <input type="number" name="endereco" id="endereco" class="form-control" placeholder="<?php echo $rowTransferencia['CODLOCALORIG']; ?>"> 
                    
                </div>

                <div class="d-flex justify-content-center align-items-center">
                    <div class="input-h6">
                        <h6>Referência:</h6> 
                    </div>
                    <input type="text" name="referencia" id="referencia" class="form-control" placeholder="<?php echo $rowTransferencia['REFERENCIA']; ?>"> 
                </div>
            
                <div class="d-flex justify-content-center align-items-center">
                    <div class="input-h6">
                        <h6>Quantidade:</h6>
                    </div>
                    <input type="number" name="qtdneg" id="qtdneg" class="form-control" required placeholder=" <?php echo $rowTransferencia['QTDNEG']; ?>">
                </div> 

                <h6>Agp. min: <?php echo $rowTransferencia['AGRUPMIN']; ?></h6>     
                
                <div class="d-flex justify-content-start">
                    <h6 id="qtdLocal">Qtd Local: <?php echo $rowTransferencia['QTDLOCAL']; ?>&nbsp / &nbsp</h6>
                    <h6 id="informacaoAtualizada">0</h6> 
                </div>
                
                <h6>Max. loc. padrão: <?php echo $rowTransferencia['AD_QTDMAXLOCAL']; ?></h6>
                <h6>Est. loc. padrão: <?php echo $rowTransferencia['QTDLOCAL']; ?></h6>
                <h6>Med. venda: <?php echo $rowTransferencia['MEDIA']; ?></h6>
               
            </div>

        </div>

        <div class="image d-flex justify-content-center">
            <?php
                $referencia = $rowTransferencia['REFERENCIA'];

                if($referencia!=''){
                    $tsql2 = "SELECT [sankhya].[AD_FN_IMAGEM_PRODUTO_PHP] ('$referencia')";
                } else {
                    $tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000 ";
                }

                $stmt2 = sqlsrv_query( $conn, $tsql2);

                if($stmt2){
                    $row_count = sqlsrv_num_rows( $stmt2 ); 

                    while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
                    {
                        echo '<img style="vertical-align: middle; margin: auto; max-width: 100%; max-height: 166px;" src="data:image/jpeg;base64,'.base64_encode($row2[0]).'"/>';
                    }
                } 
            ?> 
        </div>

        <div class="btn-proximo-abast">
            <button id="proximo" name="proximo" class="btn btn-primary btn-form">Próximo</button>
        </div>  
        
    </div>
    <footer class="footer d-flex justify-content-center">
        <button class="btnWidth btnPendencia " data-toggle="modal" data-target="#exampleModal">
            Observação
        </button>
        <button class="btnWidth btnPendencia btnOutroLocal" data-toggle="modal" data-target="#otroLocalModal">
            Procurar em outro local
        </button>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        sessionStorage.setItem('status', '<?php echo $rowStatus[0] ?>');

        $(document).ready(function(){

            if(sessionStorage.getItem('status') == 'P') 
            {
                $("#result_shops").load('time.php');
            }

            var t = window.setInterval(function() 
            {
                if(sessionStorage.getItem('status') == 'A') 
                {
                    $("#result_shops").load('time.php');
                }
                
            }, 1000);

        });
    </script>
    <script>

        // Obtém referências para os elementos HTML
        var inputTexto = document.getElementById('qtdneg');
        var informacaoAtualizada = document.getElementById('informacaoAtualizada');
        var qtdLocal = <?php echo $rowTransferencia['QTDLOCAL']; ?>;

        // Adiciona um ouvinte de evento 'input' ao campo de entrada
        inputTexto.addEventListener('input', function() {
            // Obtém o valor atual do campo de entrada
            var texto = inputTexto.value;
            // Atualiza a informação em tempo real
            informacaoAtualizada.textContent = ' ' +qtdLocal - texto
        });

        // Obtém o botão "Abrir Pop-up" e o pop-up
        var botaoAbrirPopUp = document.getElementById("botaoAbrirPopUp");
        var meuPopUp = document.getElementById("editarQuantidade");

        // Obtém o botão dentro do pop-up
        var botaoDentroDoPopUp = meuPopUp.querySelector("#btnEditarQuantidade");
        
        document.addEventListener('DOMContentLoaded', function() {
            // Adiciona um ouvinte de evento ao botão "Abrir Pop-up"
            botaoAbrirPopUp.addEventListener("click", function() {
                // Obtém o valor do atributo data-id do botão clicado
                var dataId = this.getAttribute("data-id");

                // Define o valor em um atributo personalizado do botão dentro do pop-up
                botaoDentroDoPopUp.setAttribute("data-id-pop-up", dataId);

                // Abre o pop-up (você pode implementar sua própria lógica de exibição do pop-up)
                meuPopUp.style.display = "block";
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var botoesExcluir = document.querySelectorAll('.botao-abastecer');
            
            botoesExcluir.forEach(function(botao) {
                botao.addEventListener('click', function() {
                    var sequencia = botao.getAttribute('data-id');
                    
                    if (confirm('Tem certeza que deseja abastecer a gondola?')) {
                        abastecerGondola(<?php echo $nunota2; ?>, sequencia);
                    }
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

        function abrirObs(){
            document.getElementById('popupObservacao').style.display = 'block';
        }

        function proximoProduto(qtdneg, nunota, codusu, sequencia, referencia, endereco)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'proximoproduto.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: {qtdneg: qtdneg, nunota: nunota, codusu: codusu, sequencia: sequencia, referencia: referencia, endereco: endereco},//Dados para consulta
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
        $('#proximo').click(function () {
            proximoProduto($("#qtdneg").val(), <?php echo $nunota2; ?>, <?php echo $codusu; ?>, <?php echo $sequencia; ?>, $("#referencia").val(), $("#endereco").val())
        });

        function iniciarpausa(status, nota)
		{
			//O método $.ajax(); é o responsável pela requisição
			$.ajax
				({
					//Configurações
					type: 'POST',//Método que está sendo utilizado.
					dataType: 'html',//É o tipo de dado que a página vai retornar.
					url: 'iniciarpausa.php',//Indica a página que está sendo solicitada.
					//função que vai ser executada assim que a requisição for enviada
					beforeSend: function () {
						$("#iniciarpausa").html("Carregando...");
					},
					data: {status: status, nota: nota},//Dados para consulta
					//função que será executada quando a solicitação for finalizada.
					success: function (msg)
					{
						location.reload();
					}
				});
		}
		$('#btnStatus').click(function () {
			var nunota = "<?php echo $nunota2; ?>"
			var status = "<?php echo $varStatus; ?>"
			iniciarpausa(status, nunota)
		});


        

        function  alterarQuantidade(nunota, sequencia, quantidade)
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

        function  abastecerGondola(nunota, sequencia)
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
                data: {nunota: nunota, sequencia: sequencia},//Dados para consulta
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

        
        function procurarOutroLocal(nunota, sequencia, codprod)
        {
            var selectedOption = $("input[name='fav_language']:checked").val();

            //O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'procuraroutrolocal.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: {option: selectedOption, nunota: nunota, sequencia: sequencia, codprod: codprod},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    alert(msg);
                    location.reload();
                }
            });
        }
        $('#btnOutroLocal').click(function () {
            procurarOutroLocal(<?php echo $nunota2; ?>, <?php echo $sequencia; ?>, <?php echo $codprod; ?>)
        });

        
        function  deletarProduto(nunota, sequencia)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'deletarproduto.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: {nunota: nunota, sequencia: sequencia},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    if(msg == "success"){
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

