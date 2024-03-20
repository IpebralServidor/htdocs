//Crie um evento que ao perder o foco do input de código de produto, execute o código abaixo:

const inputProduto = //Implemente uma forma de preencher a variável "inputProduto" com o valor do input de produto

$.ajax
({
    //Configurações
    type: 'POST',
    dataType: 'html',
    //endereco da procedure PHP
    url: './procedures/imagemProduto.php',
    beforeSend: function () {
    },
    //nome e valores das variáveis que serão recebidas via POST na procedure PHP
    data: {referencia: inputProduto},
    //função que será executada quando a solicitação for finalizada.
    success: function (msg)
    {
        $("#imagemproduto").html(msg);
    }
});