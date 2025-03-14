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
                    $statusText = 'Concluido';
                    $action = "onclick='confirmaAbrirInventario(this)'";
                    break;
                case 'D':
                    $color = 'white';
                    $statusText = 'Disponivel';
                    $action = "onclick='confirmaAbrirInventario(this)'";
                    break;
                case 'S':
                    $color = '#FF4D4D';
                    $statusText = 'Bloqueado - Separacao';
                    $action = "onclick='mostraBloqueio('" . $row['CODLOCAL'] . "')'";
                    break;
                case 'R':
                    $color = '#FF4D4D';
                    $statusText = 'Bloqueado - Reabastecimento';
                    $action = "onclick='mostraBloqueio(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'T':
                    $color = '#FF4D4D';
                    $statusText = 'Bloqueado - Troca Propriedade';
                    $action = "onclick='mostraBloqueio(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'M':
                    $color = '#FF4D4D';
                    $statusText = 'Bloqueado - Prod. Empresa diferente';
                    $action = "onclick='alert(`" . produtosEmpDiferente($conn, $codemp, $row['CODLOCAL']) . "`)'";
                    break;
                case 'E':
                    $color = '#FF4D4D';
                    $statusText = 'Bloqueado - Entrada de mercadoria';
                    $action = "onclick='mostraBloqueioEntradaMercadoria(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'CS':
                    $color = '#F08650';
                    $statusText = 'Concluido / Bloqueado - Separacao';
                    $action = "onclick='mostraBloqueio(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'CR':
                    $color = '#F08650';
                    $statusText = 'Concluido / Bloqueado - Reabastecimento';
                    $action = "onclick='mostraBloqueio(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'CT':
                    $color = '#F08650';
                    $statusText = 'Concluido / Bloqueado - Troca Propriedade';
                    $action = "onclick='mostraBloqueio(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'CM':
                    $color = '#F08650';
                    $statusText = 'Concluido / Bloqueado - Prod. Empresa diferente';
                    $action = "onclick='alert(`" . produtosEmpDiferente($conn, $codemp, $row['CODLOCAL']) . "`)'";
                    break;
                case 'CE':
                    $color = '#F08650';
                    $statusText = 'Concluido / Bloqueado - Entrada de mercadoria';
                    $action = "onclick='mostraBloqueioEntradaMercadoria(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'CP':
                    $color = '#9ebf78';
                    $statusText = 'Concluido Parcialmente';
                    $action = "onclick='confirmaAbrirInventario(this)'";
                    break;
                case 'CD':
                    $color = '#9ebf78';
                    $statusText = 'Concluido / Adicionado posteriormente';
                    $action = "onclick='confirmaAbrirInventario(this)'";
                    break;
                case 'CDS':
                    $color = '#F08650';
                    $statusText = 'Concluido / Adicionado posteriormente / Bloqueado - Separacao';
                    $action = "onclick='mostraBloqueio(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'CDR':
                    $color = '#F08650';
                    $statusText = 'Concluido / Adicionado posteriormente / Bloqueado - Reabastecimento';
                    $action = "onclick='mostraBloqueio(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'CDT':
                    $color = '#F08650';
                    $statusText = 'Concluido / Adicionado posteriormente / Bloqueado - Troca Propriedade';
                    $action = "onclick='mostraBloqueio(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'CDM':
                    $color = '#F08650';
                    $statusText = 'Concluido / Adicionado posteriormente / Bloqueado - Prod. Empresa diferente';
                    $action = "onclick='alert(`" . produtosEmpDiferente($conn, $codemp, $row['CODLOCAL']) . "`)'";
                    break;
                case 'CDE':
                    $color = '#F08650';
                    $statusText = 'Concluido / Adicionado posteriormente / Bloqueado - Entrada de mercadoria';
                    $action = "onclick='mostraBloqueioEntradaMercadoria(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'CPS':
                    $color = '#F08650';
                    $statusText = 'Concluido Parcialmente / Bloqueado - Separacao';
                    $action = "onclick='mostraBloqueio(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'CPR':
                    $color = '#F08650';
                    $statusText = 'Concluido Parcialmente / Bloqueado - Reabastecimento';
                    $action = "onclick='mostraBloqueio(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'CPT':
                    $color = '#F08650';
                    $statusText = 'Concluido Parcialmente / Bloqueado - Troca Propriedade';
                    $action = "onclick='mostraBloqueio(" . $row['CODLOCAL'] . ")'";
                    break;
                case 'CPM':
                    $color = '#F08650';
                    $statusText = 'Concluido Parcialmente / Bloqueado - Prod. Empresa diferente';
                    $action = "onclick='alert(`" . produtosEmpDiferente($conn, $codemp, $row['CODLOCAL']) . "`)'";
                    break;
                case 'CPE':
                    $color = '#F08650';
                    $statusText = 'Concluido Parcialmente / Bloqueado - Entrada de mercadoria';
                    $action = "onclick='mostraBloqueioEntradaMercadoria(" . $row['CODLOCAL'] . ")'";
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

function mostraBloqueio($conn, $codlocal, $codemp)
{
    try {
        $params = array($codlocal, $codemp);
        $tsql = "DECLARE @CODLOCAL INT = ?
                DECLARE @CODEMP INT = ?
                DECLARE @CODEMP_TEXT VARCHAR(100) = CASE 
                                                        WHEN @CODEMP = 1 THEN (SELECT STRING_AGG(CODEMP, ',') FROM TGFEMP WHERE CODEMP NOT IN (6, 7))
                                                        ELSE CAST(@CODEMP AS VARCHAR(10))
                                                    END

                SELECT ITE.NUNOTA, CAB.CODTIPOPER, CONVERT(VARCHAR, CAB.DTNEG, 103) AS DTNEG, PRO.REFERENCIA, ITE.CONTROLE, ITE.QTDNEG
                FROM TGFEST EST INNER JOIN
                    TGFPRO PRO ON EST.CODPROD = PRO.CODPROD INNER JOIN
                    TGFITE ITE ON EST.CODPROD = ITE.CODPROD
                            AND EST.CODLOCAL = ITE.CODLOCALORIG
                            AND EST.CODEMP = ITE.CODEMP
                            AND EST.CONTROLE = ITE.CONTROLE INNER JOIN
                    TGFCAB CAB ON ITE.NUNOTA = CAB.NUNOTA
                WHERE EST.CODEMP IN (SELECT VALUE FROM STRING_SPLIT(@CODEMP_TEXT, ','))
                AND EST.CODLOCAL = @CODLOCAL
                AND EST.ESTOQUE <> 0
                AND EST.CODPARC = 0
                AND EST.RESERVADO <> 0
                AND ITE.RESERVA = 'S'
                AND ITE.PENDENTE = 'S'
                AND ITE.ATUALESTOQUE = 1";
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $tableHtml = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tableHtml .= "<tr>";
            $tableHtml .= '<td>' . $row['NUNOTA'] . '</td>';
            $tableHtml .= '<td>' . $row['CODTIPOPER'] . '</td>';
            $tableHtml .= '<td>' . $row['DTNEG'] . '</td>';
            $tableHtml .= '<td>' . $row['REFERENCIA'] . '</td>';
            $tableHtml .= '<td>' . $row['CONTROLE'] . '</td>';
            $tableHtml .= '<td>' . $row['QTDNEG'] . '</td>';
            $tableHtml .= '</tr>';
        }
        sqlsrv_free_stmt($stmt);
        echo json_encode(['success' => utf8_encode($tableHtml)]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function mostraBloqueioEntradaMercadoria($conn, $codlocal, $codemp)
{
    try {
        $params = array($codlocal, $codemp);
        $tsql = "DECLARE @CODLOCAL INT = ?
                DECLARE @CODEMP INT = ?
                DECLARE @CODEMP_TEXT VARCHAR(100) = CASE 
                                                        WHEN @CODEMP = 1 THEN (SELECT STRING_AGG(CODEMP, ',') FROM TGFEMP WHERE CODEMP NOT IN (6, 7))
                                                        ELSE CAST(@CODEMP AS VARCHAR(10))
                                                    END

                SELECT CASE WHEN CABORIG.STATUSNOTA = 'L' THEN 'Sim'
                            WHEN CABORIG.STATUSNOTA <> 'L' THEN 'Nao'
                        END AS CONFIRMADO,
                    CABORIG.NUNOTA AS NUNOTAORIG, 
                    CONVERT(VARCHAR, CABORIG.DTNEG, 103) AS DTNEGORIG, 
                    TGFCAB.NUNOTA, 
                    CONVERT(VARCHAR, TGFCAB.DTNEG, 103) AS DTNEG, 
                    TGFPRO.REFERENCIA, 
                    TGFITE.CONTROLE, 
                    TGFITE.QTDNEG
                FROM TGFCAB INNER JOIN 
                    TGFITE ON TGFCAB.NUNOTA = TGFITE.NUNOTA INNER JOIN
                    TGFCAB CABORIG ON CABORIG.NUNOTA = TGFCAB.AD_VINCULONF INNER JOIN
                    TGFPRO ON TGFPRO.CODPROD = TGFITE.CODPROD
                WHERE TGFITE.ATUALESTOQUE = 0
                AND TGFCAB.DTNEG > '20240101'
                AND TGFITE.SEQUENCIA < 0
                AND TGFITE.CODLOCALORIG = @CODLOCAL
                AND TGFITE.CODEMP IN (SELECT VALUE FROM STRING_SPLIT(@CODEMP_TEXT, ','))
                AND (SELECT CAB.TIPMOV FROM tgfcab CAB WHERE CAB.nunota = tgfcab.AD_vinculonf) = 'C'";
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $tableHtml = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tableHtml .= "<tr>";
            $tableHtml .= '<td>' . $row['CONFIRMADO'] . '</td>';
            $tableHtml .= '<td>' . $row['NUNOTAORIG'] . '</td>';
            $tableHtml .= '<td>' . $row['DTNEGORIG'] . '</td>';
            $tableHtml .= '<td>' . $row['NUNOTA'] . '</td>';
            $tableHtml .= '<td>' . $row['DTNEG'] . '</td>';
            $tableHtml .= '<td>' . $row['REFERENCIA'] . '</td>';
            $tableHtml .= '<td>' . $row['CONTROLE'] . '</td>';
            $tableHtml .= '<td>' . $row['QTDNEG'] . '</td>';
            $tableHtml .= '</tr>';
        }
        sqlsrv_free_stmt($stmt);
        echo json_encode(['success' => utf8_encode($tableHtml)]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function produtosEmpDiferente($conn, $codemp, $codlocal)
{
    $params = array($codemp, $codlocal);
    $tsql = "DECLARE @CODEMP INT = ?
            DECLARE @CODEMP_TEXT VARCHAR(100) = CASE 
                                                    WHEN @CODEMP = 1 THEN (SELECT STRING_AGG(CODEMP, ',') FROM TGFEMP WHERE CODEMP NOT IN (6, 7))
                                                    ELSE CAST(@CODEMP AS VARCHAR(10))
                                                END;
            WITH REFERENCIA AS (
                SELECT PRO.REFERENCIA
                FROM TGFEST EST INNER JOIN
                    TGFPRO PRO ON EST.CODPROD = PRO.CODPROD
                WHERE EST.CODPARC = 0
                AND EST.ESTOQUE <> 0
                AND EST.CODEMP IN (SELECT VALUE FROM STRING_SPLIT(@CODEMP_TEXT, ','))
                AND EST.CODLOCAL = ?
                AND (EST.CODLOCAL NOT IN (5000000, 2010101, 2018888, 1370101, 1990000, 3990000, 5990000, 8990000, 5080501, 5080502, 5080503, 5080504, 5080505,
                                                5080701, 5080702, 5080703, 5080704, 5080705, 5080901, 5080902, 5080903, 5080904, 5080905, 5081101, 5081102, 5081103,
                                                5081104, 5081105, 5081301, 5081302, 5081303, 5081304, 5081305, 5081501, 5081502, 5081503, 5081504, 5081505)
                            OR (EST.CODLOCAL = 2010101 AND @CODEMP = 6))
                GROUP BY PRO.REFERENCIA
                HAVING COUNT(DISTINCT CODEMP) > 1
            )

            SELECT CONCAT('Produtos ', STRING_AGG(TRIM(REFERENCIA), ', '), ' neste local em empresas diferentes.') AS REF
            FROM REFERENCIA";
    $stmt = sqlsrv_query($conn, $tsql, $params);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    return $row['REF'];
}
