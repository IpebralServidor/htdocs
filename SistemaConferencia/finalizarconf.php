<?php

session_start();
include "../conexaophp.php";

//echo "string";
//$linhas = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//var_dump($linhas);

$qtdvol = $_POST['qtdvol'];
$volume = $_POST['volume'];
$pesobruto = $_POST['pesobruto'];
$nunota = $_POST['nunota'];
$observacao = $_POST['observacao'];
//;$observacao = ' ';
$usuconf = $_SESSION["idUsuario"];

$tsql5 = "select count(*) from [sankhya].[AD_FN_PRODUTOS_DIVERGENTES_CONFERENCIA]($nunota)";
$stmt5 = sqlsrv_query( $conn, $tsql5);
$row2 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_NUMERIC);
$linhas = $row2[0];

if($linhas > 0){
    $tsql4 = "EXEC [sankhya].[AD_STP_CORTAITENS_CONFERENCIA] $nunota, $usuconf, '$pesobruto', '$qtdvol', '$volume', '$observacao' ";
}else{
    $tsql4 = "EXEC [sankhya].[AD_STP_FINALIZAR_CONFERENCIA] $nunota, $usuconf, '$pesobruto', '$qtdvol', '$volume', '$observacao', '' ";
}
var_dump($tsql4);
$stmt4 = sqlsrv_query( $conn, $tsql4);
$row = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_NUMERIC);

echo $row[0];

?>