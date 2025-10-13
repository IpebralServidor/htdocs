<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

session_start();
set_time_limit(600);

include "../../conexaophp.php";
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
//echo "PhpSpreadsheet instalado com sucesso! <br />";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Verificar se o arquivo foi enviado
    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == UPLOAD_ERR_OK) {
        $uploadFilePath = 'uploads/' . basename($_FILES['excelFile']['name']);
        move_uploaded_file($_FILES['excelFile']['tmp_name'], $uploadFilePath);
        
        $dataInicial = $_POST['dataInicial']; // Valor da data inicial selecionada
        $dataFinal = $_POST['dataFinal']; // Valor da data final selecionada

        // echo "$conta";

        //Ler o arquivo Excel
        $spreadsheet = IOFactory::load($uploadFilePath);

        $sheet = $spreadsheet->getSheetByName("Resumo do banco de horas");  // Nome exato da aba, conforme a imagem
        
        // Verificação de segurança: se a aba não existir, para o script
        if ($sheet === null) {
            die("Erro: Aba 'Resumo do banco horas' não encontrada na planilha!");
        }

        //$sheet = $spreadsheet->getSheetByName("Resumo do banco de horas"); // Nome da aba específica
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

            IF($linhaDados[7] != null || $linhaDados[7] != ''){      
            
                // Adicionar as colunas desejadas (exemplo: A e B)
                $dados[] = [
                    'CODIGO' => $linhaDados[0] ?? null, // Coluna A
                    'NOME' => $linhaDados[2] ?? null,  // Coluna C 
                    'TOTALBANCO' => $linhaDados[7] ?? null,  // Coluna H 
                ];
            }
        }

        $sql = "EXEC AD_STP_INSERE_BANCO_DE_HORAS ?, ?, ?, ?, ?";

        foreach ($dados as $linha) {
            $params = [$dataInicial, $dataFinal, $linha['CODIGO'], 
                       $linha['NOME'], $linha['TOTALBANCO']];
            //$teste =  implode(', ',$params);
            $stmt = sqlsrv_query($conn, $sql, $params);

            
            //echo $teste;

            

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
        }

        echo "Banco de Horas inseridos com sucesso!";


    } else {
        echo "Erro no upload.";
    }
}


?>
