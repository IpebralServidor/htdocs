const limiteMensagens = 3;

$(document).ready(function() {
    $.ajax({
        type: 'POST',  
        dataType: 'html', 
        url: './components/popUpAviso/php/verificaAviso.php', 
        data: {
            limiteMensagens: limiteMensagens
        }, 
        success: function(response) {
            if(response == 'S') {
                $('#popUpAviso').load('../../components/popUpAviso/html/popUpAviso.html', function() {
                    // Após o conteúdo ser carregado, adiciono o link para o CSS
                    $('head').append('<link rel="stylesheet" type="text/css" href="../../components/popUpAviso/css/popUpAviso.css">');
                });    
            }
        }
    });
});
function fecharPopUp() {
    document.getElementById('popAviso').style.display = 'none';
}

function naoMostrarNovamente() {    

    $.ajax({
        type: 'POST', 
        dataType: 'html', 
        url: './components/popUpAviso/php/atualizaLimiteAviso.php', 
        data: {
            limiteMensagens : limiteMensagens
            
        }, 
        success: function(msg) {
            fecharPopUp()
        }
    });


}




