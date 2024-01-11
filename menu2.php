
<?php
	include "conexaophp.php";
	require_once 'App/auth.php';

	$usuconf = $_SESSION["idUsuario"];

	$tsqlNomeUsu = "SELECT NOMEPARC FROM TGFPAR WHERE CODPARC = (
		SELECT codparc FROM TSIUSU WHERE CODUSU = $usuconf)";
	$stmtNomeUsu = sqlsrv_query($conn, $tsqlNomeUsu);
	$rowNomeUsu = sqlsrv_fetch_array($stmtNomeUsu, SQLSRV_FETCH_NUMERIC);

	$a = array(2, 3274, 3266, 42, 7257, 106);

	$tsqlAdmin = "SELECT AD_PERMISSAO_CONFERENCIA FROM TSIUSU WHERE CODUSU = $usuconf";
	$stmtAdmin = sqlsrv_query($conn, $tsqlAdmin);
	$row_countAdmin = sqlsrv_fetch_array($stmtAdmin, SQLSRV_FETCH_NUMERIC);

	$tsqlNotas = "	SELECT TOP 10 
						NUNOTA, 
						TGFCAB.CODPARC, 
						DTMOV, 
						TGFPAR.NOMEPARC, 
						VLRNOTA 
					FROM TGFCAB INNER JOIN 
						TGFPAR ON TGFPAR.CODPARC = TGFCAB.CODPARC
					WHERE TGFCAB.CODUSU = $usuconf
					ORDER BY DTMOV DESC ";
	$stmtNotas = sqlsrv_query($conn, $tsqlNotas);

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/menu.css?v=<?= time() ?>">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;600&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;600&family=Overpass:wght@700&family=Roboto&display=swap" rel="stylesheet">
	<title>Menu</title>
</head>

<body>
	<div class="page-menu">
		<div class="side-menu">
			<div class="side-menu-content">
				<div>
					<div class="div-logo">
						<img src="images/logo ipebral.png" alt="" class="img-logo">
					</div>

					<div class="menu">
						
						<?php if ($row_countAdmin[0] == 'A') { ?>
							<a href="./SistemaConferencia/listaconferenciaadmin.php">
								<div class="side-menu-itens">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
										<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
										<path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
									</svg>
									<span>Conferência admin</span>
								</div>
							</a>
						<?php } ?>

						<a href="./SistemaConferencia/listaconferencia.php">
							<div class="side-menu-itens">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
									<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
									<path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
								</svg>
								<span>Conferência</span>
							</div>
						</a>
						
						<a href="./ConsultaEstoque">
							<div class="side-menu-itens">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
									<path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
								</svg>
								<span>Consulta de estoque</span>
							</div>
						</a>

						<a href="./SistemaEstoque">
							<div class="side-menu-itens">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-right" viewBox="0 0 16 16">
									<path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5m14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5"/>
								</svg>
								<span>Entrada de mercadorias</span>
							</div>
						</a>

						<?php if (in_array($usuconf, $a, true)) { ?>
							<a href='./Manutencao'>
								<div class="side-menu-itens">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
										<path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
										<path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
									</svg>
									<span>Manutenção</span>
								</div>
							</a>
						<?php } ?>

						<a href="./SistemaReabastecimento">
							<div class="side-menu-itens">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive" viewBox="0 0 16 16">
									<path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5zm13-3H1v2h14zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
								</svg>
								<span>Transferências</span>
							</div>	
						</a>			
					</div>
				</div>

				<div>
					<hr class="hr">
					<div class="div-profile">
						<?php
							$tsql2 = " SELECT ISNULL(FOTO, (SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000)) FROM TSIUSU WHERE CODUSU = $usuconf";
							$stmt2 = sqlsrv_query( $conn, $tsql2);
							$row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC);

							echo '<img src="data:image/jpeg;base64,'.base64_encode($row2[0]).'" alt="" class="img-profile">';
							
						?>
						<span><?php echo $rowNomeUsu[0] ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="content">
			<div class="main-content">
				<div class="header">
					<div class="header-title">
						<span>Dashboard</span>
					</div>
					<div class="div-header-functions">
						<a href="#" onclick="abrir()">
							<div class="header-functions">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-lock" viewBox="0 0 16 16">
									<path d="M8 5a1 1 0 0 1 1 1v1H7V6a1 1 0 0 1 1-1m2 2.076V6a2 2 0 1 0-4 0v1.076c-.54.166-1 .597-1 1.224v2.4c0 .816.781 1.3 1.5 1.3h3c.719 0 1.5-.484 1.5-1.3V8.3c0-.627-.46-1.058-1-1.224M6.105 8.125A.637.637 0 0 1 6.5 8h3a.64.64 0 0 1 .395.125c.085.068.105.133.105.175v2.4c0 .042-.02.107-.105.175A.637.637 0 0 1 9.5 11h-3a.637.637 0 0 1-.395-.125C6.02 10.807 6 10.742 6 10.7V8.3c0-.042.02-.107.105-.175"/>
									<path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1"/>
								</svg>
								<span>Alterar senha</span>
							</div>
						</a>
						
						<a href="logout.php">
							<div class="header-functions">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
									<path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
									<path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
								</svg>
								<span>Logout</span>
							</div>
						</a>
					</div>
				</div>
				<div class="cards-panel">
					<div class="grid-container">
						<div class="col1 col">
							<div class="card-options">
								<div>
									<div class="header-options">
										<span>Menu de opções</span>
									</div>

									<div class="div-profile">
										<?php if ($row_countAdmin[0] == 'A') { ?>
											<a href="./SistemaConferencia/listaconferenciaadmin.php" class="options">	
												<div>
													<div class="img-options">
														<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
															<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
														</svg>
													</div>
													<div class="span-options">
														<span>Conferência ADMIN</span>
													</div>
												</div>
											</a>
										<?php } ?>

										<a href="./SistemaConferencia/listaconferencia.php" class="options yellow">
											<div>
												<div class="img-options ">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
														<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
													</svg>
												</div>
												<div class="span-options">
													<span>Conferência</span>
												</div>
											</div>
										</a>
										
										<a href="./ConsultaEstoque" class="options green">
											<div>
												<div class="img-options">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
														<path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
													</svg>
												</div>
												<div class="span-options">
													<span>Consulta de estoque</span>
												</div>
											</div>
										</a>
										
										<a href="./SistemaEstoque" class="options purple">
											<div>
												<div class="img-options">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-right" viewBox="0 0 16 16">
														<path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5m14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5"/>
													</svg>
												</div>
												<div class="span-options">
													<span>Entrada de mercadorias</span>
												</div>
											</div>
										</a>
										
										<?php if (in_array($usuconf, $a, true)) { ?>
											<a href='./Manutencao' class="options green">
												<div>
													<div class="img-options">
														<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
															<path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
														</svg>
													</div>
													<div class="span-options">
														<span>Manutenção</span>
													</div>
												</div>
											</a>
										<?php } ?>
										<a href="./SistemaReabastecimento" class="options yellow">
											<div>
												<div class="img-options">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive-fill" viewBox="0 0 16 16">
														<path d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1M.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8H.8z"/>
													</svg>
												</div>
												<div class="span-options">
													<span>Transferências</span>
												</div>
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>

						<div class="col3 col">
							<div class="card-notes">
								<div class="div-notes">
									<div class="header-options">
										<span>Últimas notas realizadas</span>
									</div>
									<div class="div-table">
										<table>
											<thead>
												<tr>
													<th>Nº NOTA</th>
													<th>CÓD PARCEIRO</th>
													<th>DATA DE MOVIMENTAÇÃO</th>
													<th>NOME DO PARCEIRO</th>
													<th>VALOR DA NOTA</th>
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
						<div style="grid-row-start: 8;">

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- pop up LOGIN e CADASTRO -->
	<div class="popup" id="popAlterarSenha">
		<div class="overlay"></div>
			<div class="content">
				<div style="padding: 20px; width: 100%;">
					<div class="close-btn" onclick="abrir()">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
							<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
						</svg>
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