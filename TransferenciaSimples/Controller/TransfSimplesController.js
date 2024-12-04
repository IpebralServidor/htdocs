// Variáveis que guardarão o tempo de digitação dos campos
let tempoInicialReferencia = 0;
let tempoInicialEndSaida = 0;
let tempoInicialEndChegada = 0;

// Variáveis que guardarão o texto de digitação dos campos
let inputInicialReferencia = '';
let inputInicialEndSaida = '';
let inputInicialEndChegada = '';

let referenciaBipado = '';
let enderecoSaidaBipado = '';
let enderecoChegadaBipado = '';
// Variável que guarda o tempo de input para ser considerado digitação ou leitor de código de barras
const tempoMaximoDigitacao = 250;

const desabilitaSelectPadrao = () => {
    document.getElementById("selectPadrao").disabled = true;
}

const buscaInformacoesProduto = () => {
    const referencia = document.getElementById('referencia').value;
    $.ajax({
        method: 'GET',
        url: '../routes/routes.php',
        dataType: 'json',
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            referencia: referencia,
            route: 'buscaInformacoesProduto'
        },
        success: function(response) {
            if(response.success) {
                document.getElementById('imagemproduto').src = 'data:image/jpeg;base64,' + response.success.imagem;
                response.success.tipcontest != 'L' ? document.getElementById('lote').disabled = true : document.getElementById('lote').disabled = false;
                document.getElementById('lote').value = '';
            } else {
                document.getElementById('referencia').value = '';
                document.getElementById('lote').value = '';
                alert('Erro: ' + response.error);
            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });   
}

const buscaLocalPadrao = () => {
    const referencia = document.getElementById('referencia').value;
    const codemp = document.getElementById('empresas').value;
    if(referencia != '' && codemp != '') {
        verificaLocaisComProduto(referencia, codemp);
        $.ajax({
            method: 'GET',
            url: '../routes/routes.php',
            dataType: 'json',
            beforeSend: function() {
                $("#loader").show();
            },
            complete: function() {
                $("#loader").hide();
            },
            data: {
                referencia: referencia,
                codemp: codemp,
                route: 'buscaLocalPadrao'
            },
            success: function(response) {
                if(response.success) {
                    document.getElementById('localpadrao').innerHTML = response.success.codlocalpad;
                } else {
                    alert('Erro: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert('Erro na requisição AJAX: ' + error);
                console.log(xhr);
                console.log(status);
            }
        });   
    }
}

const buscaInformacoesLocal = () => {
    const codemp = document.getElementById('empresas').value;
    const referencia = document.getElementById('referencia').value;
    const endsaida = document.getElementById('endsaida').value;
    const lote = document.getElementById('lote').value;
    if(codemp != '') {
        if(referencia != '') {
            $.ajax({
                method: 'GET',
                url: '../routes/routes.php',
                dataType: 'json',
                beforeSend: function() {
                    $("#loader").show();
                },
                complete: function() {
                    $("#loader").hide();
                },
                data: {
                    codemp: codemp,
                    referencia: referencia,
                    endsaida: endsaida,
                    lote: lote,
                    route: 'buscaInformacoesLocal'
                },
                success: function(response) {
                    if(response.success) {
                        document.getElementById('qtdlocal').innerHTML = response.success.qtdlocal;
                        if(response.success.qtdmax == -1) {
                            document.getElementById('qtdmax').value = '';
                        } else {
                            document.getElementById('qtdmax').value = response.success.qtdmax;
                        }
                    } else {
                        document.getElementById('endsaida').value = '';
                        alert('Erro: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Erro na requisição AJAX: ' + error);
                    console.log(xhr);
                    console.log(status);
                }
            });   
        } else {
            document.getElementById('endsaida').value = '';
            alert('Digite a referência.');    
        }
    } else {
        document.getElementById('endsaida').value = '';
        alert('Selecione uma empresa.');
    }
}

const buscaQtdMax = () => {
    const referencia = document.getElementById('referencia').value;
    const codemp = document.getElementById('empresas').value;
    const endchegada = document.getElementById('endchegada').value;
    if(referencia != '' && codemp != '') {
        $.ajax({
            method: 'GET',
            url: '../routes/routes.php',
            dataType: 'json',
            beforeSend: function() {
                $("#loader").show();
            },
            complete: function() {
                $("#loader").hide();
            },
            data: {
                referencia: referencia,
                codemp: codemp,
                endchegada: endchegada,
                route: 'buscaQtdMax'
            },
            success: function(response) {
                if(response.success) {
                    if(response.success.qtdmax != '') {
                        document.getElementById('qtdmax').value = response.success.qtdmax;
                    }
                } else {
                    alert('Erro: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert('Erro na requisição AJAX: ' + error);
                console.log(xhr);
                console.log(status);
            }
        });   
    }
}

const validaParametros = () => {
    const codemp = document.getElementById('empresas').value;
    const referencia = document.getElementById('referencia').value;
    const lote = document.getElementById('lote').value;
    const endsaida = document.getElementById('endsaida').value;
    const endchegada = document.getElementById('endchegada').value;
    const qtdmax = document.getElementById('qtdmax').value;
    if(codemp != '' && referencia != '' && endsaida != '' && endchegada != '') {
        $.ajax({
            method: 'GET',
            url: '../routes/routes.php',
            dataType: 'json',
            beforeSend: function() {
                $("#loader").show();
            },
            complete: function() {
                $("#loader").hide();
            },
            data: {
                codemp: codemp,
                referencia: referencia,
                lote: lote,
                endsaida: endsaida,
                endchegada: endchegada,
                qtdmax: qtdmax,
                route: 'validaParametros'
            },
            success: function(response) {
                if(response.success) {
                    // Verifica se o endereco de chegada e de saida começam com 1 ou 8
                    const regex = /^[18]/;
                    let localPadraoText = '';
                    if(regex.test(endsaida) && regex.test(endchegada)) {
                        localPadraoText = 'O local padrão será alterado. '
                    } 
                    const confirmacao = confirm(localPadraoText + `Confirma a transferência do item ${referencia} do local ${endsaida} para o local ${endchegada}?`);
                    if(confirmacao) {
                        transferirProduto(codemp, referencia, lote, endsaida, endchegada, qtdmax);
                    }
                } else {
                    alert('Erro: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert('Erro na requisição AJAX: ' + error);
                console.log(error);
                console.log(xhr);
                console.log(status);
            }
        });   
    } else {
        alert('Preencha os campos corretamente.');
    }
}

const transferirProduto = (codemp, referencia, lote, endsaida, endchegada, qtdmax) => {
    $.ajax({
        method: 'POST',
        url: '../routes/routes.php',
        dataType: 'json',
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            codemp: codemp,
            referencia: referencia,
            lote: lote,
            endsaida: endsaida,
            endchegada: endchegada,
            qtdmax: qtdmax,
            referenciaBipado: referenciaBipado,
            enderecoSaidaBipado: enderecoSaidaBipado,
            enderecoChegadaBipado: enderecoChegadaBipado,
            route: 'transferirProduto'
        },
        success: function(response) {
            console.log(response);
            if(response.success) {
                alert('Produto transferido com sucesso!');
                location.reload();
            } else {
                alert('Erro: ' + response.error);
            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });
}

const limpaCampo = (campo) => {
    document.getElementById(campo).value = '';
}

const iniciarMedicaoReferencia = () => {
    tempoInicialReferencia = Date.now();
}

const finalizarMedicaoReferencia = () => {
    let tempoFinalReferencia = Date.now();
    if(tempoFinalReferencia - tempoInicialReferencia > tempoMaximoDigitacao) {
        inputInicialReferencia = document.getElementById('referencia').value;
        togglePopupConfirmarReferencia();
        limpaCampo('referencia');
    } else {
        referenciaBipado = 'S';
        buscaInformacoesProduto();
        buscaLocalPadrao(); 
    }
}

const togglePopupConfirmarReferencia = () => {
    document.getElementById('popupConfirmarReferencia').classList.toggle("active");
    document.getElementById('confirmacaoReferencia').value = '';
    document.getElementById('confirmacaoReferencia').focus();
}

const confirmaReferencia = () => {
    let inputNovoReferencia = document.getElementById('confirmacaoReferencia').value;
    if(inputNovoReferencia != '') {
        if(inputNovoReferencia != inputInicialReferencia) {
            alert('Valores digitados não batem. Verifique a digitação');
        } else {
            togglePopupConfirmarReferencia();
            document.getElementById('referencia').value = inputNovoReferencia;
            referenciaBipado = 'N';
            buscaInformacoesProduto(); 
            buscaLocalPadrao(); 
        }
    } else {
        alert('Digite um valor.');
    }
}

const iniciarMedicaoEndSaida = () => {
    tempoInicialEndSaida = Date.now();
}

const finalizarMedicaoEndSaida = () => {
    let tempoFinalEndSaida = Date.now();
    if(tempoFinalEndSaida - tempoInicialEndSaida > tempoMaximoDigitacao) {
        inputInicialEndSaida = document.getElementById('endsaida').value;
        togglePopupConfirmarEndSaida();
        limpaCampo('endsaida');
    } else {
        enderecoSaidaBipado = 'S';
        buscaInformacoesLocal();
    }
}

const togglePopupConfirmarEndSaida = () => {
    document.getElementById('popupConfirmarEndSaida').classList.toggle("active");
    document.getElementById('confirmacaoEndSaida').value = '';
    document.getElementById('confirmacaoEndSaida').focus();
}

const confirmaEndSaida = () => {
    let inputNovoEndSaida = document.getElementById('confirmacaoEndSaida').value;
    if(inputNovoEndSaida != '') {
        if(inputNovoEndSaida != inputInicialEndSaida) {
            alert('Valores digitados não batem. Verifique a digitação');
        } else {
            togglePopupConfirmarEndSaida();
            document.getElementById('endsaida').value = inputNovoEndSaida;
            enderecoSaidaBipado = 'N';
            buscaInformacoesLocal();
        }
    } else {
        alert('Digite um valor.');
    }
}

const iniciarMedicaoEndChegada = () => {
    tempoInicialEndChegada = Date.now();
}

const finalizarMedicaoEndChegada = () => {
    let tempoFinalEndChegada = Date.now();
    if(tempoFinalEndChegada - tempoInicialEndChegada > tempoMaximoDigitacao) {
        inputInicialEndChegada = document.getElementById('endchegada').value;
        togglePopupConfirmarEndChegada();
        limpaCampo('endchegada');
    } else {
        enderecoChegadaBipado = 'S';
        buscaQtdMax();
    }
}

const togglePopupConfirmarEndChegada = () => {
    document.getElementById('popupConfirmarEndChegada').classList.toggle("active");
    document.getElementById('confirmacaoEndChegada').value = '';
    document.getElementById('confirmacaoEndChegada').focus();
}


const confirmaEndChegada = () => {
    let inputNovoEndChegada = document.getElementById('confirmacaoEndChegada').value;
    if(inputNovoEndChegada != '') {
        if(inputNovoEndChegada != inputInicialEndChegada) {
            alert('Valores digitados não batem. Verifique a digitação');
        } else {
            togglePopupConfirmarEndChegada();
            document.getElementById('endchegada').value = inputNovoEndChegada;
            enderecoChegadaBipado = 'N';
            buscaQtdMax();
        }
    } else {
        alert('Digite um valor.');
    }
}