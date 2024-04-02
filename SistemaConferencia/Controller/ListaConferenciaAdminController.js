$(document).ready(function() {
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
    $('.checkbox').on('click', function() {
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $('#select_all').prop('checked', true);
        } else {
            $('#select_all').prop('checked', false);
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
        }
    });
}