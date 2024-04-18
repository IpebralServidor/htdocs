function abrirEscolherNota() {
    document.getElementById('popupEscolherNota').style.display = 'flex';
}

function fecharEscolherNota() {
    document.getElementById('popupEscolherNota').style.display = 'none';
}

function abrirCriarNotaTransf() {
    document.getElementById('popupCriaNotaTransf').style.display = 'flex';
}

function fecharCriarNotaTransf() {
    document.getElementById('popupCriaNotaTransf').style.display = 'none';
}

function criaNotaTransf(nunota, cddestino, usuario) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/crianotatransf.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nunota: nunota,
            cddestino: cddestino,
            usuario: usuario
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            if (msg.length <= 10) {
                abrirNota(msg);
            } else {
                alert(msg);
            }
        }
    });
}

//Recarrega o POP UP de Escolher a nota
function retornaEscolherNota(nunota) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/retornaescolhernota.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#escolherNota").html("Carregando...");
        },
        data: {
            nunota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            $("#escolherNota").html(msg);
        }
    });
}

$('#abrirNotabtn').click(function() {
    abrirEscolherNota();
    retornaEscolherNota($("#nunota").val());
});

//Recarrega o POP UP de Criação de Notas
function retornaCriarNota(nunota) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/retornacriarnota.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#criarNota").html("Carregando...");
        },
        data: {
            nunota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            $("#criarNota").html(msg);
        }
    });
}

$('#gerarNovaTransf').click(function() {
    abrirCriarNotaTransf();
    retornaCriarNota($("#nunota").val());
});

// Função para abrir a nota
function abrirNota(nota) {
    window.location.href = 'insereestoque.php?nunota=' + nota;
}
// Fim da função para abrir a nota

// Cria a nota de transferência, baseado no botão do CD que foi criado
function criaNota(nunota, cddestino, usuario) {
    result = confirm("Tem certeza que deseja criar a transferência para o CD" + cddestino + "?");
    if (result) {
        criaNotaTransf(nunota, cddestino, usuario);
    }

}
// Fim cria a nota de transferência, baseado no botão do CD que foi criado