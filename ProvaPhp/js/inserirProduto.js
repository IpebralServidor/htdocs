//Crie um evento para que quando o botão "Inserir produto" for pressionado, execute o código AJAX abaixo:

const inputProduto = //Implemente uma forma de preencher a variável "inputProduto" com o valor do input criado
const inputQuantidade = //Implemente uma forma de preencher a variável "inputQuantidade" com o valor do input de quantidade

//O método $.ajax(); é o responsável pela requisição
$.ajax
({
    //Configurações
    type: 'POST',
    dataType: 'html',
    url: './procedures/inserirProduto.php',
    beforeSend: function () {
    },
    data: {produto: inputProduto.value, quantidade: inputQuantidade.value},
    //função que será executada quando a solicitação for finalizada.
    success: function (msg)
    {
        //Crie um alerta personalizado com uma mensagem de sucesso
    }
});
