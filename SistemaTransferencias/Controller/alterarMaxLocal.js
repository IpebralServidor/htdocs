document.getElementById("referencia").addEventListener("focusout",() =>{
    const inputQtdMax = document.getElementById("qtdMax")
    const inputReferencia = document.getElementById("referencia")

    const urlParams = new URLSearchParams(window.location.search)
    const nunota = urlParams.get("nunota")

    $.ajax
    ({
        type: 'POST',
        dataType: 'html',
        url: '../Model/alterarMaxLocal.php',
        beforeSend: function () {
        },
        data: {referencia: inputReferencia.value, nunota2: nunota},
        success: function (msg)
        {
            inputQtdMax.value = msg
        }
    });
})