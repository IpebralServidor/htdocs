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
// $observacao = $_POST['OBSERVACAO'];
$observacao = ' ';
$usuconf = $_SESSION["idUsuario"];

echo $qtdvol;
echo "<br>";
echo $volume;
echo "<br>";
echo $pesobruto;
echo "<br>";
echo $nunota;
echo "<br>";
echo $observacao;
echo "<br>";
echo $usuconf;



   $tsql4 = "EXEC [sankhya].[AD_STP_FINALIZAR_CONFERENCIA] $nunota, $usuconf, '$pesobruto', $qtdvol, '$volume', '$observacao' ";

   var_dump($tsql4);
    $stmt4 = sqlsrv_query( $conn, $tsql4); 



    echo "<script> alert('Conferência finalizada.'); </script>"; 
    //header("Location: listaconferencia.php");
    


?>