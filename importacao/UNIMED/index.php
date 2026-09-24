<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <title>Importação de Fatura UNIMED</title>
</head>
<body>

    <div id="loader" style="display: none;">
        <img style="width: 150px; margin-top: 5%;" src="../../images/soccer-ball-joypixels.gif">
    </div>

    <div class="container">

        <h2 class="titulo">Importação de Fatura UNIMED</h2>
        <h5>Selecione o PDF da fatura ("Dados da Fatura") para extrair e gravar os beneficiários no banco.</h5><br>

        <div class="upload-button">
            <input type="file" name="pdfFile" accept=".pdf" id="escolherArquivo" required>
        </div>

        <div class="campo-tipo">
            <label for="tipoArquivo">Tipo de Arquivo:</label>
            <select id="tipoArquivo" required>
                <option value="" disabled selected>Selecione...</option>
                <option value="UNIMAX">UNIMAX</option>
                <option value="UNIPART">UNIPART</option>
                <option value="ODONTO">Odonto</option>
            </select>
        </div> <br>

        <div class="button-group">
            <button class="insereUNIMED" id="insereUNIMED">
                <i class="fas fa-upload"></i> Importar Fatura
            </button>
        </div>

        <div id="resultado" class="resultado" style="display: none;"></div>

    </div>

    <script src="./js/app.js?v=<?= time() ?>"></script>
</body>
</html>
