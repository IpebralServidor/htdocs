<?php

//Valida a Conexão e o Login
require '../../vendor/autoload.php';
include "../../conexaophp.php";
require_once '../../App/auth.php';

//Inclui bibliotecas que serão usadas para manipulação de planilhas
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Limpa qualquer saída anterior, para não inserir dados que não fazem parte do orçamento posicionado
if (ob_get_contents()) {
    ob_end_clean();
}

//Armazena as variáveis 
$nuorcamento = $_POST['nuorcamento'];

// Criar uma nova planilha
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Consulta ao banco de dados
$sql = "SELECT REFERENCIAFABRICANTE, 
               FABRICANTE, 
               ISNULL(REFERENCIAINTERNA, '') AS REFERENCIAINTERNA, 
               ISNULL(PRECOVENDA, 0) AS PRECOVENDA,
               ISNULL(QUANTIDADE,0) AS QUANTIDADE
        FROM AD_IMPORTACAO_TELEMARKETING
        WHERE NUORCAMENTO = ?
        ORDER BY ORDEM";
$params = [$nuorcamento];
$stmt = sqlsrv_query($conn, $sql, $params);
// Verifica se a consulta foi executada com sucesso
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}



// Adicionar cabeçalhos na planilha
$sheet->setCellValue('A1', 'Referência');
$sheet->setCellValue('B1', 'Fabricante');
$sheet->setCellValue('C1', 'Referência Interna');
$sheet->setCellValue('D1', 'Quantidade');
$sheet->setCellValue('E1', 'Preço Unit.');
$sheet->setCellValue('F1', 'Preço Total');


// Preencher os dados
$row = 2; // Iniciar na segunda linha após o cabeçalho
while ($data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

    $sheet->setCellValue('A' . $row, $data['REFERENCIAFABRICANTE']);
    $sheet->setCellValue('B' . $row, $data['FABRICANTE']);
    $sheet->setCellValue('C' . $row, $data['REFERENCIAINTERNA']);
    $sheet->setCellValue('D' . $row, $data['QUANTIDADE']);
    $sheet->setCellValue('E' . $row, $data['PRECOVENDA']);
    $sheet->setCellValue('F' . $row, ($data['PRECOVENDA'] * $data['QUANTIDADE']));
    $row++;

}

// Liberar recursos fazendo a execução no banco de dados
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);


// Gerar o nome do arquivo, gera um nome baseado na hora, para que caso baixe mais de uma, não fique com o mesmo nome.
$filename = "planilha_atualizada_" . time() . ".xlsx";

// Caminho completo para salvar o arquivo
$filepath = __DIR__ . "/temp/" . $filename;

//Cria a planilha e salva na pasta
$writer = new Xlsx($spreadsheet);
$writer->save($filepath);


// Definindo o cabeçalho Content-Type para JSON
header('Content-Type: application/json');

//Retorna o caminho para usar na função JavaScript que faz o Download.
echo "/importacao/preco/temp/$filename";

exit;
?>