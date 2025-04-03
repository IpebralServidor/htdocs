<?php

//inclui a coxão e valida usuário logado.
include "../../conexaophp.php";
require_once '../../App/auth.php';

//Armazena o usuário que está fazendo a cotação.
$codUsuario = $_SESSION['idUsuario'];

//Coloca os valores de orçamento e código do parceiro como nulo, para recarregar eles novamente no clique em uma linha ou na criação.
$_SESSION['nuorcamento'] = null;
$_SESSION['codParc'] = null;
$_SESSION['codEmp'] = null;
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
</head>    

<body class="background-lista">

<!-- Ícone da bola de futebol, para o carregamento da importação dos itens -->
<div id="loader" style="display: none;">
    <img style=" width: 150px; margin-top: 5%;" src="../../images/soccer-ball-joypixels.gif">
</div>

    <br>
    <br>

<div style="display: flex;">
    <div style="height: 80%; width: 60%; float: left; margin-left: 4%; float: left;" id="ListaOrcamento" class="listaconferencia">
        <table style="width: 100%;" id="tableListaOrcamento">
            <thead>
                <tr>
                    <th width="20%">Núm. Orçamento</th>
                    <th width="20%">Empresa</th>
                    <th width="20%">Parceiro</th>
                    <th width="20%">Data Criação</th>
                    <th width="20%">Status</th>
                </tr>
            </thead>
            
            <tbody>

            <?php

            //echo '<script> alert("'.$codUsuario.'") </script>';
            $tsql = "SELECT NUORCAMENTO,
                            CODPARC,
                            DTCRIACAO,
                            CASE WHEN STATUSORCAMENTO = 'A' THEN 'Em Andamento'
                                 WHEN STATUSORCAMENTO = 'C' THEN 'Concluído'
                            END AS STATUSORCAMENTO,
                            CORLINHA,
                            CODEMP
                     FROM AD_IMPORTACAO_TELEMARKETING_CAB
                     WHERE CODUSU = $codUsuario
                     ORDER BY NUORCAMENTO DESC
                     ";

            //echo $tsql;
            $stmt = sqlsrv_query($conn, $tsql);

            $listaConferencias = "";

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $dataHora = $row['DTCRIACAO']->format('d/m/Y H:i:s');

                $listaConferencias .= "
                        <tr id='linhaSelecionada' data-id='{$row['NUORCAMENTO']}' style='background-color: {$row['CORLINHA']};'>
                            <td style='width: 30px;'>{$row['NUORCAMENTO']} </td>
                            <td style='width: 30px;'>{$row['CODEMP']} </td>
                            <td style='width: 30px;'>{$row['CODPARC']} </td>
                            <td style='width: 30px;'>$dataHora </td>
                            <td style='width: 30px;'>{$row['STATUSORCAMENTO']} </td>
                        </tr>
                ";
            }

            echo $listaConferencias;


            ?>
        </tbody>

        </table>
    </div>

</div>
        <!-- Botão que abre a barra lateral de pesquisa -->
        <div id="floating-button" onclick="openSidebar()">+</div>
        <div id="search-container">

            <div class="sidebar-header">
                <span>Incluir Orçamento</span>
                <button onclick="closeSidebar()">X</button>
            </div>

            <!-- Formulário que chama o arquivo para importação da planilha  -->
            <form action="importaplanilha.php" method="post" enctype="multipart/form-data" onsubmit="showLoading()">
                <!-- <span> Código do Parceiro </span> -->
                <input type="text" name="codParc" id="codParc" placeholder="Digite o Cód. do Parceiro" required>
                <input type="text" name="codEmp" id="codEmp" placeholder="Digite a Empresa (Código)" required>
                <!-- <span> Arquivo para Upload </span> -->
                <input type="file" name="excelFile" accept=".xls,.xlsx" id="escolherArquivo" required>
                <button type="submit" id="uploadarquivo">Upload</button>
            </form>
            <!-- Fim do Formulário que chama o arquivo para importação da planilha  -->

            <!-- Botão para poder baixar Planilha Modelo -->
            <a href="planilhamodelo/Planilha Modelo.xlsx" download="Planilha Modelo.xlsx" style="text-decoration: none;">
                <button id="baixararquivo">Baixar Planilha Modelo</button>
            </a>

            
        </div>

    <!-- Arquivos com funções JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/app.js"></script>
    <script src="./js/listaController.js"> </script>

</body>

</html>