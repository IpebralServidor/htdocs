<?php
include "../../../conexaophp.php";

$codbarra = $_POST["codigodebarra"];

$params = array($codbarra);
$tsql2 = "exec [STP_ENVIA_EMAIL_PRODUTO_SEM_FOTO] ? ";

$stmt2 = sqlsrv_query($conn, $tsql2, $params);
