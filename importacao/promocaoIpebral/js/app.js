$(document).ready(function () {

    $('#inserePromocoes').click(function () {

        var fileInput = $('#escolherArquivo')[0].files[0];
        var numeroPromocao = $('#numeroPromocao').val();

        // Validações
        if (!fileInput) {
            alert('Selecione um arquivo Excel.');
            return;
        }

        if (!numeroPromocao || numeroPromocao.trim() === '') {
            alert('Informe o Número da Promoção.');
            $('#numeroPromocao').focus();
            return;
        }

        var formData = new FormData();
        formData.append('excelFile', fileInput);
        formData.append('numeroPromocao', numeroPromocao);

        $.ajax({
            url: 'inserepromocoes.php',
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
