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