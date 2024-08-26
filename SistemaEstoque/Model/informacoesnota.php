<?php
include "../../conexaophp.php";

$nunotadest = $_GET['nunota'];

$tsql = "
        SELECT * FROM sankhya.AD_FNT_RetornaInfoNota_SistemaEstoque($nunotadest)";

$stmt = sqlsrv_query($conn, $tsql);

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

$array = convertArrayToUtf8($row);

echo json_encode($array);

function convertArrayToUtf8($array) {
        foreach ($array as &$value) {
                if (is_array($value)) {
                $value = convertArrayToUtf8($value);
                } elseif (is_string($value)) {
                $value = mb_convert_encoding($value, 'UTF-8', mb_detect_encoding($value, 'UTF-8, ISO-8859-1', true));
                }
        }
        return $array;
        }
