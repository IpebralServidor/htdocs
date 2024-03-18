// botão para criar nota de transferência
document.getElementById("criarNota").addEventListener("click", (event) => {
    let empresa = document.querySelector('#empresas')
    let endereco = document.getElementById('local').value

    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: './Model/criarNota.php',
        async: false,
        beforeSend: function () {
            document.getElementById("loader").style.display = 'block';
        },
        complete: function(){
            document.getElementById("loader").style.display = 'none';
        },
        data: {empresa: empresa.value, endereco: endereco},
        success: function (msg)
        {
            window.location.href = "./View/transferencia.php?nunota=" +msg;
        }
    });
})

// ação ao clicar no select do form
document.getElementById("empresas").addEventListener("click", (event) => {
    document.getElementById("selectPadrao").disabled = true
})

document.getElementById("body").onload = function() {
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: './Model/notasAndamento.php',
        async: false,
        beforeSend: function () {
            $("#loader").show();
        },
        complete: function(){
            $("#loader").hide();
        },
        data: {},
        success: function (msg)
        {
            document.getElementById('prodId').innerHTML = msg
        }
    });
}

function abrirNota(nunota){
    window.location.href = "./View/transferencia.php?nunota=" +nunota.getAttribute('data-id');
}