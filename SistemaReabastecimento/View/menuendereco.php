<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <link href="../css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../../../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../../node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Controller/MenuEnderecoController.js"></script>
</head>

<body>
    <div class="popup" id="popAlterarSenha">
        <div class="overlay"></div>
        <div class="content">
            <div style="width: 100%;">
                <div class="close-btn" onclick="abrir()">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <div class="div-form">
                    <div id="form_alterasenha" class="form">
                        <label> Endereço inicial:</label>
                        <input type="number" name="senha_alt" id="senha_alt" value="0" required>
                        <label> Endereço final:</label>
                        <input type="number" name="senha_conf" id="senha_conf" value="0" required>
                        <button name="AlteraSenha" id="btn-alterasenha">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="img-voltar">
            <a href="index.php">
                <img src="../images/216446_arrow_left_icon.png" />
            </a>
        </div>
        <div class="screen">
            <div class="margin-top35" style="width: 80%;">
                <button type="submit" id="aplicar" name="aplicar" class="btn btn-primary btn-form">Separar a nota inteira</button><br><br>
                <button type="submit" id="aplicar-sem-fila" name="aplicar-sem-fila" class="btn btn-primary btn-form">Pegar endereços específicos</button><br><br>
                <button type="submit" id="liberartodos" name="liberartodos" class="btn btn-danger ">Liberar todos produtos atribuídos a usuários</button><br><br>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/9c65c9f9d0.js" crossorigin="anonymous"></script>

</html>