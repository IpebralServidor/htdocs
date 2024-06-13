$(document).ready(function() {
    // Seleciona todas as checkboxs caso haja um clique na checkbox do cabeçalho
    $('#select_all').on('click', function() {
        if (this.checked) {
            $('.checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $('.checkbox').each(function() {
                this.checked = false;
            });
        }
    });
    // Simula um clique na checkbox do cabeçalho caso seja clicado ao redor dela
    $('#headerCheckbox').on('click', function(event) {
        if(event.target.id === 'headerCheckbox') {
            $('#select_all').click();
        }
    });
    
    $('#parceiroFiltro, #numnotaFiltro, #nunotaFiltro, #statusFiltro, #filtroEmpresas, #dtIniFiltro, #dtFimFiltro').on('keypress', function(event) {
        if(event.keyCode === 13) {
            aplicarFiltro();
        }
    });
    $('#nunotaImpressao').on('keypress', function(event) {
        if(event.keyCode === 13) {
            confirmarPopImpressao();
        }
    });
    $.ajax({
        //Configurações
        type: 'GET', //Método que está sendo utilizado.
        dataType: 'text', //É o tipo de dado que a página vai retornar.
        url: '../Model/pegarlistaempresas.php', //Indica a página que está sendo solicitada.
        success: function(listaEmpresas) {
            document.getElementById('filtroEmpresas').innerHTML = listaEmpresas;
            aplicarFiltro();
        }
    });
});

function search_conferente() {
    let input = document.getElementById('searchbar').value
    input = input.toLowerCase();
    let x = document.getElementsByClassName('conferentes');
    for (i = 0; i < x.length; i++) {
        if (!x[i].innerHTML.toLowerCase().includes(input)) {
            x[i].style.display = "none";
        } else {
            x[i].style.display = "list-item";
        }
    }
}

function abrirconf() {
    $.ajax({
        //Configurações
        type: 'GET', //Método que está sendo utilizado.
        dataType: 'json', //É o tipo de dado que a página vai retornar.
        url: '../Model/pegarcodconferentes.php', //Indica a página que está sendo solicitada.
        success: function(codConferentes) {
            document.getElementById("codconferentes").value = codConferentes[0];
            document.getElementById('popupconf').style.display = 'block';
        }
    });
}

function abrirconferentes() {
    $.ajax({
        //Configurações
        type: 'GET', //Método que está sendo utilizado.
        dataType: 'text', //É o tipo de dado que a página vai retornar.
        url: '../Model/pegarlistaconferentes.php', //Indica a página que está sendo solicitada.
        success: function(listaConferentes) {
            document.getElementById('usersList').innerHTML = listaConferentes;
            document.getElementById('popupconferentes').style.display = 'block';
            setConferentesActions(); // Atribui ações de click aos botões da lista de conferentes 
        }
    });
}

function setConferentesActions() {
    // Adicionar evento de clique aos botões do conferente
    var btns = document.getElementsByClassName('conferente-btn');
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener('click', function() {
            var checkedCheckboxes = document.querySelectorAll('#ListaConferencia tbody .checkbox:checked');
            if (checkedCheckboxes[0] == null) {
                alert('Selecione pelo menos uma nota');
            } else {
                var user = this.getAttribute('data-user');
                var notas = "";
                for (var i = 0; i < checkedCheckboxes.length; i++) {
                    var nota = checkedCheckboxes[i].getAttribute('data-nota');
                    notas += nota;
                    if (i < checkedCheckboxes.length - 1) {
                        notas += "/"
                    }
                }
                fazerUpdateNoBanco(notas, user);
            };
            window.location.href = 'listaconferenciaadmin.php';
            $("#aplicar").click();
        });
    }
}

function fecharconferentes() {
    document.getElementById('popupconferentes').style.display = 'none';
}

function fecharatribuicao() {
    document.getElementById('popupconf').style.display = 'none';
}

function fazerUpdateNoBanco(notas, usuario) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/cadastrarnotas.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            notas: notas,
            usuario: usuario
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            alert(msg);
            window.location.href = 'listaconferenciaadmin.php';

        }
    });
}

function aplicarFiltro() {
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/listarconferenciasadmin.php', //Indica a página que está sendo solicitada.
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
            filtroEmpresas: document.getElementById('filtroEmpresas').value,
            dtIniFiltro: document.getElementById('dtIniFiltro').value,
            dtFimFiltro: document.getElementById('dtFimFiltro').value,
            parceiroFiltro: document.getElementById('parceiroFiltro').value
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(listaConferencias) {
            document.getElementById('conferenciasList').innerHTML = listaConferencias;
            // Confere se foi a última checkbox de linha checada para checar também a checkbox do cabeçalho
            $('.checkbox').on('click', function() {
                if ($('.checkbox:checked').length == $('.checkbox').length) {
                    $('#select_all').prop('checked', true);
                } else {
                    $('#select_all').prop('checked', false);
                }
            });
            // Checa a checkbox caso tenha sido clicado fora dela
            $('.outerCheckbox').on('click', function(event) {
                if($(event.target).attr('class') === 'outerCheckbox') {
                    let checkbox = $(this).find("input:checkbox");
                    checkbox.is(':checked') === false ? checkbox.prop("checked", true) : checkbox.prop("checked", false);

                    // Confere se foi a última checkbox de linha checada para checar também a checkbox do cabeçalho
                    if ($('.checkbox:checked').length == $('.checkbox').length) {
                        $('#select_all').prop('checked', true);
                    } else {
                        $('#select_all').prop('checked', false);
                    }
                }
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
        case 'numStr':
            return value.replace(/[0-9]/g, '');
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