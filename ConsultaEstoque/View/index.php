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

	<title>Consulta Estoque</title>
</head>

<body>
	<div>
		<div id="loader" style="display: none;">
			<img style=" width: 150px; margin-top: 5%;" src="../images/soccer-ball-joypixels.gif">
		</div>
		<div class="img-voltar">
			<a href="../../menu.php">
				<img src="../images/216446_arrow_left_icon.png" />
			</a>
		</div>
		<div class="screen">
			<div class="margin-top35" style="width: 80%;">
				<div class="form-group">
					<label for="referencia" class="label-input">Pesquisa de produto:</label>
					<input type="text" class="form-control margin-top10" id="referencia" name="REFERENCIA" required>
				</div>
				<button type="submit" name="pesquisar" class="btn btn-primary btn-form margin-top35" onclick="pesquisaProduto()">Pesquisar</button>
			</div>
		</div>

		<div id="popupprodutos" class="popupprodutos">
			<div style=" width: 100%; overflow: auto; margin-top: 5px;">
				<table width="95%" border="1px" style="margin-top: 5px; margin-left: 7px; table-layout: fixed" id="table">
					<thead>
						<tr>
							<th width="33%" style="text-align: center;">Referência</th>
							<th width="67%" style="text-align: center;">Descrição do Produto</th>
						</tr>
					</thead>
					<tbody id="produtos">
					</tbody>
				</table>
			</div>

			<button class="fechar" onclick="fecharprodutos();"></button>
		</div>
	</div>
	<script src="../Controller/IndexController.js"></script>
</body>

</html>