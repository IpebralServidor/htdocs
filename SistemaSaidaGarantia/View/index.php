<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <title>Saida de Garantia</title>
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


 
    
    <div class="menu-flutuante" id ="botaoExpandir">
        <!-- Botões que aparecem ao expandir -->
        <!-- <button class="botao-flutuante item-menu" id="botaoAdicionar" onclick="adicionarItem()">
            <i class="fa-solid fa-clipboard"></i>
        </button> -->
        <button class="botao-flutuante item-menu" id="botaoPallet" onclick="abrirPallet()">
            <i class="fa-solid fa-boxes-stacked"></i>
        </button>

        <!-- Botão principal para expandir/recolher -->
        <button id="botaoToggleMenu" class="botao-flutuante" onclick="alternarMenuFlutuante()">
            <i class="fa-solid fa-chevron-up"></i>
        </button>
    </div>

           
        </div>

        <div style=" overflow-x: auto; width: 100%; border: 10px solid #ddd;" >
            <table width="100%" border="1px" id="table" class="tableNotas">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll" onclick="selecionarTodos(this)"></th> 
                        <th width="20%">Referencia</th>
                        <th width="20%">Qtd</th>
                        <th width="20%">Empresa</th>
                        <th width="20%">Codlocal</th>

                    </tr>
                </thead>
                <tbody id="listaNotas" style="cursor: pointer"></tbody>
            </table>
        </div>

     
        <div id="popup-overlay-pallet" class="popup-overlay" onclick="fecharPallet()">
        <div id="popupconferentes" class="popupconferentes" onclick="event.stopPropagation()">
            <h4>Deseja transferir itens da Saida de Garantia?</h4>
            <div class="input-busca">
        </div><br>
            
            <div class="btn-container">
                <button class="btn btn-primary w-50 fw-bold actionBtn" onclick="transferirGarantia()">Transferir Itens</button>
            </div>
            </div>
         </div>

       
        </div>

    </main>    
</body>
</html>
