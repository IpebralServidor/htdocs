<?php


function buscaNotasContagem($conn,$tipo)
{
    try {
        $params = array($tipo);
        $tsql = "SELECT * FROM [sankhya].[AD_FNT_LISTA_ITENS_CONTAGEM_ENTRADA_MERCADORIAS] (?) ";
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $tableHtml = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $action = '';
            $tipoText = '';
            switch ($row['STATUS'] . '-' . $row['OCORRENCIA'] . '-' . $row['RECONTAGEM']  ) {
                case 'A-S-N':
                    $color = '#FFFF95';
                    $statusText = 'Em andamento';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;
                case 'C-S-N':
                    $color = '#8fffb1';
                    $statusText = 'Concluido';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;
             case 'D-S-N':
                    $color = 'white';
                    $statusText = 'Disponivel';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;  
                case 'A-N-N':
                    $color = '#FFFF95';
                    $statusText = 'Em andamento';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;
                case 'C-N-N':
                    $color = '#8fffb1';
                    $statusText = 'Concluido';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;
                case 'D-N-N':
                    $color = 'white';
                    $statusText = 'Disponivel';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;       
                    
                case 'A-S-S':
                    $color = '#FFD700';
                    $statusText = 'Em andamento';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;
                case 'C-S-S':
                    $color = '#ADFF2F';
                    $statusText = 'Concluido';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;
                case 'D-S-S':
                    $color = 'white';
                    $statusText = 'Disponivel';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;  
                case 'A-N-S':
                    $color = '#FFD700';
                    $statusText = 'Em andamento';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;
                case 'C-N-S':
                    $color = '#ADFF2F';
                    $statusText = 'Concluido';
                    $action = "onclick='confirmaAbrirContagem(this)'";
                    break;
                case 'D-N-S':
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
            $tableHtml .= '<td>' . $row['RAZAOSOCIAL'] . '</td>';
            $tableHtml .= '<td>' . $row['DTNEG'] . '</td>';
            $tableHtml .= '<td>' . $row['CODTIPOPER'] . '</td>';
            $tableHtml .= '<td>' . $row['CODEMP'] . '</td>';
            $tableHtml .= '<td>' . $row['NUTRANSF'] . '</td>';



            $tableHtml .= '</tr>';
        }
        sqlsrv_free_stmt($stmt);
        echo json_encode(['success' => utf8_encode($tableHtml)]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}



function verificaEmpresa ($conn, $nunota,$codusu) {
    try {
        $params = array($nunota,$codusu);
        $tsql = "SELECT * FROM [AD_FNT_VERIFICA_EMPRESA_CONTAGEM](?,?)";


        $stmt = sqlsrv_query($conn, $tsql, $params);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $msg = '';
        $usulogado = '';
        $usunota = '';
   
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        if ($row['RESULT'] == 1) {            
            $msg = 'prioridade: '. $row['NUNOTA'];
        }
        else  if ($row['RESULT'] == 2){
            $msg = 'pend '.$row['NUNOTA']; 
        }
        else  if ($row['RESULT'] == 3){
            $msg = 'usuario '.$row['NUNOTA'];
            $usulogado = $codusu;
            $usunota  = $row['CODUSU'];
        }
        else {
            $msg = $row['NUNOTA'];
        }

        $response = [
            'success' => [
                'msg' => $msg,
                'codusulog' => $usulogado,
                'codusunota' => $usunota
            ]
        ];
        
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
