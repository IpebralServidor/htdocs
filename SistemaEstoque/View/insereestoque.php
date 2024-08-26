<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

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
	<meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/insereestoque.css?v=2">
	<link rel="stylesheet" href="../../../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
	<title>Coletor</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
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
						<img src="../images/216446_arrow_left_icon.png" />
					</aabr>
				</a>
			</div>
		</nav>


	</div>
	<!-- Fim do Menu-->

	<!-- Informações da Nota -->
	<div class="infonota">
		<table class="infonotatable">
			<tr>
				<td><b>Parc: </b> <br><label id="codparc"></label></td>
				<td><b>Nome Parc: </b> <br><label id="nomeparc"></label></td>
				<td><b>TOP Origem: </b> <br><label id="codtipoper"></label></td>
			</tr>
			<tr>
				<td><b>Núm. Ún. Orig.: </b> <br><label id="nunota"></label></td>
				<td><b>TOP Destino: </b> <br><label id="codtipoperdest"></label></td>
				<td><b>Núm. Ún. Dest.: </b> <br><label id="nunotadest"></label></td>
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
				<input class="cxtexto" type="text" id="enderecotemp" oninput="iniciarMedicaoEnderecoTemp();" onchange="finalizarMedicaoEnderecoTemp();" class="text" value="">

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

		<!-- Modal para confirmar produto-->

		<div class="popup" id="popupConfirmarProduto">
			<div class="overlay"></div>
			<div class="content">
				<div style="width: 100%;">
					<div class="close-btn" onclick="togglePopupConfirmarProduto(); limpaCampo('produto');">
						<i class="fa-solid fa-xmark"></i>
					</div>

					<div class="div-form">
						<div class="form">
							<label>Digite a referência novamente:</label>
							<input type="text" class="form-control" id="confirmacaoProduto" style="color: #86B7FE !important;" required>
							<button onclick="confirmaProduto()">Confirmar</button>
						</div>
					</div>
				</div>

			</div>
		</div>

		<!-- Modal para confirmar endereço-->

		<div class="popup" id="popupConfirmarEndereco">
			<div class="overlay"></div>
			<div class="content">
				<div style="width: 100%;">
					<div class="close-btn" onclick="togglePopupConfirmarEndereco(); limpaCampo('endereco');">
						<i class="fa-solid fa-xmark"></i>
					</div>

					<div class="div-form">
						<div class="form">
							<label>Digite o endereço novamente:</label>
							<input type="number" class="form-control" id="confirmacaoEndereco" style="color: #86B7FE !important;" required>
							<button onclick="confirmaEndereco()">Confirmar</button>
						</div>
					</div>
				</div>

			</div>
		</div>

		<!-- Modal para confirmar endereço temp-->

		<div class="popup" id="popupConfirmarEnderecoTemp">
			<div class="overlay"></div>
			<div class="content">
				<div style="width: 100%;">
					<div class="close-btn" onclick="togglePopupConfirmarEnderecoTemp(); limpaCampo('enderecotemp');">
						<i class="fa-solid fa-xmark"></i>
					</div>

					<div class="div-form">
						<div class="form">
							<label>Digite o endereço:</label>
							<input type="number" class="form-control" id="confirmacaoEnderecoTemp" style="color: #86B7FE !important;" required>
							<button onclick="confirmaEnderecoTemp()">Confirmar</button>
						</div>
					</div>
				</div>

			</div>
		</div>

		<!-- Fim dos POPUP's -->

		<div class="container">

			<div class="header-body">

				<div class="header-body-left">

					<!-- INPUT de Produto -->
					<div class="d-flex justify-content-center align-items-center">
						<div class="input-h6">
							<h6>Produto:</h6>
							<input style="margin-top: 3px;" type="checkbox" class="checkVariosProdutos" name="checkVariosProdutos" id="checkVariosProdutos" style="margin-top: 4px;">
							<span id='resultadoVariosProd' style="margin-left:3px; margin-top: 0;">
								Marcar Vários</span> <!--Retorno do resultado checkbox-->
						</div>
						<input type="text" id="produto" class="form-control" oninput="iniciarMedicaoProduto();" onchange="finalizarMedicaoProduto();" style="color: #86B7FE !important;" placeholder="">
					</div>
					<!-- Fim do INPUT de Produto -->

					<!-- INPUT de Quantidade -->
					<div class="d-flex justify-content-center align-items-center">
						<div class="input-h6">
							<h6>Quantidade:</h6>
						</div>
						<input type="number" id="quantidade" class="form-control" style="color: #86B7FE !important;" placeholder="">
					</div>
					<!-- Fim do INPUT de Quantidade -->

					<!-- INPUT de Endereço -->
					<div class="d-flex justify-content-center align-items-center">
						<div class="input-h6">
							<h6>Endereço:</h6>
						</div>
						<input type="number" id="endereco" class="form-control" oninput="iniciarMedicaoEndereco();" onchange="finalizarMedicaoEndereco();" style="color: #86B7FE !important;" placeholder="">
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
				</div>

			</div>

			<div class="image d-flex justify-content-center" id="imagemproduto">
				<?php

				$tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000 ";

				$stmt2 = sqlsrv_query($conn, $tsql2);

				if ($stmt2) {
					$row_count = sqlsrv_num_rows($stmt2);

					while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC)) {
						echo '<img style="vertical-align: middle; margin: auto; max-width: 100%; max-height: 115;" src="data:image/jpeg;base64,' . base64_encode($row2[0]) . '"/>';
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
	<script src="../Controller/InsereEstoqueController.js"></script>
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
			if (isset($_SESSION["checkVariosProdutos"])) {
				echo "resultado.innerHTML = 'Desmarque para concluir';";
			}
			?>
		}
	})();
</script>