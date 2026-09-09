<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

$nuimportacao = $_SESSION['nuimportacao'];
$referenciaForn = $_POST['id'];
$codprod = $_POST['codprod'];

$tsql = "EXEC SANKHYA.AD_STP_EXCLUIR_REFERENCIA_IMPORTACAO_COTACAO_ITE_PAP ?,?,?";
$params = array($nuimportacao, $referenciaForn, $codprod);

$stmt = sqlsrv_query($conn, $tsql, $params);

if ($stmt === false) {
    echo json_encode(['sucesso' => false, 'mensagem' => print_r(sqlsrv_errors(), true)]);
    exit;
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

if ($row) {
    echo json_encode(['sucesso' => true, 'mensagem' => $row[0]]);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhum registro encontrado para excluir.']);
}