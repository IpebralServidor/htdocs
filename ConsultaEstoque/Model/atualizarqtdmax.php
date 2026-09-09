<?php
include "../../conexaophp.php";
include '../../App/auth.php';

$params = array($_POST['novoValor'], $_POST['codProduto'], $_POST['codLocalProduto'], $_POST['empresa'], $_SESSION["idUsuario"]);

$tsql = "
            DECLARE 
            @MAXLOCAL FLOAT = ?,
            @CODPROD INT = ?,
            @CODLOCAL INT = ?,
            @CODEMP INT = ?,
            @CODUSU INT = ?
             
			INSERT INTO AD_LOG_QTDMAX
			SELECT 
			@CODPROD
			,@CODEMP 
			,(SELECT TOP 1  CODLOCALPAD FROM TGFPEM WHERE CODPROD = @codprod AND CODEMP = @codemp)
			,@CODLOCAL
			,@CODUSU
			,GETDATE()
			,(SELECT TOP 1 tgfpem.AD_QTDMAXLOCAL FROM tgfpem WHERE codprod=  @codprod AND codemp = @codemp)
			,@MAXLOCAL
			,'Maxima alterada via consulta de produtos APP'

            UPDATE TGFPEM 
            SET AD_QTDMAXLOCAL = @MAXLOCAL
            FROM TGFPEM
            WHERE TGFPEM.CODPROD = @CODPROD
            AND TGFPEM.CODLOCALPAD = @CODLOCAL
            AND TGFPEM.CODEMP = @CODEMP
            
            
            "
            ;

$stmt = sqlsrv_query($conn, $tsql, $params);
