<?php

function buscaItensInventario($conn, $codemp, $codlocal, $codusu)
{
    try {

        $params = array($codemp, $codlocal, $codusu);
        $tsql = "EXEC [sankhya].[AD_STP_BUSCA_ITENS_INVENTARIO] ?, ?, ?";

        $stmt = sqlsrv_query($conn, $tsql, $params);


        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }


        $tableHtml = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tableHtml .= "<tr>";
            $tableHtml .= '<td>' . $row['REFERENCIA'] . '</td>';
            $tableHtml .= '<td>' . $row['CONTROLE'] . '</td>';
            $tableHtml .= '<td>' . $row['DTINVENTARIO'] . '</td>';
            $tableHtml .= '<td>' . $row['USUARIO'] . '</td>';
            $tableHtml .= "<td>" . $row['QTDATUAL'] . '</td>';
            /*if (isset($row['QTDATUAL'])) {
                $tableHtml .= "<td>
                                <button class='btnTransferencia' onclick='transfereItem(this)'>
                                    <i class='fa-solid fa-right-left'></i>
                                </button>
                            </td>";
            } else {
                $tableHtml .= "<td></td";
            }*/
            $tableHtml .= '</tr>';
            $progressBar = $row['PROGRESS_BAR'];
        }
        sqlsrv_free_stmt($stmt);

        echo json_encode([
            'success' => utf8_encode($tableHtml),
            'progress_bar' => $progressBar
        ]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function buscaInformacoesProduto($conn, $codemp, $referencia, $codlocal)
{
    try {
        $params = array($codemp, $codemp, $referencia, $codlocal);
        $tsql = "
        DECLARE @CODEMP_TEXT VARCHAR(100) = CASE 
                                                WHEN ? = 1 THEN (SELECT STRING_AGG(CODEMP, ',') FROM TGFEMP WHERE CODEMP NOT IN (6, 7))
                                                ELSE CAST(? AS VARCHAR(10))
                                            END

        SELECT PRO.CODPROD,
               PRO.TIPCONTEST, 
               PRO.DESCRPROD,
               PRO.AGRUPMIN,
               PRO.OBSETIQUETA,
               ISNULL(IMAGEM, (SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000)) AS IMAGEM 
        FROM AD_INVENTARIOITE INVITE 
        INNER JOIN TGFPRO PRO
            ON INVITE.CODPROD = PRO.CODPROD
        WHERE INVITE.CODEMP IN (SELECT VALUE FROM STRING_SPLIT(@CODEMP_TEXT, ','))
          AND PRO.REFERENCIA = ?
          AND INVITE.CODLOCAL = ?
        ";

        $stmt = sqlsrv_query($conn, $tsql, $params);


        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (!isset($row['CODPROD'])) {
            throw new Exception('Produto não existe no local.');
        }
        $response = [
            'success' => [
                'codprod' => $row['CODPROD'],
                'tipcontest' => $row['TIPCONTEST'],
                'descrprod' => $row['DESCRPROD'],
                'agrupmin' => $row['AGRUPMIN'],
                'obsetiqueta' => $row['OBSETIQUETA'],
                'imagem' => base64_encode($row['IMAGEM'])
            ]
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function verificaRecontagem($conn, $codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario)
{
    try {
        $params = array($referencia, $codemp, $codemp, $codlocal, $controle, $codlocal);
        $tsql = "DECLARE @CODEMP_ITEM INT
        DECLARE @CODPROD INT = (SELECT CODPROD FROM TGFPRO WHERE REFERENCIA = ?)
        DECLARE @CODEMP_TEXT VARCHAR(100) = CASE 
                                                WHEN ? = 1 THEN (SELECT STRING_AGG(CODEMP, ',') FROM TGFEMP WHERE CODEMP NOT IN (6, 7))
                                                ELSE CAST(? AS VARCHAR(10))
                                            END
        SELECT TOP 1 @CODEMP_ITEM = EST.CODEMP
        FROM TGFEST EST
        INNER JOIN TGFPRO PRO
          ON EST.CODPROD = PRO.CODPROD
        WHERE EST.CODEMP IN (SELECT VALUE FROM STRING_SPLIT(@CODEMP_TEXT, ','))
          AND EST.CODPROD = @CODPROD
          AND EST.CODLOCAL = ?
          AND EST.CODPARC = 0
          AND ((PRO.TIPCONTEST = 'L' AND EST.CONTROLE = ?) OR PRO.TIPCONTEST <> 'L')
        
        SELECT SUM(ESTOQUE - RESERVADO) AS QTDESTOQUE
        FROM TGFEST 
        WHERE CODEMP = @CODEMP_ITEM 
          AND CODPARC = 0 
          AND ESTOQUE - RESERVADO <> 0 
          AND CODLOCAL = ? 
          AND CODPROD = @CODPROD";

        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (isset($row['QTDESTOQUE'])) {
            $tolerance = 0.000001;
            if (abs($quantidade - $row['QTDESTOQUE']) < $tolerance) {
                echo contaProduto($conn, $codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario);
            } else {
                $params = array($referencia, $codemp, $codemp, $codlocal, $controle, $quantidade, $codlocal, $controle);
                $tsql = "DECLARE @CODEMP_ITEM INT
                DECLARE @CODPROD INT = (SELECT CODPROD FROM TGFPRO WHERE REFERENCIA = ?)
                DECLARE @CODEMP_TEXT VARCHAR(100) = CASE 
                                                        WHEN ? = 1 THEN (SELECT STRING_AGG(CODEMP, ',') FROM TGFEMP WHERE CODEMP NOT IN (6, 7))
                                                        ELSE CAST(? AS VARCHAR(10))
                                                    END
                
                SELECT TOP 1 @CODEMP_ITEM = EST.CODEMP
                FROM TGFEST EST
                INNER JOIN TGFPRO PRO
                    ON EST.CODPROD = PRO.CODPROD
                WHERE EST.CODEMP IN (SELECT VALUE FROM STRING_SPLIT(@CODEMP_TEXT, ','))
                    AND EST.CODPROD = @CODPROD
                    AND EST.CODLOCAL = ?
                    AND EST.CODPARC = 0
                    AND ((PRO.TIPCONTEST = 'L' AND EST.CONTROLE = ?) OR PRO.TIPCONTEST <> 'L')

                UPDATE AD_INVENTARIOITE
                SET QTDRECONTAGEM = ?,
                    DTRECONTAGEM = GETDATE()
                FROM AD_INVENTARIOITE
                INNER JOIN TGFPRO
                ON AD_INVENTARIOITE.CODPROD = TGFPRO.CODPROD
                WHERE AD_INVENTARIOITE.CODPROD = @CODPROD
                  AND AD_INVENTARIOITE.CODEMP = @CODEMP_ITEM
                  AND AD_INVENTARIOITE.CODLOCAL = ?
                  AND ((TGFPRO.TIPCONTEST = 'L' AND AD_INVENTARIOITE.CONTROLE = ?) OR TGFPRO.TIPCONTEST <> 'L')
                  AND AD_INVENTARIOITE.DTCRIACAO = (SELECT MAX(DTCRIACAO)
                                                    FROM AD_INVENTARIOITE INVITE
                                                    WHERE INVITE.CODLOCAL = AD_INVENTARIOITE.CODLOCAL
                                                      AND INVITE.CODEMP = AD_INVENTARIOITE.CODEMP
                                                      AND INVITE.CODPROD = AD_INVENTARIOITE.CODPROD
                                                      AND INVITE.CONTROLE = AD_INVENTARIOITE.CONTROLE)
                ";
                $stmt = sqlsrv_query($conn, $tsql, $params);
                if ($stmt === false) {
                    throw new Exception(var_dump(sqlsrv_errors(SQLSRV_ERR_ERRORS)));
                }
                $response = [
                    'recontagem' => 'Recontagem'
                ];
                echo json_encode($response);
            }
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function contaProduto($conn, $codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario)
{
    try {
        $params = array($codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario);
        $tsql = "EXEC [sankhya].[AD_STP_AJUSTA_ESTOQUE_INVENTARIO] ?, ?, ?, ?, ?, ?";
        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS);

            if ($errors !== null) {
                foreach ($errors as $error) {
                    // Remover a parte '[Microsoft][ODBC Driver 17 for SQL Server][SQL Server]' da mensagem
                    $errorMessage = $error['message'];
                    $errorMessage = preg_replace('/\[[^\]]*\]/', '', $errorMessage);

                    if (strpos($errorMessage, 'IPB: Estoque insuficiente! Produto:' . $referencia) !== false) {
                        // Se for especificamente esta mensagem, é porque o GERAITE dá erro ao transferir itens do 6000000.
                        // Neste caso, ainda são gerados os dados, e este erro é ignorado.
                    } else {
                        throw new Exception($errorMessage);
                    }
                }
            }
        }
        $response = [
            'success' => 'Produto inventariado com sucesso.'
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function finalizaInventario($conn, $codemp, $codlocal, $idUsuario)
{
    try {
        $params = array($codemp, $codemp, $idUsuario, $codemp, $codlocal, $codlocal);
        $tsql = "DECLARE @CODEMP_TEXT VARCHAR(100) = CASE 
                                                         WHEN ? = 1 THEN (SELECT STRING_AGG(CODEMP, ',') FROM TGFEMP WHERE CODEMP NOT IN (6, 7))
                                                         ELSE CAST(? AS VARCHAR(10))
                                                     END
        
        UPDATE AD_INVENTARIOCAB
        SET STATUS = 'C',
            DTFIM = GETDATE(),
            CODUSUFIM = ?
        FROM AD_INVENTARIOCAB INVCAB
        WHERE INVCAB.CODEMP = ? 
        AND INVCAB.CODLOCAL = ?
        AND INVCAB.STATUS = 'A'
        
        UPDATE TGFEST
        SET ATIVO = 'S'
        WHERE CODPARC = 0 
		AND CODEMP IN (SELECT VALUE FROM STRING_SPLIT(@CODEMP_TEXT, ','))
		AND ESTOQUE - RESERVADO <> 0
		AND CODLOCAL = ?";
        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $response = [
            'success' => 'Inventário finalizado para o local ' . $codlocal
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function transfereItem($conn, $codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario)
{
    try {
        $params = array($codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario);
        $tsql = "EXEC [sankhya].[AD_STP_TRANSFERE_ITEM_INVENTARIO] ?, ?, ?, ?, ?, ?";
        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $response = [
            'success' => 'Inventário finalizado para o local ' . $codlocal
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
