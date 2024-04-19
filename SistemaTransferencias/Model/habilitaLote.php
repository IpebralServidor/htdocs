<?php

include "../../conexaophp.php";

$referencia = $_POST["referencia"];
$params = array($referencia);

$tsql = "SELECT TIPCONTEST FROM TGFPRO 
        WHERE REFERENCIA = ?
        ";

$stmt = sqlsrv_query($conn, $tsql, $params);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (isset($row['TIPCONTEST'])) {
    echo $row['TIPCONTEST'];
} else {
    echo 'N';
}
