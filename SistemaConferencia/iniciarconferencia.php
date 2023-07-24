<?php

session_start();
include "../conexaophp.php";

if (isset($_POST['nota'])) {

	$nunota = $_POST['nota'][0];
	$usuario = $_SESSION['idUsuario'];

	$tsqlTipoPer = "SELECT CODTIPOPER, STATUSNOTA FROM TGFCAB WHERE NUNOTA = $nunota";
	$stmTipoPer = sqlsrv_query( $conn, $tsqlTipoPer);
	$rowTipoPer = sqlsrv_fetch_array( $stmTipoPer, SQLSRV_FETCH_NUMERIC);

	$tsql = "SELECT [sankhya].[AD_FN_RETORNA_STATUS_NOTA]($nunota)";
	$stm = sqlsrv_query( $conn, $tsql);
	$row = sqlsrv_fetch_array( $stm, SQLSRV_FETCH_NUMERIC);

	
	if($row[0] === 'N' && ($rowTipoPer[0] == 1720 || $rowTipoPer[0] == 1721 || $rowTipoPer[0] == 1722)){
		echo "A separação ainda não foi inciada";
	}
	else if($row[0] != 'C' && ($rowTipoPer[0] == 1720 || $rowTipoPer[0] == 1721 || $rowTipoPer[0] == 1722)){
		echo "A separação está em andamento";
	}
	else if($rowTipoPer[1] != 'L' && ($rowTipoPer[0] == 1720 || $rowTipoPer[0] == 1721 || $rowTipoPer[0] == 1722)){
		echo "A separação ainda não foi confirmada";
	}
	else{
		$tsqlGera1780 = "EXEC AD_STP_GERA1780_CONFERENCIA $nunota, $usuario";
		$stmGera1780 = sqlsrv_query( $conn, $tsqlGera1780);

		$tsql7 = "SELECT [sankhya].[RETORNA_NUNOTA_TGFVAR]($nunota)";
		$stm7 = sqlsrv_query( $conn, $tsql7);
		$row7 = sqlsrv_fetch_array( $stm7, SQLSRV_FETCH_NUMERIC);

		$tsqlCheckinPhp = "EXEC [sankhya].[AD_STP_CHECKIN_PHP] $usuario, $row7[0]";
		$stmCheckinPhp  = sqlsrv_query( $conn, $tsqlCheckinPhp);

		$tsqlCodTipoPer = "SELECT CODTIPOPER FROM TGFCAB WHERE NUNOTA = $row7[0]";
		$stmCodTipoPer = sqlsrv_query( $conn, $tsqlCodTipoPer);
		$rowCodTipoPer = sqlsrv_fetch_array( $stmCodTipoPer, SQLSRV_FETCH_NUMERIC);

		echo $rowCodTipoPer[0] ."/" .$row7[0];
	}
}
?>