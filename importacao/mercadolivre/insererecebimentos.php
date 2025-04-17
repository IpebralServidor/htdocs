<?php

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
        
        $conta = $_POST['conta']; // Valor da conta selecionada

        // echo "$conta";

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

            IF($linhaDados[10] != null || $linhaDados[10] != ''){      
            
                // Adicionar as colunas desejadas (exemplo: A e B)
                $dados[] = [
                    'DATACOMPRA' => $linhaDados[0] ?? null, // Coluna A
                    'DATALIBERACAO' => $linhaDados[2] ?? null,  // Coluna C 
                    'REFERENCIAML' => $linhaDados[10] ?? null,  // Coluna K 
                    'SKUPRODUTO' => $linhaDados[11] ?? null,  // Coluna L 
                    'STATUSOPERACAO' => $linhaDados[13] ?? null,  // Coluna N 
                    'VALORPRODUTO' => $linhaDados[16] ?? null,  // Coluna Q 
                    'TARIFAMERCLIVRE' => $linhaDados[17] ?? null,  // Coluna R
                    'TARIFAMKTPLACE' => $linhaDados[18] ?? null,  // Coluna S
                    'VALORFRETE' => $linhaDados[19] ?? null,  // Coluna T
                    'REFERENCIAML2' => $linhaDados[29] ?? null,  // Coluna AD
                    'STATUSFRETE' => $linhaDados[35] ?? null  // Coluna AJ
                ];
            }
        }

        $sql = "EXEC AD_STP_INSERE_VENDAS_MERCADOLIVRE ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";

        foreach ($dados as $linha) {
            $params = [$linha['DATACOMPRA'], $linha['DATALIBERACAO'], $linha['REFERENCIAML'], 
                       $linha['SKUPRODUTO'], $linha['STATUSOPERACAO'], $linha['VALORPRODUTO'], 
                       $linha['TARIFAMERCLIVRE'], $linha['TARIFAMKTPLACE'], $linha['VALORFRETE'], 
                       $linha['REFERENCIAML2'], $linha['STATUSFRETE'], $conta];
            //$teste =  implode(', ',$params);
            $stmt = sqlsrv_query($conn, $sql, $params);

            
            //echo $teste;

            

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
        }

        echo "Recebimentos inseridos com sucesso!";


    } else {
        echo "Erro no upload.";
    }
}


?>
