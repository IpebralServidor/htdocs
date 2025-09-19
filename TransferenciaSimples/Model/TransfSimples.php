<?php

function buscaInformacoesProduto($conn, $referencia)
{
    try {
        $params = array($referencia, $referencia);
        $tsql = "SELECT TIPCONTEST, ISNULL(IMAGEM, (SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000)) AS IMAGEM, DESCRPROD FROM TGFPRO INNER JOIN TGFBAR ON TGFPRO.CODPROD = TGFBAR.CODPROD WHERE REFERENCIA = ? OR CODBARRA = ?";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL 1.');
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (!isset($row['IMAGEM'])) {
            throw new Exception('Produto não encontrado.');
        }
        $response = [
            'success' => [
                'tipcontest' => $row['TIPCONTEST'],
                'imagem' => base64_encode($row['IMAGEM']),
                'descrprod' => mb_convert_encoding($row['DESCRPROD'], 'UTF-8', mb_detect_encoding($row['DESCRPROD'], 'UTF-8, ISO-8859-1', true))
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
        $params = array($referencia, $referencia, $codemp);
        $tsql = "SELECT CODLOCALPAD FROM TGFPEM INNER JOIN 
						TGFPRO ON TGFPEM.CODPROD = TGFPRO.CODPROD INNER JOIN
						TGFBAR ON TGFPRO.CODPROD = TGFBAR.CODPROD
                   WHERE (TGFPRO.REFERENCIA = ? OR TGFBAR.CODBARRA = ?)
                   AND CODEMP = ?
        ";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL 2 .');
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
        $params = array($codemp, $referencia, $endsaida, $lote);

        $tsql = "SELECT * FROM [AD_FNT_TRANSF_SIMPLES_PHP] (?,?,?,?)";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL 3.');
        }

        $rows = []; // Armazena todas as linhas retornadas
        $codempSet = []; // Para verificar duplicidade em CODEMP

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[] = $row; // Adiciona a linha ao array
            $codempSet[$row['CODEMP']] = true; // Usa o valor de CODEMP como chave
        }

        if (empty($rows)) {
            throw new Exception('Produto com reserva ou não encontrado neste endereço.');
        }

        // Verifica se há mais de um valor distinto em CODEMP
        if (count($codempSet) > 1) {
            throw new Exception('Local existe em mais de uma empresa. Favor escolher a empresa manualmente.');
        }

        // Recupera a única linha, pois temos apenas uma empresa
        $row = $rows[0];
        $response = [
            'success' => [
                'qtdlocal' => $row['QTDLOCAL'],
                'qtdmax' => $row['QTDMAX'],
                'codemp' => $row['CODEMP']
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
            throw new Exception('Erro ao executar a consulta SQL 4.');
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

function validaParametros($conn, $codemp, $referencia, $lote, $endsaida, $endchegada, $qtdneg, $qtdmax)
{
    try {
        $params = array($codemp, $referencia, $lote, $endsaida, $endchegada, $qtdneg, $qtdmax);

        $tsql = "SELECT * FROM [sankhya].[AD_FNT_VALIDA_PARAMETROS_TRANSF_SIMPLES_APP](?, ?, ?, ?, ?, ?, ?)";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            //throw new Exception('Erro ao executar a consulta SQL.');
            $errors = sqlsrv_errors();

            // Cria uma mensagem de erro detalhada
            $errorMessage = "Erro ao executar a consulta SQL 5: ";
            foreach ($errors as $error) {
                $errorMessage .= "Código: " . $error['code'] . ", Mensagem: " . $error['message'] . "; ";
            }

            // Lança a exceção com a mensagem detalhada
            throw new Exception($errorMessage);
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

function transferirProduto($conn, $codemp, $referencia, $lote, $endsaida, $endchegada, $qtdneg, $qtdmax, $referenciaBipado, $enderecoSaidaBipado, $enderecoChegadaBipado, $idUsuario)
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
        $params = array($codemp, $referencia, $lote, $endsaida, $endchegada, $qtdneg, $qtdmax, $observacao, $idUsuario);

        $tsql = "EXEC [sankhya].[AD_STP_TRANSF_SIMPLES_APP] ?, ?, ?, ?, ?, ?, ?, ?, ?";

        $stmt = sqlsrv_query($conn, $tsql, $params);

        if ($stmt === false) {
            throw new Exception('Erro ao executar a consulta SQL 6.');
            // $errors = sqlsrv_errors();

            // // Cria uma mensagem de erro detalhada
            // $errorMessage = "Erro ao executar a consulta SQL: ";
            // foreach ($errors as $error) {
            //     $errorMessage .= "Codigo: " . $error['code'] . ", Mensagem: " . $error['message'] . "; ";
            // }

            // // Lança a exceção com a mensagem detalhada
            // echo $errorMessage;
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
