$(document).ready(function () {

    $('#insereTGFPAP').click(function () {

        var fileInput = $('#escolherArquivo')[0].files[0];
        var codigoParceiro = $('#codigoParceiro').val();
        var codigoUsuario = $('#codigoUsuario').val();

        // Validações
        if (!fileInput) {
            alert('Selecione um arquivo Excel.');
            return;
        }

        if (!codigoParceiro || codigoParceiro.trim() === '') {
            alert('Informe o Código do Parceiro.');
            $('#codigoParceiro').focus();
            return;
        }

        var formData = new FormData();
        formData.append('excelFile', fileInput);
        formData.append('codigoParceiro', codigoParceiro);
        formData.append('codigoUsuario', codigoUsuario);
        
        $.ajax({
            url: 'insereTGFPAP.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $("#loader").show();
            },
            complete: function () {
                $("#loader").hide();
            },
            success: function (response) {
                alert(response);
            },
            error: function (xhr, status, error) {
                alert('Erro ao enviar o arquivo: ' + error);
            }
        });
    });

});
