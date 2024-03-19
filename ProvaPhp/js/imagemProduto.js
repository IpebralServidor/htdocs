
//Crie um evento que ao perder o foco do input de código de produto, execute o código AJAX abaixo:

const inputProduto = //Implemente uma forma de preencher a variável "inputProduto" com o valor do input criado

// O método $.ajax(); é o responsável pela requisição
$.ajax
({
    //Configurações
    type: 'POST',
    dataType: 'html',
    url: './procedures/imagemProduto.php',
    beforeSend: function () {
    },
    data: {referencia: inputProduto.value},
    //função que será executada quando a solicitação for finalizada.
    success: function (msg)
    {
        $("#imagemproduto").html(msg);
    }
});