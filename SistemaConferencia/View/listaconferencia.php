<?php
include "../../conexaophp.php";
require_once '../App/auth.php';

$usuconf = $_SESSION["idUsuario"];
$_SESSION["codbarraselecionado"] = 0;
$_SESSION["nmrComplemento"] = "";
$_SESSION["funcao"] = false;

?>

<html>

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lista Conferência - <?php echo $usuconf; ?></title>
	<link href="../css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="../Controller/ListaConferenciaController.js"></script>
</head>

<body class="background-lista">
	<div id="loader" style="display: none;">
		<img style=" width: 150px; margin-top: 5%;" src="../images/soccer-ball-joypixels.gif">
	</div>

	<div id="Filtro" class="filtro">
		<div class="img-voltar">
			<a href="../../menu.php">
				<img src="../images/216446_arrow_left_icon.png">
			</a>
		</div>
		<div>
			<div class="form-group">
				<input id="numnotaFiltro" type="text" value="" class="form-control" name="NUMNOTA" class="text" placeholder="Número da Nota:">
			</div>
			<div>
				<input id="nunotaFiltro" type="text" value="" class="form-control" name="nunota" class="text" placeholder="Número Único:">
			</div>
			<div class="form-group">
				<select id="statusFiltro" name="status" class="form-control">
					<option value="todos">Todas as notas</option>
					<option value="aguardandoconf">Aguardando Conferência</option>
					<option value="emandamento">Conferência em andamento</option>
					<option value="aguardandorecont">Aguardando Recontagem</option>
					<option value="recontemandamento">Recontagem em Andamento</option>
				</select>
			</div>
			<div class="form-group">
				<input id="parceiroFiltro" type="text" class="form-control" name="parceiro" class="text" placeholder="Parceiro:">
			</div>
			<div class="form-group">
				<button id="aplicar" name="aplicar" class="btn btn-form" type="submit" onclick="aplicarFiltro();">Aplicar</button>
			</div>
		</div>
		<div class='my-legend'>
			<div class='legend-title'>Legenda:</div>
			<div class='legend-scale'>
				<ul class='legend-labels'>
					<li><span style='background:#8fffb1;'></span>Separação concluída</li>
					<li><span style='background:#FFFF95;'></span>Separação em andamento</li>
					<li><span style='background:#ff9595;'></span>Separação não iniciada</li>
					<li><span style='background:#9c95ff;'></span>Separação em pausa</li>
				</ul>
			</div>
		</div>
	</div> <!-- Filtro -->
	<div class="listaconferenciatext">
		<p class="text-center">Lista de Conferência
			<button class="btn btn-admin btn-form col" onclick="pegarProximaNota(<?php echo $usuconf ?>);">Pegar próxima nota</button>
		</p>
	</div>
	<div id="ListaConferencia" class="listaconferencia">
		<table width="4000" id="tableListaConferencia">
			<thead>
				<tr style="color: white;">
					<th onclick="sortTable(0, 'num');">Parceiro</th>
					<th onclick="sortTable(1, 'date');">Dt. do Movimento</th>
					<th onclick="sortTable(2, 'num');">Nro. Único</th>
					<th onclick="sortTable(3, 'str');">Nome (Conferente)</th>
					<th onclick="sortTable(4, 'num');">TOP</th>
					<th onclick="sortTable(5, 'vlr');">Valor Nota</th>
					<th onclick="sortTable(6, 'str');">Status Separação</th>
					<th onclick="sortTable(7, 'str');">Status Conferência</th>
					<th onclick="sortTable(8, 'num');">Nro. Nota</th>
					<th onclick="sortTable(9, 'num');">Empresa</th>
					<th onclick="sortTable(10, 'str');">Nome Fantasia (Empresa)</th>
					<th onclick="sortTable(11, 'num');">Cod conferente</th>
					<th onclick="sortTable(12, 'str');">Descrição (Tipo de Operação)</th>
					<th onclick="sortTable(13, 'num');">Ordem de Carga</th>
					<th onclick="sortTable(14, 'num');">Sequência da Carga</th>
					<th onclick="sortTable(15, 'num');">Qtd. Volumes</th>
					<th onclick="sortTable(16, 'num');">Cód. Conferente</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="listaConferencias">

			</tbody>
		</table>
	</div>
</body>

</html>