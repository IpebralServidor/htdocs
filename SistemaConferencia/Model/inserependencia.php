<?php
include "../../conexaophp.php";

$nunota = $_POST['nunota'];
$codbarra = $_POST['codigobarra'];

$tsql = "EXEC [sankhya].[AD_STP_INSERIR_PENDENCIA] $nunota, '$codbarra'";
$stmt = sqlsrv_query($conn, $tsql);
