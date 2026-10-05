$(document).ready(function () {

    $('#insereUNIMED').click(function () {

        var fileInput = $('#escolherArquivo')[0].files[0];
        var tipoArquivo = $('#tipoArquivo').val();
        var empresa = $('#empresa').val();

        // Validações
        if (!fileInput) {
            alert('Selecione o PDF da fatura.');
            return;
        }

        if (!empresa) {
            alert('Selecione a Empresa (Ipebral ou Mecontech).');
            return;
        }

        if (!tipoArquivo) {
            alert('Selecione o Tipo de Arquivo (UNIMAX, UNIPART ou Odonto).');
            return;
        }

        var formData = new FormData();
        formData.append('pdfFile', fileInput);
        formData.append('tipoArquivo', tipoArquivo);
        formData.append('empresa', empresa);

        $.ajax({
            url: 'insereUNIMED.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $("#loader").show();
                $("#resultado").hide();
            },
            complete: function () {
                $("#loader").hide();
            },
            success: function (response) {
                var ehErro = response.indexOf('ERRO:') === 0;
                $("#resultado")
                    .removeClass('resultado-erro resultado-sucesso')
                    .addClass(ehErro ? 'resultado-erro' : 'resultado-sucesso')
                    .text(response)
                    .show();
            },
            error: function (xhr, status, error) {
                $("#resultado")
                    .removeClass('resultado-sucesso')
                    .addClass('resultado-erro')
                    .text('Erro ao enviar o arquivo: ' + error + "\n\n" + xhr.responseText)
                    .show();
            }
        });
    });

});
