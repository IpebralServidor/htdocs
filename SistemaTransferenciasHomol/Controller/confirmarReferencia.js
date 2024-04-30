let tempoInicialRef
let tempoFinalRef

document.getElementById("referencia").addEventListener("input", () =>{
    tempoInicialRef = new Date();
})

document.getElementById("referencia").addEventListener("change", () =>{
    tempoFinalRef = new Date();
    var tempoDecorrido = tempoFinalRef - tempoInicialRef;

    if(tempoDecorrido > 250){
        document.getElementById("btnConfirmaReferencia").click()
        document.getElementById('referencia').value = null
    }
})
