
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
  // Evento de mudança no input
  $(".quantidade").on("change", function () {    
    
    // Encontrando a linha (tr) do input que está sendo alterado
    const row = $(this).closest("tr");
    
    // Capturando o ID da linha e a quantidade digitada no input
    const id = row.data("id");
    const quantidade = $(this).val(); // Pega o valor atualizado

    //alert(quantidade + ' / ' +id);
    updateQuantidade(id, quantidade);


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






$(document).ready(function () {
    // Captura o clique em uma linha da Tabela 1
    $('#tableListaOrcamento tr').on('click', function () {


        const nuorcamento = $(this).data('id'); // Obtém o ID da linha clicada
        const orcamento = $(this).find('td:eq(0)').text(); // Primeira coluna
        const empresa = $(this).find('td:eq(1)').text(); // Segunda coluna
        const parceiro = $(this).find('td:eq(1)').text(); // Terceira coluna

        //alert(nuorcamento + ' / ' + coluna1 + ' / ' + coluna2);

        // window.idSelecionado = id; // Alternativa global

        // if (!id) return; // Ignora cliques no cabeçalho ou linhas sem ID


        // listaReferencia(id);

        // Exemplo de uso
        const url = "listaitens.php"; // URL de destino
        const data = {
            nuorcamento: nuorcamento,
            codParc: parceiro,
            codEmp: empresa
        };
        abrirOrcamento(url, data);


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

