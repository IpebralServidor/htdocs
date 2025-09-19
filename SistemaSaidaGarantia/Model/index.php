<?php


function buscaItensGarantia($conn)
{
    try {
        $tsql = "SELECT * FROM [sankhya].[AD_FNT_LISTA_ITENS_SAIDA_GARANTIA]()";
        $stmt = sqlsrv_query($conn, $tsql); 
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $tableHtml = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $action = '';
            $tipoText = '';

            $tableHtml .= '<td><input type="checkbox" id="check" class="linha-checkbox"></td>' ; 
           $tableHtml .= '<td class="referencia">' . $row['REFERENCIA'] . '</td>';
            $tableHtml .= '<td>' . $row['QTD'] . '</td>';
            $tableHtml .= '<td>' . $row['CODEMP'] . '</td>';
            $tableHtml .= '<td>' . $row['CODLOCAL'] . '</td>';
            $tableHtml .= '</tr>';
        }

        sqlsrv_free_stmt($stmt);
        echo json_encode(['success' => utf8_encode($tableHtml)]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}



function transferirGarantia ($conn,$notas) {
    try {

        $params = array($notas);
        $tsql = "EXEC [AD_STP_TRANSFERIR_SAIDA_GARANTIA] ? ";

        $stmt = sqlsrv_query($conn, $tsql, $params);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $msg = '';
        $result = '';
        
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }
        
        if (isset($row['result'])) {
             $result = $row['result'];
            if ($row['result'] === 'PEND') { // <- aqui corrigido
                $msg = 'PEND';
            } else {
                $msg = 'true';
                $result = $row['result'];
            }
        } else {
            $msg = 'false';
        }

        $response = [
            'success' => [
                'msg' => $msg,
                'result' => $result,
                         ]
                    ];
        
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}





