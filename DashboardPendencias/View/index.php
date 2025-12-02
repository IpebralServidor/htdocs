<?php
require_once '../../App/auth.php';
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/main.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="../../../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="../../../node_modules/bootstrap/dist/css/bootstrap.min.css">
	<script src="../../../node_modules/jquery/dist/jquery.min.js"></script>
	<script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="../Controller/IndexController.js?v=<?php echo time(); ?>"></script>
	<title>Dashboard Pendências</title>
</head>

<body>
	<div>
		<div class="popup" id="popFiltroPendencia">
			<div class="overlay"></div>
			<div class="content">
				<div style="width: 100%;">
					<div class="close-btn" onclick="abrirPopFiltroPendencia()">
						<i class="fa-solid fa-xmark"></i>
					</div>

					<div class="div-form">
						<div class="form">
							<strong>Número único:</strong>
							<input type="number" id="nunota">

							<strong>Parceiro:</strong>
							<input type="number" id="codparc">
							<button id="confirmaFiltroPendencia" onclick="confirmaFiltroPendencia()">Confirmar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="loader" style="display: none;">
			<img style=" width: 150px; margin-top: 5%;" src="../../images/soccer-ball-joypixels.gif">
		</div>
		<header class="header">
			<div class="img-voltar">
				<a href="../../menu.php">
					<img src="../../images/216446_arrow_left_icon.png" />
				</a>
			</div>
			<div class="setaDown fw-bold" onclick="abrirPopFiltroPendencia()">
				<span>
					<i class="fa-solid fa-filter"></i>
				</span>
			</div>
		</header>
		<div>
			<table width="99%" border="1px" style="margin-left: 7px; overflow: auto" id="table">
				<thead>
					<tr>
						<th>Referência</th>
						<th>Descrição</th>
						<th>Cód. Local</th>
						<th>Qtd. Pendente</th>
						<th>Controle</th>
						<th>Estoque Possível</th>
						<th>Núm. Único</th>
						<th>Cód. Parc.</th>
					</tr>
				</thead>
				<tbody id="pendencias"></tbody>
			</table>
		</div>
	</div>
</body>

</html>