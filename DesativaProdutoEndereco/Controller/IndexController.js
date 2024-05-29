const urlParams = new URLSearchParams(window.location.search);
const codemp = urlParams.get('codemp');

$(document).ready(function() {
    aplicarFiltro();
});

const aplicarFiltro = () => {
    $.ajax({
        type: 'POST', 
        dataType: 'HTML', 
        url: '../Model/retornaprodutos.php', 
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            codemp: codemp
        },
        success: function(listaProdutos) {
            document.getElementById('produtos').innerHTML = listaProdutos;
        }
    });
}

const openDesativaModal = (currentRow) => {
    let campoEndereco = document.getElementById('enderecoProduto');
    let campoReferencia = document.getElementById('referenciaProduto');

    document.getElementById('semcaixa').checked = false;
    document.getElementById('referenciaProduto').disabled = false;
    document.getElementById('codprodatual').value = currentRow.id;
    campoEndereco.placeholder = document.getElementById(currentRow.id+'end').innerHTML.trim();
    campoReferencia.placeholder = document.getElementById(currentRow.id+'ref').innerHTML.trim();
    campoEndereco.value = '';
    campoReferencia.value = '';

}

const desativaProduto = () => {
    if(document.getElementById('enderecoProduto').value != document.getElementById('enderecoProduto').placeholder) {
        alert('Digite o endereço correto!');
    } else if(document.getElementById('semcaixa').checked == false && document.getElementById('referenciaProduto').value != document.getElementById('referenciaProduto').placeholder) {
        alert('Digite a referência correta!');
    } else {
        $.ajax({
            type: 'POST', 
            dataType: 'HTML', 
            url: '../Model/desativalocalpad.php', 
            beforeSend: function() {
                $("#loader").show();
            },
            complete: function() {
                $("#loader").hide();
            },
            data: {
                codprod: document.getElementById('codprodatual').value,
                codemp: codemp
            },
            success: function(msg) {
                alert(msg);
                aplicarFiltro();
            }
        });
    }
}

const handleCheck = (checkbox) => {
    if(checkbox.checked) {
        document.getElementById('referenciaProduto').disabled = true;
    } else {
        document.getElementById('referenciaProduto').disabled = false;
    }
}