$(document).ready(function() {
    let nunota = document.getElementById('searchInput').value;    
    buscaNotasContagem(nunota);
});

const buscaNotasContagem = () => {
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
            route: 'buscaNotasContagem'
        },
        success: function(response) {
            
          
            if(response.success || response.success === '') {
                document.getElementById('listaNotas').innerHTML = response.success;
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

const confirmaAbrirContagem= (row) => {
    const nunota = row.id;
    const tipo = row.getAttribute('data-tipo');
    const status = row.getAttribute('data-status');   
    let confirmMsg;
    if(status === 'D') {
        confirmMsg = `Deseja abrir contagem o N° ${nunota}?`;
    } else if(status.includes('C')) {
        confirmMsg = `Contagem já concluída. Deseja abrir nova contagem p/N° ${nunota}?`;
    }
    const confirmacao = (status === 'D' || status.includes('C')) ? confirm(confirmMsg) : true;
    if(confirmacao) {
        window.location.href = `./Contagem.php?nunota=${nunota}&tipo=${tipo}`;
    }
}

const confirmaAbrirContagemFiltro= () => {
    const nunota = document.getElementById('searchInput').value; 
    const tabela = document.getElementById('listaNotas');  
    if (Array.from(tabela.rows).map(linha => linha.id).includes(nunota)) {
        confirmaAbrirContagem(document.getElementById(nunota));
    } else{
        alert("Por favor, insira um número válido."); 
    }
}