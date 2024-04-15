<?php

include "../../conexaophp.php";

session_start();
$usuarioLogado = $_SESSION["idUsuario"];

$_POST["numnotaFiltro"] === '' ? $numnota = -1 : $numnota = $_POST["numnotaFiltro"];
$_POST["nunotaFiltro"] === '' ? $nunota = -1 : $nunota = $_POST["nunotaFiltro"];
$_POST["parceiroFiltro"] === '' ? $parceiro = -1 : $parceiro = $_POST["parceiroFiltro"];
$status = $_POST["statusFiltro"];
$empresa = $_POST["filtroEmpresas"];
$dtIni = str_replace("-", "", $_POST["dtIniFiltro"]);
$dtFim = str_replace("-", "", $_POST["dtFimFiltro"]);

$tsql = "SELECT * FROM [sankhya].[AD_FNT_LISTANOTAS_ADMIN_CONFERENCIA]($nunota, $numnota, $parceiro, '$status', $usuarioLogado, $empresa, '$dtIni', '$dtFim') ORDER BY CODTIPOPER, STATUSSEP, NUNOTA";
$stmt = sqlsrv_query($conn, $tsql);

$conferenciasList = "";

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
    if ($row[1] == 1780 || $row[1] == 1781 || $row[1] == 1782) {
        $color = "white";
    } else if (utf8_encode($row[15]) == 'Separação em andamento') {
        $color = "#FFFF95;";
    } else if (utf8_encode($row[15]) == 'Separação não iniciada') {
        $color = "#ff9595;";
    } else if (utf8_encode($row[15]) == 'Separação em pausa') {
        $color = "#9c95ff;";
    } else if (utf8_encode($row[15]) == 'Separação concluída') {
        $color = "#8fffb1";
    }
    $statusSeparacao = utf8_encode($row[15]);
    $descricaoTipoOper = utf8_encode($row[9]);
    $conferenciasList .= "<tr style='background-color:$color'>
                            <td class='outerCheckbox' style='width: 0.1% !important'>
                                <input type='checkbox' id='rowCheckbox' class='checkbox' data-nota='$row[0]' />
                            </td>
                            <td style='width: 0.1% !important'>$row[14] - $row[13]</td>
                            <td style='width: 0.1% !important'>$row[0]</td>
                            <td>$row[1]</td>
                            <td>$row[17]</td>
                            <td>$statusSeparacao</td>
                            <td>$row[2]</td>
                            <td>$row[4]</td>
                            <td>$row[6]</td>
                            <td>$row[7]</td>
                            <td>$row[3] - $row[8]</td>
                            <td>$descricaoTipoOper</td>
                            <td>$row[10]</td>
                            <td>$row[11]</td>
                            <td style='width: 0.1% !important'>$row[12]</td>
                            <td>$row[16]</td>
                            <td style='width: 60% !important'></td>
                        </tr>
    ";
}

echo $conferenciasList;
