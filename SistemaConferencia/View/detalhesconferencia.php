<?php
include "../../conexaophp.php";
require_once '../App/auth.php';
include '../Etiquetas/WebClientPrint.php';

use Neodynamic\SDK\Web\WebClientPrint;

$codparc = 0;
$usuconf = $_SESSION["idUsuario"];
$funcaoJaExecutada = $_SESSION["funcao"];
$VLR1700 = 0;
$VLR1720 = 0;
$VLR1780 = 0;

if (isset($_SESSION["codbarraselecionado"])) {
	$codbarraselecionado = $_SESSION["codbarraselecionado"];
} else {
	$codbarraselecionado = 0;
}

$nunota2 = $_REQUEST["nunota"];
$tsqlPendente = "select count(1) as contador from [sankhya].[AD_FN_pendencias_CONFERENCIA]($nunota2)";
$stmtPendente = sqlsrv_query($conn, $tsqlPendente);
$rowPendente = sqlsrv_fetch_array($stmtPendente, SQLSRV_FETCH_NUMERIC);
$QtdPendente = $rowPendente[0];


$tsqlStatus = "SELECT [sankhya].[AD_FN_CONFERENCIA_RETORNA_STATUS_DA_NOTA]($nunota2)";
$stmtStatus = sqlsrv_query($conn, $tsqlStatus);
$rowStatus = sqlsrv_fetch_array($stmtStatus, SQLSRV_FETCH_NUMERIC);


$tsqlTimer = "SELECT (SUM(DATEDIFF(sECOND, ISNULL(DTFIM,gETDATE()),DTINIC)) *-1) FROM AD_TGFAPONTAMENTOATIVIDADE WHERE NUNOTA = $nunota2 AND CODUSU = $usuconf";
$stmtTimer = sqlsrv_query($conn, $tsqlTimer);
$rowTimer = sqlsrv_fetch_array($stmtTimer, SQLSRV_FETCH_NUMERIC);

$_SESSION['time'] = $rowTimer[0];

$tsql2 = "  SELECT NUMNOTA,			
					   CONVERT(VARCHAR(MAX),TGFCAB.CODVEND) + ' - ' + APELIDO,
					   CONVERT(VARCHAR(MAX),TGFCAB.CODPARC) + ' - ' + TRIM(RAZAOSOCIAL),
                       TGFCAB.OBSERVACAO,
					   TGFCAB.VLRFRETE,
					   TGFCAB.CODEMP,
					   TGFCAB.AD_SEPARADOR,
					   TGFCAB.CODPARC
				FROM TGFCAB INNER JOIN
					 TGFPAR ON TGFPAR.CODPARC = TGFCAB.CODPARC INNER JOIN
					 TGFVEN ON TGFVEN.CODVEND = TGFCAB.CODVEND
				WHERE NUNOTA = {$nunota2}
							";

$stmt2 = sqlsrv_query($conn, $tsql2);

while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC)) {
	$NUMNOTA = $row2[0];
	$VENDEDOR = $row2[1];
	$PARCEIRO = $row2[2];
	$OBSERVACAO = $row2[3];
	$VLRFRETE = $row2[4];
	$codemp = $row2[5];
	$adseparador = $row2[6];
	$codparc = $row2[7];
}

$tsql3 = "  DECLARE @NUNOTA INT = {$nunota2}
				DECLARE @ULTCOD INT 

				IF ((SELECT NUCONFATUAL FROM TGFCAB WHERE NUNOTA = @NUNOTA) IS NULL)
				BEGIN
					
					SET @ULTCOD = (SELECT ULTCOD + 1 FROM TGFNUM WHERE ARQUIVO = 'TGFCON2')
					UPDATE TGFNUM SET ULTCOD = @ULTCOD WHERE ARQUIVO = 'TGFCON2'
					
					INSERT INTO TGFCON2 (CODUSUCONF,DHFINCONF,DHINICONF,NUCONF,NUCONFORIG,NUNOTADEV,NUNOTAORIG,NUPEDCOMP,QTDVOL,STATUS)
					SELECT $usuconf,
							NULL,
							GETDATE(),
							@ULTCOD,
							NULL,
							NULL,
							@NUNOTA,
							NULL,
							0,
							'A'

					UPDATE TGFCAB SET NUCONFATUAL = @ULTCOD, LIBCONF = 'S' WHERE NUNOTA = @NUNOTA

				END
						";

$stmt3 = sqlsrv_query($conn, $tsql3);

$tsql4 = " exec [sankhya].[AD_STP_MARCALINHA_CONFERENCIA] '$codbarraselecionado', $nunota2";
$stmt4 = sqlsrv_query($conn, $tsql4);
while ($row2 = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_NUMERIC)) {
	$linhamarcada = $row2[0];
}


$tsql4 = "SELECT * FROM [sankhya].[AD_FN_LISTAGEM_TOPS]($nunota2)";

$stmt5 = sqlsrv_query($conn, $tsql4);

while ($row2 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_NUMERIC)) {
	$VLR1700 = $row2[0];
	$VLR1720 = $row2[1];
	$VLR1780 = $row2[2];
	$SEPARADOR = $row2[3];
}

?>

<html>

<head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Detalhes Conferência - <?php echo $usuconf;
									echo $linhamarcada; ?></title>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript" src="jquery-1.8.0.min.js"></script>
	<script src="../Etiquetas/impressao.js"></script>
	<script src="../Controller/DetalhesConferenciaController.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
	<link href="../css/style.css?v=<?= time() ?>" rel="stylesheet" type="text/css" media="all" />
	<link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

	<script>
		sessionStorage.setItem('status', '<?php echo $rowStatus[0] ?>');
	</script>
	<script>
		function deletaprodutos() {
			var checkedCheckboxes = document.querySelectorAll('.checkbox:checked');

			if (checkedCheckboxes[0] == null) {
				alert('Selecione pelo menos uma linha para excluir!');
			} else {
				var referencia = "";
				for (var i = 0; i < checkedCheckboxes.length; i++) {
					var referencia = checkedCheckboxes[i].getAttribute('data-ref');
					var controle = checkedCheckboxes[i].getAttribute('data-parent');
					deletaproduto(<?php echo $nunota2; ?>, referencia, controle);
				}
				alert("Item(s) excluído(s) com sucesso!");
			}
		}

		function inserirpendencias() {
			var checkedCheckboxes = document.querySelectorAll('.checkbox:checked');
			if (checkedCheckboxes[0] == null) {
				alert('Selecione pelo menos uma linha para inserir!');
			} else {
				for (var i = 0; i < checkedCheckboxes.length; i++) {
					referencia = checkedCheckboxes[i].getAttribute('data-codbarra').trim();
					qtdInserir = checkedCheckboxes[i].getAttribute('data-insert');
					if (qtdInserir > 0) {
						inserependencia(<?php echo $nunota2; ?>, referencia, qtdInserir);
					} else {
						alert('Digite uma quantidade válida do item ' + referencia + '.');
					}
				}
			}
		}
	</script>
	<link href="../css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<?php
	$varStatus = $rowStatus[0];
	$tsql2 = "select count(*) from [sankhya].[AD_FN_PRODUTOS_DIVERGENTES_CONFERENCIA]($nunota2)";
	$stmt2 = sqlsrv_query($conn, $tsql2);
	while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC)) {
		$QtdDivergencias = $row2[0];
	}

	$tsql3 = " select isnull(COUNT(1), 0)
		from TGFITE inner join
				TGFCAB ON TGFCAB.NUNOTA = TGFITE.NUNOTA LEFT JOIN
				TGFCOI2 on TGFCOI2.NUCONF = TGFCAB.NUCONFATUAL
					AND TGFCOI2.CODPROD = TGFITE.CODPROD INNER JOIN
				TGFPRO ON TGFPRO.CODPROD = TGFITE.CODPROD
		where TGFITE.NUNOTA = '{$nunota2}'
		group by tgfite.codprod, ISNULL(TGFCOI2.QTDCONF,0)
			having ISNULL(TGFCOI2.QTDCONF,0) <> sum(TGFITE.QTDNEG)
				";

	$stmt3 = sqlsrv_query($conn, $tsql3);

	while ($row2 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_NUMERIC)) {
		$QtdDivCorte = $row2[0];
	}

	if (empty($QtdDivCorte)) {
		$QtdDivCorte = 0;
	}

	?>

	<script type="text/javascript">
		function confirmar_conf() {
			if (<?php echo $QtdDivCorte; ?> > 0 || <?php echo $QtdPendente ?> > 0) {
				abrirconfdivcorte();
				return true;
			} else if (<?php echo $QtdDivergencias ?> == 0) {
				abrirconf();
				return true;
			} else {
				abrirconfdivergencia();
				return true;
			}
		}
	</script>
</head>

<body style="margin: 0;" onload="executarUmaVez(); <?php if ($adseparador == null && $codemp == 7) { ?> abrirconferentes(); <?php } ?> scrollToRow(<?php echo $linhamarcada; ?>)">
	<div id="loader" style="display: none;">
		<img style=" width: 150px; margin-top: 5%;" src="../images/soccer-ball-joypixels.gif">
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

				<?php
				$tsql3 = "SELECT CODUSU, NOMEUSU 
							  FROM TSIUSU 
							  WHERE CODUSU IN (SELECT ITEM FROM SANKHYA.AD_FN_SPLIT((SELECT TEXTO FROM TSIPAR WHERE CHAVE = 'UsuSeparacao7'), ','))
							  ORDER BY NOMEUSU";

				$stmt3 = sqlsrv_query($conn, $tsql3);
				$row_count = sqlsrv_num_rows($stmt3);
				while ($row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_NUMERIC)) {
				?>
					<ul id='list'>
						<li class='conferentes'>
							<button style="width: 100%; height: 100%; background-color:rgba(144,  203,  44,  0); cursor: pointer; " id="conferente-btn" class="conferente-btn" data-user="<?php echo $row3[0]; ?>">
								<?php echo $row3[1]; ?>
							</button>
						</li>
					</ul>
				<?php
				}
				?>
			</table>
			<button class="fechar" onclick="fecharconferentes();">X</button>
		</div>
	</div>

	<div style="width:100%; top: 0; height: 25px; padding-left: 30px; background-color: #3a6070; position: fixed;">
		<table width="100%" id="table">
			<tr>
				<th width="25%">1700: R$ <?php echo $VLR1700 ?></th>
				<th width="25%">1720: R$ <?php echo $VLR1720 ?></th>
				<th width="25%">1780: R$ <?php echo $VLR1780 ?></th>
				<th width="25%">Separador: <?php echo $SEPARADOR ?></th>
			</tr>
		</table>
	</div>
	<div style="margin-top: 2%; position: fixed;">
		<span style="margin-bottom: 0; margin-left: 30px; font-size: 20px;">
			<strong> Nro. Nota: &nbsp </strong> <?php echo $NUMNOTA ?> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
			<strong>Vendedor: </strong> <?php echo $VENDEDOR ?> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
			<strong>Nro. Único: </strong> <?php echo $nunota2; ?> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
			<strong>Parceiro: </strong> <?php echo $PARCEIRO; ?>
		</span>
	</div>

	<div style="margin-left: 30px; margin-top: 4.8%; position: fixed; z-index: 99999;">
		<a href="./listaconferencia.php">
			<aabr title="Voltar"><img style="width: 35px; float: left; padding-right: 20px" src="../images/Seta%20Voltar.png" /></aabr>
		</a>
		Cód. Barras: <input type="text" name="CODBAR" class="text" id='codigodebarra' required>
		Quantidade: <input type="text" name="QUANTIDADE" id="quantidade" class="text" size="5" style="text-align: left;">
		Controle: <input type="text" name="CONTROLE" size="10" id="controle" class="text">
		<button name="conferir" id="conferir" type="submit" value="" style="margin-left: 30px;">Conferir</button>
		<?php
		if ($rowStatus[0] == "A") {
			$colorStatus = "green";
			$valueStatus = "Em andamento";
			$valueF = "Iniciar pausa";
			$class = "pause";
		} else if ($rowStatus[0] == "P") {
			$colorStatus = "yellow";
			$valueStatus = "Em pausa";
			$valueF = "Finalizar pausa";
			$class = "play";
		}
		?>

		<button class="<?php echo $class ?>" id="btnStatus">
			<?php echo $valueF ?>
		</button>

		<strong style="margin-left: 50px;">Conferência: </strong> <?php echo $valueStatus; ?>
		<span style="height: 25px; width: 25px; background-color: <?php echo $colorStatus ?>; border-radius: 50%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<strong id="result_shops" style="margin-left: 50px;"></strong>
		<div id="insereitem" style="display: inline-block; margin-top: 5px;"></div>
	</div>

	<!-- Itens em Conferência-->
	<div id="container" style="width:100%;height: 80%;position: absolute;margin-top: 65px;margin-bottom: 0;padding-left: 0px;padding-right: 0px;right: 0px;bottom: 0px;top: 0px;left: 0px;">
		<div id="ItensConferencia" style="width: 48%; height:48%; display: inline-block; margin-right: 0; overflow: hidden; margin-left: 1%; margin-top: 50px;">
			<div style="background-color: #ADADC7" class="d-flex justify-content-around">
				<div>
					<h4 style="margin: 0 !important; ">Itens em Conferência
						<button style="font-size: 13px;" onclick="confirmar_conf();">Finalizar Conferência</button>
						<button style="font-size: 13px;" onclick="abrirdivergencias();">Produtos Divergentes</button>
						<button style="font-size: 13px;" onclick="abrirImpressaoEtiqueta();">Imprimir etiqueta</button>
						<?php
						$tsql2 = "select count(1) as contador from [sankhya].[AD_FN_pendencias_CONFERENCIA]($nunota2)";
						$stmt2 = sqlsrv_query($conn, $tsql2);
						while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
							$auxiliar = $row2['contador'];
						}
						if ($auxiliar > 0) {
							echo '<button id="btnPendencia" class="btnPendencia" onclick="abrirpendencias();">Pendências</button>';
						}
						?>
					</h4>
				</div>
			</div>
			<div id="popupconf" class="popupconf">
				<div style="margin-top: -10px; text-align: center;width: 100%">
					<br>Qtd. Volume: <input type="text" name="qtdvol" id="qtdvol" class="text" value="" style="margin-top: 5px;">
					<br>Volume: <br><input type="text" name="volume" id="volume" class="text" style="margin-top: 5px;">
					<br>Peso Bruto: <input type="text" minlength="1" name="pesobruto" id="pesobruto" class="text" style="margin-top: 5px;">
					<br>Valor frete: <input type="text" name="frete" id="frete" class="text" style="margin-top: 5px;" value="<?php echo $VLRFRETE; ?>"><br>
					<br>Motivo da divergência:<br>
					<select name="mtvdivergencia" id="mtvdivergencia" class="form-control" <?php if ($QtdDivergencias == 0 && $QtdPendente == 0) {
																								echo 'disabled';
																							} ?>>
						<?php if ($QtdDivergencias > 0 || $QtdPendente > 0) { ?>
							<option value="Pendencia incluida">Pendência incluída</option>
							<option value="Corte de item divergente">Corte de item divergente</option>
							<option value="Desconto por item na nota">Desconto por item na nota</option>
						<?php } else { ?>
							<option value="">Não possui divergência</option>
						<?php } ?>
					</select>
					<br>Observação: <textarea id="observacao" cols="20" rows="20" name="observacao" class="text" style="margin-top: 5px; height: 100px;"><?php echo $OBSERVACAO; ?></textarea>
					<br><input name="confirmar" id="confirmar" type="submit" value="Confirmar" style="cursor: hand; cursor: pointer; margin-top: 2%;">
					<div id="insereitem2" style="display: inline-block; margin-top: 5px;"></div>
					<button class="fechar" onclick="fecharconf();">X</button>
				</div>
			</div>

			<div id="popupObservacao" class="popupconf" style="height: 180px !important">
				<div style="margin-top: -10px; text-align: center;width: 100%">
					<br>Observação: <textarea id="observacao" cols="20" rows="20" name="observacao" class="text" style="margin-top: 5px; height: 100px;" disabled><?php echo $OBSERVACAO; ?></textarea>
					<button class="fechar" onclick="fecharObs();">X</button>
				</div>
			</div>

			<!--
				POP UP de Produtos Divergentes
			-->

			<div id="popupdivergencias" class="popupdivergencias">
				<h6 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-left:15px; padding-top: 2px; width: 90%; display:inline-block;">Produtos com Divergência</h6>
				<div style=" width: 98%; height: 340px; position: absolute; overflow: auto; margin-top: 5px;">
					<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
						<tr>
							<th width="10.6%">Sequencia</th>
							<th width="10.6%">Referência</th>
							<th width="36.6%" style="text-align: center;">Descrição do Produto</th>
							<th width="10.6%" align="center">Complemento</th>
							<th width="12.6%" align="center">Controle</th>
							<th width="12.6%" align="center">Qtd. Conferida</th>
							<th width="16.6%" align="center">Qtd. Pedido</th>
							<th width="16.6%" align="center">Qtd. Abast</th>
						</tr>
						<?php
						$tsql2 = "select * from [sankhya].[AD_FN_PRODUTOS_DIVERGENTES_CONFERENCIA]($nunota2) order by SEQCONF DESC";
						$stmt2 = sqlsrv_query($conn, $tsql2);
						while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC)) {
							$NUCONF = $row2[0];
						?>
							<tr style="cursor: hand; cursor: pointer;">
							<tr>
								<td width="10.6%"><?php echo $row2[0]; ?>&nbsp;</td>
								<td width="10.6%"><?php echo $row2[1]; ?>&nbsp;</td>
								<td width="36.6%"><?php echo $row2[2]; ?>&nbsp;</td>
								<td width="10.6%" align="center"><?php echo $row2[3]; ?>&nbsp;</td>
								<td width="12.6%" align="center"><?php echo $row2[4]; ?></td>
								<td width="12.6%" align="center"><?php echo $row2[5]; ?></td>
								<td width="16.6%" align="center"><?php echo $row2[6]; ?></td>
								<td width="16.6%" align="center"><?php echo $row2[7]; ?></td>
							</tr></a>
						<?php
						}
						?>
					</table>
				</div>
				<button class="fechar" onclick="fechardivergencias();">X</button>
			</div>
			<!-- Fim
			POP UP de Produtos Divergentes
			-->
			<div id="popuppendencias" class="popuppendencias">
				<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-top: 2px; width: 100%;">Produtos com Pendências</h4>
				<!-- Formulário para inserir as pendências -->
				<div style="background-color: red; margin: 0">
					<div style=" width: 98.15%; height: 340px; position: absolute; overflow: auto; margin-top: 5px;">
						<table width="98%" border="1px" style="margin-top: 5px; margin-left: 0px;" id="tablePendencias">
							<tr>
								<th width="1%" style="margin-right: 0; "><input type="checkbox" id="select_all" value="" /></th>
								<th width="10.0%" align="center">Referencia</th>
								<th width="40.0%" style="text-align: center;">Descrição do Produto</th>
								<th width="10.0%">Local</th>
								<th align="center">Quantidade conferida</th>
								<th align="center">Estoque possível</th>
								<th align="center">Quantidade pendente</th>
								<th width="10.0%" align="center">Controle</th>
							</tr>
							<?php
							$tsql2 = "select * from [sankhya].[AD_FN_pendencias_CONFERENCIA]($nunota2)";
							$stmt2 = sqlsrv_query($conn, $tsql2);
							$i = 0;
							while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC)) {
							?>
								<tr style="cursor: hand; cursor: pointer;">
								<tr>
									<td align="center" width="1%"><input type="checkbox" name="id[<?php echo "$row2[0]/$nunota2"; ?>]" class="checkbox" data-codbarra="<?php echo $row2[0]; ?>" id="<?php echo 'pendenciasTr' . $i ?>" data-insert="<?php echo $row2[5] ?>" /></td>
									<td><?php echo $row2[0]; ?></td>
									<td><?php echo $row2[1]; ?></td>
									<td align="center"><?php echo $row2[2]; ?></td>
									<td align="center"><input type="number" value="<?php echo $row2[5] ?>" class="qtdInserir" style="width: 100%;" data-index="<?php echo $i; ?>"></td>
									<td align="center"><?php echo $row2[5] ?></td>
									<td align="center"><?php echo $row2[3]; ?></td>
									<td align="center"><?php echo $row2[4]; ?></td>
								</tr>
							<?php
								$i++;
							}
							?>
						</table>
					</div>
					<!-- Fim formulário para inserir as pendências -->
					<button type="submit" style="cursor: hand; cursor: pointer;  float: right; right: 0; bottom: 0; margin-bottom: 2%; margin-right: 4%; margin-top: 10px; position: absolute;" id="inserePendenciaBtn">Inserir item</button>
				</div>
				<button class="fechar" onclick="fecharconfdivpendencia();">X</button>
			</div>
			<!--
				POP UP Para Conferência Finalizada com Divergência
			-->
			<div id="popupconfdivergencia" class="popupconfdivergencia">
				<h4 style="text-align: center; margin-top: 8%;">Conferência finalizada como divergente!</h4>
				<button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto;" onclick="">Realizar recontagem</button>
				<button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto; margin-top: 3%;" onclick="fecharconfdivergencia(); abrirconf();">Concluir</button>
			</div>
			<!-- Fim
				POP UP Para Conferência Finalizada com Divergência
			-->
			<div id="popupetiqueta" class="popupetiqueta">
				<h4 style="text-align: center; margin-top: 8%;">Imprimir etiquetas: </h4>
				<input type="number" id="qtdImpressao" value="1" step="1" style="display: block; width: 50%; margin-left: auto; margin-right: auto; margin-top: 3%;"></td>
				<button style="cursor: hand; cursor: pointer; display: block; width: 50%; margin-left: auto; margin-right: auto; margin-top: 3%;" onclick="validaNumeroInserido();">Imprimir</button>
				<button class="fechar" onclick="fecharImpressaoEtiqueta();">X</button>
			</div>
			<!--
				POP UP Para Conferência Finalizada com Divergência (Corte)
			-->
			<?php
			if (isset($_POST['btn-recontagem'])) {
				$tsql3 = "  DECLARE @ULTCOD INT
									select @ULTCOD = ULTCOD + 1 from TGFNUM where ARQUIVO = 'TGFCON2' and CODEMP = 1 and SERIE = '.'
									UPDATE TGFNUM SET ULTCOD = @ULTCOD WHERE ARQUIVO = 'TGFCON2' and CODEMP = 1 and SERIE = '.'
									UPDATE TGFCAB SET NUCONFATUAL = @ULTCOD WHERE TGFCAB.NUNOTA = $nunota2";
				$stmt3 = sqlsrv_query($conn, $tsql3);
			}
			?>
			<div id="popupconfdivcorte" class="popupconfdivcorte">
				<h4 style="text-align: center; margin-top: 3%;">Conferência finalizada como divergente!</h4>
				<div style="width: 100%;">
					<button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto; margin-top: 3%;" name="btn-corte" onclick="fecharconfdivcorte(); abrirconf();">Cortar Itens Divergentes</button>
				</div>
				<button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto; margin-top: 3%;" onclick="fecharconfdivcorte(); abrirconf();">Concluir</button>
				<button class="fechar" onclick="fecharconfdivcorte();">X</button>
			</div>
			<!-- Fim
				POP UP Para Conferência Finalizada com Divergência (Corte)
			-->

			<div style="overflow: auto; height: 86%;">
				<div id="produtoconferencia">
					<table width="1300" border="1px" bordercolor="black" style="margin-top: 5px;" id="table">
						<tr>
							<th width="10.6%">Produto</th>
							<th width="36.6%" style="text-align: center;">Descrição do Produto</th>
							<th width="10.6%" align="center">UN</th>
							<th width="12.6%" align="center">Controle</th>
							<th width="12.6%" align="center">Ref. do Forn.</th>
							<th width="16.6%" align="center">Código de Barras</th>
						</tr>
					</table>
				</div>
			</div>
		</div> <!-- Itens Conferência -->

		<!-- Itens Conferidos-->

		<div style="width: 48%; height:48%; /*background-color: yellow;*/ display: inline-block; float: right; margin-left: 0;;  margin-top: 50px;">
			<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; width: 90%; display: inline-block;">Itens conferidos
				<button type="submit" id="deletaprodutobtn" style="margin-left: 10%; font-size: 13px;" name="deletaprodutobtn">Apagar Item(ns) Selecionado(s)</button>

				<?php
				if ($OBSERVACAO != "") {
					echo "<button style='margin-left: 2%; font-size: 13px;' class='btnPendencia' onclick='abrirObs();'>Observação</button>";
				}
				?>
			</h4>
			<?php
			$tsql2 = "  DECLARE @NUNOTA INT = $nunota2
							DECLARE @NUCONF INT = (SELECT NUCONFATUAL from TGFCAB where NUNOTA = @NUNOTA)
							SELECT COUNT (1)
							FROM TGFPRO INNER JOIN
								TGFCOI2 ON TGFCOI2.CODPROD = TGFPRO.CODPROD
							WHERE TGFCOI2.NUCONF = @NUCONF";
			$stmt2 = sqlsrv_query($conn, $tsql2);
			while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC)) {
				$QtdConferidos = $row2[0];
			}
			echo $QtdConferidos;
			?>
			<form name="bulk_action_form" onSubmit="return delete_confirm();">
				<div style="overflow: auto; height: 85.5%; width: 109.5%;" id="itensconferidos">
					<?php
					$tsql2 = "SELECT * FROM [sankhya].[AD_FNT_ITENS_CONFERIDOS_CONFERENCIA]($nunota2) ORDER BY SEQCONF DESC ";
					$stmt2 = sqlsrv_query($conn, $tsql2);
					?>
					<table width="2000" border="1px" bordercolor="white" style="margin-top: 5px;" id="table">
						<?php $fields = sqlsrv_field_metadata($stmt2);
						echo "<tr style='text-align: center; border: 1px solid black'>";
						echo "<th></th>";
						foreach ($fields as $field) {
							echo "<th>" . utf8_encode($field["Name"]) . "</th>";
						}
						echo "</tr>";
						// Dados da tabela
						while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC)) {
							echo "<tr>"; ?>
							<td align='center' width='1%'>
								<input type='checkbox' class='checkbox' data-ref='<?php echo $row2[2]; ?>' data-parent='<?php echo $row2[7]; ?>' />
							</td>
						<?php foreach ($row2 as $value) {
								echo "<td>" . $value . "</td>";
							}
							echo "</tr>";
						} ?>
					</table>
				</div>
			</form>
		</div> <!-- Itens Conferidos -->

		<!-- Imagem e Consulta de Produtos -->
		<div id="Imagem do Produto" style="width: 48%; height: 50%; /*background-color: #D9DAFA;*/ display: inline-block; margin-left: 1%; ">
			<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7;padding-top: 2px; width: 100%;">Informações do Produto</h4>
			<div style="width: 50%; height: 50%; line-height: 100%; align-items: center; display: inline-flex; margin-top: 0; padding-top: 0" id="imagemproduto">
				<?php
				$tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000 ";
				$stmt2 = sqlsrv_query($conn, $tsql2);
				if ($stmt2) {
					$row_count = sqlsrv_num_rows($stmt2);
					while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC)) {
						echo '<img style="vertical-align: middle;  max-width: 280px; margin: auto; max-height: 90%;" src="data:image/jpeg;base64,' . base64_encode($row2[0]) . '"/>';
					}
				}
				?>
			</div> <!-- Parte da Imagem -->

			<!-- Parte das Características -->
			<div style=" display: inline-block; height: 90%; width: 49%; overflow-y: hidden; overflow-x: hidden; margin-top: 10px;">
				<div id="caracteristicas">
					<nav class="nav_tabs">
						<ul>
							<li>
								<input type="radio" id="tab1" class="rd_tab" name="tabs" checked>
								<label for="tab1" class="tab_label">Carac.</label>
								<div class="tab-content">
									<article>
										<div style="margin-top: 10px;">
										</div>
									</article>
								</div>
							</li>
							<li>
								<input type="radio" name="tabs" class="rd_tab" id="tab2">
								<label for="tab2" class="tab_label">Comp.</label>
								<div class="tab-content" style="overflow: auto;">
									<article>
									</article>
								</div>
							</li>
							<li>
								<input type="radio" name="tabs" class="rd_tab" id="tab3">
								<label for="tab3" class="tab_label">Estoque</label>
								<div class="tab-content">
									<article>
									</article>
								</div>
							</li>
							<li>
								<input type="radio" name="tabs" class="rd_tab" id="tab4">
								<label for="tab4" class="tab_label">Preço</label>
								<div class="tab-content">
									<article>
									</article>
								</div>
							</li>
							<li>
								<input type="radio" name="tabs" class="rd_tab" id="tab5">
								<label for="tab5" class="tab_label">Res.</label>
								<div class="tab-content">
									<article>
									</article>
								</div>
							</li>
						</ul>
					</nav>
				</div>
			</div> <!-- Parte das Características -->
		</div> <!-- Imagem do Produto -->

		<div id="Itens do Pedido" style="width: 48%; height:54%; /*background-color: red;*/ display: inline-block; float: right; margin-left: 1%; overflow: hidden; margin-left: 0;">
			<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-top: 2px; width: 90%; display: inline-block;">Itens do Pedido</h4>
			<?php
			$tsql2 = "  SELECT COUNT(1)
							FROM TGFCAB CAB INNER JOIN
									TGFITE ITE ON ITE.NUNOTA = CAB.NUNOTA INNER JOIN
									TGFPRO PRO ON PRO.CODPROD = ITE.CODPROD LEFT JOIN
									TGFBAR BAR ON BAR.CODPROD = PRO.CODPROD 
											  AND BAR.DHALTER = (SELECT MAX(TGFBAR.DHALTER) 
											  					 FROM TGFBAR 
											  					 WHERE TGFBAR.CODPROD = BAR.CODPROD
											  					) INNER JOIN
									TGFVOL VOL ON VOL.CODVOL = ITE.CODVOL 
							WHERE CAB.NUNOTA = $nunota2";
			$stmt2 = sqlsrv_query($conn, $tsql2);
			while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC)) {
				$QtdPedido = $row2[0];
			}
			echo $QtdPedido;
			?>
			<div style="overflow: auto; width: 100%; height: 86%;">
				<table width="2000" border="1px" bordercolor="black" style="margin-top: 5px;" id="minhaTabela">
					<tr>
						<font size="-1" face="Arial, Helvetica, sans-serif">
							<th width="25%" style="text-align: center;">
								<font face="Arial, Helvetica, sans-serif">Descrição (Produto)</font>
							</th>
							<th width="10%" align="center">
								<font face="Arial, Helvetica, sans-serif">Nro. Único</font>
							</th>
							<th width="10%" align="center">
								<font face="Arial, Helvetica, sans-serif">Sequência</font>
							</th>
							<th width="5%" align="center">
								<font face="Arial, Helvetica, sans-serif">UN</font>
							</th>
							<th width="10%" align="center">
								<font face="Arial, Helvetica, sans-serif">Descrição (UN)</font>
							</th>
							<th width="10%" style="text-align: center;">
								<font face="Arial, Helvetica, sans-serif">Controle</font>
							</th>
					</tr>
					<!-- Pesquisa o Número da Nota no Banco para que sejam retornados os itens -->
					<?php
					$tsql2 = " SELECT * FROM [sankhya].[AD_FNT_ITENS_PEDIDO]($nunota2) ORDER BY SEQUENCIA";
					$stmt2 = sqlsrv_query($conn, $tsql2);
					while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC)) {
						$NUCONF = $row2[0];
					?>
						<tr style="cursor: hand; cursor: pointer;">
							<td width="10%"><?php echo $row2[0]; ?>&nbsp;</td>
							<td width="5%"><?php echo $row2[1]; ?>&nbsp;</td>
							<td width="10%"><?php echo $row2[2]; ?>&nbsp;</td>
							<td width="5%" align="center"><?php echo $row2[3]; ?>&nbsp;</td>
							<td width="25%" align="center"><?php echo $row2[4]; ?></td>
							<td width="10%" align="center"><?php echo $row2[5]; ?></td>
						</tr></a>
					<?php
					}
					?>
				</table>
			</div>
		</div> <!-- Itens do Pedido -->
	</div> <!--container-->

	<script>
		var index,
			table = document.getElementById("table");

		function executarUmaVez() {
			if ('<?php echo $funcaoJaExecutada ?>' == false) {
				complemento('sim');

				<?php $_SESSION["funcao"] = true; ?>
			}
		}

		// display selected row data into input text
		function selectedRowToInput() {
			for (var i = 1; i < table.rows.length; i++) {
				table.rows[i].onclick = function() {
					if (typeof index !== "undefined") {
						table.rows[index].classList.toggle("selected");
					}
					index = this.rowIndex;
					this.classList.toggle("selected");
				};
			}
		}
		selectedRowToInput();

		function removeSelectedRow() {
			table.deleteRow(index);
		}

		function iniciarpausa(status, nota) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: '../Model/iniciarpausa.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					$("#iniciarpausa").html("Carregando...");
				},
				data: {
					status: status,
					nota: nota
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					window.location.href = 'detalhesconferencia.php?nunota=<?php echo $nunota2 ?>&codbarra=0';
				}
			});
		}
		$('#btnStatus').click(function() {
			var nunota = "<?php echo $nunota2; ?>"
			var status = "<?php echo $varStatus; ?>"
			iniciarpausa(status, nunota)
		});

		function insereitens(codbarra, quantidade, controle, nunota) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: '../Model/insereitem.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					//$("#itensconferidos").html("Carregando...");
				},
				data: {
					codbarra: codbarra,
					quantidade: quantidade,
					controle: controle,
					nunota: nunota
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					if (msg == "Codigo de barras nao esta cadastrado!") {
						alert(msg);
						document.getElementById("quantidade").value = "";
						document.getElementById("codigodebarra").focus();
						document.getElementById("codigodebarra").select();
					} else if (msg == "Quantidade inserida nao pode ser maior do que a existente na nota!") {
						alert(msg);
						document.getElementById("quantidade").focus()
						document.getElementById("quantidade").select();
					} else if (msg == "Produto nao existe na nota!") {
						alert(msg);
						document.getElementById("quantidade").value = "";
						document.getElementById("codigodebarra").focus();
						document.getElementById("codigodebarra").select();
					} else if (msg == "Estoque insuficiente!") {
						alert(msg);
						document.getElementById("quantidade").focus()
						document.getElementById("quantidade").select();
					} else {
						$("#insereitem").html(msg);
						if (document.getElementById("codigodebarra").value === "") {
							document.getElementById("codigodebarra").focus();
						}
						if (document.getElementById("quantidade").value != "1") {
							document.getElementById("codigodebarra").value = "";
							document.getElementById("quantidade").value = "";
							document.getElementById("codigodebarra").focus();
						}
					}
				}
			});
		}
		$('#conferir').click(function() {
			var nunota = "<?php echo $nunota2; ?>"
			var status = "<?php echo $varStatus; ?>"
			if (status == "P") {
				iniciarpausa(status, nunota)
			}
			insereitens($("#codigodebarra").val(), $("#quantidade").val(), $("#controle").val(), <?php echo $nunota2; ?>)
		});

		function finalizar(nunota, usuconf, pesobruto, qtdvol, volume, observacao, frete, mtvdivergencia) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: '../Model/finalizarconf.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					$("#loader").show();
				},
				complete: function() {
					$("#loader").hide();
				},
				data: {
					nunota: nunota,
					usuconf: usuconf,
					pesobruto: pesobruto,
					qtdvol: qtdvol,
					volume: volume,
					observacao: observacao,
					frete: frete,
					mtvdivergencia: mtvdivergencia
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					complemento('nao');
					if (!msg.includes("Finalizado com sucesso")) {
						alert(msg);
					} else {
						alert(msg);
						window.location.href = '../Etiquetas/impressao.php?nunota=' + nunota;
					}
				}
			});
		}

		$('#confirmar').click(function() {
			finalizar(<?php echo $nunota2; ?>, <?php echo $usuconf; ?>, $("#pesobruto").val(), $("#qtdvol").val(), $("#volume").val(), $("#observacao").val(), $("#frete").val(), $("#mtvdivergencia").val())
		});

		function complemento(primeiro) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: '../Model/complemento.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					//$("#itensconferidos").html("Carregando...");
				},
				data: {
					nunota: <?php echo $nunota2; ?>,
					codparc: '<?php echo $codparc; ?>'
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					var stringSemLetras = '';
					for (var i = 0; i < msg.length; i++) {
						var caractere = msg.charAt(i);
						if ((caractere >= '0' && caractere <= '9') || caractere === '|') {
							stringSemLetras += caractere;
						}
					}
					//se a stringSemLetras for vazia eu sei que a mensagem é 
					//'Existe um Complemento para esse pedido. Atualize a Tela!' 
					//pois não existe nenhum número nela

					$.ajax({
						async: false,
						type: 'POST', //Método que está sendo utilizado.
						dataType: 'html', //É o tipo de dado que a página vai retornar.
						url: '../Model/varsessao.php?timestamp=' + new Date().getTime(), //Indica a página que está sendo solicitada.
						beforeSend: function() {},
						data: {
							string: stringSemLetras
						},
						success: function(nmrSession) {
							if (msg != '' && nmrSession == 'S') {
								//verifica se o retorno da função complemento é diferente de vazio, se for vazio não tem nenhum complemento
								teste(msg, primeiro)
							}
						}
					});
				}
			});
		}

		function teste(msg, primeiro) {
			requiredFunction(msg, primeiro);
		}

		function abrirconferentes() {
			document.getElementById('popupconferentes').style.display = 'block';
			var btns = document.getElementsByClassName('conferente-btn');
			for (var i = 0; i < btns.length; i++) {
				btns[i].addEventListener('click', function() {
					var nunota = "<?php echo $nunota2; ?>"
					var user = this.getAttribute('data-user');
					atribuirseparador(user, nunota);
				});
			}
		}

		function requiredFunction(msg, primeiro) {
			var answer = prompt(msg + '\n' + '' + '\n' + 'Digite seu nome para continuar');
			if (answer == "" || answer === null) {
				teste(msg);
			} else {
				if (primeiro != 'sim') {
					window.location.href = 'listaconferencia.php';
				}
			}
		}

		function atribuirseparador(separador, nunota) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: '../Model/atribuirseparador.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					// $("#loader").show();
				},
				data: {
					separador: separador,
					nunota: nunota
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					window.location.href = 'detalhesconferencia.php?nunota=<?php echo $nunota2 ?>&codbarra=0';
				}
			});
		}

		function inserependencia(nunota, referencia, quantidade) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: '../Model/inserependencia.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					$("#loader").show();
				},
				data: {
					nunota: nunota,
					referencia: referencia,
					quantidade: quantidade
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					alert(msg);
					$("#loader").show();
					window.location.href = 'detalhesconferencia.php?nunota=<?php echo $nunota2 ?>&codbarra=0';
				}
			});
		}

		$('#inserePendenciaBtn').click(function() {
			inserirpendencias();
		});

		function caracteristica(codigodebarra) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: './caracteristicas.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					$("#caracteristicas").html("Carregando...");
				},
				data: {
					codigodebarra: codigodebarra
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					$("#caracteristicas").html(msg);
				}
			});
		}

		$('#codigodebarra').change(function() {
			caracteristica($("#codigodebarra").val());
			imagemproduto($("#codigodebarra").val());
			produtoconferencia($("#codigodebarra").val(), <?php echo $nunota2; ?>);
		});

		function produtoconferencia(codigodebarra, nunota) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: './produtoconferencia.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					$("#produtoconferencia").html("Carregando...");
				},
				data: {
					codigodebarra: codigodebarra,
					nunota: nunota
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					$("#produtoconferencia").html(msg);
				}
			});
		}

		function imagemproduto(codigodebarra) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: '../Model/imagemproduto.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					$("#imagemproduto").html("Carregando...");
				},
				data: {
					codigodebarra: codigodebarra
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					$("#imagemproduto").html(msg);
				}
			});
		}

		function deletaproduto(nunota, codprod, controle) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: '../Model/action.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					// $("#imagemproduto").html("Carregando...");
				},
				data: {
					nunota: nunota,
					codprod: codprod,
					controle: controle
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					// $("#imagemproduto").html(msg);
					window.location.href = 'detalhesconferencia.php?nunota=<?php echo $nunota2 ?>';
				}
			});
		}

		$('#deletaprodutobtn').click(function() {
			deletaprodutos();
		});

		function itensconferidos(nunota) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: './itensconferidos.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					$("#itensconferidos").html("Carregando...");
				},
				data: {
					nunota: nunota
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					$("#itensconferidos").html(msg);
				}
			});
		}

		$('#conferir').click(function() {
			itensconferidos(<?php echo $nunota2; ?>)
		});


		if (document.getElementById("codigodebarra").value === "") {
			document.getElementById("codigodebarra").focus();
		}

		document.addEventListener("keypress", function(e) {
			if (e.key === "Enter") {
				const btn = document.querySelector("#conferir");
				btn.click();
				if (document.getElementById("codigodebarra").value != "") {
					document.getElementById("quantidade").focus();
					document.getElementById("quantidade").value = "1";
					document.getElementById("quantidade").select();
				}
			}
		});
		var codbarselecionado = "<?php echo $codbarraselecionado; ?>";
		if (codbarselecionado != 0) {
			function scrollToRow(i) {
				var tabela = document.getElementById("minhaTabela");
				var linhas = tabela.getElementsByTagName("tr");
				linhas[i].classList.toggle("selecionado");
				setTimeout(function() {
					linhas[i].scrollIntoView();
				}, 50);
			}
		} else {
			function scrollToRow(i) {
				var tabela = document.getElementById("minhaTabela");
				var linhas = tabela.getElementsByTagName("tr");

				linhas[i].classList.toggle("");

				setTimeout(function() {
					linhas[i].scrollIntoView();
				}, 50);
			}
		}

		$(".qtdInserir").change(function() {
			var index = $(this).data('index');
			var valor = $(this).val();
			document.getElementById('pendenciasTr' + index).setAttribute('data-insert', valor);
		});
	</script>

	<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-2.2.0.min.js"></script>
	<script src="https://ajax.aspnetcdn.com/ajax/bootstrap/3.3.6/bootstrap.min.js"></script>
	<?php $currentAbsoluteURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
	$currentAbsoluteURL .= $_SERVER["SERVER_NAME"];
	if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
		$currentAbsoluteURL .= ":" . $_SERVER["SERVER_PORT"];
	}

	$webClientPrintControllerAbsoluteURL = $currentAbsoluteURL . '/SistemaConferencia/Etiquetas/WebClientPrintController.php';
	$printFileControllerAbsoluteURL = $currentAbsoluteURL . '/SistemaConferencia/Etiquetas/PrintFileController.php';

	echo WebClientPrint::createScript($webClientPrintControllerAbsoluteURL, $printFileControllerAbsoluteURL, session_id());
	?>
</body>

</html>