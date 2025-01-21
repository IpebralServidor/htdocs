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
    let referencia = document.getElementById("search-input").value.trim();

    
    //alert(referencia);

    referencia = referencia.replaceAll(' ', '%');
    if(referencia === '') {
        alert("Digite algo válido!");
    } else {
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: 'consultaproduto.php',
            cache: false,
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
                // } else if(qtdProdutos === 1) {
                //     chamaTelaConsulta(document.getElementById('produtos').rows[0].id);
                } else {
                    abrirprodutos();
                }
            }
        });
    }

}

// const chamaTelaConsulta = (codprod) => {
//     window.location.href="../consulta.php?codprod=" + codprod;
// }

// //Faz o evento de clique de botão após pesquisar um item específico
// const popupTable = document.getElementById('produtos');
// //const selectedId = this.getAttribute('data-id');

// popupTable.addEventListener('click', function(event) {
//     if (event.target.tagName === 'TD') {
//         const itemId = event.target.getAttribute('data-popup');
//         //const idAdicionar = event.target.getAttribute('id-popup');
//         const idTabela = window.idSelecionado; // ID da tabela principal
        
//         //alert('teste');

//         //alert(idTabela + ' / ' + itemId);
//         //alert(itemId);
//         //alert(idTabela + ' / '+ idAdicionar);

//         //alert(id);


//             insertItem(idTabela, itemId);

//             fecharprodutos();

//     }
// });

document.addEventListener('click', function(event) {
    if (event.target.closest('#produtos td')) {
        const itemId = event.target.getAttribute('data-popup');
        const idTabela = window.idSelecionado;

        if (idTabela && itemId) {
            insertItem(idTabela, itemId);
            fecharprodutos();
        } else {
            alert('Erro: Não foi possível identificar o item ou a tabela.');
        }
    }
});


 function insertItem(idTabela, itemId) {
            //O método $.ajax(); é o responsável pela requisição
            $.ajax({
                //Configurações
                type: 'POST', //Método que está sendo utilizado.
                dataType: 'html', //É o tipo de dado que a página vai retornar.
                url: './insereItemEscolhido.php', //Indica a página que está sendo solicitada.
                //função que vai ser executada assim que a requisição for enviada
                //method: "GET",
                cache: false,
                beforeSend: function() {
                    // $("#imagemproduto").html("Carregando...");
                },
                data: {
                    id: idTabela,
                    codprod: itemId
                }, //Dados para consulta
                //função que será executada quando a solicitação for finalizada.
                success: function(msg) {
                    // $("#imagemproduto").html(msg);
                    //alert(msg);
                    console.log('Item inserido com sucesso!');
                    alert('Item inserido!');
                    listaReferencia(idTabela);
                }
            });
        }



function openSidebar() {
    document.getElementById('search-container').classList.add('open');
}

function closeSidebar() {
    document.getElementById('search-container').classList.remove('open');
}