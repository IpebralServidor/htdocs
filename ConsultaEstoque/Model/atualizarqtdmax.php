<?php
include "../../conexaophp.php";

$params = array($_POST['novoValor'], $_POST['codProduto'], $_POST['codLocalProduto'], $_POST['empresa']);

$tsql = "UPDATE TGFPEM 
            SET AD_QTDMAXLOCAL = ?
            FROM TGFPEM
            WHERE TGFPEM.CODPROD = ?
            AND TGFPEM.CODLOCALPAD = ?
            AND TGFPEM.CODEMP = ?";

$stmt = sqlsrv_query($conn, $tsql, $params);
