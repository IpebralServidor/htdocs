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


   $tsql4 = "EXEC [sankhya].[AD_STP_FINALIZAR_CONFERENCIA] $nunota, $usuconf, '$pesobruto', $qtdvol, '$volume', '$observacao' ";

    $stmt4 = sqlsrv_query( $conn, $tsql4);

    $row = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_NUMERIC);

    echo $row[0];

?>