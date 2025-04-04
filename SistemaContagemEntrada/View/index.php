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
         <div class="buttonsRow">
                <div class="w-100 d-flex justify-content-center align-items-center">
                    <input type="text" id="searchInput" class="form-control" style="color: #86B7FE" placeholder="Abrir N°">
                    <button class="btn btn-primary w-50 fw-bold actionBtn" onclick="confirmaAbrirContagemFiltro()">Buscar</button>
                </div>
            </div>
        </div>

        <div style=" overflow-x: auto; width: 100%; border: 1px solid #ddd;">
            <table width="100%" border="1px" id="table" class="tableNotas">
                <thead>
                    <tr>
                        <th width="20%">Tipo</th>
                        <th width="20%">Nro. Único</th>
                        <th width="20%">Qtd Itens</th>
                        <th width="20%">Status</th>
                        <th width="20%">Usuário</th>
                        <th width="20%">Parceiro</th>
                        <th width="20%">Dt.Neg.</th>
                        <th width="20%">TOP</th>
                        <th width="20%">Empresa</th>
                        <th width="20%">N° Transf</th>

                    </tr>
                </thead>
                <tbody id="listaNotas" style="cursor: pointer"></tbody>
            </table>
        </div>
    </main>    
</body>
</html>
