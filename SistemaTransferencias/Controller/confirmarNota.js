document.getElementById("btnConfirmaNota").addEventListener("click",() =>{

    const urlParams = new URLSearchParams(window.location.search)
    const nunota = urlParams.get("nunota")
    const gif = document.getElementById("loader")

    $.ajax
    ({
        type: 'POST',
        dataType: 'html',
        url: '../Model/confirmaNota.php',
        beforeSend: function () {
            gif.style.display = "block"
            gif.classList.add("loader")
        },
        complete: function(){
            gif.style.display = "none"
            gif.classList.remove("loader")
        },
        data: {nunota: nunota},
        success: function (msg)
        {
            if(msg == 'Nota confirmada com sucesso. Não é possível inserir mais produtos'){

                document.getElementById("closePopUpConfirma").click()

                alertMessage.classList.remove("d-none")
                alertMessage.classList.add("d-block")

                alertMessage.classList.remove("alert-danger")
                alertMessage.classList.add("alert-success")

                msgAlert.textContent = msg
            }else{
                document.getElementById("closePopUpConfirma").click()

                alertMessage.classList.remove("d-none")
                alertMessage.classList.add("d-block")

                alertMessage.classList.remove("alert-success")
                alertMessage.classList.add("alert-danger")

                msgAlert.textContent = msg
            }
        }
    });
})