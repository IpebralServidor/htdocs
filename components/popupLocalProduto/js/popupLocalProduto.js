$(document).ready(function() {
    $('#modalLocalProduto').load('../../components/popupLocalProduto/html/popupLocalProduto.html', function() {
        // Após o conteúdo ser carregado, adiciono o link para o CSS
        $('head').append('<link rel="stylesheet" type="text/css" href="../../components/popupLocalProduto/css/popupLocalProduto.css">');
    });
});

const verificaLocaisComProduto = (referencia, codemp) => {
    $.ajax({
        url: '../../components/popupLocalProduto/php/verificaLocaisComProduto.php', 
        type: 'GET',
        dataType: 'json',
        data: { 
            referencia: referencia,
            codemp: codemp
        },
        success: function(response) {
            if (response.existe) {
                exibirExclamacao(response.locais);
            } else {
                removerExclamacao();
            }
        },
        error: function(xhr, status, error) {
            console.log('Erro ao fazer a requisição AJAX:', error);
        }
    });
}

const exibirExclamacao = (locais) => {
    removerExclamacao();
    const exclamacao = $('<i>')
        .addClass('fa-solid fa-circle-exclamation')
        .css({
            fontSize: '25px',
            cursor: 'pointer',
            color: 'red',
            display: 'inline',
            'white-space': 'nowrap'
        })
        .on('click', function() {
            exibirPopup(locais);
        });
    
    $('#popupLocalProduto').append(exclamacao);

}

function removerExclamacao() {
    $('#popupLocalProduto .fa-circle-exclamation').remove();
}

function exibirPopup(locais) {
    document.getElementById('locaisProduto').innerHTML = locais;
    let locaisModal = new bootstrap.Modal(document.getElementById('mostraLocaisProduto'));
    locaisModal.show();
}
