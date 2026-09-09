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
    <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="stylesheet" href="../css/style.css?v=<? echo time(); ?>">
    <link rel="stylesheet" href="../../../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../../../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../../node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <title>Document</title>
</head>

<body id="body">
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="p-3">
                    <div class="modal-body fw-bold">
                        Editar valor máximo: <span style="color: red">*</span><span id="prodDelete"></span>
                    </div>
                    <div class="mb-1">
                        <input type="number" class="form-control" id="novoMax" step="0.01" value="">
                    </div>
                    <div class="mt-3">
                        <button id="atualizaValorBtn" onclick='atualizaValorMaximo();' type="button" class="btn btn-primary fw-bold w-100" style="background-color: #3a6070 !important; border-color: #3a6070 !important" data-bs-dismiss="modal">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="emailFoto"></div>
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
                    <button type="button" class="btn btn-primary btnAlterarMaxLocal fw-bold" id="btnDeletaLocal" data-bs-dismiss="modal" onclick="deletaLocal(this)">Sim</button>
                    <button type="button" class="btn btn-secondary closePopUp fw-bold" id="fechaModalDeletaLocal" data-bs-dismiss="modal">Não</button>
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
            <div id="setaDownDiv" class="setaDown fw-bold" data-bs-toggle="collapse" data-bs-target="#tableCollapse" aria-expanded="false" aria-controls="tableCollapse">
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
                <span>End. retirada: <?php echo $endereco ?></span>
            </div>
        </header>

        <main>
            <div class="header-body">
                <div style="width: 100%">
                    <div class="mb-1">
                        <label for="endereco" class="form-label">Referência/Codbarra <span style="color: red">*</span></label>
                        <input type="text" class="form-control" id="referencia" style="color: #86B7FE !important;">
                    </div>
                    <div class="mb-1">
                        <label for="endereco" class="form-label">Endereço <span style="color: red">*</span> </label>
                        <input type="number" class="form-control" id="endereco" style="color: #86B7FE !important;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onchange="checkboxChange(this)" value="" id="enderecoReservaCheckbox" data-bs-toggle="modal" data-bs-target="" tabindex="-1">
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
                            <label for="qtdMax" class="form-label" style="width: auto">Qtd Máx Local <span style="color: red">*</span></label>
                            <span id='editMaxBtn' data-bs-toggle='modal' data-bs-target='#editModal' onclick='document.getElementById("novoMax").value = document.getElementById("qtdMax").value;'>
                                <i class='fa-solid fa-pen' style='color: #d80e0e;'></i>
                            </span>
                            <input type="number" class="form-control" id="qtdMax" style="color: #86B7FE !important;" disabled>
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
                                    <span class="fw-bold">Descrição : </span><span id="descricao"></span>
                                    <br>
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
                <button data-bs-toggle="modal" data-bs-target="#modalConfirmaNota" id="inserirProdutoBtn" class="btn btn-primary w-75 fw-bold" style="background-color: red !important; border-color: red !important;">Confirmar nota</button>
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
                                <button id="atualizaEnderecoReserva" onclick="atualizaEnderecoReserva();" type="button" class="btn btn-primary fw-bold w-100" data-bs-dismiss="modal">Confirmar</button>
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
            $('input').on('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Impede envio do form
        
                    // Pega todos os inputs visíveis e habilitados
                    const inputs = $('input:visible:enabled');
                    const index = inputs.index(this);
                    
                    if (index > -1 && index + 1 < inputs.length) {
                        inputs.eq(index + 1).focus();
                    } else {
                        // Último input: perde o foco
                        $(this).blur();
                    }
                }
            });
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

        function atualizaValorMaximo() {
            const novoMax = document.getElementById("novoMax").value;
            document.getElementById("qtdMax").value = novoMax;
            const inputReferencia = document.getElementById("referencia").value;

            const urlParams = new URLSearchParams(window.location.search);
            const nunota = urlParams.get("nunota");

            const endereco = document.getElementById("endereco");
            if (inputReferencia == '') {
                alert('IPB: Favor bipar um item.')
                document.getElementById("qtdMax").value = '';
            } else if (endereco.placeholder != '') {
                $.ajax({
                    type: 'POST',
                    dataType: 'html',
                    url: '../Model/atualizaValorMaximo.php',
                    beforeSend: function() {},
                    data: {
                        referencia: inputReferencia,
                        nunota: nunota,
                        qtdneg: novoMax
                    },
                    success: function() {

                    }
                });
            }
        };
    </script>
    <script src="../../components/emailFoto/js/emailFoto.js"></script>
</body>

</body>

</html>