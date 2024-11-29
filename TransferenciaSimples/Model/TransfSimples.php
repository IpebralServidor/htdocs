<?php

function buscaInformacoesProduto($conn, $referencia)
{
    try {
        $params = array($referencia, $referencia);
        $tsql = "SELECT TIPCONTEST, ISNULL(IMAGEM, (SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000)) AS IMAGEM FROM TGFPRO INNER JOIN TGFBAR ON TGFPRO.CODPROD = TGFBAR.CODPROD WHERE REFERENCIA = ? OR CODBARRA = ?";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (!isset($row['IMAGEM'])) {
            throw new Exception('Produto não encontrado.');
        }
        $response = [
            'success' => [
                'tipcontest' => $row['TIPCONTEST'],
                'imagem' => base64_encode($row['IMAGEM'])
            ]
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function buscaLocalPadrao($conn, $referencia, $codemp)
{
    try {
        $params = array($referencia, $referencia , $codemp);
        $tsql = "SELECT CODLOCALPAD FROM TGFPEM INNER JOIN 
						TGFPRO ON TGFPEM.CODPROD = TGFPRO.CODPROD INNER JOIN
						TGFBAR ON TGFPRO.CODPROD = TGFBAR.CODPROD
                   WHERE (TGFPRO.REFERENCIA = ? OR TGFBAR.CODBARRA = ?)
                   AND CODEMP = ?
        ";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (!isset($row['CODLOCALPAD'])) {
            throw new Exception('Produto não encontrado.');
        }
        $response = [
            'success' => [
                'codlocalpad' => $row['CODLOCALPAD']
            ]
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function buscaInformacoesLocal($conn, $codemp, $referencia, $endsaida, $lote)
{
    try {
        $params = array($codemp, $referencia, $referencia, $endsaida, $lote);

        $tsql = "SELECT SUM(EST.ESTOQUE - EST.RESERVADO) AS QTDLOCAL, ISNULL(PEM.AD_QTDMAXLOCAL, -1) AS QTDMAX FROM TGFEST EST
                INNER JOIN TGFPRO PRO
                ON EST.CODPROD = PRO.CODPROD
                LEFT JOIN TGFPEM PEM
                ON EST.CODPROD = PEM.CODPROD
                AND EST.CODEMP = PEM.CODEMP
                AND EST.CODLOCAL = PEM.CODLOCALPAD
				INNER JOIN TGFBAR BAR
				ON PEM.CODPROD = BAR.CODPROD
                WHERE EST.CODEMP = ?
                AND EST.CODPARC = 0 
                AND (PRO.REFERENCIA = ?
				OR BAR.CODBARRA = ?)
                AND EST.CODLOCAL = ?
                AND ((PRO.TIPCONTEST = 'L' AND EST.CONTROLE = ?) OR PRO.TIPCONTEST <> 'L')
                AND ESTOQUE - RESERVADO > 0
                AND RESERVADO = 0
                GROUP BY ISNULL(PEM.AD_QTDMAXLOCAL, -1)";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (!isset($row['QTDLOCAL'])) {
            throw new Exception('Produto com reserva ou não encontrado neste endereço.');
        }

        $response = [
            'success' => [
                'qtdlocal' => $row['QTDLOCAL'],
                'qtdmax' => $row['QTDMAX']
            ]
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function buscaQtdMax($conn, $referencia, $codemp, $endchegada)
{
    try {
        $params = array($referencia, $codemp, $endchegada);
        $tsql = "SELECT ISNULL(AD_QTDMAXLOCAL, 0) AS QTDMAX
                 FROM TGFPEM 
                 WHERE CODPROD = (SELECT CODPROD FROM TGFPRO WHERE REFERENCIA = ?)
                 AND CODEMP = ?
                 AND (CODLOCALPAD = ? OR CODLOCALPAD = 1990000)
        ";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (!isset($row['QTDMAX'])) {
            $qtdmax = '';
        } else {
            $qtdmax = $row['QTDMAX'];
        }
        $response = [
            'success' => [
                'qtdmax' => $qtdmax
            ]
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function validaParametros($conn, $codemp, $referencia, $lote, $endsaida, $endchegada, $qtdmax)
{
    try {
        $params = array($codemp, $referencia, $lote, $endsaida, $endchegada, $qtdmax);

        $tsql = "SELECT * FROM [sankhya].[AD_FNT_VALIDA_PARAMETROS_TRANSF_SIMPLES_APP](?, ?, ?, ?, ?, ?)";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if ($row['MSG'] != 'SUCESSO') {
            throw new Exception($row['MSG']);
        } else {
            $response = [
                'success' => $row['MSG']
            ];
        }

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function transferirProduto($conn, $codemp, $referencia, $lote, $endsaida, $endchegada, $qtdmax, $referenciaBipado, $enderecoSaidaBipado, $enderecoChegadaBipado, $idUsuario)
{
    try {
        $observacao = '';
        if ($referenciaBipado === 'N') {
            $observacao .= '| Referencia digitada ';
        }
        if ($enderecoSaidaBipado === 'N') {
            $observacao .= '| Endereco de saida digitado ';
        }
        if ($enderecoChegadaBipado === 'N') {
            $observacao .= '| Endereco de chegada digitado ';
        }
        $params = array($codemp, $referencia, $lote, $endsaida, $endchegada, $qtdmax, $observacao, $idUsuario);

        $tsql = "EXEC [sankhya].[AD_STP_TRANSF_SIMPLES_APP] ?, ?, ?, ?, ?, ?, ?, ?";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL.');
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (!isset($row['SUCCESS'])) {
            throw new Exception('Erro ao executar transferencia');
        }

        echo json_encode(['success' => 'ok']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
