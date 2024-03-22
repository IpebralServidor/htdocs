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

