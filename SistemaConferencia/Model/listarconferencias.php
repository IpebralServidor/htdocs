<?php

include "../../conexaophp.php";

session_start();
$usuarioLogado = $_SESSION["idUsuario"];

$_POST["numnotaFiltro"] === '' ? $numnota = -1 : $numnota = $_POST["numnotaFiltro"];
$_POST["nunotaFiltro"] === '' ? $nunota = -1 : $nunota = $_POST["nunotaFiltro"];
$_POST["parceiroFiltro"] === '' ? $parceiro = -1 : $parceiro = $_POST["parceiroFiltro"];
$status = $_POST["statusFiltro"];

$params = array($nunota, $numnota, $parceiro, $status, $usuarioLogado);

$tsql = "SELECT * FROM [sankhya].[AD_FNT_LISTANOTAS_CONFERENCIA](?, ?, ?, ?, ?) ORDER BY NUNOTA DESC";
$tsqlTiposConferencia = "SELECT CODTIPOPER FROM [SANKHYA].[AD_FNT_TOP_PORTIPO]('Conferencia', NULL, NULL, NULL, NULL)";

$stmt = sqlsrv_query($conn, $tsql, $params);
$stmtTiposConferencia = sqlsrv_query($conn, $tsqlTiposConferencia);
$arrayTiposConferencia = array();

while ($rowTiposConferencia = sqlsrv_fetch_array($stmtTiposConferencia, SQLSRV_FETCH_NUMERIC)) {
    array_push($arrayTiposConferencia, $rowTiposConferencia[0]);
}

$listaConferencias = "";

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
    if (in_array($row[1], $arrayTiposConferencia)) {
        $color = "white";
    } else if (utf8_encode($row[16]) == 'Separação em andamento') {
        $color = "#FFFF95;";
    } else if (utf8_encode($row[16]) == 'Separação não iniciada') {
        $color = "#ff9595;";
    } else if (utf8_encode($row[16]) == 'Separação em pausa') {
        $color = "#9c95ff;";
    } else if (utf8_encode($row[16]) == 'Separação concluída') {
        $color = "#8fffb1";
    }

    $vlrNota = str_replace('.', ',', $row[17]);
    $statusSeparacao = utf8_encode($row[16]);
    $descrOper = utf8_encode($row[9]);

    $listaConferencias .= "<tbody>
            <tr style='background-color:$color' id='linhaSelecionada' data-nota='$row[0]' data-localconf='$row[18]'>
                <td style='width: 30px;'>$row[3] </td>
                <td style='width: 30px;'>$row[4] </td>
                <td style='width: 30px;'>$row[0] </td>
                <td style='width: 30px;'>$row[14] </td>
                <td style='width: 30px;'>$row[1] </td>
                <td style='width: 30px;'>$vlrNota </td>
                <td style='width: 30px;'>$statusSeparacao</td>
                <td style='width: 30px;'>$row[2] </td>
                <td style='width: 30px;'>$row[6] </td>
                <td style='width: 30px;'>$row[7] </td>
                <td style='width: 30px;'>$row[8] </td>
                <td style='width: 30px;'>$row[15] </td>
                <td style='width: 30px;'>$descrOper </td>
                <td style='width: 30px;'>$row[10] </td>
                <td style='width: 30px;'>$row[11] </td>
                <td style='width: 30px;'>$row[12] </td>
                <td style='width: 30px;'>$row[13] </td>
                <td></td>
            </tr>
        </tbody>
    ";
}
echo $listaConferencias;
