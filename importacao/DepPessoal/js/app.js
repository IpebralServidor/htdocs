$(document).ready(function() {
    
    // Quando o botão "Insere Recebimentos" for clicado
    $('#insereBancoDeHoras').click(function() {
        // Cria um objeto FormData para enviar o arquivo e outros dados
        var formData = new FormData();
        var fileInput = $('#escolherArquivo')[0].files[0]; // Pega o arquivo selecionado
        var dataInicial = $('#dataInicial').val(); // Pega o valor da Data Inicial
        var dataFinal = $('#dataFinal').val(); // Pega o valor da Data Final

        // Adiciona o arquivo e a conta ao FormData
        formData.append('excelFile', fileInput);
        formData.append('dataInicial', dataInicial);
        formData.append('dataFinal', dataFinal);

        // Faz a requisição AJAX
        $.ajax({
            url: 'insereBancoDeHoras.php', // Arquivo PHP que será chamado
            type: 'POST',
            data: formData,
            processData: false, // Impede o jQuery de processar os dados
            contentType: false, // Impede o jQuery de definir o contentType
            beforeSend: function() {
                // Você pode exibir um loader aqui, se quiser
                $("#loader").show();
            },

            complete: function() {
                // Aqui você pode esconder o loader
                $("#loader").hide();
            },

            success: function(response) {
                // Exibe a resposta do servidor
                alert(response);
            },
            error: function(xhr, status, error) {
                // Exibe mensagem de erro
                alert('Erro ao enviar o arquivo: ' + error);
            }
        });
    });





});