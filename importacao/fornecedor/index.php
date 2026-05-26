<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Importação de Tabela de Preço — Ipebral</title>
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

    .page-wrap {
      width: 100%;
      max-width: 620px;
    }

    /* HEADER */
    .header {
      display: flex;
      align-items: center;
      gap: 16px;
      background: #fff;
      border: 1px solid #e2e4e8;
      border-radius: 12px;
      padding: 1.25rem 1.5rem;
      margin-bottom: 1.25rem;
    }
    .header img {
      height: 40px;
      display: block;
    }
    .header-text { display: flex; flex-direction: column; gap: 2px; }
    .header-title { font-size: 17px; font-weight: 700; color: #111; }
    .header-sub { font-size: 12px; color: #666; }

    /* INFO */
    .info-card {
      background: #f0f2f5;
      border-radius: 12px;
      padding: 1rem 1.25rem;
      margin-bottom: 1.25rem;
    }
    .info-row {
      display: flex;
      gap: 10px;
      align-items: flex-start;
      margin-bottom: 10px;
    }
    .info-row:last-child { margin-bottom: 0; }
    .info-icon { font-size: 16px; color: #1a73e8; flex-shrink: 0; margin-top: 2px; }
    .info-row p { font-size: 13px; color: #555; line-height: 1.5; }
    .info-row p strong { color: #1a1a1a; }
    .fields-list { display: flex; gap: 6px; flex-wrap: wrap; margin-left: 26px; margin-top: 6px; }
    .field-chip {
      font-size: 11px;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 99px;
      background: #fff;
      color: #555;
      border: 1px solid #d0d3d8;
      font-family: 'Courier New', Courier, monospace;
    }

    /* DOWNLOAD */
    .download-card {
      background: #fff;
      border: 1px solid #e2e4e8;
      border-radius: 12px;
      padding: 1rem 1.25rem;
      margin-bottom: 1.25rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }
    .download-left { display: flex; align-items: center; gap: 12px; }
    .download-icon {
      width: 42px;
      height: 42px;
      border-radius: 8px;
      background: #e8f5e9;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-size: 22px;
      color: #2e7d32;
    }
    .download-label { font-size: 13px; font-weight: 700; color: #111; margin-bottom: 2px; }
    .download-hint { font-size: 12px; color: #777; }
    .btn-download {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      border-radius: 8px;
      background: #f0f2f5;
      border: 1px solid #d0d3d8;
      font-size: 13px;
      font-weight: 600;
      color: #1a1a1a;
      text-decoration: none;
      white-space: nowrap;
      transition: background 0.15s;
    }
    .btn-download:hover { background: #e2e4e8; color: #1a1a1a; text-decoration: none; }

    /* FORM CARD */
    .form-card {
      background: #fff;
      border: 1px solid #e2e4e8;
      border-radius: 12px;
      padding: 1.5rem;
    }
    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: #555;
      margin-bottom: 8px;
    }

    /* DROPZONE */
    .dropzone {
      border: 2px dashed #c5c8ce;
      border-radius: 8px;
      padding: 2rem;
      text-align: center;
      cursor: pointer;
      transition: border-color 0.15s, background 0.15s;
    }
    .dropzone:hover, .dropzone.active {
      border-color: #1a73e8;
      background: #e8f0fe;
    }
    .dropzone-icon { font-size: 36px; color: #999; display: block; margin-bottom: 10px; }
    .dropzone:hover .dropzone-icon, .dropzone.active .dropzone-icon { color: #1a73e8; }
    .dropzone p { font-size: 13px; color: #666; margin: 0; }
    .dropzone p span { color: #1a73e8; font-weight: 600; }
    .dropzone small { font-size: 12px; color: #999; display: block; margin-top: 4px; }

    /* FILE SELECTED */
    .file-selected {
      display: none;
      align-items: center;
      gap: 10px;
      padding: 10px 14px;
      background: #e8f0fe;
      border-radius: 8px;
      margin-top: 10px;
    }
    .file-selected.show { display: flex; }
    .file-selected-icon { font-size: 18px; color: #1a73e8; }
    .file-selected span { font-size: 13px; color: #1a73e8; font-weight: 600; }

    .divider { border: none; border-top: 1px solid #e2e4e8; margin: 1.25rem 0; }

    /* SUBMIT */
    .btn-submit {
      width: 100%;
      padding: 11px;
      font-size: 14px;
      font-weight: 700;
      border-radius: 8px;
      background: #1a1a1a;
      color: #fff;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: opacity 0.15s;
    }
    .btn-submit:hover { opacity: 0.82; }
  </style>
</head>
<body>
  <div class="page-wrap">

    <!-- Cabeçalho -->
    <div class="header">
      <img src="themes/img/logo40.png" alt="Ipebral" />
      <div class="header-text">
        <div class="header-title">Ipebral</div>
        <div class="header-sub">Upload de tabela de preço de fornecedor</div>
      </div>
    </div>

    <!-- Informações -->
    <div class="info-card">
      <div class="info-row">
        <span class="info-icon">&#9432;</span>
        <p>Esta rotina importa planilhas Excel para a tabela <strong>AD_MIGRACAOPRECO</strong> no banco de dados.</p>
      </div>
      <div class="info-row">
        <span class="info-icon">&#9783;</span>
        <p>A planilha deve conter os seguintes campos com cabeçalho na <strong>primeira linha</strong>. A importação começa da segunda linha:</p>
      </div>
      <div class="fields-list">
        <span class="field-chip">Cód. Forn.</span>
        <span class="field-chip">Descr. Forn.</span>
        <span class="field-chip">Preço</span>
        <span class="field-chip">Cód. Parc.</span>
        <span class="field-chip">Cód. Barra</span>
      </div>
    </div>

    <!-- Download do modelo -->
    <div class="download-card">
      <div class="download-left">
        <div class="download-icon">&#128196;</div>
        <div>
          <div class="download-label">Planilha modelo</div>
          <div class="download-hint">Baixe e preencha antes de importar</div>
        </div>
      </div>
      <!-- Altere o href abaixo pelo caminho real da planilha -->
      <a class="btn-download" href="modelo_importacao.xlsx" download>
        &#8595; Baixar modelo
      </a>
    </div>

    <!-- Formulário -->
    <div class="form-card">
      <form method="post" action="processa.php" enctype="multipart/form-data" id="upload-form">

        <label class="form-label" for="arquivo-input">Arquivo Excel</label>

        <div class="dropzone" id="dropzone" onclick="document.getElementById('arquivo-input').click()">
          <span class="dropzone-icon">&#9650;</span>
          <p>Arraste o arquivo aqui ou <span>clique para selecionar</span></p>
          <small>Formato aceito: .xlsx / .xls</small>
          <input type="file" id="arquivo-input" name="arquivo" accept=".xlsx,.xls" style="display:none;" />
        </div>

        <div class="file-selected" id="file-info">
          <span class="file-selected-icon">&#10003;</span>
          <span id="file-name"></span>
        </div>

        <hr class="divider" />

        <button type="submit" class="btn-submit">
          &#8679; Enviar arquivo
        </button>

      </form>
    </div>

  </div>

  <script>
    var input    = document.getElementById('arquivo-input');
    var dropzone = document.getElementById('dropzone');
    var fileInfo = document.getElementById('file-info');
    var fileName = document.getElementById('file-name');

    function mostrarArquivo(file) {
      fileName.textContent = file.name;
      fileInfo.classList.add('show');
      dropzone.classList.add('active');
    }

    // Seleção via clique normal
    input.addEventListener('change', function () {
      if (input.files.length > 0) {
        mostrarArquivo(input.files[0]);
      }
    });

    dropzone.addEventListener('dragover', function (e) {
      e.preventDefault();
      dropzone.classList.add('active');
    });

    dropzone.addEventListener('dragleave', function () {
      if (!input.files.length) dropzone.classList.remove('active');
    });

    // Drop: atribui o arquivo ao input nativo via DataTransfer
    // para que o PHP receba corretamente via $_FILES
    dropzone.addEventListener('drop', function (e) {
      e.preventDefault();
      var file = e.dataTransfer.files[0];
      if (file) {
        var dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files; // <-- isso faz o PHP enxergar o arquivo
        mostrarArquivo(file);
      }
    });

    document.getElementById('upload-form').addEventListener('submit', function (e) {
      if (!input.files.length) {
        e.preventDefault();
        alert('Selecione um arquivo XML antes de enviar.');
      }
    });
  </script>
</body>
</html>
