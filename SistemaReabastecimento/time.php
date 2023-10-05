<?php
$_SESSION['tempo_fixo'] = 316; 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Timer</title>
</head>
<body>
    <h1>Timer</h1>
    <div id="timer">00:00:00</div>
    <button id="startButton" style="display: none;">Iniciar</button>
    <button id="pauseButton">Pausar</button>
    <button id="resumeButton">Continuar</button>

    <script>
        var timerInterval;
        var startTime = 0;
        var elapsedTime = 0;
        var isRunning = false;

        function updateTimer() {
            timerInterval = setInterval(function() {
                if (!isRunning) return;

                elapsedTime = Math.floor((Date.now() / 1000) - startTime);
                var fixedTime = <?php echo isset($_SESSION['tempo_fixo']) ? $_SESSION['tempo_fixo'] : 0; ?>;
                var totalElapsedTime = fixedTime + elapsedTime;

                var hours = Math.floor(totalElapsedTime / 3600);
                var minutes = Math.floor((totalElapsedTime % 3600) / 60);
                var seconds = totalElapsedTime % 60;

                var formattedTime = 
                    ("0" + hours).slice(-2) + ":" + 
                    ("0" + minutes).slice(-2) + ":" + 
                    ("0" + seconds).slice(-2);

                document.getElementById("timer").innerHTML = formattedTime;
            }, 1000);
        }

        document.getElementById("startButton").addEventListener("click", function() {
            if (!isRunning) {
                startTime = Math.floor(Date.now() / 1000) - elapsedTime;
                updateTimer();
                isRunning = true;
            }
        });

        document.getElementById("pauseButton").addEventListener("click", function() {
            clearInterval(timerInterval);
            isRunning = false;
        });

        document.getElementById("resumeButton").addEventListener("click", function() {
            if (!isRunning) {
                startTime = Math.floor(Date.now() / 1000) - elapsedTime;
                updateTimer();
                isRunning = true;
            }
        });

        // Inicialize o contador quando a página for carregada
        window.onload = function() {
            updateTimer();
            // Inicie o timer automaticamente
            document.getElementById("startButton").click();
        };
    </script>
</body>
</html>
