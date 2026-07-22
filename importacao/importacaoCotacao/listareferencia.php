<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

//Retorna as varíaveis do evento de clique o o Número do orçamento que já foi carregado na Sessão anteriormente.
$id = $_POST['id'];
$nuimportacao = $_SESSION['nuimportacao'];
?>

<section class="produtosconferencia">
	<div style="height: 90%; width: 30%; position: fixed; right: 0; text-align: center; margin-right: 3%; display: flex; flex-direction: column;" id="listaReferencia">

		<!-- Tabela de Possíveos Itens, baseado na linha que foi clicada da referência -->
		<table id="tableListaItens" class="listaconferencia" style="width: 100%; height: 55%;">
			<tr>
				<th>Ref. Forn.</th>
				<th>Cód. Prod.</th>
				<th>Referência Interna</th>
				<th>Descr. Prod</th>
			</tr>


			<?php

			
			$tsql2 = "SELECT * 
					  FROM SANKHYA.AD_FNT_ListaReferencias_CotacaoCompras('$id', $nuimportacao)";

			$stmt2 = sqlsrv_query($conn, $tsql2);
			while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
			?>
				<tr style="cursor: hand; cursor: pointer;" data-id="<?php echo $id; ?>" 
														   data-ref="<?php echo $row2['REFERENCIAINTERNA'] ?>" 
														   data-codprod = "<?php echo $row2['CODPROD'] ?>"
														   data-descrprod = "<?php echo $row2['DESCRPROD'] ?>"
														   >
					<td width="20%"><?php echo $id; ?>&nbsp;</td>
					<td width="25%"><?php echo $row2['CODPROD']; ?>&nbsp;</td>
					<td width="40%"><?php echo $row2['REFERENCIAINTERNA']; ?>&nbsp;</td>
					<td width="15%"><?php echo mb_convert_encoding($row2['DESCRPROD'], 'UTF-8', mb_detect_encoding($row2['DESCRPROD'], 'UTF-8, ISO-8859-1', true)); ?>&nbsp;</td>
				</tr>
			<?php


			}
			?>
		</table>
		<!-- DIV para produtos em promoção -->
		<div class="informacoes-produto">

			<div class= "img-prod"  id="imagemproduto" onclick="confirmarEnvioEmail()">
				
					
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

			<div>
					<!-- Tabela de Possíveos Itens, baseado na linha que foi clicada da referência -->
					<table id="ItemDesconto">
						
					</table>
			</div> <!-- Parte da da promoção -->

		</div>


		<!-- Fim da tabela de Possíveis Itens, baseado na linha que foi clicada da referência -->

			<!-- Botões de Finalização e Inclusão -->
			<div id="floating-container">
				<div id="limparlinha-button" class="floating-button-listaitens">Limpar</div>
				<div id="floating-button-item" class="floating-button-item" onclick="openSidebar()">+</div>
			</div>
			<div id="search-container">

				<!-- Inclusão de Itens -->
				<div class="sidebar-header">
				    <span>Adicionar Item</span>
				    <button onclick="closeSidebar()" id="buttonFechar">X</button>
				</div>

			    <input type="text" id="search-input" placeholder="Pesquise para incluir ...">
			    <button type="submit" name="pesquisar" id="referencia" onclick="pesquisaProduto()">Pesquisar</button>
			</div>

	</div>

		<div id="popupprodutos" class="popupprodutos" style="display: none;">
			
			<button class="fechar" onclick="fecharprodutos();" id="fecharPesquisa">X</button>

			<div style=" width: 100%; overflow: auto; margin-top: 5px;">
				<table width="95%" style="margin-top: 5px; margin-left: 7px; table-layout: fixed" id="table">
					<thead>
						<tr>
							<th width="33%" style="text-align: center;">Referência</th>
							<th width="67%" style="text-align: center;">Descrição do Produto</th>
							<th width="22%" style="text-align: center;">Estoque</th>
						</tr>
					</thead>
					<tbody id="produtos">
					</tbody>
				</table>
			</div>
			
		</div>

</section>