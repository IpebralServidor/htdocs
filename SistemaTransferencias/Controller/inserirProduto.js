
document.getElementById("inserirProdutoBtn").addEventListener("click",() =>{

    const inputReferencia = document.getElementById("referencia")
    const inputQuantidade = document.getElementById("endereco")
    const inputEndereco = document.getElementById("quantidade")
    const inputLote = document.getElementById("lote")
    const alert = document.getElementById("alert")
    const alertText = document.getElementById("alertText")
    const gif = document.getElementById("loader")
    const urlParams = new URLSearchParams(window.location.search)
    const nunota = urlParams.get("nunota")

    $.ajax
    ({
        type: 'POST',
        dataType: 'html',
        url: '../Model/inserirProduto.php',
        beforeSend: function () {
            gif.style.display = "block"
            gif.classList.add("loader")
        },
        complete: function(){
            gif.style.display = "none"
            gif.classList.remove("loader")
        },
        data: {nunota: nunota, referencia: inputReferencia.value, qtdneg: inputQuantidade.value, endereco: inputEndereco.value, lote: inputLote.value},
        success: function (msg)
        {
            alert.classList.add("d-block")
            alert.classList.remove("d-none")
            alertText.textContent = msg

            inputReferencia.value = ''
            inputQuantidade.value = ''
            inputEndereco.value = ''
            inputLote.value = ''
        }
    });
})