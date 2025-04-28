<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

//Cria variável de sessão desses parâmetros, para ser usado depois caso precise.
$codParc = isset($_SESSION['codParc']) ? trim($_SESSION['codParc']) : $_POST['codParc'];
$codEmp = isset($_SESSION['codEmp']) ? trim($_SESSION['codEmp']) : $_POST['codEmp'];
$nuorcamento = isset($_SESSION['nuorcamento']) ? trim($_SESSION['nuorcamento']) : $_POST['nuorcamento'];
$codUsuario = $_SESSION['idUsuario'];

//Se foi feito através do clique em uma das tabelas no cabeçalho, cria variáveis de sessão para usar no AJAX
$_SESSION['codParc'] = $codParc;
$_SESSION['nuorcamento'] = $nuorcamento;
$_SESSION['codEmp'] = $codEmp;


$tsql = "SELECT sankhya.AD_FN_STATUS_IMPORTACAO_TELEMARKETING($nuorcamento)";
$stmt = sqlsrv_query($conn, $tsql);
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
    $status = $row[0];
}




?>

<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <title>Lista Itens<?php echo $usuconf; ?></title>
    <link href="../../css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
    <link href="./css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- <script src="../../Controller/ListaConferenciaController.js"></script> -->
    <!-- <script src="./listaController.js"> </script> -->
</head>    

<body class="background-lista">

<div id="loader" style="display: none;">
    <img style=" width: 150px; margin-top: 5%;" src="../../images/soccer-ball-joypixels.gif">
</div>

    <br>
    <br>

<div style="width:100%; top: 0; height: 25px; padding-left: 30px; background-color: #3a6070; position: absolute; ">

    <?php
    
          //Retorna os dados da tabela para ser exibida na tela. As referências que tem na importação.
               $tsql = "SELECT NUORCAMENTO, 
                            CODEMP, 
                            CODPARC, 
                            (SELECT RAZAOSOCIAL
                                FROM TGFPAR 
                                WHERE TGFPAR.CODPARC = AD_IMPORTACAO_TELEMARKETING_CAB.CODPARC) AS RAZAOSOCIAL,
                            'Total: ' + CONVERT(VARCHAR(MAX),(SELECT COUNT(*) FROM AD_IMPORTACAO_TELEMARKETING WHERE AD_IMPORTACAO_TELEMARKETING.NUORCAMENTO = AD_IMPORTACAO_TELEMARKETING_CAB.NUORCAMENTO)) 
	                        + ' / Prenchidos: ' + CONVERT(VARCHAR(MAX),(SELECT COUNT(*) FROM AD_IMPORTACAO_TELEMARKETING_ITE WHERE AD_IMPORTACAO_TELEMARKETING_ITE.NUORCAMENTO = AD_IMPORTACAO_TELEMARKETING_CAB.NUORCAMENTO AND MARCADO = 'S')) AS QTD
                        FROM AD_IMPORTACAO_TELEMARKETING_CAB
                        WHERE NUORCAMENTO = $nuorcamento";

            $stmt = sqlsrv_query($conn, $tsql);

            $listaConferencias = "";


            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $codemp = $row['CODEMP'];
                $codparc = $row['CODPARC'];
                $razaosocial = $row['RAZAOSOCIAL'];
                $qtd = $row['QTD'];
            
            }
            



    ?>
    
    <table style="width: 100%; background-color: black;" id="table">
        <thead>
            <tr class="bg-dark text-white">
                <th>Núm. Orçamento: <?php echo $nuorcamento; ?></th>
                <th>Empresa: <?php echo $codemp; ?></th>
                <th>Cód. Parc.: <?php echo $codparc; ?></th>
                <th>Razão Social: <?php echo $razaosocial; ?></th>
                <th id="contadorItens"><?php echo $qtd; ?> </th>
            </tr>
        </thead>
    </table>
    
    <div class="img-voltar">
        <a href="listaorcamento.php">
            <img src="../../images/216446_arrow_left_icon.png">
        </a>
	</div>

</div>

<div style="display: flex;">
    <div style="height: 80%; width: 60%; float: left; margin-left: 4%; float: left;" id="ListaConferencia" class="listaconferencia">
        <table style="width: 100%;" id="tableListaReferencias">
            <!-- Monta o cabeçalho da tabela -->
            <thead>
                <tr>
                    <th width="15%">Referência</th>
                    <th width="30%">Descrição</th>
                    <th width="10%">Quantidade</th>
                    <th width="10%">Agrup. Mín.</th>
                    <th width="15%">Referência Interna</th>
                    <th width="10%">Preço Venda</th>
                    <th width="10%">Estoque</th>
                </tr>
            </thead>
            

            <tbody>


            <?php

            //Retorna os dados da tabela para ser exibida na tela. As referências que tem na importação.
            $tsql = "SELECT REFERENCIAFABRICANTE,
                            FABRICANTE,
                            REFERENCIAINTERNA,
                            PRECOVENDA,
                            CORLINHA,
                            DESCRICAO,
                            QUANTIDADE,
                            ESTOQUE,
                            AGRUPMIN
                     FROM AD_IMPORTACAO_TELEMARKETING
                     WHERE NUORCAMENTO = $nuorcamento";

            $stmt = sqlsrv_query($conn, $tsql);

            $listaConferencias = "";


            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $listaConferencias .= "
                        <tr id='linhaSelecionada' data-id='$row[REFERENCIAFABRICANTE]' style='background-color: $row[CORLINHA];'>
                            <td style='width: 15%;'>$row[REFERENCIAFABRICANTE] </td>
                            <td style='width: 30%;'>$row[DESCRICAO] </td>
                            <td style='width: 10%;'><input class='quantidade' style='width: 100%;' type='number' value='$row[QUANTIDADE]' min='0'> </td>
                            <td style='width: 10%;'>$row[AGRUPMIN] </td>
                            <td style='width: 15%;'>$row[REFERENCIAINTERNA] </td>
                            <td style='width: 10%;'>$row[PRECOVENDA] </td>
                            <td style='width: 10%;'>$row[ESTOQUE] </td>
                            
                        </tr>
                ";
            }
            echo $listaConferencias;


            ?>
        </tbody>

        </table>


        <div id="popupitens1700" class="popupitens1700" style="display: none;">
			
			<button class="fechar" onclick="fechargera1700();" id="fecharPesquisa">X</button>

			<div style=" width: 100%; overflow: auto; margin-top: 5px; height: 82%;">
				<table width="95%" border="1px" style="margin-top: 5px; margin-left: 7px; table-layout: fixed" id="table">
					<thead>
						<tr>
                            <th width="5%"><input type="checkbox" id="selectAll"></th>
							<th width="20%" style="text-align: center;">Referência</th>
							<th width="60%" style="text-align: center;">Descrição do Produto</th>
							<th width="15%" style="text-align: center;">Quantidade</th>
						</tr>
					</thead>
					<tbody>
                        <?php
                             //Retorna os dados da tabela para ser exibida na tela. As referências que tem na importação.
                            $tsql = "SELECT * FROM [AD_FNT_PRODUTOSPARAGERAR1700_IMPORTACAO_TELEMARKETING]($nuorcamento)";

                            $stmt = sqlsrv_query($conn, $tsql);

                            $listaItnes1700 = "";

                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                                $descricao = utf8_encode($row['DESCRICAO']);

                                $listaItnes1700 .= "
                                        <tr data-referencia = '$row[REFERENCIAINTERNA]'>
                                            <td width='5%'><input type='checkbox' class='itemCheckbox'></td>
                                            <td width='20%' style='text-align: center;'>$row[REFERENCIAINTERNA]</td>
                                            <td width='60%' style='text-align: center;'>$descricao</td>
                                            <td width='15%' style='text-align: center;'>$row[QUANTIDADE]</td>
                                        </tr>
                                ";

                            }

                            echo $listaItnes1700;


                        ?>

					</tbody>
				</table>
			</div>
			
            <!-- Botão para gerar 1700 depois que todos os itens estiverem selecionados. -->
            <div id="floating-container-1700">
				<div id="gerar1700-button" class="floating-button">Gerar 1700</div>
				<div id="cancelar1700-button" class="floating-button" onclick="fechargera1700();">Cancelar</div>
			</div>

		</div>


    </div>

    <!-- Itens que aparecerem na pesquisa -->
    <div style="height: 80%; width: 30%; position: fixed; right: 0; text-align: center; margin-right: 3%;" id="listaReferencia">
        
                <!-- Itens são mostrados via AJAX, baseado na linha que é clicada. -->
        
    </div>

    <div id="floating-container-listaitens">
        <div id="gera1700-button" class="floating-button">Gera 1700</div>
        <div id="finalizar-button" class="floating-button">Finalizar</div>
        <div id="exportarPlanilha" class="floating-button">Exportar</div>
    </div>

</div>

    <script>

        //Função que atualiza os botões para não aparecerem
        var statusfinalizacao = '<?php echo $status; ?>';

        if(statusfinalizacao == 'C') {
            document.getElementById('finalizar-button').style.display = 'none';
            document.getElementById('gera1700-button').style.display = 'block';
        } else {
            document.getElementById('finalizar-button').style.display = 'block';
            document.getElementById('gera1700-button').style.display = 'none';
        }

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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/app.js"></script>
    <script src="./js/listaController.js"> </script>



</body>

</html>