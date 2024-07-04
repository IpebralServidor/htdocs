$(document).ready(function() {
    // Evento de clique na linha da tabela
    $('#numnotaFiltro, #nunotaFiltro, #statusFiltro, #parceiroFiltro').on('keypress', function(event) {
        if(event.keyCode === 13) {
            aplicarFiltro();
        }
    });
    $('#nunotaImpressao').on('keypress', function(event) {
        if(event.keyCode === 13) {
            confirmarPopImpressao();
        }
    });
    aplicarFiltro();
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
                $("#aplicar").click();
            } else if (msg == 'N') {
                alert('IPB: Não existe nota para ser pega');
            } else {
                alert('IPB: Existem notas que não foram concluídas. Não é possível pegar uma nova nota');
            }
        }
    });
}

const aplicarFiltro = () => {
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/listarconferencias.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            document.getElementById("loader").style.display = "block";
            document.getElementById("loader").classList.add("loader");
        },
        complete: function() {
            document.getElementById("loader").style.display = "none";
            document.getElementById("loader").classList.remove("loader");
        },
        data: {
            numnotaFiltro: document.getElementById('numnotaFiltro').value,
            nunotaFiltro: document.getElementById('nunotaFiltro').value,
            statusFiltro: document.getElementById('statusFiltro').value,
            parceiroFiltro: document.getElementById('parceiroFiltro').value
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(listaConferencias) {
            document.getElementById('listaConferencias').innerHTML = listaConferencias;
            $('#listaConferencias tr').dblclick(function() {
                // Obtém o ID da linha clicada
                var nota = this.getAttribute('data-nota');
                $.ajax({
                    type: 'POST',
                    dataType: 'html',
                    url: '../Model/buscaseparador.php',
                    data: {
                        nota: nota
                    }, 
                    success: function(msg) {
                        if(msg === 'ok') {
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
                        } else {
                            abrirPopSeparador();
                            document.getElementById('notaSeparador').value = nota;
                        }
                    }
                });
            });
        }
    });
}

//Função para ordenamento da tabela ao clicar no cabeçalho
function sortTable(n, type) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("tableListaConferencia");
    switching = true;
    //Set the sorting direction to ascending:
    dir = "asc"; 
    /*Make a loop that will continue until
    no switching has been done:*/
    while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /*Loop through all table rows (except the
        first, which contains table headers):*/
        for (i = 1; i < (rows.length - 1); i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            /*Get the two elements you want to compare,
            one from current row and one from the next:*/
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /*check if the two rows should switch place,
            based on the direction, asc or desc:*/
            if (dir == "asc") {
                if (converteTipo(type, x.innerHTML) > converteTipo(type, y.innerHTML)) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch= true;
                    break;
                }
            } else if (dir == "desc") {
                if (converteTipo(type, x.innerHTML) < converteTipo(type, y.innerHTML)) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            /*If a switch has been marked, make the switch
            and mark that a switch has been done:*/
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            //Each time a switch is done, increase this count by 1:
            switchcount ++;      
        } else {
            /*If no switching has been done AND the direction is "asc",
            set the direction to "desc" and run the while loop again.*/
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
  }

// Converte os tipos para que a função retorne valores que possam ser comparados
function converteTipo (type, value) {
    switch(type) {
        case 'num':
            return parseFloat(value);
        case 'str':
            return value.toLowerCase();
        case 'vlr':
            return parseFloat(value.replace(',','.'));
        case 'date':
            return new Date(value.split('/').reverse().join('/'));
    }
}

function abrirPopImpressao() {
    document.getElementById('popImpressao').classList.toggle("active");
}

function confirmarPopImpressao() {
    let nunota = document.getElementById('nunotaImpressao').value;
    if(nunota) {
        window.location.href = "../Etiquetas/impressao.php?nunota=" + nunota;
    } else {
        alert("Digite um número único válido!");
    }
}

function abrirPopSeparador() {
    document.getElementById('popSeparador').classList.toggle("active");
}

function confirmarPopSeparador() {
    let codSeparador = document.getElementById('codSeparador').value;
    if(codSeparador) {
        let notaSeparador = document.getElementById('notaSeparador').value;
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: '../Model/atribuirseparador.php',
            data: {
                nunota: notaSeparador,
                separador: codSeparador
            }, 
            success: function(msg) {
                abrirPopSeparador();
                $("#listaConferencias tr[data-nota="+notaSeparador+"]").trigger("dblclick");
            }
        });
    } else {
        alert("Digite um código válido!");
    }
}