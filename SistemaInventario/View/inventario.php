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
    <script src="../../../node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Controller/InventarioController.js?v=<?php time() ?>"></script>
    <title>Inventário</title>
</head>

<body>
    <div class="page">
        <div id="loader" style="display: none;">
            <img style=" width: 150px; margin-top: 5%;" src="../../images/soccer-ball-joypixels.gif">
        </div>

        <div class="popup" id="popupConfirmarReferencia">
            <div class="overlay"></div>
            <div class="content">
                <div style="width: 100%;">
                    <div class="close-btn" onclick="togglePopupConfirmarReferencia(); limpaCampo('referencia');">
                        <i class="fa-solid fa-xmark"></i>
                    </div>

                    <div class="div-form">
                        <div class="form">
                            <label>Digite a referência novamente:</label>
                            <input type="text" class="form-control" id="confirmacaoReferencia" style="color: #86B7FE !important;" required>
                            <button onclick="confirmaReferencia()">Confirmar</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="collapse" id="itensInventario">
            <div class="background">
                <div class="titleBox">
                    <h6 id="titleBoxH6"></h6>
                </div>
            </div>
            <div class="card-body">
                <table id="tableItens" class="table tableItens">
                    <thead>
                        <tr>
                            <th>Referência</th>
                            <th>Controle</th>
                            <th>Data Inventário</th>
                            <th>Usuário</th>
                            <th>Qtd Inventário</th>
                        </tr>
                    </thead>
                    <tbody id="itens">
                    </tbody>
                </table>
            </div>
        </div>
        <header class="header">
            <div class="img-voltar">
                <a href="./index.php">
                    <img src="../../images/216446_arrow_left_icon.png" />
                </a>
            </div>
            <div class="nota fw-bold">
                <span id="enderecoAtual"></span>
            </div>
            <div id="setaDownDiv" class="setaDown fw-bold" data-bs-toggle="collapse" data-bs-target="#itensInventario" aria-expanded="false" aria-controls="itensInventario">
                <span>
                    <i id="setaDown" class="fa-solid fa-caret-down"></i>
                </span>
            </div>
        </header>
    </div>
    <main>
        <div class="header-body">
            <div style="width: 100%">
                <div class="row">
                    <div class="mb-1 col-6">
                        <label for="referencia" class="form-label">Referência <span style="color: red">*</span> </label>
                        <input type="text" class="form-control" oninput="iniciarMedicaoReferencia();" onchange="finalizarMedicaoReferencia();" id="referencia" style="color: #86B7FE !important;">
                    </div>
                    <div class="mb-1 col-6">
                        <label for="lote" class="form-label">Lote:</label>
                        <input type="text" class="form-control" id="lote" disabled value="" style="color: #86B7FE !important;">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 col-6">
                        <label for="quantidade" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="quantidade" style="color: #86B7FE !important;">
                    </div>

                    <div class="mb-1 col-6">
                        <label for="qtdMax" class="form-label">Qtd Máx Local</label>
                        <input type="number" class="form-control" id="qtdmax" style="color: #86B7FE !important;">
                    </div>

                    <div class="mt-3">
                        <div class="form-control" style="font-size: 12px !important;">
                            <div>
                                <span class="fw-bold">Descrição: </span><span id="descrprod"></span>
                            </div>
                            <div>
                                <span class="fw-bold">Agrupamento mínimo: </span><span id="agrupmin"></span>
                            </div>
                            <div>
                                <span class="fw-bold">Ref. fornecedores: </span><span id="obsetiqueta"></span>
                            </div>
                        </div>
                    </div>

                    <div class="image d-flex justify-content-center">
                        <img id="imagemproduto" style="vertical-align: middle; margin: auto; max-width: 100%; max-height: 166px;" src="" />
                    </div>
                </div>
            </div>


        </div>
        <div class="buttonsRow">
            <div class="mt-5 w-100 d-flex justify-content-center align-items-center">
                <button id="contarBtn" onclick="verificaRecontagem();" class="btn btn-primary w-75 fw-bold actionBtn">Contar</button>
            </div>
            <div class="mt-5 w-100 d-flex justify-content-center align-items-center">
                <button id="movimentarBtn" onclick="checafinalizaInventario();" class="btn btn-primary w-75 fw-bold actionBtn">Finalizar inventario</button>
            </div>
        </div>

        <div id="progress-container">
            <div id="progress-bar"></div>
        </div>
    </main>
</body>

</html>