<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="stylesheet" type="text/css" href="../css/Contagem.css?v=<?php time() ?>">
    <link rel="stylesheet" href="../../../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../../../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../../node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Controller/contagemController.js?v=<?php time() ?>"></script>
    <script src="../../components/emailFoto/js/emailFoto.js"></script>    
    <title>Contagem</title>
</head>
<body>

<!-- POP UP ENVIAR EMAIL FOTO E AUTORIZA FINALZAR CONTAGEM -->
    <div class="page">
        <div id="emailFoto"></div>
        <div id="loader" style="display: none;">
            <img style="width: 150px; margin-top: 5%;" src="../../images/soccer-ball-joypixels.gif" alt="Loading...">
        </div>
        

        <div class="popup" id="popAutorizaTrava">
            <div class="overlay"></div>
            <div class="content">
                <div style="width: 100%;">
                    <div class="close-btn" onclick="abrirPopAutorizaContagem()">
                        <i class="fa-solid fa-xmark" onclick="fecharPopAutorizaContagem()"></i>
                    </div>

                    <div class="form">
                        <strong><label id="msg">A contagem está abaixo do esperado. Para finalizar, é necessária a autorização do gerente.</label></strong>
                        <br>
                        <label>Usuário:</label>
                        <input type="text" id="user" required>

                        <label>Senha:</label>
                        <input type="password" id="senha" required>
                        <button id="btn-autorizacorte" onclick="autorizaTrava();">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="ocorrenciaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><span id="mensagemModal">Motivo da ocorrência:</span></h5>
                        <div class="btnClose ms-auto" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>
                    <div class="modal-body">
                        <input type="radio" id="danificado" name="ocorrencia" value="Produto danificado">
                        <label class="labelOcorrencia" for="danificado">Produto danificado</label><br>

                        <input type="radio" id="trocado" name="ocorrencia" value="Produto trocado">
                        <label class="labelOcorrencia" for="trocado">Produto trocado</label><br>

                        <input type="radio" id="naorecebido" name="ocorrencia" value="Produto nao recebido">
                        <label class="labelOcorrencia" for="naorecebido">Produto nao recebido</label><br>

                        <div class="d-flex align-items-center">
                            <label class="labelOcorrencia me-2" for="outros">Outros: </label>
                            <input type="text" id="outros" name="outros" class="flex-grow-1" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary fw-bold actionBtn" id="btnAplicarOcorrencia" onclick="aplicarOcorrencia();">Aplicar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="subcontagemModal" tabindex="-1" aria-labelledby="subcontagemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="subcontagemModalLabel">Contagens: <span id="referenciaSpan"></span></h5>
                        <div class="btnClose ms-auto" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <table id="tableContagens" class="table tableItens">
                                <thead>
                                    <tr>
                                        <th>Nro.</th>
                                        <th>Data</th>
                                        <th>Qtd</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="contagens">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="collapse" id="itensContagem">
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
                            <th>Qtd Contagem</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="itens">
                    </tbody>
                </table>
            </div>
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


        <header class="header">
            <div class="img-voltar">
            <a href="index.php" style="background"><img src="../../../images/216446_arrow_left_icon.png" alt="Voltar"></a></div>
            <div class="nota fw-bold">
                <span id="notaAtual"></span>
            </div>
            <div id="setaDownDiv" class="setaDown" data-bs-toggle="collapse" data-bs-target="#itensContagem" aria-expanded="false" aria-controls="itensContagem">
                <span>
                    <i id="setaDown" class="fa-solid fa-caret-down"></i>
                </span>
            </div>
        </header>

        <main>
            <div class="header-body">
                <div style="width: 100%">
                    <div class="row">
                        <div class="mb-1 col-6">
                            <label for="referencia" class="form-label">Referência <span style="color: red">*</span></label>
                            <input type="text" class="form-control" id="referencia" oninput="iniciarMedicaoReferencia();" onchange="finalizarMedicaoReferencia();">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="lote" class="form-label">Lote</label>
                            <input type="text" class="form-control" id="lote" disabled >
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-1 col-6">
                            <label for="codbalanca" class="form-label">Cod. Balança</label>
                            <input type="number" class="form-control" id="codbalanca" >
                        </div>
                        <div class="mb-1 col-6">
                            <label for="quantidade" class="form-label">Qtd. Total Contada</label>
                            <input type="number" class="form-control" id="quantidade">
                        </div>
                    </div>
                </div>
                <div class="mt-3 col-12">
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
                        <div class="special-item total">
                            <span class="fw-bold">Qtd. Separar: </span><span id="qtdseparar"></span><br>
                            <!-- <span class="fw-bold">Total: </span><span id="total"></span> -->
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="image d-flex align-items-center justify-content-center">
                        <img id="imagemproduto" style="vertical-align: middle; margin: auto; max-width: 100%; max-height: 166px;" src="" />
                    </div>
                </div>

                <div class="dimensions-box">
                    <div class="dimensions-header">
                        <h4>Dimensões e Peso</h4>
                        <!-- <button class="update-btn" onclick="atualizarDimensoes()"><i class="fa-solid fa-arrows-rotate"></i> Atualizar</button> -->
                    </div>
                    <div class="row">
                        <div class="mb-1 col-6">
                            <label for="peso" class="form-label">Peso (kg)</label>
                            <input type="number" class="form-control" id="peso" step="0.01">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="largura" class="form-label">Largura (cm)</label>
                            <input type="number" class="form-control" id="largura" step="0.1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-1 col-6">
                            <label for="altura" class="form-label">Altura (cm)</label>
                            <input type="number" class="form-control" id="altura" step="0.1">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="comprimento" class="form-label">Comprimento (cm)</label>
                            <input type="number" class="form-control" id="comprimento" step="0.1">
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-center">
                        <img id="imagemcaixa" style="vertical-align: middle; margin: auto; max-width: 100%; max-height: 166px;" src="../../images/dimensoes.png" />
                    </div>
                </div>    
                

            </div>

            <div class="buttonsRow"> 
                <div class="mt-5 w-100 d-flex justify-content-center align-items-center"> 
                    <button id="contarBtn" onclick="verificaRecontagem();" class="btn btn-primary w-100 fw-bold actionBtn">Contar/Parcial</button>
                </div>
                <div class="mt-5 w-100 d-flex justify-content-center align-items-center">
                    <button id="finalizaContarBtn" onclick="verificaFinalizaContagem();" class="btn btn-primary w-100 fw-bold actionBtn">Finalizar Contagem</button>
                </div>
                <div class="mt-5 w-100 d-flex justify-content-center align-items-center">
                    <button id="movimentarBtn" class="btn btn-primary w-100 fw-bold actionBtn" data-bs-toggle="modal" data-bs-target="#ocorrenciaModal">Ocorrência</button>
                </div>
            </div>
            
            <div id="progress-container">
                <div id="progress-bar"></div>
            </div>
        </main>
    </div>
</body>
</html>