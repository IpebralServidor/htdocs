$(document).ready(function() {
    // Evento de clique na linha da tabela
    $('#ListaConferencia tr').dblclick(function() {
        // Obtém o ID da linha clicada
        var nota = this.getAttribute('data-nota');
        //Enviar o dado via AJAX para o servidor
        $.ajax({
            //Configurações
            type: 'POST', //Método que está sendo utilizado.
            dataType: 'html', //É o tipo de dado que a página vai retornar.
            url: '../Model/iniciarconferencia.php', //Indica a página que está sendo solicitada.
            //função que vai ser executada assim que a requisição for enviada
            beforeSend: function() {
                $("#loader").show();
            },
            complete: function() {
                $("#loader").hide();
            },
            data: {
                nota: nota
            }, //Dados para consulta
            //função que será executada quando a solicitação for finalizada.
            success: function(msg) {
                if (msg == 'errado') {
                    alert('Fature a nota pelo SANKHYA')
                } else if (msg.length <= 10) {
                    window.location.href = 'detalhesconferencia.php?nunota=' + msg + '&codbarra=0';
                } else {
                    alert(msg);
                }

            }
        });

    });
});

function pegarProximaNota(usuario) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/proximanota.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            usuario: usuario
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            if (msg == 'C') {
                window.location.href = 'listaconferencia.php';
                $("#aplicar").click();
            } else if (msg == 'N') {
                alert('IPB: Não existe nota para ser pega');
            } else {
                alert('IPB: Existem notas que não foram concluídas. Não é possível pegar uma nova nota');
            }
        }
    });
}