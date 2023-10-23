<?php  
include "../conexaophp.php";
require_once '../App/auth.php';

$usuconf = $_SESSION["idUsuario"];

$nunotaorig = $_GET["nunota"]; 

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/insereestoque.css">
	<title>Insere Estoque</title>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


	<script type="text/javascript">
		function abrir(){
			document.getElementById('popupprodutos').style.display = 'block';
		}
		function fechar(){
			document.getElementById('popupprodutos').style.display =  'none';
		}
		function abrirEditar(){
			document.getElementById('popupEditar').style.display = 'block';
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

		function delete_confirm(){

		        var result = confirm("Tem certeza que deseja apagar esse item?");
			        if(result){
			            return true;
			        }else{
			            return false;
			        }
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
			document.getElementById('popupInserirEndereco').style.display = 'block';
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
			        	resultado.innerHTML = "Desmarque para concluir";
			            return false;
			        }
		    }
	</script>



</head>
<body>

<div id="loader" style="display: none;">
	<img style=" width: 150px; margin-top: 5%;" src="../images/soccer-ball-joypixels.gif">
</div>

<a href="index.php"><aabr title="Voltar"><img style="width: 40px; margin-top: 2px; margin-left: 10px; position: fixed;" src="images/Seta Voltar.png" /></aabr></a>

<div class="container">

	<div class="infonota">
		<div class="infonotadiv">
			<?php
				$tsql2 = " 
					SELECT * FROM sankhya.AD_FNT_RetornaInfoNota_SistemaEstoque($nunotaorig)
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

			<span class="infonotatext">Parc: <?php if(!isset($codparcorig)){ echo '';} else { echo $codparcorig;} ?></span><br>
			<span class="infonotatext">Nome Parc: <?php if(!isset($nomeparcorig)){ echo '';} else { echo $nomeparcorig;} ?></span><br>
			<span class="infonotatext">TOP Origem: <?php if(!isset($toporig)){ echo '';} else { echo $toporig;} ?></span><br>
			<span class="infonotatext">Núm. Ún. Orig.: <?php if(!isset($notaorig)){ echo '';} else { echo $notaorig;} ?></span><br>
			<span class="infonotatext">TOP Destino: <?php if(!isset($topdest)){ echo '';} else { echo $topdest;} ?></span><br>
			<span class="infonotatext">Núm. Ún. Dest.: <?php if(!isset($notadest)){ echo '';} else { echo $notadest;} ?></span><br>
		</div>
	</div> <!-- Fim infonota -->

	<div class="acoes"> 

		<div id="popupprodutos" class="popupprodutos">
			<button class="fechar" id="fechar" onclick="fechar();">X</button>
			<div id="editarProdutosDiv" style=" width: 91%; height: 90%; position: absolute; overflow: auto; margin-top: 5px;">
					
			</div>
		</div>

		<!-- Edição de Produtos -->
		<div id="popupEditar" class="popupEditar">
			<button class="fechar" id="fechar" onclick="fecharEditar();">X</button>
			
			<div style="width: 100%; height:100%;">

				<form action="editar.php?sequencia=<?php echo $sequenciaEdit; ?>" method="post" name="fmrEditaItens">

					<br>
					<label>Produto:</label><br>
					<input class="cxtexto" type="text" name="PRODUTOEDIT" class="text" disabled>

					<br><BR><label>Local Destino:</label><br>
					<input class="cxtexto" type="text" name="LOCALDESTEDIT" class="text">

					<br><br>
					<label>Quantidade:</label><br>
					<input class="cxtexto" type="text" name="QUANTIDADEEDIT" class="text">

					<br><br>
					<input id="Editar" name="Editar" type="submit" value="Editar">					 

				</form>
			</div>
		</div> <!-- POP UP para Editar Produtos -->


		<!-- Edição de Produtos Temp-->
		<div id="popupEditarTemp" class="popupEditarTemp">
			<button class="fechar" id="fechar" onclick="fecharEditarTemp();">X</button>
			
			<div style="width: 100%; height:100%;">

				<form action="editartemp.php?sequencia=<?php echo $sequenciaEditTemp; ?>" method="post" name="fmrEditaItens">
				
					<?php

						$tsql3 = "

							DECLARE @NUNOTADEST INT = (SELECT AD_VINCULONF FROM TGFCAB WHERE NUNOTA = $nunotaorig)

							SELECT REFERENCIA, 
								   QTDNEG
							FROM TEMP_PRODUTOS_COLETOR
							WHERE SEQUENCIA = $sequenciaEditTemp
							  AND CODUSU = $usuconf
							  and NUNOTA = @NUNOTADEST
							  

					 			";

					 	$stmt3 = sqlsrv_query( $conn, $tsql3);

					 	if($stmt3){

						 	while( $row2 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_NUMERIC))  
									{

									if(!isset($row2[0])){ $produtoedittemp = '';} else {$produtoedittemp = $row2[0];}
									if(!isset($row2[1])){ $quantidadeedittemp = '';} else {$quantidadeedittemp = $row2[1];}
									}
						}

					if(isset($_POST["EditarTemp"])){
						//$produtoeditar = $_POST["PRODUTOEDIT"];
						//$enderecoeditar = $_POST["LOCALDESTEDIT"];
						$quantidadeeditar = $_POST["QUANTIDADEEDITTEMP"];
						
					}


				 	?>

					<br>
					<label>Produto:</label><br>
					<input class="cxtexto" type="text" name="PRODUTOEDITTEMP" class="text" value="<?php echo $produtoedittemp; ?>" disabled>

					<br><br>
					<label>Quantidade:</label><br>
					<input class="cxtexto" type="text" name="QUANTIDADEEDITTEMP" class="text" value="<?php echo $quantidadeedittemp; ?>">

					<br><br>
					<input id="Editar" name="EditarTemp" type="submit" value="Editar">					 

				</form>
			</div>
		</div> <!-- POP UP para Editar Produtos Temp-->

		<!-- Edição de Produtos Temp-->
		<div id="popupInserirEndereco" class="popupInserirEndereco">
			<button class="fechar" id="fechar" onclick="fecharInsereEndereco();">X</button>
			
			<div style="width: 100%; height:100%;">

					<br>
					<label>Endereço:</label><br>
					<input class="cxtexto" type="text" id="enderecotemp" class="text" value="">


					<br><br>
					<input id="InserirTempITE" name="InserirTemp" type="submit" value="Confirmar">					 
			</div>
		</div> <!-- POP UP para Editar Produtos Temp-->




		<div id="tempprodutos" class="tempprodutos">
			<button class="fechar" id="fechar" onclick="fechartemp();">X</button>
			<div id="produtosTempDiv" style=" width: 91%; height: 90%; position: absolute; overflow: auto; margin-top: 5px;">
				
			</div>
		</div> <!-- Temp dos Produtos -->




		<!-- POP UP Divergências -->
		<div id="popupdivergencias" class="popupdivergencias">
			<button class="fechar" id="fechar" onclick="fechardivergencias();">X</button>
			<div id="produtosDivergentesDiv" style=" width: 91%; height: 90%; position: absolute; overflow: auto; margin-top: 5px;">

			</div>
		</div> <!-- POP UP Divergências -->



		<div class="botões">

			<button id="finalizar" name="Finalizar">Finalizar</button>

			<button class="editarbtn" id="editarprodutosbtn" onclick="abrir();">Editar Prod.</button>

			<button class="divergenciasbtn" id="produtosDivergentesBtn" onclick="abrirdivergencias();">Divergências</button>

			<button class="editarbtn" name="editarTempBtn" id="editarTempBtn" onclick="abrirtemp();">Editar Temp.</button>

		</div>
	</div> <!-- Fim acoes -->


	<div class="item">
		<div> <!-- DIV de inserção dos produtos -->
			

			<br>
			<label>Produto:</label><br>
			<input class="cxtexto" type="text" name="PRODUTO" id="produto" class="text">
			<br> <input style="margin-top: 3px;" type="checkbox" class="checkVariosProdutos" name="checkVariosProdutos" id="checkVariosProdutos" style="margin-top: 4px;" >
			<span id='resultadoVariosProd' style="margin-left:3px; margin-top: 0;">
			Marcar Vários Produtos</span> <!--Retorno do resultado checkbox-->
			

			<br><br>
			<label>Quantidade:</label><br>
			<input class="cxtexto" type="text" name="QUANTIDADE" id = "quantidade" class="text">

			<br><br><label>Endereço:</label><br>
			<input class="cxtexto" type="text" name="ENDERECO" id="endereco" class="text">

			

			<br><br>
			<input id="confirmar" name="confirmar" type="submit" value="Confirmar">

			
		</div>

		<div class="foto">
			<div class="foto-d" id="foto-d"> 
				<div style="width: 50%; height: 50%; line-height: 100%; align-items: center; display: inline-flex; margin-top: 0; padding-top: 0" id="imagemproduto">

					<?php

						$tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000 ";

						$stmt2 = sqlsrv_query( $conn, $tsql2);

						if($stmt2){
							$row_count = sqlsrv_num_rows( $stmt2 );


							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))
							{
								echo '<img style="vertical-align: middle;  max-width: 280px; margin: auto; max-height: 90%;" src="data:image/jpeg;base64,'.base64_encode($row2[0]).'"/>';
								//$imageData = $row2["image"];
							}
						}

					?>


				</div > <!-- Parte da Imagem -->
			</div>
		</div>



		<br><span class="infonotatextprod">Referência: <span id="referenciaprod"></span></span><br>
		<span class="infonotatextprod">Cód. Forn.: <span id="codfornprod"></span></span><br>
		<span class="infonotatextprod">Nome Prod.: <span id="descrprod"></span></span><br>
		

	</div><!-- Fim item -->

</div> <!-- Fim container -->

</body>
</html>


<!-- Evento de Clique do CheckBox para Marcar Vários Produtos -->
<script type="text/javascript">

 (function() {
    var elements = document.getElementsByClassName('checkVariosProdutos');
    var resultado = document.getElementById('resultadoVariosProd');
    var variosProdutos = 'Marcar Vários Produtos';		
    
    

    
    
    for (var i = 0; i < elements.length; i++) {
        elements[i].onclick = function() {
            if (this.checked === false) {
                variosProdutos = 'Marcar Vários Produtos';
                document.getElementById("endereco").disabled = false;
                document.getElementById("editarTempBtn").style.display = "none";
                
                 
            } else {
                variosProdutos = 'Desmarque para concluir';
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
			insereitens($("#produto").val(), $("#quantidade").val(), $("#endereco").val(), <?php echo $nunotaorig; ?>, $("#checkVariosProdutos").val())
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
            editarprodutos(<?php echo $nunotaorig; ?>)
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
            retornaprodutosdivergentes(<?php echo $nunotaorig; ?>)
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
            retornaprodutostemp(<?php echo $nunotaorig; ?>)
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
	            finalizanota(<?php echo $nunotaorig; ?>);
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
	            insereItensTempITE(<?php echo $nunotaorig; ?>, $("#enderecotemp").val());
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

