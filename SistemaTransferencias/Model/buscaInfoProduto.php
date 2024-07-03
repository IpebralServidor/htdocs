<?php

include "../../conexaophp.php";

$referencia = $_POST["referencia"];
$nunota = $_POST["nunota"];

$tsqlInfos = "DECLARE @NUNOTA INT = $nunota
              DECLARE @REFERENCIA VARCHAR(100) = '$referencia'
              DECLARE @STRING VARCHAR(100) = (SELECT AD_PARAMETROS_REABAST FROM TGFCAB WHERE NUNOTA = @NUNOTA)
              DECLARE @ENDERECO VARCHAR(100)
              DECLARE @RESERVA VARCHAR(100)
              IF((SELECT CHARINDEX('_', @STRING)) = 0)
              BEGIN
                SET @ENDERECO = @STRING
                SET @RESERVA = ''
              END
              ELSE
              BEGIN
                SET @ENDERECO = (SELECT SUBSTRING(@STRING, 0, CHARINDEX('_', @STRING)))
                SET @RESERVA = (SELECT RIGHT(@STRING,CHARINDEX('_',@STRING)-1))
              END

              SELECT ROUND(TGFPEM.ESTMIN,2),
                  ISNULL((SELECT SUM(ISNULL(ESTOQUE - RESERVADO, 0)) FROM TGFEST EST WHERE CODLOCAL = @ENDERECO AND EST.CODPROD = TGFPRO.CODPROD AND EST.CODEMP = TGFCAB.CODEMP AND EST.CODPARC = 0), 0), 
                  (SELECT ROUND(MEDIA6, 2) FROM AD_MEDIAVENDAEMP WHERE AD_MEDIAVENDAEMP.CODEMP = TGFCAB.CODEMP AND AD_MEDIAVENDAEMP.CODPROD = TGFPEM.CODPROD),
                CASE 
                  WHEN (SELECT CODLOCALPAD FROM TGFPEM PEM WHERE PEM.CODEMP = TGFCAB.CODEMP AND PEM.CODPROD = TGFPRO.CODPROD) <> 1990000 
                    THEN (SELECT CODLOCALPAD FROM TGFPEM PEM WHERE PEM.CODEMP = TGFCAB.CODEMP AND PEM.CODPROD = TGFPRO.CODPROD)
                  ELSE ''
                END AS LOCALPADRAO
              FROM TGFPRO INNER JOIN 
              TGFPEM ON TGFPEM.CODPROD = TGFPRO.CODPROD INNER JOIN
              TGFCAB ON TGFCAB.CODEMP = TGFPEM.CODEMP
              WHERE REFERENCIA = @REFERENCIA
                  AND NUNOTA = @NUNOTA";
$stmtInfos = sqlsrv_query($conn, $tsqlInfos);
$rowInfos = sqlsrv_fetch_array($stmtInfos, SQLSRV_FETCH_NUMERIC);

echo $rowInfos[0] . '|' . $rowInfos[1] . '|' . $rowInfos[2] . '|' . $rowInfos[3];
