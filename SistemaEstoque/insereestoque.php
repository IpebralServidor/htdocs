<?php  
include "../conexaophp.php";
require_once '../App/auth.php';

$usuconf = $_SESSION["idUsuario"];

$nunotadest = $_GET["nunota"];

$tsql = "SELECT AD_PEDIDOECOMMERCE FROM TGFCAB WHERE NUNOTA = $nunotadest";
$stmt = sqlsrv_query($conn, $tsql); 
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

$tipoNota = $row[0];

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/insereestoque.css?v=2">
	<title>Coletor</title>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

	<script type="text/javascript">
		function abrir(){
			document.getElementById('popupprodutos').style.display = 'block';
		}
		function fechar(){
			document.getElementById('popupprodutos').style.display =  'none';
		}
		function abrirEditar(referencia, codlocaldest, qtdneg){
			// alert(referencia + " " + codlocaldest + " " + qtdneg);
			document.getElementById("produtoedit").value = referencia;
			document.getElementById("localdestedit").value = codlocaldest;
			document.getElementById("quantidadeedit").value = qtdneg;
			document.getElementById('popupEditar').style.display = 'flex';
		}
		function fecharEditar(){
			document.getElementById('popupEditar').style.display =  'none';
		}

		function abrirdivergencias(){
			document.getElementById('popupdivergencias').style.display = 'block';
		}
		function fechardivergencias(){
			document.getElementById('popupdivergencias').style.display =  'none';
		}

		function abrirtemp(){
			document.getElementById('tempprodutos').style.display = 'block';
		}
		function fechartemp(){
			document.getElementById('tempprodutos').style.display =  'none';
		}
		function abrirEditarTemp(){
			document.getElementById('popupEditarTemp').style.display = 'block';
		}
		function fecharEditarTemp(){
			document.getElementById('popupEditarTemp').style.display =  'none';
		}
		function abrirInsereEndereco(){
			document.getElementById('popupInserirEndereco').style.display = 'flex';
		}
		function fecharInsereEndereco(){
			document.getElementById('popupInserirEndereco').style.display =  'none';
		}

		function marca_variosprod_confirm(){

		        var result = confirm("Tem certeza que deseja Concluir?");
			        if(result){
			        	abrirInsereEndereco();
			            return true;
			        }else{
			        	// document.getElementsByClassName("resultadoVariosProd").checked;
			        	var resultado = document.getElementById('resultadoVariosProd');
			        	const checkbox = document.getElementById('checkVariosProdutos');
			        	checkbox.checked = true;
			        	document.getElementById("endereco").disabled = true;
                	    document.getElementById("endereco").value = "";
			        	document.getElementById("editarTempBtn").style.display = "inline-block";
			        	resultado.innerHTML = "Desm. p/ concluir";
			            return false;
			        }
		    }
	</script>

	<title>Estoque CD3</title>

</head>
<body onload="endereco()">


	<!-- Ícone para carregamento de certos botões -->
	<div id="loader" style="display: none;">
		<img style=" width: 150px; margin-top: 5%;" src="../images/soccer-ball-joypixels.gif">
	</div>
	<!-- Fim do ícone para carregamento de certos botões -->



	<!-- Menu, com parte de voltar e Relógio marcador de tempo das tarefas -->
	<div class="d-flex justify-content-between" style="background-color: #3a6070 !important;">
            <nav class="bg navbar">
                <div class="img-voltar">
					        <a href="index.php" class="btn btn-back">
					            <aabr title="Voltar para Menu">
					                <img src="images/216446_arrow_left_icon.png" />
					            </aabr>
					        </a>
				        </div>
            </nav>

            
        </div>
    </div>
    <!-- Fim do Menu-->



	<!-- Informações da Nota -->
	<div class="infonota">
		<?php
				$tsql2 = " 
					SELECT * FROM sankhya.AD_FNT_RetornaInfoNota_SistemaEstoque($nunotadest)
				 ";

				$stmt2 = sqlsrv_query( $conn, $tsql2); 

				while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))  
				{ $codparcorig = $row2['CODPARCORIGEM'];
				  $nomeparcorig = $row2['NOMEPARCORIGEM'];
				  $toporig = $row2['CODTIPOPERORIGEM'];
				  $notaorig = $row2['NUNOTAORIGEM'];
				  $topdest = $row2['CODTIPOPERDESTINO'];
				  $notadest = $row2['NUNOTADESTINO'];
				}

		?>
		<table class="infonotatable">
			<tr> 
                <td><b>Parc: </b> <br><?php echo $codparcorig; ?></td>
                <td><b>Nome Parc: </b> <br> <?php echo $nomeparcorig; ?></td>
                <td><b>TOP Origem: </b> <br> <?php echo $toporig; ?></td>
            </tr>
            <tr> 
                <td><b>Núm. Ún. Orig.: </b> <br> <?php echo $notaorig; ?></td>
                <td><b>TOP Destino: </b> <br> <?php echo $topdest; ?></td>
                <td><b>Núm. Ún. Dest.: </b> <br> <?php echo $notadest; ?></td>
            </tr>
		</table>
	</div>
	<!-- Fim das Informações da Nota -->
	


	<!-- Informações dos produtos, juntamente com INPUT's e Imagem -->
	<div class="infoproduto">

		<!-- POPUPS -->

		<!-- Produtos que já foram passados -->
		<div id="popupprodutos" class="popupprodutos">
			<button class="fechar" id="fechar" onclick="fechar();">X</button>
			<div id="editarProdutosDiv" style=" width: 91%; height: 90%; position: absolute; overflow: auto; margin-top: 5px;">
			</div>
		</div>
		<!-- Fim dos Produtos que já foram passados -->

		<!-- Edição de Produtos -->
		<div id="popupEditar" class="popupEditar">
			<button class="fechar" id="fechar" onclick="fecharEditar();">X</button>
			
			<div style="width: 100%; height:100%;">

				<!-- <form action="editar.php?sequencia=<?php echo $sequenciaEdit; ?>" method="post" name="fmrEditaItens"> -->

					<label>Produto:</label>
					<input class="cxtexto" type="text" id="produtoedit" class="text" disabled>

					<br><br>
					<label>Local Destino:</label>
					<input class="cxtexto" type="text" id="localdestedit" class="text">

					<br><br>
					<label>Quantidade:</label>
					<input class="cxtexto" type="text" id="quantidadeedit" class="text">

					<br><br>
					<button class="btn btn-primary btn-form" id="Editar" onclick="edit_confirm()">Editar</button>	 

				<!-- </form> -->
			</div>
		</div> 
		<!-- Fim da Edição de Produtos -->

		<!-- Edição de Produtos Temporários-->
		<div id="popupEditarTemp" class="popupEditarTemp">
			<button class="fechar" id="fechar" onclick="fecharEditarTemp();">X</button>
			
			<div style="width: 100%; height:100%;">

			</div>
		</div> 
		<!-- Fim da Edição de Produtos Temporários-->

		<!-- Inserindo Endereço no Produto, quando desmarcar o Marcar Vários -->
		<div id="popupInserirEndereco" class="popupInserirEndereco">
			<button class="fechar" id="fechar" onclick="fecharInsereEndereco();">X</button>
			
			<div style="width: 100%; height:100%;">

					<br>
					<label>Endereço:</label>
					<input class="cxtexto" type="text" id="enderecotemp" class="text" value="">

					<br><br>
					<input id="InserirTempITE" class="btn btn-primary btn-form" name="InserirTemp" type="submit" value="Inserir">					 
			</div>
		</div>
		<!-- Fim da inserção de Endereço no Produto, quando desmarcar o Marcar Vários -->

		<!-- Exibição dos produtos que estão na temporária -->
		<div id="tempprodutos" class="tempprodutos">
			<button class="fechar" id="fechar" onclick="fechartemp();">X</button>
			<div id="produtosTempDiv" style=" width: 91%; height: 90%; position: absolute; overflow: auto; margin-top: 5px;">
				
			</div>
		</div>
		<!-- Fim da exibição dos produtos que estão na temporária -->

		<!-- Exibição das Divergências -->
		<div id="popupdivergencias" class="popupdivergencias">
			<button class="fechar" id="fechar" onclick="fechardivergencias();">X</button>
			<div id="produtosDivergentesDiv" style=" width: 91%; height: 90%; position: absolute; overflow: auto; margin-top: 5px;">

			</div>
		</div> 
		<!-- Fim da exibição das Divergências -->

		<!-- Fim dos POPUP's -->

		<div class="container">

	        <div class="header-body">

	            <div class="header-body-left">

	            	<!-- INPUT de Produto -->
	            	<div class="d-flex justify-content-center align-items-center">
	                    <div class="input-h6">
	                        <h6>Produto:</h6> 
	                        <input style="margin-top: 3px;" type="checkbox" class="checkVariosProdutos" name="checkVariosProdutos" id="checkVariosProdutos" style="margin-top: 4px;" >
							<span id='resultadoVariosProd' style="margin-left:3px; margin-top: 0;">
							Marcar Vários</span> <!--Retorno do resultado checkbox-->
	                    </div>
	                    <input type="text" id="produto" class="form-control" placeholder=""> 
	                </div>
	                <!-- Fim do INPUT de Produto -->

	            	<!-- INPUT de Quantidade -->
	                <div class="d-flex justify-content-center align-items-center">
	                    <div class="input-h6">
	                        <h6>Quantidade:</h6>
	                    </div>
	                    <input type="number" id="quantidade" class="form-control" placeholder="">
	                </div> 
	                <!-- Fim do INPUT de Quantidade -->

	                <!-- INPUT de Endereço -->
	                <div class="d-flex justify-content-center align-items-center">
	                    <div class="input-h6">
	                        <h6>Endereço:</h6> 
	                    </div>
	                    <input type="number" id="endereco" class="form-control" placeholder="">
	                </div>
	                <!-- Fim do INPUT de Endereço -->

	                <!-- Informações do Produto Digitado -->
	                <div class="infos">
	                    <div class="informacoes">
	                        <h6>Referência: <span id="referenciaprod"></span></h6>     
	                        <h6>Cód. Forn: <span id="codfornprod"></span></h6>
	                        <h6>Nome Prod: <span id="descrprod"></span></h6>
	                    </div>
	                </div>
	                <!-- Fim das informações do Produto Digitado -->

	                <!-- <span id="sequencia"></span> --><!-- 
	                <input type="text" id="sequencia" value="" style="display: none;">
	                <input type="text" id="qtdlocalInput" value="" style="display: none;">
	                <input type="text" id="codprod" value="" style="display: none;">
	                <input type="text" id="observacao" value="" style="display: none;"> -->
	               
	            </div>

	        </div>

	        <div class="image d-flex justify-content-center" id="imagemproduto">
	            <?php

	                $tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000 ";

	                $stmt2 = sqlsrv_query( $conn, $tsql2);

	                if($stmt2){
	                    $row_count = sqlsrv_num_rows( $stmt2 ); 

	                    while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
	                    {
	                        echo '<img style="vertical-align: middle; margin: auto; max-width: 100%; max-height: 115;" src="data:image/jpeg;base64,'.base64_encode($row2[0]).'"/>';
	                    }
	                } 
	            ?>  
	        </div>

	        <div class="btn-confirmar-coletor">
	            <button id="confirmar" class="btn btn-primary btn-form">Confirmar</button>
	        </div>  
	        
	    </div> <!-- Container -->
	</div> <!-- infoproduto -->
	<!-- Fim das informações dos produtos, juntamente com INPUT's e Imagem -->




	<!-- Botões de Ação -->	
	<div class="botoes">
		<div class="botoes-conteudo">
	        <button id="finalizar" class="btn-form-finalizar">Finalizar</button>
	        <button class="btn-form-divergencias" id="produtosDivergentesBtn" onclick="abrirdivergencias();">Divergências</button>
	        <button id="editarprodutosbtn" onclick="abrir();" class="btn-form-editprod">Editar Prod.</button>
	        <button class="btn-form-editprod" id="editarTempBtn" onclick="abrirtemp();" style="display: none;">Editar Temp.</button>	
   		</div>
	</div>
	<!-- Fim dos botões de Ação -->	


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    




</body>
</html>

<!-- Partes de AJAX e JAVASCRIPT -->
<!-- Evento de Clique do CheckBox para Marcar Vários Produtos -->
<script type="text/javascript">

 (function() {
    var elements = document.getElementsByClassName('checkVariosProdutos');
    var resultado = document.getElementById('resultadoVariosProd');
    var variosProdutos = 'Marcar Vários';		
    
    

    
    
    for (var i = 0; i < elements.length; i++) {
        elements[i].onclick = function() {
            if (this.checked === false) {
                variosProdutos = 'Marcar Vários';
                document.getElementById("endereco").disabled = false;
                document.getElementById("editarTempBtn").style.display = "none";
                
                 
            } else {
                variosProdutos = 'Desm. p/ concluir';
                document.getElementById("endereco").disabled = true;
                document.getElementById("endereco").value = "";
                document.getElementById("editarTempBtn").style.display = "inline-block";

            }
            
            resultado.innerHTML = variosProdutos;
        }
        resultado.innerHTML = variosProdutos;

        <?php 

        	if(isset($_SESSION["checkVariosProdutos"])){
        		echo "resultado.innerHTML = 'Desmarque para concluir';";
        	}

        ?>
        }
	})();

	    function imagemproduto(produto)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
                    ({
                        //Configurações
                        type: 'POST',//Método que está sendo utilizado.
                        dataType: 'html',//É o tipo de dado que a página vai retornar.
                        url: 'imagemproduto.php',//Indica a página que está sendo solicitada.
                        //função que vai ser executada assim que a requisição for enviada
                        beforeSend: function () {
                          //  $("#imagemproduto").html("Carregando...");
                        },
                        data: {produto: produto},//Dados para consulta
                        //função que será executada quando a solicitação for finalizada.
                        success: function (msg)
                        {
                            $("#imagemproduto").html(msg);
                            //alert(msg);
                        }
                    });
        }


        document.getElementById("quantidade").addEventListener("focus", function() {

			 		imagemproduto($("#produto").val());
			 		retornainfoprodutos($("#produto").val(), '<?php echo $codparcorig; ?>');

			     	//document.getElementById("localorigem").textContent = "teste";
			 }); //,{ once: true }


        // $('#confirmar').click(function () {
        //     imagemproduto($("#produto").val())
        // });

		function endereco(){

			const endereco = document.getElementById("endereco")
			const tipoNota = "<?php echo $tipoNota ?>"

			if(tipoNota == "TRANSF_CD5"){
				endereco.disabled = true
				endereco.val = "5069900"
				endereco.placeholder = "5069900"
			}
		}

	    function retornainfoprodutos(produto, codparc)
		{
		    $.ajax
			({
				//Configurações
				type: 'POST',//Método que está sendo utilizado.
				dataType: 'html',//É o tipo de dado que a página vai retornar.
				url: 'retornainfoproduto.php',//Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function () {
					//$("#loader").show();
				},
				complete: function(){
					$("#loader").hide();
				},
				data: {produto: produto, codparc: codparc},//Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function (msg)
				{
					var retorno = msg.split("/");
					document.getElementById("referenciaprod").textContent = retorno[0];
					document.getElementById("codfornprod").textContent = retorno[1];
					document.getElementById("descrprod").textContent = retorno[2];
					
				}
			});
		}

		// $('#confirmar').click(function () {
        //     retornainfoprodutos($("#produto").val(), '<?php echo $codparcorig; ?>')
        // });


	 //Função que faz a validação e insere os itens da transferência no Banco
	 function insereitens(produto, quantidade, endereco, nunota, checkvariosprodutos){
        	 //O método $.ajax(); é o responsável pela requisição
			var isChecked = $("#checkVariosProdutos").prop("checked");

			$.ajax
			({
				//Configurações
				type: 'POST',//Método que está sendo utilizado.
				dataType: 'html',//É o tipo de dado que a página vai retornar.
				url: 'insereestoquebtn.php',//Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function () {
					$("#loader").show();
				},
				complete: function(){
					$("#loader").hide();
				},
				data: {produto: produto, quantidade: quantidade, endereco: endereco, nunota: nunota, checkvariosprodutos: isChecked},//Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function (msg)
				{
					
					msg = msg.trim();
					if(msg.includes("Item inserido com sucesso")){
						document.getElementById("produto").value = "";
						document.getElementById("quantidade").value = "";
						document.getElementById("endereco").value = "";
					} else if (msg != ""){
						alert(msg);
					}

				}
			});
        }
		$('#confirmar').click(function () {
			var endereco = document.getElementById("endereco").value

			if("<?php echo $tipoNota ?>" == "TRANSF_CD5"){
				endereco = document.getElementById("endereco").val
			}

			insereitens($("#produto").val(), $("#quantidade").val(), endereco, <?php echo $nunotadest; ?>, $("#checkVariosProdutos").val())
		});



		//Retorna os dados dos produtos que já foram inseridos quando se clica no Editar Prod.
	     function editarprodutos(nunota)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
                    ({
                        //Configurações
                        type: 'POST',//Método que está sendo utilizado.
                        dataType: 'html',//É o tipo de dado que a página vai retornar.
                        url: 'editarprodutos.php',//Indica a página que está sendo solicitada.
                        //função que vai ser executada assim que a requisição for enviada
                        beforeSend: function () {
                            $("#editarProdutosDiv").html("Carregando...");
                        },
                        data: {nunota: nunota},//Dados para consulta
                        //função que será executada quando a solicitação for finalizada.
                        success: function (msg)
                        {
                            $("#editarProdutosDiv").html(msg);
                        }
                    });
        }


        $('#editarprodutosbtn').click(function () {
            editarprodutos(<?php echo $nunotadest; ?>)
        });



        //Retorna a Tabela de Produtos Divergentes ao se clicar no botão
		function retornaprodutosdivergentes(nunota)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
                    ({
                        //Configurações
                        type: 'POST',//Método que está sendo utilizado.
                        dataType: 'html',//É o tipo de dado que a página vai retornar.
                        url: 'retornaprodutosdivergentes.php',//Indica a página que está sendo solicitada.
                        //função que vai ser executada assim que a requisição for enviada
                        beforeSend: function () {
                            $("#produtosDivergentesDiv").html("Carregando...");
                        },
                        data: {nunota: nunota},//Dados para consulta
                        //função que será executada quando a solicitação for finalizada.
                        success: function (msg)
                        {
                            $("#produtosDivergentesDiv").html(msg);
                        }
                    });
        }


        $('#produtosDivergentesBtn').click(function () {
            retornaprodutosdivergentes(<?php echo $nunotadest; ?>)
        });


                //Retorna a Tabela de Produtos Divergentes ao se clicar no botão
		function retornaprodutostemp(nunota)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
                    ({
                        //Configurações
                        type: 'POST',//Método que está sendo utilizado.
                        dataType: 'html',//É o tipo de dado que a página vai retornar.
                        url: 'retornaprodutostemp.php',//Indica a página que está sendo solicitada.
                        //função que vai ser executada assim que a requisição for enviada
                        beforeSend: function () {
                            $("#produtosTempDiv").html("Carregando...");
                        },
                        data: {nunota: nunota},//Dados para consulta
                        //função que será executada quando a solicitação for finalizada.
                        success: function (msg)
                        {
                            $("#produtosTempDiv").html(msg);
                        }
                    });
        }


        $('#editarTempBtn').click(function () {
            retornaprodutostemp(<?php echo $nunotadest; ?>)
        });


    	//Função que faz a finalização da nota do coletor
 		function finalizanota(nunota){

		$.ajax
		({
			//Configurações
			type: 'POST',//Método que está sendo utilizado.
			dataType: 'html',//É o tipo de dado que a página vai retornar.
			url: 'finalizar.php',//Indica a página que está sendo solicitada.
			//função que vai ser executada assim que a requisição for enviada
			beforeSend: function () {
				//$("#itensconferidos").html("Carregando...");
			},
			data: {nunota: nunota},//Dados para consulta
			//função que será executada quando a solicitação for finalizada.
			success: function (msg)
			{
				
				alert(msg);

			}
		});
        }
		$('#finalizar').click(function () {

			var confirmafinalizacao = confirm("Tem certeza que deseja confirmar essa nota?");
	        if(confirmafinalizacao){
	            finalizanota(<?php echo $nunotadest; ?>);
	        }else{
	            return false;
	        }
			
		});



		//Função que insere os itens da temporária para a TGFITE
 		function insereItensTempITE(nunota, endereco){

		$.ajax
		({
			//Configurações
			type: 'POST',//Método que está sendo utilizado.
			dataType: 'html',//É o tipo de dado que a página vai retornar.
			url: 'inserirTempITE.php',//Indica a página que está sendo solicitada.
			//função que vai ser executada assim que a requisição for enviada
			beforeSend: function () {
				//$("#itensconferidos").html("Carregando...");
			},
			data: {nunota: nunota, endereco: endereco},//Dados para consulta
			//função que será executada quando a solicitação for finalizada.
			success: function (msg)
			{
				
				msg = msg.trim();
				if(msg.includes("IPB: Itens Inseridos com Sucesso!")){
					fecharInsereEndereco();
				} else if (msg != ""){
					alert(msg);
				}

			}
		});
        }
		$('#InserirTempITE').click(function () {
	            insereItensTempITE(<?php echo $nunotadest; ?>, $("#enderecotemp").val());
		});


		const checkbox = document.getElementById('checkVariosProdutos');
		const botao = document.getElementById('editarTempBtn');
		let checkboxEstadoAnterior = checkbox.checked;


		checkbox.addEventListener('change', function () {

		  // Verifica a mudança de estado do checkbox
		  if (checkbox.checked !== checkboxEstadoAnterior) {
		    if (checkbox.checked) {
		      console.log('Checkbox foi marcado');
		    } else {
		      console.log('Checkbox foi desmarcado');
		      marca_variosprod_confirm();
		    }
		    
		    // Atualiza o estado anterior
		    checkboxEstadoAnterior = checkbox.checked;
		  }
		});


</script>

<!-- Lógica de Exclusão dos Produtos -->
<script type="text/javascript">


function delete_confirm(nunota, sequencia, tipo, codusu){

    var result = confirm("Tem certeza que deseja apagar esse item?");
        if(result){
            excluirprodutonota(nunota, sequencia, tipo, codusu);
            editarprodutos(nunota);
            retornaprodutostemp(nunota);

        }else{
            return false;
        }
}

function  excluirprodutonota(nunota, sequencia, tipo, codusu) //Tipo é para se é da ITE da nota ou temporária
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
        data: {nunota: nunota, sequencia: sequencia, tipo: tipo},//Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function (msg)
        {
            // if(msg == 'Concluido'){
            //     location.reload();
            // }else{
                alert(msg);
            // }                 
        }
    });
}
</script>
<!-- Fim da lógica de Exclusão dos Produtos -->


<!-- Lógica de Edição dos Produtos -->
<script type="text/javascript">


function edit_confirm(){

	produto = document.getElementById("produtoedit").value;
	localdest = document.getElementById("localdestedit").value;
	quantidade = document.getElementById("quantidadeedit").value;
	nunota = <?php echo $nunotadest; ?>;

	alert(produto + " " + localdest + " " + quantidade + " " + nunota);

    var result = confirm("Tem certeza que deseja editar esse item?");
        if(result){
            editarprodutonota(nunota, produto, localdest, quantidade);
            editarprodutos(nunota);
            retornaprodutostemp(nunota);

        }else{
            return false;
        }
}

function  editarprodutonota(nunota, produto, localdest, quantidade) //Tipo é para se é da ITE da nota ou temporária
{
    //O método $.ajax(); é o responsável pela requisição
    $.ajax
    ({
        //Configurações
        type: 'POST',//Método que está sendo utilizado.
        dataType: 'html',//É o tipo de dado que a página vai retornar.
        url: 'editarbtn.php',//Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function () {
            $("#loader").show();
        },
        complete: function(){
            $("#loader").hide();
        },
        data: {nunota: nunota, produto: produto, localdest: localdest, quantidade: quantidade},//Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function (msg)
        {
            // if(msg == 'Concluido'){
            //     location.reload();
            // }else{
                alert(msg);
            // }                 
        }
    });
}
</script>
<!-- Fim da lógica de Edição dos Produtos -->