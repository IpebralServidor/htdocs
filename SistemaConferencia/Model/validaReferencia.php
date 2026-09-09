<?php

include "../../conexaophp.php";

$referencia = $_POST['referencia'];


$params = array($referencia);
$tsql = "DECLARE @REFERENCIA VARCHAR(MAX) = ?

        SELECT STRING_AGG(MENSAGEM, CHAR(10))
        FROM (
            SELECT
                CASE
                    WHEN PRO.REFERENCIA IS NULL
                        THEN REF.VALUE + ' - Produto não encontrado'
                    WHEN NOT EXISTS (
                        SELECT 1
                        FROM TGFBAR BAR
                        WHERE BAR.CODPROD = PRO.CODPROD
                        AND BAR.CODBARRA <> PRO.REFERENCIA
                    )
                        THEN REF.VALUE + ' - Sem código de barras válido'
                END AS MENSAGEM
            FROM STRING_SPLIT(@REFERENCIA, ',') REF
            LEFT JOIN TGFPRO PRO
                ON PRO.REFERENCIA = LTRIM(RTRIM(REF.VALUE))
        ) X
        WHERE MENSAGEM IS NOT NULL;";

$stmt = sqlsrv_query($conn, $tsql, $params);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

echo $row[0];
