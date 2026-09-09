<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <title>Importação Promoções</title>
</head>
<body>

    <div id="loader" style="display: none;">
        <img style="width: 150px; margin-top: 5%;" src="../../images/soccer-ball-joypixels.gif">
    </div>

    <div class="container">

        <h2 class="titulo">Importação de Promoções</h2>

        <div class="download-modelo">
            <a href="./modelo/Planilha Modelo Promoções Ipebral.xls" download>
                <button class="btnModelo">
                    <i class="fas fa-download"></i> Baixar Planilha Modelo
                </button>
            </a>
        </div>

        <div class="upload-button">
            <input type="file" name="excelFile" accept=".xls,.xlsx" id="escolherArquivo" required>
        </div>

        <div class="campo-promocao">
            <label for="numeroPromocao">Número da Promoção:</label>
            <input type="number" id="numeroPromocao" placeholder="Ex: 403" required>
        </div>


        <div class="button-group">
            <button class="inserePromocoes" id="inserePromocoes">
                <i class="fas fa-upload"></i> Importar Promoções
            </button>
        </div>

    </div>

    <script src="./js/app.js?v=<?= time() ?>"></script>
</body>
</html>
