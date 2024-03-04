
<?php
	include "conexaophp.php";
	require_once 'App/auth.php';

	$usuconf = $_SESSION["idUsuario"];

	$tsqlNomeUsu = "SELECT NOMEPARC FROM TGFPAR WHERE CODPARC = (
		SELECT codparc FROM TSIUSU WHERE CODUSU = $usuconf)";
	$stmtNomeUsu = sqlsrv_query($conn, $tsqlNomeUsu);
	$rowNomeUsu = sqlsrv_fetch_array($stmtNomeUsu, SQLSRV_FETCH_NUMERIC);

	$a = array(2, 3274, 3266, 42, 7257, 106);
	$b = array(3274, 2, 3149, 3287, 3276);

	$tsqlAdmin = "SELECT AD_PERMISSAO_CONFERENCIA FROM TSIUSU WHERE CODUSU = $usuconf";
	$stmtAdmin = sqlsrv_query($conn, $tsqlAdmin);
	$row_countAdmin = sqlsrv_fetch_array($stmtAdmin, SQLSRV_FETCH_NUMERIC);

	$tsqlNotas = "	SELECT TOP 10 
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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer" >
	<title>Menu</title>
</head>

<body>
	<div class="page">
		<div class="side-menu">
			<div class="side-menu-div">
				<div class="menu">
					<div class="inside-menu-div">
						<div class="div-logo">
							<div id="div-logo">
								<img src="images/logo ipebral.png" alt="" class="img-logo">
							</div>

							<div class="arrow-menu" id="arrow-menu">
								<i class="fa-solid fa-circle-arrow-left" id="arrow-menu"></i>
							</div>
						</div>
					
						<div class="menu-itens">
							<!-- <?php if ($row_countAdmin[0] == 'A') { ?>
								<a href="./SistemaConferencia/listaconferenciaadmin.php" class="a-deactive">
									<div class="side-menu-itens">
										<i class="fa-solid fa-check-to-slot"></i>
										<span class="span-menu">Conferência Admin</span>
									</div>
								</a>
							<?php } ?>

							<a href="./SistemaConferencia/listaconferencia.php" class="a-deactive">
								<div class="side-menu-itens">
									<i class="fa-solid fa-circle-check"></i>
									<span class="span-menu">Conferência</span>
								</div>
							</a>

							<a href="./ConsultaEstoque" class="a-deactive">
								<div class="side-menu-itens">
									<i class="fa-solid fa-boxes-packing"></i>
									<span class="span-menu">Consulta de estoque</span>
								</div>
							</a>

							<a href="./SistemaEstoque" class="a-deactive">
								<div class="side-menu-itens">
									<i class="fa-solid fa-truck-ramp-box"></i>
									<span class="span-menu">Entrada de mercadorias</span>
								</div>
							</a>
						
							<?php if (in_array($usuconf, $a, true)) { ?>
								<a href='./Manutencao' class="a-deactive">
									<div class="side-menu-itens">
										<i class="fa-solid fa-gear"></i>
										<span class="span-menu">Manutenção</span>
									</div>
								</a>
							<?php } ?>
							<a href="./SistemaReabastecimento" class="a-deactive">
								<div class="side-menu-itens">
									<i class="fa-solid fa-right-left"></i>
									<span class="span-menu">Transferências</span>
								</div>	
							</a> -->
							<a href="#" onclick="abrir()">
								<div class="side-menu-itens show">
									<i class="fa-solid fa-lock"></i>
									<span class="span-menu">Alterar senha</span>
								</div>
							</a>	
							<a href="logout.php">
								<div class="side-menu-itens show">
									<i class="fa-solid fa-right-from-bracket"></i>
									<span class="span-menu">Logout</span>
								</div>
							</a>
						</div>
					</div>
					
					
					<div>
						
						<div class="div-profile" id="div-profile">
							<?php
								$tsql2 = " SELECT ISNULL(FOTO, (SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000)) FROM TSIUSU WHERE CODUSU = $usuconf";
								$stmt2 = sqlsrv_query( $conn, $tsql2);
								$row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC);

								echo '<img src="data:image/jpeg;base64,'.base64_encode($row2[0]).'" alt="" class="img-profile">';
							?>
							<span id="nome-usu"><?php echo $rowNomeUsu[0] ?></span>
							
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<div class="main">
			<div class="">
				<div class="div-card">

					<?php if ($row_countAdmin[0] == 'A') { ?>
						<a href="./SistemaConferencia/listaconferenciaadmin.php" class="card">
							<div class="padding">
								<div class="icon-card">
									<i class="fa-solid fa-check-to-slot"  style="background-color: #fa5a7d"></i>
								</div>
								<span>Conferência Admin</span>
							</div>
						</a>
					<?php } ?>

					<a href="./SistemaConferencia/listaconferencia.php" class="card">
						<div class="padding">
							<div class="icon-card">
								<i class="fa-solid fa-circle-check" style="background-color: #ff947a"></i>
							</div>
							<span>Conferência</span>
						</div>
					</a>

					<a href="./ConsultaEstoque" class="card">
						<div class="padding">
							<div class="icon-card">
								<i class="fa-solid fa-boxes-packing" style="background-color: #3cd856"></i>
							</div>
							<span>Consulta de estoque</span>
						</div>
					</a>

					<a href="./SistemaEstoque" class="card">
						<div class="padding">
							<div class="icon-card">
								<i class="fa-solid fa-truck-ramp-box" style="background-color: #bf83ff"></i>
							</div>
							<span>Entrada de mercadorias</span>
						</div>
					</a>

					<?php if (in_array($usuconf, $a, true)) { ?>
						<a href='./Manutencao' class="card">
							<div class="padding">
								<div class="icon-card">
									<i class="fa-solid fa-gear" style="background-color: #3cd856"></i>
								</div>
								<span>Manutenção</span>
							</div>
						</a>
					<?php } ?>

					<a href="./SistemaReabastecimento" class="card">
						<div class="padding">
							<div class="icon-card">
								<i class="fa-solid fa-right-left" style="background-color: #ff947a"></i>
							</div>
							<span>Transferências</span>
						</div>
					</a>

					<?php if (in_array($usuconf, $b, true)) { ?>
						<a href="./SistemaReabastecimentoHomol" class="card">
							<div class="padding">
								<div class="icon-card">
									<i class="fa-solid fa-right-left" style="background-color: #ff947a"></i>
								</div>
								<span>Transferências Homol</span>
							</div>
						</a>
					<?php } ?>
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
								<?php while( $rowNotas = sqlsrv_fetch_array($stmtNotas, SQLSRV_FETCH_ASSOC)) { ?>
									<tr>
										<td><?php echo $rowNotas['NUNOTA']; ?></td>
										<td><?php echo $rowNotas['CODPARC']; ?></td>
										<td><?php echo date_format($rowNotas['DTMOV'], "d/m/Y"); ?></td>
										<td><?php echo $rowNotas['NOMEPARC']; ?></td>
										<td><?php echo 'R$' .$rowNotas['VLRNOTA']; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			
		</div>
	</div>





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
	
	<script src="https://kit.fontawesome.com/9c65c9f9d0.js" crossorigin="anonymous"></script>
	<script src="js/menu.js"></script>
	<script type="text/javascript">
		var mobile = document.getElementById("div-cabecalho-2");

		function abrir() {
			document.getElementById('popAlterarSenha').classList.toggle("active");
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