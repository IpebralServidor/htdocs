document.getElementById("gerarEtiqueta").addEventListener("click",() =>{
    const urlParams = new URLSearchParams(window.location.search)
    const numeroNota = urlParams.get("nunota")
    const gif = document.getElementById("loader")
    const file = 'Cliente volume nota novo'

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
        success: function (msg)
        {
            window.open(`http:\\SistemaConferencia\\Etiquetas\\vendor\\geekcom\\phpjasper\\examples\\${numeroNota}\\${file}.pdf`)
        }
    });
})