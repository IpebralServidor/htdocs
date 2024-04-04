const openEditModal = (rowId) => {
    let cells = document.getElementById(rowId).cells;
    let codProduto = document.getElementById('codProduto').getAttribute('data-codprod');

    setEditModalValues(codProduto, cells[1].innerHTML, cells[0].innerHTML, cells[4].innerHTML);
}
    
const setEditModalValues = (codProduto, codLocalProduto, empresa, valorAtual) => {
    let regex = /[+-]?\d+(\.\d+)?/g;
    let vlrAtual = valorAtual.match(regex).map(function(str) { return parseFloat(str); });
    document.getElementById('novoMax').value = vlrAtual[0];
    document.getElementById('atualizaValorBtn').setAttribute('codProduto', codProduto);
    document.getElementById('atualizaValorBtn').setAttribute('codLocalProduto', codLocalProduto);
    document.getElementById('atualizaValorBtn').setAttribute('empresa', empresa);
}

const atualizarNovoValor = () => {
    let codProduto = document.getElementById('atualizaValorBtn').getAttribute('codProduto');
    let codLocalProduto = document.getElementById('atualizaValorBtn').getAttribute('codLocalProduto').match(/\d/g).join("");
    let empresa = document.getElementById('atualizaValorBtn').getAttribute('empresa');
    let novoValor = document.getElementById('novoMax').value;
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: '../Model/atualizarqtdmax.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            codProduto: codProduto,
            codLocalProduto: codLocalProduto,
            empresa: empresa,
            novoValor: novoValor
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            document.getElementById('fecharModal').click();
            location.reload();
        }
    });
}