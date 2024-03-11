
document.getElementById("produto").addEventListener("focusout",() =>{
    const inputReferencia = document.getElementById("produto")
    $.ajax
    ({
        type: 'POST',
        dataType: 'html',
        url: './procedures/imagemProduto.php',
        beforeSend: function () {
        },
        data: {referencia: inputReferencia.value},
        success: function (msg)
        {
            $("#imagemproduto").html(msg);
        }
    });
})