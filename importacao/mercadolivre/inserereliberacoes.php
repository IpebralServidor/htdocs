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

            IF($linhaDados[2] != null || $linhaDados[2] != ''){      
                // Adicionar as colunas desejadas (exemplo: A e B)
                $dados[] = [
                    'DATALIBERACAO' => $linhaDados[0] ?? null,  // Coluna A
                    'REFERENCIAML' => $linhaDados[2] ?? null,  // Coluna C
                    'DESCRICAOSTATUS' => $linhaDados[4] ?? null,  // Coluna E
                    'VALORBRUTO' => $linhaDados[7] ?? null,  // Coluna H
                    'CUSTOENVIO' => $linhaDados[11] ?? null,  // Coluna L
                    'PACKID' => $linhaDados[30] ?? ''  // Coluna AE
                ];
            }
        }

        $sql = "EXEC AD_STP_INSERE_LIBERACOES_MERCADOLIVRE ?, ?, ?, ?, ?, ?, ?";

        foreach ($dados as $linha) {
            if($linha['REFERENCIAML'] != null && $linha['REFERENCIAML'] != ''){
                
                $params = [$linha['DATALIBERACAO'], $linha['REFERENCIAML'], $linha['DESCRICAOSTATUS'], 
                        $linha['VALORBRUTO'], $linha['CUSTOENVIO'], $linha['PACKID'] ?? '', 
                        $conta];
                //$teste =  implode(', ',$params);
                //echo $teste;
                $stmt = sqlsrv_query($conn, $sql, $params);

                
                //echo $teste;

                

                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
            }
        }
        
        echo "Liberações inseridos com sucesso!";

    } else {
        echo "Erro no upload.";
    }
}


?>
