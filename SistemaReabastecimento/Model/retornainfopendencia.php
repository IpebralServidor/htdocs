<?php
include "../../conexaophp.php";
try {
    $nunota = $_POST["nunota"];
    $codprod = $_POST["codprod"];

    $params = array($nunota, $codprod);
    $tsql = "
            DECLARE @NUNOTA INT = ?
            DECLARE @CODPROD INT = ?
            SELECT CONCAT(ABSTITE.QTDNEG, ' / ', ISNULL((SELECT SUM(QTDNEG) 
                                                        FROM TGFITE 
                                                        WHERE NUNOTA = @NUNOTA 
                                                        AND CODPROD = @CODPROD 
                                                        AND SEQUENCIA < 0 
                                                        AND ATUALESTOQUE = 1),0)) AS QTD,
                    PEM.CODLOCALPAD AS LOCALPAD
            FROM AD_TGFABSTITE ABSTITE INNER JOIN 
                TGFPEM PEM ON ABSTITE.CODPROD = PEM.CODPROD
            WHERE ABSTITE.NUNOTATRF = (SELECT NUNOTA FROM TGFCAB WHERE AD_VINCULONF = @NUNOTA)
            AND ABSTITE.CODPROD = @CODPROD
            AND PEM.CODEMP = 1";

    $stmt = sqlsrv_query($conn, $tsql, $params);
    if ($stmt === false) {
        throw new Exception('Erro ao executar a consulta SQL.');
    }
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if (!isset($row['QTD'])) {
        throw new Exception('Produto não encontrado.');
    }
    $response = [
        'success' => [
            'qtd' => $row['QTD'],
            'localpad' => $row['LOCALPAD']
        ]
    ];
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
