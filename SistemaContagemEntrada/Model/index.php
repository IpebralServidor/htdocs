<?php


function buscaNotasContagem($conn, $tipo, $codusu)
{
    try {
        $params = array($tipo, $codusu);
        $tsql = "
        DECLARE @TIPO VARCHAR(1) = ?,
        @CODUSU INT = ?
        IF @TIPO = 'N'
        BEGIN
         SELECT * FROM [sankhya].[AD_FNT_LISTA_ITENS_CONTAGEM_ENTRADA_MERCADORIAS] (@TIPO) WHERE STATUS <> 'C' ORDER BY ORDEM
        END ELSE
        IF @TIPO = 'O' AND isnull(@CODUSU,0) NOT IN  (0,3813,89,3308,4046,3,1696, 32, 3195, 692, 42, 4418, 181, 694, 7257, 100,30,3564,135, 691,135)
        BEGIN
         SELECT * FROM [sankhya].[AD_FNT_LISTA_ITENS_CONTAGEM_ENTRADA_MERCADORIAS] (@TIPO) WHERE STATUS = 'A' ORDER BY ORDEM
        END else 
        IF @TIPO = 'O' AND isnull(@CODUSU,0)  IN  (0,3813,89,3308,4046,3,1696, 32, 3195, 692, 42, 4418, 181, 694, 7257, 100,30,3564,135, 691,135,3266)
        BEGIN
         SELECT * FROM [sankhya].[AD_FNT_LISTA_ITENS_CONTAGEM_ENTRADA_MERCADORIAS] (@TIPO) WHERE (STATUS = 'A' OR STATUS = 'D') ORDER BY ORDEM
        END
        
        
        ";
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $tableHtml = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $action = '';
            $tipoText = '';
            switch ($row['STATUS'] . '-' . $row['OCORRENCIA'] . '-' . $row['RECONTAGEM']) {
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

            if ($row['TIPO'] === 'N') {
                $tipoText = 'Nota';
            } else if ($row['TIPO'] === 'O') {
                $tipoText = 'OP';
            }


            $tableHtml .= "<tr " . $action . "id = " . $row['NUNOTA'] . " style='background-color: $color' data-tipo='" . $row['TIPO'] . "' data-status='" . $row['STATUS'] . "'>";
            $tableHtml .= '<td>' . $row['REFERENCIA'] . '</td>';
            $tableHtml .= '<td>' . $row['CODEMP'] . '</td>';
            // $tableHtml .= '<td>' . $tipoText . '</td>';
            $tableHtml .= '<td>' . $row['NUNOTA'] . '</td>';
            $tableHtml .= '<td>' . $row['NUMNOTA'] . '</td>';
            $tableHtml .= '<td>' . $row['VOLUME'] . '</td>';
            $tableHtml .= '<td>' . $row['RAZAOSOCIAL'] . '</td>';
            $tableHtml .= '<td>' . $row['CODUSU'] . '</td>';
            $tableHtml .= '<td>' . $row['ENDERECO'] . '</td>';
            $tableHtml .= '<td>' . $row['DTNEG'] . '</td>';
            $tableHtml .= '<td>' . $row['CODTIPOPER'] . '</td>';
            $tableHtml .= '<td>' . $row['NUTRANSF'] . '</td>';






            $tableHtml .= '</tr>';
        }
        sqlsrv_free_stmt($stmt);
        echo json_encode(['success' => utf8_encode($tableHtml)]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}



function buscaAtribuirNotas($conn, $tipo, $codusu)
{
    try {
        $podeVerPrioridade = usuarioPodePriorizar($codusu);
        $params = array($tipo);
        $tsql = "SELECT G.*, CAB.REBOQUE3
                 FROM [sankhya].[AD_FNT_LISTA_GERENCIA_CONTAGEM] (?) G LEFT JOIN
                      TGFCAB CAB ON CAB.NUNOTA = G.NUNOTA
                                AND G.TIPO = 'N'
                 ORDER BY G.ORDEM, G.NUNOTA";
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $tableHtml = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $action = '';
            $tipoText = '';
            switch ($row['STATUS'] . '-' . $row['OCORRENCIA'] . '-' . $row['RECONTAGEM']) {
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
            if ($row['TIPO'] === 'N') {
                $tipoText = 'Nota';
            } else if ($row['TIPO'] === 'O') {
                $tipoText = 'OP';
            }
            $linhaPrioritaria = $podeVerPrioridade && $row['TIPO'] === 'N' && (int)$row['REBOQUE3'] === 1;
            $estiloLinha = $linhaPrioritaria ? " style='background-color: #ffcccc'" : '';
            $colunaReferenciaParceiro = $row['TIPO'] === 'N'
                ? htmlspecialchars((string)$row['RAZAOSOCIAL'], ENT_QUOTES, 'ISO-8859-1')
                : $row['REFERENCIA'];

            $tableHtml .= '<tr' . $estiloLinha . '>';
            $tableHtml .= '<td><input type="checkbox" id="check" class="linha-checkbox"></td>';
            $tableHtml .= '<td  class="nro-unico">' . $row['NUNOTA'] . '</td>';
            $tableHtml .= '<td>' . $colunaReferenciaParceiro . '</td>';
            $tableHtml .= '<td>' . $row['QTDOP'] . '</td>';
            $tableHtml .= '<td>' . $row['DTNEG'] . '</td>';
            $tableHtml .= '<td>' . $row['CODEMP'] . '</td>';





            $tableHtml .= '</tr>';
        }
        sqlsrv_free_stmt($stmt);
        echo json_encode(['success' => utf8_encode($tableHtml)]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}




function buscaLiberaPaletes($conn, $tipo)
{
    try {
        $params = array($tipo);
        $tsql = "SELECT * FROM [sankhya].[AD_FNT_LISTA_PALETES_CONTAGEM] (?) ";
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $tableHtml = '';
        $color = 'white';
        $statusText = 'Disponivel';
        $action = "onclick='confirmaAbrirContagem(this)'";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $action = '';
            $tipoText = '';

            $tableHtml .= '<td><input type="radio" name="seleciona-palete" id="check" class="linha-checkbox"></td>';
            $tableHtml .= '<td>' . $row['PALETE'] . '</td>';
            $tableHtml .= '<td>' . $row['CODEMP'] . '</td>';
            $tableHtml .= '<td  class="endereco">' . $row['ENDERECO'] . '</td>';
            $tableHtml .= '<td>' . $row['QTDITENS'] . '</td>';





            $tableHtml .= '</tr>';
        }
        sqlsrv_free_stmt($stmt);
        echo json_encode(['success' => utf8_encode($tableHtml)]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}


function buscaPaletesPendentes($conn, $tipo)
{
    try {
        $params = array($tipo);
        $tsql = "SELECT * FROM [sankhya].[AD_FNT_LISTA_PALETES_PENDENTES] (?) order by endereco ";
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $tableHtml = '';
        $color = 'white';
        $statusText = 'Disponivel';
        $action = "onclick='confirmaAbrirContagem(this)'";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $action = '';
            $tipoText = '';

            $tableHtml .= '<td>' . $row['NUNOTA'] . '</td>';
            $tableHtml .= '<td>' . $row['CODEMP'] . '</td>';
            $tableHtml .= '<td  class="endereco">' . $row['ENDERECO'] . '</td>';
            $tableHtml .= '<td>' . $row['QTDITENS'] . '</td>';





            $tableHtml .= '</tr>';
        }
        sqlsrv_free_stmt($stmt);
        echo json_encode(['success' => utf8_encode($tableHtml)]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}



function transferirPaletes($conn, $notas)
{
    try {
        $msg = '';
        $params = array($notas);
        $tsql = "EXEC [AD_STP_TRANSFERIR_PALETES_CONTAGEM] ?";



        $stmt = sqlsrv_query($conn, $tsql, $params);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $msg = '';

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        if (isset($row['result']) && $row['result'] != 'pend') {
            $msg = 'true';
        } else if ($row['result'] == 'pend') {

            $msg = 'pend';
        } else {
            $msg = 'false';
        }

        $response = [
            'success' => [
                'msg' => $msg,
            ]
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}


function verificaEmpresa($conn, $nunota, $codusu, $tipo)
{
    try {
        $params = array($nunota, $codusu, $tipo);
        $tsql = "SELECT * FROM [AD_FNT_VERIFICA_EMPRESA_CONTAGEM](?,?,?)";


        $stmt = sqlsrv_query($conn, $tsql, $params);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $msg = '';
        $usulogado = '';
        $usunota = '';
        $endereco = '';


        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        if ($row['RESULT'] == 1) {
            $msg = 'prioridade: ' . $row['NUNOTA'];
        } else  if ($row['RESULT'] == 2) {
            $msg = 'pend ' . $row['NUNOTA'];
        } else  if ($row['RESULT'] == 3 && $tipo == 'O') {
            $msg = 'usuario ' . $row['NUNOTA'];
            $usulogado = $codusu;
            $usunota  = $row['CODUSU'];
        } else {
            $msg = $row['NUNOTA'];
        }

        $response = [
            'success' => [
                'msg' => $msg,
                'codusulog' => $usulogado,
                'codusunota' => $usunota,

            ]
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}


function verificaProximo($conn, $tipo, $codusu)
{
    try {
        $params = array($tipo, $codusu);
        $tsql = "SELECT * FROM [AD_FNT_VERIFICA_PROX_NOTA_CONTAGEM](?,?)";


        $stmt = sqlsrv_query($conn, $tsql, $params);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $msg = '';
        $usulogado = '';
        $usunota = '';

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        if ($row['RESULT'] == 2) {
            $msg = 'pend ' . $row['NUNOTA'];
        } else  if ($row['RESULT'] == 3) {
            $msg = 'sucess';
            $usulogado = $codusu;
            $usunota  = $row['CODUSU'];
        } else {
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


function pegaProximaNota($conn, $tipo, $codusu)
{
    try {
        $params = array($tipo, $codusu);
        $tsql = "SELECT * FROM [AD_FNT_RETORNA_PROX_NOTA_CONTAGEM2](?,?)";


        $stmt = sqlsrv_query($conn, $tsql, $params);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $msg = '';
        $nunota = '';
        $status = '';

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }


        $nunota = $row['NUNOTA'];
        $status = $row['STATUS'];
        $referencia = $row['REFERENCIA'];
        $endereco = $row['ENDERECO'];




        $response = [
            'success' => [
                'nunota' => $nunota,
                'status' => $status,
                'referencia' => $referencia,
                'endereco' => $endereco
            ]
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}


function verificaGerente($conn, $codusu)
{
    try {
        $params = array($codusu);
        $tsql = "select CODUSU
                 from tsiusu 
                 where codusu in (3813,89,3308,4046,3,1696, 32, 3195, 692, 3266, 42, 4418, 181, 694, 7257, 100,30,3564,135, 691,135)
                   and codusu = ?";

        $stmt = sqlsrv_query($conn, $tsql, $params);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $msg = '';

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        if (isset($row['CODUSU'])) {
            $msg = 'true';
        } else {
            $msg = 'false';
        }

        $response = [
            'success' => [
                'msg' => $msg,
            ]
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}



function usuarioPodePriorizar($codusu)
{
    $usuariosPrioridade = array(692, 89, 3, 1696, 3195, 3266, 42, 4418, 181, 694, 7257, 100);

    return in_array((int)$codusu, $usuariosPrioridade, true);
}


function verificaPermissaoPrioridade($conn, $codusu)
{
    try {
        $msg = usuarioPodePriorizar($codusu) ? 'true' : 'false';

        $response = [
            'success' => [
                'msg' => $msg,
            ]
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}


function alteraPrioridadeNota($conn, $tipo, $notas, $acao, $codusu)
{
    try {
        if (!usuarioPodePriorizar($codusu)) {
            throw new Exception('APP: Usuário sem permissão para alterar prioridade.');
        }

        if ($tipo !== 'N') {
            throw new Exception('APP: Prioridade disponível apenas para notas.');
        }

        $nunotas = explode(',', $notas);

        if (count($nunotas) === 0) {
            throw new Exception('APP: Selecione ao menos uma nota.');
        }

        $valor = ($acao === 'S') ? 1 : null;
        $placeholders = implode(',', array_fill(0, count($nunotas), '?'));

        $params = array_merge(array($valor), $nunotas);
        $tsql = "UPDATE TGFCAB SET REBOQUE3 = ? WHERE NUNOTA IN ($placeholders)";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            $erros = sqlsrv_errors(SQLSRV_ERR_ERRORS);
            $mensagemErro = 'Erro ao executar a consulta SQL.';
            if ($erros !== null) {
                $mensagemErro = preg_replace('/\[[^\]]*\]/', '', $erros[0]['message']);
            }
            throw new Exception($mensagemErro);
        }

        $proximo = sqlsrv_next_result($stmt);
        while ($proximo !== null) {
            if ($proximo === false) {
                $erros = sqlsrv_errors(SQLSRV_ERR_ERRORS);
                $mensagemErro = 'Erro ao executar a consulta SQL.';
                if ($erros !== null) {
                    $mensagemErro = preg_replace('/\[[^\]]*\]/', '', $erros[0]['message']);
                }
                throw new Exception($mensagemErro);
            }
            $proximo = sqlsrv_next_result($stmt);
        }

        sqlsrv_free_stmt($stmt);

        $response = [
            'success' => [
                'msg' => ($acao === 'S') ? 'APP: Prioridade aplicada com sucesso!' : 'APP: Prioridade removida com sucesso!',
            ]
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}


function atribuirNotaUsuario($conn, $tipo, $notas, $usuario)
{
    try {
        $msg = '';
        $params = array($tipo, $notas, $usuario);
        $tsql = "EXEC [AD_STP_ATRIBUIR_NOTA_CONTAGEM] ?, ?, ?";



        $stmt = sqlsrv_query($conn, $tsql, $params);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $msg = '';

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        if (isset($row['result'])) {
            $msg = 'true';
        } else {
            $msg = 'false';
        }

        $response = [
            'success' => [
                'msg' => $msg,
            ]
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}


function desatribuirNota($conn, $tipo, $notas, $usuario)
{
    try {
        $msg = '';
        $params = array($notas, $tipo, $usuario);
        $tsql = "EXEC [AD_STP_APAGA_CONTAGENS_EM_ANDAMENTO] ?, ? ,?";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $response = [
            'success' => [
                'msg' => 'ok',
            ]
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}



function buscaInfoCodUsu($conn, $codusu)
{
    try {
        $params = array($codusu);
        $tsql = "
        SELECT NOMEUSU
        FROM TSIUSU 
        WHERE CODUSU = ?
        
        ";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }


        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if (!isset($row['NOMEUSU'])) {

            throw new Exception('APP: Usuario inválido.');
        }

        $response = [
            'success' => [
                'nomeusu' => $row['NOMEUSU']
            ]
        ];


        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}



function buscaInfoNomeUsu($conn, $nomeusu)
{
    try {
        $params = array($nomeusu);
        $tsql = "
        SELECT CODUSU
        FROM TSIUSU 
        WHERE NOMEUSU = ?
        
        ";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }


        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if (!isset($row['CODUSU'])) {

            throw new Exception('APP: Usuario inválido.');
        }

        $response = [
            'success' => [
                'codusu' => $row['CODUSU']
            ]
        ];


        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}


function getReferenciaOp($conn, $nunota)
{
    try {
        $params = array($nunota);
        $tsql = "SELECT REFERENCIA 
                 from tgfpro inner join
                      tpripa on tpripa.codprodpa = tgfpro.codprod
                 WHERE tpripa.idiproc = ?";


        $stmt = sqlsrv_query($conn, $tsql, $params);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $msg = '';
        $usulogado = '';
        $usunota = '';

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }


        $msg = $row['REFERENCIA'];


        $response = [
            'success' => [
                'msg' => $msg
            ]
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
