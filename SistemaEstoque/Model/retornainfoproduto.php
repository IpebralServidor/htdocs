<?php
include "../../conexaophp.php";
session_start(); //Iniciando a sessão

$produto = $_POST["produto"];
$codparcorig = $_POST["codparc"];

$tsql = "DECLARE @CODBARRA VARCHAR(100) = '$produto'

			SELECT TGFPRO.REFERENCIA,
				   ISNULL(TGFPAP.CODPROPARC,''),
				   TGFPRO.DESCRPROD
			FROM TGFPRO INNER JOIN	
				 TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD LEFT JOIN
				 TGFPAP ON TGFPAP.CODPROD = TGFPRO.CODPROD
					   AND TGFPAP.CODPARC = $codparcorig
					   AND TGFPAP.SEQUENCIA = 0
			WHERE TGFBAR.CODBARRA = @CODBARRA";

$stmt = sqlsrv_query($conn, $tsql);

if ($stmt) {
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
		echo $row[0] . '/' . $row[1] . '/' . $row[2];
	}
}
