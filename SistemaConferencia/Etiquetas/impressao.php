<?php
ob_start();
session_start();

include 'WebClientPrint.php';

use Neodynamic\SDK\Web\WebClientPrint;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/main.css" rel='stylesheet' type='text/css' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
</head>

<body>
    <div>
        <div id="loader" style="display: none;">
            <img style=" width: 150px; margin-top: 5%;" src="../images/soccer-ball-joypixels.gif">
        </div>
        <div class="img-voltar">
            <a href="../View/listaconferencia.php">
                <img src="../images/216446_arrow_left_icon.png" />
            </a>
        </div>
        <div class="screen">
            <div class="margin-top35" style="width: 80%;">
                <button type="submit" id="gerarEtiqueta" onclick="impressao('etiqueta');" class="btn btn-primary btn-form">Imprimir etiqueta</button><br><br>
                <button type="submit" id="gerarVale" onclick="impressao('vale');" class="btn btn-primary btn-form">Imprimir vale</button><br><br>
            </div>
        </div>
    </div>

    <script>
        const impressao = (tipoImpressao) => {
            const urlParams = new URLSearchParams(window.location.search);
            const numeroNota = urlParams.get("nunota");
            const gif = document.getElementById("loader");
            let file;
            if (tipoImpressao === 'etiqueta') {
                file = 'Cliente volume nota novo';
            } else if (tipoImpressao === 'vale') {
                file = 'Vale_novo';
            }
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: './compileJasper.php',
                beforeSend: function() {
                    gif.style.display = "block"
                    gif.classList.add("loader")
                },
                complete: function() {
                    gif.style.display = "none"
                    gif.classList.remove("loader")
                },
                data: {
                    nunota: numeroNota,
                    arquivo: file,
                    funcao: 'compileJasper'
                },
                success: function(msg) {
                    console.log(msg);
                    jsWebClientPrint.print('printerName=' + tipoImpressao + '&filePath=' + './nunotas/' + numeroNota + '/' + file + '.pdf');
                    $.ajax({
                        type: 'POST',
                        dataType: 'html',
                        url: './compileJasper.php',
                        data: {
                            funcao: 'fechaJanelaWcpp'
                        }
                    });
                }
            });
        }
    </script>

    <script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-2.2.0.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/bootstrap/3.3.6/bootstrap.min.js"></script>
</body>


</html>

<?php

//Get Absolute URL of this page
$currentAbsoluteURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
$currentAbsoluteURL .= $_SERVER["SERVER_NAME"];
if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
    $currentAbsoluteURL .= ":" . $_SERVER["SERVER_PORT"];
}
$currentAbsoluteURL .= $_SERVER["REQUEST_URI"];

//WebClientPrinController.php is at the same page level as WebClientPrint.php
$webClientPrintControllerAbsoluteURL = substr($currentAbsoluteURL, 0, strrpos($currentAbsoluteURL, '/')) . '/WebClientPrintController.php';

//DemoPrintFileController.php is at the same page level as WebClientPrint.php
$demoPrintFileControllerAbsoluteURL = substr($currentAbsoluteURL, 0, strrpos($currentAbsoluteURL, '/')) . '/DemoPrintFileController.php';

//Specify the ABSOLUTE URL to the WebClientPrintController.php and to the file that will create the ClientPrintJob object
echo WebClientPrint::createScript($webClientPrintControllerAbsoluteURL, $demoPrintFileControllerAbsoluteURL, session_id());
?>