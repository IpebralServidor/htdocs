<?php

$param = $_POST['nunota'];
$file = $_POST['arquivo'];

use PHPJasper\PHPJasper;

require __DIR__ . '/vendor/autoload.php';

$input = __DIR__ . '/vendor/geekcom/phpjasper/examples/' .$file .'.jrxml';

$jasper = new PHPJasper;
$jasper->compile($input)->execute();


mkdir((__DIR__).'\vendor\geekcom\phpjasper\examples\nunotas/' .$param , 0777, true);
$input = 'C:\xampp\htdocs\SistemaConferencia\Etiquetas\vendor\geekcom\phpjasper\examples\\' .$file .'.jasper';
$output = __DIR__ . '\vendor\geekcom\phpjasper\examples\nunotas/'.$param;
$jdbc_dir = __DIR__ . '\vendor\geekcom\phpjasper\bin\jasperstarter\jdbc';

$options = [
    'format' => ['pdf'],
    'locale' => 'en',
    'params' => [
        'PK_NUNOTA' => $param,
    ],
    'db_connection' => [
        'driver' => 'generic',
        'host' => '10.0.0.232',
        'port' => '1433',
        'database' => 'SANKHYA_TESTE',
        'username' => 'sankhya',
        'password' => 'tecsis',
        'jdbc_driver' => 'com.microsoft.sqlserver.jdbc.SQLServerDriver',
        'jdbc_url' => 'jdbc:sqlserver://10.0.0.232:1433;SANKHYA_TESTE=SANKHYA_TESTE',
        'jdbc_dir' => $jdbc_dir
    ]
];

$jasper->process(
    $input,
    $output,
    $options
)->execute();
