<?php

function buscaPendencias($conn, $nunota, $codparc)
{
    try {
        $params = array($nunota, $codparc);
        $tsql = "EXEC [sankhya].[AD_STP_DASHBOARD_PENDENCIAS_APP] ?, ?";
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $tableHtml = '';
        $ultimaNunota = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            // if ($row['NUNOTA'] !== $ultimaNunota) {
                // $tableHtml .= "<tr class='agrupador'>";
                // // $tableHtml .= "<td colspan='11'>Nota 1720: " . $row['NUNOTA'] . " | Parceiro: " . $row['PARCEIRO'] . " | Data: " . $row['DTPEND'] . "</td>";
                // $tableHtml .= '</tr>';

                // Cor da linha vinda do banco
            $corLinha = trim($row['CORLINHA'] ?? '');
                // $ultimaNunota = $row['NUNOTA'];
            // }
            $tableHtml .= "<tr style='background-color: {$corLinha};'>";
            $tableHtml .= "<td>" . $row['REFERENCIA'] . '</td>';
            $tableHtml .= '<td>' . $row['DESCRPROD'] . '</td>';
            $tableHtml .= '<td>' . $row['CODLOCALORIG'] . '</td>';
            $tableHtml .= '<td>' . $row['QTDPENDENTE'] . '</td>';
            $tableHtml .= '<td>' . $row['CONTROLE'] . '</td>';
            $tableHtml .= '<td>' . $row['ESTOQUEPOSSIVEL'] . '</td>';
            $tableHtml .= '<td>' . $row['NUNOTA'] . '</td>';
            $tableHtml .= '<td>' . $row['CODPARC'] . '</td>';
            // $tableHtml .= '<td>' . $row['CORLINHA'] . '</td>';
            $tableHtml .= '</tr>';
        }
        sqlsrv_free_stmt($stmt);
        echo json_encode(['success' => utf8_encode($tableHtml)]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
