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
const regex = /^[18]/;
const ignorar = ['1980101', '1980102', '1181001','1981001','1378888','1390703'
    ,'1390101'
,'1390102'
,'1390103'
,'1390104'
,'1390200'
,'1390201'
,'1390202'
,'1390203'
,'1390204'
,'1390300'
,'1390301'
,'1390302'
,'1390303'
,'1390304'
,'1390400'
,'1390401'
,'1390402'
,'1390403'
,'1390404'
,'1390500'
,'1390501'
,'1390502'
,'1390503'
,'1390504'
,'1390600'
,'1390601'
,'1390602'
,'1390603'
,'1390604'
,'1390700'
,'1390701'
,'1390702'
,'1390703'
,'1390704'
,'1390800'
,'1390801'
,'1390802'
,'1390803'
,'1390804'
,'1390900'
,'1390901'
,'1390902'
,'1390903'
,'1390904'
,'1391000'
,'1391001'
,'1391002'
,'1391003'
,'1391004'
,'1391100'
,'1391101'
,'1391102'
,'1391103'
,'1391104'
,'1391200'
,'1391201'
,'1391202'
,'1391203'
,'1391204'
,'1980101'
,'1980102'
,'1980201'
,'1980202'
,'1980203'
,'1980301'
,'1980306'
,'1980501'
,'1980502'
,'1980503'
,'1980506'
,'1980701'
,'1980801'
,'1981001'
,'1981002'
,'1981003'
,'1981004'
,'1981005'
,'1981006'
,'1981101'
,'1981102'
,'1981103'
,'1981104'
,'1981105'
,'1981106'
,'1981201'
,'1981202'
,'1981203'
,'1981204'
,'1981205'
,'1981206'
,'1981301'
,'1981302'
,'1981303'
,'1981304'
,'1981305'
,'1981306'
,'1986401'
,'1988001'
];

$(document).ready(function () {
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
});

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
                document.getElementById('descrprod').innerHTML = response.success.descrprod;
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
            codemp: codemp , // Enviar vazio se não houver valor
            referencia: referencia,
            endsaida: endsaida,
            lote: lote,
            route: 'buscaInformacoesLocal'
        },
        success: function(response) {

            
           // console.log('Valor de codemp retornado:', response.success ? response.success.codemp : 'Nenhum dado');

            if (response.success) {
                // Preencher qtdlocal com o valor retornado
                document.getElementById('qtdlocal').innerHTML = response.success.qtdlocal;
                const select = document.getElementById('empresas');

                const returnedValue = response.success.codemp; // Valor retornado pelo PHP
                const options = select.options;

                // Percorrer as opções do select e selecionar a que corresponde ao valor retornado
                for (let i = 0; i < options.length; i++) {
                    if (options[i].value == returnedValue) {
                        options[i].selected = true; // Define como selecionado
                        break;
                    }
                }
          
                // Preencher qtdmax com o valor retornado ou deixar vazio se for -1
                if (response.success.qtdmax == -1) {
                    document.getElementById('qtdmax').value = '';
                } else {
                    document.getElementById('qtdmax').value = response.success.qtdmax;
                }
                buscaLocalPadrao();
                // Preencher o campo selectPadrao com o codemp retornado
                document.getElementById('selectPadrao').value = response.success.codemp;
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
};




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
    const qtdneg = document.getElementById('qtdneg').value;
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
                qtdneg: qtdneg,
                route: 'validaParametros'
            },
            success: function(response) {
                if(response.success) {
                    // Verifica se o endereco de chegada e de saida começam com 1 ou 8
                    let localPadraoText = '';
                    if(regex.test(endsaida) && regex.test(endchegada) && !ignorar.includes(endsaida) && !ignorar.includes(endchegada)) {
                        localPadraoText = 'O local padrão será alterado. '
                    } 
                    const confirmacao = confirm(localPadraoText + `Confirma a transferência do item ${referencia} do local ${endsaida} para o local ${endchegada}?`);
                    if(confirmacao) {
                        transferirProduto(codemp, referencia, lote, endsaida, endchegada, qtdneg, qtdmax);
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

const transferirProduto = (codemp, referencia, lote, endsaida, endchegada, qtdneg, qtdmax) => {
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
            qtdneg: qtdneg,
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
        habilitaQuantidade();
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
            habilitaQuantidade();
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
        habilitaQuantidade();
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
            habilitaQuantidade();
        }
    } else {
        alert('Digite um valor.');
    }
}

const habilitaQuantidade = () => {
    let endsaida = document.getElementById('endsaida').value
    let endchegada = document.getElementById('endchegada').value;
    let qtdneg = document.getElementById('qtdneg');
    if(endsaida != '' && endchegada != '') {
        if(regex.test(endsaida) && regex.test(endchegada) && !ignorar.includes(endsaida) && !ignorar.includes(endchegada)) {
            qtdneg.disabled = true;
            qtdneg.value = '';
        } else {
            qtdneg.disabled = false;
            qtdneg.focus();
        }
    } else {
        qtdneg.disabled = true;
        qtdneg.value = '';
    }
}

const preencheEnderecoChegada = () => {
    document.getElementById('endchegada').value = document.getElementById('localpadrao').innerHTML;
    enderecoChegadaBipado = 'N';
    buscaQtdMax();
    habilitaQuantidade();
}

const preencheEnderecoChegadaPalete = () => {
    document.getElementById('endchegada').value = '1980102';
    enderecoChegadaBipado = 'N';
    buscaQtdMax();
    habilitaQuantidade();
}