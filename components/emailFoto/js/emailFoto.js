$(document).ready(function() {
    $('#emailFoto').load('../../components/emailFoto/html/emailFoto.html', function() {
        // Após o conteúdo ser carregado, adiciono o link para o CSS
        $('head').append('<link rel="stylesheet" type="text/css" href="../../components/emailFoto/css/emailFoto.css">');
    });
    $('#imagemproduto').on('click', confirmarEnvioEmail);
});

function confirmarEnvioEmail() {
    document.getElementById('popupEmail').style.display = 'block';
}


function fecharPopupEmail() {   
    document.getElementById('popupEmail').style.display = 'none';   
}

function enviaEmailSemFoto() {
    let codigodebarra =  $("#codigodebarra").val();

    if(codigodebarra != '') {
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: '../../components/emailFoto/php/emailFoto.php', 
            data: { 
                codigodebarra: codigodebarra
            
            },
            success: function(msg) {					
                fecharPopupEmail(); // Fecha o pop-up após enviar o e-mail
            

            } 
        });
    }
    else{
        fecharPopupEmail(); // Fecha o pop-up após enviar o e-mail
        }
}