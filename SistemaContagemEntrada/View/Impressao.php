<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <title>Impressão</title>
    <link rel="stylesheet" type="text/css" href="../css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../../../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Controller/impressaoController.js?v=<?php echo time(); ?>"></script>
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
        <div style="overflow-x: auto; width: 100%; border: 10px solid #ddd;">
            <table width="100%" border="1px" id="table" class="tableNotas">
                <thead>
                    <tr>
                        <th>Referência</th>
                        <th>Descrição</th>
                        <th>Locais</th>
                        <th>Total</th>
                        <th>Últ. Mov.</th>
                    </tr>
                </thead>
                <tbody id="listaImpressao"></tbody>
            </table>
        </div>
    </main>
</body>

</html>
