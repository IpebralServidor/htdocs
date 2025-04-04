<?php
include "conexaophp.php";
require_once 'App/auth.php';

$usuconf = $_SESSION["idUsuario"];

$tsqlNomeUsu = "SELECT NOMEPARC FROM TGFPAR WHERE CODPARC = (
		SELECT codparc FROM TSIUSU WHERE CODUSU = $usuconf)";
$stmtNomeUsu = sqlsrv_query($conn, $tsqlNomeUsu);
$rowNomeUsu = sqlsrv_fetch_array($stmtNomeUsu, SQLSRV_FETCH_NUMERIC);

$a = array(2, 100, 3266, 42, 7257, 106, 692);
$b = array(2, 100, 3266, 42, 7257, 692, 1696, 32, 3, 3711, 36, 25, 3782, 82, 4041, 3370, 3149, 3254, 602, 605, 608, 603, 3320, 3563, 139, 196,12789,141,3930,12728, 7129, 195, 7125);

$tsqlAdmin = "SELECT AD_PERMISSAO_CONFERENCIA FROM TSIUSU WHERE CODUSU = $usuconf";
$stmtAdmin = sqlsrv_query($conn, $tsqlAdmin);
$row_countAdmin = sqlsrv_fetch_array($stmtAdmin, SQLSRV_FETCH_NUMERIC);

$tsqlNotas = "  SELECT TOP 10 
						TGFCAB.NUNOTA, 
						TGFCAB.CODPARC, 
						DTMOV, 
						TGFPAR.NOMEPARC, 
						VLRNOTA 
					FROM TGFCAB INNER JOIN 
						AD_TGFAPONTAMENTOATIVIDADE ATV ON ATV.NUNOTA = TGFCAB.NUNOTA INNER JOIN
						TGFPAR ON TGFPAR.CODPARC = TGFCAB.CODPARC
					WHERE ATV.CODUSU = $usuconf
					GROUP BY TGFCAB.NUNOTA, TGFCAB.CODPARC, DTMOV, TGFPAR.NOMEPARC, VLRNOTA 
					ORDER BY DTMOV DESC ";
$stmtNotas = sqlsrv_query($conn, $tsqlNotas);

?>







<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="css/menu2.css?v=<?= time() ?>">
	<link rel="stylesheet" href="./node_modules/@fortawesome/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
	<script src="./node_modules/jquery/dist/jquery.min.js"></script>
	<script src="./node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
	<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="./components/popUpAviso/js/popUpAviso.js"></script>
	<title>Menu</title>
</head>

<body>

	

	<div class="page">
		<div class="dropdown">
			<button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fa-solid fa-bars"></i>
			</button>
			<div class="dropdown-menu" style="display: flex; flex-direction: column;" aria-labelledby="dropdownMenuButton">

				<div id="div-logo" style="text-align: center; margin-left: 10px; margin-right: 10px; margin-bottom: 10px;">
					<img src="images/logo ipebral.png" alt="" class="img-logo">
				</div>
				<a class="dropdown-item" href="#" onclick="abrirLeitorQRCode()">
					<i class="fa-solid fa-qrcode"></i>
					<span class="span-menu">Configurar leitor QR Code</span>
				</a>
				<a class="dropdown-item" href="#" onclick="abrirArquivo('hierarquiaCod')">
					<i class="fa-solid fa-file"></i>
					<span class="span-menu">Hierarquia de Códigos</span>
				</a>
				<a class="dropdown-item" href="#" onclick="abrir()">
					<i class="fa-solid fa-lock"></i>
					<span class="span-menu">Alterar senha</span>
				</a>
				<a class="dropdown-item" href="logout.php">
					<i class="fa-solid fa-right-from-bracket"></i>
					<span class="span-menu">Logout</span>
				</a>
				<div id="nomefoto" style="text-align: center; margin-top: auto;">

					<?php
					$tsql2 = " SELECT ISNULL(FOTO, (SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000)) FROM TSIUSU WHERE CODUSU = $usuconf";
					$stmt2 = sqlsrv_query($conn, $tsql2);
					$row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC);

					echo '<img src="data:image/jpeg;base64,' . base64_encode($row2[0]) . '" alt="" class="img-profile" style="width: 70px; height: auto;">';
					?>
					<span id="nome-usu" style="margin-left: 5px; margin-right: 10px; white-space: nowrap;"><?php echo $rowNomeUsu[0] ?></span>


				</div>
			</div>
		</div>
		<div class="main">
			<div class="">
				<div class="div-card">

					<?php if ($row_countAdmin[0] == 'A') { ?>
						<a href="./SistemaConferencia/View/listaconferenciaadmin.php" class="cardStyle">
							<div class="padding">
								<div class="icon-card">
									<i class="fa-solid fa-check-to-slot" style="background-color: #fa5a7d"></i>
								</div>
								<span>Conferência Admin</span>
							</div>
						</a>
					<?php } ?>

					<a href="./SistemaConferencia/View/listaconferencia.php" class="cardStyle">
						<div class="padding">
							<div class="icon-card">
								<i class="fa-solid fa-circle-check" style="background-color: #fa5a7d"></i>
							</div>
							<span>Conferência</span>
						</div>
					</a>

					<a href="./SistemaEstoque/View" class="cardStyle">
						<div class="padding">
							<div class="icon-card">
								<i class="fa-solid fa-truck-ramp-box" style="background-color: #bf83ff"></i>
							</div>
							<span>Entrada de mercadorias</span>
						</div>
					</a>
					<?php
					$tsql = "SELECT DISTINCT CASE 
												WHEN ITE.CODLOCALORIG = 2018888 THEN 5
												ELSE LEFT(ITE.CODLOCALORIG, 1)
											END AS NOTIFICATION
							FROM TGFCAB CAB INNER JOIN
								TGFITE ITE ON CAB.NUNOTA = ITE.NUNOTA
							WHERE CAB.STATUSNOTA = 'A'
							AND CAB.AD_PEDIDOECOMMERCE = 'TRANSF_PENDENCIA'
							AND CAB.AD_GARANTIAVERIFICADA = 'S'
							AND ITE.SEQUENCIA > 0";

					$result = sqlsrv_query($conn, $tsql);
					$notifications = [];

					// Coletando os resultados
					if ($result) {
						while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
							$notifications[] = $row['NOTIFICATION'];
						}
						sqlsrv_free_stmt($result);
					}
					?>
					<a href="./SistemaReabastecimento/View" class="cardStyle" style="position: relative;">
						<div class="padding">
							<div class="icon-card">
								<i class="fa-solid fa-right-left" style="background-color: #bf83ff"></i>
							</div>
							<span>Transferências (Sankhya)</span>

							<?php if (in_array(3, $notifications)) : ?>
								<span class="notification" style="top: 5px; left: 1px;">3</span>
							<?php endif; ?>

							<?php if (in_array(2, $notifications)) : ?>
								<span class="notification" style="top: 5px; left: 50%; transform: translateX(-50%);">2</span>
							<?php endif; ?>

							<?php if (in_array(5, $notifications)) : ?>
								<span class="notification" style="top: 5px; right: 1px;">5</span>
							<?php endif; ?>
						</div>
					</a>

					<a href="./TransferenciaSimples/View/transfsimples.php" class="cardStyle">
						<div class="padding">
							<div class="icon-card">
								<i class="fa-solid fa-arrow-down-up-across-line" style="background-color: #bf83ff"></i>
							</div>
							<span>Transferência simples</span>
						</div>
					</a>

					<?php if (in_array($usuconf, $a, true)) { ?>
						<a href='./Manutencao' class="cardStyle">
							<div class="padding">
								<div class="icon-card">
									<i class="fa-solid fa-gear" style="background-color: #04BFAD"></i>
								</div>
								<span>Manutenção</span>
							</div>
						</a>
					<?php } ?>



					<a href="#" onclick="abrirPopRetirarCaixa()" class="cardStyle">
						<div class="padding">
							<div class="icon-card">
								<i class="fa-solid fa-box" style="background-color: #ff947a"></i>
							</div>
							<span>Retirar caixa gôndola</span>
						</div>
					</a>
					<a href="./SistemaTransferencias" class="cardStyle">
						<div class="padding">
							<div class="icon-card">
								<i class="fa-solid fa-right-left" style="background-color: #ff947a"></i>
							</div>
							<span>Alocar caixa gôndola</span>
						</div>
					</a>

					<a href="./ConsultaEstoque/View/" class="cardStyle">
						<div class="padding">
							<div class="icon-card">
								<i class="fa-solid fa-boxes-packing" style="background-color: #3cd856"></i>
							</div>
							<span>Consulta de estoque</span>
						</div>
					</a>
					<?php if (in_array($usuconf, $b, true)) { ?>
						<a href="./SistemaInventario/View/index.php" class="cardStyle">
							<div class="padding">
								<div class="icon-card">
									<i class="fa-solid fa-calculator" style="background-color: #74C0FC"></i>
								</div>
								<span>Inventário</span>
							</div>
						</a>
					<?php } ?>
					<a href="./DashboardPendencias/View/index.php" class="cardStyle">
						<div class="padding">
							<div class="icon-card">
								<i class="fa-solid fa-table-list" style="background-color: #FFD43B"></i>
							</div>
							<span>Dashboard Pendências</span>
						</div>
					</a>
					<a href="./SistemaContagemEntrada/View/index.php" class="cardStyle">
						<div class="padding">
							<div class="icon-card">
								<i class="fa-solid fa-scale-unbalanced" style="background-color:rgb(255, 59, 245)"></i>
							</div>
							<span>Contagem Ent.Mercadorias</span>
						</div>
					</a>
				</div>
			</div>

			<div class="content table-content">
				<div class="div-notes">
					<div class="header-options">
						<span>Últimas notas realizadas</span>
					</div>
					<div class="div-table">
						<table>
							<thead>
								<tr>
									<th>Nº Nota</th>
									<th>Cód Parceiro</th>
									<th>Data de Movimentação</th>
									<th>Nome do parceiro</th>
									<th>Valor da nota</th>
								</tr>
							</thead>
							<tbody>
								<?php while ($rowNotas = sqlsrv_fetch_array($stmtNotas, SQLSRV_FETCH_ASSOC)) { ?>
									<tr>
										<td><?php echo $rowNotas['NUNOTA']; ?></td>
										<td><?php echo $rowNotas['CODPARC']; ?></td>
										<td><?php echo date_format($rowNotas['DTMOV'], "d/m/Y"); ?></td>
										<td><?php echo $rowNotas['NOMEPARC']; ?></td>
										<td><?php echo 'R$' . $rowNotas['VLRNOTA']; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>
	</div>

	 <!-- Bloco do popup -->
	 


	<!-- PopUp Avisos -->

	<div id="popUpAviso"></div>


									
	<!-- pop up LOGIN e CADASTRO -->
	<div class="popup" id="popAlterarSenha">
		<div class="overlay"></div>
		<div class="content">
			<div style="width: 100%;">
				<div class="close-btn" onclick="abrir()">
					<i class="fa-solid fa-xmark"></i>
					<!-- <i class="fa-solid fa-circle-xmark"></i> -->
				</div>

				<div class="div-form">
					<form method="post" id="form_alterasenha" action="" class="form">
						<label> Nova Senha:</label>
						<input type="password" name="senha_alt" required>

						<label>Confirmar Senha:</label>
						<input type="password" name="senha_conf" required>

						<button name="AlteraSenha" id="btn-alterasenha">Confirmar</button>
					</form>
				</div>
			</div>

		</div>
	</div>

	<div class="popup" id="popRetirarCaixa">
		<div class="overlay"></div>
		<div class="content">
			<div style="width: 100%;">
				<div class="close-btn" onclick="abrirPopRetirarCaixa()">
					<i class="fa-solid fa-xmark"></i>
				</div>

				<div class="div-form">
					<div class="form">
						<label>Empresa:</label>
						<select id="filtroEmpresa" name="status" class="form-control">
							<option value="1">1</option>
							<option value="6">6</option>
							<option value="7">7</option>
						</select>

						<button name="confirmaFiltroRetirarCaixa" id="confirmaFiltroRetirarCaixa" onclick="confirmaFiltroRetirarCaixa()">Confirmar</button>
					</div>
				</div>
			</div>

		</div>
	</div>


	<div class="popup" id="popLeitorQRCode">
		<div class="overlay"></div>
		<div class="content">
			<div style="width: 100%;">
				<div class="close-btn" onclick="abrirLeitorQRCode()">
					<i class="fa-solid fa-xmark"></i>
				</div>
				<div class="div-form" style="display: flex; flex-direction: row;">
					<div class="form">
						<strong style="white-space: nowrap;">- Pareamento Bluetooth: </strong>
						<img src="images/pareamentoBluetooth.jpeg" alt="" style="margin-bottom: 40px;">
						<strong style="white-space: nowrap;">- Tab Automático: </strong>
						<img src="images/tabAutomatico.jpeg" alt="">
					</div>
					<div class="form" style="margin-left: 40px;">
						<strong style="white-space: nowrap;">- Resetar leitor: </strong>
						<img src="images/resetScan.jpeg" alt="">
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php

	if (isset($_POST['AlteraSenha'])) {

		$senhaalt = $_POST['senha_alt'];
		$senhaconf = $_POST['senha_conf'];

		if ($senhaalt === $senhaconf) {
			$tsql = "UPDATE TSIUSU SET AD_SENHA = '$senhaconf' WHERE CODUSU = $usuconf";
			$stmt = sqlsrv_query($conn, $tsql);
		} else {
			echo "<script> alert('As senhas são diferentes. Digite a mesma senha nas duas caixas de texto!') </script>";
		}
	}

	
	?>
	<script type="text/javascript">
		var mobile = document.getElementById("div-cabecalho-2");

		function abrir() {
			document.getElementById('popAlterarSenha').classList.toggle("active");
		}

		function abrirPopRetirarCaixa() {
			document.getElementById('popRetirarCaixa').classList.toggle("active");
		}

		function abrirLeitorQRCode() {
			document.getElementById('popLeitorQRCode').classList.toggle("active");
		}

		function abrirArquivo(arquivo) {
			if (arquivo === 'hierarquiaCod') {
				window.open('./Files/Hierarquia dos Códigos da Ipebral.pdf');
			}
		}

		function confirmaFiltroRetirarCaixa() {
			window.location.href = 'DesativaProdutoEndereco/View/index.php?codemp=' + document.getElementById('filtroEmpresa').value;
		}

		function menuM() {
			mobile.style.display = "block";
		}

		function fecharmobile() {
			mobile.style.display = "none";
		}
	</script>
</body>

</html>