<?php
include "../conexaophp.php";
session_start();



if(isset($_POST["aplicar"])){

    $request = $_POST["numeroNota"]; 
    $codusu = $_SESSION["idUsuario"];

    $tsqlCheckin = "EXEC [sankhya].[AD_STP_CHECKIN_PHP] $codusu, $request";
    $stmtCheckin = sqlsrv_query( $conn, $tsqlCheckin);

    $tsqlTipoNota = "SELECT * FROM [sankhya].[AD_FNT_PROXIMO_PRODUTO_REABASTECIMENTO] ($request)";
    $stmtTipoNota = sqlsrv_query( $conn, $tsqlTipoNota);
    $rowTipoNota = sqlsrv_fetch_array( $stmtTipoNota, SQLSRV_FETCH_ASSOC);

    $tsqlEhTransf = "   SELECT AD_PEDIDOECOMMERCE 
                        FROM TGFCAB 
                        WHERE NUNOTA = $request";
    $stmtEhTransf = sqlsrv_query( $conn, $tsqlEhTransf);
    $rowEhTransf = sqlsrv_fetch_array( $stmtEhTransf, SQLSRV_FETCH_NUMERIC);

    if($rowEhTransf[0] == "TRANSFAPP"){
        if(utf8_encode($rowTipoNota['TIPO_NOTA']) == "Abastecimento"){
            header('Location: menuseparacao.php?nunota=' .$request);
        }else{
            header('Location: reabastecimento.php?nunota=' .$request .'&fila=S');
        }
    }else{
        echo
            '<script>
                window.alert("Essa nota não foi feita pelo novo botão!")
                window.location.href="./";
            </script>';
    }

}

?>