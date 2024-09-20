<?php
include "../../conexaophp.php";
try {
    $nunota = $_POST["nunota"];
    $tiponota = $_POST["tiponota"];

    $params = array($tiponota, $nunota);
    $tsql = "
            DECLARE @TIPONOTA VARCHAR(1) = ?
            DECLARE @NUNOTA INT = ?
            SELECT TOP 1 CAB.NUNOTA AS NUNOTA,
                         CONCAT(PAR.CODPARC, ' - ', PAR.RAZAOSOCIAL) AS PARCEIRO
            FROM AD_TGFABSTITE ABSTITE INNER JOIN
                AD_TGFABSTCAB ABSTCAB ON ABSTITE.NUNOTA = ABSTCAB.NUNOTA INNER JOIN
                TGFCAB CAB ON ABSTCAB.NUNOTA_CAB = CAB.NUNOTA INNER JOIN
                TGFPAR PAR ON CAB.CODPARC = PAR.CODPARC
            WHERE ABSTITE.NUNOTATRF = CASE
                                            WHEN @TIPONOTA = 'S' THEN @NUNOTA
                                            WHEN @TIPONOTA = 'A' THEN (SELECT NUNOTA FROM TGFCAB WHERE AD_VINCULONF = @NUNOTA)
                                      END";

    $stmt = sqlsrv_query($conn, $tsql, $params);
    if ($stmt === false) {
        throw new Exception('Erro ao executar a consulta SQL.');
    }
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if (!isset($row['NUNOTA'])) {
        throw new Exception('Nota 1720 não encontrada.');
    }
    $response = [
        'success' => [
            'nunota' => $row['NUNOTA'],
            'parceiro' => $row['PARCEIRO']
        ]
    ];
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
