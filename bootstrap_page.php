<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Genomics DB — Bootstrap Dashboard</title>

  <!-- Bootstrap 5.3 (adapted from startbootstrap.com/template/sb-admin) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- HTMX для DB-запиту -->
  <script src="https://unpkg.com/htmx.org@1.9.12" defer></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* ── Кастомна тема поверх Bootstrap ── */
    :root {
      --bs-body-bg: #0d1117;
      --bs-body-color: #c9d1d9;
      --sidebar-bg: #161b22;
      --sidebar-width: 260px;
      --accent: #238636;
      --accent-blue: #1f6feb;
      --border: #30363d;
      --card-bg: #161b22;
    }

    body {
      background: var(--bs-body-bg);
      color: var(--bs-body-color);
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
    }

    /* ── Sidebar (адаптовано з SB Admin 2) ── */
    #sidebar {
      width: var(--sidebar-width);
      min-height: 100vh;
      background: var(--sidebar-bg);
      border-right: 1px solid var(--border);
      position: fixed; top: 0; left: 0; z-index: 100;
      display: flex; flex-direction: column;
      transition: transform 0.3s;
    }
    .sidebar-brand {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid var(--border);
      font-family: 'JetBrains Mono', monospace;
      font-size: 0.9rem;
      font-weight: 600;
      color: #fff;
      text-decoration: none;
      display: flex; align-items: center; gap: 0.6rem;
    }
    .sidebar-brand .brand-icon { color: var(--accent); font-size: 1.1rem; }
    .sidebar-nav { padding: 1rem 0; flex: 1; }
    .sidebar-heading {
      font-size: 0.65rem;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: #8b949e;
      padding: 0.75rem 1.5rem 0.25rem;
    }
    .sidebar-nav .nav-link {
      padding: 0.5rem 1.5rem;
      color: #8b949e;
      font-size: 0.85rem;
      display: flex; align-items: center; gap: 0.6rem;
      border-left: 2px solid transparent;
      transition: all 0.15s;
    }
    .sidebar-nav .nav-link:hover,
    .sidebar-nav .nav-link.active {
      color: #c9d1d9;
      background: rgba(255,255,255,0.04);
      border-left-color: var(--accent-blue);
    }
    .sidebar-nav .nav-link i { font-size: 0.95rem; width: 16px; text-align: center; }

    /* ── Main content ── */
    #main-content {
      margin-left: var(--sidebar-width);
      min-height: 100vh;
    }
    @media (max-width: 768px) {
      #sidebar { transform: translateX(-100%); }
      #sidebar.show { transform: translateX(0); }
      #main-content { margin-left: 0; }
    }

    /* ── Topbar ── */
    .topbar {
      height: 56px;
      background: var(--sidebar-bg);
      border-bottom: 1px solid var(--border);
      padding: 0 1.5rem;
      display: flex; align-items: center; gap: 1rem;
      position: sticky; top: 0; z-index: 99;
    }
    .topbar-title {
      font-size: 0.9rem;
      font-weight: 600;
      color: #c9d1d9;
    }
    .topbar-badge {
      font-family: 'JetBrains Mono', monospace;
      font-size: 0.65rem;
      background: rgba(31,111,235,0.15);
      border: 1px solid rgba(31,111,235,0.3);
      color: #79c0ff;
      padding: 0.15rem 0.5rem;
      border-radius: 20px;
    }

    /* ── Cards ── */
    .stat-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 1.25rem 1.5rem;
    }
    .stat-card-label {
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: #8b949e;
      margin-bottom: 0.4rem;
    }
    .stat-card-value {
      font-family: 'JetBrains Mono', monospace;
      font-size: 1.9rem;
      font-weight: 600;
      color: #fff;
      line-height: 1;
    }
    .stat-card-sub { font-size: 0.75rem; color: #8b949e; margin-top: 0.35rem; }
    .stat-icon {
      font-size: 1.4rem;
      opacity: 0.6;
    }

    /* ── SNP Table ── */
    .db-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 6px;
      overflow: hidden;
    }
    .db-card-header {
      padding: 0.9rem 1.25rem;
      border-bottom: 1px solid var(--border);
      display: flex; justify-content: space-between; align-items: center;
      background: rgba(255,255,255,0.02);
      gap: 1rem; flex-wrap: wrap;
    }
    .db-card-title {
      font-family: 'JetBrains Mono', monospace;
      font-size: 0.78rem;
      color: #79c0ff;
    }
    .db-search {
      display: flex; gap: 0.5rem;
    }
    .db-search input {
      background: var(--bs-body-bg);
      border: 1px solid var(--border);
      border-radius: 4px;
      padding: 0.3rem 0.7rem;
      color: #c9d1d9;
      font-family: 'JetBrains Mono', monospace;
      font-size: 0.78rem;
      outline: none;
      width: 200px;
      transition: border-color 0.2s;
    }
    .db-search input:focus { border-color: var(--accent-blue); }

    .snp-tbl { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .snp-tbl th {
      padding: 0.6rem 1rem;
      font-size: 0.67rem;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: #8b949e;
      border-bottom: 1px solid var(--border);
      font-weight: 500;
      background: rgba(255,255,255,0.02);
    }
    .snp-tbl td {
      padding: 0.55rem 1rem;
      border-bottom: 1px solid rgba(48,54,61,0.7);
      color: #c9d1d9;
      font-family: 'JetBrains Mono', monospace;
      font-size: 0.78rem;
    }
    .snp-tbl tbody tr:hover { background: rgba(255,255,255,0.03); }
    .badge-consequence {
      font-size: 0.65rem;
      padding: 0.2rem 0.5rem;
      border-radius: 3px;
      font-family: 'JetBrains Mono', monospace;
    }

    /* ── Progress bars ── */
    .maf-bar {
      height: 4px;
      background: #21262d;
      border-radius: 2px;
      overflow: hidden;
      margin-top: 3px;
      width: 80px;
    }
    .maf-bar-fill {
      height: 100%;
      background: linear-gradient(90deg, #1f6feb, #79c0ff);
      border-radius: 2px;
    }

    /* ── HTMX indicator ── */
    .htmx-indicator { display: none; }
    .htmx-request .htmx-indicator { display: inline-flex; }
    .spinner-dot {
      display: inline-flex; gap: 4px; align-items: center;
      font-size: 0.75rem; color: #79c0ff;
    }
    .spinner-dot::before {
      content: '';
      width: 8px; height: 8px;
      border-radius: 50%;
      border: 2px solid #79c0ff;
      border-top-color: transparent;
      animation: spin 0.8s linear infinite;
      display: inline-block;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Toast ── */
    .toast-container { z-index: 1100; }

    /* ── Back link ── */
    .back-link {
      display: inline-flex; align-items: center; gap: 0.4rem;
      font-size: 0.8rem;
      color: #8b949e;
      text-decoration: none;
      transition: color 0.2s;
    }
    .back-link:hover { color: #c9d1d9; }

    /* ── Chart legend ── */
    .legend-dot { width: 10px; height: 10px; border-radius: 2px; display: inline-block; }
  </style>
</head>
<body>

<!-- SIDEBAR (адаптовано з SB Admin 2 / startbootstrap.com) -->
<div id="sidebar">
  <a class="sidebar-brand" href="index.php">
    <i class="bi bi-hexagon-fill brand-icon"></i>
    GenomicsDB
  </a>
  <nav class="sidebar-nav">
    <div class="sidebar-heading">Навігація</div>
    <a class="nav-link active" href="bootstrap_page.php">
      <i class="bi bi-grid-1x2"></i> Dashboard
    </a>
    <a class="nav-link" href="index.php#mutations">
      <i class="bi bi-dna"></i> Мутації
    </a>
    <a class="nav-link" href="index.php#snp">
      <i class="bi bi-bar-chart-steps"></i> SNP & GWAS
    </a>
    <a class="nav-link" href="index.php#db-table">
      <i class="bi bi-table"></i> HTMX таблиця
    </a>
    <a class="nav-link" href="index.php#contact">
      <i class="bi bi-envelope"></i> Контакт
    </a>

    <div class="sidebar-heading mt-3">База даних</div>
    <a class="nav-link" href="#snp-section">
      <i class="bi bi-database"></i> SNP варіанти
    </a>
    <a class="nav-link" href="#stats-section">
      <i class="bi bi-pie-chart"></i> Статистика
    </a>

    <div class="sidebar-heading mt-3">Зовнішні ресурси</div>
    <a class="nav-link" href="https://www.ebi.ac.uk/gwas/" target="_blank">
      <i class="bi bi-box-arrow-up-right"></i> GWAS Catalog
    </a>
    <a class="nav-link" href="https://www.ncbi.nlm.nih.gov/snp/" target="_blank">
      <i class="bi bi-box-arrow-up-right"></i> dbSNP
    </a>
  </nav>
  <div style="padding:1rem 1.5rem;border-top:1px solid var(--border)">
    <a href="index.php" class="back-link">
      <i class="bi bi-arrow-left"></i> Повернутись на головну
    </a>
  </div>
</div>

<!-- MAIN CONTENT -->
<div id="main-content">

  <!-- TOPBAR -->
  <div class="topbar">
    <button class="btn btn-sm d-md-none" style="color:#8b949e;background:none;border:1px solid var(--border)"
      onclick="document.getElementById('sidebar').classList.toggle('show')">
      <i class="bi bi-list"></i>
    </button>
    <span class="topbar-title">SNP Dashboard</span>
    <span class="topbar-badge">Bootstrap 5.3 + HTMX + MySQL</span>
    <span class="ms-auto" style="font-size:0.75rem;color:#8b949e;font-family:'JetBrains Mono',monospace">
      genomics_db · snp_variants
    </span>
  </div>

  <div class="p-4">

    <!-- ALERT: Bootstrap component -->
    <div class="alert alert-info d-flex align-items-center gap-2 mb-4"
         style="background:rgba(31,111,235,0.1);border-color:rgba(31,111,235,0.3);color:#79c0ff;border-radius:6px"
         role="alert">
      <i class="bi bi-info-circle-fill fs-5"></i>
      <div>
        Це сторінка, адаптована зі <strong>StartBootstrap SB Admin</strong> (startbootstrap.com).
        Нижче — запит до <strong>MySQL бази даних</strong> через HTMX і PHP API.
        <a href="https://startbootstrap.com/template/sb-admin" target="_blank"
           style="color:#79c0ff">Оригінальний шаблон →</a>
      </div>
      <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
    </div>

    <!-- STAT CARDS (завдання 3: Bootstrap grid + cards) -->
    <div id="stats-section" class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="stat-card-label">SNP у таблиці</div>
              <div class="stat-card-value" id="stat-total">—</div>
              <div class="stat-card-sub">snp_variants</div>
            </div>
            <i class="bi bi-database stat-icon" style="color:#79c0ff"></i>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="stat-card-label">Missense</div>
              <div class="stat-card-value" id="stat-missense">—</div>
              <div class="stat-card-sub">найчастіший тип</div>
            </div>
            <i class="bi bi-exclamation-triangle stat-icon" style="color:#f0883e"></i>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="stat-card-label">Nonsense</div>
              <div class="stat-card-value" id="stat-nonsense">—</div>
              <div class="stat-card-sub">стоп-кодони</div>
            </div>
            <i class="bi bi-x-octagon stat-icon" style="color:#f85149"></i>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="stat-card-label">Унікальних генів</div>
              <div class="stat-card-value" id="stat-genes">—</div>
              <div class="stat-card-sub">різних локусів</div>
            </div>
            <i class="bi bi-diagram-3 stat-icon" style="color:#3fb950"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- SNP TABLE: запит до БД (завдання 4) -->
    <div id="snp-section" class="db-card mb-4">
      <div class="db-card-header">
        <div>
          <div class="db-card-title"><i class="bi bi-database me-1"></i>SELECT * FROM snp_variants</div>
          <div style="font-size:0.72rem;color:#8b949e;margin-top:2px">
            Дані з MySQL · Запит через HTMX → api.php → PDO
          </div>
        </div>
        <div class="db-search">
          <input type="search" id="bs-search" name="search"
            placeholder="Пошук SNP, гену…"
            hx-get="api.php?action=htmx_snp_table"
            hx-target="#bs-snp-tbody"
            hx-trigger="keyup changed delay:400ms, search"
            hx-indicator="#bs-spin"
          />
          <button class="btn btn-sm"
            style="background:var(--accent-blue);color:#fff;border:none;border-radius:4px;font-size:0.75rem"
            hx-get="api.php?action=htmx_snp_table"
            hx-target="#bs-snp-tbody"
            hx-include="#bs-search"
            hx-indicator="#bs-spin">
            <i class="bi bi-search"></i>
          </button>
          <span id="bs-spin" class="htmx-indicator">
            <span class="spinner-dot">Запит…</span>
          </span>
        </div>
      </div>

      <div style="overflow-x:auto">
        <table class="snp-tbl">
          <thead>
            <tr>
              <th>rs ID</th>
              <th>Ген</th>
              <th>Хром.</th>
              <th>Ref → Alt</th>
              <th>MAF</th>
              <th>Тип мутації</th>
              <th>Захворювання</th>
            </tr>
          </thead>
          <tbody id="bs-snp-tbody"
            hx-get="api.php?action=htmx_snp_table"
            hx-trigger="load"
            hx-indicator="#bs-spin">
            <tr>
              <td colspan="7" style="text-align:center;padding:2rem;color:#8b949e">
                <span class="spinner-dot">Завантаження з БД…</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Bootstrap Tabs: додаткові Bootstrap-елементи -->
    <div class="db-card mb-4">
      <div class="db-card-header">
        <div class="db-card-title"><i class="bi bi-info-circle me-1"></i>Довідка</div>
      </div>
      <div class="p-3">
        <ul class="nav nav-tabs" id="infoTabs" role="tablist"
          style="border-color:var(--border)">
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-schema"
              style="color:#8b949e;background:none;border-color:transparent">
              Схема БД
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-api"
              style="color:#8b949e;background:none;border-color:transparent">
              API endpoints
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-htmx"
              style="color:#8b949e;background:none;border-color:transparent">
              Як працює HTMX
            </button>
          </li>
        </ul>
        <div class="tab-content pt-3" style="color:#8b949e;font-size:0.85rem;line-height:1.8">
          <div class="tab-pane fade show active" id="tab-schema">
            <pre style="background:var(--bs-body-bg);border:1px solid var(--border);border-radius:4px;padding:1rem;font-size:0.78rem;color:#c9d1d9">
CREATE TABLE snp_variants (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  rs_id       VARCHAR(20)  NOT NULL UNIQUE,
  gene        VARCHAR(50)  NOT NULL,
  chromosome  TINYINT UNSIGNED NOT NULL,
  position    BIGINT UNSIGNED  NOT NULL,
  ref_allele  CHAR(1) NOT NULL,
  alt_allele  CHAR(1) NOT NULL,
  maf         DECIMAL(6,4),
  consequence ENUM('missense','nonsense','silent',...),
  disease     VARCHAR(255)
);</pre>
          </div>
          <div class="tab-pane fade" id="tab-api">
            <table style="width:100%;font-family:'JetBrains Mono',monospace;font-size:0.78rem">
              <tr style="border-bottom:1px solid var(--border)">
                <td style="padding:0.4rem 0.5rem;color:#79c0ff">GET api.php?action=snp_list</td>
                <td style="padding:0.4rem 0.5rem">JSON-список всіх SNP</td>
              </tr>
              <tr style="border-bottom:1px solid var(--border)">
                <td style="padding:0.4rem 0.5rem;color:#79c0ff">GET api.php?action=snp_search&q=ATM</td>
                <td style="padding:0.4rem 0.5rem">JSON-пошук за геном/rs_id/хворобою</td>
              </tr>
              <tr style="border-bottom:1px solid var(--border)">
                <td style="padding:0.4rem 0.5rem;color:#79c0ff">GET api.php?action=htmx_snp_table</td>
                <td style="padding:0.4rem 0.5rem">HTML-фрагмент &lt;tr&gt; для HTMX</td>
              </tr>
              <tr>
                <td style="padding:0.4rem 0.5rem;color:#79c0ff">POST api.php?action=contact</td>
                <td style="padding:0.4rem 0.5rem">Збереження контактної форми</td>
              </tr>
            </table>
          </div>
          <div class="tab-pane fade" id="tab-htmx">
            <p>HTMX дозволяє робити AJAX-запити прямо через HTML-атрибути:</p>
            <pre style="background:var(--bs-body-bg);border:1px solid var(--border);border-radius:4px;padding:1rem;font-size:0.78rem;color:#c9d1d9">&lt;tbody
  hx-get="api.php?action=htmx_snp_table"
  hx-trigger="load"&gt;
&lt;/tbody&gt;

&lt;input
  hx-get="api.php?action=htmx_snp_table"
  hx-target="#tbody"
  hx-trigger="keyup delay:400ms"
  name="search"
/&gt;</pre>
            <p>Сервер (api.php) виконує SQL і повертає готовий HTML — без JS-фреймворків.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap Toast trigger -->
    <div class="d-flex gap-2 mb-4">
      <button class="btn btn-sm" onclick="showToast()"
        style="background:var(--accent);color:#fff;border:none;border-radius:4px;font-size:0.78rem">
        <i class="bi bi-bell me-1"></i> Тест Bootstrap Toast
      </button>
      <a href="index.php" class="btn btn-sm"
        style="background:transparent;color:#8b949e;border:1px solid var(--border);border-radius:4px;font-size:0.78rem">
        <i class="bi bi-arrow-left me-1"></i> Головна сторінка
      </a>
    </div>

    <div style="font-size:0.72rem;color:#8b949e;font-family:'JetBrains Mono',monospace">
      Адаптовано з шаблону
      <a href="https://startbootstrap.com/template/sb-admin" target="_blank" style="color:#79c0ff">
        SB Admin · startbootstrap.com
      </a>
      · Bootstrap 5.3 · HTMX 1.9 · PHP 8 · MySQL
    </div>

  </div><!-- /p-4 -->
</div><!-- /main-content -->

<!-- Bootstrap Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="liveToast" class="toast align-items-center border-0"
       style="background:#161b22;border:1px solid var(--border)!important;color:#c9d1d9"
       role="alert">
    <div class="d-flex">
      <div class="toast-body">
        <i class="bi bi-check-circle-fill text-success me-2"></i>
        Bootstrap Toast працює! Дані з БД завантажено.
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<script>
/* Завантаження статистики з API (AJAX) */
fetch('api.php?action=snp_list')
  .then(r => r.json())
  .then(({ data }) => {
    if (!data) return;
    document.getElementById('stat-total').textContent    = data.length;
    document.getElementById('stat-missense').textContent = data.filter(d => d.consequence === 'missense').length;
    document.getElementById('stat-nonsense').textContent = data.filter(d => d.consequence === 'nonsense').length;
    const genes = new Set(data.map(d => d.gene));
    document.getElementById('stat-genes').textContent   = genes.size;
  })
  .catch(() => {
    ['stat-total','stat-missense','stat-nonsense','stat-genes']
      .forEach(id => document.getElementById(id).textContent = 'N/A');
  });

/* Bootstrap Toast */
function showToast() {
  const toastEl = document.getElementById('liveToast');
  new bootstrap.Toast(toastEl, { delay: 3500 }).show();
}

/* Active tab styling */
document.querySelectorAll('[data-bs-toggle="tab"]').forEach(btn => {
  btn.addEventListener('shown.bs.tab', () => {
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(b => {
      b.style.color = '#8b949e';
      b.style.borderColor = 'transparent';
      b.style.background = 'none';
    });
    btn.style.color = '#c9d1d9';
    btn.style.borderColor = 'var(--border) var(--border) var(--card-bg)';
    btn.style.background = 'var(--card-bg)';
  });
});
</script>
</body>
</html>
