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
				<th>Estoque</th>
			</tr>


			<?php

			
			$tsql2 = "SELECT REFERENCIAFABRICANTE, REFERENCIAINTERNA, DESCRPROD, PRECOVENDA, ESTOQUE, CODPROD
					  FROM AD_IMPORTACAO_TELEMARKETING_ITE
					  WHERE REFERENCIAFABRICANTE = '{$id}'
						AND NUORCAMENTO = $nuorcamento";

			$stmt2 = sqlsrv_query($conn, $tsql2);
			while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
			?>
				<tr style="cursor: hand; cursor: pointer;" data-id="<?php echo $id; ?>" 
														   data-ref="<?php echo $row2['REFERENCIAINTERNA'] ?>" 
														   data-price = "<?php echo $row2['PRECOVENDA'] ?>" 
														   data-est="<?php echo $row2['ESTOQUE'] ?>"
														   data-codprod = "<?php echo $row2['CODPROD'] ?>">
					<td width="20%"><?php echo $id; ?>&nbsp;</td>
					<td width="25%"><?php echo $row2['REFERENCIAINTERNA']; ?>&nbsp;</td>
					<td width="40%"><?php echo mb_convert_encoding($row2['DESCRPROD'], 'UTF-8', mb_detect_encoding($row2['DESCRPROD'], 'UTF-8, ISO-8859-1', true)); ?>&nbsp;</td>
					<td width="15%"><?php echo $row2['ESTOQUE']; ?>&nbsp;</td>
				</tr>
			<?php


			}
			?>
		</table>

		<!-- Fim da tabela de Possíveos Itens, baseado na linha que foi clicada da referência -->

			<?php
				$tsql = "SELECT sankhya.AD_FN_STATUS_IMPORTACAO_TELEMARKETING($nuorcamento)";
				$stmt = sqlsrv_query($conn, $tsql);
				while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
					$status = $row[0];
				}


			?>

			<!-- Botões de Finalização e Inclusão -->
			<div id="floating-container">
				<div id="limparlinha-button" class="floating-button">Limpar</div>
				<div id="gera1700-button" class="floating-button">Gera 1700</div>
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
				<table width="95%" style="margin-top: 5px; margin-left: 7px; table-layout: fixed" id="table">
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

//Função que atualiza os botões para não aparecerem
var statusfinalizacao = '<?php echo $status; ?>';

if(statusfinalizacao == 'C') {
	document.getElementById('finalizar-button').style.display = 'none';
	document.getElementById('gera1700-button').style.display = 'block';
} else {
	document.getElementById('finalizar-button').style.display = 'block';
	document.getElementById('gera1700-button').style.display = 'none';
}

//Função que faz a atualização dos dados, assim que é clicado em uma linha da tabela de Itens das referências que foram encontradas.
document.querySelectorAll('#tableListaItens tbody tr').forEach(row => {
            row.addEventListener('click', function () {            	

            	// Armazena os dados das linhas em varíaveis.
                const selectedId = this.getAttribute('data-id'); // ID da linha na segunda tabela
                const selectedRef = this.getAttribute('data-ref'); // Valor selecionado
                const selectedPreco = this.getAttribute('data-price'); // Valor selecionado
				const selectedEstoque = this.getAttribute('data-est'); // Valor selecionado
                

                // Atualizar linha correspondente na primeira tabela
                const rowInTable1 = document.querySelector(`#tableListaReferencias tbody tr[data-id="${selectedId}"]`);
                if (rowInTable1) {
                    // Seleciona a linha
                    document.querySelectorAll('#tableListaReferencias tbody tr').forEach(r => r.classList.remove('selected'));
                    rowInTable1.classList.add('selected');

                    // Atualizar conteúdo da célula quando se clica em uma linha e escolhe a mesma.
                    rowInTable1.cells[4].textContent = selectedRef; 
                    rowInTable1.cells[5].textContent = selectedPreco;
                    rowInTable1.cells[6].textContent = selectedEstoque;
                }


                function updateDados(selectedId, selectedRef, selectedPreco, selectedEstoque) {
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
							preco: selectedPreco,
							estoque: selectedEstoque
						}, //Dados para consulta
						//função que será executada quando a solicitação for finalizada.
						success: function(msg) {

						}
					});
				}


					updateDados(selectedId, selectedRef, selectedPreco, selectedEstoque);


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

	//Botão para limpar a linha que está selecionada, nos casos onde não temos os itens corretos.
	$('#limparlinha-button').click(function() {
		// Encontra a linha selecionada
		//alert('teste');

		const rowToColor = document.querySelector(`#tableListaReferencias tr[data-id='<?php echo $id; ?>']`);
		if (rowToColor) {
			// Limpa os dados da linha
			rowToColor.cells[4].textContent = '';
			rowToColor.cells[5].textContent = '';
			rowToColor.cells[6].textContent = '';
			rowToColor.style.backgroundColor = ''; // Remove a cor de fundo

			limparDados('<?php echo $id; ?>');
		}


		function limparDados(selectedId) {
			//O método $.ajax(); é o responsável pela requisição
			$.ajax({
				//Configurações
				type: 'POST', //Método que está sendo utilizado.
				dataType: 'html', //É o tipo de dado que a página vai retornar.
				url: './limpardados.php', //Indica a página que está sendo solicitada.
				//função que vai ser executada assim que a requisição for enviada
				beforeSend: function() {
					// $("#imagemproduto").html("Carregando...");
				},
				data: {
					id: selectedId
				}, //Dados para consulta
				//função que será executada quando a solicitação for finalizada.
				success: function(msg) {
					//alert(msg);
				}
			});
		}
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

	//Abre o POPUP para abrir as notas de geração da 1700
	$('#gera1700-button').click(function() {

		nuorcamento = <?php echo $nuorcamento; ?>;
		if (confirm('Você tem certeza que deseja criar a 1700?')) {
			
			abrirMarcacaoitens(nuorcamento);
			//finalizarCotacao(nuorcamento); 	  

		} 


		function abrirMarcacaoitens(nuorcamento) {
			document.getElementById('popupitens1700').style.display = 'block';
	   	}

	});

	$('#gerar1700-button').click(function() {
		
		nuorcamento = <?php echo $nuorcamento; ?>;

		var checkboxes = document.querySelectorAll('.itemCheckbox:checked');
    	var selecionados = [];

		
		checkboxes.forEach(function(checkbox) {
			var linha = checkbox.closest('tr'); // Encontra a linha (tr) do checkbox
			var referencia = linha.getAttribute('data-referencia'); // Pega o valor de data-referencia
			//var descricao = linha.querySelector('td:nth-child(3)').innerText; // Pega o valor da coluna Descrição
			//var quantidade = linha.querySelector('td:nth-child(4)').innerText; // Pega o valor da coluna Quantidade

			// Adiciona os dados da linha ao array de selecionados
			selecionados.push({
				referencia: referencia//,
				//descricao: descricao,
				//quantidade: quantidade
			});
    	});

		if (selecionados.length > 0) {
			
			
			// // Enviar os dados via AJAX
			// var xhr = new XMLHttpRequest();
			// xhr.open('POST', 'gera1700.php', true);
			// xhr.setRequestHeader('Content-Type', 'application/json');
			// xhr.onreadystatechange = function() {
			// 	if (xhr.readyState === 4 && xhr.status === 200) {
			// 		var mensagem = 'Nota Criada com Sucesso! Núm. Único: ' + xhr.responseText;
			// 		alert(mensagem);

			// 		console.log(xhr.responseText); // Exibe a resposta do servidor
			// 	} else {
			// 		alert('Erro ao criar nota.');
			// 	}
			// };
			// xhr.send(JSON.stringify({ selecionados: selecionados })); // Envia os dados como JSON


			$.ajax({
				type: 'POST', // Método HTTP
				dataType: 'html', // Espera-se um JSON de resposta
				url: 'gera1700.php', // URL do arquivo PHP
				contentType: 'application/json', // Enviando os dados como JSON
				data: JSON.stringify({ selecionados: selecionados }), // Dados enviados para o PHP

				beforeSend: function() {
					// Você pode exibir um loader aqui, se quiser
					$("#loader").show();
				},

				complete: function() {
					// Aqui você pode esconder o loader
					$("#loader").hide();
				},

				success: function(response) {
						alert(response);
						console.log(response);
				},

				error: function(xhr, status, error) {
					// Caso ocorra algum erro na requisição
					console.error('Erro AJAX:', error);
					alert('Erro ao enviar os dados.');
				}
			});


		} else {
			alert('Nenhum item selecionado.');
		}


	} );

		

		

</script>