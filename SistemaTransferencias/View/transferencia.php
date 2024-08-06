<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

$nunota = $_GET['nunota'];
$codusu = $_SESSION['idUsuario'];

$tsqlCheckin = "EXEC [sankhya].[AD_STP_CHECKIN_PHP] $codusu, $nunota";
$stmtCheckin = sqlsrv_query($conn, $tsqlCheckin);

$tsqlStatus = "SELECT [sankhya].[AD_FN_RETORNA_STATUS_NOTA]($nunota, $codusu)";
$stmtStatus = sqlsrv_query($conn, $tsqlStatus);
$rowStatus = sqlsrv_fetch_array($stmtStatus, SQLSRV_FETCH_NUMERIC);
$varStatus = $rowStatus[0];

$tsqlStatusNota = "SELECT STATUSNOTA FROM TGFCAB WHERE NUNOTA = $nunota";
$stmtStatusNota = sqlsrv_query($conn, $tsqlStatusNota);
$rowStatusNota = sqlsrv_fetch_array($stmtStatusNota, SQLSRV_FETCH_NUMERIC);
$varStatusNota = $rowStatusNota[0];

$tsqlEnderecoReserva = "SELECT AD_PARAMETROS_REABAST FROM TGFCAB WHERE NUNOTA = $nunota";
$stmtEnderecoReserva = sqlsrv_query($conn, $tsqlEnderecoReserva);
$rowEnderecoReserva = sqlsrv_fetch_array($stmtEnderecoReserva, SQLSRV_FETCH_NUMERIC);
$adParametrosReabast = explode("_", $rowEnderecoReserva[0]);
$endereco = $adParametrosReabast[0];
if (isset($adParametrosReabast[1])) {
    $reserva = $adParametrosReabast[1];
} else {
    $reserva = '';
}


if ($varStatusNota == 'L') {
    header('Location: ../index.html');
}

?>

<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="../css/style.css?v=<? time() ?>">
    <title>Document</title>
</head>

<body id="body">

    <div id="loader" class="" style="display: none;">
        <img style=" width: 150px; margin-top: 5%;" src="../images/soccer-ball-joypixels.gif">
    </div>

    <?php include '../Components/popUp.php' ?>
    <?php include '../Components/confirmarNota.php' ?>
    <?php include '../Components/confirmarEndereco.php' ?>
    <?php include '../Components/confirmarReferencia.php' ?>

    <div class="alert alert-success fade show d-none" id="alertMessage">
        <div class="d-flex align-items-start gap-3">
            <i class="close fa-solid fa-xmark" id="closeIcon"></i>
            <strong id="msgAlert"></strong>
        </div>
    </div>

    <div class="modal fade" id="deletaLocalVazio" tabindex="-1" role="dialog" aria-labelledby="deletaLocalVazio" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body fw-bold">
                    Deseja excluir da lista de locais vazios?
                </div>
                <div class="modal-footer flex-nowrap">
                    <button type="button" class="btn btn-primary btnAlterarMaxLocal fw-bold" id="btnDeletaLocal" data-dismiss="modal" onclick="deletaLocal(this)">Sim</button>
                    <button type="button" class="btn btn-secondary closePopUp fw-bold" id="fechaModalDeletaLocal" data-dismiss="modal">Não</button>
                </div>
            </div>
        </div>
    </div>

    <div class="collapse" id="tableCollapse">
        <div class="background">
            <div class="switchBox">
                <div class="tabSwitch">
                    <input type="checkbox" class="checkbox" id="chkInp" onchange="alteraTable()">

                    <label for="chkInp" class="label">
                        <div class="ball" id="ball"></div>
                    </label>
                </div>
                <div class="titleBox">
                    <h6 id="titleBoxH6"></h6>
                </div>
            </div>
        </div>
        <div class="card card-body">
            <table class="table tableProdutos" id="tabelaSwitch">

            </table>
        </div>
    </div>

    <div class="page">
        <header>
            <div id="setaDownDiv" class="setaDown fw-bold" data-toggle="collapse" data-target="#tableCollapse" aria-expanded="false" aria-controls="tableCollapse">
                <span>
                    <i id="setaDown" class="fa-solid fa-caret-down"></i>
                </span>
            </div>

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
                        <label for="endereco" class="form-label">Referência <span style="color: red">*</span></label>
                        <input type="text" class="form-control" id="referencia" style="color: #86B7FE !important;">
                    </div>
                    <div class="mb-1">
                        <label for="endereco" class="form-label">Endereço <span style="color: red">*</span> </label>
                        <input type="number" class="form-control" id="endereco" style="color: #86B7FE !important;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="checkboxChange(this)" value="" id="enderecoReservaCheckbox" data-toggle="modal" data-target="" tabindex="-1">
                            <label class="form-check-label" for="enderecoReservaCheckbox">
                                Usar endereço de reserva?
                            </label>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label for="lote" class="form-label">Lote:</label>
                        <input type="text" class="form-control" id="lote" disabled value="" style="color: #86B7FE !important;">
                    </div>
                    <div class="row">
                        <div class="mb-1 col-6">
                            <label for="endereco" class="form-label">Quantidade <span style="color: red">*</span></label>
                            <input type="number" class="form-control" id="quantidade" style="color: #86B7FE !important;">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="qtdMax" class="form-label">Qtd Máx Local <span style="color: red">*</span></label>
                            <input type="number" class="form-control" id="qtdMax" style="color: #86B7FE !important;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mt-3 image d-flex justify-content-center" id="imagemproduto">
                            <?php
                            $tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000 ";
                            $stmt2 = sqlsrv_query($conn, $tsql2);

                            if ($stmt2) {
                                $row_count = sqlsrv_num_rows($stmt2);

                                while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC)) {
                                    echo '<img style="vertical-align: middle; margin: auto; max-width: 100%; max-height: 166px;" src="data:image/jpeg;base64,' . base64_encode($row2[0]) . '"/>';
                                }
                            }
                            ?>
                        </div>

                        <div class="col-6 mt-3">
                            <div class="form-control" style="font-size: 10px !important;">
                                <div>
                                    <span class="fw-bold">Qtd.local retirada : </span><span id="locRet">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="mt-5 w-100 d-flex justify-content-center align-items-center">
                <button id="inserirProdutoBtn" class="btn btn-primary w-75 fw-bold">Inserir Produto</button>
            </div>
            <div class="mt-2 w-100 d-flex justify-content-center align-items-center">
                <button data-toggle="modal" data-target="#modalConfirmaNota" id="inserirProdutoBtn" class="btn btn-primary w-75 fw-bold" style="background-color: red !important; border-color: red !important;">Confirmar nota</button>
            </div>

            <div class="modal fade" id="enderecoReservaModal" tabindex="-1" role="dialog" aria-labelledby="enderecoReservaModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="p-3">
                            <div class="modal-body fw-bold">
                                Digite o local de reserva da mercadoria: <span style="color: red">*</span>
                            </div>
                            <div class="mb-1">
                                <input type="text" class="form-control" style="color: #86B7FE !important;" id="enderecoReservaInput">
                            </div>
                            <div class="mt-3">
                                <button id="atualizaEnderecoReserva" onclick="atualizaEnderecoReserva();" type="button" class="btn btn-primary fw-bold w-100" data-dismiss="modal">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="../Controller/calcularTimer.js"></script>
    <script src="../Controller/botaoTimer.js"></script>
    <script src="../Controller/imagemProduto.js"></script>
    <script src="../Controller/inserirProduto.js"></script>
    <script src="../Controller/alterarMaxLocal.js"></script>
    <script src="../Controller/habilitaLote.js"></script>
    <script src="../Controller/buscaInfoProduto.js"></script>
    <script src="../Controller/onLoadBody.js"></script>
    <script src="../Controller/confirmarNota.js"></script>
    <script src="../Controller/confirmarEndereco.js"></script>
    <script src="../Controller/confirmarReferencia.js"></script>
    <script>
        document.getElementById("body").onload = function() {

            calcularTempo(<?php echo $_GET['nunota']; ?>);

            let statusPausa = "<?php echo $varStatus; ?>"
            if (statusPausa == 'P') {
                pausarIniciarContagem('P', document.getElementById("botaoTimer").getAttribute('data-id'));
            }
        };

        function checkboxChange(checkbox) {
            if (checkbox.checked == true) {
                if ('<?php echo $reserva ?>' === '') {
                    $('#enderecoReservaModal').modal('show');
                    document.getElementById("enderecoReservaCheckbox").checked = false;
                } else {
                    document.getElementById('endereco').value = '<?php echo $reserva; ?>';
                    document.getElementById('endereco').disabled = true;
                }
            } else {
                document.getElementById('endereco').disabled = false;
                document.getElementById('endereco').value = '';
                document.getElementById('endereco').placeholder = '';
                $('#enderecoReservaModal').modal('hide');
            }
        };

        function atualizaEnderecoReserva() {
            let nunota = <?php echo $nunota; ?>;
            let endereco = <?php echo $endereco; ?>;
            let reserva = document.getElementById("enderecoReservaInput").value;
            if (reserva != '') {
                $.ajax({
                    type: 'POST',
                    dataType: 'html',
                    url: '../Model/salvaEnderecoReserva.php',

                    data: {
                        nunota: nunota,
                        endereco: endereco,
                        reserva: reserva
                    },
                    success: function(msg) {
                        if (msg == 'OK') {
                            location.reload();
                        } else {
                            alert(msg);
                        }
                    }
                });
            } else {
                alert("Digite um valor.");
            }
        };
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</body>

</html>