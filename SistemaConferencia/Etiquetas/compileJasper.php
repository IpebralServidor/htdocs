<?php

//$param = $_POST['nunota'];
//$file = $_POST['arquivo'];

$file = "Vale_novo";
$param = 3372583;

use PHPJasper\PHPJasper;

require 'C:\xampp\htdocs\vendor\autoload.php';

mkdir('C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/' .$param , 0777, true);
$output = 'C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/'.$param;
$input = 'C:\xampp\htdocs\Reports/' .$file .'.jrxml';

$jasper = new PHPJasper;

$jasper->compile(
    $input,
    $output
)->execute();

//$data = file_get_contents('C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/'.$param .'\Vale_novo.jasper');
//$gzdata = gzencode($data, 9);
//file_put_contents('Vale_novo.jasper.gz', $gzdata);
$gzipFile = 'C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/'.$param .'\Vale_novo.jasper.gz';
$jasperFile = 'C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/'.$param .'\Vale_novo.jasper';
exec("gzip -c $jasperFile > $gzipFile");

//
//$input = 'C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/' .$file .'.jasper';
//$output = 'C:\xampp\htdocs\SistemaConferencia\Etiquetas\nunotas/'.$param;
//$jdbc_dir = 'C:\xampp\htdocs\vendor\geekcom\phpjasper\bin\jasperstarter\jdbc';
//
//$options = [
//    'format' => ['pdf'],
//    'locale' => 'en',
//    'params' => [
//        'PK_NUNOTA' => $param,
//    ],
//    'db_connection' => [
//        'driver' => 'generic',
//        'host' => '10.0.0.232',
//        'port' => '1433',
//        'database' => 'SANKHYA_TESTE',
//        'username' => 'sankhya',
//        'password' => 'tecsis',
//        'jdbc_driver' => 'com.microsoft.sqlserver.jdbc.SQLServerDriver',
//        'jdbc_url' => 'jdbc:sqlserver://10.0.0.232:1433;SANKHYA_TESTE=SANKHYA_TESTE',
//        'jdbc_dir' => $jdbc_dir
//    ]
//];
//
//$jasper->process(
//    $input,
//    $output,
//    $options
//)->execute();

