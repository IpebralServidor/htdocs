<?php 
    include "../conexaophp.php";
    require_once '../App/auth.php';

    $corTipoNota = '';
    
    $enderecoInit = $_SESSION['enderecoInit'];
    $enderecoFim = $_SESSION['enderecoFim'];

    echo $_SESSION['enderecoInit'];
    echo " / " .$_SESSION['enderecoFim'];

    $nunota2 = $_REQUEST["nunota"];
    $codusu = $_SESSION["idUsuario"];

    $tsqlTipoNota = "SELECT [sankhya].[AD_FN_TIPO_NOTA_REABASTECIMENTO] ($nunota2)";
    $stmtTipoNota = sqlsrv_query( $conn, $tsqlTipoNota);
    $rowTipoNota = sqlsrv_fetch_array( $stmtTipoNota, SQLSRV_FETCH_NUMERIC);
    $_SESSION['tipoNota'] = $rowTipoNota[0];
    $tipoNota = $rowTipoNota[0];

    if($tipoNota == 'A'){
        $enderecoInit = 0;
        $enderecoFim = 0;
    }

    $tsqlNotaVinculo = "SELECT [sankhya].[AD_FN_VINCULO_NOTA_REABASTECIMENTO] ($nunota2)";
    $stmtNotaVinculo = sqlsrv_query( $conn, $tsqlNotaVinculo);
    $rowNotaVinculo = sqlsrv_fetch_array( $stmtNotaVinculo, SQLSRV_FETCH_NUMERIC);

    $tsql = "SELECT [sankhya].[AD_FNT_PROXIMO_PRODUTO_SEM_USUARIO_REABASTECIMENTO] ($nunota2, $enderecoInit, $enderecoFim)";
    $stmt = sqlsrv_query( $conn, $tsql);
    $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);

    $tsqlPdtAtual = "SELECT [sankhya].[AD_FN_PRODUTO_ATUAL_REABASTECIMENTO] ($nunota2, $codusu)";
    $stmtPdtAtual = sqlsrv_query( $conn, $tsqlPdtAtual);
    $rowPdtAtual = sqlsrv_fetch_array( $stmtPdtAtual, SQLSRV_FETCH_NUMERIC);

    $tsqlPdSemFiltro = "SELECT AD_CODUSUBIP FROM [sankhya].[AD_FNT_PROXIMO_PRODUTO_REABASTECIMENTO_SEM_FILTRO] ($nunota2)";
    $stmtPdSemFiltro = sqlsrv_query( $conn, $tsqlPdSemFiltro);
    $rowPdSemFiltro = sqlsrv_fetch_array( $stmtPdSemFiltro, SQLSRV_FETCH_NUMERIC);

    if($rowPdtAtual[0] == 0){
        if(empty($rowPdSemFiltro[0]) && $row[0] != 0){
            $tsqlUpdate = "UPDATE TGFITE SET AD_CODUSUBIP = $codusu WHERE NUNOTA = ($nunota2) AND ABS(SEQUENCIA) = $row[0]";
            $stmtUpdate = sqlsrv_query( $conn, $tsqlUpdate);
        }else{
            if($row[0] === 0){
                header("Location: verificarprodutos.php?nunota=".$nunota2 );
            }
            else if(empty($row[0])){ 
                echo "<script>alert('Acabaram os seus produtos'); location = './' </script>";        
            }
        }
    }

    if(isset($_REQUEST["fila"])){
        $fila = $_REQUEST["fila"];
        $_SESSION["fila"] = $fila;
    }

    $tsqlStatus = "SELECT [sankhya].[AD_FN_RETORNA_STATUS_NOTA]($nunota2, $codusu)";
	$stmtStatus = sqlsrv_query( $conn, $tsqlStatus);
	$rowStatus = sqlsrv_fetch_array( $stmtStatus, SQLSRV_FETCH_NUMERIC);
    $varStatus = $rowStatus[0];

    $tsqlTimer = "SELECT (SUM(DATEDIFF(sECOND, ISNULL(DTFIM,gETDATE()),DTINIC)) *-1) FROM AD_TGFAPONTAMENTOATIVIDADE WHERE NUNOTA = $nunota2";
	$stmtTimer = sqlsrv_query( $conn, $tsqlTimer);
	$rowTimer = sqlsrv_fetch_array( $stmtTimer, SQLSRV_FETCH_NUMERIC);
	$_SESSION['time']= $rowTimer[0];
    
    $tsqlNota = "SELECT OBSERVACAO FROM TGFCAB WHERE NUNOTA = $nunota2";
    $stmtNota = sqlsrv_query( $conn, $tsqlNota);
    $rowNota = sqlsrv_fetch_array( $stmtNota, SQLSRV_FETCH_ASSOC);

    $tsql2 = "SELECT * FROM [sankhya].[AD_FNT_PRODUTO_SEPARADO_REABASTECIMENTO] ($nunota2) ORDER BY CODLOCALORIG DESC, SEQUENCIA DESC"; 
    $stmt2 = sqlsrv_query( $conn, $tsql2);  

    $tsqlEhTransf = "   SELECT AD_PEDIDOECOMMERCE 
                        FROM TGFCAB 
                        WHERE NUNOTA = $nunota2";
    $stmtEhTransf = sqlsrv_query( $conn, $tsqlEhTransf);
    $rowEhTransf = sqlsrv_fetch_array( $stmtEhTransf, SQLSRV_FETCH_NUMERIC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema reabastecimento</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/main.css?v=<?= time() ?>">
    <link href="css/main.css?v=<?= time() ?>" rel='stylesheet' type='text/css' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer" >
</head>
<body class="body" 
    <?php if($fila == "S"){ ?> 
        <?php if($rowStatus[0] == "P"){ ?>
            onload="retornainfoprodutos(<?php echo $nunota2; ?>, 'N'), 
            iniciarpausa('P', <?php echo $nunota2; ?>),
            produtos(<?php echo $nunota2; ?>),
            retornaMovimentacoes()"
        <?php } else{ ?>
            onload="retornainfoprodutos(<?php echo $nunota2; ?>, 'N'),
            produtos(<?php echo $nunota2; ?>),
            retornaMovimentacoes()" 
        <?php } ?> 
    <?php } else {?>
        onload="produtos(<?php echo $nunota2; ?>), endereco()"
    <?php } ?>>

    <div id="loader" style="display: none;">
        <img style=" width: 150px; margin-top: 5%;" src="images/soccer-ball-joypixels.gif">
    </div>

    <!-- Modal para confirmar endereços-->

    <div class="popup" id="popConfEnd">
		<div class="overlay"></div>
			<div class="content">
				<div style="width: 100%;">
					<div class="close-btn" onclick="abrir()">
						<i class="fa-solid fa-xmark"></i>	
						<!-- <i class="fa-solid fa-circle-xmark"></i> -->
					</div>
					
					<div class="div-form">
						<div id="form_alterasenha" class="form">
							<label> Digite o endereço novamente:</label>
							<input type="number" name="end_conf" id="end_conf" required>
							
							<button name="ConfEnd" id="btn_confend">Confirmar</button>
                        </div>
					</div>
				</div>
				
			</div>
		</div>
	</div>

    <!-- Modal para confirmar referencia-->

    <div class="popup" id="popConfRef">
		<div class="overlay"></div>
			<div class="content">
				<div style="width: 100%;">
					<div class="close-btn" onclick="abrirRef(), clearRef()">
						<i class="fa-solid fa-xmark"></i>	
					</div>
					
					<div class="div-form">
						<div id="form_alterasenha" class="form">
							<label> Digite a referência novamente:</label>
							<input type="number" name="end_conf" id="ref_conf" required>
							
							<button name="ConfRef" id="btn_confref">Confirmar</button>
                        </div>
					</div>
				</div>
				
			</div>
		</div>
	</div>

    <!-- Modal para observações-->

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Observação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo 'Observação da nota: ' .utf8_encode($rowNota['OBSERVACAO']) ?><br><br>
                    <?php echo 'Observação do item: '?> <span id="observacao"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para movimentações da nota após a sua criação-->

    <div class="modal fade" id="movimentacoesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Movimentações</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class='movTable'>
                        <thead>
                            <tr>
                                <th>Nota</th>
                                <th>TOP</th>
                                <th>Emp</th>
                                <th>Data</th>
                                <th>Qtd</th>
                            </tr>
                        </thead>
                        <tbody id="movId">
                               
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ocorrência-->

    <div class="modal fade" id="ocorrenciaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span id="mensagemModal"></span></h5>
                    <button type="button" id="close" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input class="input-ocorrencia" type="radio" id="qtd_mais" name="fav_language" value="Quantidade informada a mais">
                    <label for="qtd_mais">Quantidade a mais no local</label><br>
                    
                    <input class="input-ocorrencia" type="radio" id="qtd_menos" name="fav_language" value="Quantidade informada a menos">
                    <label for="qtd_menos">Quantidade a menos no local</label><br>

                    <input class="input-ocorrencia" type="radio" id="nao_encontrado" name="fav_language" value="Produto nao foi encontrado">
                    <label for="nao_encontrado">Produto não foi encontrado</label><br>

                    <input class="input-ocorrencia" type="radio" id="agp_divergente" name="fav_language" value="Agrupamento divergente">
                    <label for="agp_divergente">Agrupamento divergente</label><br>

                    <label class="input-ocorrencia" for="outros">Outros: </label>
                    <input type="text" id="outros" name="outros" value="">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnAplicarOcorrencia">Aplicar</button>
                    <button type="button" class="btn btn-primary" id="btnAplicarOutroLocal" style="display: none;">Aplicar outro</button>
                    <button type="button" class="btn btn-primary" id="btnAplicarProximo" style="display: none;">Aplicar proximo</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para entregar tudo -->
    <div class="modal fade" id="entregaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if($rowEhTransf[0]  == 'TRANSFPRODENTRADA') {?>
                    <p>Tem certeza que deseja guardar todas as mercadorias?</p>
                <?php } else {?>
                    <p>Tem certeza que deseja separar todas as mercadorias?</p>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnEntregarTudo">Sim</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="buscarUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Buscar usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex flex-wrap">
                    <label for="qtd">Usuário: </label>
                    <input type="number" id="usu" name="usu" value="" style="width: 50px; margin-right: 10px;">
                    <button class="buscar-usuario">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                    <input type="number" id="nomeusu" name="nomeusu" value="" style="width: 140px; margin-left: 10px;" disabled>
                </div>
                <div class="modal-footer">
                    <button type="button" class="botao-entregar btn btn-primary" id="btnEntregar">Entregar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarQuantidadeMaxLocPad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar quantidade máxima local padrão</h5>
                    <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="qtdMaxLocPad">Quantidade: </label>
                    <input type="number" id="qtdMaxLocPad" name="qtdMaxLocPad" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnAlterarQtdMaxLocPad">Editar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg">
        <div class="collapse" id="navbarToggleExternalContent">
 
            
                <div class="background">
                    <div class="switchBox">
                        <div class="tabSwitch">
                            <input type="checkbox" class="checkbox" id="chkInp" onchange="produtos(<?php echo $nunota2; ?>)">

                            <label for="chkInp" class="label">
                                <div class="ball" id="ball"></div>
                            </label>
                        </div>

                        <div class="titleBox">
                            <h6 id="titleBoxH6">Produtos separados</h6>
                        </div>
                    </div>
                </div>
            

            <div class="table d-flex justify-content-center">
                
                <table >
                    <thead>
                        <tr> 
                            <th>Seq.</th>
                            <th>Ref.</th>
                            <th>Local</th>
                            <th>Qtde</th>
                            <?php if($tipoNota == 'S'){ ?>
                                <th></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody class="movTable" id="prodId">
                        
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="d-flex justify-content-between" style="background-color: #3a6070 !important;">
            <nav class="bg navbar">
                <a id="navbar-toggler" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-square" viewBox="0 0 16 16">
                        <path d="M3.626 6.832A.5.5 0 0 1 4 6h8a.5.5 0 0 1 .374.832l-4 4.5a.5.5 0 0 1-.748 0l-4-4.5z"/>
                        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2z"/>
                    </svg>
                </a>
            </nav>

            <?php 
                if($tipoNota == "S"){
                    $tituloNota = "Separação";
                    $corTipoNota = "#9c95ff;";
                }else{
                    $tituloNota = "Abastecimento";
                    $corTipoNota = "#ff9595;";
                }
            ?>

            <div class="timer">
                <span class="timer-color" id="timer-color">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;
                <div id="timer"> 00:00:00 </div>&nbsp;&nbsp;
                <button id="startButton" style="display: none;">Iniciar</button>
                <button id="pauseButton" class="pause btnPendencia btnOutroLocal">Pausar</button>
                <button id="resumeButton" class="play btnPendencia btnOutroLocal" style="display: none;">Continuar</button>
            </div>

            <div class="d-flex justify-content-end">
                <button class="statusReabastecimento" style=" background-color: <?php echo $corTipoNota; ?> !important;">
                    <?php echo $tituloNota; ?>
                </button>
            </div>
            
        </div>
    </div>

    <div class="container">
        <div class="notas-vinculadas">
            <div>
                <span>Nota atual: <?php echo $nunota2?></span> 
            </div>
            <div>
                <span>Nota vinculada: <?php echo $rowNotaVinculo[0]?></span> 
            </div>
        </div>
        <div class="header-body">

            <div class="header-body-left">
                

                <div class="d-flex justify-content-center align-items-center">
                    <div class="input-h6">
                        <h6>Endereço:</h6> 
                    </div>
                    <input type="number" name="endereco" id="endereco" class="form-control" placeholder="" oninput="iniciarMedicao()" onblur="finalizarMedicao()"> 
                    
                </div>

                <div class="d-flex justify-content-center align-items-center">
                    <div class="input-h6">
                        <h6>Referência:</h6> 
                    </div>
                    <input type="text" name="referencia" id="referencia" class="form-control" placeholder="" oninput="iniciarMedicao2()" onblur="finalizarMedicao2()"> 
                </div>
            
                <div class="d-flex justify-content-center align-items-center">
                    <div class="input-h6">
                        <h6>Quantidade:</h6>
                    </div>
                    <input type="number" name="qtdneg" id="qtdneg" class="form-control" required placeholder="">
                </div> 

                <div class="infos">
                    <div class="informacoes">
                        <h6>Agp. min: <span id="agrupmin"><span></h6>     
                        
                        <div class="d-flex justify-content-start">
                            <h6 id="qtdLocal">Qtd Local: <span id="qtdlocal"></span>&nbsp / &nbsp</h6>
                            <h6 id="informacaoAtualizada">0</h6> 
                            <!-- <?php if($tipoNota == 'S') {?> -->
                                <span class="obsMovimentacoes movimentacoesFlag" id="obsMovimentacoes" data-toggle="modal" data-target="#movimentacoesModal" style="display: block;" onclick="retornaMovimentacoes()"></span>
                            <!-- <?php }?> -->
                        </div>

                        <h6>Max. loc. padrão: <span id="maxlocalpadrao"></span>
                            <?php if ($tipoNota == 'A') { ?>
                                <button class="btnPendencia" data-toggle="modal" data-target="#editarQuantidadeMaxLocPad" style="border-radius: 13%">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                    </svg>
                                </button>
                            <?php } ?>
                        </h6>

                        <h6>Est. loc. padrão: <span id="estlocalpadrao"></span></h6>
                        <h6>Med. venda: <span id="mediavenda"></span></h6>
                    </div>

                    <div class="fornecedores">
                        <h6><span id="fornecedores"></span></h6>
                    </div>
                </div>

                <input type="text" id="sequencia" value="" style="display: none;">
                <input type="text" id="qtdlocalInput" value="" style="display: none;">
                <input type="text" id="codprod" value="" style="display: none;">
                <input type="text" id="observacao" value="" style="display: none;">
                <input type="text" id="codemp" value="" style="display: none;">
                <input type="text" id="enderecoMaxLoc" value="" style="display: none;">
               
            </div>

        </div>

        <div class="image d-flex justify-content-center" id="imagemproduto">
            <?php
               // $referencia = $rowTransferencia['REFERENCIA'];

                //if($referencia!=''){
                  //  $tsql2 = "SELECT [sankhya].[AD_FN_IMAGEM_PRODUTO_PHP] ('$referencia')";
                //} else {
                    $tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000 ";
                //}

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

        <div class="btn-proximo-abast">
            <button id="proximo" name="proximo" class="btn btn-primary btn-form">Próximo</button>
        </div>  
        
    </div>
    <footer class="footer d-flex justify-content-center">
        <button class="btnWidth btnPendencia " data-toggle="modal" data-target="#exampleModal" onclick="exibirObservacao()">
            Observação
        </button>
        <?php if($tipoNota == 'S'){ ?>
            <button id="btnOutroLocal" class="btnWidth btnPendencia btnOutroLocal" data-toggle="modal" data-target="#ocorrenciaModal">
                Outro local
            </button>
        <?php } ?>
        <button id="btnOcorrencia" class="btnWidth btnPendencia " data-toggle="modal" data-target="#ocorrenciaModal">
            Ocorrência
        </button>
        <button id="btnProximo" class="btnWidth btnPendencia " data-toggle="modal" data-target="#ocorrenciaModal" style="display: none;">
            Proximo
        </button>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        var tempoInicial;
        var tempoFinal;

        function iniciarMedicao() {
            tempoInicial = new Date();
        }

        function finalizarMedicao() {
            tempoFinal = new Date();
            var tempoDecorrido = tempoFinal - tempoInicial;

            if(tempoDecorrido > 250){
                abrirEndereco()
                document.getElementById('endereco').value = null
            }
        }

    </script>
    <script>
        var tempoInicial;
        var tempoFinal;

        function iniciarMedicao2() {
            tempoInicial = new Date();
        }

        function finalizarMedicao2() {

            tempoFinal = new Date();
            var tempoDecorrido = tempoFinal - tempoInicial;

            if(tempoDecorrido > 250){
                abrirReferencia()
                document.getElementById('referencia').value = null
            }
        }

    </script>
    <script>

        function exibirObservacao(){

            var referencia = document.getElementById("referencia").value

            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'retornainfoproduto.php',//Indica a página que está sendo solicitada.
                async: false,
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: { referencia: referencia, nunota: <?php echo $nunota2 ?>, codusu: <?php echo $codusu ?>},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    var retorno = msg.split("/");

                    document.getElementById("observacao").textContent = retorno[9];
                }
            });
        }

        function clearEnd() {
			document.getElementById('endereco').value = null;
		}

        function clearRef() {
			document.getElementById('referencia').value = null;
		}

        function abrirEndereco() {
            var valEndereco = document.getElementById('endereco').value

            if(valEndereco){
                abrir()
            }
		}

        function abrirReferencia() {
            var valReferencia = document.getElementById('referencia').value

            if(valReferencia){
                abrirRef()
            }
		}

        function abrirRef() {
			document.getElementById('popConfRef').classList.toggle("active");
		}

        function abrir() {
			document.getElementById('popConfEnd').classList.toggle("active");
		}

        $('#btn_confend').click(function () {
            var enderecoPopUp = document.getElementById('end_conf').value
            document.getElementById('endereco').value = enderecoPopUp
            abrir()
        })

        $('#btn_confref').click(function () {
            var referenciaPopUp = document.getElementById('ref_conf').value
            document.getElementById('referencia').value = referenciaPopUp
            abrirRef()
        })

        var produtoseq

        $(document).ready(function() {
            $("#navbar-toggler").click(function() {
                $(this).toggleClass("rotated");
            });
        });

        $('#outros').click(function () {
            var radios = document.getElementsByName('fav_language');

            radios.forEach(function(radio) {
                radio.checked = false;
            });
        })

        function retornaMovimentacoes(){
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'movimentacoes.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                data: {nunota: '<?php echo $nunota2; ?>', sequencia: '<?php echo $row[0]; ?>'},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    var movimentacoes = msg.split('|');

                    if(msg == ''){
                        document.getElementById('obsMovimentacoes').style.display = "none";
                    }else{
                        // document.getElementById('notaMovimentacao').innerHTML = movimentacoes[0];
                        // document.getElementById('topMovimentacao').innerHTML = movimentacoes[1];
                        // document.getElementById('empMovimentacao').innerHTML = movimentacoes[2];
                        // document.getElementById('dataMovimentacao').innerHTML = movimentacoes[3];
                        // document.getElementById('qtdMovimentacao').innerHTML = movimentacoes[4];
                        document.getElementById('movId').innerHTML = movimentacoes[0];
                    }
                }
            });
        }        
        $('#btnProximo').click(function () {
                document.getElementById("mensagemModal").textContent = "Quantidade digitada diferente da solicitada.";
                document.getElementById("btnAplicarOutroLocal").style.display = "none";
                document.getElementById("btnAplicarProximo").style.display = "block";
                document.getElementById("btnAplicarOcorrencia").style.display = "none";
            });
        $('#btnOcorrencia').click(function () {
            document.getElementById("mensagemModal").textContent = "Selecione uma das opções abaixo.";
            document.getElementById("btnAplicarOutroLocal").style.display = "none";
            document.getElementById("btnAplicarProximo").style.display = "none";
            document.getElementById("btnAplicarOcorrencia").style.display = "block";
        });
        $('#btnOutroLocal').click(function () {
            document.getElementById("mensagemModal").textContent = "Qual motivo para buscar de outro local?";
            document.getElementById("btnAplicarOutroLocal").style.display = "block";
            document.getElementById("btnAplicarOcorrencia").style.display = "none";
            document.getElementById("btnAplicarProximo").style.display = "none";
        });
        $('#btnAplicarProximo').click(function () {

            var selectedOption = $("input[name='fav_language']:checked").val();
            var observacao = document.getElementById("outros").value;

            if(selectedOption == undefined){
                selectedOption = '';
            }

            var ocorrencia = selectedOption +' ' +observacao

            //registrarOcorrencia(<?php echo $nunota2; ?>, $("#sequencia").val(), $("#qtdneg").val());
            proximoProduto($("#qtdneg").val(), <?php echo $nunota2; ?>, <?php echo $codusu; ?>, $("#sequencia").val(), $("#referencia").val(), $("#endereco").val(), ocorrencia)
        });

        document.getElementById("timer-color").style.backgroundColor = "green";

        let tempInic = 0;        
        
        function calcularTempo() {
            $.ajax 
            ({
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'time.php',//Indica a página que está sendo solicitada.
                data: {codusu: <?php echo $codusu ?>, nunota: <?php echo $nunota2 ?>},//Dados para consulta
                success: function (msg)
                {
                    tempInic = msg 

                    let hh = Math.floor(tempInic / 3600);
                    let mm = Math.floor((tempInic % 3600) / 60);
                    let ss = tempInic % 60;

                    if(hh < 10){ hh = '0'+hh }
                    if(mm < 10){ mm = '0'+mm }
                    if(ss < 10){ ss = '0'+ss }

                    let time = (`${hh}: ${mm}: ${ss}`);
                    tempInic++; // Incrementar o tempo a cada execução

                    document.getElementById("timer").innerHTML = time;
                }
            });
        }

        setInterval(calcularTempo, 1000);

        document.getElementById("pauseButton").addEventListener("click", function() {
            document.getElementById("resumeButton").style.display = "block";
            document.getElementById("pauseButton").style.display = "none";
            document.getElementById("timer-color").style.backgroundColor = "yellow";
            iniciarpausa('A', <?php echo $nunota2; ?>)
        });

        document.getElementById("resumeButton").addEventListener("click", function() {
            document.getElementById("resumeButton").style.display = "none";
            document.getElementById("pauseButton").style.display = "block";
            document.getElementById("timer-color").style.backgroundColor = "green";
            iniciarpausa('P', <?php echo $nunota2; ?>)
        });

        var inputTexto = document.getElementById('qtdneg');
        var informacaoAtualizada = document.getElementById('informacaoAtualizada');
        var qtdLocal = document.getElementById('qtdlocalInput');

        // Adiciona um ouvinte de evento 'input' ao campo de entrada
        inputTexto.addEventListener('input', function() {
            var qtd = qtdLocal.value;
            // Obtém o valor atual do campo de entrada
            var texto = inputTexto.value;
            <?php if($tipoNota == 'S'){?>
                var num = qtd - texto;
            <?php } else {?>
                var num = parseFloat(qtd) + parseFloat(texto);
            <?php }?>
            
            // Atualiza a informação em tempo real
            informacaoAtualizada.textContent = ' ' +num.toFixed(2)
        });

        document.addEventListener('DOMContentLoaded', function() {
            var botoesAbrirPopUp = document.querySelectorAll(".botao-abastecer");
            var meuPopUp = document.getElementById("buscarUsuario");
            var botaoDentroDoPopUp = meuPopUp.querySelector("#btnEntregar");
           

            // Adicione um ouvinte de eventos para cada botão
            botoesAbrirPopUp.forEach(function(botao) {
                botao.addEventListener("click", function() {

                    // Obtém o valor do atributo data-id do botão clicado
                    var dataId = this.getAttribute('data-id');

                    // Define o valor em um atributo personalizado do botão dentro do pop-up
                    botaoDentroDoPopUp.setAttribute('data-id-pop-up', dataId);

                    // Abre o pop-up
                    meuPopUp.style.display = "block";
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var botaoEditar = document.querySelectorAll('.botao-entregar');
            var codUsu = document.getElementById('usu');

            botaoEditar.forEach(function(botao) {
                botao.addEventListener('click', function() {
                    var sequencia = botao.getAttribute('data-id-pop-up');
                    var codusu = codUsu.value;
                    
                    abastecerGondola(<?php echo $nunota2; ?>, sequencia, codusu);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var botaoBuscar = document.querySelectorAll('.buscar-usuario');
            var inputTexto = document.getElementById("usu");

            botaoBuscar.forEach(function(botao) {
                botao.addEventListener('click', function() {
                    var valorTexto = inputTexto.value;
                    
                    buscarUsuario(valorTexto);
                });
            });
        });

        function abrirObs(){
            document.getElementById('popupObservacao').style.display = 'block';
        }

        function proximoProduto(qtdneg, nunota, codusu, sequencia, referencia, endereco, ocorrencia)
        {
            // O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'proximoproduto.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: {qtdneg: qtdneg, nunota: nunota, codusu: codusu, sequencia: sequencia, referencia: referencia, endereco: endereco, ocorrencia: ocorrencia},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    if(msg == 'Concluido'){
                        location.reload();
                    }else{
                        alert(msg);
                    }
                    
                }
            });
        }
        $('#proximo').click(function () {

            var qtdDigitada = $("#qtdneg").val();
            var qtdRetornada = document.getElementById("qtdneg").placeholder;

            let endereco = document.getElementById("endereco").value

			if("<?php echo $rowEhTransf[0] ?>" == "TRANSF_CD5"){
				endereco = document.getElementById("endereco").val
			}

            if((qtdDigitada > qtdRetornada) && '<?php echo $rowEhTransf[0] ?>' != 'TRANSFAPP'){
                alert('Esta nota não é possível passar quantidade a mais!')
            }else if((qtdDigitada != qtdRetornada) && '<?php echo $tipoNota ?>' == 'S'){
                $('#btnProximo').click();
            }else{
                proximoProduto($("#qtdneg").val(), <?php echo $nunota2; ?>, <?php echo $codusu; ?>, $("#sequencia").val(), $("#referencia").val(), endereco,'')
            }
           
        });

        

        function registrarOcorrencia(nunota, sequencia, qtdneg)
		{
            
            var selectedOption = $("input[name='fav_language']:checked").val();
            var observacao = document.getElementById("outros").value;

            if(selectedOption == undefined){
                selectedOption = '';
            }

            var ocorrencia = selectedOption +' ' +observacao

            if(qtdneg == ''){
                qtdneg = 0;
            }

            $.ajax
				({
					type: 'POST',//Método que está sendo utilizado.
					dataType: 'html',//É o tipo de dado que a página vai retornar.
					url: 'registrarocorrencia.php',//Indica a página que está sendo solicitada.
					data: {nunota: nunota, sequencia: sequencia, ocorrencia: ocorrencia, qtdneg: qtdneg},//Dados para consulta
					//função que será executada quando a solicitação for finalizada.
					success: function (msg)
					{
						// if(msg == 'Concluido'){
                        //     alert('Ocorrência registrada com sucesso!');
                        // }
					}
				});

		}
		$('#btnAplicarOcorrencia').click(function () {

            let endereco = document.getElementById("endereco").value

			if("<?php echo $rowEhTransf[0] ?>" == "TRANSF_CD5"){
				endereco = document.getElementById("endereco").val
			}

            let referencia = document.getElementById("referencia").value;
            let qtdneg = document.getElementById("qtdneg").value;
            
            if(!endereco || !referencia || !qtdneg){
                alert('Preencha todos os campos antes de registrar uma ocorrência!');
            }else{
                registrarOcorrencia(<?php echo $nunota2; ?>, $("#sequencia").val(), $("#qtdneg").val());
                alert('Ocorrência inserida com sucesso!')
                $('#close').click();
            }
			
		});

        function iniciarpausa(status, nota)
		{
			//O método $.ajax(); é o responsável pela requisição
			$.ajax
				({
					//Configurações
					type: 'POST',//Método que está sendo utilizado.
					dataType: 'html',//É o tipo de dado que a página vai retornar.
					url: 'iniciarpausa.php',//Indica a página que está sendo solicitada.
					//função que vai ser executada assim que a requisição for enviada
					beforeSend: function () {
						$("#iniciarpausa").html("Carregando...");
					},
					data: {status: status, nota: nota},//Dados para consulta
					//função que será executada quando a solicitação for finalizada.
					success: function (msg)
					{
						// alert(msg);
					}
				});
		}
		$('#btnStatus').click(function () {
			var nunota = "<?php echo $nunota2; ?>"
			var status = "<?php echo $varStatus; ?>"
			iniciarpausa(status, nunota)
		});

        function buscarUsuario(codusu)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'buscarusuario.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: {codusu: codusu},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    var retorno = msg.split("|");

                    // alert(retorno[1]);

                    document.getElementById("nomeusu").placeholder = retorno[1];
                }
            });
        }
        $('#buscar-usuario').click(function () {
            buscarUsuario($("#codusuinput").val())
        });

        function alterarQuantidade(nunota, sequencia, quantidade)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'alterarquantidade.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: {nunota: nunota, sequencia: sequencia, quantidade: quantidade},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    if(msg == 'Concluido'){
                        location.reload();
                    }else{
                        alert(msg);
                    }                 
                }
            });
        }

        function abastecerGondola(nunota, sequencia, codusu)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'abastecergondola.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: {nunota: nunota, sequencia: sequencia, codusu: codusu},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    if(msg == 'Concluido'){
                        location.reload();
                    }else{
                        alert(msg);
                    }                 
                }
            });
        }

        function abastecerTudo(nunota)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'abastecertudo.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: {nunota: nunota},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    if(msg == 'Concluido'){
                        location.reload();
                    }else{
                        alert(msg);
                    }                 
                }
            });
        }

        $('#btnEntregarTudo').click(function () {
            abastecerTudo('<?php echo $nunota2; ?>')
        });

        $('#btnAplicarOutroLocal').click(function () {
            // registrarOcorrencia(<?php echo $nunota2; ?>, $("#sequencia").val(), $("#qtdneg").val());
            procurarOutroLocal($("#qtdneg").val(), <?php echo $nunota2; ?>, $("#sequencia").val(), $("#codprod").val(), '<?php echo $codusu; ?>')
        });

        function  deletarProduto(nunota, sequencia)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'deletarproduto.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: {nunota: nunota, sequencia: sequencia},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    if(msg == "success"){
                        location.reload();
                    }else{
                        alert(msg);
                    }
                }
            });
        }

        <?php if($fila == 'N') { ?>

            document.getElementById("qtdneg").addEventListener("focus", function() {
            
            retornainfoprodutos(<?php echo $nunota2; ?>, $("#referencia").val());
                    //document.getElementById("localorigem").textContent = "teste";
                },{ once: true });
            imagemproduto($("#referencia").val());
        
        <?php } ?>
        
        function endereco(){

            const endereco = document.getElementById("endereco")
            const ehTransf = "<?php echo $rowEhTransf[0] ?>"
            const tipoNota = "<?php echo $tipoNota[0] ?>"

            if(ehTransf == "TRANSF_CD5" && tipoNota == "S"){
                endereco.disabled = true
                endereco.val = "5069900"
                endereco.placeholder = "5069900"
            }
        }

        function retornainfoprodutos(nunota, referencia)
        {
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'retornainfoproduto.php',//Indica a página que está sendo solicitada.
                async: false,
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: { referencia: referencia, nunota: nunota, codusu: <?php echo $codusu ?>},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    var retorno = msg.split("/");

                    if(msg == ' '){
                        <?php if($tipoNota == 'A') { ?>
                            alert('Este item não foi separado!');
                        <?php } else {?>
                            alert('Ocorreu um erro favor contatar a T.I');
                        <?php }?>
                        
                    }else{

                        if(retorno[8] == 0){
                            window.location.href= "verificarprodutos.php?nunota="+<?php echo $nunota2 ?>;
                        }else{
                            <?php if($tipoNota == 'S') { ?>
                                if(retorno[4] == 0 || retorno[2] == 0){
                                    
                                document.getElementById("outros").value = 'Produto transferido';

                                procurarOutroLocal(0, <?php echo $nunota2; ?>, retorno[8], retorno[10], '<?php echo $codusu; ?>')

                                document.getElementById("proximo").style.display = "none";
                            }
                            <?php }?>
                        
                            document.getElementById("qtdneg").placeholder = retorno[2];
                            document.getElementById("endereco").placeholder = retorno[1];
                            document.getElementById("enderecoMaxLoc").value = retorno[1];
                            document.getElementById("referencia").placeholder = retorno[0];
                            document.getElementById("observacao").textContent = retorno[9];
                            document.getElementById("agrupmin").textContent = retorno[3];
                            document.getElementById("qtdlocal").textContent = retorno[4];
                            document.getElementById("fornecedores").textContent = retorno[11];
                            document.getElementById("maxlocalpadrao").textContent = retorno[5];
                            document.getElementById("estlocalpadrao").textContent = retorno[6];
                            document.getElementById("mediavenda").textContent = retorno[7];
                            document.getElementById("codemp").value = retorno[12];
                            document.getElementById("codprod").value = retorno[10];
                            document.getElementById("qtdlocalInput").value = retorno[4];
                            document.getElementById("sequencia").value = retorno[8];

                            produtoseq = retorno[8]
                            
                            imagemproduto(retorno[0]);
                        }
                    }
                }
            });
        }

        function produtos(nota)
		{
            var teste = document.getElementById('chkInp');

            if('<?php echo $tipoNota; ?>' == 'A'){
                
                if(teste.checked == true){
                    document.getElementById('titleBoxH6').textContent = 'Produtos não guardados'
                    teste = 'S'
                }else{
                    document.getElementById('titleBoxH6').textContent = 'Produtos guardados'
                    teste = 'N'
                }
            }else{
                if(teste.checked == true){
                    document.getElementById('titleBoxH6').textContent = 'Produtos não separados'
                    teste = 'S'
                }else{
                    document.getElementById('titleBoxH6').textContent = 'Produtos separados'
                    teste = 'N'
                } 
            }
            
                       
            //teste.checked
            //O método $.ajax(); é o responsável pela requisição
			$.ajax
				({
                    
					//Configurações
					type: 'POST',//Método que está sendo utilizado.
					dataType: 'html',//É o tipo de dado que a página vai retornar.
					url: 'produtos.php',//Indica a página que está sendo solicitada.
					//função que vai ser executada assim que a requisição for enviada
					beforeSend: function () {
						$("#iniciarpausa").html("Carregando...");
					},
					data: {nunota: nota, tipoProduto: teste},//Dados para consulta
					//função que será executada quando a solicitação for finalizada.
					success: function (msg)
					{
						var produtos = msg.split('|');

                        document.getElementById('prodId').innerHTML = produtos[0]
					}
				});
		}

        function  alterarQtdMaxLocPad(qtd, locpad, codemp, codprod)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'alterarqtdmaxlocalpad.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: {qtd: qtd, locpad: locpad, codemp: codemp, codprod: codprod},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (retorno)
                {
                    document.getElementById("maxlocalpadrao").textContent = retorno;
                    $("#close").click()
                }
            });
        }
        $('#btnAlterarQtdMaxLocPad').click(function () {
            alterarQtdMaxLocPad($("#qtdMaxLocPad").val(), $("#enderecoMaxLoc").val(),$("#codemp").val(), $("#codprod").val())
        });

        function imagemproduto(referencia)
        {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax
                    ({
                        //Configurações
                        type: 'POST',//Método que está sendo utilizado.
                        dataType: 'html',//É o tipo de dado que a página vai retornar.
                        url: 'imagemproduto.php',//Indica a página que está sendo solicitada.
                        //função que vai ser executada assim que a requisição for enviada
                        beforeSend: function () {
                          //  $("#imagemproduto").html("Carregando...");
                        },
                        data: {referencia: referencia},//Dados para consulta
                        //função que será executada quando a solicitação for finalizada.
                        success: function (msg)
                        {
                            $("#imagemproduto").html(msg);
                            //alert(msg);
                        }
                    });
        }
        function procurarOutroLocal(qtdneg, nunota, sequencia, codprod, codusu)
        {
            var selectedOption = $("input[name='fav_language']:checked").val();
            var observacao = document.getElementById("outros").value;

            if(selectedOption == undefined){
                selectedOption = '';
            }

            var ocorrencia = selectedOption +' ' +observacao

            //O método $.ajax(); é o responsável pela requisição
            $.ajax
            ({
                //Configurações
                type: 'POST',//Método que está sendo utilizado.
                dataType: 'html',//É o tipo de dado que a página vai retornar.
                url: 'procuraroutrolocal.php',//Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function(){
                    $("#loader").hide();
                },
                data: {qtdneg: qtdneg, nunota: nunota, sequencia: sequencia, codprod: codprod, codusu: codusu, ocorrencia: ocorrencia},//Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function (msg)
                {
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>

