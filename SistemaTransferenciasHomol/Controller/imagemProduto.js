
document.getElementById("referencia").addEventListener("change",() =>{
    const inputReferencia = document.getElementById("referencia")
    $.ajax
    ({
        type: 'POST',
        dataType: 'html',
        url: '../Model/imagemProduto.php',
        beforeSend: function () {
        },
        data: {referencia: inputReferencia.value},
        success: function (msg)
        {
            $("#imagemproduto").html(msg);
        }
    });
})