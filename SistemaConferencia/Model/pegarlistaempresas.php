<?php

include "../../conexaophp.php";
session_start();

$usuarioLogado = $_SESSION["idUsuario"];
$tsqlEmpresaAtual = "SELECT CODEMP FROM TSIUSU WHERE CODUSU = $usuarioLogado";
$stmtEmpresaAtual = sqlsrv_query($conn, $tsqlEmpresaAtual);
$rowEmpresaAtual = sqlsrv_fetch_array($stmtEmpresaAtual, SQLSRV_FETCH_NUMERIC);

$tsqlTodasEmpresas = "SELECT DISTINCT CODEMP, NOMEFANTASIA FROM TSIEMP UNION SELECT 0, 'Todas as empresas'";
$stmtTodasEmpresas = sqlsrv_query($conn, $tsqlTodasEmpresas);

$empresasList = "";

while ($rowTodasEmpresas = sqlsrv_fetch_array($stmtTodasEmpresas, SQLSRV_FETCH_NUMERIC)) {
    if ($rowEmpresaAtual[0] == $rowTodasEmpresas[0]) {
        $selected = "selected";
    } else {
        $selected = "";
    }
    $empresasList .= "<option name='empresas' value='$rowTodasEmpresas[0]' $selected> $rowTodasEmpresas[1]</option>";
}

echo $empresasList;
