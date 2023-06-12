<?php

session_start();
include "../conexaophp.php";

//echo "string";
//$linhas = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//var_dump($linhas);

$qtdvol = $_POST['QTDVOLUME'];
$volume = $_POST['VOLUME'];
$pesobruto = $_POST['PESOBRUTO'];
$nunota = $_POST['NUNOTACONF'];
$observacao = $_POST['OBSERVACAO'];
$usuconf = $_SESSION["idUsuario"];

echo $qtdvol;
echo "<br>";
echo $volume;
echo "<br>";
echo $pesobruto;
echo "<br>";
echo $nunota;


   $tsql4 = "EXEC [sankhya].[AD_STP_FINALIZAR_CONFERENCIA] $nunota, $usuconf, '$pesobruto', $qtdvol, '$volume', '$observacao' ";

    $stmt4 = sqlsrv_query( $conn, $tsql4); 



    echo "<script> alert('Conferência finalizada.'); </script>"; 
    header("Location: listaconferencia.php");
    


?>