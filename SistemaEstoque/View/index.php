<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../css/main.css?v=<?php time() ?>">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<title>Estoque CD3</title>
</head>

<body>

	<div id="loader" style="display: none;">
		<img style=" width: 150px; margin-top: 5%;" src="../images/soccer-ball-joypixels.gif">
	</div>

	<div id="popupEscolherNota" class="popupEscolherNota">
		<button class="fechar" id="fechar" onclick="fecharEscolherNota();">X</button>
		<div style="width: 95%; height:95%;" id="escolherNota"></div>
	</div>

	<div id="popupCriaNotaTransf" class="popupCriaNotaTransf">
		<button class="fechar" id="fechar" onclick="fecharCriarNotaTransf();">X</button>
		<div style="width: 95%; height:95%;" id="criarNota"></div>
	</div>

	<div>
		
		<div class="screen">
			<div class="img-voltar">
				<a href="../../menu.php">
					<img src="../images/216446_arrow_left_icon.png" />
				</a>
			</div>
			<div style="width: 80%; margin-top:90px;">
				<div class="form-group">
					<label for="nunota">Número Único: (Origem)</label>
					<input type="number" name="NUNOTA" id="nunota" class="form-control margin-top10">
				</div>
				<button id="abrirNotabtn" class="btn btn-primary btn-form margin-top35" value="Abrir">Abrir</button>
				<button class="btn btn-primary btn-form margin-top35" id="gerarNovaTransf" value="gerarNovaTransf	">Gerar Nova Transf.</button>
			</div>
		</div>
	</div>

	<script src="../Controller/IndexController.js"></script>
</body>

</html>