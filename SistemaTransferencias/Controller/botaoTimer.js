document.getElementById("botaoTimer").addEventListener("click", ()=>{
    const botao = document.getElementById("botaoTimer")
    const farol = document.getElementById("timer-color")

    if(botao.classList.contains("fa-pause")){
        botao.classList.remove("fa-pause")
        botao.classList.add("fa-play")
        farol.style.backgroundColor = "#FDE512"
        pausarIniciarContagem('A', botao.getAttribute('data-id'))
    }else{
        botao.classList.add("fa-pause")
        botao.classList.remove("fa-play")
        farol.style.backgroundColor = "#228B22"
        pausarIniciarContagem('P', botao.getAttribute('data-id'))
    }
})

function pausarIniciarContagem(status, nota)
{
    $.ajax
    ({
        type: 'POST',
        dataType: 'html',
        url: '../Model/botaoTimer.php',
        beforeSend: function () {
            $("#iniciarpausa").html("Carregando...");
        },
        data: {status: status, nota: nota},
        success: function (msg)
        {
        }
    });
}