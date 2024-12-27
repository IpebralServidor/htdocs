<?php
include "../../../conexaophp.php";
require "../../../App/auth.php";


$limiteMensagens = $_POST["limiteMensagens"];



// Verificar o valor atual de USUREDE
$tsqlCheck = "SELECT ISNULL(USUREDE, 0) AS USUREDE FROM tsiusu WHERE codusu = $idUsuario";
$stmtCheck = sqlsrv_query($conn, $tsqlCheck);

// Obtém o resultado da consulta
$rowCheck = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);

// Inicializa o controle do popup
$exibirPopUp = false;

// Verifica se o resultado é válido e incrementa USUREDE se necessário
if ($rowCheck['USUREDE'] < $limiteMensagens) {
    // Incrementar o contador USUREDE
    $tsqlUpdate = "UPDATE tsiusu SET USUREDE = ISNULL(USUREDE, 0) + 1 WHERE codusu = $idUsuario";
    $stmtUpdate = sqlsrv_query($conn, $tsqlUpdate);

    echo 'S';
} else {
    echo 'N';
}
?>
