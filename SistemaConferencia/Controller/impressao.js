const impressao = (tipoImpressao) => {
    const urlParams = new URLSearchParams(window.location.search);
    const numeroNota = urlParams.get("nunota");
    const gif = document.getElementById("loader");
    let file;
    if(tipoImpressao === 'etiqueta') {
        file = 'Cliente volume nota novo';
    } else if(tipoImpressao === 'vale') {
        file = 'Vale_novo';
    }
    $.ajax
    ({
        type: 'POST',
        dataType: 'html',
        url: '../Etiquetas/compileJasper.php',
        beforeSend: function () {
            gif.style.display = "block"
            gif.classList.add("loader")
        },
        complete: function(){
            gif.style.display = "none"
            gif.classList.remove("loader")
        },
        data: {nunota: numeroNota, arquivo: file},
        success: function ()
        {
            if(tipoImpressao === 'etiqueta') {
                alert('Selecione a impressora: zebra-cd4');
            }
            window.open(`http:\\SistemaConferencia\\Etiquetas\\nunotas\\${numeroNota}\\${file}.pdf`);
        }
    });
}