<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';


?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="../css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="../../../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="../../../node_modules/bootstrap/dist/css/bootstrap.min.css">
	<script src="../../../node_modules/jquery/dist/jquery.min.js"></script>
	<script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

	<title>Desativa produtos</title>
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
		<div>
			<table width="99%" border="1px" style="margin-top: 5px; margin-left: 7px; table-layout: fixed" id="table">
				<thead>
					<tr>
						<th width="29%" style="text-align: center;">Referência</th>
						<th width="42%" style="text-align: center;">Descrição do produto</th>
						<th width="28%" style="text-align: center;">Local</th>
					</tr>
				</thead>
				<tbody id="produtos">
				</tbody>
			</table>
		</div>
		<div class="modal fade" id="desativaModal" tabindex="-1" role="dialog" aria-labelledby="desativaModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="p-3">
						<input type="hidden" id="codprodatual">
						<div class="modal-body fw-bold">
							Endereço: <span style="color: red">*</span>
						</div>
						<div class="mb-1">
							<input type="text" class="form-control" style="color: #86B7FE !important;" id="enderecoProduto">
						</div>
						<div class="modal-body fw-bold">
							Referência: <span style="color: red">*</span>
						</div>
						<div class="mb-1">
							<input type="text" class="form-control" style="color: #86B7FE !important;" id="referenciaProduto">
							<input type="checkbox" id="semcaixa" onchange='handleCheck(this);'>
							<label for="semcaixa">Caixa não está no endereço</label><br>
						</div>
						<div class="mt-3">
							<button id="atualizaValorBtn" onclick="desativaProduto();" type="button" class="btn btn-primary fw-bold w-100" style="background-color: red !important; border-color: red !important" data-bs-dismiss="modal">Desativar</button>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
	<script src="../Controller/IndexController.js"></script>
</body>

</html>