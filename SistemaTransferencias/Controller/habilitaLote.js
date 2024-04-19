document.getElementById("referencia").addEventListener("change",() =>{
    const inputReferencia = document.getElementById("referencia");
    const inputLote = document.getElementById("lote");
    $.ajax
    ({
        type: 'POST',
        dataType: 'html',
        url: '../Model/habilitaLote.php',
        data: {
            referencia: inputReferencia.value
        },
        success: function (tipoControle) {
            if(tipoControle != 'N') {
                inputLote.disabled = false;
            } else {
                inputLote.disabled = true;
            }
        }
    });
})