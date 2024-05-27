<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

$codprod = $_REQUEST['codprod'];
$codusu = $_SESSION["idUsuario"];
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="../css/consulta.css">
	<link rel="stylesheet" type="text/css" href="../css/main.css?v=<?= time() ?>">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="../Controller/ConsultaController.js"></script>
	<title>Consulta Estoque</title>
</head>

<body>
	<div class="img-voltar">
		<a href="./">
			<img src="../images/216446_arrow_left_icon.png" />
		</a>
	</div>
	<div id="loader" style="display: none;">
		<img style=" width: 150px; margin-top: 5%;" src="../images/soccer-ball-joypixels.gif">
	</div>
	<div class="container">
		<div class="header-body">
			<?php
			$params = array($codprod, $codusu);
			$tsql = "SELECT * FROM [sankhya].[AD_FNT_InfoProduto_ConsultaEstoque](?, ?)";

			$stmt = sqlsrv_query($conn, $tsql, $params);
			$row2 = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

			$produto = $row2['CODPROD'];
			$codreferencia = $row2['REFERENCIA'];
			$descrprod = $row2['DESCRPROD'];
			$qtdmaxlocal = $row2['AD_QTDMAXLOCAL'];
			$mediavenda = $row2['MEDIA'];
			$agrupmin = $row2['AGRUPMIN'];
			$precovenda = $row2['PRECOVENDA'];
			$fornecedores = $row2['OBSETIQUETA'];

			?>

			<div class="header-body-left">
				<div class="infos">
					<div class="informacoes">
						<h6 id="codProduto" data-codprod=<?php echo $produto; ?>>Produto: <?php echo $produto; ?></h6>
						<h6>Referência: <?php echo $codreferencia; ?></h6>
						<h6>Preço venda: R$<?php echo str_replace('.', ',', $precovenda); ?></h6>
						<h6>Ref. Fornecedores: <?php echo str_replace('.', ',', $fornecedores); ?></h6>
					</div>
					<div class="infos-2">
						<h6>Media venda: <?php echo $mediavenda; ?></h6>
						<h6>Agrupamento mínimo: <?php echo $agrupmin; ?></h6>
						<h6>Descrição: <?php echo $descrprod; ?></h6>
						<h6>Local padrão:
							<?php
							$tsql = "	SELECT DISTINCT CODLOCALPAD 
											FROM TGFPEM WHERE CODPROD = $codprod
											  AND CODEMP IN (1,7)";
							$stmt = sqlsrv_query($conn, $tsql);
							while ($row2 = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								echo $row2["CODLOCALPAD"] . " | ";
							}
							?>
						</h6>
					</div>
				</div>
			</div>
		</div>


		<div class="image d-flex justify-content-center">
			<?php
			if ($codprod != '') {
				$tsql2 = " select ISNULL(IMAGEM,(SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000))
							from TGFPRO
							where CODPROD = $codprod";
			} else {
				$tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000";
			}
			$stmt2 = sqlsrv_query($conn, $tsql2);
			$row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC);

			echo '<img style="vertical-align: middle; margin: auto; max-width: 100%; max-height: 166px;" src="data:image/jpeg;base64,' . base64_encode($row2[0]) . '"/>';
			?>
		</div>

		<div class="overflow">
			<table class="table">
				<tr class="position-sticky">
					<th class="border-top-left-radius">Emp.</th>
					<th>Cód. Loc.</th>
					<th>Estoque</th>
					<th>Reserv.</th>
					<th>
						Pad./Máx.
					</th>
					<th class="border-top-right-radius">Controle</th>
				</tr>

				<?php
				$tsql2 = "SELECT * FROM [sankhya].[AD_FNT_TabelaEstoque_ConsultaEstoque]('$codprod')";

				$stmt2 = sqlsrv_query($conn, $tsql2);
				$rowId = 0;
				while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
					$rowId++ ?>
					<tr id=<?php echo "$rowId" ?>>
						<td><?php echo $row2['CODEMP']; ?></td>
						<td style="width: 50%"><?php echo $row2['CODLOCAL']; ?></td>
						<td><?php echo $row2['ESTOQUE']; ?></td>
						<td><?php echo $row2['RESERVADO']; ?></td>
						<td>
							<div class="d-flex justify-content-between" style="background-color: transparent !important">
								<div style="text-align: left">
									<?php echo $row2['PADRAO_QTDMAX']; ?>
								</div>
								<div style="text-align: right">
									<?php $pattern = "/X/";
									$codLocalPad = $row2['CODLOCAL'];
									$codEmp = $row2['CODEMP'];
									$qtdMax = $row2['PADRAO_QTDMAX'];
									if (preg_match($pattern, $row2['PADRAO_QTDMAX']) === 1) {
										echo  "<span id='editMaxBtn' data-toggle='modal' data-target='#editModal' onclick='openEditModal($rowId)'>";
										echo "<i class='fa-solid fa-pen' style='color: #d80e0e;'></i>";
										echo "</span>";
									} else {
										echo '';
									};

									?>
								</div>
							</div>
						</td>
						<td><?php echo $row2['CONTROLE'] ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>
		<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="p-3">
						<div class="modal-body fw-bold">
							Editar valor máximo: <span style="color: red">*</span><span id="prodDelete"></span>
						</div>
						<div class="mb-1">
							<input type="number" class="form-control" id="novoMax" step="0.01" value="">
						</div>
						<div class="mt-3">
							<button id="atualizaValorBtn" onclick="atualizarNovoValor();" type="button" class="btn btn-primary fw-bold w-100" style="background-color: var(--color-pad) !important; border-color: var(--color-pad) !important" data-dismiss="modal">Salvar</button>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</body>

</html>