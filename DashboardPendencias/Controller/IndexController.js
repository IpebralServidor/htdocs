// Função executada quando o DOM estiver pronto
$(function() {
    
    //Função para salvar os filtros caso esteja filtrado
    const filtroSalvo = JSON.parse(sessionStorage.getItem('filtroPendencia')) || {};
    
    buscaPendencias(
        filtroSalvo.nunota || null, 
        filtroSalvo.codparc || null, 
        filtroSalvo.codemp || null
    );

    const nunotaInput = document.getElementById('nunota');
    const codparcInput = document.getElementById('codparc');
    const codempInput = document.getElementById('codemp');
    
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

    codempInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            confirmaFiltroPendencia();
        }
    });
});

const recarregarPagina = () => {
    location.reload();
}

setTimeout(recarregarPagina, 15000);

const buscaPendencias = (nunota, codparc, codemp) => {
    // Salva os filtros antes de buscar
    sessionStorage.setItem('filtroPendencia', JSON.stringify({
        nunota: nunota,
        codparc: codparc,
        codemp: codemp
    }));

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
            codemp: codemp,
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
    
    const filtroSalvo = JSON.parse(sessionStorage.getItem('filtroPendencia')) || {};
    
    document.getElementById('nunota').value = filtroSalvo.nunota || '';
    document.getElementById('codparc').value = filtroSalvo.codparc || '';
    document.getElementById('codemp').value = filtroSalvo.codemp || '';

    document.getElementById('popFiltroPendencia').classList.toggle("active");
}

const confirmaFiltroPendencia = () => {
    let nunota = document.getElementById('nunota').value;
    let codparc = document.getElementById('codparc').value;
    let codemp = document.getElementById('codemp').value;
    
    // Busca com ou sem filtros
    buscaPendencias(
        nunota !== '' ? nunota : null, 
        codparc !== '' ? codparc : null,
        codemp !== '' ? codemp : null
    );
    abrirPopFiltroPendencia();
}