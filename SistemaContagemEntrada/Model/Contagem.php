<?php

function buscaItensContagem($conn, $nunota, $tipo, $codusu)
{
    try {
        $params = array($nunota, $tipo, $codusu);
        $tsql = "EXEC [sankhya].[AD_STP_GERA_CONTAGEM_ENTRADA_MERCADORIAS_APP] ?, ?, ?";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }
        $tableHtml = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tableHtml .= "<tr>";
            $tableHtml .= '<td>' . $row['REFERENCIA'] . '</td>';
            $tableHtml .= '<td>' . $row['CONTROLE'] . '</td>';  
            $tableHtml .= "<td>" . $row['QTDCONT'] . '</td>';
            $tableHtml .= "<td><i class='btnLupa fa-solid fa-magnifying-glass' onclick='mostraContagens(". $row['NUCONTITE'] . ")'></i></td>";
            $tableHtml .= '</tr>';
            $progressBar = $row['PROGRESS_BAR'];
        }

        echo json_encode([
            'success' => utf8_encode($tableHtml),
            'progress_bar' => $progressBar
        ]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function buscaInformacoesProduto($conn,$nunota,$referencia,$tipo)
{
    try {
        $params = array($nunota,$referencia, $referencia,$tipo);
        $tsql = "
        SELECT * FROM [AD_FNT_BUSCA_INFO_PRODUTO_ENTRADA_APP](?,?,?)
        ";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

       
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if (!isset($row['CODPROD'])) {
            
            throw new Exception('APP: Produto não existe nessa nota.');
        
         }

       
         $response = [
            'success' => [
                'codprod' => $row['CODPROD'],
                'tipcontest' => $row['TIPCONTEST'],
                'descrprod' => mb_convert_encoding($row['DESCRPROD'], 'UTF-8', mb_detect_encoding($row['DESCRPROD'], 'UTF-8, ISO-8859-1', true)),
                'agrupmin' => $row['AGRUPMIN'],
                'obsetiqueta' => $row['OBSETIQUETA'],
                'imagem' => base64_encode($row['IMAGEM']),
                'peso' => ($row['PESOBRUTO'] == 0 ? null : $row['PESOBRUTO']),
                'largura' => ($row['LARGURA'] == 0 ? null : $row['LARGURA']),
                'altura' => ($row['ALTURA'] == 0 ? null : $row['ALTURA']),
                'espessura' => ($row['ESPESSURA'] == 0 ? null : $row['ESPESSURA']),
                'qtdseparar' => ($row['QTDSEPARAR'] == 0 ? null : $row['QTDSEPARAR']),

            ]
        ];
        

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function atualizarDimensoes($conn,$referencia,$peso,$largura,$altura,$comprimento)
{
    try {
        $params = array($referencia,$referencia,$peso,$largura,$altura,$comprimento);
        $tsql = "

        DECLARE @CODPROD INT = (SELECT DISTINCT PRO.CODPROD FROM TGFPRO PRO INNER JOIN TGFBAR BAR ON PRO.CODPROD = BAR.CODPROD WHERE PRO.REFERENCIA = ? OR BAR.CODBARRA = ?)
        UPDATE TGFPRO 
        SET  pesobruto = ?,
              largura = ?,
              altura = ?,
              espessura = ?
        WHERE CODPROD = @CODPROD
        ";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

       
        $response = [
            'success' => [
                'msg' => 'APP: Item Atualizado com sucesso!',
            ]
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function desabilitaFinalizaCont($conn,$nunota)
{
    try {
        $params = array($nunota);
        $tsql = "
        DECLARE @NUNOTA INT = ?

        SELECT count(CODPROD) AS CODPROD
		FROM ad_tgfcontite ite INNER JOIN
			 ad_tgfcontcab cab ON cab.nucont = ite.nucont
		WHERE ISNULL(qtdcont,0) = 0 
		  AND nunota = @NUNOTA
		  AND cab.nucont = (SELECT MAX(nucont)
						FROM sankhya.AD_TGFCONTCAB
						WHERE nunota = @NUNOTA)
        ";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $msg = '';
        
        if ($row['CODPROD'] != 0) {
            $msg = 'false';
        } else {
            $msg = 'true';
        }
        
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


function atualizarContagem($conn,$referencia,$nunota,$tipo,$codbalanca,$qtdcont, $lote)
{
    try {
        $params = array($referencia,$referencia,$nunota,$tipo,$codbalanca,$qtdcont, $lote);
        $tsql = "
        DECLARE @CODPROD INT = (SELECT DISTINCT PRO.CODPROD FROM TGFPRO PRO INNER JOIN TGFBAR BAR ON PRO.CODPROD = BAR.CODPROD WHERE PRO.REFERENCIA = ? OR BAR.CODBARRA = ?)
        
          DECLARE @NUNOTA INT  = ?,
		  @TIPO VARCHAR(1) = ?,
          @CODBALANCA INT = ?,
          @QTDCONT FLOAT = ?,
          @LOTE VARCHAR(11) = ?,
          @NUCONT INT 

          SET @NUCONT = (SELECT MAX(NUCONT)
                         FROM sankhya.AD_TGFCONTCAB
                         WHERE NUNOTA = @NUNOTA
                             AND TIPO = @TIPO
                             AND STATUS = 'A')
       
           UPDATE AD_TGFCONTSUB
            set AD_TGFCONTSUB.QTDCONT = @QTDCONT,
				AD_TGFCONTSUB.CODBALANCA = @CODBALANCA,
                AD_TGFCONTSUB.DTCONT = GETDATE()
            FROM  AD_TGFCONTSUB  INNER JOIN 
                    AD_TGFCONTITE ITE ON  ITE.NUCONTITE = AD_TGFCONTSUB.NUCONTITE INNER JOIN
                    TGFPRO PRO ON PRO.CODPROD = ITE.CODPROD
            WHERE  ITE.NUCONT = @NUCONT
                AND ITE.CODPROD = @CODPROD
                AND ((PRO.TIPCONTEST = 'L' AND ITE.CONTROLE = @LOTE) OR PRO.TIPCONTEST <> 'L')
                
            UPDATE AD_TGFCONTITE 
            SET AD_TGFCONTITE.QTDCONT =  (SELECT SUM(QTDCONT) FROM AD_TGFCONTSUB sub WHERE SUB.nucontite = AD_TGFCONTITE.nucontite  )
            FROM AD_TGFCONTITE                         
            WHERE AD_TGFCONTITE.NUCONT = @NUCONT
            ";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

       
        $response = [
            'success' => [
                'msg' => 'APP: Contagem atualizada com sucesso!',
            ]
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}


function finalizarContagem($conn,$nunota,$tipo)
{
    try {
        $params = array($nunota,$tipo);
        $tsql = "
            DECLARE @NUNOTA INT  = ?,
                    @NUCONT INT ,
                    @TIPO varchar(100) = ?
                    
            SET @NUCONT = (SELECT MAX(NUCONT)
                                FROM sankhya.AD_TGFCONTCAB
                                WHERE NUNOTA = @NUNOTA
                                    AND TIPO = @TIPO
                                    AND STATUS = 'A')
            IF(@TIPO = 'N')
            BEGIN
                UPDATE ITE
                SET OBSERVACAO = CONCAT(CONVERT(VARCHAR(max), OBSERVACAO), ' | ', CONCAT('Solicitado: ', CONTITE.QTDNEG, ' Informado: ', CONTITE.QTDCONT)),
                    AD_OCORRENCIAVERIFICADA = 'N'
                FROM TGFITE ITE INNER JOIN
                    AD_TGFCONTCAB CONTCAB ON ITE.NUNOTA = CONTCAB.NUNOTA INNER JOIN
                    AD_TGFCONTITE CONTITE ON CONTCAB.NUCONT = CONTITE.NUCONT
                                        AND CONTITE.CODPROD = ITE.CODPROD
                                        AND CONTITE.CONTROLE = ITE.CONTROLE
                WHERE ITE.NUNOTA = @NUNOTA
                AND CONTCAB.NUCONT = @NUCONT
                AND CONTITE.QTDCONT <> CONTITE.QTDNEG
            END
            
            UPDATE AD_TGFCONTCAB 
            SET STATUS = 'C',
            DTFIM = GETDATE()
            WHERE NUCONT = @NUCONT
            
            IF(@TIPO = 'O')
            BEGIN
            
            DECLARE 
            @QTDCONT INT = (SELECT QTDCONT 
                            FROM AD_TGFCONTITE ITE INNER JOIN
                                AD_TGFCONTCAB CAB ON CAB.NUCONT = ITE.NUCONT 
                            WHERE NUNOTA = 774
                            AND CAB.NUCONT = @nucont)
           
            UPDATE TPRIPROC	 
            SET AD_OBSERVACAO = CONCAT(CONVERT(VARCHAR(max), OBSERVACAO), ' | ', CONCAT('Solicitado: ', (@QTDCONT -  TPRAPA.QTDAPONTADA), ' Informado: ', @QTDCONT))
            FROM TPRIPA INNER JOIN 
                TPRIATV ON TPRIATV.IDIPROC = TPRIPA.IDIPROC INNER JOIN 
                TPRAPO ON TPRAPO.IDIATV = TPRIATV.IDIATV INNER JOIN
                TPRAPA ON TPRAPA.NUAPO = TPRAPO.NUAPO INNER JOIN 
                TPRIPROC ON tpriatv.idiproc = TPRIPROC.IDIPROC 
            WHERE TPRIPROC.IDIPROC = @nunota
            AND TPRAPA.QTDAPONTADA <> @QTDCONT
              
            END";
            
        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $response = [
            'success' => [
                'msg' => 'APP: Contagem finalizada com sucesso!',
            ]
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function aplicarOcorrencia ($conn, $nunota, $tipo, $referencia, $lote, $ocorrencia) {
    try {
        $params = array($nunota, $tipo, $referencia, $lote, $ocorrencia);
        $tsql = "EXEC AD_STP_REGISTRA_OCORRENCIA_CONTAGEM ?, ?, ?, ?, ?";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $response = [
            'success' => [
                'msg' => 'APP: Ocorrência registrada com sucesso!',
            ]
        ];

         echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function mostraContagens ($conn, $nucontite) {
    try {
        $params = array($nucontite);
        $tsql = "SELECT ROW_NUMBER() OVER (ORDER BY NUCONTSUB) AS NRO,
                        CONTSUB.NUCONTSUB,
                        ISNULL(FORMAT(CONTSUB.DTCONT, 'dd/MM/yyyy HH:mm:ss'), '') AS DTCONT,
                        CASE
                            WHEN QTDCONT IS NULL THEN ''
                            ELSE CAST(QTDCONT AS VARCHAR(50)) 
                        END AS QTDCONT
                FROM AD_TGFCONTSUB CONTSUB
                WHERE CONTSUB.NUCONTITE = ?";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }
        $tableHtml = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $editButton = $row['QTDCONT'] === '' ? '' : "<i class='fa-solid fa-pen' style='color: #d80e0e;' onclick='editQtd(" . $row['NUCONTSUB'] . ")'></i>";

            $tableHtml .= "<tr id='row_" . $row['NUCONTSUB'] . "'>";
            $tableHtml .= '<td>' . $row['NRO'] . '</td>';
            $tableHtml .= "<td id='dt_" . $row['NUCONTSUB'] . "'>" . $row['DTCONT'] . "</td>";  
            $tableHtml .= "<td id='qtd_" . $row['NUCONTSUB'] . "' style='width: 26%'>" . $row['QTDCONT'] . '</td>';
            $tableHtml .= "<td>" . $editButton . "</td>";
            $tableHtml .= '</tr>';
        }

        echo json_encode([
            'success' => utf8_encode($tableHtml)
        ]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}



function verificaRecontagem($conn, $nunota, $referencia, $qtdcont, $codbalanca,$tipo, $lote)
{
    try {
        $params = array($referencia, $referencia, $nunota, $qtdcont, $tipo, $lote, $codbalanca, $codbalanca);
        $tsql = "
            DECLARE @CODPROD INT =  (SELECT DISTINCT TGFPRO.CODPROD 
                                    FROM TGFPRO LEFT JOIN 
                                        TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD
                                    WHERE (REFERENCIA = ? OR TGFBAR.CODBARRA = ?))
            DECLARE @NUNOTA INT = ? ,
                    @QTDCONT FLOAT  = ? ,
                    @TIPO char(1) = ?,
                    @LOTE VARCHAR(11) = ?,
                    @NUCONT INT 
            
            SET @NUCONT = (SELECT MAX(NUCONT)
                                FROM sankhya.AD_TGFCONTCAB
                                WHERE NUNOTA = @NUNOTA
                                    AND TIPO = @TIPO
                                    AND STATUS = 'A')
            IF(@TIPO = 'N')
            BEGIN
                SELECT ITE.QTDNEG as QTDNEG,
                    ISNULL((SELECT 1 FROM AD_TGFBALANCA WHERE CODBALANCA = ?),0) AS CODBALANCA
                FROM TGFITE ITE INNER JOIN
                     TGFPRO PRO ON ITE.CODPROD = PRO.CODPROD
                WHERE ITE.NUNOTA = @NUNOTA
                  AND ITE.CODPROD = @CODPROD
                  AND ((PRO.TIPCONTEST = 'L' AND ITE.CONTROLE = @LOTE) OR PRO.TIPCONTEST <> 'L')
            END 
            ELSE IF(@TIPO = 'O')
            BEGIN
               SELECT TPRIPA.QTDPRODUZIR as QTDNEG,
                    ISNULL((SELECT 1 FROM AD_TGFBALANCA WHERE CODBALANCA = ?),0) AS CODBALANCA
                 FROM TPRIPA INNER JOIN 
							  TPRIATV ON TPRIATV.IDIPROC = TPRIPA.IDIPROC INNER JOIN 
							  TPRAPO ON TPRAPO.IDIATV = TPRIATV.IDIATV INNER JOIN
							  TPRAPA ON TPRAPA.NUAPO = TPRAPO.NUAPO INNER JOIN 
							  TPRIPROC ON tpriatv.idiproc = TPRIPROC.IDIPROC  inner join
                              TGFPRO PRO ON PRO.CODPROD = TPRAPA.CODPRODPA 
                WHERE TPRIPROC.IDIPROC = @NUNOTA
                  AND TPRAPA.CODPRODPA = @CODPROD
                  AND ((PRO.TIPCONTEST = 'L' AND TPRAPA.CONTROLEPA = @LOTE) OR PRO.TIPCONTEST <> 'L')
            END
                ";

        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (isset($row['QTDNEG'])) {
            $tolerance = 0.000001;
            if (abs($qtdcont == $row['QTDNEG']) || $row['CODBALANCA'] !== 0) {
                echo atualizarContagem($conn,$referencia,$nunota,$tipo,$codbalanca,$qtdcont, $lote);
            } else {
                $params = array($referencia,$referencia,$tipo,$nunota, $lote, $qtdcont);
                $tsql = "
                DECLARE @CODPROD INT =  (SELECT DISTINCT TGFPRO.CODPROD 
                                    FROM TGFPRO LEFT JOIN 
                                        TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD
                                    WHERE (REFERENCIA = ? OR TGFBAR.CODBARRA = ?)),
                @NUCONT INT ,
                @TIPO char(1) = ?,
                @NUNOTA INT = ?,
                @LOTE VARCHAR(11) = ?
                

                SET @NUCONT = (SELECT MAX(NUCONT)
                                FROM sankhya.AD_TGFCONTCAB
                                WHERE NUNOTA = @NUNOTA
                                    AND TIPO = @TIPO
                                    AND STATUS = 'A')                                    
                                
                UPDATE AD_TGFCONTSUB
                SET AD_TGFCONTSUB.QTDRECONT = ?,
                    AD_TGFCONTSUB.DTRECONT = GETDATE()
                FROM  AD_TGFCONTSUB  INNER JOIN 
                    AD_TGFCONTITE ite ON  ite.nucontite = AD_TGFCONTSUB.nucontite INNER JOIN
                    TGFPRO PRO ON ITE.CODPROD = PRO.CODPROD
                WHERE ITE.NUCONT = @NUCONT
                    AND ITE.CODPROD = @CODPROD
                    AND ((PRO.TIPCONTEST = 'L' AND ITE.CONTROLE = @LOTE) OR PRO.TIPCONTEST <> 'L')
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
            $response = [
                'error' => 'APP: Item digitado não existe na nota.'
            ];
            echo json_encode($response);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}


function editaSubContagem ($conn, $nucontsub, $qtd) {
    try {
        $params = array($nucontsub, $qtd);
        $tsql = "DECLARE @NUCONTSUB INT = ?

                UPDATE AD_TGFCONTSUB
                SET QTDCONT = ?,
                    DTCONT = GETDATE()
                WHERE NUCONTSUB = @NUCONTSUB
                
                DECLARE @NUCONTITE INT = (SELECT NUCONTITE FROM AD_TGFCONTSUB WHERE NUCONTSUB = @NUCONTSUB)

                UPDATE AD_TGFCONTITE
                SET AD_TGFCONTITE.QTDCONT =  (SELECT SUM(QTDCONT) FROM AD_TGFCONTSUB SUB WHERE SUB.NUCONTITE = AD_TGFCONTITE.NUCONTITE)
                FROM AD_TGFCONTITE
                WHERE NUCONTITE = @NUCONTITE";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar o update SQL.');
        }

        $paramsSelect = array($nucontsub);
        $tsqlSelect = "SELECT FORMAT(DTCONT, 'dd/MM/yyyy HH:mm:ss') AS DATAATUAL FROM AD_TGFCONTSUB WHERE NUCONTSUB = ?";

        $stmtSelect = sqlsrv_query($conn, $tsqlSelect, $paramsSelect);

        $row = sqlsrv_fetch_array($stmtSelect, SQLSRV_FETCH_ASSOC);

        echo json_encode([
            'success' => $row['DATAATUAL']
        ]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}