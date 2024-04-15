$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const nunota = urlParams.get('nunota');

    $('#aplicar').click(function() {
        abrirNota(nunota, 'S')
    });
    
    $('#aplicar-sem-fila').click(function() {
        document.getElementById('popAlterarSenha').classList.toggle("active");
    });
    
    $('#btn-alterasenha').click(function() {
        abrirNota(nunota, 'N')
    });
});


function abrirNota(nunota, fila) {
    let enderecoInit;
    let enderecoFim;
    if(fila === 'S'){
        enderecoInit = 0;
        enderecoFim = 0;
    } else {
        enderecoInit = document.getElementById('senha_alt').value;
        enderecoFim = document.getElementById('senha_conf').value;
    }
    $.ajax({
        type: 'POST', //Método que está sendo utilizado.
        dataType: 'html', //É o tipo de dado que a página vai retornar.
        url: 'armazenarendereco.php', //Indica a página que está sendo solicitada.
        data: {
            enderecoInit: enderecoInit,
            enderecoFim: enderecoFim
        }, //Dados para consulta
        //função que será executada quando a solicitação for finalizada.
        success: function() {
            window.location.href = 'reabastecimento.php?nunota=' + nunota + '&fila=S';
        }
    });    
}


function abrir() {
    document.getElementById('popAlterarSenha').classList.toggle("active");
}