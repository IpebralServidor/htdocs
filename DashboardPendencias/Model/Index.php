<?php

function buscaPendencias($conn, $nunota, $codparc)
{
    try {
        $params = array($nunota, $codparc);
        $tsql = "SELECT * FROM [sankhya].[AD_FNT_DASHBOARD_PENDENCIAS_APP] (?, ?) ORDER BY NUNOTA";
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $tableHtml = '';
        $ultimaNunota = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if ($row['NUNOTA'] !== $ultimaNunota) {
                $tableHtml .= "<tr class='agrupador'>";
                $tableHtml .= "<td colspan='11'>Nota 1720: " . $row['NUNOTA'] . " | Parceiro: " . $row['PARCEIRO'] . " | Data: " . $row['DTPEND'] . "</td>";
                $tableHtml .= '</tr>';
                $ultimaNunota = $row['NUNOTA'];
            }
            $tableHtml .= "<tr>";
            $tableHtml .= "<td>" . $row['REFERENCIA'] . '</td>';
            $tableHtml .= '<td>' . $row['DESCRPROD'] . '</td>';
            $tableHtml .= '<td>' . $row['STATUS'] . '</td>';
            $tableHtml .= '<td>' . $row['CODLOCALORIG'] . '</td>';
            $tableHtml .= '<td>' . $row['CODLOCALDEST'] . '</td>';
            $tableHtml .= '<td>' . $row['QTDNEG'] . '</td>';
            $tableHtml .= '<td>' . $row['DTULTMOV'] . '</td>';
            $tableHtml .= '<td>' . $row['USUULTBIP'] . '</td>';
            $tableHtml .= '<td>' . $row['TRANSFERENCIAS'] . '</td>';
            $tableHtml .= '<td>' . $row['CONTROLE'] . '</td>';
            $tableHtml .= '<td>' . $row['NUNOTAABAST'] . '</td>';
            $tableHtml .= '</tr>';
        }
        sqlsrv_free_stmt($stmt);
        echo json_encode(['success' => utf8_encode($tableHtml)]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
