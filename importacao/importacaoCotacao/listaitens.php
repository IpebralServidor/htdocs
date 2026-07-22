<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

//Cria variável de sessão desses parâmetros, para ser usado depois caso precise.
$codParc = isset($_SESSION['codParc']) ? trim($_SESSION['codParc']) : $_POST['codParc'];
$nuimportacao = isset($_SESSION['nuimportacao']) ? trim($_SESSION['nuimportacao']) : $_POST['nuimportacao'];
$codUsuario = $_SESSION['idUsuario'];

//Se foi feito através do clique em uma das tabelas no cabeçalho, cria variáveis de sessão para usar no AJAX
$_SESSION['codParc'] = $codParc;
$_SESSION['nuImportacao'] = $nuimportacao;




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
               $tsql = " SELECT NUIMPORTACAO, 
                                NUMCOTACAO, 
                                CODPARC, 
                                (SELECT RAZAOSOCIAL
                                    FROM TGFPAR 
                                    WHERE TGFPAR.CODPARC = AD_IMPORTACAO_COTACAO_CAB.CODPARC) AS RAZAOSOCIAL,
                                'Total: ' + CONVERT(VARCHAR(MAX),(SELECT COUNT(*) FROM AD_IMPORTACAO_COTACAO_ITE WHERE AD_IMPORTACAO_COTACAO_ITE.NUIMPORTACAO = AD_IMPORTACAO_COTACAO_CAB.NUIMPORTACAO)) 
                                + ' / Prenchidos: ' + CONVERT(VARCHAR(MAX),(SELECT COUNT(*) FROM AD_IMPORTACAO_COTACAO_ITE WHERE AD_IMPORTACAO_COTACAO_ITE.NUIMPORTACAO = AD_IMPORTACAO_COTACAO_CAB.NUIMPORTACAO)) AS QTD
                            FROM AD_IMPORTACAO_COTACAO_CAB
                            WHERE NUIMPORTACAO =  $nuimportacao";

            $stmt = sqlsrv_query($conn, $tsql);

            $listaConferencias = "";


            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $codCotacao = $row['NUMCOTACAO'];
                $codparc = $row['CODPARC'];
                $razaosocial = $row['RAZAOSOCIAL'];
                $qtd = $row['QTD'];
            
            }
            



    ?>
    
    <table style="width: 100%; position: fixed;" id="table">
        <thead>
            <tr class="bg-dark text-white">
                <th>Núm. Importação: <?php echo $nuimportacao; ?></th>
                <th>Núm. Cotação: <?php echo $codCotacao; ?></th>
                <th>Cód. Parc.: <?php echo $codparc; ?></th>
                <th>Razão Social: <?php echo $razaosocial; ?></th>
                <th id="contadorItens"><?php echo $qtd; ?> </th>
            </tr>
        </thead>
    </table>
    
    <div class="img-voltar">
        <a href="listaImportacoes.php">
            <img src="../../images/216446_arrow_left_icon.png">
        </a>
	</div>

</div>

<div style="display: flex;">
    <div style="height: 90%; width: 60%; float: left; margin-left: 4%; float: left; position: fixed;" id="ListaConferencia" class="listaconferencia">
        <table style="width: 100%;" id="tableListaReferencias">
            <!-- Monta o cabeçalho da tabela -->
            <thead>
                <tr>
                    <th width="10%">Referência Forn.</th>
                    <th width="25%">Descrição Fornecedor</th>
                    <th width="10%">Quantidade</th>
                    <th width="10%">Preço Orçamento</th>
                    <th width="10%">Cód. Produto</th>
                    <th width="10%">Referência Interna</th>
                    <th width="25%">Descrição Interna</th>
                </tr>
            </thead>
            

            <tbody>


            <?php

            //Retorna os dados da tabela para ser exibida na tela. As referências que tem na importação.
            $tsql = "SELECT NUIMPORTACAO,
                            AD_IMPORTACAO_COTACAO_ITE.CODPROD,
                            DESCRICAO_SANKHYA,
                            CODPROPARC,
                            DESCRICAO_FORNECEDOR,
                            QUANTIDADE,
                            UNIDADE_PARC,
                            UNIDADE_SANKHYA,
                            FATOR,
                            PRECO_ORCAMENTO,
                            PRECO_GRAVAR,
                            TGFPRO.REFERENCIA,
                            TGFPRO.DESCRPROD,
                            CORLINHA
                      FROM AD_IMPORTACAO_COTACAO_ITE LEFT JOIN
	                       TGFPRO ON TGFPRO.CODPROD = AD_IMPORTACAO_COTACAO_ITE.CODPROD
                      WHERE NUIMPORTACAO =  $nuimportacao
                      ORDER BY ORDEM";

            $stmt = sqlsrv_query($conn, $tsql);

            $listaConferencias = "";


            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $listaConferencias .= "
                        <tr id='linhaSelecionada' data-id='$row[CODPROPARC]' style='background-color: $row[CORLINHA];'>
                            <td style='width: 10%;'>$row[CODPROPARC] </td>
                            <td style='width: 25%;'>$row[DESCRICAO_FORNECEDOR] </td>
                            <td style='width: 10%;'><input class='quantidade' style='width: 100%;' type='number' value='$row[QUANTIDADE]' min='0' step='1'> </td>
                            <td style='width: 10%;'>$row[PRECO_ORCAMENTO] </td>
                            <td style='width: 10%;'>$row[CODPROD] </td>
                            <td style='width: 10%;'>$row[REFERENCIA] </td>
                            <td style='width: 10%;'>$row[DESCRPROD] </td>
                        </tr>
                ";
            }
            echo $listaConferencias;


            ?>
        </tbody>

        </table>



    </div>

    <!-- Itens que aparecerem na pesquisa -->
    <div style="height: 80%; width: 30%; position: fixed; right: 0; text-align: center; margin-right: 3%;" id="listaReferencia">
        
                <!-- Itens são mostrados via AJAX, baseado na linha que é clicada. -->
        
    </div>

    <!-- <div id="floating-container-listaitens">
        <div id="gera1700-button" class="floating-button-listaitens">Gera 1700</div>
        <div id="finalizar-button" class="floating-button-listaitens">Finalizar</div>
        <div id="exportarPlanilha" class="floating-button-listaitens">Exportar</div>
    </div> -->

</div>

    
<!-- Botão de próxima página -->
<button id="btnProximaPagina" onclick="irParaProximaPagina()">
    Próxima &raquo;
</button>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/app.js"></script>
    <script src="./js/listaController.js"> </script>



</body>

</html>