<?php

    include "../conexaophp.php";
    require_once '../App/auth.php';

    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];
  
    $tsql = "SELECT COUNT(1) FROM TSIUSU WHERE NOMEUSU = '$usuario'";
    $stmt = sqlsrv_query( $conn, $tsql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

    if($row[0] == 1){
        $tsql = "SELECT COUNT(1) FROM TSIUSU WHERE NOMEUSU = '$usuario' and AD_SENHA = '$senha'";
        $stmt = sqlsrv_query( $conn, $tsql);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

        if($row[0] == 1){
            echo "Concluido";
        }else{
            echo "Senha incorreta";
        }
    }else{
        echo "Usuario incorreto";
    }

?>