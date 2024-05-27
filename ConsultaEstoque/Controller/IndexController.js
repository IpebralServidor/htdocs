$(document).keypress(function(e) {
    if(e.which == 13) {
        pesquisaProduto();
    }
});

const abrirprodutos = () => {
    document.getElementById('popupprodutos').style.display = 'block';
}

const fecharprodutos = () => {
    document.getElementById('popupprodutos').style.display = 'none';
}

const pesquisaProduto = () => {
    let referencia = document.getElementById("referencia").value.trim();
    if(referencia === '') {
        alert("Digite algo válido!");
    } else {
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: '../Model/consultaproduto.php',
            data: {
                referencia: referencia
            },
            beforeSend: function() {
                $("#loader").show();
            },
            complete: function() {
                $("#loader").hide();
            },
            success: function(retorno) {
                let tableProdutos = document.getElementById('produtos');
                tableProdutos.innerHTML = retorno;
                let qtdProdutos = tableProdutos.rows.length;
                if(qtdProdutos === 0) {
                    alert('Não existem produtos para essa pesquisa.');
                } else if(qtdProdutos === 1) {
                    chamaTelaConsulta(document.getElementById('produtos').rows[0].id);
                } else {
                    abrirprodutos();
                }
            }
        });
    }
}

const chamaTelaConsulta = (codprod) => {
    window.location.href="./consulta.php?codprod=" + codprod;
}