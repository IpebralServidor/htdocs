<?php
include "../conexaophp.php";

$linhas = filter_input_array(INPUT_POST, FILTER_DEFAULT);

foreach ($linhas['id'] as $id => $linha) {
    $nunota = substr($id, stripos($id, "/") + 1);
    $codbarra = substr($id, 0, stripos($id, "/"));


    $tsql = "EXEC [sankhya].[AD_STP_INSERIR_PENDENCIA] $nunota, '$codbarra'";

    $stmt = sqlsrv_query($conn, $tsql);

}

echo "<script> alert('Item(ns) inseridos.'); </script>";
echo "<script> window.location.href='detalhesconferencia.php?nunota=$nunota' </script>";

?>
