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
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

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