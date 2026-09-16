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
			$mediaproducao = $row2['MEDIAPA'];
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
						<button onclick="abrirPopMediaVenda()"
							style="
								background: linear-gradient(135deg, #0d6efd, #0b5ed7);
								color: #fff;
								border: none;
								border-radius: 8px;
								padding: 10px 10px;
								font-size: 12px;
								font-weight: 600;
								cursor: pointer;
								display: inline-flex;
								align-items: center;
								gap: 8px;
								box-shadow: 0 4px 10px rgba(13,110,253,.35);
								transition: all .2s ease;
							"
							onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 15px rgba(13,110,253,.45)'"
							onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 10px rgba(13,110,253,.35)'">

							<i class="fa-solid fa-chart-line"></i>
							Médias de Venda
						</button>
						<h6></h6>
						<h6>Media produção: <?php echo $mediaproducao; ?></h6>
						<h6>Agrup. mínimo: <?php echo $agrupmin; ?></h6>
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

		<div class="popup" id="popupMediaVenda">
    <div class="overlay"></div>

    <div class="content"
        style="   width:550px;
        max-width:95%;
        max-height:85vh;
        margin:60px auto;
        background:#fff;
        border-radius:15px;
        overflow-y:auto;
		top: 550px;
        box-shadow:0 10px 35px rgba(0,0,0,.35);">

        <!-- Cabeçalho -->
        <div style="background:#0d6efd;color:#fff;padding:18px 20px;
                    display:flex;justify-content:space-between;align-items:center;">

            <h3 style="margin:0;">
                <i class="fa fa-chart-line"></i> Médias de Venda
            </h3>

            <i class="fa-solid fa-xmark"
                style="font-size:24px;cursor:pointer;"
                onclick="fecharPopmediaVenda()"></i>
        </div>

        <!-- Corpo -->
        <div style="padding:20px;">

            <div style="display:grid;
                        grid-template-columns:repeat(2,1fr);
                        gap:12px;">

                <!-- Card -->
                <div style="background:#f8f9fa;border-left:5px solid #0d6efd;
                            padding:12px;border-radius:8px;">
                    <strong>Emp. 1 + 7</strong><br>
                    <span style="font-size:22px;color:#0d6efd;font-weight:bold;">
                        <?php
                        $tsql = "SELECT ROUND(SUM(MEDIA6),2) MEDIA6
                                 FROM AD_MEDIAVENDAEMP
                                 WHERE CODEMP IN (1,7)
                                 AND CODPROD = $codprod";
                        $stmt = sqlsrv_query($conn,$tsql);
                        $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
                        echo $row["MEDIA6"];
                        ?>
                    </span>
                </div>

                <div style="background:#f8f9fa;border-left:5px solid #198754;
                            padding:12px;border-radius:8px;">
                    <strong>Emp. 3 + 6</strong><br>
                    <span style="font-size:22px;color:#198754;font-weight:bold;">
                        <?php
                        $tsql = "SELECT ROUND(SUM(MEDIA6),2) MEDIA6
                                 FROM AD_MEDIAVENDAEMP
                                 WHERE CODEMP IN (3,6)
                                 AND CODPROD = $codprod";
                        $stmt = sqlsrv_query($conn,$tsql);
                        $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
                        echo $row["MEDIA6"];
                        ?>
                    </span>
                </div>

                <div style="background:#f8f9fa;padding:12px;border-radius:8px;">
                    <strong>Empresa 1</strong><br>
                    <span style="font-size:20px;font-weight:bold;">
                        <?php
                        $tsql="SELECT ROUND(SUM(MEDIA6),2) MEDIA6
                               FROM AD_MEDIAVENDAEMP
                               WHERE CODEMP=1
                               AND CODPROD=$codprod";
                        $stmt=sqlsrv_query($conn,$tsql);
                        $row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
                        echo $row["MEDIA6"];
                        ?>
                    </span>
                </div>

                <div style="background:#f8f9fa;padding:12px;border-radius:8px;">
                    <strong>Empresa 3</strong><br>
                    <span style="font-size:20px;font-weight:bold;">
                        <?php
                        $tsql="SELECT ROUND(SUM(MEDIA6),2) MEDIA6
                               FROM AD_MEDIAVENDAEMP
                               WHERE CODEMP=3
                               AND CODPROD=$codprod";
                        $stmt=sqlsrv_query($conn,$tsql);
                        $row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
                        echo $row["MEDIA6"];
                        ?>
                    </span>
                </div>

                <div style="background:#f8f9fa;padding:12px;border-radius:8px;">
                    <strong>Empresa 6</strong><br>
                    <span style="font-size:20px;font-weight:bold;">
                        <?php
                        $tsql="SELECT ROUND(SUM(MEDIA6),2) MEDIA6
                               FROM AD_MEDIAVENDAEMP
                               WHERE CODEMP=6
                               AND CODPROD=$codprod";
                        $stmt=sqlsrv_query($conn,$tsql);
                        $row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
                        echo $row["MEDIA6"];
                        ?>
                    </span>
                </div>

                <div style="background:#f8f9fa;padding:12px;border-radius:8px;">
                    <strong>Empresa 7</strong><br>
                    <span style="font-size:20px;font-weight:bold;">
                        <?php
                        $tsql="SELECT ROUND(SUM(MEDIA6),2) MEDIA6
                               FROM AD_MEDIAVENDAEMP
                               WHERE CODEMP=7
                               AND CODPROD=$codprod";
                        $stmt=sqlsrv_query($conn,$tsql);
                        $row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
                        echo $row["MEDIA6"];
                        ?>
                    </span>
                </div>

                <div style="background:#f8f9fa;padding:12px;border-radius:8px;
                            grid-column:1 / span 2;">
                    <strong>Empresa 10</strong><br>
                    <span style="font-size:20px;font-weight:bold;">
                        <?php
                        $tsql="SELECT ROUND(SUM(MEDIA6),2) MEDIA6
                               FROM AD_MEDIAVENDAEMP
                               WHERE CODEMP=10
                               AND CODPROD=$codprod";
                        $stmt=sqlsrv_query($conn,$tsql);
                        $row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
                        echo $row["MEDIA6"];
                        ?>
                    </span>
                </div>

				<div style="background:#f8f9fa;padding:12px;border-radius:8px;
                            grid-column:1 / span 2;">
                    <strong>Empresa 0(TODAS)</strong><br>
                    <span style="font-size:20px;font-weight:bold;">
                        <?php
                        $tsql="SELECT ROUND(SUM(MEDIA6),2) MEDIA6
                               FROM AD_MEDIAVENDAEMP
                               WHERE CODEMP=0
                               AND CODPROD=$codprod";
                        $stmt=sqlsrv_query($conn,$tsql);
                        $row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
                        echo $row["MEDIA6"];
                        ?>
                    </span>
                </div>

            </div>

            <div style="text-align:center;margin-top:25px;">
                <button
                    onclick="fecharPopmediaVenda()"
                    style="background:#0d6efd;
                           color:#fff;
                           border:none;
                           padding:12px 35px;
                           border-radius:8px;
                           font-size:16px;
                           cursor:pointer;
                           font-weight:bold;">
                    <i class="fa fa-check"></i> Fechar
                </button>
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
							Deseja alterar máxima?: <span style="color: red">*</span><span id="prodDelete"></span>
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