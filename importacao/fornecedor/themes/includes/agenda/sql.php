<?php

$host = "localhost";//caso esteja usando o xampp ou wamp

$user = "root";// padrão para xampp ou wamp é root

$pass = "";// padrão para xampp ou wamp é ""

$db = "site";// é o nome do banco que vc criou no phpmyadmin

$conn = mysql_connect($host, $user, $pass) or die (mysql_error());



@mysql_select_db($db);



?>