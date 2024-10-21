<?php
include "../../conexaophp.php";
session_start();

try {
	$codbarra = trim($_POST["codbarra"], " ");
	$quantidade = str_replace(',', '.', $_POST["quantidade"]);
	$controle = $_POST["controle"];
	$nunota2 = $_POST["nunota"];
	$codusu = $_SESSION['idUsuario'];
	$endereco = $_POST["endereco"];
	$volume = $_POST["volume"];

	if ($quantidade == '') {
		$quantidade = 0;
	}
	if ($quantidade != "0") {
		$_SESSION['codbarraselecionado'] = $codbarra;

		$tsql6 = "exec AD_STP_INSEREITEM_CONFERENCIA $nunota2 , '$codbarra' , $quantidade , '$controle', $codusu, $endereco, $volume ";
		$stmt6 = sqlsrv_query($conn, $tsql6);

		if ($stmt6 === false) {
			throw new Exception('Erro ao executar a consulta SQL: ' . print_r(sqlsrv_errors(), true));
		}

		$row3 = sqlsrv_fetch_array($stmt6, SQLSRV_FETCH_ASSOC);

		if ($row3['ErrorCode'] !== 0) {
			echo json_encode(['success' => false, 'errorCode' => $row3['ErrorCode'], 'errorMessage' => $row3['ErrorMessage']]);
		} else {
			echo json_encode(['success' => true, 'data' => "<script> window.location.href='../View/detalhesconferencia.php?nunota=$nunota2' </script>"]);
		}
	} else {
		echo json_encode(['success' => false, 'errorMessage' => 'Quantidade deve ser maior que zero.']);
	}
} catch (Exception $e) {
	echo json_encode(['success' => false, 'errorMessage' => $e->getMessage()]);
}
