<?php
include "../../../conexaophp.php";
require "../../../App/auth.php";

$limiteMensagens = $_POST["limiteMensagens"];

$tsqlUpdate = "UPDATE tsiusu SET USUREDE = $limiteMensagens  WHERE codusu = $idUsuario";
$stmtUpdate = sqlsrv_query($conn, $tsqlUpdate);



?>
