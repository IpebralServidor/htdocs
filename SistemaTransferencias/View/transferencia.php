<?php
include "../conexaophp.php";
require_once '../App/auth.php';

$nunota = $_GET['nunota'];
$codusu = $_SESSION['idUsuario'];

$tsqlStatus = "SELECT [sankhya].[AD_FN_RETORNA_STATUS_NOTA]($nunota, $codusu)";
$stmtStatus = sqlsrv_query( $conn, $tsqlStatus);
$rowStatus = sqlsrv_fetch_array( $stmtStatus, SQLSRV_FETCH_NUMERIC);
$varStatus = $rowStatus[0];

?>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer" >
    <link rel="stylesheet" href="../css/style.css?v=<? time() ?>">
    <title>Document</title>
</head>
<body id="body">

    <div id="loader" class="" style="display: none;">
        <img style=" width: 150px; margin-top: 5%;" src="../images/soccer-ball-joypixels.gif">
    </div>

    <?php include '../Components/popUp.php' ?>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="display: none"></button>

    <div class="alert alert-warning alert-dismissible fade d-none show m-3" role="alert" id="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong id="alertText"></strong>
    </div>

    <div class="page">
        <header>
            <div class="timer">
                <span class="timer-color" id="timer-color">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;
                <div class="fw-bold" id="timer"> 00:00:00 </div>&nbsp;&nbsp;
                <div class="div-playPause">
                    <i id="botaoTimer" class="fa-solid fa-pause" data-id="<?php echo $_GET['nunota'] ?>"></i>
                </div>
            </div>

            <div class="tipoNota fw-bold">
                <span>Nº nota: <?php echo $nunota ?></span>
            </div>
        </header>

        <main>
            <div class="header-body">
                <div style="width: 100%">
                    <div class="mb-1">
                        <label for="endereco" class="form-label">Endereço <span style="color: red">*</span> </label>
                        <input type="number" class="form-control" id="endereco">
                    </div>
                    <div class="mb-1">
                        <label for="endereco" class="form-label">Referência <span style="color: red">*</span></label>
                        <input type="text" class="form-control" id="referencia">
                    </div>
                    <div class="mb-1">
                        <label for="lote" class="form-label">Lote:</label>
                        <input type="text" class="form-control" id="lote">
                    </div>
                    <div  class="row">
                        <div class="mb-1 col-6">
                            <label for="endereco" class="form-label">Quantidade <span style="color: red">*</span></label>
                            <input type="number" class="form-control" id="quantidade">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="qtdMax" class="form-label">Qtd Máx Local <span style="color: red">*</span></label>
                            <input type="number" class="form-control" id="qtdMax">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mt-3 image d-flex justify-content-center" id="imagemproduto">
                            <?php
                            $tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000 ";
                            $stmt2 = sqlsrv_query( $conn, $tsql2);

                            if($stmt2){
                                $row_count = sqlsrv_num_rows( $stmt2 );

                                while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))
                                {
                                    echo '<img style="vertical-align: middle; margin: auto; max-width: 100%; max-height: 166px;" src="data:image/jpeg;base64,'.base64_encode($row2[0]).'"/>';
                                }
                            }
                            ?>
                        </div>

                        <div class="col-6 mt-3">
                            <div class="form-control" style="font-size: 10px !important;">
                                <div>
                                    <span class="fw-bold">Est. mín: </span><span id="estMin">0</span>
                                </div>
                                <div>
                                    <span class="fw-bold">Qtd. loc. padrão: </span><span id="qtdEstPad">0</span>
                                </div>
<!--                                <div>-->
<!--                                    <span class="fw-bold">Med. venda: </span><span id="medVend">0</span>-->
<!--                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="mt-5 w-100 d-flex justify-content-center align-items-center">
                <button id="inserirProdutoBtn" class="btn btn-primary w-75">Inserir Produto</button>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="../Controller/calcularTimer.js"></script>
    <script src="../Controller/botaoTimer.js"></script>
    <script src="../Controller/imagemProduto.js"></script>
    <script src="../Controller/inserirProduto.js"></script>
    <script src="../Controller/alterarMaxLocal.js"></script>
    <script src="../Controller/buscaInfoProduto.js"></script>
    <script>
        document.getElementById("body").onload = function() {

            calcularTempo(<?php echo $_GET['nunota']; ?>);

            let statusPausa = "<?php echo $varStatus; ?>"
            if(statusPausa == 'P'){
                pausarIniciarContagem('P', document.getElementById("botaoTimer").getAttribute('data-id'));
            }
        };
    </script>
</body>
</html>
