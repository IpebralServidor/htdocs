$(document).ready(function() {
    let codemp = document.getElementById('empresaInventario').value;
    buscaEnderecosInventario(codemp, -1, -1, 'C');
});

const buscaEnderecosInventario = (codemp, endini, endfim, concluidos) => {
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
            endini: endini,
            endfim: endfim,
            concluidos: concluidos,
            route: 'buscaEnderecosInventario'
        },
        success: function(response) {
            if(response.success || response.success === '') {
                document.getElementById('enderecosInventario').innerHTML = response.success;
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

const confirmaAbrirInventario = (row) => {
    const codlocal = row.cells[0].innerHTML;
    const status = row.cells[4].innerHTML;
    const codemp = row.cells[5].innerHTML;
    let confirmMsg;
    if(status === 'D') {
        confirmMsg = `Deseja abrir inventário para o local ${codlocal}?`;
    } else if(status.includes('C')) {
        confirmMsg = `Inventário já concluído. Deseja abrir novo inventário para o local ${codlocal}?`;
    }
    const confirmacao = (status === 'D' || status.includes('C')) ? confirm(confirmMsg) : true;
    if(confirmacao) {
        window.location.href = `./inventario.php?codemp=${codemp}&codlocal=${codlocal}`;
    }
}

const abrirPopFiltroInventario = () => {
    document.getElementById('popFiltroInventario').classList.toggle("active");
}

const confirmaFiltroInventario = () => {
    let enderecoInicio = document.getElementById('endIni').value;
    let enderecoFim = document.getElementById('endFim').value;
    let codEmp = document.getElementById('empresaInventario').value;
    let concluidos = document.getElementById('mostrarConcluidos').checked === true ? 'Z' : 'C';
    if(enderecoInicio !== '' && enderecoFim !== '') {
        if (!isNaN(enderecoInicio) && enderecoInicio !== '') {
            if (!isNaN(enderecoFim) && enderecoFim !== '') {
                buscaEnderecosInventario(codEmp, enderecoInicio, enderecoFim, concluidos);
                abrirPopFiltroInventario();
            } else {
                alert('Insira um endereço fim válido!');
            }
        } else {
            alert('Insira um endereço de início válido!');
        }
    } else {
        buscaEnderecosInventario(codEmp, -1, -1, concluidos);
        abrirPopFiltroInventario();
    }
}

const mostraBloqueio = (codlocal) => {
    let codemp = document.getElementById('empresaInventario').value;
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
            codlocal: codlocal,
            codemp: codemp,
            route: 'mostraBloqueio'
        },
        success: function(response) {
            if(response.success) {
                document.getElementById('notasBloqueio').innerHTML = response.success;
                let bloqueioModal = new bootstrap.Modal(document.getElementById('mostraBloqueio'));
                bloqueioModal.show();
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