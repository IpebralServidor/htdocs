
$(document).ready(function () {
    // Captura o clique em uma linha da Tabela 1
    $('#tableListaReferencias tr').on('click', function () {

        // Remove a classe 'selected' de todas as linhas
        document.querySelectorAll('#tableListaReferencias tr').forEach(r => r.classList.remove('selected'));
         
        // Adiciona a classe 'selected' apenas na linha clicada
        this.classList.add('selected');

        const id = $(this).data('id'); // Obtém o ID da linha clicada

        //Cria uma Variável Global para que seja usado em outro trecho do código
        window.idSelecionado = id; // Alternativa global
        //$('#idSelecionado').val(id); // Alternativa com input oculto (mais seguro)

        //alert($(this).data('id'));

        if (!id) return; // Ignora cliques no cabeçalho ou linhas sem ID


        listaReferencia(id);

    });

});



$(document).ready(function () {
  $(".quantidade").on("change", function () {
    const row = $(this).closest("tr");
    const id = row.data("id");
    const quantidade = $(this).val();

    // Atualiza a quantidade no back-end
    updateQuantidade(id, quantidade);

    // Atualiza a lista de referências (tabela com os preços)
    listaReferencia(id);

    // Aguarda a tabela atualizar antes de pegar e aplicar o novo preço
    setTimeout(() => {
      // Seleciona a linha na tabela que contém o atributo data-id correspondente
      const rowAtualizada = document.querySelector(`#tableListaItens tbody tr[data-id="${id}"]`);
      const rowAtualizar = document.querySelector(`#tableListaReferencias tbody tr[data-id="${id}"]`);

      if (rowAtualizada) {
        // Lê o novo preço atualizado da linha (após listaReferencia atualizar os dados)
        const precoAtualizado = rowAtualizada.getAttribute('data-price');

        console.log("Preço atualizado:", precoAtualizado);

        // Atualiza o próprio atributo data-price da linha
       rowAtualizar.cells[5].textContent = precoAtualizado;

      } else {
        console.warn("Linha com data-id", id, "não encontrada em #tableListaItens");
      }
    }, 900); // Ajuste esse tempo se necessário
  });
});






function listaReferencia(id) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: './listareferencia.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#listaReferencia").html("Carregando...");
        },
        data: {
            id: id
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            $("#listaReferencia").html(msg);
        }
    });
}


function ItemDesconto(nuorcamento, referenciaprod) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: './descontoItem.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#ItemDesconto").html("Carregando...");
        },
        data: {
            nuorcamento: nuorcamento,
            referenciaprod: referenciaprod
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            $("#ItemDesconto").html(msg);
        }
    });
}





$(document).ready(function () {
    // Pega o clique duplo para abrir o orçamento
    $('#tableListaOrcamento tr').on('dblclick', function () {


        const nuorcamento = $(this).data('id'); // Obtém o ID da linha clicada
        const orcamento = $(this).find('td:eq(0)').text(); // Primeira coluna
        const empresa = $(this).find('td:eq(1)').text(); // Segunda coluna
        const parceiro = $(this).find('td:eq(2)').text(); // Terceira coluna


        // Exemplo de uso
        const url = "listaitens.php"; // URL de destino
        const data = {
            nuorcamento: nuorcamento,
            codParc: parceiro,
            codEmp: empresa
        };
        abrirOrcamento(url, data);


    });

    
    // Quando a página carrega, salva a cor original de cada linha
    $('#tableListaOrcamento tbody tr').each(function () {
        const originalColor = $(this).css('background-color');
        $(this).attr('data-original-color', originalColor); // Armazena a cor original no atributo 'data-original-color'
    });

    // Clique simples para marcar linha
    $('#tableListaOrcamento').on('click', 'tbody tr', function () {
        
        // Restaura a cor de todas as linhas, exceto a linha clicada
        $('#tableListaOrcamento tbody tr').each(function () {
            const originalColor = $(this).attr('data-original-color');
            if (originalColor) {
                $(this).css('background-color', originalColor); // Restaura a cor original
            }
            $(this).removeClass('selecionada'); // Remove a classe 'selecionada' de todas as linhas
        });

        // Pinta de amarelo a linha clicada e adiciona a classe "selecionada"
        $(this).addClass('selecionada');
        $(this).css('background-color', '#fded06'); // Cor amarela para a linha selecionada

        // Salva o nuorcamento para exclusão
        const nuorcamento = $(this).data('id');
        window.nuorcamentoSelecionado = nuorcamento;

        //alert(nuorcamento);
        console.log('Selecionado: ' + nuorcamento);
    });

 
});


//Função para atualizar a quantidade dos itens quando for alterado na tela
function updateQuantidade(Id, Quantidade) {
        //O método $.ajax(); é o responsável pela requisição
        $.ajax({
            //Configurações
            type: 'POST', //Método que está sendo utilizado.
            dataType: 'html', //É o tipo de dado que a página vai retornar.
            url: './updatequantidade.php', //Indica a página que está sendo solicitada.
            //função que vai ser executada assim que a requisição for enviada
            beforeSend: function() {
                // $("#imagemproduto").html("Carregando...");
            },
            data: {
                id: Id,
                quantidade: Quantidade
            }, //Dados para consulta
            //função que será executada quando a solicitação for finalizada.
            success: function(msg) {
                // $("#imagemproduto").html(msg);
                //alert(msg);
                //alert('Atualização bem-sucedida no banco de dados.');
            }
        });
    }


//Função para mandar dados via post
function abrirOrcamento(url, data) {
    // Cria um formulário
    const form = document.createElement("form");
    form.method = "POST";
    form.action = url;

    // Adiciona os dados ao formulário como campos ocultos
    for (const key in data) {
        if (data.hasOwnProperty(key)) {
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = key;
            input.value = data[key];
            form.appendChild(input);
        }
    }

    // Adiciona o formulário ao corpo da página e o submete
    document.body.appendChild(form);
    form.submit();
}




function showLoading() {
    // Mostra o GIF
    document.getElementById('loader').style.display = 'block';
    // Desativa o botão para evitar múltiplos envios
    document.getElementById('uploadarquivo').disabled = true;
}


document.getElementById("selectAll").addEventListener("change", function() {
    let checkboxes = document.querySelectorAll(".itemCheckbox");
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});



// Função para excluir o orçamento
function excluirOrcamento() {
    // Verifica se há um orçamento selecionado
    if (window.nuorcamentoSelecionado) {
        const nuorcamento = window.nuorcamentoSelecionado;

        //alert(nuorcamento);

        // Exclui a linha da tabela (remover do DOM)
        $('#tableListaOrcamento tr[data-id="' + nuorcamento + '"]').remove();

        // Envia a requisição para excluir o orçamento no PHP
        $.ajax({
            url: 'excluiOrcamento.php', // O arquivo PHP que vai tratar a exclusão
            method: 'POST',
            data: {
                nuorcamento: nuorcamento // Passa o ID do orçamento a ser excluído
            },
            success: function(response) {
                // Retorna a mensagem que veio do Banco de Dados
                alert(response); 
            },
            error: function() {
                alert('Erro ao se comunicar com o servidor.');
            }
        });
    } else {
        alert('Selecione um orçamento para excluir.');
    }
}


function imagemproduto(codigodebarra) {
    //O método $.ajax(); é o responsável pela requisição
    $.ajax({
        //Configurações
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: './imagemproduto.php', //Indica a página que está sendo solicitada.
        //função que vai ser executada assim que a requisição for enviada
        beforeSend: function() {
            $("#imagemproduto").html("Carregando...");
        },
        data: {
            codigodebarra: codigodebarra
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function(msg) {
            $("#imagemproduto").html(msg);
        }
    });
}