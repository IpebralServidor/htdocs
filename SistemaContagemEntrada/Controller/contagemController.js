const urlParams = new URLSearchParams(window.location.search);
const nunota = urlParams.get('nunota');
const tipo = urlParams.get('tipo');

// Variáveis que guardarão o tempo de digitação dos campos
let tempoInicialReferencia = 0;
// Variáveis que guardarão o texto de digitação dos campos
let inputInicialReferencia = '';
// Variáveis para guardar se o campo foi bipado ou digitado
let referenciaBipado = '';
// Variável que guarda o tempo de input para ser considerado digitação ou leitor de código de barras
const tempoMaximoDigitacao = 250;

$(document).ready(function() {    
    document.getElementById('titleBoxH6').innerHTML = 'Produtos na nota: ' + nunota;
    document.getElementById('notaAtual').innerHTML = 'Nota: ' + nunota;
    buscaItensContagem(nunota, tipo);
    desabilitaFinalizaCont(); //libero ou nao o botao de finalizar contagem

    $('#ocorrenciaModal').on('show.bs.modal', function () {
        // Desmarca os botões de rádio
        $('input[name="ocorrencia"]').prop('checked', false);
    
        // Limpa o campo de texto
        $('#outros').val('');
    });
    // let inputQtd = document.getElementById('quantidade');
    // let qtdSeparar = document.getElementById('qtdseparar');
    // let total = document.getElementById('total');

    // inputQtd.addEventListener('input', function() {
    //     let qtd = (inputQtd.value == '' ? 0 : inputQtd.value);
    //     let tot = parseFloat(qtd) + parseFloat(qtdSeparar.innerHTML);
    //     total.textContent = ' ' + tot;
    // });
});

const buscaItensContagem = (nunota, tipo) => {
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
            nunota: nunota,
            tipo: tipo,
            route: 'buscaItensContagem'
        },
        success: function(response) {
            if(response.success) {
                document.getElementById('itens').innerHTML = response.success;
                let progressBar = document.getElementById('progress-bar');
                progressBar.style.width = response.progress_bar + '%';
                progressBar.innerText = response.progress_bar + '%';
            } else {
                alert('Erro: ' + response.error);
            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
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
        }
    } else {
        alert('Digite um valor.');
    }
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
            nunota: nunota,
            referencia: referencia,
            tipo : tipo,
            route: 'buscaInformacoesProduto'
        },
        success: function(response) {
            if(response.success) {
                document.getElementById('imagemproduto').src = 'data:image/jpeg;base64,' + response.success.imagem;
                response.success.tipcontest != 'L' ? document.getElementById('lote').disabled = true : (document.getElementById('lote').disabled = false, document.getElementById('lote').focus());
                document.getElementById('lote').value = '';
                document.getElementById('descrprod').innerHTML = response.success.descrprod;
                document.getElementById('agrupmin').innerHTML = response.success.agrupmin;
                document.getElementById('peso').value  = response.success.peso;
                document.getElementById('largura').value  = response.success.largura;
                document.getElementById('altura').value  = response.success.altura;
                document.getElementById('comprimento').value  = response.success.espessura;
                document.getElementById('obsetiqueta').innerHTML  = response.success.obsetiqueta;
                document.getElementById('qtdseparar').innerHTML  = response.success.qtdseparar;
                //document.getElementById('total').textContent = response.success.qtdseparar;
            } else {
                document.getElementById('referencia').value = '';
                document.getElementById('lote').value = '';
                document.getElementById('referencia').focus();
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

const atualizarDimensoes = () => {
    const referencia = document.getElementById('referencia').value;
    const peso = document.getElementById('peso').value;
    const largura = document.getElementById('largura').value;
    const altura = document.getElementById('altura').value;
    const comprimento = document.getElementById('comprimento').value;

    // Validação para evitar valores inválidos
    if (peso == 0 || largura == 0 || altura == 0 || comprimento == 0) {
        alert('Favor preencher com valores válidos as medidas e peso.');
        return; // Interrompe a execução da função
    }

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
            referencia: referencia,
            peso: peso,
            largura: largura,
            altura: altura,
            comprimento: comprimento,            
            route: 'atualizarDimensoes'
        },
        success: function(response) {
            if(response.success) {
                alert(response.success.msg);
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

const desabilitaFinalizaCont = () => {
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
            nunota : nunota,
            route: 'desabilitaFinalizaCont'
        },
        success: function(response) {
            if(response.success.msg == 'false') {
                document.getElementById("finalizaContarBtn").disabled = true;
            } else {
                document.getElementById("finalizaContarBtn").disabled = false;
            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });   
}

const atualizaContagem = (qtdcont) => {
    const referencia = document.getElementById('referencia').value
    const codbalanca = document.getElementById('codbalanca').value
    const lote = document.getElementById('lote').value;
    const qtdseparar = document.getElementById('qtdseparar').innerHTML;


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
            referencia: referencia,
            nunota: nunota,
            tipo : tipo,    
            codbalanca: codbalanca,
            qtdcont: qtdcont,
            lote: lote,
            qtdseparar: qtdseparar,
            route: 'atualizarContagem'
        },
        success: function(response) {
            if(response.success) {
                alert(response.success.msg)
                atualizarDimensoes();
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



const verificaRecontagem = () => {
    const referencia = document.getElementById('referencia').value;
    const codbalanca = document.getElementById('codbalanca').value;
    const qtdcont = document.getElementById('quantidade').value;
    const lote = document.getElementById('lote');
    const qtdseparar = document.getElementById('qtdseparar').innerHTML;
    
    const peso = document.getElementById('peso').value;
    const largura = document.getElementById('largura').value;
    const altura = document.getElementById('altura').value;
    const comprimento = document.getElementById('comprimento').value;

    // Verifica se peso, largura ou altura são nulos, vazios ou iguais a zero
    if (!peso || peso == 0 || !largura || largura == 0 || !altura || altura == 0 || !comprimento || comprimento == 0) {
        alert('Verifique se as dimensões foram preenchidas corretamente.');
    } else if (quantidade < 0 || referencia === '' || (!lote.disabled && lote.value === '')) {
        alert('Informe os campos corretamente.');
    } else {
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
                nunota: nunota,
                referencia: referencia,
                qtdcont: qtdcont,
                codbalanca: codbalanca,
                tipo:tipo,
                lote: lote.value,
                qtdseparar: qtdseparar,
                route: 'verificaRecontagem'
            },
            success: function(response) {
                if(response.success) {
                    alert(response.success.msg);
                    location.reload();
                } else if(response.recontagem) {
                    let quantidadeRecont;
                    do {
                        quantidadeRecont = prompt(`O item ${referencia.trim()} deu divergência em relação a nota. Favor recontar e digite a quantidade novamente: `);
                    }  while(quantidadeRecont == null || quantidadeRecont.trim() === ""); 
                    
                    if(isNaN(Number(quantidadeRecont))) {
                        alert('Digite uma quantidade válida!');
                    } else {
                        atualizaContagem(quantidadeRecont);
                    }
                } else {
                    alert('Erro: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert('Erro na requisição AJAX: ' + error);
            }
        });
    }
}


const finalizarContagem = () => {
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
            nunota: nunota,
            tipo : tipo,    
            route: 'finalizarContagem'
        },
        success: function(response) {
            if(response.success) {
                alert(response.success.msg);
                window.location.href = '../View/index.php';
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

const mostraContagens = (nucontite) => {
    $.ajax({
        method: 'GET', 
        dataType: 'json', 
        url: '../routes/routes.php',
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nucontite: nucontite,
            route: 'mostraContagens'
        },
        success: function(response) {
            if(response.success) {
                document.getElementById('contagens').innerHTML = response.success;
                $('#subcontagemModal').modal('show');
            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });
}

const aplicarOcorrencia = () => {
    let referencia = document.getElementById("referencia").value;
    let lote = document.getElementById("lote");

    if (!referencia || (!lote.value && !lote.disabled)) {
        alert('Preencha todos os campos antes de registrar uma ocorrência!');
    } else {
        let opcao = $("input[name='ocorrencia']:checked").val();
        let observacao = document.getElementById("outros").value;
        if (opcao == 'undefined') {
            opcao = '';
        }
        if(opcao == '' && observacao == '') {
            alert('Nenhuma ocorrencia preenchida!');
        } else {
            let ocorrencia = opcao + ' ' + observacao;
            $.ajax({
                method: 'POST', 
                dataType: 'json', 
                url: '../routes/routes.php',
                beforeSend: function() {
                    $("#loader").show();
                },
                complete: function() {
                    $("#loader").hide();
                },
                data: {
                    nunota: nunota,
                    tipo: tipo,
                    referencia: referencia,
                    lote: lote.value,
                    ocorrencia: ocorrencia,
                    route: 'aplicarOcorrencia'
                },
                success: function(response) {
                    if(response.success) {
                        alert(response.success.msg);
                        $('#ocorrenciaModal').modal('hide');
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
}

const editQtd = (nucontsub) => {
    let td = document.getElementById('qtd_' + nucontsub);
    let currentValue = td.innerText;
    
    td.innerHTML = "<input type='number' id='input_" + nucontsub + "' class='form-control' value='" + currentValue + "' style='text-align: right'/>";

    document.getElementById('input_' + nucontsub).select();

    let editButton = document.querySelector('#row_' + nucontsub + ' i');
    editButton.className = 'fa-solid fa-circle-check'; 
    editButton.setAttribute('onclick', 'saveQtd(' + nucontsub + ')'); 
}

const saveQtd = (nucontsub) => {
    let input = document.getElementById('input_' + nucontsub);
    let newValue = input.value;
    $.ajax({
        method: 'POST', 
        dataType: 'json', 
        url: '../routes/routes.php',
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nucontsub: nucontsub,
            qtd: newValue,
            nunota: nunota,
            tipo: tipo,
            route: 'editaSubContagem'
        },
        success: function(response) {
            if(response.success) {
                document.getElementById('qtd_' + nucontsub).innerText = newValue;
                document.getElementById('dt_' + nucontsub).innerText = response.success;

                let editButton = document.querySelector('#row_' + nucontsub + ' i');
                editButton.className = 'fa-solid fa-pen';
                editButton.setAttribute('onclick', 'editQtd(' + nucontsub + ')');
                buscaItensContagem(nunota, tipo)
            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });
}

