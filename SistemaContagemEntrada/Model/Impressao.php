<?php


function montaLinhaImpressao($grupo)
{
    $dtUltMov = $grupo['dtUltMov'] instanceof DateTime ? $grupo['dtUltMov']->format('d/m/Y') : '';

    $linhaHtml = '<tr>';
    $linhaHtml .= '<td>' . $grupo['referencia'] . '</td>';
    $linhaHtml .= '<td>' . $grupo['descrprod'] . '</td>';
    $linhaHtml .= '<td>' . implode(' | ', $grupo['locais']) . '</td>';
    $linhaHtml .= '<td>' . $grupo['total'] . '</td>';
    $linhaHtml .= '<td>' . $dtUltMov . '</td>';
    $linhaHtml .= '</tr>';

    return $linhaHtml;
}


function buscaListaImpressao($conn, $tipo)
{
    try {
        $tsql = "
WITH IMPRESSAO AS (
	SELECT EST.AD_DT_ULT_MOV, EST.CODEMP, EST.CODPROD, PRO.REFERENCIA, PRO.DESCRPROD, EST.CODLOCAL, EST.ESTOQUE
	FROM TGFEST EST INNER JOIN
		 TGFPRO PRO ON EST.CODPROD = PRO.CODPROD
	WHERE EST.CODLOCAL BETWEEN 5900101 AND 5900504
	  AND EST.ESTOQUE > 0
	  AND EST.CODPARC = 0
), SALDO AS (
	SELECT IMP.CODPROD,
		   ISNULL(SUM(EST.ESTOQUE - EST.RESERVADO), 0) AS SALDO
	FROM (SELECT DISTINCT CODPROD FROM IMPRESSAO) IMP LEFT JOIN
		  TGFEST EST ON EST.CODPROD = IMP.CODPROD
             AND EST.CODEMP IN (1, 3, 10)
             AND EST.CODPARC = 0
             AND EST.CODLOCAL NOT BETWEEN 5900101 AND 5900504
             AND EST.CODLOCAL NOT IN (1988001, 3909907, 3907100, 1960101, 3909901, 3909903, 3909905, 3909906, 3909907, 3909910, 3909930, 5460101, 5980801, 5980808)
             AND EST.CODLOCAL NOT IN (2010101, 5000000, 5986000)
             AND EST.CODLOCAL NOT LIKE '60000%'
	GROUP BY IMP.CODPROD
), MEDIA AS (
	SELECT S.CODPROD,
           S.SALDO,
		   ISNULL((SELECT SUM(MDV.MEDIA6)
                   FROM sankhya.AD_MEDIAVENDAEMP MDV
                   WHERE MDV.CODEMP IN (1, 3, 10)
                     AND MDV.CODPROD = S.CODPROD), 0)
                 + CASE WHEN (SELECT COUNT(1)
                              FROM sankhya.AD_FORMULAS_PRODUCAO_W F
                              WHERE F.CODPRODMP = S.CODPROD
                                AND F.TIPOPROD = 'M') > 0
                        THEN ISNULL((SELECT SUM(MDV2.MEDIA6)
                                     FROM sankhya.AD_MEDIAVENDAEMP MDV2
                                     WHERE MDV2.CODEMP IN (1, 3, 10)
                                       AND MDV2.CODPROD IN (SELECT DISTINCT F2.CODPRODPA
                                                            FROM sankhya.AD_FORMULAS_PRODUCAO_W F2
                                                            WHERE F2.CODPRODMP = S.CODPROD
                                                              AND F2.TIPOPROD = 'M')), 0)
                        ELSE 0 END AS MEDIAVENDA
            FROM SALDO S
        )
SELECT IMP.CODPROD, IMP.AD_DT_ULT_MOV, IMP.CODEMP, IMP.REFERENCIA, IMP.DESCRPROD, IMP.CODLOCAL, IMP.ESTOQUE
FROM IMPRESSAO IMP INNER JOIN
     MEDIA M ON M.CODPROD = IMP.CODPROD
ORDER BY CASE WHEN ROUND(M.SALDO, 4) = 0 AND M.MEDIAVENDA > 0 THEN 0 ELSE 1 END,
         CASE WHEN ROUND(M.SALDO, 4) = 0 AND M.MEDIAVENDA > 0 THEN M.MEDIAVENDA END DESC,
         CASE WHEN M.MEDIAVENDA > 0 THEN 0 ELSE 1 END,
         CASE WHEN M.MEDIAVENDA > 0 THEN M.SALDO / M.MEDIAVENDA END,
         MIN(CASE IMP.CODEMP WHEN 1 THEN 1 WHEN 3 THEN 2 WHEN 10 THEN 3 ELSE 4 END) OVER (PARTITION BY IMP.CODPROD),
         MIN(ISNULL(IMP.AD_DT_ULT_MOV, GETDATE())) OVER (PARTITION BY IMP.CODPROD),
         IMP.CODPROD,
         CASE IMP.CODEMP WHEN 1 THEN 1 WHEN 3 THEN 2 WHEN 10 THEN 3 ELSE 4 END,
         ISNULL(IMP.AD_DT_ULT_MOV, GETDATE()),
         IMP.CODLOCAL
        ";

        $stmt = sqlsrv_query($conn, $tsql);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $tableHtml = '';
        $grupo = null;
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if ($grupo !== null && $grupo['codprod'] !== $row['CODPROD']) {
                $tableHtml .= montaLinhaImpressao($grupo);
                $grupo = null;
            }

            if ($grupo === null) {
                $grupo = [
                    'codprod' => $row['CODPROD'],
                    'referencia' => htmlspecialchars(trim((string)$row['REFERENCIA']), ENT_QUOTES, 'ISO-8859-1'),
                    'descrprod' => htmlspecialchars(trim((string)$row['DESCRPROD']), ENT_QUOTES, 'ISO-8859-1'),
                    'locais' => [],
                    'total' => 0,
                    'dtUltMov' => null,
                ];
            }

            $grupo['locais'][] = 'Emp.' . $row['CODEMP'] . ' - ' . $row['CODLOCAL'] . ': ' . $row['ESTOQUE'];
            $grupo['total'] += $row['ESTOQUE'];

            if ($row['AD_DT_ULT_MOV'] instanceof DateTime && ($grupo['dtUltMov'] === null || $row['AD_DT_ULT_MOV'] < $grupo['dtUltMov'])) {
                $grupo['dtUltMov'] = $row['AD_DT_ULT_MOV'];
            }
        }
        if ($grupo !== null) {
            $tableHtml .= montaLinhaImpressao($grupo);
        }
        sqlsrv_free_stmt($stmt);

        echo json_encode(['success' => utf8_encode($tableHtml)]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
