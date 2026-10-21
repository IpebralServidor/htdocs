<?php

session_start();
include "../../conexaophp.php";

header('Content-Type: text/plain; charset=utf-8');

// PRINT/avisos do SQL Server não devem ser tratados como erro pelo driver
sqlsrv_configure("WarningsReturnAsErrors", 0);

// Garante UTF-8 na mensagem que volta para a tela
function textoUtf8($texto)
{
    $texto = (string)$texto;
    return mb_check_encoding($texto, 'UTF-8') ? $texto : mb_convert_encoding($texto, 'UTF-8', 'Windows-1252');
}

// Executa a procedure passando o NUIMPORTACAO da sessão; a mensagem vem como o primeiro campo do SELECT retornado
$stmt = sqlsrv_query($conn, "SET NOCOUNT ON; EXEC AD_STP_FINALIZA_COTACAO_COMPRAS ?", array($_SESSION['nuimportacao']));

$mensagem = '';

if ($stmt === false) {
    // Só chega aqui se o SQL Server devolver um erro que a procedure não tratou
    $erros = sqlsrv_errors();
    $mensagem = $erros ? preg_replace('/^(\[[^\]]*\])+\s*/', '', $erros[0]['message']) : 'Erro ao finalizar a cotação.';
} else {
    do {
        if (sqlsrv_num_fields($stmt) > 0) {
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
            if ($row && $row[0] !== null) {
                $mensagem = $row[0];
                break;
            }
        }
    } while (sqlsrv_next_result($stmt));
}

echo textoUtf8(trim($mensagem));
