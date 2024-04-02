<?php

session_start();
include "../../conexaophp.php";

$separador = $_POST['separador'];
$nunota2 = $_POST["nunota"];

$tsqlSeparador = "EXEC [sankhya].[AD_STP_ATRIBUIR_SEPARADOR] $nunota2, $separador";
$stmtSeparador = sqlsrv_query($conn, $tsqlSeparador);
