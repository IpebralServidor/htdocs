<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

//Cria variável de sessão desses parâmetros, para ser usado depois caso precise.
$codParc = isset($_SESSION['codParc']) ? trim($_SESSION['codParc']) : $_POST['codParc'];
$nuorcamento = isset($_SESSION['nuorcamento']) ? trim($_SESSION['nuorcamento']) : $_POST['nuorcamento'];
$codUsuario = $_SESSION['idUsuario'];

//Se foi feito através do clique em uma das tabelas no cabeçalho, cria variáveis de sessão para usar no AJAX
$_SESSION['codParc'] = $codParc;
$_SESSION['nuorcamento'] = $nuorcamento;


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

<div style="display: flex;">
    <div style="height: 80%; width: 60%; float: left; margin-left: 4%; float: left;" id="ListaConferencia" class="listaconferencia">
        <table style="width: 100%;" id="tableListaReferencias">
            <!-- Monta o cabeçalho da tabela -->
            <thead>
                <tr>
                    <th width="15%">Referência</th>
                    <th width="15%">Fabricante</th>
                    <th width="40%">Descrição</th>
                    <th width="10%">Quantidade</th>
                    <th width="15%">Referência Interna</th>
                    <th width="15%">Preço Venda</th>
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
                            QUANTIDADE
                     FROM AD_IMPORTACAO_TELEMARKETING
                     WHERE NUORCAMENTO = $nuorcamento";

            $stmt = sqlsrv_query($conn, $tsql);

            $listaConferencias = "";


            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $listaConferencias .= "
                        <tr id='linhaSelecionada' data-id='$row[REFERENCIAFABRICANTE]' style='background-color: $row[CORLINHA];'>
                            <td style='width: 15%;'>$row[REFERENCIAFABRICANTE] </td>
                            <td style='width: 15%;'>$row[FABRICANTE] </td>
                            <td style='width: 40%;'>$row[DESCRICAO] </td>
                            <td style='width: 5%;'><input class='quantidade' style='width: 100%;' type='number' value='$row[QUANTIDADE]' min='0'> </td>
                            <td style='width: 15%;'>$row[REFERENCIAINTERNA] </td>
                            <td style='width: 15%;'>$row[PRECOVENDA] </td>
                            
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
        <table style="width: 100%;" id="tableListaItens" class="listaconferencia">
            <tbody>
                <!-- Itens são mostrados via AJAX, baseado na linha que é clicada. -->
            </tbody>
        </table>
    </div>

</div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/app.js"></script>
    <script src="./js/listaController.js"> </script>



</body>

</html>