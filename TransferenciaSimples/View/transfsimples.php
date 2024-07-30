<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="stylesheet" type="text/css" href="../css/main.css?v=<?php time() ?>">
    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
    <script src="../../../js/bootstrap.bundle.min.js"></script>
    <script src="../../../js/jquery-3.7.1.min.js"></script>
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
    <script src="../Controller/TransfSimplesController.js?v=<?php time() ?>"></script>
    <title>Transferência Simples</title>
</head>

<body>
    <div class="page">
        <div id="loader" style="display: none;">
            <img style=" width: 150px; margin-top: 5%;" src="../../images/soccer-ball-joypixels.gif">
        </div>
        <div class="img-voltar">
            <a href="../../menu.php">
                <img src="../../../images/216446_arrow_left_icon.png" />
            </a>
        </div>
        <main>
            <div class="header-body">
                <div style="width: 100%">
                    <div class="mb-1">
                        <label for="endereco" class="form-label">Empresa <span style="color: red">*</span></label>
                        <select name="empresas" id="empresas" class="selectEmpresa" onclick="desabilitaSelectPadrao()">
                            <option value="" id="selectPadrao" selected>Escolha uma empresa</option>
                            <option value="1">Matriz</option>
                            <option value="3">CD Geral</option>
                            <option value="6">Nordeste</option>
                            <option value="7">Triângulo</option>
                            <option value="10">Mecontech</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="mb-1 col-6">
                            <label for="referencia" class="form-label">Referência <span style="color: red">*</span> </label>
                            <input type="text" class="form-control" onchange="buscaInformacoesProduto()" id="referencia" style="color: #86B7FE !important;">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="lote" class="form-label">Lote:</label>
                            <input type="text" class="form-control" id="lote" disabled value="" style="color: #86B7FE !important;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-1 col-6">
                            <label for="endereco" class="form-label">Endereço saída <span style="color: red">*</span></label>
                            <input type="number" class="form-control" onchange="buscaInformacoesLocal()" id="endsaida" style="color: #86B7FE !important;">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="qtdMax" class="form-label">Endereço chegada <span style="color: red">*</span></label>
                            <input type="number" class="form-control" id="endchegada" style="color: #86B7FE !important;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-1 col-6">
                            <label for="qtdMax" class="form-label">Qtd Máx Local <span style="color: red">*</span></label>
                            <input type="number" class="form-control" id="qtdmax" style="color: #86B7FE !important;">
                        </div>

                        <div class="col-6 mt-3">
                            <div class="form-control" style="font-size: 12px !important;">
                                <div>
                                    <span class="fw-bold">Qtd.local retirada : </span><span id="qtdlocal">0</span>
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