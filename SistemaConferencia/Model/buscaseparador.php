<?php

include "../../conexaophp.php";

$nunota = $_POST['nota'];

$params = array($nunota);

$tsql = "SELECT AD_SEPARADOR, CODTIPOPER, CODPARCTRANSP, CODEMPPREF, TGFCAB.CODEMP
         FROM TGFCAB INNER JOIN
              TGFPAR ON TGFPAR.CODPARC = TGFCAB.CODPARC
         WHERE NUNOTA = ?";
$stmt = sqlsrv_query($conn, $tsql, $params);

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

// CODPARCTRANSP 12092 é Apanha Balcão, para pedidos que serão retirados no balcão
$tops = array(1721, 1722);
if ($row[0] === NULL && ($row[1] == 1720 || (in_array($row[1], $tops) && $row[2] == 12092) || $row[3] == 5 || $row[3] == 30 || $row[4] == 7)) {
    echo $row[0];
} else {
    echo 'ok';
}
