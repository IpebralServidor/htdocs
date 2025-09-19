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

const fechargera1700 = () => {
    document.getElementById('popupitens1700').style.display = 'none';
}

const pesquisaProduto = () => {

     let searchContainer = document.getElementById("search-container");

    // Só deixa rodar se o popup estiver aberto
    if (!searchContainer.classList.contains("open")) {
        return;
    }

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
                    alert(msg);
                    listaReferencia(idTabela);
                    atualizarContadorItens();
                    
                    // Aguarda um tempo para garantir que a tabela foi recarregada antes de buscar a última linha
                    setTimeout(() => {
                        
                        // Seleciona a linha correta na tabela #tableListaItens baseada no código do produto
                        const rowInTable2 = document.querySelector(`#tableListaItens tbody tr[data-codprod="${itemId}"]`);
                        
                        if (rowInTable2) {
                            console.log("Linha encontrada:", rowInTable2);
                            // Recupera os valores diretamente da linha na tabela de Itens das Referências
                            const selectedRef = rowInTable2.getAttribute('data-ref');
                            const selectedPreco = rowInTable2.getAttribute('data-price');
                            const selectedEstoque = rowInTable2.getAttribute('data-est');
                            const selectedCodProd = rowInTable2.getAttribute('data-codprod');

                            // Agora, encontramos a linha correspondente na tabela de retorno (#tableListaReferencias)
                            const rowInTable1 = document.querySelector(`#tableListaReferencias tbody tr[data-id="${idTabela}"]`);

                            if (rowInTable1) {
                                // Remove a seleção de outras linhas e adiciona a classe "selected" na linha correta
                                document.querySelectorAll('#tableListaReferencias tbody tr').forEach(r => r.classList.remove('selected'));
                                rowInTable1.classList.add('selected');

                                // Atualiza as células da linha correspondente na tabela de retorno
                                rowInTable1.cells[4].textContent = selectedRef;
                                rowInTable1.cells[5].textContent = selectedPreco;
                                rowInTable1.cells[6].textContent = selectedEstoque;
                                rowInTable1.style.backgroundColor = '#D7C0DB'; // Define a cor da linha

                            }
                        } else {
                            console.log("Nenhuma linha encontrada com o ID:", idTabela);
                        }
                    }, 500); // Ajusta o tempo necessário para a tabela ser recarregada, para pegar os dados da tabela
                                        
                    



                }
            });
        }



function openSidebar() {
    document.getElementById('search-container').classList.add('open');
}

function closeSidebar() {
    document.getElementById('search-container').classList.remove('open');
}


function atualizarContadorItens() {
    $.ajax({
        url: './buscarContadorItens.php',
        method: 'POST',
        success: function(data) {
            //alert(data);
            $('#contadorItens').text(data); // atualiza o valor na tela
        }
    });
}

document.addEventListener("keydown", function(e) {
    // Só intercepta se a tecla for Enter
    if (e.key === "Enter") {
        let ativo = document.activeElement;

        // Verifica se o foco está em um input de quantidade
        if (ativo && ativo.classList.contains("quantidade")) {
            e.preventDefault(); // impede o Enter normal (ex: submit)

            // Move o foco para o próximo elemento
            let inputs = Array.from(document.querySelectorAll("input, select, textarea, button"));
            let index = inputs.indexOf(ativo);
            if (index > -1 && index < inputs.length - 1) {
                let proximo = inputs[index + 1];
                proximo.focus(); //foca no conteúdo
                proximo.select(); // já seleciona o conteúdo
            }
        }
    }
});