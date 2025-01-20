<?php
include "../../conexaophp.php";

$codbarra = $_POST["codigodebarra"];

if ($codbarra != 0) {
	$tsql2 = " exec AD_STP_ENVIA_EMAIL_SEM_FOTO $codbarra ";
} else {
	echo 'erro';
}

$stmt2 = sqlsrv_query($conn, $tsql2);
