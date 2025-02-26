const urlParams = new URLSearchParams(window.location.search);
const codemp = urlParams.get('codemp');
const codlocal = urlParams.get('codlocal');

// Variáveis que guardarão o tempo de digitação dos campos
let tempoInicialReferencia = 0;
// Variáveis que guardarão o texto de digitação dos campos
let inputInicialReferencia = '';
// Variáveis para guardar se o campo foi bipado ou digitado
let referenciaBipado = '';
// Variável que guarda o tempo de input para ser considerado digitação ou leitor de código de barras
const tempoMaximoDigitacao = 250;

$(document).ready(function() {
    document.getElementById('titleBoxH6').innerHTML = 'Produtos no endereço ' + codlocal;
    document.getElementById('enderecoAtual').innerHTML = 'Endereço: ' + codlocal;
    codlocal.startsWith('1') || codlocal.startsWith('8') ? document.getElementById('qtdmax').disabled = false : document.getElementById('qtdmax').disabled = true;
    buscaItensInventario(codemp, codlocal);
});

const buscaItensInventario = (codemp, codlocal) => {
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
            codlocal: codlocal,
            route: 'buscaItensInventario'
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
            codemp: codemp,
            referencia: referencia,
            codlocal: codlocal,
            route: 'buscaInformacoesProduto'
        },
        success: function(response) {
            if(response.success) {
                if(!response.success.nunota) {
                    let resgate = confirm('Item não consta no local. Deseja resgatar do 6000000?');
                    if(resgate) {
                        document.getElementById('imagemproduto').src = 'data:image/jpeg;base64,' + response.success.imagem;
                        response.success.tipcontest != 'L' ? document.getElementById('lote').disabled = true : document.getElementById('lote').disabled = false;
                        document.getElementById('lote').value = '';
                        document.getElementById('descrprod').innerHTML = response.success.descrprod;
                        document.getElementById('agrupmin').innerHTML = response.success.agrupmin;
                        document.getElementById('obsetiqueta').innerHTML = response.success.obsetiqueta;
                        document.getElementById('qtdmax').value = response.success.qtdmax;
                    } else {
                        document.getElementById('referencia').value = '';
                        document.getElementById('lote').value = '';
                        document.getElementById('qtdmax').value = '';
                        document.getElementById('referencia').focus();
                    }
                } else {
                    document.getElementById('imagemproduto').src = 'data:image/jpeg;base64,' + response.success.imagem;
                    response.success.tipcontest != 'L' ? document.getElementById('lote').disabled = true : document.getElementById('lote').disabled = false;
                    document.getElementById('lote').value = '';
                    document.getElementById('descrprod').innerHTML = response.success.descrprod;
                    document.getElementById('agrupmin').innerHTML = response.success.agrupmin;
                    document.getElementById('obsetiqueta').innerHTML = response.success.obsetiqueta;
                    document.getElementById('qtdmax').value = response.success.qtdmax;
                }
            } else {
                document.getElementById('referencia').value = '';
                document.getElementById('lote').value = '';
                document.getElementById('qtdmax').value = '';
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

const verificaRecontagem = () => {
    const referencia = document.getElementById('referencia').value;
    const controle = document.getElementById('lote').value;
    const quantidade = document.getElementById('quantidade').value;
    const qtdmax = document.getElementById('qtdmax').value;
    if(quantidade < 0 || referencia === '') {
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
                codemp: codemp,
                codlocal: codlocal,
                referencia: referencia,
                controle: controle,
                quantidade: quantidade,
                qtdmax: qtdmax,
                route: 'verificaRecontagem'
            },
            success: function(response) {
                if(response.success) {
                    alert(response.success);
                    location.reload();
                } else if(response.recontagem) {
                    let quantidadeTransf;
                    do {
                        quantidadeTransf = prompt(`O item ${referencia.trim()} deu divergência em relação ao estoque do sistema. Favor recontar e digite a quantidade novamente: `);
                    }  while(quantidadeTransf === null || quantidadeTransf.trim() === "");
                    
                    if(isNaN(Number(quantidadeTransf))) {
                        alert('Digite uma quantidade válida!');
                    } else {
                        contaProduto(codemp, codlocal, referencia, controle, quantidadeTransf, qtdmax);
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

const contaProduto = (codemp, codlocal, referencia, controle, quantidadeTransf, qtdmax) => {
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
            codlocal: codlocal,
            referencia: referencia,
            controle: controle,
            quantidade: quantidadeTransf,
            qtdmax: qtdmax,
            route: 'contaProduto'
        },
        success: function(response) {
            if(response.success) {
                alert(response.success);
                location.reload();
            } else {
                alert('Erro: ' + response.error);
            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
        }
    });
}

const checafinalizaInventario = () => {
    let progressBar = document.getElementById('progress-bar');
    if(progressBar.innerText === '100%') {
        finalizaInventario();
    } else {
        let confirmacaoFinalizar = confirm('Faltam itens para contar. Deseja finalizar o inventário mesmo assim?');
        if(confirmacaoFinalizar) {
            finalizaInventario();
        }
    }
}

const finalizaInventario = () => {
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
            codlocal: codlocal,
            route: 'finalizaInventario'
        },
        success: function(response) {
            if(response.success) {
                alert(response.success);
                window.location.href = '../View/index.php';
            } else {
                alert('Erro: ' + response.error);
            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
        }
    });
}

/*const transfereItem = (button) => {
    const row = button.closest('tr');
    console.log(row);

    const tdReferencia = row.querySelector('td');
    const tdControle = row.querySelectorAll('td')[1];
    const tdQuantidadeInventario = row.querySelectorAll('td')[4];

    const referencia = tdReferencia.textContent || tdReferencia.innerText;
    const controle = tdControle.textContent || tdControle.innerText;
    const quantidadeInventario = tdQuantidadeInventario.textContent || tdQuantidadeInventario.innerText;

    const quantidadeTransf = prompt(`Deseja transferir qual quantidade do item ${referencia.trim()}?`);
    if(isNaN(Number(quantidadeTransf))) {
        alert('Digite uma quantidade válida!');
    } else {
        if(0 < Number(quantidadeTransf)  && Number(quantidadeTransf) <= Number(quantidadeInventario)) {
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
                    codlocal: codlocal,
                    referencia: referencia,
                    controle: controle,
                    quantidade: quantidadeTransf,
                    route: 'transfereItem'
                },
                success: function(response) {
                    if(response.success) {
                        alert(response.success);
                        location.reload();
                    } else {
                        alert('Erro: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Erro na requisição AJAX: ' + error);
                }
            });
        } else {
            alert('Quantidade insuficiente para transferir.');
        }
    }
}*/