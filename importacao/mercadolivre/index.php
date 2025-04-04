<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Importação ML</title>

</head>
<body>

    <div id="loader" style="display: none;">
        <img style=" width: 150px; margin-top: 5%;" src="../../images/soccer-ball-joypixels.gif">
    </div>

    <div class="container">

        <div class="upload-button">
            <!-- <span> Selecione o Arquivo para Importação </span> -->
            <!-- <br> -->
            <input type="file" name="excelFile" accept=".xls,.xlsx" id="escolherArquivo" required>
            <!-- <button type="submit" id="uploadarquivo">Importar Arquivo</button> -->
        </div>

        <div class="account-selection">
            <label for="account">Selecione a Conta:</label>
            <select id="account">
                <option value="5">Conta 5</option>
                <option value="4">Conta 4</option>
                <option value="3">Conta 3</option>
            </select>
        </div>

        <div class="button-group">
            <button class="insereRecebimentos" id="insereRecebimentos">Insere Recebimentos</button>
            <button class="insereMovBancaria" id="insereMovBancaria">Insere Movimentação Bancária</button> 
            <button class="insereMovCielo">Insere Movimentação Cielo</button>
            <button class="insereLiberacoes" id="insereLiberacoes">Insere Liberações</button>
            <button class="insereReclamacoes">Insere Reclamações</button>
            <button class="insereExtratoInter">Insere Extrato Inter</button>
        </div>
    </div>


    <script src="./js/app.js"></script>
    <script> 

    </script>
</body>
</html>