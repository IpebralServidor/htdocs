const urlParams = new URLSearchParams(window.location.search);
const nunota = urlParams.get('nunota');

$(document).ready(function() {
    $.ajax({
        //Configurações
        type: 'GET', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/listaverificacaoprodutos.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nunota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(produtos) {
            document.getElementById('produtosList').innerHTML = produtos;
        }
    });
    $.ajax({
        //Configurações
        type: 'GET', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/buscarqtditens.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nunota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(qtdItens) {
            document.getElementById('qtdItens').innerHTML = qtdItens;
        }
    });
    $.ajax({
        //Configurações
        type: 'GET', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/buscartipotransf.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nunota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(tipoTransf) {
            var tr = document.getElementById('productsTable').tHead.children[0];
            if(tipoTransf == 'TRANSFPRODSAIDA') {
                tr.insertCell(3).outerHTML = 
                "<th> <button class='btnEntregaPegaTudo' data-toggle='modal' data-target='#entregaModal'>Entregar </button></th>";
            } else if(tipoTransf == 'TRANSFPRODENTRADA') {
                tr.insertCell(3).outerHTML = 
                "<th><button class='btnEntregaPegaTudo' data-toggle='modal' data-target='#pegaModal'>Pegar</button></th>";
            } else {
                tr.insertCell(3).outerHTML = "<th></th>";
            }
        }
    });
});

document.body.addEventListener("load", encerrarAtividade(nunota));

// Obtém o botão "Abrir Pop-up" e o pop-up
document.addEventListener('DOMContentLoaded', function() {
    var botoesAbrirPopUp = document.querySelectorAll(".botaoAbrirPopUp");
    var meuPopUp = document.getElementById("editarQuantidade");
    var botaoDentroDoPopUp = meuPopUp.querySelector("#btnEditarQuantidade");

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
    var botaoEditar = document.querySelectorAll('.botao-editar');
    var inputTexto = document.getElementById("qtd");

    botaoEditar.forEach(function(botao) {
        botao.addEventListener('click', function() {
            var sequencia = botao.getAttribute('data-id-pop-up');
            var valorTexto = inputTexto.value;

            alterarQuantidade(nunota, sequencia, valorTexto);
        });
    });
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

            abastecerGondola(nunota, sequencia, codusu);
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

function encerrarAtividade(nunota) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/encerraratividade.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nunota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {}
    });
}

function confirmarNota(nunota) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/confirmarnota.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nunota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            if (msg == 'Concluido') {
                alert(msg);
                window.location.href = "index.php";
            } else {
                alert(msg);
            }

        }
    });
}

$('#confirmar').click(function() {
    confirmarNota(nunota)
});

function buscarUsuario(codusu) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/buscarusuario.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        data: {
            codusu: codusu
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            var retorno = msg.split("|");
            document.getElementById("nomeusu").placeholder = retorno[1];
        }
    });
}
$('#buscar-usuario').click(function() {
    buscarUsuario($("#codusuinput").val())
});

function alterarQuantidade(nunota, sequencia, quantidade) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/alterarquantidade.php', //Indica a página que está sendo solicitada.
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
            quantidade: quantidade
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            if (msg == 'Concluido') {
                location.reload();
            } else {
                alert(msg);
            }
        }
    });
}

function abastecerGondola(nunota, sequencia, codusu) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/abastecergondola.php', //Indica a página que está sendo solicitada.
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
            codusu: codusu
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            if (msg == 'Concluido') {
                location.reload();
            } else {
                alert(msg);
            }
        }
    });
}

function abastecerTudo(nunota) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/abastecertudo.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nunota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            if (msg == 'Concluido') {
                location.reload();
            } else {
                alert(msg);
            }
        }
    });
}

$('#btnEntregarTudo').click(function() {
    abastecerTudo(nunota)
});