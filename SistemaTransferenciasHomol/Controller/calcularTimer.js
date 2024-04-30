function calcularTempo(nunota){
    let tempInic = 0;

    function registrarTempo() {
        $.ajax
        ({
            type: 'POST',//Método que está sendo utilizado.
            dataType: 'html',//É o tipo de dado que a página vai retornar.
            url: '../Model/time.php',//Indica a página que está sendo solicitada.
            data: {nunota: nunota},//Dados para consulta
            success: function (msg)
            {
                tempInic = msg

                let hh = Math.floor(tempInic / 3600);
                let mm = Math.floor((tempInic % 3600) / 60);
                let ss = tempInic % 60;

                if(hh < 10){ hh = '0'+hh }
                if(mm < 10){ mm = '0'+mm }
                if(ss < 10){ ss = '0'+ss }

                let time = (`${hh}: ${mm}: ${ss}`);
                tempInic++; // Incrementar o tempo a cada execução

                document.getElementById("timer").innerHTML = time;
            }

        });
    }

    setInterval(registrarTempo, 1000);
}

