<?php

session_start();
include "../../conexaophp.php";

$nunota = $_POST['nunota'];
$usuconf = $_SESSION['idUsuario'];
if (!isset($_POST['linhasRecontar'])) {
    $linhasRecontar = [];
} else {
    $linhasRecontar = $_POST['linhasRecontar'];
}

$params = array($nunota, $usuconf);

$tsqlAbrirRecontagem = "EXEC AD_STP_ABRIR_RECONTAGEM_CONFERENCIA ?, ?";
$stmtAbrirRecontagem = sqlsrv_query($conn, $tsqlAbrirRecontagem, $params);

$row = sqlsrv_fetch_array($stmtAbrirRecontagem, SQLSRV_FETCH_NUMERIC);

$nuconf = $row[0];

$tsqlDeletarItemRecontagem = "DELETE FROM TGFCOI2 WHERE NUCONF = ? AND CODPROD = ? AND CONTROLE = ?";
foreach ($linhasRecontar as $row) {
    $params = array($nuconf, $row['CODPROD'], $row['CONTROLE']);
    sqlsrv_query($conn, $tsqlDeletarItemRecontagem, $params);
}

echo 'Recontagem aberta.';
