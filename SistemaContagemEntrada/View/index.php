<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <title>Contagem de Entrada</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" type="text/css" href="../css/index.css?v=<?php time() ?>">
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="../../../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../../../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Controller/IndexController.js?v=<?php time() ?>"></script>
</head>
<body>
    <div id="loader" class="loader" style="display: none">
        <img src="../../images/soccer-ball-joypixels.gif" alt="Loading...">
    </div>

    <header class="header">
        <div class="img-voltar">
            <a href="../../menu.php">
                <img src="../../images/216446_arrow_left_icon.png" />
            </a>
        </div>
    </header>

    <main>
    <div class="d-flex justify-content-end">
  <button class="btn btn-primary w-50 fw-bold actionBtn2" id = "atribuirContagem"  onclick="abrirAtribuir()">Atribuir contagem</button>
</div>
         <div class="buttonsRow">
                <div class="w-100 d-flex justify-content-center align-items-center">
                    <input type="text" id="searchInput" class="form-control" style="color: #86B7FE" placeholder="Abrir N°">
                    <button class="btn btn-primary w-50 fw-bold actionBtn" onclick="confirmaAbrirContagemFiltro()">Buscar</button>
                    <button class="btn btn-primary w-50 fw-bold actionBtn" onclick="verificaProximo()">Próximo</button>

                </div>
            </div>
        </div>

        <div style=" overflow-x: auto; width: 100%; border: 10px solid #ddd;" >
            <table width="100%" border="1px" id="table" class="tableNotas">
                <thead>
                    <tr>
                        <th width="20%">Ref.</th>
                        <th width="20%">Empresa</th>
                        <th width="20%">Nro. Único</th>
                        <th width="20%">Nro. Nota</th>
                        <th width="20%">Volume</th>
                        <th width="20%">Parceiro</th>
                        <th width="20%">Usuário</th>
                        <th width="20%">Endereco</th>
                        <th width="20%">Dt.Neg.</th>
                        <th width="20%">TOP</th>
                        <th width="20%">N° Transf</th>

                        


                    </tr>
                </thead>
                <tbody id="listaNotas" style="cursor: pointer"></tbody>
            </table>
        </div>

        <div id="popup-overlay" class="popup-overlay" onclick="fecharAtribuir()">
        <div id="popupconferentes" class="popupconferentes" onclick="event.stopPropagation()">
            <h4>Atribuir contagem</h4>
            <div class="input-busca">
            <!-- <select id="selectConferente" onchange="conferenteSelecionado(this.value)">
                <option value="">Selecione um contador</option>
                <option value="KAUA">KAUA</option>
                <option value="GOULART">GOULART</option>
                <option value="SAMUEL">SAMUEL</option>
                <option value="REMOVER CONTAGENS*">REMOVER CONTAGENS</option>
            </select> -->

            <div class="input-duplo">
            <input type="" id="nomeUsu" class="input-letras"  onchange="buscaInfoNomeUsu();" placeholder="NOMEUSU"
                oninput="this.value = this.value.replace(/[^a-zA-ZÀ-ú\s]/g, '')">
             
            <span class="icone-lupa"><i class="fa fa-search" aria-hidden="true"></i></span>

            <input type="text" id="codUsu" class="input-numeros"  onchange="buscaInfoCodUsu();" placeholder="CODUSU"
                oninput="this.value = this.value.replace(/[^0-9]/g, '') ">
            </div>

            

        </div><br>
            <div class="tabela-scroll">
                <table width="100%"  id="table" class="tableNotas">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" onclick="selecionarTodos(this)"></th> 
                            <th width="20%">Nro. Único</th>
                            <th width="20%">Referência</th>
                            <th width="20%">Qtd.Op</th>
                            <th width="20%">Dt.Neg.</th>
                            <th width="20%">Empresa</th>
                        </tr>
                    </thead>
                    <tbody id="listaAtribuirNotas" style="cursor: pointer"></tbody>
                    <!-- <button onclick="getNrosUnicosSelecionados()">Obter Nros. Únicos Selecionados</button> -->
                </table>
            </div>
            <div class="btn-container">
                <button class="btn btn-primary w-50 fw-bold actionBtn" onclick="atribuirNotaUsuario()">Atribuir</button>
                <button class="btn btn-primary w-50 fw-bold actionBtn" onclick="desatribuirNota()"><i class="fa fa-trash" aria-hidden="true"></i>
                </button>

            </div>
        </div>
    </div>


    <div class="menu-flutuante" id ="botaoExpandir">
        <!-- Botões que aparecem ao expandir -->
        <button class="botao-flutuante item-menu" id="botaoAdicionar" onclick="adicionarItem()">
            <i class="fa-solid fa-clipboard"></i>
        </button>
        <button class="botao-flutuante item-menu" id="botaoPallet" onclick="abrirPallet()">
            <i class="fa-solid fa-boxes-stacked"></i>
        </button>

        <!-- Botão principal para expandir/recolher -->
        <button id="botaoToggleMenu" class="botao-flutuante" onclick="alternarMenuFlutuante()">
            <i class="fa-solid fa-chevron-up"></i>
        </button>
    </div>

 <!-- POP UP LIMPAR PALETES -->

 <div id="popup-overlay-pallet" class="popup-overlay" onclick="fecharPallet()">
        <div id="popupconferentes" class="popupconferentes" onclick="event.stopPropagation()">
            <h4>Liberar Paletes</h4>
            <div class="input-busca">
        </div><br>
            <div class="tabela-scroll">
                <table width="100%"  id="table" class="tableNotas">
                    <thead>
                        <tr>
                            <th width="20%"></th>
                            <th width="20%">Pallet</th>
                            <th width="20%">Empresa</th>
                            <th width="20%">Endereco</th>
                            <th width="20%">Qtd Itens</th>
                        </tr>
                    </thead>
                    <tbody id="listaPaletes" style="cursor: pointer"></tbody>
                    <!-- <button onclick="getNrosUnicosSelecionados()">Obter Nros. Únicos Selecionados</button> -->
                </table>
            </div>
            <div class="btn-container">
                <button class="btn btn-primary w-50 fw-bold actionBtn" onclick="transferirPaletes()">Transferir Itens</button>
            </div>
            </div>
         </div>



         <div id="popup-overlay-pallet2" class="popup-overlay" onclick="fecharPallet()">
        <div id="popupconferentes" class="popupconferentes" onclick="event.stopPropagation()">
            <h4>Transferencias Palete Pendentes</h4>
            <div class="input-busca">
        </div><br>
            <div class="tabela-scroll">
                <table width="100%"  id="table" class="tableNotas">
                    <thead>
                        <tr>
                            <th width="20%">N°unico</th>
                            <th width="20%">Empresa</th>
                            <th width="20%">Endereco</th>
                            <th width="20%">Qtd Itens</th>
                        </tr>
                    </thead>
                    <tbody id="listaPaletesPendentes" style="cursor: pointer"></tbody>
                    <!-- <button onclick="getNrosUnicosSelecionados()">Obter Nros. Únicos Selecionados</button> -->
                </table>
            </div>
            
            </div>
         </div>


        <!--        
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
                       
                    </div>
                </div>
            </div>
        </div> -->


       
        </div>

    </main>    
</body>
</html>
