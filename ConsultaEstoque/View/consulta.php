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
	<link rel="stylesheet" href="../../../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="../../../node_modules/bootstrap/dist/css/bootstrap.min.css">
	<script src="../../../node_modules/jquery/dist/jquery.min.js"></script>
	<script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../components/emailFoto/js/emailFoto.js"></script>    
	<script src="../Controller/ConsultaController.js"></script>
	<title>Consulta Estoque</title>
</head>

<body>

<div class="page">
        <div id="emailFoto"></div>
        <div id="loader" style="display: none;">
            <img style="width: 150px; margin-top: 5%;" src="../../images/soccer-ball-joypixels.gif" alt="Loading...">
        </div>

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
			$volume = $row2['VOLUME'];
			$COMPLDESC = $row2['COMPLDESC'];
			$STATUSPROD = $row2['STATUSPROD'];
			$ULTBIP = $row2['ULTBIP'];

			


			?>

			<div class="header-body-left">
				<div class="infos">
					<div class="informacoes">
						<h6 id="codProduto" data-codprod=<?php echo $produto; ?>>Produto: <?php echo $produto; ?></h6>
						<h6>Referência: <?php echo $codreferencia; ?></h6>
						<input type="hidden" id="referencia" value="<?php echo $codreferencia; ?>">
						<h6>Preço venda: R$<?php echo str_replace('.', ',', $precovenda); ?></h6>
						<h6>Ref. Fornecedores: <?php echo str_replace('.', ',', $fornecedores); ?></h6>
						<h6>Complemento: <?php echo $COMPLDESC; ?></h6>
						<h6>Último Bip: <?php echo $ULTBIP; ?></h6>
						<h6>Produção: <?php echo $STATUSPROD; ?></h6>

					</div>
					<div class="infos-2">
						<h6>Media venda: <?php echo $mediavenda; ?></h6>
						<h6>Agrupamento mínimo: <?php echo $agrupmin; ?></h6>
						<h6>Descrição: <?php echo $descrprod; ?></h6>
						<h6>Vol.: <?php echo $volume; ?></h6>						
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
						
					</div>
				</div>
			</div>
		</div>


		<div class="image d-flex justify-content-center" id="imagemproduto">
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
						Max./Padrão
					</th>
					<th class="border-top-right-radius">Controle</th>
					<th class="border-top-right-radius">Status Contagem</th>

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
										echo  "<span id='editMaxBtn' data-bs-toggle='modal' data-bs-target='#editModal' onclick='openEditModal($rowId)'>";
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
						<td><?php echo $row2['STATUSCONT']; ?></td>
						
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
							<button id="atualizaValorBtn" onclick="atualizarNovoValor();" type="button" class="btn btn-primary fw-bold w-100" style="background-color: var(--color-pad) !important; border-color: var(--color-pad) !important" data-bs-dismiss="modal">Salvar</button>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</body>

</html>