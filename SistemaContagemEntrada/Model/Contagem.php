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
    } catch  (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function buscaInformacoesProduto($conn,$nunota,$referencia,$tipo)
{
    try {
        $params = array($nunota,$referencia,$tipo);
        $tsql = "
        SELECT top 1 * FROM [AD_FNT_BUSCA_INFO_PRODUTO_ENTRADA_APP](?,?,?) order by codprod desc
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
                'qtdseparar' => $row['QTDSEPARAR'],
                'referencia' => $row['REFERENCIA'],
                'CD1' => $row['CD1'],
                'CD3' => $row['CD3'],
                'CODVOL' => $row['CODVOL'],
                'PRIMEIRAENTRADACODVOL' => $row['PRIMEIRAENTRADA']




            ]
        ];
        

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function atualizarDimensoes($conn,$referencia,$peso,$largura,$altura,$comprimento,$tipo)
{
    try {
        $params = array($referencia,$referencia,$peso,$largura,$altura,$comprimento,$tipo);
        $tsql = "
        
        DECLARE @CODPROD INT = (SELECT DISTINCT PRO.CODPROD
                                FROM TGFPRO PRO INNER JOIN
                                TGFBAR BAR ON PRO.CODPROD = BAR.CODPROD 
                                WHERE PRO.REFERENCIA = ? OR BAR.CODBARRA = ?)
        DECLARE @PESO FLOAT = ?,
                @LARGURA FLOAT = ?,
                @ALTURA FLOAT  = ?,
                @ESPESSURA float  = ?,
                @TIPO varchar(100) = ?

        IF @TIPO = 'N'
        BEGIN
            UPDATE TGFPRO 
            SET  pesobruto = @PESO,
                largura = @LARGURA,
                altura = @ALTURA,
                espessura = @ESPESSURA
            WHERE CODPROD = @CODPROD
        END

        IF @TIPO = 'O'
        BEGIN

            UPDATE AD_TGFPROCPL 
            SET  pesobruto = @PESO,
                largura = @LARGURA,
                altura = @ALTURA,
                espessura = @ESPESSURA
            WHERE CODPROD = @CODPROD


        END
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

	IF (SELECT TOP 1 TIPO FROM AD_TGFCONTCAB WHERE NUNOTA  = @NUNOTA) = 'N'
	BEGIN 
	
	SELECT 0  as CODPROD

	END ELSE
	BEGIN

       SELECT count(CODPROD) AS CODPROD
		FROM ad_tgfcontite ite INNER JOIN
			 ad_tgfcontcab cab ON cab.nucont = ite.nucont
		WHERE qtdcont IS null 
		  AND nunota = @NUNOTA
		  AND cab.nucont = (SELECT MAX(nucont)
						FROM sankhya.AD_TGFCONTCAB
						WHERE nunota = @NUNOTA)

	END
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

function atualizarContagem($conn,$referencia,$nunota,$tipo,$codbalanca,$qtdcont, $lote,$qtdseparar,$codusu)
{
    try {
        $params = array($referencia,$referencia,$referencia,$referencia,$tipo ,$nunota,$tipo,$nunota,$tipo,$codbalanca,$qtdcont, $lote,$qtdseparar,$codusu);
        $tsql = "
        DECLARE @CODPROD INT = (SELECT top 1 TGFPRO.CODPROD 
                                    FROM TGFPRO LEFT JOIN 
                                        TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD left join
                                        tgfpap on tgfpap.codprod = tgfpro.codprod
                                    WHERE (REFERENCIA = ? OR TGFBAR.CODBARRA = ? or tgfpap.codbarra = ? or tgfpap.codproparc = ?)
                         			 AND (? = 'N' AND EXISTS (SELECT 1 FROM tgfite WHERE nunota = ? AND codprod = TGFPRO.CODPROD) OR ? = 'O')

                                    )
          DECLARE @NUNOTA INT  = ?,
		  @TIPO VARCHAR(1) = ?,
          @CODBALANCA INT = ?,
          @QTDCONT FLOAT = ?,
          @LOTE VARCHAR(11) = ?,
          @NUCONT INT ,
          @QTDSEPARAR FLOAT = ?,
          @NUCONTITE INT,
          @CODUSU int = ?

          SET @NUCONT = (SELECT MAX(NUCONT)
                         FROM sankhya.AD_TGFCONTCAB
                         WHERE NUNOTA = @NUNOTA
                             AND TIPO = @TIPO
                             AND STATUS = 'A')
          SET @NUCONTITE = (SELECT ITE.NUCONTITE
                            FROM AD_TGFCONTITE ITE INNER JOIN
                                TGFPRO PRO ON ITE.CODPROD = PRO.CODPROD
                            WHERE ITE.NUCONT = @NUCONT
                            AND ITE.CODPROD = @CODPROD
                            AND ((PRO.TIPCONTEST = 'L' AND ITE.CONTROLE = @LOTE) OR PRO.TIPCONTEST <> 'L'))                    

            IF (SELECT SUB.QTDCONT 
            FROM sankhya.AD_TGFCONTSUB SUB INNER JOIN
                    sankhya.AD_TGFCONTITE ITE ON  ITE.NUCONTITE = SUB.NUCONTITE
            WHERE ITE.NUCONTITE = @NUCONTITE
                AND NUCONTSUB = (SELECT MAX(NUCONTSUB)
                                FROM  sankhya.AD_TGFCONTSUB
                                WHERE AD_TGFCONTSUB.NUCONTITE = ITE.NUCONTITE)) IS NOT NULL
            BEGIN 

                    INSERT INTO 
                    sankhya.AD_TGFCONTSUB
                    (
                        NUCONTITE,
                        QTDCONT,
                        DTCONT,
                        QTDRECONT,
                        DTRECONT,
                        CODBALANCA,
                        QTDSEPARAR
                    )
                    VALUES
                    (   
                        @NUCONTITE, -- NUCONTITE - int
                        @QTDCONT, -- QTDCONT - float
                        GETDATE(), -- DTCONT - datetime
                        NULL, -- QTDRECONT - float
                        NULL, -- DTRECONT - datetime
                        @CODBALANCA, -- CODBALANCA - varchar(100)
                        @QTDSEPARAR  -- QTDSEPARAR - float

                    )
            END ELSE
            BEGIN

                UPDATE AD_TGFCONTSUB
                set AD_TGFCONTSUB.QTDCONT = @QTDCONT,
                    AD_TGFCONTSUB.CODBALANCA = @CODBALANCA,
                    AD_TGFCONTSUB.DTCONT = GETDATE(),
                    AD_TGFCONTSUB.QTDSEPARAR = @QTDSEPARAR
                FROM  AD_TGFCONTSUB  INNER JOIN 
                        AD_TGFCONTITE ITE  ON  ITE.NUCONTITE = AD_TGFCONTSUB.NUCONTITE
                WHERE  ITE.NUCONTITE = @NUCONTITE
                   AND AD_TGFCONTSUB.NUCONTSUB = (SELECT MAX(NUCONTSUB)
                                                  FROM  sankhya.AD_TGFCONTSUB
                                                  WHERE AD_TGFCONTSUB.NUCONTITE = ITE.NUCONTITE)
                    
            END
            UPDATE AD_TGFCONTITE 
            SET AD_TGFCONTITE.QTDCONT =  (SELECT SUM(QTDCONT) 
                                        FROM AD_TGFCONTSUB sub
                                        WHERE SUB.nucontite = AD_TGFCONTITE.nucontite  )
            FROM AD_TGFCONTITE                         
            WHERE AD_TGFCONTITE.NUCONT = @NUCONT

            IF (@TIPO = 'N')
            BEGIN

                 UPDATE AD_TGFCONTITE 
                 SET CODUSU = @CODUSU
                 WHERE NUCONTITE = @NUCONTITE


            END

            IF(@TIPO = 'O')
            BEGIN
                UPDATE TPRAPA
                SET TPRAPA.AD_QTDCONTADA = (
                                    SELECT CASE WHEN QTDCONT - (SELECT ISNULL(SUM(QTDAPONTADA), 0)
                                                    FROM tpriatv ATV INNER JOIN
                                                            TPRAPO APO ON APO.IDIATV = ATV.IDIATV INNER join
                                                            TPRAPA APA ON APA.NUAPO= APO.NUAPO INNER JOIN
                                                            TPREFX EFX  ON EFX.IDEFX = ATV.IDEFX
                                                        WHERE ATV.IDIPROC = @NUNOTA
                                                        AND APO.SITUACAO = 'C'
                                                        AND EFX.DESCRICAO LIKE '%1152%') < 0 
                                                THEN 0
                                                ELSE QTDCONT - (SELECT ISNULL(SUM(QTDAPONTADA), 0)
                                                    FROM tpriatv ATV INNER JOIN
                                                            TPRAPO APO ON APO.IDIATV = ATV.IDIATV INNER join
                                                            TPRAPA APA ON APA.NUAPO= APO.NUAPO INNER JOIN
                                                            TPREFX EFX  ON EFX.IDEFX = ATV.IDEFX
                                                        WHERE ATV.IDIPROC = @NUNOTA
                                                        AND APO.SITUACAO = 'C'
                                                        AND EFX.DESCRICAO LIKE '%1152%')

                                            END
                                    FROM AD_TGFCONTCAB INNER JOIN
                                        AD_TGFCONTITE ON AD_TGFCONTCAB.NUCONT = AD_TGFCONTITE.NUCONT
                                    WHERE NUNOTA = @NUNOTA
                                        AND AD_TGFCONTITE.NUCONT = (SELECT MAX(NUCONT) 
                                                                    FROM AD_TGFCONTCAB CC
                                                                    WHERE CC.NUNOTA = AD_TGFCONTCAB.NUNOTA))
                FROM TPRIPROC INNER JOIN
                    TPRIATV ON TPRIPROC.IDIPROC = TPRIATV.IDIPROC INNER JOIN
                    TPREFX ON TPREFX.IDEFX = TPRIATV.IDEFX
                        AND TPREFX.IDPROC = TPRIPROC.IDPROC INNER JOIN
                    TPRAPO ON TPRAPO.IDIATV = TPRIATV.IDIATV INNER JOIN
                    TPRAPA ON TPRAPO.NUAPO = TPRAPA.NUAPO
                WHERE TPRIPROC.IDIPROC = @NUNOTA
                AND TPREFX.DESCRICAO LIKE '%1152%'
                AND TPRAPA.NUAPO = (SELECT MAX(A.NUAPO)
                                    FROM TPRAPA A INNER JOIN
                                        TPRAPO O ON O.NUAPO = A.NUAPO INNER JOIN
                                        TPRIATV TV ON TV.IDIATV = O.IDIATV 
                                    WHERE TV.IDIPROC = @NUNOTA 
                                        AND O.SITUACAO <> 'C')
            END 
           

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

function autorizatrava($conn,$user,$senha)
{
    try {
        $msg = '';
        $params = array($user, $senha);            
        $tsqlAutorizaCorte = "SELECT CODUSU FROM TSIUSU WHERE NOMEUSU = ? AND AD_SENHA = ? AND CODUSU IN (4046,3,1696, 32, 3195, 692, 3266, 42, 4418, 181, 694, 7257, 100,30,3564)";
        $stmtAutorizaCorte = sqlsrv_query($conn, $tsqlAutorizaCorte, $params);
        
        $row = sqlsrv_fetch_array($stmtAutorizaCorte, SQLSRV_FETCH_NUMERIC);                

        if (isset($row[0])) {
            $msg = 'sucess';
        } else {
            $msg = 'erro';
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



function verificaFinalizaContagem($conn,$nunota,$tipo, $codusu)
{
    try {
        $msg = '';
        $params = array($nunota,$tipo, $codusu);
        $tsql = "EXEC AD_STP_VERIFICA_FINALIZA_CONTAGEM_APP ?, ?, ?";
            
        $stmt = sqlsrv_query($conn, $tsql, $params);
        

        if ($stmt === false) {
            $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS);
            if ($errors !== null) {
                foreach ($errors as $error) {
                    $errorMessage = $error['message'];       
                    $msg = preg_replace('/\[[^\]]*\]/', '', $errorMessage);
                }
            }
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


function finalizarContagem($conn,$nunota,$tipo, $codusu,$separar)
{
    try {
        $msg = '';
        $params = array($nunota,$tipo, $codusu,$separar);
        $tsql = "EXEC AD_STP_FINALIZA_CONTAGEM_APP ?, ?, ?,?";
           
        $stmt = sqlsrv_query($conn, $tsql, $params);
        
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }
        // if ($stmt === false) {
        //     $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS);
            
        //     if ($errors !== null) {
        //         foreach ($errors as $error) {
        //             $errorMessage = $error['message'];       
        //             $errorMessage = preg_replace('/\[[^\]]*\]/', '', $errorMessage);                           
        //                 throw new Exception($errorMessage);
                    
        //         }
        //     }
        // }

        if ($tipo === 'O'){
        //Consulta para obter o número da transferência
            $paramsCheck = array($nunota, $nunota);
            $tsqlCheck = "SELECT NUTRANSF 
                          FROM AD_TGFCONTCAB 
                          WHERE NUNOTA = $nunota 
                            AND NUCONT = (SELECT MAX(nucont) 
                                          FROM sankhya.AD_TGFCONTCAB 
                                          WHERE nunota = $nunota)";
            
            $stmtCheck = sqlsrv_query($conn, $tsqlCheck);

            if ($stmtCheck === false) {
                throw new Exception("Erro ao buscar o número da transferência.");
            }
            
            $row = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);
            $nutransf = $row['NUTRANSF'] ;

            $msg = "APP: Contagem finalizada com sucesso! Transferência N° " . $nutransf;
        }else if ($tipo === 'N'){
            $msg = "APP: Contagem finalizada com sucesso!";
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


function finalizarContagemSeparar($conn,$nunota,$tipo, $codusu,$tdCD3EMP3,$qtdCD5EMP3,$QTDGONDOLA,$qtdCD5EMP1,$qtdCD5EMP10,$QTDCONT)
{
    try {
        $msg = '';
        $params = array($nunota,$tipo, $codusu,$tdCD3EMP3,$qtdCD5EMP3,$QTDGONDOLA,$qtdCD5EMP1,$qtdCD5EMP10,$QTDCONT);
        $tsql = "EXEC AD_STP_FINALIZA_CONTAGEM_SEPARAR_APP ?,?,?,?,?,?,?,?,?";
           
        $stmt = sqlsrv_query($conn, $tsql, $params);
        
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }
        // if ($stmt === false) {
        //     $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS);
            
        //     if ($errors !== null) {
        //         foreach ($errors as $error) {
        //             $errorMessage = $error['message'];       
        //             $errorMessage = preg_replace('/\[[^\]]*\]/', '', $errorMessage);                           
        //                 throw new Exception($errorMessage);
                    
        //         }
        //     }
        // }

        if ($tipo === 'O'){
        //Consulta para obter o número da transferência
            $paramsCheck = array($nunota, $nunota);
            $tsqlCheck = "SELECT NUTRANSF 
                          FROM AD_TGFCONTCAB 
                          WHERE NUNOTA = $nunota 
                            AND NUCONT = (SELECT MAX(nucont) 
                                          FROM sankhya.AD_TGFCONTCAB 
                                          WHERE nunota = $nunota)";
            
            $stmtCheck = sqlsrv_query($conn, $tsqlCheck);

            if ($stmtCheck === false) {
                throw new Exception("Erro ao buscar o número da transferência.");
            }
            
            $row = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);
            $nutransf = $row['NUTRANSF'] ;

            $msg = "APP: Contagem finalizada com sucesso! Transferência N° " . $nutransf;
        }else if ($tipo === 'N'){
            $msg = "APP: Contagem finalizada com sucesso!";
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




  
function verificaRecontagem($conn, $nunota, $referencia, $qtdcont, $codbalanca,$tipo, $lote, $qtdseparar,$codusu)
{
    try {
        $params = array($referencia, $referencia,$referencia,$referencia, $tipo,$nunota,$tipo , $nunota, $qtdcont, $tipo, $lote, $codbalanca, $codbalanca);
        $tsql = "
            DECLARE @CODPROD INT =  (SELECT top 1 TGFPRO.CODPROD 
                                    FROM TGFPRO LEFT JOIN 
                                        TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD left join
                                        tgfpap on tgfpap.codprod = tgfpro.codprod
                                    WHERE (REFERENCIA = ? OR TGFBAR.CODBARRA = ? or tgfpap.codbarra = ? or tgfpap.codproparc = ?)
                                     AND (?= 'N' AND EXISTS (SELECT 1 FROM tgfite WHERE nunota = ? AND codprod = tgfpro.CODPROD) OR ? = 'O')
                                    )
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
                SELECT sum(ITE.QTDNEG) as QTDNEG,
                    ISNULL((SELECT 1 FROM AD_TGFBALANCA WHERE CODBALANCA = ?),0) AS CODBALANCA
                FROM TGFITE ITE INNER JOIN
                     TGFPRO PRO ON ITE.CODPROD = PRO.CODPROD
                WHERE ITE.NUNOTA = @NUNOTA
                  AND ITE.CODPROD = @CODPROD
                  AND ((PRO.TIPCONTEST = 'L' AND ITE.CONTROLE = @LOTE) OR PRO.TIPCONTEST <> 'L')
            END 
            ELSE IF(@TIPO = 'O')
            BEGIN
                SELECT (TPRIPA.QTDPRODUZIR - ISNULL(SUM(tprapa.QTDAPONTADA),0) - (select ISNULL((QTDCONT),0) from sankhya.AD_TGFCONTITE WHERE NUCONT= (SELECT MAX(NUCONT)
                                                                                    FROM sankhya.AD_TGFCONTCAB
                                                                                    WHERE NUNOTA = @NUNOTA
                                                                                    AND TIPO = 'O'
                                                                                    AND STATUS = 'A'))) as QTDNEG,
                       ISNULL((SELECT 1 FROM AD_TGFBALANCA WHERE CODBALANCA = ?),0) AS CODBALANCA
                FROM TPRIPA INNER join
                    TPRIPROC ON TPRIPA.idiproc = TPRIPROC.IDIPROC  inner join
                    TGFPRO PRO ON PRO.CODPROD = TPRIPA.CODPRODPA  INNER JOIN
                    tpriatv ON tpriatv.idiproc = tpriproc.idiproc LEFT JOIN
                    tprapo ON tprapo.IDIATV = tpriatv.IDIATV LEFT JOIN
                    tprapa ON tprapa.nuapo = tprapo.nuapo
                WHERE TPRIPROC.IDIPROC = @NUNOTA
                  AND TPRIPA.CODPRODPA = @CODPROD
                  AND ((PRO.TIPCONTEST = 'L' AND TPRIPA.CONTROLEPA = @LOTE) OR PRO.TIPCONTEST <> 'L')
                group by TPRIPA.QTDPRODUZIR
            END
            
                ";

        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (isset($row['QTDNEG'])) {
            $tolerance = 0.000001;
            if ($qtdcont > $row['QTDNEG'] && $tipo == 'O'){
                throw new Exception('APP: Contagem acima do esperado! Verifique com gerente se já existem apontamentos pra essa op!');
            }
            // else if ($qtdcont > $row['QTDNEG'] && $tipo == 'N'){
            //     throw new Exception('APP: Contagem acima do esperado! Verifique com gerente');
            // } 
            
            else if (abs($qtdcont == $row['QTDNEG']) || $row['CODBALANCA'] !== 0) {
                echo atualizarContagem($conn,$referencia,$nunota,$tipo,$codbalanca,$qtdcont, $lote, $qtdseparar,$codusu);
            } else {
                $params = array($referencia,$referencia,$referencia,$referencia,$tipo,$nunota,$tipo,$tipo,$nunota, $lote, $qtdcont,$codusu);
                $tsql = "
                DECLARE @CODPROD INT =  (SELECT top 1 TGFPRO.CODPROD 
                                    FROM TGFPRO LEFT JOIN 
                                        TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD inner join
                                        tgfpap on tgfpap.codprod = tgfpro.codprod
                                    WHERE (REFERENCIA = ? OR TGFBAR.CODBARRA = ? or tgfpap.codbarra = ? or tgfpap.codproparc = ?)
                                    AND (? = 'N' AND EXISTS (SELECT 1 FROM tgfite WHERE nunota = ? AND codprod = TGFPRO.CODPROD) OR ? = 'O')
                                    )
            ,
                @NUCONT INT ,
                @TIPO char(1) = ?,
                @NUNOTA INT = ?,
                @LOTE VARCHAR(11) = ?,
                @QTDCONT FLOAT = ?,
                @NUCONTITE INT,
                @CODUSU INT = ?
                
               

                SET @NUCONT = (SELECT MAX(NUCONT)
                                FROM sankhya.AD_TGFCONTCAB
                                WHERE NUNOTA = @NUNOTA
                                    AND TIPO = @TIPO
                                    AND STATUS = 'A')          
                
                SET @NUCONTITE = (SELECT ITE.NUCONTITE
                                  FROM AD_TGFCONTITE ITE INNER JOIN
                                       TGFPRO PRO ON ITE.CODPROD = PRO.CODPROD
                                  WHERE ITE.NUCONT = @NUCONT
                                    AND ITE.CODPROD = @CODPROD
                                    AND ((PRO.TIPCONTEST = 'L' AND ITE.CONTROLE = @LOTE) OR PRO.TIPCONTEST <> 'L')
                                    )                   
                                    
                update ad_tgfcontite 
                set codusu = @codusu
                where nucontite = @NUCONTITE                   

                IF (SELECT SUB.QTDCONT 
                FROM sankhya.AD_TGFCONTSUB SUB INNER JOIN
                        sankhya.AD_TGFCONTITE ITE ON  ITE.NUCONTITE = SUB.NUCONTITE
                WHERE ITE.NUCONTITE = @NUCONTITE
                    AND NUCONTSUB = (SELECT MAX(NUCONTSUB)
                                    FROM  sankhya.AD_TGFCONTSUB
                                    WHERE AD_TGFCONTSUB.NUCONTITE = ITE.NUCONTITE)) IS NOT NULL
                BEGIN 

                        INSERT INTO 
                        sankhya.AD_TGFCONTSUB
                        (
                            NUCONTITE,
                            QTDCONT,
                            DTCONT,
                            QTDRECONT,
                            DTRECONT,
                            CODBALANCA,
                            QTDSEPARAR
                        )
                        VALUES
                        (   @NUCONTITE, -- NUCONTITE - int
                            NULL, -- QTDCONT - float
                            NULL, -- DTCONT - datetime
                            @QTDCONT, -- QTDRECONT - float
                            GETDATE(), -- DTRECONT - datetime
                            NULL, -- CODBALANCA - varchar(100)
                            NULL  -- QTDSEPARAR - float
                            )
                END ELSE
                BEGIN
                    UPDATE AD_TGFCONTSUB 
                    SET AD_TGFCONTSUB.QTDRECONT = @QTDCONT,
                        AD_TGFCONTSUB.DTRECONT = GETDATE()
                    FROM AD_TGFCONTSUB  INNER JOIN 
                         AD_TGFCONTITE ite ON  ite.nucontite = AD_TGFCONTSUB.nucontite
                    WHERE ITE.NUCONTITE = @NUCONTITE
                      AND AD_TGFCONTSUB.NUCONTSUB = (SELECT MAX(NUCONTSUB)
                                                     FROM  sankhya.AD_TGFCONTSUB
                                                     WHERE AD_TGFCONTSUB.NUCONTITE = ITE.NUCONTITE) 
                END
                ";
                $stmt = sqlsrv_query($conn, $tsql, $params);
                if ($stmt === false) {
                    throw new Exception(var_dump(sqlsrv_errors(SQLSRV_ERR_ERRORS)));
                } 


                $response = [
                    'recontagem' => 'Recontagem',
                    'qtdneg' => $row['QTDNEG']
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




function retornaQtdContada ($conn,$nunota) {
    try {
        $params = array($nunota);
        $tsql = "
                DECLARE @NUNOTA INT = ?        

                 select QTDCONT,
                 (SELECT QTDPRODUZIR FROM tpripa WHERE IDIPROC = @NUNOTA) - 
                 (SELECT ISNULL( SUM (QTDAPONTADA),0)
                    FROM TPRAPO  INNER JOIN 
                        TPRAPA ON TPRAPA.NUAPO = TPRAPO.NUAPO
                    WHERE TPRAPO.IDIATV = (SELECT MAX(IDIATV) 
                                    FROM sankhya.TPRIATV ATV 
                                    WHERE ATV.IDIPROC = @NUNOTA)) AS QTDPRODUZIR
                 

                 from ad_tgfcontite 
                 where nucont = ((SELECT MAX(NUCONT)
                                  FROM sankhya.AD_TGFCONTCAB
                                  WHERE NUNOTA = @NUNOTA
                                    AND TIPO = 'O'
                                    AND STATUS = 'A'))
                ";
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro na consulta.');
        }
        
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        echo json_encode([
            'success' => 'Contado: '. $row['QTDCONT'] .' Esperado: '. $row['QTDPRODUZIR']
        ]);

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}





function verificaQtdSeparar ($conn, $nunota,$tipo) {
    try {
        
        $params = array($nunota,$tipo);
        $tsql = " 
        
      SELECT * FROM [AD_FNT_QTD_SEPARAR_PALETES_CONTAGEM](?,?)			        
        
        ";


       $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row === false) {
            throw new Exception("Nenhum dado retornado da query.");
        }

        $codemp = '';
        $tipo = '';
        $qtdspearar= '';
        $qtdgondola = '';
        $qtdresto = '';
        $qtdcont = '';


   
        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

    
        else {
            $codemp = $row['CODEMP'];
            $tipo = $row['TIPO'];
            $qtdseparar = $row['QTDSEPARAR'];
            $qtdgondola = $row['QTDGONDOLA'];
            $qtdresto = $row['QTDRESTO'];
            $qtdcont = $row['QTDCONT'];

        }

        $response = [
            'success' => [
                'codemp' => $codemp,
                'tipo' => $tipo,
                'qtdseparar' => $qtdseparar,
                'qtdgondola'=> $qtdgondola,
                'qtdresto'=> $qtdresto,
                'qtdcont'=> $qtdcont,
            ]
        ];
        
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}


function editaSubContagem ($conn, $nucontsub, $qtd, $nunota, $tipo) {
    try {
        $params = array($nucontsub, $nunota, $tipo, $qtd);
        $tsql = "exec [AD_STP_EDITA_CONTAGENS_APP] ?,?,?,?
                ";
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            throw new Exception('Erro ao executar o update SQL.');
        }
        $rowEditSubContagem = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if($rowEditSubContagem['RET'] == 1) {
            throw new Exception('Contagem acima do esperado! Verifique com gerente se já existem apontamentos e digite a quantidade novamente');
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

