const urlParams = new URLSearchParams(window.location.search);

$(document).ready(function() {
    buscaItensGarantia('');
});


const buscaItensGarantia = () => {

    $.ajax({
        method: 'GET',
        url: '../routes/routes.php',
        dataType: 'json',
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {            
            route: 'buscaItensGarantia'
        },
        success: function(response) {
            
          
            if(response.success || response.success === '') {
                document.getElementById('listaNotas').innerHTML = response.success;
            } else {
                alert('Erro: ' + response.error);
            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });
}


function alternarMenuFlutuante() {
    const menu = document.querySelector('.menu-flutuante');
    menu.classList.toggle('ativo');
}



function abrirPallet() {
    document.getElementById("popup-overlay-pallet").style.display = "flex";
    document.getElementById('codUsu').value  = ''; //esvazio campos
    document.getElementById('nomeUsu').value  = '';
    const checkboxes = document.querySelectorAll('.linha-checkbox');
    checkboxes.forEach(cb => cb.checked = false);




}

function fecharPallet() {
    document.getElementById("popup-overlay-pallet").style.display = "none";
    document.getElementById("popup-overlay-pallet2").style.display = "none";

}

//  selecionar todos os checkboxes
    function selecionarTodos(master) {
        const checkboxes = document.querySelectorAll('.linha-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
    } 


const transferirGarantia = () => {


    const referenciasSelecionadas = getEnderecosSelecionados();

    if (referenciasSelecionadas.length === 0) {
        alert('Selecione pelo menos uma linha.');
        return;
    }

   
    $.ajax({
        method: 'POST',
        url: '../routes/routes.php',
        dataType: 'json',
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {            
            route: 'transferirGarantia',
            notas :referenciasSelecionadas,

        },
        success: function(response) {
                
        if(response.success.msg != 'PEND') {
           
            alert('Itens transferidos com sucesso! Num:' + response.success.result )
            location.reload()
        
        } else if (response.success.msg == 'PEND') {

            alert('App: Já existem transferências da garantia em aberto!');

        }
        
                              
               
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });

}
    //armazeno as notas seleciondas pelo gerente que serao atribuidas
function getEnderecosSelecionados() {
    const selecionados = document.querySelectorAll('.linha-checkbox:checked');
    const referencias = [];

    selecionados.forEach(checkbox => {
        const row = checkbox.closest('tr');
        const ref = row.querySelector('.referencia')?.textContent.trim();
        if (ref) {
            referencias.push(ref);
        }
    });

    return referencias.join(','); // transforma o array em uma string separada por vírgula
}