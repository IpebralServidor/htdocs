const urlParams = new URLSearchParams(window.location.search);
const tipo = urlParams.get('tipo');

$(document).ready(function() {
    let nunota = document.getElementById('searchInput').value;    
    buscaNotasContagem(nunota,tipo);
    buscaAtribuirNotas(nunota,tipo);
    verificaGerente();
    buscaLiberaPaletes(nunota,tipo);
    buscaPaletesPendentes(nunota,tipo);
    verificaTipo(tipo);


});

const buscaLiberaPaletes = () => {
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
            route: 'buscaLiberaPaletes',
            tipo: tipo
        },
        success: function(response) {
            
          
            if(response.success || response.success === '') {
                document.getElementById('listaPaletes').innerHTML = response.success;
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


const buscaPaletesPendentes = () => {
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
            route: 'buscaPaletesPendentes',
            tipo: tipo
        },
        success: function(response) {
            
          
            if(response.success || response.success === '') {
                document.getElementById('listaPaletesPendentes').innerHTML = response.success;
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



const verificaGerente = () => {
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
            route: 'verificaGerente'
        },
        success: function(response) {
            
          
            if(response.success.msg.includes('true')) {
              document.getElementById('atribuirContagem').style.display = 'inline-block'
            } 
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });
}

const buscaNotasContagem = () => {
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
            route: 'buscaNotasContagem',
            tipo: tipo
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

const buscaAtribuirNotas = () => {
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
            route: 'buscaAtribuirNotas',
            tipo: tipo
        },
        success: function(response) {
            
          
            if(response.success || response.success === '') {
                document.getElementById('listaAtribuirNotas').innerHTML = response.success;
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

const confirmaAbrirContagem= (row) => {
    
    const nunota = row.id;
    const tipo = row.getAttribute('data-tipo');
    const status = row.getAttribute('data-status');   
    
       
       
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
                nunota : nunota,
                tipo: tipo,
                route: 'verificaEmpresa'
            },
            success: function(response) {
                if (response.success.msg.includes('pend') && status == 'D'){                    
                    alert('APP: Existem contagens atribuidas em abertas para voce: ' + response.success.msg);                  
                } 
                else if ((response.success.msg.includes('prioridade'))){
                    alert('Existem contagens  que são prioridade! Clique em próximo ou peça ao Gerente pra atribuir essa contagem se necessário: ')
                    return;
                }
                else{

                if ((response.success.msg.includes('usuario') && (response.success.codusulog != response.success.codusunota  ) && status == 'A' )){
                    alert('Nota já em andamento pelo ' + response.success.msg)
                    return;
                }
                    let confirmMsg;
                    if(status === 'D') {
                        confirmMsg = `Deseja abrir contagem o N° ${nunota}?`;
                    } else if(status.includes('C')) {
                    alert('Não é possível abrir contagem concluída sem pedido de recontagem.');
                    return;
                    }
                    const confirmacao = (status === 'D' || status.includes('C')) ? confirm(confirmMsg) : true;
                    if(confirmacao) {
                        window.location.href = `./Contagem.php?nunota=${nunota}&tipo=${tipo}`;
                    }        
                }
              
                   
            },
            error: function(xhr, status, error) {
                alert('Erro na requisição AJAX: ' + error);
                console.log(xhr);
                console.log(status);
            }
        });                                                                
}


const confirmaAbrirContagemFiltro= () => {
    const nunota = document.getElementById('searchInput').value; 
    const tabela = document.getElementById('listaNotas');  
    if (Array.from(tabela.rows).map(linha => linha.id).includes(nunota)) {
        confirmaAbrirContagem(document.getElementById(nunota));
    } else{
        alert("Por favor, insira um número válido."); 
    }
}




const verificaProximo = () => {
    $.ajax({
        method: 'GET',
        url: '../routes/routes.php',
        dataType: 'json',
        // beforeSend: function() {
        //     $("#loader").show();
        // },
        // complete: function() {
        //     $("#loader").hide();
        // },
        data: {            
            
            tipo: tipo,
            route: 'verificaProximo'
        },
        success: function(response) {
            if (response.success.msg.includes('pend')){                    
                alert('APP: Existem contagens atribuidas em abertas para voce: ' + response.success.msg);                  
            } 
            else{

                pegaProximaNota();
          
            }
          
               
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });
}


const pegaProximaNota = () => {
    
   
   
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
                tipo: tipo,
                route: 'pegaProximaNota'
            },
            success: function(response) {
                    
            if(response.success) {
                let confirmMsg;
                let nunota = response.success.nunota;
                let status = response.success.status;
                let referencia = response.success.referencia;
                let endereco = response.success.endereco;


                if(status === 'D') {
                    confirmMsg = `Deseja abrir contagem o N° ${nunota}? ref: ${referencia} local: ${endereco}  `;
                } 
                const confirmacao = (status === 'D' || status.includes('C')) ? confirm(confirmMsg) : true;
                if(confirmacao) {
                    window.location.href = `./Contagem.php?nunota=${nunota}&tipo=${tipo}`;
                }        
            
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



    
const getReferenciaOp = (row) => {
    const nunota = row;


    $.ajax({
        method: 'GET',
        url: '../routes/routes.php',
        dataType: 'json',
        // beforeSend: function() {
        //     $("#loader").show();
        // },
        // complete: function() {
        //     $("#loader").hide();
        // },
        data: {            
            
            nunota: nunota,
            route: 'getReferenciaOp'
        },
        success: function(response) {
            if (response.success.msg){                    
                return response.success.msg;
            } 
           
          
               
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });
}

    //Scrip's de atribuir contagem aos usuarios
    
    //abre e fecha o pop up
    function abrirAtribuir() {
        document.getElementById("popup-overlay").style.display = "flex";
        document.getElementById('codUsu').value  = ''; //esvazio campos
        document.getElementById('nomeUsu').value  = '';
        const checkboxes = document.querySelectorAll('.linha-checkbox');
        checkboxes.forEach(cb => cb.checked = false);




    }
    
    function fecharAtribuir() {
        document.getElementById("popup-overlay").style.display = "none";

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


    function verificaTipo(){
        if (tipo == 'N') {
            
            document.getElementById("botaoExpandir").style.display = "none";
        } else if (tipo == 'O'){
            document.getElementById("botaoExpandir").style.display = "flex";

        };

    }


    //armazeno as notas seleciondas pelo gerente que serao atribuidas
    function getEnderecoSelecionado() {
        const selecionado = document.querySelector('.linha-checkbox:checked');
        if (selecionado) {
            const row = selecionado.closest('tr');
            const nroUnico = row.querySelector('.endereco')?.textContent.trim();
            console.log('Selecionado:', nroUnico);
            return nroUnico;
        }
        return '';
    }

    const transferirPaletes = () => {

        const resultadoTexto = getEnderecoSelecionado(); // sem "s" no final

        // Para desmarcar o radio manualmente:
        const radios = document.querySelectorAll('.linha-checkbox');
        radios.forEach(radio => radio.checked = false);


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
                route: 'transferirPaletes',
                notas :resultadoTexto,
            },
            success: function(response) {
                    
            if(response.success.msg == 'true') {
               
                alert('Itens transferidos com sucesso!')
                location.reload()
            
            } else if(response.success.msg == 'pend') {

             alert('ERRO: JA EXISTEM PALETES PENDENTES PARA ESTE LOCAL, FINALIZAR GUARDA DELES PARA QUE POSSA GERAR OUTRO')

            }
             else {
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


    //armazeno as notas seleciondas pelo gerente que serao atribuidas
    function getNrosUnicosSelecionados() {
        const selecionados = document.querySelectorAll('.linha-checkbox:checked');
        const valores = [];
    
        selecionados.forEach(cb => {
            const row = cb.closest('tr');
            const nroUnico = row.querySelector('.nro-unico')?.textContent.trim();
            if (nroUnico) valores.push(nroUnico);
        });
    
        const resultadoTexto = valores.join(", ");
        return resultadoTexto;
    }
    
    
    const atribuirNotaUsuario= () => {

        const resultadoTexto  = getNrosUnicosSelecionados();
        const usuario = document.getElementById('codUsu').value;
        const checkboxes = document.querySelectorAll('.linha-checkbox');
        checkboxes.forEach(cb => cb.checked = false);
        
        if (!usuario) {
            alert('App: preencha os campos corretamente!.');
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
                tipo: tipo,
                route: 'atribuirNotaUsuario',
                notas :resultadoTexto,
                usuario: usuario
            },
            success: function(response) {
                    
            if(response.success.msg == 'true') {
               
                alert('Contagem atribuida com sucesso!')
            
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

    
    const desatribuirNota= () => {

        const resultadoTexto  = getNrosUnicosSelecionados();
        const usuario = document.getElementById('codUsu').value;
        const checkboxes = document.querySelectorAll('.linha-checkbox');
        checkboxes.forEach(cb => cb.checked = false);
        
       

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
                tipo: tipo,
                route: 'desatribuirNota',
                notas :resultadoTexto,
                usuario: usuario
            },
            success: function(response) {
                if(response.success.msg == 'ok') {
                    alert('Contagem removida com sucesso!')
                } else {
                    alert('Erro. Favor procurar o TI.');
                }                            
            },
            error: function(xhr, status, error) {
                alert('Erro na requisição AJAX: ' + error);
                console.log(xhr);
                console.log(status);
            }
        });

    }
    
//  selecionar todos os checkboxes
    function selecionarTodos(master) {
        const checkboxes = document.querySelectorAll('.linha-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
    } 



//busco usuario ao digitiar no campo entidade 
const buscaInfoNomeUsu = () => {

    const codusu  = document.getElementById('codUsu').value;
    const nomeusu  = document.getElementById('nomeUsu').value;


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

            codusu: codusu,
            nomeusu: nomeusu,
            route: 'buscaInfoNomeUsu'
        },
        success: function(response) {
            
            if(response.success) {
                document.getElementById('codUsu').value  = response.success.codusu;

                //document.getElementById('total').textContent = response.success.qtdseparar;
            } else {
             
                document.getElementById('codUsu').focus();
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

function adicionarItem() {
    document.getElementById("popup-overlay-pallet2").style.display = "flex";
    document.getElementById('codUsu').value  = ''; //esvazio campos
    document.getElementById('nomeUsu').value  = '';
    const checkboxes = document.querySelectorAll('.linha-checkbox');
    checkboxes.forEach(cb => cb.checked = false);

}


const buscaInfoCodUsu = () => {

    const codusu  = document.getElementById('codUsu').value;


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

            codusu: codusu,
            route: 'buscaInfoCodUsu'
        },
        success: function(response) {
            
            if(response.success) {
                document.getElementById('nomeUsu').value  = response.success.nomeusu;

                //document.getElementById('total').textContent = response.success.qtdseparar;
            } else {
             
                document.getElementById('codUsu').focus();
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