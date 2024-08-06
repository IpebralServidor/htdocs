let tempoInicial;
let tempoFinal;
let inputInicialEndereco = '';
let enderecoBipado = '';

document.getElementById("endereco").addEventListener("input", () =>{
    tempoInicial = new Date();
})

document.getElementById("endereco").addEventListener("change", () =>{
    tempoFinal = new Date();
    var tempoDecorrido = tempoFinal - tempoInicial;

    if(tempoDecorrido > 250){
        inputInicialEndereco = document.getElementById('endereco').value;
        document.getElementById("btnConfirmaEndereco").click();
        document.getElementById('endereco').value = '';
    } else {
        enderecoBipado = 'S';
    }
})

document.getElementById("btnConfirmarEndereco").addEventListener("click", () =>{
    let novoEndereco = document.getElementById("novoEndereco").value
    if (novoEndereco != '') {
        if (inputInicialEndereco != novoEndereco) {
            alert('Valores digitados não batem. Verifique a digitação');
        } else {
            document.getElementById("endereco").value = novoEndereco;
            enderecoBipado = 'N';
        }
    } else {
        alert('Digite um valor.');
    }
})
