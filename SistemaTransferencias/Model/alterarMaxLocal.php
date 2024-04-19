<?php

include "../../conexaophp.php";

$referencia = $_POST["referencia"];
$nunota = $_POST["nunota2"];

$tsqlSlctLocPad = " SELECT AD_QTDMAXLOCAL FROM TGFPEM 
                    WHERE CODEMP = (SELECT CODEMP FROM TGFCAB WHERE NUNOTA = $nunota) 
                      AND CODPROD = (SELECT CODPROD FROM TGFPRO WHERE REFERENCIA = '$referencia')";
$stmtSlctLocPad = sqlsrv_query($conn, $tsqlSlctLocPad);
$rowSlctLocPad = sqlsrv_fetch_array($stmtSlctLocPad, SQLSRV_FETCH_NUMERIC);

if (empty($rowSlctLocPad[0])) {
  $rowSlctLocPad[0] = 0;
}
echo $rowSlctLocPad[0];
