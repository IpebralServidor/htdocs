<?php
include "../conexaophp.php";

$nunota = $_POST['nunota'];
$codbarra = $_POST['codigobarra'];

//echo $nunota." ".$codbarra;

$tsql = "EXEC [sankhya].[AD_STP_INSERIR_PENDENCIA] $nunota, '$codbarra'";

$stmt = sqlsrv_query($conn, $tsql); 



// echo "<script> alert('Item(ns) inseridos.'); </script>";
// echo "<script> window.location.href='detalhesconferencia.php?nunota=$nunota' </script>";

?>
