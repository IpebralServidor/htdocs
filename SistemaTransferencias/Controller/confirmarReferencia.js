let tempoInicialRef
let tempoFinalRef
let inputInicialReferencia = '';
let referenciaBipado = '';


document.getElementById("referencia").addEventListener("input", () =>{
    tempoInicialRef = new Date();
})

document.getElementById("referencia").addEventListener("change", () =>{
    tempoFinalRef = new Date();
    var tempoDecorrido = tempoFinalRef - tempoInicialRef;

    if(tempoDecorrido > 250){
        inputInicialReferencia = document.getElementById('referencia').value;
        document.getElementById("btnConfirmaReferencia").click()
        document.getElementById('referencia').value = ''
    } else {
        referenciaBipado = 'S';
    }
})

document.getElementById("btnConfirmarReferencia").addEventListener("click", () =>{
    let novaReferencia = document.getElementById("novaReferencia").value;
    if (novaReferencia != '') {
        if (inputInicialReferencia != novaReferencia) {
            alert('Valores digitados não batem. Verifique a digitação');
        } else {
            document.getElementById("referencia").value = novaReferencia;
            referenciaBipado = 'N';
        }
    } else {
        alert('Digite um valor.');
    }
})