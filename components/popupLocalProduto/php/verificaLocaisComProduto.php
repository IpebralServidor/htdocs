<?php
include "../../../conexaophp.php";

$referencia = $_GET["referencia"];
$codemp = $_GET["codemp"];
$params = array($referencia, $referencia, $codemp);

$tsql = "DECLARE @CODPROD INT = (SELECT DISTINCT TGFPRO.CODPROD 
                                FROM TGFPRO INNER JOIN 
                                     TGFBAR ON TGFPRO.CODPROD = TGFBAR.CODPROD 
                                WHERE (REFERENCIA = ? OR TGFBAR.CODBARRA = ?))
        DECLARE @CODEMP INT = ?;

        WITH LocalProdutos AS (
            SELECT CODLOCAL, ESTOQUE, CONTROLE, NULL AS DTNEG
            FROM TGFEST 
            WHERE CODPROD = @CODPROD 
            AND CODPARC = 0 
            AND CODEMP = @CODEMP
            AND CODLOCAL NOT IN (2010101, 2018888, 1370101, 5000000, 5080501, 5080502, 5080503, 5080504, 5080505, 5080701, 5080702, 5080703, 
                                5080704, 5080705, 5080901, 5080902, 5080903, 5080904, 5080905, 5081101, 5081102, 5081103, 5081104, 5081105, 
                                5081301, 5081302, 5081303, 5081304, 5081305, 5081501, 5081502, 5081503, 5081504, 5081505)
            AND CODLOCAL NOT LIKE '390%'
            AND CODLOCAL NOT LIKE '385%'
            AND CODLOCAL NOT LIKE '60000%'
            AND CODLOCAL NOT LIKE '507%'
            AND CODLOCAL NOT LIKE '%990000'
            AND CODLOCAL > CASE WHEN @CODEMP = 6 THEN 1000000
                                ELSE 1999999
                            END
            UNION
            SELECT DISTINCT ITE.CODLOCALORIG, 0, ITE.CONTROLE, MAX(CAB.DTNEG) AS DTNEG
            FROM TGFCAB CAB INNER JOIN
                TGFITE ITE ON CAB.NUNOTA = ITE.NUNOTA
            WHERE ITE.CODPROD = @CODPROD
            AND CAB.DTNEG >= DATEADD(YEAR, -1, GETDATE())
            AND CAB.CODEMP = @CODEMP
            AND CAB.CODTIPOPER LIKE '13%'
            AND ITE.CODLOCALORIG NOT IN (2010101, 2018888, 1370101, 5000000, 5080501, 5080502, 5080503, 5080504, 5080505, 5080701, 5080702, 5080703, 
                                5080704, 5080705, 5080901, 5080902, 5080903, 5080904, 5080905, 5081101, 5081102, 5081103, 5081104, 5081105, 
                                5081301, 5081302, 5081303, 5081304, 5081305, 5081501, 5081502, 5081503, 5081504, 5081505)
            AND ITE.CODLOCALORIG NOT LIKE '390%'
            AND ITE.CODLOCALORIG NOT LIKE '385%'
            AND ITE.CODLOCALORIG NOT LIKE '60000%'
            AND ITE.CODLOCALORIG NOT LIKE '507%'
            AND ITE.CODLOCALORIG NOT LIKE '%990000'
            AND ITE.CODLOCALORIG > CASE WHEN @CODEMP = 6 THEN 1000000
                                        ELSE 1999999
                                    END
            GROUP BY ITE.CODLOCALORIG, ITE.CONTROLE
        )



        SELECT CODLOCAL, 
            CONCAT(SUM(ESTOQUE), CASE WHEN MAX(LocalProdutos.DTNEG) = (SELECT MAX(DTNEG) FROM LocalProdutos) THEN ' - U'
                                    END, CASE WHEN CODLOCAL IN (SELECT DISTINCT CODLOCAL FROM AD_INVENTARIOITE WHERE CODPROD = @CODPROD AND CODEMP = @CODEMP AND TIPO <> 'N') THEN ' - I'
                                        END) AS ESTOQUE,  
            IIF(CONTROLE = 'I', '', CONTROLE) AS LOTE
        FROM LocalProdutos
        GROUP BY CODLOCAL, IIF(CONTROLE = 'I', '', CONTROLE)
        ORDER BY CODLOCAL";

$stmt = sqlsrv_query($conn, $tsql, $params);
$locais = '';

if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $locais .= "<tr>";
        $locais .= '<td>' . $row['CODLOCAL'] . '</td>';
        $locais .= '<td>' . $row['ESTOQUE'] . '</td>';
        $locais .= '<td>' . $row['LOTE'] . '</td>';
        $locais .= '</tr>';
    }
}

echo json_encode(['existe' => strlen($locais) > 0, 'locais' => $locais]);
