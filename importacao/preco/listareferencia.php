<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

//Retorna as varíaveis do evento de clique o o Número do orçamento que já foi carregado na Sessão anteriormente.
$id = $_POST['id'];
$nuorcamento = $_SESSION['nuorcamento'];
?>

<section class="produtosconferencia">
	<div style="height: 90%; width: 30%; position: fixed; right: 0; text-align: center; margin-right: 3%;" id="listaReferencia">

		<!-- Tabela de Possíveos Itens, baseado na linha que foi clicada da referência -->
		<table id="tableListaItens" class="listaconferencia" style="width: 100%;">
			<tr>
				<th>Ref. Fabricante</th>
				<th>Referência Interna</th>
				<th>Descr. Prod</th>
			</tr>


			<?php

			
			$tsql2 = "SELECT REFERENCIAFABRICANTE, REFERENCIAINTERNA, DESCRPROD, PRECOVENDA
					  FROM AD_IMPORTACAO_TELEMARKETING_ITE
					  WHERE REFERENCIAFABRICANTE = '{$id}'
						AND NUORCAMENTO = $nuorcamento";

			$stmt2 = sqlsrv_query($conn, $tsql2);
			while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
			?>
				<tr style="cursor: hand; cursor: pointer;" data-id="<?php echo $id; ?>" data-ref="<?php echo $row2['REFERENCIAINTERNA'] ?>" data-price = "<?php echo $row2['PRECOVENDA'] ?>" >
					<td width="33%" align="center"><?php echo $id; ?>&nbsp;</td>
					<td width="33%" align="center"><?php echo $row2['REFERENCIAINTERNA']; ?>&nbsp;</td>
					<td width="33%" align="center"><?php echo mb_convert_encoding($row2['DESCRPROD'], 'UTF-8', mb_detect_encoding($row2['DESCRPROD'], 'UTF-8, ISO-8859-1', true)); ?>&nbsp;</td>
				</tr>
			<?php


			}
			?>
		</table>

		<!-- Fim da tabela de Possíveos Itens, baseado na linha que foi clicada da referência -->

			<!-- Botões de Finalização e Inclusão -->
			<div id="floating-container">
				<div id="finalizar-button" class="floating-button">Finalizar</div>
				<div id="exportarPlanilha" class="floating-button">Exportar</div>
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
				<table width="95%" border="1px" style="margin-top: 5px; margin-left: 7px; table-layout: fixed" id="table">
					<thead>
						<tr>
							<th width="33%" style="text-align: center;">Referência</th>
							<th width="67%" style="text-align: center;">Descrição do Produto</th>
						</tr>
					</thead>
					<tbody id="produtos">
					</tbody>
				</table>
			</div>
			
		</div>

</section>

<script >

//Função que faz a atualização dos dados, assim que é clicado em uma linha da tabela de Itens das referências que foram encontradas.
document.querySelectorAll('#tableListaItens tbody tr').forEach(row => {
            row.addEventListener('click', function () {            	

            	// Armazena os dados das linhas em varíaveis.
                const selectedId = this.getAttribute('data-id'); // ID da linha na segunda tabela
                const selectedRef = this.getAttribute('data-ref'); // Valor selecionado
                const selectedPreco = this.getAttribute('data-price'); // Valor selecionado
                

                // Atualizar linha correspondente na primeira tabela
                const rowInTable1 = document.querySelector(`#tableListaReferencias tbody tr[data-id="${selectedId}"]`);
                if (rowInTable1) {
                    // Seleciona a linha
                    document.querySelectorAll('#tableListaReferencias tbody tr').forEach(r => r.classList.remove('selected'));
                    rowInTable1.classList.add('selected');

                    // Atualizar conteúdo da célula quando se clica em uma linha e escolhe a mesma.
                    rowInTable1.cells[4].textContent = selectedRef; 
                    rowInTable1.cells[5].textContent = selectedPreco; 
                }


                function updateDados(selectedId, selectedRef, selectedPreco) {
					//O método $.ajax(); é o responsável pela requisição
					$.ajax({
						//Configurações
						type: 'POST', //Método que está sendo utilizado.
						dataType: 'html', //É o tipo de dado que a página vai retornar.
						url: './updatepreco.php', //Indica a página que está sendo solicitada.
						//função que vai ser executada assim que a requisição for enviada
						beforeSend: function() {
							// $("#imagemproduto").html("Carregando...");
						},
						data: {
							id: selectedId,
							referencia: selectedRef,
							preco: selectedPreco
						}, //Dados para consulta
						//função que será executada quando a solicitação for finalizada.
						success: function(msg) {

						}
					});
				}


					updateDados(selectedId, selectedRef, selectedPreco);


				// Encontra a linha correspondente na segunda tabela
		        const rowToColor = document.querySelector(`#tableListaReferencias tr[data-id='${selectedId}']`);
		        if (rowToColor) {
		            rowToColor.style.backgroundColor = '#D7C0DB'; // Define a cor da linha
		        }


		        //Faz o evento de clique de botão após pesquisar um item específico
				const popupTable = document.getElementById('produtos');
				//const selectedId = this.getAttribute('data-id');


				
				
            });
        });

		
	//Processo de exportar a Planilha já com os campos prontos
	$('#exportarPlanilha').click(function() {
		

		 nuorcamento = <?php echo $nuorcamento; ?>;

	     if (confirm("Tem certeza que deseja exportar a planilha novamente para Excel?")) {
		 	
		 	//alert('teste');
		 	//alert(nuorcamento);
		 	exportarPlanilha(nuorcamento); 	  

		 } 

		//Função via AJAX que faz a exportação da planilha.
		function exportarPlanilha(nuorcamento) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: './exportarplanilha.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					//$("#loader").show();
				},
				complete: function() {
					//$("#loader").hide();
				},
				data: {
					nuorcamento: nuorcamento
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					
					//Redireciona para a página do orçamento que foi criado.
					window.location.href = window.location.origin + msg;

				},
			    error: function(xhr, status, error) {
			        console.log('Erro AJAX:', error);
			    }

			});
		}


	});	


	//Processo para finalizar a cotação, marcar como finalizado e mostrar que já está concluída
	$('#finalizar-button').click(function() {
		
		 nuorcamento = <?php echo $nuorcamento; ?>;

		 onclick="confirm('Você tem certeza que deseja finalizar?')"
	     if (confirm('Você tem certeza que deseja marcar essa cotação como finalizada?')) {
		 	
		 	finalizarCotacao(nuorcamento); 	  

		 } 


		//Função que marca a cotação como finalizada.
		function finalizarCotacao(nuorcamento) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: './finalizarcotacao.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					//$("#loader").show();
				},
				complete: function() {
					//$("#loader").hide();
				},
				data: {
					nuorcamento: nuorcamento
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					
					alert('Cotação Finalizada com Sucesso!');
					window.location.href = 'listaorcamento.php';

				},
			    error: function(xhr, status, error) {
			        console.log('Erro AJAX:', error);
			    }

			});
		}


	});	




		

		

</script>