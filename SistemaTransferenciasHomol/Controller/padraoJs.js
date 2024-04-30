document.getElementById("btnConfirmarEndereco").addEventListener("click", () =>{
    let novoEndereco = document.getElementById("novoEndereco").value
    document.getElementById("endereco").value = novoEndereco
})

document.getElementById("btnConfirmarReferencia").addEventListener("click", () =>{
    let novaReferencia = document.getElementById("novaReferencia").value
    document.getElementById("referencia").value = novaReferencia
})