<?php

session_start();
include "../../conexaophp.php";

$_SESSION['enderecoInit'] = $_POST['enderecoInit'];
$_SESSION['enderecoFim'] = $_POST['enderecoFim'];

echo $_SESSION['enderecoInit'];
echo $_SESSION['enderecoFim'];

?>