
document.getElementById("inserirProdutoBtn").addEventListener("click",() =>{
    const inputReferencia = document.getElementById("referencia");
    const inputQuantidade = document.getElementById("quantidade");
    const inputEndereco = document.getElementById("endereco");
    const inputLote = document.getElementById("lote");
    const inputQtdMaxLocal = document.getElementById("qtdMax");
    const gif = document.getElementById("loader");
    const msgAlert = document.getElementById("msgAlert");
    const urlParams = new URLSearchParams(window.location.search);
    const nunota = urlParams.get("nunota");
    const alertMessage = document.getElementById("alertMessage");

    if(inputQtdMaxLocal.value > 0) {
        $.ajax ({
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
            data: {nunota: nunota, referencia: inputReferencia.value, qtdneg: inputQuantidade.value, endereco: inputEndereco.value, lote: inputLote.value, qtdMaxLocal: inputQtdMaxLocal.value},
            success: function (msg) {
                if(msg == 'Produto adicionado com sucesso!'){
                    alertMessage.classList.remove("d-none")
                    alertMessage.classList.add("d-block")

                    alertMessage.classList.remove("alert-danger")
                    alertMessage.classList.add("alert-success")
                    msgAlert.textContent = msg

                    inputReferencia.value = ''
                    inputQuantidade.value = ''
                    inputEndereco.value = ''
                    inputLote.value = ''
                    inputQtdMaxLocal.value = ''

                    inputEndereco.focus();
                    inputLote.disabled = true;
                } else{
                    alertMessage.classList.remove("d-none")
                    alertMessage.classList.add("d-block")

                    alertMessage.classList.remove("alert-success")
                    alertMessage.classList.add("alert-danger")

                    msgAlert.textContent = msg
                }
            }
        });
    } else {
        alertMessage.classList.remove("d-none");
        alertMessage.classList.add("d-block");

        alertMessage.classList.remove("alert-success");
        alertMessage.classList.add("alert-danger");

        msgAlert.textContent = 'Insira uma quantidade máxima válida!';
    }

})