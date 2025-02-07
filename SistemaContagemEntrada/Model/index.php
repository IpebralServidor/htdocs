<?php


function buscaNotasContagem($conn)
{
    try {
        $params = array();
        $tsql = "SELECT * FROM [sankhya].[AD_FNT_LISTA_ITENS_CONTAGEM_ENTRADA_MERCADORIAS] () ";
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $tableHtml = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $action = '';
            $tipoText = '';
            switch ($row['STATUS']) {
                case 'A':
                    $color = '#FFFF95';
                    $statusText = 'Em andamento';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;
                case 'C':
                    $color = '#8fffb1';
                    $statusText = 'Concluido';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;
                case 'D':
                    $color = 'white';
                    $statusText = 'Disponivel';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;                
            }
            if($row['TIPO'] === 'N') {
                $tipoText = 'Nota';
            } else if($row['TIPO'] === 'O') {
                $tipoText = 'OP';
            }

            $tableHtml .= "<tr " . $action . "id = ". $row['NUNOTA']. " style='background-color: $color' data-tipo='" . $row['TIPO'] . "' data-status='" . $row['STATUS'] . "'>";
            $tableHtml .= '<td>' . $tipoText . '</td>';
            $tableHtml .= '<td>' . $row['NUNOTA'] . '</td>';
            $tableHtml .= '<td>' . $row['QTDITENS'] . '</td>';
            $tableHtml .= '<td>' . $statusText . '</td>';
            $tableHtml .= '<td>' . $row['CODUSU'] . '</td>';
            $tableHtml .= '</tr>';
        }
        sqlsrv_free_stmt($stmt);
        echo json_encode(['success' => utf8_encode($tableHtml)]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}