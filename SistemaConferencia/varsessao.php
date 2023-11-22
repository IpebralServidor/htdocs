<?php

session_start();
include "../conexaophp.php";

$string = $_POST['string'];

if($_SESSION["nmrComplemento"] != $string){
    $_SESSION["nmrComplemento"] = $string;
    echo 'S';
}else{
    echo 'N';
}

?>