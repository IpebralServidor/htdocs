<?php
// ============================================================
//  processa.php — Importação de Excel para AD_MIGRACAOPRECO
//  Requer: PhpSpreadsheet (composer require phpoffice/phpspreadsheet)
// ============================================================

// ----- Autoload do Composer -----
// Ajuste o caminho se o vendor estiver em outro lugar
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// ----- Conexão SQL Server -----
$serverName     = "10.0.0.229";
$uid            = "sankhya";
$pwd            = "tecsis";
$databaseName   = "SANKHYA_PROD";
$connectionInfo = [
    "UID"      => $uid,
    "PWD"      => $pwd,
    "Database" => $databaseName,
    "CharacterSet" => "UTF-8",
];

$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    die("<p style='color:red;font-family:Arial'>Erro ao conectar ao SQL Server:<br>" .
        print_r(sqlsrv_errors(), true) . "</p>");
}

// ----- Valida o upload -----
if (empty($_FILES['arquivo']['tmp_name']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
    die("<p style='color:red;font-family:Arial'>Nenhum arquivo recebido ou erro no upload.</p>");
}

$extensaoPermitida = ['xlsx', 'xls'];
$nomeOriginal      = $_FILES['arquivo']['name'];
$extensao          = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

if (!in_array($extensao, $extensaoPermitida)) {
    die("<p style='color:red;font-family:Arial'>Formato inválido. Envie um arquivo .xlsx ou .xls.</p>");
}

// ----- Lê a planilha -----
try {
    $spreadsheet = IOFactory::load($_FILES['arquivo']['tmp_name']);
} catch (\Exception $e) {
    die("<p style='color:red;font-family:Arial'>Erro ao ler o arquivo Excel: " .
        htmlspecialchars($e->getMessage()) . "</p>");
}

$sheet = $spreadsheet->getActiveSheet();
$rows  = $sheet->toArray(null, true, true, false); // índice numérico, a partir de 0

// ----- Zera a tabela -----
$sqlDelete = "DELETE FROM AD_MIGRACAOPRECO";
if (!sqlsrv_query($conn, $sqlDelete)) {
    die("<p style='color:red;font-family:Arial'>Erro ao limpar a tabela:<br>" .
        print_r(sqlsrv_errors(), true) . "</p>");
}

// ----- Importa as linhas (pula a primeira — cabeçalho) -----
$importados = 0;
$erros      = [];

foreach ($rows as $idx => $row) {
    if ($idx === 0) continue; // pula cabeçalho (linha 1 da planilha)

    // Colunas: A=0 B=1 C=2 D=3 E=4
    $codproparc  = isset($row[0]) ? trim((string)$row[0]) : '';
    $descrproparc= isset($row[1]) ? trim((string)$row[1]) : '';
    $preco       = isset($row[2]) ? trim((string)$row[2]) : '';
    $codparc     = isset($row[3]) ? trim((string)$row[3]) : '';
    $codbarra    = isset($row[4]) ? trim((string)$row[4]) : '';

    // Pula linhas completamente vazias
    if ($codproparc === '' && $descrproparc === '' && $preco === '') continue;

    // Usa parâmetros para evitar SQL Injection e problemas com aspas
    $sqlInsert = "INSERT INTO AD_MIGRACAOPRECO
                    (CODPROPARC, DESCPROPARC, PRECO, CODPARC, CODBARRA)
                  VALUES (?, ?, ?, ?, ?)";

    $params = [
        [$codproparc,   SQLSRV_PARAM_IN],
        [$descrproparc, SQLSRV_PARAM_IN],
        [$preco,        SQLSRV_PARAM_IN],
        [$codparc,      SQLSRV_PARAM_IN],
        [$codbarra,     SQLSRV_PARAM_IN],
    ];

    $result = sqlsrv_query($conn, $sqlInsert, $params);

    if ($result === false) {
        $erros[] = "Linha " . ($idx + 1) . " ($codproparc): " .
                   print_r(sqlsrv_errors(), true);
    } else {
        $importados++;
    }
}

// ----- Libera recursos -----
sqlsrv_close($conn);

// ============================================================
//  Saída de resultado
// ============================================================
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Resultado da Importação — Ipebral</title>
  <link rel="stylesheet" href="themes/css/bootstrap.min.css" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: Arial, Helvetica, sans-serif;
      background: #f4f5f7;
      color: #1a1a1a;
      min-height: 100vh;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 2.5rem 1rem;
    }
    .page-wrap { width: 100%; max-width: 620px; }

    .header {
      display: flex; align-items: center; gap: 16px;
      background: #fff; border: 1px solid #e2e4e8;
      border-radius: 12px; padding: 1.25rem 1.5rem;
      margin-bottom: 1.25rem;
    }
    .header img { height: 40px; display: block; }
    .header-title { font-size: 17px; font-weight: 700; color: #111; }
    .header-sub   { font-size: 12px; color: #666; }

    .result-card {
      background: #fff; border: 1px solid #e2e4e8;
      border-radius: 12px; padding: 1.5rem;
      margin-bottom: 1.25rem;
    }
    .result-success {
      display: flex; align-items: center; gap: 14px;
      padding: 1rem 1.25rem;
      background: #e8f5e9; border-radius: 8px;
      margin-bottom: 1rem;
    }
    .result-success-icon { font-size: 28px; }
    .result-success-count { font-size: 22px; font-weight: 700; color: #1b5e20; }
    .result-success-label { font-size: 13px; color: #2e7d32; }

    .error-list {
      background: #fff3e0; border: 1px solid #ffe0b2;
      border-radius: 8px; padding: 1rem 1.25rem;
    }
    .error-list h3 { font-size: 13px; font-weight: 700; color: #e65100; margin-bottom: 8px; }
    .error-list ul { margin: 0; padding-left: 18px; }
    .error-list li { font-size: 12px; color: #bf360c; margin-bottom: 4px; }

    .btn-back {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 10px 20px; border-radius: 8px;
      background: #1a1a1a; color: #fff;
      text-decoration: none; font-size: 14px; font-weight: 700;
      transition: opacity 0.15s;
    }
    .btn-back:hover { opacity: 0.82; color: #fff; text-decoration: none; }
  </style>
</head>
<body>
  <div class="page-wrap">

    <div class="header">
      <img src="themes/img/logo40.png" alt="Ipebral" />
      <div>
        <div class="header-title">Ipebral</div>
        <div class="header-sub">Resultado da importação</div>
      </div>
    </div>

    <div class="result-card">

      <div class="result-success">
        <span class="result-success-icon">&#10003;</span>
        <div>
          <div class="result-success-count"><?= $importados ?> <?= $importados === 1 ? 'linha importada' : 'linhas importadas' ?></div>
          <div class="result-success-label">Arquivo: <?= htmlspecialchars($nomeOriginal) ?></div>
        </div>
      </div>

      <?php if (!empty($erros)): ?>
      <div class="error-list">
        <h3>&#9888; <?= count($erros) ?> linha(s) com erro:</h3>
        <ul>
          <?php foreach ($erros as $erro): ?>
            <li><?= htmlspecialchars($erro) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

    </div>

    <a class="btn-back" href="index.php">&#8592; Voltar</a>

  </div>
</body>
</html>
