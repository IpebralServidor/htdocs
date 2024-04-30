function deletarProduto(button, prod){
    document.getElementById("prodDelete").textContent = prod
    document.getElementById("btnAlterarMaxLocal").setAttribute('data-atributo', button.getAttribute('data-id'))
}

document.getElementById("btnAlterarMaxLocal").addEventListener("click", ()=>{
    const seq = document.getElementById("btnAlterarMaxLocal").getAttribute('data-atributo')
    const urlParams = new URLSearchParams(window.location.search)
    const nunota = urlParams.get("nunota")
    const gif = document.getElementById("loader")

    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '../Model/deletarProduto.php',
        async: false,
        beforeSend: function () {
            gif.style.display = "block"
            gif.classList.add("loader")
        },
        complete: function(){
            gif.style.display = "none"
            gif.classList.remove("loader")
        },
        data: {sequencia: seq, nunota: nunota},
        success: function (msg)
        {
            document.getElementById("closePopUp").click()
            document.getElementById("setaDownDiv").click()

            alertMessage.classList.remove("d-none")
            alertMessage.classList.add("d-block")

            alertMessage.classList.remove("alert-success")
            alertMessage.classList.add("alert-danger")

            msgAlert.textContent = msg
        }
    });
})
document.getElementById("closeIcon").addEventListener("click", () =>{
    const alertMessage = document.getElementById("alertMessage")

    alertMessage.classList.remove("d-block")
    alertMessage.classList.add("d-none")
})

let rotated = false;

document.getElementById("setaDownDiv").addEventListener("click", ()=>{
    const setaDown = document.getElementById("setaDown");
    const urlParams = new URLSearchParams(window.location.search)
    const nunota = urlParams.get("nunota")

    if (!rotated) {
        setaDown.style.transform = "rotate(180deg)";
        rotated = true;
    } else {
        setaDown.style.transform = "rotate(0deg)";
        rotated = false;
    }

    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '../Model/produtosInseridos.php',
        async: false,
        data: {nunota: nunota},
        success: function (msg)
        {
            document.getElementById('tabelaProdutosInseridos').innerHTML = msg
        }
    });
})

