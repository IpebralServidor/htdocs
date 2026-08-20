$(document).ready(function () {

    $('#insereTGFPAP').click(function () {

        var fileInput = $('#escolherArquivo')[0].files[0];
        var codigoParceiroOrigem = $('#codigoParceiroOrigem').val();
        var codigoParceiroDestino = $('#codigoParceiroDestino').val();
        var codigoUsuario = $('#codigoUsuario').val();

        // Validações
        if (!fileInput) {
            alert('Selecione um arquivo Excel.');
            return;
        }

        if (!codigoParceiroDestino || codigoParceiroDestino.trim() === '') {
            alert('Informe o Código do Parceiro Destino.');
            $('#codigoParceiroDestino').focus();
            return;
        }

        var formData = new FormData();
        formData.append('excelFile', fileInput);
        formData.append('codigoParceiroDestino', codigoParceiroDestino);
        formData.append('codigoParceiroOrigem', codigoParceiroOrigem);
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
