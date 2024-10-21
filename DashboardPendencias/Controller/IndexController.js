// Função executada quando o DOM estiver pronto
$(function() {
    buscaPendencias(null, null);
    const nunotaInput = document.getElementById('nunota');
    const codparcInput = document.getElementById('codparc');
    
    // Adiciona um evento de escuta para a tecla 'Enter'
    nunotaInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            confirmaFiltroPendencia();
        }
    });

    codparcInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            confirmaFiltroPendencia();
        }
    });
});

const recarregarPagina = () => {
    location.reload();
}

// Define um tempo aleatório entre 60 e 120 segundos (60000 a 120000 milissegundos)
const tempoRecarregar = Math.floor(Math.random() * (120000 - 60000 + 1)) + 60000;

// Chama a função recarregarPagina após o tempo definido
setTimeout(recarregarPagina, tempoRecarregar);

const buscaPendencias = (nunota, codparc) => {
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
            nunota: nunota,
            codparc: codparc,
            route: 'buscaPendencias'
        },
        success: function(response) {
            if(response.success || response.success === '') {
                document.getElementById('pendencias').innerHTML = response.success;
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

const abrirPopFiltroPendencia = () => {
    document.getElementById('nunota').value = '';
    document.getElementById('codparc').value = '';
    document.getElementById('popFiltroPendencia').classList.toggle("active");
}

const confirmaFiltroPendencia = () => {
    let nunota = document.getElementById('nunota').value;
    let codparc = document.getElementById('codparc').value;
    if((nunota !== '' && !isNaN(nunota)) || (codparc !== '' && !isNaN(codparc))) {
        buscaPendencias(nunota !== '' ? nunota : null, codparc !== '' ? codparc : null);
        abrirPopFiltroPendencia();
    } else {
        buscaPendencias(null, null);
        abrirPopFiltroPendencia();
    }
}