<?php

function buscaEnderecosInventario($conn, $codemp, $endini, $endfim, $concluidos)
{
    try {
        $params = array($codemp, $endini, $endfim, $concluidos);
        $tsql = "SELECT * FROM [sankhya].[AD_FNT_BUSCA_ENDERECOS_INVENTARIO] (?, ?, ?, ?) ORDER BY CODLOCAL";
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $tableHtml = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $action = '';
            switch ($row['STATUS']) {
                case 'A':
                    $color = '#FFFF95';
                    $statusText = 'Em andamento';
                    $action = "onclick='confirmaAbrirInventario(this)'";
                    break;
                case 'C':
                    $color = '#8fffb1';
                    $statusText = 'Concluído';
                    $action = "onclick='confirmaAbrirInventario(this)'";
                    break;
                case 'D':
                    $color = 'white';
                    $statusText = 'Disponivel';
                    $action = "onclick='confirmaAbrirInventario(this)'";
                    break;
                case 'S':
                    $color = '#FF4D4D';
                    $statusText = 'Bloqueado - Separação';
                    break;
                case 'R':
                    $color = '#FF4D4D';
                    $statusText = 'Bloqueado - Reabastecimento';
                    break;
                case 'T':
                    $color = '#FF4D4D';
                    $statusText = 'Bloqueado - Troca Propriedade';
                    break;
                case 'CS':
                    $color = '#F08650';
                    $statusText = 'Concluido / Bloqueado - Separação';
                    break;
                case 'CR':
                    $color = '#F08650';
                    $statusText = 'Concluido / Bloqueado - Reabastecimento';
                    break;
                case 'CT':
                    $color = '#F08650';
                    $statusText = 'Concluido / Bloqueado - Troca Propriedade';
                    break;
            }
            $tableHtml .= "<tr " . $action . "style='background-color: $color'>";
            $tableHtml .= '<td>' . $row['CODLOCAL'] . '</td>';
            $tableHtml .= '<td>' . $row['ITENS'] . '</td>';
            $tableHtml .= '<td>' . $row['PORCENTAGEM_TIPO_S'] . '</td>';
            $tableHtml .= '<td>' . $statusText . '</td>';
            $tableHtml .= "<td style='display: none'>" . $row['STATUS'] . '</td>';
            $tableHtml .= "<td style='display: none'>" . $row['CODEMP'] . '</td>';
            $tableHtml .= '<td>' . $row['USUARIO'] . '</td>';
            $tableHtml .= '</tr>';
        }
        sqlsrv_free_stmt($stmt);
        echo json_encode(['success' => utf8_encode($tableHtml)]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
