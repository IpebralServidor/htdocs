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
        $params = array($codemp, $codemp, $referencia, $referencia, $codlocal, $codemp, $codlocal);
        $tsql = "
        DECLARE @CODEMP_TEXT VARCHAR(100) = CASE 
                                        WHEN ? = 1 THEN (SELECT STRING_AGG(CODEMP, ',') FROM TGFEMP WHERE CODEMP NOT IN (6, 7))
                                        ELSE CAST(? AS VARCHAR(10))
                                    END
        DECLARE @CODPROD INT = (SELECT DISTINCT PRO.CODPROD FROM TGFPRO PRO INNER JOIN TGFBAR BAR ON PRO.CODPROD = BAR.CODPROD WHERE PRO.REFERENCIA = ? OR BAR.CODBARRA = ?)
        SELECT PRO.CODPROD,
                PRO.TIPCONTEST, 
                PRO.DESCRPROD,
                PRO.AGRUPMIN,
                PRO.OBSETIQUETA,
                ISNULL(IMAGEM, (SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000)) AS IMAGEM,
                INVITE.NUNOTA,
                ISNULL(CONVERT(VARCHAR(100), PEM.AD_QTDMAXLOCAL), '') AS QTDMAX
        FROM TGFPRO PRO LEFT JOIN
             AD_INVENTARIOITE INVITE ON INVITE.CODPROD = PRO.CODPROD
                                    AND INVITE.CODLOCAL = ?
                                    AND INVITE.CODEMP IN (SELECT VALUE FROM STRING_SPLIT(@CODEMP_TEXT, ',')) LEFT JOIN
             TGFPEM PEM ON PEM.CODEMP = ?
                    AND PEM.CODPROD = PRO.CODPROD
                    AND PEM.CODLOCALPAD = ?
        WHERE PRO.CODPROD = @CODPROD
        ";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if (!isset($row['NUNOTA'])) {
            if (verificaSeisMil($conn, $codemp, $referencia, 'N') === 'N') {
                throw new Exception('Produto não existe no local.');
            }
        }

        $response = [
            'success' => [
                'codprod' => $row['CODPROD'],
                'tipcontest' => $row['TIPCONTEST'],
                'descrprod' => mb_convert_encoding($row['DESCRPROD'], 'UTF-8', mb_detect_encoding($row['DESCRPROD'], 'UTF-8, ISO-8859-1', true)),
                'agrupmin' => $row['AGRUPMIN'],
                'obsetiqueta' => $row['OBSETIQUETA'],
                'imagem' => base64_encode($row['IMAGEM']),
                'nunota' => $row['NUNOTA'],
                'qtdmax' => $row['QTDMAX']
            ]
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function verificaRecontagem($conn, $codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario, $qtdmax)
{
    try {
        $paramsLote = array($referencia, $referencia, $controle);
        $tsqlLote = "SELECT DISTINCT TGFPRO.TIPCONTEST AS TIPCONTEST
                    FROM TGFPRO LEFT JOIN 
                        TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD
                    WHERE (REFERENCIA = ? OR TGFBAR.CODBARRA = ?)";
        $stmtLote = sqlsrv_query($conn, $tsqlLote, $paramsLote);
        $rowLote = sqlsrv_fetch_array($stmtLote, SQLSRV_FETCH_ASSOC);
        if ($rowLote['TIPCONTEST'] == 'L' && $controle == '') {
            throw new Exception('Produto controlado por lote, favor informar o lote.');
        }

        $params = array($referencia, $referencia, $codemp, $codemp, $codlocal, $controle, $codlocal);
        $tsql = "
            DECLARE @CODEMP_ITEM INT
            DECLARE @CODPROD INT =  (SELECT DISTINCT TGFPRO.CODPROD 
                                    FROM TGFPRO LEFT JOIN 
                                        TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD
                                    WHERE (REFERENCIA = ? OR TGFBAR.CODBARRA = ?))

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
            AND EST.ESTOQUE <> 0
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
                echo contaProduto($conn, $codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario, $qtdmax);
            } else {
                $params = array($referencia, $codemp, $codemp, $codlocal, $controle, $quantidade, $codlocal, $controle);
                $tsql = "
                DECLARE @REFERENCIA VARCHAR(100) = ?
                DECLARE @CODEMP_ITEM INT
                 DECLARE @CODPROD INT =  (SELECT DISTINCT TGFPRO.CODPROD 
                                    FROM TGFPRO INNER JOIN 
                                        TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD
                                    WHERE (REFERENCIA = @REFERENCIA OR TGFBAR.CODBARRA = @REFERENCIA))
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
                    AND EST.ESTOQUE <> 0
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
        } else {
            if (verificaSeisMil($conn, $codemp, $referencia, $controle) === 'N') {
                echo json_encode(['error' => 'Produto/codigo de barra não existe']);
            } else {
                echo contaProduto($conn, $codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario, $qtdmax);
            }
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function contaProduto($conn, $codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario, $qtdmax)
{
    try {
        $params = array($codemp, $codlocal, $referencia, $controle, $quantidade, $idUsuario, $qtdmax);
        $tsql = "EXEC [sankhya].[AD_STP_AJUSTA_ESTOQUE_INVENTARIO] ?, ?, ?, ?, ?, ?, ?";
        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS);

            if ($errors !== null) {
                foreach ($errors as $error) {
                    // Remover a parte '[Microsoft][ODBC Driver 17 for SQL Server][SQL Server]' da mensagem
                    $errorMessage = $error['message'];
                    $errorMessage = preg_replace('/\[[^\]]*\]/', '', $errorMessage);

                    if (strpos($errorMessage, 'IPB GERAITE: Estoque insuficiente! Produto:' . $referencia) !== false) {
                        // Se for especificamente esta mensagem, é porque o GERAITE dá erro ao transferir itens do 6000000.
                        // Neste caso, ainda são gerados os dados, e este erro é ignorado.
                        $response = [
                            'success' => 'Produto inventariado com sucesso.'
                        ];
                        echo json_encode($response);
                    } else {
                        throw new Exception($errorMessage);
                    }
                }
            }
        } else {
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
            $response = [
                'success' => 'Produto inventariado com sucesso.' . $row[0]
            ];
            echo json_encode($response);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function finalizaInventario($conn, $codemp, $codlocal, $idUsuario)
{
    try {
        $params = array($codemp, $codemp, $codemp, $codlocal, $idUsuario, $codemp, $codlocal, $codlocal);
        $tsql = "DECLARE @CODEMP_TEXT VARCHAR(100) = CASE 
                                                         WHEN ? = 1 THEN (SELECT STRING_AGG(CODEMP, ',') FROM TGFEMP WHERE CODEMP NOT IN (6, 7))
                                                         ELSE CAST(? AS VARCHAR(10))
                                                     END
        
        UPDATE AD_INVENTARIOCAB
        SET STATUS = CASE WHEN ISNULL((SELECT COUNT(1)
                                FROM AD_INVENTARIOCAB INVCAB
                                INNER JOIN AD_INVENTARIOITE INVITE
                                ON INVCAB.NUNOTA = INVITE.NUNOTA
                                WHERE INVCAB.CODEMP = ?
                                  AND INVCAB.CODLOCAL = ?
                                  AND INVCAB.STATUS = 'A'
                                  AND INVITE.TIPO = 'N'), 0) > 0 THEN 'CP'
                          ELSE 'C'
                     END,
            DTFIM = GETDATE(),
            CODUSUFIM = ?
        FROM AD_INVENTARIOCAB INVCAB
        INNER JOIN AD_INVENTARIOITE INVITE
        ON INVCAB.NUNOTA = INVITE.NUNOTA
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

function verificaSeisMil($conn, $codemp, $referencia, $controle)
{
    $paramsSeisMil = array($codemp, $codemp, $referencia, $referencia, $controle);

    $tsqlSeisMil = "
    DECLARE @CODEMP_TEXT VARCHAR(100) = CASE 
                                            WHEN ? = 1 THEN (SELECT STRING_AGG(CODEMP, ',') FROM TGFEMP WHERE CODEMP NOT IN (6, 7))
                                            ELSE CAST(? AS VARCHAR(10))
                                        END
    DECLARE @CODPROD INT = (SELECT DISTINCT PRO.CODPROD FROM TGFPRO PRO INNER JOIN TGFBAR BAR ON PRO.CODPROD = BAR.CODPROD WHERE PRO.REFERENCIA = ? OR BAR.CODBARRA = ?)
    DECLARE @CONTROLE VARCHAR(50) = ?

    SELECT TOP 1 ESTOQUE
    FROM TGFEST EST INNER JOIN
         TGFPRO PRO ON EST.CODPROD = PRO.CODPROD
    WHERE EST.CODPARC = 0 
    AND EST.CODEMP IN (SELECT VALUE FROM STRING_SPLIT(@CODEMP_TEXT, ','))
    AND EST.ESTOQUE <> 0
    AND EST.CODPROD = @CODPROD
    AND (((PRO.TIPCONTEST = 'L' AND EST.CONTROLE = @CONTROLE) OR PRO.TIPCONTEST <> 'L') OR @CONTROLE = 'N')
    AND CODLOCAL BETWEEN 6000000 AND 6000099
    ";
    $stmtSeisMil = sqlsrv_query($conn, $tsqlSeisMil, $paramsSeisMil);

    if ($stmtSeisMil === false) {
        throw new Exception('PHP: Erro ao executar a consulta SQL.');
    }
    $rowSeisMil = sqlsrv_fetch_array($stmtSeisMil, SQLSRV_FETCH_ASSOC);
    if (!isset($rowSeisMil['ESTOQUE'])) {
        return 'N';
    } else {
        return 'S';
    }
}
