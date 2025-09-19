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
    <script src="../Controller/TransfSimplesController.js?v=<?php time() ?>"></script>
    <script src="../../components/popupLocalProduto/js/popupLocalProduto.js?v=<?php time() ?>"></script>
    <script src="../../components/emailFoto/js/emailFoto.js"></script>
    <title>Transferência Simples</title>
</head>

<body>
    <div class="page">
        <div id="emailFoto"></div>
        <div id="modalLocalProduto"></div>
        <div id="loader" style="display: none;">
            <img style=" width: 150px; margin-top: 5%;" src="../../images/soccer-ball-joypixels.gif">
        </div>
        <div class="img-voltar">
            <a href="../../menu.php">
                <img src="../../../images/216446_arrow_left_icon.png" />
            </a>
        </div>

        <!-- Modal para confirmar referência-->
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

        <!-- Modal para confirmar endereço de saída-->

        <div class="popup" id="popupConfirmarEndSaida">
            <div class="overlay"></div>
            <div class="content">
                <div style="width: 100%;">
                    <div class="close-btn" onclick="togglePopupConfirmarEndSaida(); limpaCampo('endsaida');">
                        <i class="fa-solid fa-xmark"></i>
                    </div>

                    <div class="div-form">
                        <div class="form">
                            <label>Digite o endereço de saída novamente:</label>
                            <input type="number" class="form-control" id="confirmacaoEndSaida" style="color: #86B7FE !important;" required>
                            <button onclick="confirmaEndSaida()">Confirmar</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal para confirmar endereço de chegada-->

        <div class="popup" id="popupConfirmarEndChegada">
            <div class="overlay"></div>
            <div class="content">
                <div style="width: 100%;">
                    <div class="close-btn" onclick="togglePopupConfirmarEndChegada(); limpaCampo('endchegada');">
                        <i class="fa-solid fa-xmark"></i>
                    </div>

                    <div class="div-form">
                        <div class="form">
                            <label>Digite o endereço de chegada novamente:</label>
                            <input type="number" class="form-control" id="confirmacaoEndChegada" style="color: #86B7FE !important;" required>
                            <button onclick="confirmaEndChegada()">Confirmar</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <main>
        <div class="header-body">
            <div style="width: 100%">
                <div class="mb-1">
                    <label for="endereco" class="form-label">Empresa <span style="color: red">*</span></label>
                    <select name="empresas" id="empresas" class="selectEmpresa" onclick="desabilitaSelectPadrao()" onchange="buscaLocalPadrao();">
                        <option value="" id="selectPadrao" selected>Escolha uma empresa</option>
                        <option value="1">Matriz</option>
                        <option value="3">CD Geral</option>
                        <option value="6">Nordeste</option>
                        <option value="7">Triângulo</option>
                        <option value="10">Mecontech</option>
                        <option value="30">Remedcar (Emp 30)</option>
                    </select>
                </div>
                <div class="row">
                    <div class="mb-1 col-6">
                        <label for="referencia" class="form-label">Referência/Codbarra <span style="color: red">*</span> </label>
                        <input type="text" class="form-control" oninput="iniciarMedicaoReferencia();" onchange="finalizarMedicaoReferencia();" id="referencia" style="color: #86B7FE !important;">
                    </div>
                    <div class="mb-1 col-6">
                        <label for="lote" class="form-label">Lote:</label>
                        <input type="text" class="form-control" id="lote" disabled value="" style="color: #86B7FE !important;">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 col-6">
                        <label for="endereco" class="form-label">Endereço saída <span style="color: red">*</span></label>
                        <input type="number" class="form-control" oninput="iniciarMedicaoEndSaida()" onchange="finalizarMedicaoEndSaida();" id="endsaida" style="color: #86B7FE !important;">
                    </div>
                    <div class="mb-1 col-6">
                        <label for="qtdMax" class="form-label">Endereço chegada <span style="color: red">*</span>
                            <span id="popupLocalProduto"></span>
                        </label>
                        <input type="number" class="form-control" oninput="iniciarMedicaoEndChegada()" onchange="finalizarMedicaoEndChegada();" id="endchegada" style="color: #86B7FE !important;">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 col-6">
                        <label for="qtdneg" class="form-label">Quantidade <span style="color: red">*</span></label>
                        <input type="number" class="form-control" id="qtdneg" disabled value="" style="color: #86B7FE !important;">
                    </div>
                    <div class="mb-1 col-6">
                        <label for="qtdMax" class="form-label">Qtd Máx Local <span style="color: red">*</span></label>
                        <input type="number" class="form-control" id="qtdmax" style="color: #86B7FE !important;">
                    </div>
                </div>
                <div class="row">
                    <div class="mt-3">
                        <div class="form-control" style="font-size: 12px !important;">
                            <div>
                                <span class="fw-bold">Descrição: </span><span id="descrprod"></span>
                            </div>
                            <div>
                                <span class="fw-bold">Qtd.local retirada: </span><span id="qtdlocal">0</span>
                            </div>
                            <div style="text-align: right;">
                                <span class="fw-bold" style="display: inline-block; cursor: pointer;" onclick="preencheEnderecoChegadaPalete();">
                                    Palete Pendencia
                                </span>
                                <br />
                                <span class="fw-bold" style="display: inline-block; cursor: pointer;" onclick="preencheEnderecoChegadaPalete();">
                                    de Conferencia
                                </span>
                                <span id="localpalete"></span>
                            </div>

                            <div>
                                <span class="fw-bold">Local padrão: </span><span id="localpadrao" onclick="preencheEnderecoChegada();"></span>
                            </div>
                           
                        </div>
                          
                    </div>

                </div>
                <div class="image d-flex justify-content-center">
                    <img id="imagemproduto" style="vertical-align: middle; margin: auto; max-width: 100%; max-height: 166px;" src="" />
                </div>
            </div>


        </div>

        <div class="mt-5 w-100 d-flex justify-content-center align-items-center">
            <button id="transferirProdutoBtn" onclick="validaParametros()" class="btn btn-primary w-75 fw-bold transferirProdutoBtn">Transferir Produto</button>
        </div>
    </main>
    </div>
</body>

</html>