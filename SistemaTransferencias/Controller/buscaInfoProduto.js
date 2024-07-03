const referencia = document.getElementById("referencia")
const localRet = document.getElementById("locRet")
const urlParams = new URLSearchParams(window.location.search)
const nunota = urlParams.get("nunota")
const endereco = document.getElementById("endereco");


document.getElementById("referencia").addEventListener("change",() =>{
    $.ajax
    ({
        type: 'POST',
        dataType: 'html',
        url: '../Model/buscaInfoProduto.php',
        data: {
            referencia: referencia.value,
            nunota: nunota
        },
        success: function (msg)
        {
            let res = msg.split('|')

            let value = parseFloat(res[1])
            localRet.textContent = value.toString() + ' | ' + value.toString();
            if(res[3] === '0') {
                endereco.placeholder = '';
            } else {
                endereco.placeholder = res[3];
            }
        }
    });
})

document.getElementById("quantidade").addEventListener("input",() =>{
    let qtd = document.getElementById("quantidade")
    $.ajax
    ({
        type: 'POST',
        dataType: 'html',
        url: '../Model/buscaInfoProduto.php',
        beforeSend: function () {
        },
        data: {referencia: referencia.value, nunota: nunota},
        success: function (msg)
        {
            let res = msg.split('|')

            let value = parseFloat(res[1]);
            let subtractValue = parseFloat(res[1]) - qtd.value;
            localRet.textContent = value.toString() + ' | ' + subtractValue.toString();
        }
    });
})