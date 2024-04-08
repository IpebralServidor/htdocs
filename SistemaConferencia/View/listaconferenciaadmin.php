<?php
include "../../conexaophp.php";
require_once '../App/auth.php';

?>
<html>

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lista Conferência - <?php echo $_SESSION["idUsuario"]; ?></title>
	<link href="../css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="../Controller/ListaConferenciaAdminController.js"></script>
</head>

<body class="background-lista">
	<div id="loader" style="display: none;">
		<img style=" width: 150px; margin-top: 5%;" src="../images/soccer-ball-joypixels.gif">
	</div>
	<div id="popupconf" class="popupconf">
		<form action="../Model/atualizarconferente.php" class="form" method="post">
			<div class="form-group">
				<label for="conferentes" class="form-group">Conferentes:</label>
				<input type="text" class="form-control" id="codconferentes" name="conferentes" class="text" value="">
			</div>
			<div class="form-group">
				<input id="atualizar" name="atualizar" class="btn btn-form" type="submit" value="Atualizar">
			</div>
		</form>
		<button class="fechar" onclick="fecharatribuicao();">X</button>
	</div>

	<div id="popupconferentes" class="popupconferentes">
		<h4>Lista de conferentes</h4>
		<div class="input-busca">
			<input type="text" id="searchbar" onkeyup="search_conferente()" placeholder="Escreva o nome do conferente">
		</div><br>
		<div>
			<table class="table-conferentes">
				<div class="conferentes-title">
					<h6>
						Conferentes
					</h6>
				</div><br>
				<div id='usersList'>
				</div>
			</table>
			<button class="fechar" onclick="fecharconferentes();">X</button>
		</div>
	</div>

	<div id="Filtro" class="filtro">
		<div class="img-voltar">
			<a href="../../menu.php">
				<img src="../images/216446_arrow_left_icon.png">
			</a>
		</div>
		<div>
			<div>
				<input id="numnotaFiltro" value="" type="text" class="form-control" name="NUMNOTA" class="text" placeholder="Número da Nota:">
			</div>
			<div>
				<input id="nunotaFiltro" type="text" value="" class="form-control" name="nunota" class="text" placeholder="Número Único:">
			</div>
			<div>
				<select id="statusFiltro" name="status" class="form-control">
					<option value="todos">Todas as notas</option>
					<option value="todosnaoconfirmadas">Todos + 172X NÃO confirmadas</option>
					<option value="aguardandoconf">Aguardando Conferência</option>
					<option value="emandamento">Conferência em andamento</option>
					<option value="aguardandorecont">Aguardando Recontagem</option>
					<option value="recontemandamento">Recontagem em Andamento</option>
					<option value="emseparacao">Separação em andamento</option>
				</select>
			</div>
			<div>
				<select id="filtroEmpresas" name="empresa" class="form-control">
				</select>
			</div>

			<div>
				<input id="dtIniFiltro" type="text" onfocus="(this.type='date')" class="form-control" name="dtinicio" placeholder="Data de início">
				<input id="dtFimFiltro" type="text" onfocus="(this.type='date')" class="form-control" name="dtfim" placeholder="Data de fim">
			</div>

			<div style="margin-bottom: 7%;">
				<input id="parceiroFiltro" type="text" value="" class="form-control" name="parceiro" class="text" placeholder="Parceiro:">
			</div>
			<div>
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
		<p class="text-center col">
			Lista de Conferência ADMINISTRADOR
			<button class="btn btn-admin btn-form col" onclick="abrirconferentes();">Atribuir nota</button>

			<button style="width: 40px !important; height: 36px !important; margin-right: 10px;" class="btn btn-admin btn-form col" onclick="abrirconf();">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
					<path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0Zm-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" />
					<path d="M8.256 14a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z" />
				</svg>
			</button>
		</p>


	</div>
	<div id="ListaConferencia" class="listaconferencia">
		<table width="4000" id="tableListaConferencia">
			<thead>
				<tr style="color: white;">
					<th><input type="checkbox" name="select-all" id="select_all" value="" /></th>
					<th onclick="sortTable(1, 'numStr');">Conferente</th>
					<th onclick="sortTable(2, 'num');">Nro. Único</th>
					<th onclick="sortTable(3, 'num');">Tipo Operação</th>
					<th onclick="sortTable(4, 'str');">Separador</th>
					<th onclick="sortTable(5, 'str');">Status Separação</th>
					<th onclick="sortTable(6, 'str');">Status Conferência</th>
					<th onclick="sortTable(7, 'date');">Dt. do Movimento</th>
					<th onclick="sortTable(8, 'num');">Nro. Nota</th>
					<th onclick="sortTable(9, 'num');">Empresa</th>
					<th onclick="sortTable(10, 'numStr');">Parceiro</th>
					<th onclick="sortTable(11, 'str');">Descrição (Tipo de Operação)</th>
					<th onclick="sortTable(12, 'num');">Ordem de Carga</th>
					<th onclick="sortTable(13, 'num');">Sequência da Carga</th>
					<th onclick="sortTable(14, 'num');">Qtd. Volumes</th>
					<th onclick="sortTable(15, 'str');">Razão social</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="conferenciasList">

			</tbody>
		</table>
	</div>
</body>

</html>