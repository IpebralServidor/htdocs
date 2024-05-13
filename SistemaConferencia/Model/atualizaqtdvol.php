<?php

include "../../conexaophp.php";

$nunota = $_POST['nunota'];
$qtdVol = $_POST['atualizaQtdVol'];

$params = array($qtdVol, $nunota);
$tsqlUpdate = "UPDATE TGFCON2 SET QTDVOL = ? WHERE NUNOTAORIG = ?";
sqlsrv_query($conn, $tsqlUpdate, $params);
