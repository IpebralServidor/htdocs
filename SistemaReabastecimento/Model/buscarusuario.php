<?php
include "../../conexaophp.php";
require_once '../../App/auth.php';

$codusu = $_POST["codusu"];

$tsqlBuscaUsu = "SELECT CODUSU, NOMEUSU FROM TSIUSU WHERE CODUSU = $codusu";
$stmtBuscaUsu = sqlsrv_query($conn, $tsqlBuscaUsu);
$rowBuscaUsu = sqlsrv_fetch_array($stmtBuscaUsu, SQLSRV_FETCH_ASSOC);

echo $rowBuscaUsu['CODUSU'] . '|' . $rowBuscaUsu['NOMEUSU'];
