//Crie um evento para que quando o botão "Inserir produto" for pressionado, execute o código abaixo:

const inputProduto = //Implemente uma forma de preencher a variável "inputProduto" com o valor do input de código
const inputQuantidade = //Implemente uma forma de preencher a variável "inputQuantidade" com o valor do input de quantidade

$.ajax
({
    //Configurações
    type: 'POST',
    dataType: 'html',
    //endereco da procedure PHP
    url: './procedures/inserirProduto.php',
    beforeSend: function () {
    },
    //nome e valores das variáveis que serão recebidas via POST na procedure PHP
    data: {produto: inputProduto, quantidade: inputQuantidade},
    //função que será executada quando a solicitação for finalizada.
    success: function (msg)
    {
        //Crie um alerta personalizado com uma mensagem de sucesso
    }
});
