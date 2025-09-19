const urlParams = new URLSearchParams(window.location.search);
const nunota = urlParams.get('nunota');
const tipo = urlParams.get('tipo');

// Variáveis que guardarão o tempo de digitação dos campos
let tempoInicialReferencia = 0;
// Variáveis que guardarão o texto de digitação dos campos
let inputInicialReferencia = '';
// Variáveis para guardar se o campo foi bipado ou digitado
let referenciaBipado = '';
// Variável que guarda o tempo de input para ser considerado digitação ou leitor de código de barras
const tempoMaximoDigitacao = 250;

$(document).ready(function() {    
    document.getElementById('titleBoxH6').innerHTML = 'Produtos na nota: ' + nunota;
    document.getElementById('notaAtual').innerHTML = 'Nota: ' + nunota;
    buscaItensContagem(nunota, tipo);
    desabilitaFinalizaCont(); //libero ou nao o botao de finalizar contagem

    $('input').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Impede envio do form

            // Pega todos os inputs visíveis e habilitados
            const inputs = $('input:visible:enabled');
            const index = inputs.index(this);

            if (index > -1 && index + 1 < inputs.length) {
                inputs.eq(index + 1).focus();
            } else {
                // Último input: perde o foco
                $(this).blur();
            }
        }
    });

    $('input').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Impede envio do form

            // Pega todos os inputs visíveis e habilitados
            const inputs = $('input:visible:enabled');
            const index = inputs.index(this);

            if (index > -1 && index + 1 < inputs.length) {
                inputs.eq(index + 1).focus();
            } else {
                // Último input: perde o foco
                $(this).blur();
            }
        }
    });

    $('#ocorrenciaModal').on('show.bs.modal', function () {
        // Desmarca os botões de rádio
        $('input[name="ocorrencia"]').prop('checked', false);
    
        // Limpa o campo de texto
        $('#outros').val('');
    });
    // let inputQtd = document.getElementById('quantidade');
    // let qtdSeparar = document.getElementById('qtdseparar');
    // let total = document.getElementById('total');

    // inputQtd.addEventListener('input', function() {
    //     let qtd = (inputQtd.value == '' ? 0 : inputQtd.value);
    //     let tot = parseFloat(qtd) + parseFloat(qtdSeparar.innerHTML);
    //     total.textContent = ' ' + tot;
    // });
});

const buscaItensContagem = (nunota, tipo) => {
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
            nunota: nunota,
            tipo: tipo,
            route: 'buscaItensContagem'
        },
        success: function(response) {
            if(response.success) {
                document.getElementById('itens').innerHTML = response.success;
                let progressBar = document.getElementById('progress-bar');
                progressBar.style.width = response.progress_bar + '%';
                progressBar.innerText = response.progress_bar + '%';
            } else {
                alert('Erro: ' + response.error);
            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
        }
    });
}

const limpaCampo = (campo) => {
    document.getElementById(campo).value = '';
}

const iniciarMedicaoReferencia = () => {
    tempoInicialReferencia = Date.now();
}

const finalizarMedicaoReferencia = () => {
    let tempoFinalReferencia = Date.now();
    if(tempoFinalReferencia - tempoInicialReferencia > tempoMaximoDigitacao) {
        inputInicialReferencia = document.getElementById('referencia').value;
        togglePopupConfirmarReferencia();
        limpaCampo('referencia');
    } else {
        referenciaBipado = 'S';
        buscaInformacoesProduto();
    }
}

const togglePopupConfirmarReferencia = () => {
    document.getElementById('popupConfirmarReferencia').classList.toggle("active");
    document.getElementById('confirmacaoReferencia').value = '';
    document.getElementById('confirmacaoReferencia').focus();
}

const confirmaReferencia = () => {
    let inputNovoReferencia = document.getElementById('confirmacaoReferencia').value;
    if(inputNovoReferencia != '') {
        if(inputNovoReferencia != inputInicialReferencia) {
            alert('Valores digitados não batem. Verifique a digitação');
        } else {
            togglePopupConfirmarReferencia();
            document.getElementById('referencia').value = inputNovoReferencia;
            referenciaBipado = 'N';
            buscaInformacoesProduto(); 
        }
    } else {
        alert('Digite um valor.');
    }
}

const buscaInformacoesProduto = () => {
    const referencia = document.getElementById('referencia').value;
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
            nunota: nunota,
            referencia: referencia,
            tipo : tipo,
            route: 'buscaInformacoesProduto'
        },
        success: function(response) {
            if(response.success) {
                document.getElementById('imagemproduto').src = 'data:image/jpeg;base64,' + response.success.imagem;
                response.success.tipcontest != 'L' ? document.getElementById('lote').disabled = true : (document.getElementById('lote').disabled = false, document.getElementById('lote').focus());
                document.getElementById('lote').value = '';
                document.getElementById('descrprod').innerHTML = response.success.descrprod;
                document.getElementById('agrupmin').innerHTML = response.success.agrupmin;
                document.getElementById('peso').value  = response.success.peso;
                document.getElementById('largura').value  = response.success.largura;
                document.getElementById('altura').value  = response.success.altura;
                document.getElementById('comprimento').value  = response.success.espessura;
                document.getElementById('obsetiqueta').innerHTML  = response.success.obsetiqueta;
                document.getElementById('qtdseparar').innerHTML  = response.success.qtdseparar;
                document.getElementById('referenciaipb').innerHTML  = response.success.referencia;
                document.getElementById('CD1').innerHTML  = response.success.CD1;
                document.getElementById('CD3').innerHTML  = response.success.CD3;
                document.getElementById('codvol').innerHTML  = response.success.CODVOL;


                //document.getElementById('total').textContent = response.success.qtdseparar;
            } else {
                document.getElementById('referencia').value = '';
                document.getElementById('lote').value = '';
                document.getElementById('referencia').focus();
                alert('Erro: ' + response.error);
            }

            if(response.success.PRIMEIRAENTRADACODVOL == 'S') {

                document.getElementById('primeiraentrada').innerHTML ='<span style="color:red;">*PRIMEIRA ENTRADA DO FORNECEDOR: FAVOR SEPARAR</span>';

            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });   
}

const atualizarDimensoes = () => {
    const referencia = document.getElementById('referencia').value;
    const peso = document.getElementById('peso').value;
    const largura = document.getElementById('largura').value;
    const altura = document.getElementById('altura').value;
    const comprimento = document.getElementById('comprimento').value;

    //Validação para evitar valores inválidos
    if (peso == 0 || largura == 0 || altura == 0 || comprimento == 0) {
        alert('Favor preencher com valores válidos as medidas e peso.');
        return; // Interrompe a execução da função
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
            referencia: referencia,
            peso: peso,
            largura: largura,
            altura: altura,
            comprimento: comprimento,       
            tipo: tipo,     
            route: 'atualizarDimensoes'
        },
        success: function(response) {
            if(response.success) {
                alert(response.success.msg);
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

const desabilitaFinalizaCont = () => {
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
            route: 'desabilitaFinalizaCont'
        },
        success: function(response) {
            if(response.success.msg == 'false') {
                document.getElementById("finalizaContarBtn").disabled = true;
            } else {
                document.getElementById("finalizaContarBtn").disabled = false;
            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });   
}

const atualizaContagem = (qtdcont) => {
    const referencia = document.getElementById('referencia').value
    const codbalanca = document.getElementById('codbalanca').value
    const lote = document.getElementById('lote').value;
    const qtdseparar = document.getElementById('qtdseparar').innerHTML;


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
            referencia: referencia,
            nunota: nunota,
            tipo : tipo,    
            codbalanca: codbalanca,
            qtdcont: qtdcont,
            lote: lote,
            qtdseparar: qtdseparar,
            route: 'atualizarContagem'
        },
        success: function(response) {
            if(response.success) {
                alert(response.success.msg)
                atualizarDimensoes();
                location.reload();            
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

const verificaRecontagem = () => {
    const referencia = document.getElementById('referencia').value;
    const codbalanca = document.getElementById('codbalanca').value;
    const qtdcont = document.getElementById('quantidade').value;
    const lote = document.getElementById('lote');
    const qtdseparar = document.getElementById('qtdseparar').innerHTML;
    
    const peso = document.getElementById('peso').value;
    const largura = document.getElementById('largura').value;
    const altura = document.getElementById('altura').value;
    const comprimento = document.getElementById('comprimento').value;

   // Verifica se peso, largura ou altura são nulos, vazios ou iguais a zero
   
    if ((!peso || peso == 0 || !largura || largura == 0 || !altura || altura == 0 || !comprimento || comprimento == 0)) {
        alert('Verifique se as dimensões foram preenchidas corretamente.');
    } else if (quantidade < 0 || referencia.trim() === '' || (!lote.disabled && lote.value === '')) { //tirado trim() olhar caso do lote com diego 
        alert('Informe os campos corretamente.');
    } else {
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
                nunota: nunota,
                referencia: referencia,
                qtdcont: qtdcont,
                codbalanca: codbalanca,
                tipo:tipo,
                lote: lote.value,
                qtdseparar: qtdseparar,
                route: 'verificaRecontagem'
            },
            success: function(response) {
                if(response.success) {
                    alert(response.success.msg);
                    atualizarDimensoes();
                    location.reload();
                } else if(response.recontagem) {
                    let qtdneg = response.qtdneg;
                    let quantidadeRecont;
                    do {
                        quantidadeRecont = prompt(`O item ${referencia.trim()} deu divergência em relação a nota. Favor recontar e digite a quantidade novamente: `);
                        while(quantidadeRecont > qtdneg) {
                            quantidadeRecont = prompt(`Contagem acima do esperado! Verifique com gerente se já existem apontamentos e digite a quantidade novamente: `);
                        } 
                    }  while(quantidadeRecont == null || quantidadeRecont.trim() === ""); 
                    
                    if(isNaN(Number(quantidadeRecont))) {
                        alert('Digite uma quantidade válida!');
                    } else {
                        atualizaContagem(quantidadeRecont);
                    }
                } else {
                    alert('Erro: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert('Erro na requisição AJAX: ' + error);
            }
        });
    }
}

const abrirPopAutorizaTrava = () => {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '../routes/routes.php',
        // beforeSend: function() {
        //     $("#loader").show();
        // },
        // complete: function() {
        //     $("#loader").hide();
        // },
        data: {
            nunota: nunota,
            route: 'retornaQtdContada'
        },
        success: function(response) {

            document.getElementById("msg").textContent =   document.getElementById("msg").textContent 
                                                                                  
        }
    });



    document.getElementById('popAutorizaTrava').classList.toggle("active");
    document.getElementById('user').value = '';
    document.getElementById('senha').value = '';
}

const abrirPopQtdContada = () => {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '../routes/routes.php',
        // beforeSend: function() {
        //     $("#loader").show();
        // },
        // complete: function() {
        //     $("#loader").hide();
        // },
        data: {
            nunota: nunota,
            route: 'retornaQtdContada'
        },
        success: function(response) {

            document.getElementById("msg3").textContent = response.success;
                                                                                  
        }
    });


    document.getElementById("popAutorizaTrava").classList.remove("active");
    document.getElementById('popQtdContada').classList.toggle("active");
    
}



function fecharPopAutorizaContagem() {
    document.getElementById("popAutorizaTrava").classList.remove("active");
    document.getElementById("popQtdContada").classList.remove("active");

}


const autorizaTrava = () => {


    let user = document.getElementById('user').value;
    let senha = document.getElementById('senha').value;
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '../routes/routes.php',
        // beforeSend: function() {
        //     $("#loader").show();
        // },
        // complete: function() {
        //     $("#loader").hide();
        // },
        data: {
            user: user,
            senha: senha,
            route: 'autorizatrava'
        },
        success: function(response) {
           console.log(response);
            if(response.success.msg == 'erro') {
                alert('Não foi possível autorizar.');
            } else {
                abrirPopQtdContada();
                //finalizarContagem();
            }
        }
    });
}



const verificaFinalizaContagem = () => {
    

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
            nunota: nunota,
            tipo : tipo,    
            route: 'verificaFinalizaContagem'
        },
         success: function(response) {
            if(response.success.msg == 'senha') {
                abrirPopAutorizaTrava();                        
            } 
            else  {
                 verificaQtdSeparar() ;
            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });   
}


 

 

function verificaQtdSeparar()  {

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
            nunota: nunota,
            tipo : tipo,    
            route: 'verificaQtdSeparar'
        },
         success: function(response) {
            if(response.success.codemp == '3' && tipo == 'O' && response.success.qtdgondola != 0 ) {
               
                $('#popupConfirmacao').find('.mensagem-qtd').remove(); // Remove mensagem anterior, se existir
                // $('#popupConfirmacao').prepend('<p class="mensagem-qtd">  Palete CD3 Emp.3: ' + response.success.qtdseparar + '</p>' 
                //                                 + '<p class="mensagem-qtd">  Palete CD5 Emp.3: ' + response.success.qtdresto + '</p>'
                //                                 + '<p class="mensagem-qtd">  Palete Gondola: ' + response.success.qtdgondola + '</p>'

                // );
                
                  $('#popupConfirmacao').prepend(`
                <div class="mensagem-qtd" style="margin-bottom: 10px;">
                    <label for="inputQtdSeparar">Palete CD3 Emp.3:</label>
                    <input 
                        type="number" 
                        id="inputQtdSeparar" 
                        placeholder="${response.success.qtdseparar}" 
                        style="margin-left: 10px; padding: 5px; width: 100px;" 
                    />
                </div>

                 <div class="mensagem-qtd" style="margin-bottom: 10px;">
                    <label for="inputQtdSepararResto"> Palete CD5 Emp.3:</label>
                    <input 
                        type="number" 
                        id="inputQtdSepararResto" 
                        placeholder="${response.success.qtdresto}" 
                        style="margin-left: 10px; padding: 5px; width: 100px;" 
                    />
                </div>

               
            `);

                $('#overlay, #popupConfirmacao').show();
 
                $('#btnSim').off('click').on('click', function () {
                const qtdseparar = $('#inputQtdSeparar').val(); // <-- valor digitado
                const qtdResto = $('#inputQtdSepararResto').val(); // <-- valor digitado
                    $('#overlay, #popupConfirmacao').hide();
                   finalizarContagemSeparar(qtdseparar,qtdResto,0,0,0,response.success.qtdcont);
                });
                            
            } 

           else if (response.success.codemp == '3' && tipo == 'O' && response.success.qtdgondola == 0 ){
              
               $('#popupConfirmacao').find('.mensagem-qtd').remove(); // Remove mensagem anterior, se existir
                // $('#popupConfirmacao').prepend('<p class="mensagem-qtd">  Palete CD3 Emp.3: ' + response.success.qtdseparar + '</p>' 
                //                                 + '<p class="mensagem-qtd">  Palete CD5 Emp.3: ' + response.success.qtdresto + '</p>'
                // );
                
                  $('#popupConfirmacao').prepend(`
                <div class="mensagem-qtd" style="margin-bottom: 10px;">
                    <label for="inputQtdSeparar">Palete CD3 Emp.3:</label>
                    <input 
                        type="number" 
                        id="inputQtdSeparar" 
                        placeholder="${response.success.qtdseparar}" 
                        style="margin-left: 10px; padding: 5px; width: 100px;" 
                    />
                </div>

                 <div class="mensagem-qtd" style="margin-bottom: 10px;">
                    <label for="inputQtdSepararResto"> Palete CD5 Emp.3:</label>
                    <input 
                        type="number" 
                        id="inputQtdSepararResto" 
                        placeholder="${response.success.qtdresto}" 
                        style="margin-left: 10px; padding: 5px; width: 100px;" 
                    />
                </div>
            `);

                $('#overlay, #popupConfirmacao').show();
 
                $('#btnSim').off('click').on('click', function () {
                const qtdresto = $('#inputQtdSepararResto').val(); // <-- valor digitado
                const qtdseparar = $('#inputQtdSeparar').val(); // <-- valor digitado

                    $('#overlay, #popupConfirmacao').hide();
                finalizarContagemSeparar(qtdseparar,qtdresto,0,0,0,response.success.qtdcont);
                });
              
            
            // se tiver preenchido qtdgondola
                //     mostra o campo qtdgondola
                // se tiver preenchido qtdseparar
                //     mostra o campo qtdseparar
                // caso nao seja o caso de mandar tudo pra gondola    
                //     mostra o campo campo qtdpadrao 
           }
           else if(response.success.codemp == '1' && tipo == 'O' && response.success.qtdgondola != 0 ){

              $('#popupConfirmacao').find('.mensagem-qtd').remove(); // Remove mensagem anterior, se existir
                // $('#popupConfirmacao').prepend('<p class="mensagem-qtd">  Palete CD5 Emp.1: ' + response.success.qtdseparar + '</p>' 
                //                                 + '<p class="mensagem-qtd">  Palete Gondola: ' + response.success.qtdgondola + '</p>'

                // );

                  $('#popupConfirmacao').prepend(`
                <div class="mensagem-qtd" style="margin-bottom: 10px;">
                    <label for="inputQtdSepararResto">Palete CD5 Emp.1:</label>
                    <input 
                        type="number" 
                        id="inputQtdSepararResto" 
                        placeholder="${response.success.qtdresto}" 
                        style="margin-left: 10px; padding: 5px; width: 100px;" 
                    />
                </div>

                 <div class="mensagem-qtd" style="margin-bottom: 10px;">
                    <label for="inputQtdSepararGondola">Palete Gondola:</label>
                    <input 
                        type="number" 
                        id="inputQtdSepararGondola" 
                        placeholder="${response.success.qtdgondola}" 
                        style="margin-left: 10px; padding: 5px; width: 100px;" 
                    />
                </div>`);


                $('#overlay, #popupConfirmacao').show();
 
                $('#btnSim').off('click').on('click', function () {
                const qtdresto = $('#inputQtdSepararResto').val(); // <-- valor digitado
                const qtdgondola = $('#inputQtdSepararGondola').val(); // <-- valor digitado

                    $('#overlay, #popupConfirmacao').hide();
                finalizarContagemSeparar(0,0,qtdgondola,qtdresto,0,response.success.qtdcont);

                });
            
             

           }
            else if(response.success.codemp == '1' && tipo == 'O' && response.success.qtdgondola == 0 ){

             $('#popupConfirmacao').find('.mensagem-qtd').remove(); // Remove mensagem anterior, se existir

            $('#popupConfirmacao').prepend(`
                <div class="mensagem-qtd" style="margin-bottom: 10px;">
                    <label for="inputQtdSepararResto">Palete CD5 Emp.1:</label>
                    <input 
                        type="number" 
                        id="inputQtdSepararResto" 
                        placeholder="${response.success.qtdresto}" 
                        style="margin-left: 10px; padding: 5px; width: 100px;" 
                    />
                </div>
            `);

            $('#overlay, #popupConfirmacao').show();

            $('#btnSim').off('click').on('click', function () {
                const qtdresto = $('#inputQtdSepararResto').val(); // <-- valor digitado

                const qtdDigitada = $('#inputQtdSeparar').val() || response.success.qtdseparar; // Usa valor digitado ou placeholder
                 finalizarContagemSeparar(0,0,0,qtdresto,0,response.success.qtdcont);
                $('#overlay, #popupConfirmacao').hide();
            });


           }
             else if(response.success.codemp == '10' && tipo == 'O' && response.success.qtdgondola != 0 ) {

              $('#popupConfirmacao').find('.mensagem-qtd').remove(); // Remove mensagem anterior, se existir
               
                   $('#popupConfirmacao').prepend(`
                <div class="mensagem-qtd" style="margin-bottom: 10px;">
                    <label for="inputQtdSepararResto">Palete CD5 Emp.10:</label>
                    <input 
                        type="number" 
                        id="inputQtdSepararResto" 
                        placeholder="${response.success.qtdresto}" 
                        style="margin-left: 10px; padding: 5px; width: 100px;" 
                    />
                </div>

                 <div class="mensagem-qtd" style="margin-bottom: 10px;">
                    <label for="inputQtdSepararQtdgondola">Palete Gondola:</label>
                    <input 
                        type="number" 
                        id="inputQtdSepararQtdgondola" 
                        placeholder="${response.success.qtdgondola}" 
                        style="margin-left: 10px; padding: 5px; width: 100px;" 
                    />
                </div>
            `);

                $('#overlay, #popupConfirmacao').show();
 
                $('#btnSim').off('click').on('click', function () {

                    const qtdresto = $('#inputQtdSepararResto').val(); // <-- valor digitado
                    const qtdgondola = $('#inputQtdSepararQtdgondola').val(); // <-- valor digitado

                    $('#overlay, #popupConfirmacao').hide();
                 finalizarContagemSeparar(0,0,qtdgondola,0,qtdresto,response.success.qtdcont);

                });

           }  
              else if(response.success.codemp == '10' && tipo == 'O' && response.success.qtdgondola == 0 ) {

              $('#popupConfirmacao').find('.mensagem-qtd').remove(); // Remove mensagem anterior, se existir
               

                   $('#popupConfirmacao').prepend(`
                <div class="mensagem-qtd" style="margin-bottom: 10px;">
                    <label for="inputQtdSepararResto">Palete CD5 Emp.10:</label>
                    <input 
                        type="number" 
                        id="inputQtdSepararResto" 
                        placeholder="${response.success.qtdresto}" 
                        style="margin-left: 10px; padding: 5px; width: 100px;" 
                    />
                </div>

            `);

                $('#overlay, #popupConfirmacao').show();
 
                $('#btnSim').off('click').on('click', function () {

                    const qtdresto = $('#inputQtdSepararResto').val(); // <-- valor digitado

                    $('#overlay, #popupConfirmacao').hide();
                    finalizarContagemSeparar(0,0,0,0,qtdresto,response.success.qtdcont);

                });
            
              
           }
           
           else if(response.success.codemp == '1' && tipo == 'O' && response.success.qtdgondola == 0 ){

              $('#popupConfirmacao').find('.mensagem-qtd').remove(); // Remove mensagem anterior, se existir
                $('#popupConfirmacao').prepend('<p class="mensagem-qtd">  Palete CD5 Emp.1: ' + response.success.qtdseparar + '</p>' 

                );

                    $('#popupConfirmacao').prepend(`
                <div class="mensagem-qtd" style="margin-bottom: 10px;">
                    <label for="inputQtdSepararResto">Palete CD5 Emp.1:</label>
                    <input 
                        type="number" 
                        id="inputQtdSepararResto" 
                        placeholder="${response.success.qtdresto}" 
                        style="margin-left: 10px; padding: 5px; width: 100px;" 
                    />
                </div>

            `);

                $('#overlay, #popupConfirmacao').show();
 
                $('#btnSim').off('click').on('click', function () {
                     
                    const qtdresto = $('#inputQtdSepararResto').val(); // <-- valor digitado

                    $('#overlay, #popupConfirmacao').hide();
                    finalizarContagemSeparar(0,0,0,qtdresto,0,response.success.qtdcont);

                });
           }   
            
           else  {
                //window.alert("nao entrou")
                finalizarContagem('N');
                 }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });   
  }
 
 
 
 const finalizarContagem = (separar) => {
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
             nunota: nunota,
             tipo : tipo,    
             route: 'finalizarContagem',
             separar : separar
         },
         success: function(response) {
             if(response.success) {
                 alert(response.success.msg);
                 window.location.href = '../View/index.php?tipo=' + tipo ;
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

 
 
 const finalizarContagemSeparar = (qtdCD3EMP3,qtdCD5EMP3,QTDGONDOLA,qtdCD5EMP1,qtdCD5EMP10,QTDCONT) => {
    
    if( Number(qtdCD3EMP3) +
        Number(qtdCD5EMP3) +
        Number(QTDGONDOLA) +
        Number(qtdCD5EMP1) +
        Number(qtdCD5EMP10) !== Number(QTDCONT)){
        
        window.alert("APP: Quantidade(s) digitada(s) divergentes com o total contado!");
        verificaQtdSeparar();
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
             nunota: nunota,
             tipo : tipo,    
             route: 'finalizarContagemSeparar',
             qtdCD3EMP3: qtdCD3EMP3,
             qtdCD5EMP3: qtdCD5EMP3,
             QTDGONDOLA: QTDGONDOLA,
             qtdCD5EMP1:qtdCD5EMP1,
             qtdCD5EMP10:qtdCD5EMP10,
             QTDCONT:QTDCONT

         },
         success: function(response) {
             if(response.success) {
                 alert(response.success.msg);
                 window.location.href = '../View/index.php?tipo=' + tipo ;
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
 
 function ocultaoverlay() {
        document.getElementById("popupConfirmacao").style.display = "none";
        document.getElementById("overlay").style.display = "none";


    }

const mostraContagens = (nucontite) => {
    $.ajax({
        method: 'GET', 
        dataType: 'json', 
        url: '../routes/routes.php',
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nucontite: nucontite,
            route: 'mostraContagens'
        },
        success: function(response) {
            if(response.success) {
                document.getElementById('contagens').innerHTML = response.success;
                $('#subcontagemModal').modal('show');
            }
        },
        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
    });
}

const aplicarOcorrencia = () => {
    let referencia = document.getElementById("referencia").value;
    let lote = document.getElementById("lote");

    if (!referencia || (!lote.value && !lote.disabled)) {
        alert('Preencha todos os campos antes de registrar uma ocorrência!');
    } else {
        let opcao = $("input[name='ocorrencia']:checked").val();
        let observacao = document.getElementById("outros").value;
        if (opcao == 'undefined') {
            opcao = '';
        }
        if(opcao == '' && observacao == '') {
            alert('Nenhuma ocorrencia preenchida!');
        } else {
            let ocorrencia = opcao + ' ' + observacao;
            $.ajax({
                method: 'POST', 
                dataType: 'json', 
                url: '../routes/routes.php',
                beforeSend: function() {
                    $("#loader").show();
                },
                complete: function() {
                    $("#loader").hide();
                },
                data: {
                    nunota: nunota,
                    tipo: tipo,
                    referencia: referencia,
                    lote: lote.value,
                    ocorrencia: ocorrencia,
                    route: 'aplicarOcorrencia'
                },
                success: function(response) {
                    if(response.success) {
                        alert(response.success.msg);
                        $('#ocorrenciaModal').modal('hide');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Erro na requisição AJAX: ' + error);
                    console.log(xhr);
                    console.log(status);
                }
            });
        }
    }
}

const editQtd = (nucontsub) => {
    let td = document.getElementById('qtd_' + nucontsub);
    let currentValue = td.innerText;
    
    td.innerHTML = "<input type='number' id='input_" + nucontsub + "' class='form-control' value='" + currentValue + "' style='text-align: right'/>";

    document.getElementById('input_' + nucontsub).select();

    let editButton = document.querySelector('#row_' + nucontsub + ' i');
    editButton.className = 'fa-solid fa-circle-check'; 
    editButton.setAttribute('onclick', 'saveQtd(' + nucontsub + ')'); 
}


const saveQtd = (nucontsub) => {
    let input = document.getElementById('input_' + nucontsub);
    let newValue = input.value;
    $.ajax({
        method: 'POST', 
        dataType: 'json', 
        url: '../routes/routes.php',
        beforeSend: function() {
            $("#loader").show();
        },
        complete: function() {
            $("#loader").hide();
        },
        data: {
            nucontsub: nucontsub,
            qtd: newValue,
            nunota: nunota,
            tipo: tipo,
            route: 'editaSubContagem'
        },
        success: function(response) {
            if(response.success) {
                document.getElementById('qtd_' + nucontsub).innerText = newValue;
                document.getElementById('dt_' + nucontsub).innerText = response.success;

                let editButton = document.querySelector('#row_' + nucontsub + ' i');
                editButton.className = 'fa-solid fa-pen';
                editButton.setAttribute('onclick', 'editQtd(' + nucontsub + ')');
                buscaItensContagem(nunota, tipo)

            }
            else if (response.error){
                alert(response.error)
            }
        },

        error: function(xhr, status, error) {
            alert('Erro na requisição AJAX: ' + error);
            console.log(xhr);
            console.log(status);
        }
        
    });
}

function setaVoltar()  {
    window.location.href = 'index.php'+ '?tipo=' +  tipo
 }