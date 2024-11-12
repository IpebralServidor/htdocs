function abrir() {
    let nunota = document.getElementById('numeroNota').value;
    if (nunota != "") {
        $.ajax({
            type: 'POST', //Método que está sendo utilizado.
            dataType: 'html', //É o tipo de dado que a página vai retornar.
            url: '../Model/buscarcodtipoper.php', //Indica a página que está sendo solicitada.
            async: false,
            beforeSend: function() {
                $("#loader").show();
            },
            complete: function() {
                $("#loader").hide();
            },
            data: {
                numeroNota: nunota
            }, //Dados para consulta
            //função que será executada quando a solicitação for finalizada.
            success: function(msg) {
                if(msg == -1) {
                    alert('Nota não existe ou já confirmada.');
                } else if (msg.substring(0, 2) == 13) {
                    $.ajax({
                        type: 'POST', //Método que está sendo utilizado.
                        dataType: 'html', //É o tipo de dado que a página vai retornar.
                        url: '../Model/buscarvinculo.php', //Indica a página que está sendo solicitada.
                        async: false,
                        beforeSend: function() {
                            $("#loader").show();
                        },
                        complete: function() {
                            $("#loader").hide();
                        },
                        data: {
                            numeroNota: nunota
                        }, //Dados para consulta
                        //função que será executada quando a solicitação for finalizada.
                        success: function(msg) {
                            if (msg == 0) {
                                document.getElementById('popConfirmar').classList.toggle("active");
                            } else {
                                Confirmar();
                            }
                        }
                    });
                } else {
                    alert('Esta nota não é uma transferência');
                }
            }
        });
    } else {
        alert('Preencha uma nota válida!');
    }
}

function Confirmar() {
    let nunota = document.getElementById('numeroNota').value;
    $.ajax({
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/action.php', //Indica a página que está sendo solicitada.
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            numeroNota: nunota
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            let res = msg.split('/');
            if (res[0] == "A") {
                if (res[1] == "TRANSF_NOTA") {
                    window.location.href = 'reabastecimentotransf.php?nunota=' + nunota + '&fila=N';
                } else {
                    window.location.href = 'menuseparacao.php?nunota=' + nunota;
                }
            } else if (res[1] == "TRANSF_CD5") {
                window.location.href = 'reabastecimento.php?nunota=' + nunota + '&fila=N';
            } else {
                window.location.href = 'menuendereco.php?nunota=' + nunota;
            }
        }
    });
}

function produtos() {
    let toggleSwitch = document.getElementById('chkInp');
    let tipoNota;
    let tipoTransf = document.getElementById('tipoTransf');
    let cdTransf = document.getElementById('cdTransf');
    let nomeFiltro = tipoTransf.options[tipoTransf.selectedIndex].text;
    if(cdTransf.value != 'N') {
        if(tipoTransf.value === 'TRANSFAPP' || tipoTransf.value === 'TRANSF_PENDENCIA') {
            nomeFiltro += ' CD' + cdTransf.value;
        }
    }
    document.getElementById('filterName').textContent = nomeFiltro;
    // Observa o switch de Armazenamento/Separação e define o tipo da nota. A = Armazenamento, S = Separação
    if (toggleSwitch.checked == true) {
        document.getElementById('titleBoxH6').textContent = 'Notas de armazenamento';
        tipoNota = 'A';
    } else {
        document.getElementById('titleBoxH6').textContent = 'Notas de separação';
        tipoNota = 'S';
    }
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/notas.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#iniciarpausa").html("Carregando...");
        },
        data: {
            tipoNota: tipoNota,
            tipoTransf: tipoTransf.value,
            cdTransf: cdTransf.value
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            var notas = msg.split('|');
            document.getElementById('prodId').innerHTML = notas[0]
        }
    });
}

function atribuirDataBotao(button) {
    document.getElementById("numeroNota").value = (button.getAttribute('data-id'));
};

const abrirPopFiltroNota = () => {
    document.getElementById('popFiltroNota').classList.toggle("active");
}