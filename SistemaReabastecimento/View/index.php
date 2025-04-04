<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<link href="../css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="../../../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="../../../node_modules/bootstrap/dist/css/bootstrap.min.css">
	<script src="../../../node_modules/jquery/dist/jquery.min.js"></script>
	<script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="../Controller/IndexController.js"></script>
	<title>Estoque CD3</title>
</head>

<body onload="produtos()">
	<div id="loader" style="display: none;">
		<img style=" width: 150px; margin-top: 5%;" src="../images/soccer-ball-joypixels.gif">
	</div>

	<!-- pop up LOGIN e CADASTRO -->
	<div class="popup" id="popConfirmar">
		<div class="overlay"></div>
		<div class="content">
			<div style="padding: 20px; width: 100%;">
				<div class="close-btn" onclick="abrir()">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
						<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z" />
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

	<div class="popup" id="popAutorizaTrava">
		<div class="overlay"></div>
		<div class="content">
			<div style="width: 100%;">
				<div class="close-btn" onclick="abrirPopAutorizaTrava()">
					<i class="fa-solid fa-xmark"></i>
					<!-- <i class="fa-solid fa-circle-xmark"></i> -->
				</div>

				<div class="form">
					<strong><label id="msg"></label></strong>
					<br>
					<label>Usuário:</label>
					<input type="text" id="user" required>

					<label>Senha:</label>
					<input type="password" id="senha" required>
					<button id="btn-autorizacorte" onclick="autorizaTrava();">Confirmar</button>
				</div>
			</div>

		</div>
	</div>

	<div>
		<div class="img-voltar">
			<a href="../../menu.php">
				<img src="../images/216446_arrow_left_icon.png" />
			</a>
		</div>

		<div class="popup" id="popFiltroNota">
			<div class="overlay"></div>
			<div class="content">
				<div style="width: 100%;">
					<div class="close-btn" onclick="abrirPopFiltroNota()">
						<i class="fa-solid fa-xmark"></i>
					</div>

					<div class="div-form">
						<div class="form">
							<strong>Tipo:</strong>
							<select id="tipoTransf" class="form-control">
								<option value="N" selected>Todos</option>
								<option value="TRANSFAPP">Abastecimento</option>
								<option value="TRANSF_PENDENCIA">Pendência</option>
								<option value="TRANSFPROD_ENTRADA">Entrada da produção</option>
								<option value="TRANSFPROD_SAIDA">Saída da produção</option>
								<option value="TRANSF_NOTA">Transferência avulsa</option>
								<option value="TRANSF_CD5">Entrada CD5</option>
								<option value="TRANSF_ABAST_31">Abastecimento 3/1</option>
								<option value="TRANSF_ABAST_101_MAX">Abastecimento 10/1</option>
								<option value="TRANSF_ABAST_103_MAX">Abastecimento 10/3</option>
								<option value="TRANSF_ABAST_FILIAL_16">Abastecimento 1/6</option>
								<option value="TRANSF_ABAST_FILIAL_36">Abastecimento 3/6</option>
								<option value="TRANSF_ABAST_FILIAL_17">Abastecimento 1/7</option>
								<option value="TRANSF_TROCA_PROPRIEDADE">Troca de Propriedade</option>

							</select>
							<strong>CD:</strong>
							<select id="cdTransf" class="form-control">
								<option value="N" selected>Todos</option>
								<option value="5">5</option>
								<option value="3">3</option>
								<option value="2">2</option>
							</select>

							<strong>Referencia/Cod.barra</strong>
							<input type="text" id="referencia" class="form-control">


							<button id="confirmaFiltroNota" onclick="abrirPopFiltroNota(); produtos();">Confirmar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="screen">
			<div class="margin-top35" style="width: 80%;">
				<div class="form-group">
					<label for="referencia" class="label-input">Número da nota:</label>
					<input type="number" class="form-control margin-top10" id="numeroNota" name="numeroNota">
				</div>

				<button onclick="abrir()" name="aplicar" class="btn btn-primary btn-form margin-top35">Pesquisar</button>
			</div>
			<div class="div-tabela">
				<div>
					<div class="switchBox" style="box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px;">
						<div class="tabSwitch">
							<input type="checkbox" class="checkbox" id="chkInp" onchange="produtos()">

							<label for="chkInp" class="label">
								<div class="ball" id="ball"></div>
							</label>
						</div>

						<div class="titleBox">
							<p id="titleBoxH6">Notas de separação</p>
						</div>
						<div class="titleBox">
							<p id="filterName"></p>
						</div>
						<div class="setaDown fw-bold" onclick="abrirPopFiltroNota()">
							<span>
								<i class="fa-solid fa-filter"></i>
							</span>
						</div>
					</div>
					<div style="margin-top: 10px; display: flex; justify-content: flex-start">
						<table class="table">
							<thead>
								<tr>
									<th>Usuário</th>
									<th>Nunota</th>
									<th>Emp</th>
									<th>Cod TOP</th>
									<th>Tipo</th>
									<th>Qtd Itens</th>
									<th>Data</th>
								</tr>
							</thead>
							<tbody class="movTable" id="prodId">

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</body>

</html>