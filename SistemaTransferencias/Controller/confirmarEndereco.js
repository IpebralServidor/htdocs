let tempoInicial;
let tempoFinal;

document.getElementById("endereco").addEventListener("input", () =>{
    tempoInicial = new Date();
})

document.getElementById("endereco").addEventListener("blur", () =>{
    tempoFinal = new Date();
    var tempoDecorrido = tempoFinal - tempoInicial;

    if(tempoDecorrido > 250){
        document.getElementById("btnConfirmaEndereco").click()
        document.getElementById('endereco').value = null
    }
})
