// Pega a nunota que foi passada pela URL
const urlParams = new URLSearchParams(window.location.search);
const nunota = urlParams.get('nunota');
let tipoNota;

const checkbox = document.getElementById('checkVariosProdutos');
const botao = document.getElementById('editarTempBtn');
let checkboxEstadoAnterior = checkbox.checked;

// Variáveis que guardarão o tempo de digitação dos campos
let tempoInicialProduto = 0;
let tempoInicialEndereco = 0;
let tempoInicialEnderecoTemp = 0;

// Variáveis que guardarão o texto de digitação dos campos
let inputInicialProduto = '';
let inputInicialEndereco = '';
let inputInicialEnderecoTemp = '';

let produtoBipado = '';
let enderecoBipado = '';
let enderecoTempBipado = '';
// Variável que guarda o tempo de input para ser considerado digitação ou leitor de código de barras
const tempoMaximoDigitacao = 250;

$(document).ready(function() {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'GET', //Método que está sendo utilizado.
        dataType: 'json', //É o tipo de dado que a página vai retornar.
        url: '../Model/informacoesnota.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        data: {
            nunota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(infosNota) {
            document.getElementById('codparc').innerHTML = infosNota['CODPARCORIGEM'];
            document.getElementById('nomeparc').innerHTML = infosNota['NOMEPARCORIGEM'];
            document.getElementById('codtipoper').innerHTML = infosNota['CODTIPOPERORIGEM'];
            document.getElementById('nunota').innerHTML = infosNota['NUNOTAORIGEM'];
            document.getElementById('codtipoperdest').innerHTML = infosNota['CODTIPOPERDESTINO'];
            document.getElementById('nunotadest').innerHTML = infosNota['NUNOTADESTINO'];
            document.getElementById("codemp").value = infosNota['CODEMP'];
        }
    });

});

document.getElementById("quantidade").addEventListener("focus", function() {
    imagemproduto($("#produto").val());
    retornainfoprodutos($("#produto").val(), document.getElementById('codparc').innerHTML);
});

function abrir() {
    document.getElementById('popupprodutos').style.display = 'block';
}

function fechar() {
    document.getElementById('popupprodutos').style.display = 'none';
}

function abrirEditar(referencia, codlocaldest, qtdneg) {
    document.getElementById("produtoedit").value = referencia;
    document.getElementById("localdestedit").value = codlocaldest;
    document.getElementById("quantidadeedit").value = qtdneg;
    document.getElementById('popupEditar').style.display = 'flex';
}

function fecharEditar() {
    document.getElementById('popupEditar').style.display = 'none';
}

function abrirdivergencias() {
    document.getElementById('popupdivergencias').style.display = 'block';
}

function fechardivergencias() {
    document.getElementById('popupdivergencias').style.display = 'none';
}

function abrirtemp() {
    document.getElementById('tempprodutos').style.display = 'block';
}

function fechartemp() {
    document.getElementById('tempprodutos').style.display = 'none';
}

function abrirEditarTemp() {
    document.getElementById('popupEditarTemp').style.display = 'block';
}

function fecharEditarTemp() {
    document.getElementById('popupEditarTemp').style.display = 'none';
}

function abrirInsereEndereco() {
    document.getElementById('popupInserirEndereco').style.display = 'flex';
    document.getElementById('enderecotemp').value = ''
}

function fecharInsereEndereco() {
    document.getElementById('popupInserirEndereco').style.display = 'none';
}

function marca_variosprod_confirm() {
    var result = confirm("Tem certeza que deseja Concluir?");
    if (result) {
        abrirInsereEndereco();
        return true;
    } else {
        var resultado = document.getElementById('resultadoVariosProd');
        const checkbox = document.getElementById('checkVariosProdutos');
        checkbox.checked = true;
        document.getElementById("endereco").disabled = true;
        document.getElementById("endereco").value = "";
        document.getElementById("editarTempBtn").style.display = "inline-block";
        resultado.innerHTML = "Desm. p/ concluir";
        return false;
    }
}

function imagemproduto(produto) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/imagemproduto.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {

        },
        data: {
            produto: produto
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            $("#imagemproduto").html(msg);
        }
    });
}


function retornainfoprodutos(produto, codparc) {
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/retornainfoproduto.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {

        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            produto: produto,
            codparc: codparc
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            var retorno = msg.split("/");
            document.getElementById("referenciaprod").textContent = retorno[0];
            document.getElementById("codfornprod").textContent = retorno[1];
            document.getElementById("descrprod").textContent = retorno[2];
            verificaLocaisComProduto(produto, document.getElementById("codemp").value);
        }
    });
}

//Função que faz a validação e insere os itens da transferência no Banco
function insereitens(produto, quantidade, endereco, nunota, checkvariosprodutos) {
    //O método $.ajax(); é o responsável pela requisição
    var isChecked = $("#checkVariosProdutos").prop("checked");

    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/insereestoquebtn.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            produto: produto,
            quantidade: quantidade,
            endereco: endereco,
            nunota: nunota,
            produtoBipado: produtoBipado,
            enderecoBipado: enderecoBipado,
            enderecoTempBipado: enderecoTempBipado,
            checkvariosprodutos: isChecked
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            msg = msg.trim();
            console.log(msg);
            produtoBipado = '';
            enderecoBipado = '';
            enderecoTempBipado = '';
            if (msg.includes("Item inserido com sucesso")) {
                document.getElementById("produto").value = "";
                document.getElementById("quantidade").value = "";
                document.getElementById("endereco").value = "";
            } else if (msg != "") {
                alert(msg);
            }

        }
    });
}

//Retorna os dados dos produtos que já foram inseridos quando se clica no Editar Prod.
function editarprodutos(nunota) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: 'editarprodutos.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#editarProdutosDiv").html("Carregando...");
        },
        data: {
            nunota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            $("#editarProdutosDiv").html(msg);
        }
    });
}

//Retorna a Tabela de Produtos Divergentes ao se clicar no botão
function retornaprodutosdivergentes(nunota) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: 'retornaprodutosdivergentes.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#produtosDivergentesDiv").html("Carregando...");
        },
        data: {
            nunota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            $("#produtosDivergentesDiv").html(msg);
        }
    });
}

//Retorna a Tabela de Produtos Divergentes ao se clicar no botão
function retornaprodutostemp(nunota) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: 'retornaprodutostemp.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#produtosTempDiv").html("Carregando...");
        },
        data: {
            nunota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            $("#produtosTempDiv").html(msg);
        }
    });
}

//Função que faz a finalização da nota do coletor
function finalizanota(nunota) {
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/finalizar.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {

        },
        data: {
            nunota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            alert(msg);
        }
    });
}

//Função que insere os itens da temporária para a TGFITE
function insereItensTempITE(nunota, endereco) {
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/inserirTempITE.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {

        },
        data: {
            nunota: nunota,
            endereco: endereco,
            produtoBipado: produtoBipado,
            enderecoBipado: enderecoBipado,
            enderecoTempBipado: enderecoTempBipado
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            msg = msg.trim();
            console.log(msg);
            produtoBipado = '';
            enderecoBipado = '';
            enderecoTempBipado = '';
            if (msg.includes("IPB: Itens Inseridos com Sucesso!")) {
                alert(msg);
                fecharInsereEndereco();
            } else if (msg != "") {
                alert(msg);
            }
        }
    });
}

checkbox.addEventListener('change', function() {
    // Verifica a mudança de estado do checkbox
    if (checkbox.checked !== checkboxEstadoAnterior) {
        if (checkbox.checked) {
            console.log('Checkbox foi marcado');
        } else {
            console.log('Checkbox foi desmarcado');
            marca_variosprod_confirm();
        }
        // Atualiza o estado anterior
        checkboxEstadoAnterior = checkbox.checked;
    }
});

function delete_confirm(nunota, sequencia, tipo, codusu) {
    var result = confirm("Tem certeza que deseja apagar esse item?");
    if (result) {
        excluirprodutonota(nunota, sequencia, tipo, codusu);
        editarprodutos(nunota);
        retornaprodutostemp(nunota);
    } else {
        return false;
    }
}

function excluirprodutonota(nunota, sequencia, tipo, codusu) //Tipo é para se é da ITE da nota ou temporária
{
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/deletarproduto.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nunota: nunota,
            sequencia: sequencia,
            tipo: tipo
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            alert(msg);
        }
    });
}

// Lógica de Exclusão dos Produtos 
function delete_confirm(nunota, sequencia, tipo, codusu) {
    var result = confirm("Tem certeza que deseja apagar esse item?");
    if (result) {
        excluirprodutonota(nunota, sequencia, tipo, codusu);
        editarprodutos(nunota);
        retornaprodutostemp(nunota);
    } else {
        return false;
    }
}

function excluirprodutonota(nunota, sequencia, tipo, codusu) {//Tipo é para se é da ITE da nota ou temporária
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/deletarproduto.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nunota: nunota,
            sequencia: sequencia,
            tipo: tipo
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            alert(msg);
        }
    });
}
// Fim da lógica de Exclusão dos Produtos

function edit_confirm() {
    produto = document.getElementById("produtoedit").value;
    localdest = document.getElementById("localdestedit").value;
    quantidade = document.getElementById("quantidadeedit").value;
    alert(produto + " " + localdest + " " + quantidade + " " + nunota);
    var result = confirm("Tem certeza que deseja editar esse item?");
    if (result) {
        editarprodutonota(nunota, produto, localdest, quantidade);
        editarprodutos(nunota);
        retornaprodutostemp(nunota);
    } else {
        return false;
    }
}

// Lógica de Edição dos Produtos
function editarprodutonota(nunota, produto, localdest, quantidade) { //Tipo é para se é da ITE da nota ou temporária
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/editarbtn.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nunota: nunota,
            produto: produto,
            localdest: localdest,
            quantidade: quantidade
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            alert(msg);
        }
    });
}
// Fim da lógica de Edição dos Produtos

function endereco() {
    // Seta o tipoNota para ser usado no restante do código
    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: '../Model/buscartiponota.php', 
        data: {
            nunota: nunota
        }, 
        success: function(returnTipoNota) {
            tipoNota = returnTipoNota;

            const endereco = document.getElementById("endereco")
            if (tipoNota == "TRANSF_CD5") {
                endereco.disabled = true
                endereco.val = "5069900"
                endereco.placeholder = "5069900"
            }
        }
    });
}

$('#confirmar').click(function() {
    var endereco = document.getElementById("endereco").value

    if (tipoNota == "TRANSF_CD5") {
        endereco = document.getElementById("endereco").val
    }

    insereitens($("#produto").val(), $("#quantidade").val(), endereco, nunota, $("#checkVariosProdutos").val());
});

$('#editarprodutosbtn').click(function() {
    editarprodutos(nunota);
});

$('#produtosDivergentesBtn').click(function() {
    retornaprodutosdivergentes(nunota);
});

$('#editarTempBtn').click(function() {
    retornaprodutostemp(nunota);
});

$('#finalizar').click(function() {

    var confirmafinalizacao = confirm("Tem certeza que deseja confirmar essa nota?");
    if (confirmafinalizacao) {
        finalizanota(nunota);
    } else {
        return false;
    }

});

$('#InserirTempITE').click(function() {
    if($("#enderecotemp").val()) {
        insereItensTempITE(nunota, $("#enderecotemp").val());
    }
});

const limpaCampo = (campo) => {
    document.getElementById(campo).value = '';
}

const iniciarMedicaoProduto = () => {
    tempoInicialProduto = Date.now();
}

const finalizarMedicaoProduto = () => {
    let tempoFinalProduto = Date.now();
    if(tempoFinalProduto - tempoInicialProduto > tempoMaximoDigitacao) {
        inputInicialProduto = document.getElementById('produto').value;
        togglePopupConfirmarProduto();
        limpaCampo('produto');
    } else {
        produtoBipado = 'S';
    }
}

const togglePopupConfirmarProduto = () => {
    document.getElementById('popupConfirmarProduto').classList.toggle("active");
    document.getElementById('confirmacaoProduto').value = '';
    document.getElementById('confirmacaoProduto').focus();
}

const confirmaProduto = () => {
    let inputNovoProduto = document.getElementById('confirmacaoProduto').value;
    if(inputNovoProduto != '') {
        if(inputNovoProduto != inputInicialProduto) {
            alert('Valores digitados não batem. Verifique a digitação');
        } else {
            togglePopupConfirmarProduto();
            document.getElementById('produto').value = inputNovoProduto;
            produtoBipado = 'N';
        }
    } else {
        alert('Digite um valor.');
    }
}

const iniciarMedicaoEndereco = () => {
    tempoInicialEndereco = Date.now();
}

const finalizarMedicaoEndereco = () => {
    let tempoFinalEndereco = Date.now();
    if(tempoFinalEndereco - tempoInicialEndereco > tempoMaximoDigitacao) {
        inputInicialEndereco = document.getElementById('endereco').value;
        togglePopupConfirmarEndereco();
        limpaCampo('endereco');
    } else {
        enderecoBipado = 'S';
    }
}

const togglePopupConfirmarEndereco = () => {
    document.getElementById('popupConfirmarEndereco').classList.toggle("active");
    document.getElementById('confirmacaoEndereco').value = '';
    document.getElementById('confirmacaoEndereco').focus();
}

const confirmaEndereco = () => {
    let inputNovoEndereco = document.getElementById('confirmacaoEndereco').value;
    if(inputNovoEndereco != '') {
        if(inputNovoEndereco != inputInicialEndereco) {
            alert('Valores digitados não batem. Verifique a digitação');
        } else {
            togglePopupConfirmarEndereco();
            document.getElementById('endereco').value = inputNovoEndereco;
            enderecoBipado = 'N';
        }
    } else {
        alert('Digite um valor.');
    }
}

const iniciarMedicaoEnderecoTemp = () => {
    tempoInicialEnderecoTemp = Date.now();
}

const finalizarMedicaoEnderecoTemp = () => {
    let tempoFinalEnderecoTemp = Date.now();
    if(tempoFinalEnderecoTemp - tempoInicialEnderecoTemp > tempoMaximoDigitacao) {
        inputInicialEnderecoTemp = document.getElementById('enderecotemp').value;
        togglePopupConfirmarEnderecoTemp();
        limpaCampo('enderecotemp');
    } else {
        enderecoTempBipado = 'S';
    }
}

const togglePopupConfirmarEnderecoTemp = () => {
    document.getElementById('popupConfirmarEnderecoTemp').classList.toggle("active");
    document.getElementById('confirmacaoEnderecoTemp').value = '';
    document.getElementById('confirmacaoEnderecoTemp').focus();
}

const confirmaEnderecoTemp = () => {
    let inputNovoEnderecoTemp = document.getElementById('confirmacaoEnderecoTemp').value;
    if(inputNovoEnderecoTemp != '') {
        if(inputNovoEnderecoTemp != inputInicialEnderecoTemp) {
            alert('Valores digitados não batem. Verifique a digitação');
        } else {
            togglePopupConfirmarEnderecoTemp();
            document.getElementById('enderecotemp').value = inputNovoEnderecoTemp;
            enderecoTempBipado = 'N';
        }
    } else {
        alert('Digite um valor.');
    }
}