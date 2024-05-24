const impressao = (tipoImpressao) => {
    const urlParams = new URLSearchParams(window.location.search);
    const numeroNota = urlParams.get("nunota");
    const gif = document.getElementById("loader");
    let file;
    // Define o nome do arquivo do iReport que será usado. 
    if (tipoImpressao === 'etiqueta') { 
        file = 'Cliente volume nota novo'; // Arquivo de etiqueta a ser impresso em impressora zebra
    } else if (tipoImpressao === 'vale') {
        file = 'Vale_novo'; // Arquivo de vale a ser impresso em impressora não fiscal
    }
    // Envia os dados de qual arquivo de impressão será processado
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '../Etiquetas/compileJasper.php',
        beforeSend: function() {
            gif.style.display = "block"
            gif.classList.add("loader")
        },
        complete: function() {
            gif.style.display = "none"
            gif.classList.remove("loader")
        },
        data: {
            nunota: numeroNota,
            arquivo: file,
            funcao: 'compileJasper'
        },
        success: function(msg) {
            // As impressoras cadastradas são nomeadas no padrão 'vale'  e 'etiqueta' para evitar ter de considerar nomes diferentes de cadastro entre máquinas.
            // É necessário instalar o client WebClientPrint6 na máquina do cliente.
            // Chama o WebClientPrint na máquina do cliente para enviar o arquivo de impressão direto para a impressora.
            jsWebClientPrint.print('printerName=' + tipoImpressao + '&filePath=' + 'C:/xampp/htdocs/SistemaConferencia/Etiquetas/nunotas/' + numeroNota + '/' + file + '.pdf');
            // Chama uma função que fecha a janela que o WebClientPrint abre no computador cliente
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../Etiquetas/compileJasper.php',
                beforeSend: function() {
                    gif.style.display = "block"
                    gif.classList.add("loader")
                },
                complete: function() {
                    gif.style.display = "none"
                    gif.classList.remove("loader")
                },
                data: {
                    funcao: 'fechaJanelaWcpp'
                },
                success: function() {
                }
            });
        }
    });
    // Configurações adicionais para a impressora de etiqueta no Linux: Alterar o ppd, criando um novo modelo de página com o tamanho "306.142 113.386"; Apagar as linhas de opção de DPI e deixar apenas a opção de 203 DPI.
}