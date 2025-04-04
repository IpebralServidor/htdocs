$(document).ready(function() {
    
    // Quando o botão "Insere Recebimentos" for clicado
    $('#insereRecebimentos').click(function() {
        // Cria um objeto FormData para enviar o arquivo e outros dados
        var formData = new FormData();
        var fileInput = $('#escolherArquivo')[0].files[0]; // Pega o arquivo selecionado
        var contaSelecionada = $('#account').val(); // Pega o valor da conta selecionada

        // Adiciona o arquivo e a conta ao FormData
        formData.append('excelFile', fileInput);
        formData.append('conta', contaSelecionada);

        // Faz a requisição AJAX
        $.ajax({
            url: 'insererecebimentos.php', // Arquivo PHP que será chamado
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


    // Quando o botão "Insere Liberações" for clicado
    $('#insereLiberacoes').click(function() {
        // Cria um objeto FormData para enviar o arquivo e outros dados
        var formData = new FormData();
        var fileInput = $('#escolherArquivo')[0].files[0]; // Pega o arquivo selecionado
        var contaSelecionada = $('#account').val(); // Pega o valor da conta selecionada

        // Adiciona o arquivo e a conta ao FormData
        formData.append('excelFile', fileInput);
        formData.append('conta', contaSelecionada);

        // Faz a requisição AJAX
        $.ajax({
            url: 'inserereliberacoes.php', // Arquivo PHP que será chamado
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

    // Quando o botão "Insere Liberações" for clicado
    $('#insereMovBancaria').click(function() {
        
        // Faz a requisição AJAX
        $.ajax({
            url: 'inseremovbancaria.php', // Arquivo PHP que será chamado
            type: 'HTML',
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
            }
            // ,
            // error: function(xhr, status, error) {
            //     // Exibe mensagem de erro
            //     alert('Erro: ' + error);
            // }
        });
    });



});