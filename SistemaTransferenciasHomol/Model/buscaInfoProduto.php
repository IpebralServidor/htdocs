<?php

include "../../conexaophp.php";

$referencia = $_POST["referencia"];
$nunota = $_POST["nunota"];

$tsqlInfos = "    SELECT ROUND(TGFPEM.ESTMIN,2),
                        (SELECT SUM(ISNULL(ESTOQUE - RESERVADO, 0)) FROM TGFEST EST WHERE CODLOCAL = TGFCAB.AD_PARAMETROS_REABAST AND EST.CODPROD = TGFPRO.CODPROD AND EST.CODEMP = TGFCAB.CODEMP AND EST.CODPARC = 0), 
                        (SELECT ROUND(MEDIA6, 2) FROM AD_MEDIAVENDAEMP WHERE AD_MEDIAVENDAEMP.CODEMP = TGFCAB.CODEMP AND AD_MEDIAVENDAEMP.CODPROD = TGFPEM.CODPROD)
                    FROM TGFPRO INNER JOIN 
                    TGFPEM ON TGFPEM.CODPROD = TGFPRO.CODPROD INNER JOIN
                    TGFCAB ON TGFCAB.CODEMP = TGFPEM.CODEMP
                    WHERE REFERENCIA = '$referencia'
                      AND NUNOTA = $nunota
                    ";
$stmtInfos = sqlsrv_query($conn, $tsqlInfos);
$rowInfos = sqlsrv_fetch_array($stmtInfos, SQLSRV_FETCH_NUMERIC);

echo $rowInfos[0] . '|' . $rowInfos[1] . '|' . $rowInfos[2];
