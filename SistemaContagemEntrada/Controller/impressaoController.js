const urlParams = new URLSearchParams(window.location.search);
const tipo = urlParams.get('tipo');

$(document).ready(function() {
    buscaListaImpressao();
});

const buscaListaImpressao = () => {
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
            route: 'buscaListaImpressao',
            tipo: tipo
        },
        success: function(response) {
            if(response.success || response.success === '') {
                document.getElementById('listaImpressao').innerHTML = response.success;
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
