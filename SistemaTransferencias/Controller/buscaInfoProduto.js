document.getElementById("referencia").addEventListener("focusout",() =>{
    const estMin = document.getElementById("estMin")
    const qtdEstPad = document.getElementById("qtdEstPad")
    const medVend = document.getElementById("medVend")
    const referencia = document.getElementById("referencia")

    const urlParams = new URLSearchParams(window.location.search)
    const nunota = urlParams.get("nunota")

    $.ajax
    ({
        type: 'POST',
        dataType: 'html',
        url: '../Model/buscaInfoProduto.php',
        beforeSend: function () {
        },
        data: {referencia: referencia.value, nunota: nunota},
        success: function (msg)
        {
            let res = msg.split('|')

            estMin.textContent = res[0]
            qtdEstPad.textContent = res[1]
            // medVend.textContent = res[2]
        }
    });
})