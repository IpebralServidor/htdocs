<?php

include "../../conexaophp.php";
require_once '../../App/auth.php';

$codemp = $_POST['codemp'];
$codlocal = $_POST['codlocal'];
$params = array($codemp, $codlocal);

$tsql = "DELETE FROM AD_LOCAIS_VAZIOS WHERE CODEMP = ? AND CODLOCAL = ?";
$stmt = sqlsrv_query($conn, $tsql, $params);

echo "Concluído";
