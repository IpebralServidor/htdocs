<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <title>Importação de Referências de Fornecedor</title>
</head>
<body>

    <div id="loader" style="display: none;">
        <img style="width: 150px; margin-top: 5%;" src="../../images/soccer-ball-joypixels.gif">
    </div>

    <div class="container">

        <h2 class="titulo">Importação de Referências de Fornecedor</h2>
        <h5>(Coloque o Código do Produto ou a Referência, apenas um já é suficiente, não precisa ser os dois.)</h5><br>

        <div class="download-modelo">
            <a href="./modelo/Planilha Modelo TGFPAP.xls" download>
                <button class="btnModelo">
                    <i class="fas fa-download"></i> Baixar Planilha Modelo
                </button>
            </a>
        </div>

        <div class="upload-button">
            <input type="file" name="excelFile" accept=".xls,.xlsx" id="escolherArquivo" required>
        </div>

        <div class="campo-promocao">
            <label for="codigoParceiro">Código do Parceiro:</label>            
            <input type="number" id="codigoParceiro" placeholder="Ex: 12321" required> <br><br>
            <label for="codigoUsuario">Código do Usuário:</label>
            <input type="number" id="codigoUsuario" placeholder="Ex: 181" required>
        </div>


        <div class="button-group">
            <button class="insereTGFPAP" id="insereTGFPAP">
                <i class="fas fa-upload"></i> Importar Referências
            </button>
        </div>

    </div>

    <script src="./js/app.js?v=<?= time() ?>"></script>
</body>
</html>
