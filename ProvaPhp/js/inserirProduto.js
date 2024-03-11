document.getElementById("inserir").addEventListener("click",() =>{
    const inputProduto = document.getElementById("produto")
    const inputQuantidade = document.getElementById("quantidade")
    $.ajax
    ({
        type: 'POST',
        dataType: 'html',
        url: './procedures/inserirProduto.php',
        beforeSend: function () {
        },
        data: {produto: inputProduto.value, quantidade: inputQuantidade.value},
        success: function (msg)
        {
            alert(msg)
        }
    });
})