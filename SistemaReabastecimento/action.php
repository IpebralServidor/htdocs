<?php
include "../conexaophp.php";
session_start();



if(isset($_POST["aplicar"])){

    $request = $_POST["numeroNota"]; 
    $codusu = $_SESSION["idUsuario"];

    $tsqlCheckin = "EXEC [sankhya].[AD_STP_CHECKIN_PHP] $codusu, $request";
    $stmtCheckin = sqlsrv_query( $conn, $tsqlCheckin);

    header('Location: reabastecimento.php?nunota=' .$request);
}

?>