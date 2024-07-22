addEventListener("load", function() {
    setTimeout(hideURLbar, 0);
}, false);

function hideURLbar() {
    window.scrollTo(0, 1);
} 

$(document).ready(function() {

    if (sessionStorage.getItem('status') == 'P') {
        $("#result_shops").load('../Model/time.php');
    }

    var t = window.setInterval(function() {
        if (sessionStorage.getItem('status') == 'A') {
            $("#result_shops").load('../Model/time.php');
        }

    }, 1000);
    
});

function atualizarContador(tempoAtualSegundos) {
    var horas = Math.floor(tempoAtualSegundos / 3600);
    var minutos = Math.floor((tempoAtualSegundos % 3600) / 60);
    var segundos = tempoAtualSegundos % 60;
    document.getElementById("contador").innerHTML = horas.toString().padStart(2, "0") + ":" + minutos.toString().padStart(2, "0") + ":" + segundos.toString().padStart(2, "0");
}

function obterTempoAtual() {
    var tempoAtualSegundos = 178542;
    atualizarContador(tempoAtualSegundos);
}

// Atualiza o contador a cada segundo
setInterval(obterTempoAtual, 1000);

// Chama a função pela primeira vez para obter o tempo atual
obterTempoAtual();

jQuery(document).ready(function($) {
    $(".scroll").click(function(event) {
        event.preventDefault();
        $('html,body').animate({
            scrollTop: $(this.hash).offset().top
        }, 1000);
    });
});

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
});

function delete_confirm() {
    if ($('.checkbox:checked').length > 0) {
        var result = confirm("Tem certeza que deseja apagar esse(s) item(ns)?");
        if (result) {
            return true;
        } else {
            return false;
        }
    } else {
        alert('Selecione pelo menos uma linha para poder excluir!');
        return false;
    }
}

function insere_pendencia() {
    if ($('.checkbox:checked').length > 0) {
        var result = confirm("Tem certeza que deseja inserir esse(s) item(ns)?");
        if (result) {
            return true;
        } else {
            return false;
        }
    } else {
        alert('Selecione pelo menos uma linha para poder inserir!');
        return false;
    }
}

function abrirpendencias() {
    document.getElementById('popuppendencias').style.display = 'block';
}

function fecharpendencias() {
    document.getElementById('popuppendencias').style.display = 'none';
}

function abrirdivergencias() {
    document.getElementById('popupdivergencias').style.display = 'block';
}

function fechardivergencias() {
    document.getElementById('popupdivergencias').style.display = 'none';
}

function abrirconf() {
    document.getElementById('popupconf').style.display = 'block';
}

function fecharconf() {
    document.getElementById('popupconf').style.display = 'none';
}

function abrirObs() {
    document.getElementById('popupObservacao').style.display = 'block';
}

function fecharObs() {
    document.getElementById('popupObservacao').style.display = 'none';
}

function abrirconfdivergencia() {
    document.getElementById('popupconfdivergencia').style.display = 'block';
}

function fecharconfdivergencia() {
    document.getElementById('popupconfdivergencia').style.display = 'none';
}

function abrirconfdivcorte() {
    document.getElementById('popupconfdivcorte').style.display = 'block';
}

function fecharconfdivcorte() {
    document.getElementById('popupconfdivcorte').style.display = 'none';
}

function abrirconfrecontagem() {
    document.getElementById('popupconfrecontagem').style.display = 'block';
}

function fecharconfrecontagem() {
    document.getElementById('popupconfrecontagem').style.display = 'none';
}


function fecharconfdivpendencia() {
    document.getElementById('popuppendencias').style.display = 'none';
}

function abrirErroQtd() {
    document.getElementById('ErroQtdMaior').style.display = 'block';
}

function fecharErroQtd() {
    document.getElementById('ErroQtdMaior').style.display = 'none';
}

function abrirImpressaoEtiqueta() {
    document.getElementById('popupetiqueta').style.display = 'block';
}

function abrirpopupprodmultiploslocais() {
    document.getElementById('popupprodmultiploslocais').style.display = 'block';
}

function fecharpopupprodmultiploslocais() {
    document.getElementById('popupprodmultiploslocais').style.display = 'none';
}

function abrirPopAutorizaCorte() {
    document.getElementById('popAutorizaCorte').classList.toggle("active");
    document.getElementById('userCorte').value = '';
    document.getElementById('senhaCorte').value = '';
}

function abrirpopupverificacorte() {
    document.getElementById('popupverificacorte').style.display = 'block';
}

function fecharpopupverificacorte() {
    document.getElementById('popupverificacorte').style.display = 'none';
}

function validaNumeroInserido() {
    let value = document.getElementById('qtdImpressao').value;
    if(value > 0) {
        const urlParams = new URLSearchParams(window.location.search);
        const nunota = urlParams.get('nunota');
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: '../Model/atualizaqtdvol.php',
            data: {
                nunota: nunota,
                atualizaQtdVol: value
            },
            success: function() {
                fecharImpressaoEtiqueta();
                impressao('etiqueta');
            }
        });
    } else {
        alert("Insira um número válido!");
    }
}

function fecharImpressaoEtiqueta() {
    document.getElementById('popupetiqueta').style.display = 'none';
}


function abrirconferentes() {
    document.getElementById('popupconferentes').style.display = 'block';
    var btns = document.getElementsByClassName('conferente-btn');
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener('click', function() {
            var nunota = "<?php echo $nunota2; ?>"
            var user = this.getAttribute('data-user');
            atribuirseparador(user, nunota);
        });
    }
}

function fecharconferentes() {
    document.getElementById('popupconferentes').style.display = 'none';
}

function abrirRecontagem(nomeTabela) {
    const urlParams = new URLSearchParams(window.location.search);
    const nunota = urlParams.get('nunota');
    let linhasRecontar = [];
    $("#"+nomeTabela+" input[type=checkbox]:checked").map(function() {
        let row = $(this).closest("tr")[0];
        linhasRecontar.push(
            {
                "CODPROD": $("#" + row.id + " > .codprod").html(),
                "CONTROLE": $("#" + row.id + " > .controle").html().trim()
            }
        );
    });
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '../Model/abrirrecontagem.php',
        data: {
            nunota: nunota,
            linhasRecontar: linhasRecontar
        },
        success: function(msg) {
            alert(msg);
            location.reload();
        }
    });
}

function autorizaCorte() {
    let user = document.getElementById('userCorte').value;
    let senha = document.getElementById('senhaCorte').value;
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '../Model/autorizacorte.php',
        data: {
            user: user,
            senha: senha
        },
        success: function(msg) {
            if(msg === 'erro') {
                alert('Não foi possível autorizar.');
            } else {
                abrirPopAutorizaCorte();
                document.getElementById('codusulib').value = msg;
                abrirpopupverificacorte();
            }
        }
    });
}

function abrirVolumeBtn () {
    const urlParams = new URLSearchParams(window.location.search);
    const nunota = urlParams.get('nunota');
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '../Model/abrirvolume.php',
        data: {
            nunota: nunota
        },
        success: function(msg) {
            document.getElementById('abrirVolumeBtn').innerHTML = msg;
        }
    });
}
