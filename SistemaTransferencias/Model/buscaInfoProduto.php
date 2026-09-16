<?php

include "../../conexaophp.php";

$referencia = $_POST["referencia"];
$nunota = $_POST["nunota"];

$tsqlInfos = "
              DECLARE @NUNOTA INT = $nunota
              DECLARE @REFERENCIA VARCHAR(100) = (SELECT DISTINCT REFERENCIA FROM TGFPRO INNER JOIN TGFBAR ON TGFPRO.CODPROD = TGFBAR.CODPROD WHERE TGFPRO.REFERENCIA = '$referencia' OR TGFBAR.CODBARRA = '$referencia')
              DECLARE @STRING VARCHAR(100) = (SELECT AD_PARAMETROS_REABAST FROM TGFCAB WHERE NUNOTA = @NUNOTA)
              DECLARE @ENDERECO VARCHAR(100)
              DECLARE @RESERVA VARCHAR(100)
              DECLARE @CODEMP INT = (SELECT CODEMP FROM TGFCAB WHERE NUNOTA = @NUNOTA)
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

              SELECT ISNULL(ROUND(TGFPEM.ESTMIN,2), 0),
                  ISNULL((SELECT SUM(ISNULL(ESTOQUE - RESERVADO, 0)) FROM TGFEST EST WHERE CODLOCAL = @ENDERECO AND EST.CODPROD = TGFPRO.CODPROD AND EST.CODEMP = @CODEMP AND EST.CODPARC = 0), 0), 
                  ISNULL((SELECT ROUND(MEDIA6, 2) FROM AD_MEDIAVENDAEMP WHERE AD_MEDIAVENDAEMP.CODEMP = @CODEMP AND AD_MEDIAVENDAEMP.CODPROD = TGFPEM.CODPROD), 0),
              CASE 
                  WHEN (SELECT CODLOCALPAD FROM TGFPEM PEM WHERE PEM.CODEMP = @CODEMP AND PEM.CODPROD = TGFPRO.CODPROD) <> 1990000 
                  THEN (SELECT CODLOCALPAD FROM TGFPEM PEM WHERE PEM.CODEMP = @CODEMP AND PEM.CODPROD = TGFPRO.CODPROD)
                  ELSE ''
              END AS LOCALPADRAO,
              TGFPRO.DESCRPROD
              FROM TGFPRO left JOIN 
              TGFPEM ON TGFPEM.CODPROD = TGFPRO.CODPROD
              WHERE REFERENCIA = @REFERENCIA";
$stmtInfos = sqlsrv_query($conn, $tsqlInfos);
$rowInfos = sqlsrv_fetch_array($stmtInfos, SQLSRV_FETCH_NUMERIC);

echo $rowInfos[0] . '|' . $rowInfos[1] . '|' . $rowInfos[2] . '|' . $rowInfos[3] . '|' . $rowInfos[4];
