<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="stylesheet" type="text/css" href="../css/main.css?v=<?php time() ?>">
    <link rel="stylesheet" href="../../../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../../../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Controller/IndexController.js?v=<?php time() ?>"></script>
    <title>Inventário</title>
</head>

<body>
    <div>
        <div id="loader" style="display: none;">
            <img style=" width: 150px; margin-top: 5%;" src="../../images/soccer-ball-joypixels.gif">
        </div>

        <div class="popup" id="popFiltroInventario">
            <div class="overlay"></div>
            <div class="content">
                <div style="width: 100%;">
                    <div class="close-btn" onclick="abrirPopFiltroInventario()">
                        <i class="fa-solid fa-xmark"></i>
                    </div>

                    <div class="div-form">
                        <div class="form">
                            <strong>Informe a empresa:</strong>
                            <select id="empresaInventario" class="form-control">
                                <option value="1">Matriz</option>
                                <option value="6">Nordeste</option>
                                <option value="7">Triângulo</option>
                            </select>
                            <strong>Informe o intervalo de endereço:</strong>
                            <div style="display: flex; align-items: center;">
                                <input type="number" id="endIni" style="width: 100%; margin-right: 5px">
                                <label> a </label>
                                <input type="number" id="endFim" style="width: 100%; margin-left: 5px">
                            </div>
                            <div style="margin: 10px 0;">
                                <input type="checkbox" id="mostrarConcluidos">
                                <label for="mostrarConcluidos">Mostrar concluídos</label>
                            </div>
                            <button id=" confirmaFiltroInventario" onclick="confirmaFiltroInventario()">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <header class="header">
            <div class="img-voltar">
                <a href="../../menu.php">
                    <img src="../../images/216446_arrow_left_icon.png" />
                </a>
            </div>
            <div class="setaDown fw-bold" onclick="abrirPopFiltroInventario()">
                <span>
                    <i class="fa-solid fa-filter"></i>
                </span>
            </div>
        </header>
        <div style=" overflow-x: auto; width: 100%; border: 1px solid #ddd;">
            <table width="100%" border="1px" id="table" class="tableEnderecos">
                <thead>
                    <tr>
                        <th width="20%">Endereço</th>
                        <th width="20%">Qtd Itens</th>
                        <th width="20%">Inventariado</th>
                        <th width="20%">Status</th>
                        <th width="20%">Usuário</th>

                    </tr>
                </thead>
                <tbody id="enderecosInventario">
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="mostraBloqueio" tabindex="-1" role="dialog" aria-labelledby="mostraBloqueioLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="p-3">
                        <div style=" overflow-x: auto; width: 100%; border: 1px solid #ddd;">
                            <table width="100%" border="1px" id="table" class="tableEnderecos">
                                <thead>
                                    <tr>
                                        <th width="16%">Número Único</th>
                                        <th width="16%">TOP</th>
                                        <th width="16%">Data</th>
                                        <th width="16%">Referência</th>
                                        <th width="16%">Controle</th>
                                        <th width="16%">Qtd</th>
                                    </tr>
                                </thead>
                                <tbody id="notasBloqueio">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>