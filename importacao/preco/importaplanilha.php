<?php

session_start();
set_time_limit(600);

include "../../conexaophp.php";
require '../../vendor/autoload.php';




use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
echo "PhpSpreadsheet instalado com sucesso!<br />";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Verificar se o arquivo foi enviado
    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == UPLOAD_ERR_OK) {
        $uploadFilePath = 'uploads/' . basename($_FILES['excelFile']['name']);
        move_uploaded_file($_FILES['excelFile']['tmp_name'], $uploadFilePath);

        //Ler o arquivo Excel
        $spreadsheet = IOFactory::load($uploadFilePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Ler as colunas desejadas
        $dados = [];

        //Varre as linhas para pegar as informações
        foreach ($sheet->getRowIterator(2) as $linha) { // Começar a partir da linha 2
            $cellIterator = $linha->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Incluir células vazias

            $linhaDados = [];
            foreach ($cellIterator as $celula) {
                $linhaDados[] = $celula->getValue(); // Adicionar o valor da célula ao array
            }

            // Adicionar as colunas desejadas (exemplo: A e B)
            $dados[] = [
                'REFERENCIA' => $linhaDados[0] ?? null, // Coluna A (índice 0)
                'FABRICANTE' => $linhaDados[1] ?? null,  // Coluna B (índice 1)
                'QUANTIDADE' => $linhaDados[2] ?? null,  // Coluna C (índice 2)
                'DESCRICAO' => $linhaDados[3] ?? null  // Coluna D (índice 3)
            ];
        }

        //Recebe as variáveis de sessão e grava o Parceiro como uma varíavel de sessão.
        $codParc = isset($_POST['codParc']) ? trim($_POST['codParc']) : '';
        $_SESSION['codParc'] = $codParc;
        $codUsuario = $_SESSION['idUsuario'];

        //Seleciona o último número de orçamento, para inserção nas tabela de cabeçalho e itens
        $sql = "SELECT ISNULL((SELECT MAX(NUORCAMENTO) FROM AD_IMPORTACAO_TELEMARKETING),0) + 1";
        $stmt = sqlsrv_query($conn, $sql);
        // Verifica se a consulta foi executada com sucesso
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }


        // Extrai o valor retornado do último número do orçamento, para a criação do mesmo.
        $nuorcamento = null; // Variável para armazenar o resultado
        if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
            $nuorcamento = $row[0];
        }

        echo $nuorcamento;
        $_SESSION['nuorcamento'] = $nuorcamento;

        //insere os itens, de acordo com o cursor.
        $sql = "EXEC AD_STP_CRIA_IMPORTACAO_TELEMARKETING ?, ?, ?, ?, ?, ?, ?";
        echo $sql;

        foreach ($dados as $linha) {
            $params = [$nuorcamento, $codUsuario, $codParc, $linha['REFERENCIA'], $linha['FABRICANTE'], $linha['DESCRICAO'], $linha['QUANTIDADE']];
            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
        }

        //Atualiza os preços e as cores das linhas (Feito em uma segunda procedure por questão de performance do Banco de Dados).
        $sql = "EXEC AD_STP_ATUALIZA_PRECO_IMPORTACAO_TELEMARKETING ?, ?";
        $params = [$nuorcamento, $codUsuario];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        echo "Dados inseridos com sucesso!";

        //Redireciona para a lista de itens
        header("Location: listaitens.php");


    } else {
        echo "Erro no upload.";
    }
}
?>
