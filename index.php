<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Мутації та SNP-варіації | Генетична різноманітність</title>

  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- HTMX -->
  <script src="https://unpkg.com/htmx.org@1.9.12" defer></script>
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

  <link rel="stylesheet" href="style.css" />
  <style>
    /* ── Simulator styles (inline for self-containment) ── */
    .sim-wrap {
      background: var(--bg2);
      border: 1px solid var(--border);
      border-radius: 2px;
      padding: 2rem;
      margin-top: 2rem;
    }
    .sim-title {
      font-family: var(--mono);
      font-size: 0.72rem;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 1.5rem;
    }
    .sim-controls {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      margin-bottom: 1.5rem;
    }
    .sim-field {
      display: flex;
      flex-direction: column;
      gap: 0.3rem;
    }
    .sim-field label {
      font-family: var(--mono);
      font-size: 0.68rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.1em;
    }
    .sim-field input {
      background: var(--bg3);
      border: 1px solid var(--border);
      border-radius: 2px;
      padding: 0.5rem 0.75rem;
      color: var(--text);
      font-family: var(--mono);
      font-size: 0.82rem;
      outline: none;
      transition: border-color 0.2s;
    }
    .sim-field input:focus { border-color: var(--accent); }
    .sim-field.wide input { width: 340px; max-width: 100%; }
    .sim-run-btn {
      align-self: flex-end;
      background: var(--accent);
      color: #fff;
      border: none;
      border-radius: 2px;
      padding: 0.55rem 1.5rem;
      font-family: var(--mono);
      font-size: 0.78rem;
      letter-spacing: 0.05em;
      cursor: pointer;
      text-transform: uppercase;
      transition: background 0.2s;
    }
    .sim-run-btn:hover { background: #2563eb; }
    .sim-output { min-height: 80px; }
    .sim-result { display: flex; flex-direction: column; gap: 0.5rem; }
    .sim-row-label {
      font-family: var(--mono);
      font-size: 0.68rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.08em;
      margin-top: 0.5rem;
    }
    .sim-seq { display: flex; flex-wrap: wrap; gap: 2px; }
    .sim-divider {
      text-align: center;
      color: var(--text-muted);
      font-family: var(--mono);
      font-size: 0.8rem;
      padding: 0.5rem 0;
    }
    .sim-change-notice {
      background: rgba(139,92,246,0.1);
      border: 1px solid rgba(139,92,246,0.3);
      color: #c4b5fd;
      padding: 0.6rem 1rem;
      border-radius: 2px;
      font-family: var(--mono);
      font-size: 0.8rem;
      margin-top: 0.5rem;
    }
    .sim-silent-notice {
      background: rgba(16,185,129,0.1);
      border: 1px solid rgba(16,185,129,0.3);
      color: #6ee7b7;
      padding: 0.6rem 1rem;
      border-radius: 2px;
      font-family: var(--mono);
      font-size: 0.8rem;
      margin-top: 0.5rem;
    }
    .sim-error { color: var(--red); font-family: var(--mono); font-size: 0.82rem; }

    /* ── Tabs ── */
    .tabs-wrap { margin-top: 2.5rem; }
    .tab-buttons { display: flex; gap: 0; border-bottom: 1px solid var(--border); margin-bottom: 1.5rem; }
    .tab-btn {
      background: none;
      border: none;
      border-bottom: 2px solid transparent;
      color: var(--text-muted);
      font-family: var(--mono);
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      padding: 0.75rem 1.25rem;
      cursor: pointer;
      transition: color 0.2s, border-color 0.2s;
      margin-bottom: -1px;
    }
    .tab-btn.active { color: var(--accent2); border-bottom-color: var(--accent); }
    .tab-btn:hover { color: var(--accent3); }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; }

    /* ── Nav active ── */
    .nav-links a.active { color: var(--accent2); }

    /* ── Copy button ── */
    .copy-btn {
      background: rgba(59,130,246,0.1);
      border: 1px solid rgba(59,130,246,0.2);
      color: var(--accent3);
      border-radius: 2px;
      padding: 0.25rem 0.75rem;
      font-family: var(--mono);
      font-size: 0.68rem;
      cursor: pointer;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      transition: background 0.2s;
    }
    .copy-btn:hover { background: rgba(59,130,246,0.2); }

    /* ── SNP badges ── */
    .snp-badge-row { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1.5rem; }
    .snp-badge {
      padding: 0.4rem 1rem;
      border-radius: 2px;
      font-family: var(--mono);
      font-size: 0.75rem;
      letter-spacing: 0.05em;
      border: 1px solid;
      cursor: default;
    }
    .snp-badge-gwas { color: var(--gold2); border-color: rgba(245,158,11,0.3); background: rgba(245,158,11,0.08); }
    .snp-badge-radio { color: #f472b6; border-color: rgba(244,114,182,0.3); background: rgba(244,114,182,0.08); }
    .snp-badge-pharm { color: #34d399; border-color: rgba(52,211,153,0.3); background: rgba(52,211,153,0.08); }
    .snp-badge-cancer { color: #fb923c; border-color: rgba(251,146,60,0.3); background: rgba(251,146,60,0.08); }

    /* ── Hero canvas ── */
    #hero-canvas {
      position: absolute;
      inset: 0; width: 100%; height: 100%;
      opacity: 0.6;
      pointer-events: none;
    }

    /* ── Big stat numbers ── */
    .big-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 1.5rem;
      margin: 3rem 0;
    }
    .big-stat {
      text-align: center;
      padding: 1.5rem;
      background: var(--bg3);
      border: 1px solid var(--border);
      border-radius: 2px;
    }
    .big-stat-num {
      font-family: var(--serif);
      font-size: 2.5rem;
      font-weight: 900;
      color: var(--accent2);
      line-height: 1;
      margin-bottom: 0.4rem;
    }
    .big-stat-label {
      font-family: var(--mono);
      font-size: 0.68rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.1em;
    }

    /* ── Sickle cell visual ── */
    .sickle-grid {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      gap: 1.5rem;
      align-items: center;
      margin: 2rem 0;
      padding: 2rem;
      background: var(--bg3);
      border: 1px solid var(--border);
      border-radius: 2px;
    }
    .sickle-col { text-align: center; }
    .sickle-label {
      font-family: var(--mono);
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--text-muted);
      margin-bottom: 0.75rem;
    }
    .sickle-codon {
      display: inline-flex;
      gap: 2px;
      margin-bottom: 0.75rem;
    }
    .sickle-arrow {
      font-size: 1.5rem;
      color: var(--text-muted);
    }
    .aa-result {
      font-family: var(--serif);
      font-size: 1.1rem;
      font-weight: 600;
    }
    .aa-result.normal { color: var(--green); }
    .aa-result.mutant { color: var(--red); }
    .aa-effect {
      font-family: var(--mono);
      font-size: 0.72rem;
      color: var(--text-muted);
      margin-top: 0.35rem;
    }

    /* ══════════════════════════════════════════════════════
       МЕДІА-ЗАПИТИ — адаптивний блок статистики (big-stats)
       Застосовано до .big-stats на секції мутацій
    ══════════════════════════════════════════════════════ */
    .big-stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
      margin: 3rem 0;
    }
    /* Планшет: 2 колонки */
    @media (max-width: 900px) {
      .big-stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
      }
      .big-stat-num { font-size: 2rem; }
      .snp-layout { grid-template-columns: 1fr !important; }
    }
    /* Мобільний: 1 колонка */
    @media (max-width: 560px) {
      .big-stats {
        grid-template-columns: 1fr;
        gap: 0.75rem;
      }
      .big-stat { padding: 1rem; }
      .big-stat-num { font-size: 1.75rem; }
      .mutation-grid { grid-template-columns: 1fr !important; }
      .sim-controls { flex-direction: column; }
      .sim-field.wide input { width: 100%; }
      nav { flex-wrap: wrap; gap: 0.5rem; }
      .nav-links { display: none; }
      #rainbow-btn .btn-label { display: none; }
    }

    /* ══════════════════════════════════════════════════════
       HTMX SNP TABLE
    ══════════════════════════════════════════════════════ */
    .htmx-section {
      background: var(--bg2);
      border: 1px solid var(--border);
      border-radius: 2px;
      padding: 2rem;
      margin-top: 3rem;
    }
    .htmx-header {
      display: flex; justify-content: space-between; align-items: center;
      flex-wrap: wrap; gap: 1rem;
      margin-bottom: 1.5rem;
    }
    .htmx-title {
      font-family: var(--mono);
      font-size: 0.72rem;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: var(--accent);
    }
    .htmx-search {
      display: flex; gap: 0.5rem;
    }
    .htmx-search input {
      background: var(--bg3);
      border: 1px solid var(--border);
      border-radius: 2px;
      padding: 0.4rem 0.75rem;
      color: var(--text);
      font-family: var(--mono);
      font-size: 0.78rem;
      outline: none;
      width: 220px;
      transition: border-color 0.2s;
    }
    .htmx-search input:focus { border-color: var(--accent); }
    .htmx-search button {
      background: var(--accent);
      border: none;
      border-radius: 2px;
      color: #fff;
      padding: 0.4rem 0.9rem;
      font-family: var(--mono);
      font-size: 0.72rem;
      cursor: pointer;
      transition: background 0.2s;
    }
    .htmx-search button:hover { background: #2563eb; }
    .snp-table {
      width: 100%; border-collapse: collapse;
      font-family: var(--mono); font-size: 0.8rem;
    }
    .snp-table th {
      text-align: left;
      padding: 0.5rem 0.75rem;
      border-bottom: 1px solid var(--border-strong);
      color: var(--text-muted);
      font-size: 0.68rem;
      letter-spacing: 0.1em;
      text-transform: uppercase;
    }
    .snp-table td {
      padding: 0.55rem 0.75rem;
      border-bottom: 1px solid var(--border);
      color: var(--text-dim);
      vertical-align: middle;
    }
    .snp-table tbody tr:hover { background: rgba(59,130,246,0.04); }
    .htmx-indicator {
      display: none; color: var(--accent2);
      font-family: var(--mono); font-size: 0.75rem;
      padding: 0.5rem 0.75rem;
    }
    .htmx-request .htmx-indicator { display: inline; }
    .htmx-error { color: var(--red) !important; text-align:center; padding: 1rem; }

    /* ══════════════════════════════════════════════════════
       AJAX CONTACT FORM
    ══════════════════════════════════════════════════════ */
    .ajax-form-section {
      background: var(--bg3);
      border: 1px solid var(--border);
      border-radius: 2px;
      padding: 2rem 2.5rem;
      margin-top: 3rem;
    }
    .ajax-form-title {
      font-family: var(--serif);
      font-size: 1.5rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 0.5rem;
    }
    .ajax-form-sub {
      font-family: var(--mono);
      font-size: 0.72rem;
      color: var(--text-muted);
      letter-spacing: 0.08em;
      margin-bottom: 1.75rem;
    }
    .ajax-form { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .ajax-form { grid-template-columns: 1fr; } }
    .ajax-form .full { grid-column: 1 / -1; }
    .ajax-field { display: flex; flex-direction: column; gap: 0.3rem; }
    .ajax-field label {
      font-family: var(--mono);
      font-size: 0.68rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.1em;
    }
    .ajax-field input,
    .ajax-field textarea,
    .ajax-field select {
      background: var(--bg2);
      border: 1px solid var(--border);
      border-radius: 2px;
      padding: 0.55rem 0.85rem;
      color: var(--text);
      font-family: var(--mono);
      font-size: 0.82rem;
      outline: none;
      transition: border-color 0.2s;
      width: 100%;
    }
    .ajax-field input:focus,
    .ajax-field textarea:focus { border-color: var(--accent); }
    .ajax-field textarea { resize: vertical; min-height: 100px; }
    .ajax-submit {
      background: var(--accent);
      color: #fff;
      border: none;
      border-radius: 2px;
      padding: 0.65rem 2rem;
      font-family: var(--mono);
      font-size: 0.78rem;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      cursor: pointer;
      transition: background 0.2s;
      margin-top: 0.5rem;
    }
    .ajax-submit:hover { background: #2563eb; }
    .ajax-submit:disabled { opacity: 0.5; cursor: not-allowed; }
    #ajax-status {
      margin-top: 1rem;
      padding: 0.75rem 1rem;
      border-radius: 2px;
      font-family: var(--mono);
      font-size: 0.82rem;
      display: none;
    }
    #ajax-status.success {
      background: rgba(16,185,129,0.1);
      border: 1px solid rgba(16,185,129,0.3);
      color: #6ee7b7;
      display: block;
    }
    #ajax-status.error {
      background: rgba(239,68,68,0.1);
      border: 1px solid rgba(239,68,68,0.3);
      color: #fca5a5;
      display: block;
    }

    /* ══════════════════════════════════════════════════════
       BOOTSTRAP OVERRIDES — інтеграція в темну тему
    ══════════════════════════════════════════════════════ */
    .bs-section {
      padding: 2rem 0;
    }
    .bs-accordion .accordion-item {
      background: var(--bg2) !important;
      border: 1px solid var(--border) !important;
      border-radius: 2px !important;
      margin-bottom: 0.5rem;
    }
    .bs-accordion .accordion-button {
      background: var(--bg3) !important;
      color: var(--text) !important;
      font-family: var(--mono);
      font-size: 0.85rem;
      border-radius: 2px !important;
      box-shadow: none !important;
    }
    .bs-accordion .accordion-button:not(.collapsed) {
      color: var(--accent2) !important;
      background: var(--bg2) !important;
    }
    .bs-accordion .accordion-button::after {
      filter: invert(1) hue-rotate(200deg);
    }
    .bs-accordion .accordion-body {
      background: var(--bg2) !important;
      color: var(--text-dim);
      font-size: 0.9rem;
      line-height: 1.8;
      border-top: 1px solid var(--border);
    }
    .bs-badge { font-family: var(--mono); font-size: 0.7rem; letter-spacing: 0.05em; }
    .bs-alert {
      background: rgba(59,130,246,0.08) !important;
      border: 1px solid rgba(59,130,246,0.25) !important;
      color: var(--accent3) !important;
      border-radius: 2px !important;
      font-size: 0.88rem;
    }
  </style>
</head>
<body>
<div class="grid-bg"></div>

<!-- NAV -->
<nav>
  <span class="nav-logo">⬡ GenomicsDB</span>

  <ul class="nav-links" id="nav-links-list">
    <li><a href="#mutations">Мутації</a></li>
    <li><a href="#example">Приклад</a></li>
    <li><a href="#snp">SNP & GWAS</a></li>
    <li><a href="#pseudocode">Псевдокод</a></li>
    <li><a href="#research">Дослідження</a></li>
    <li><a href="#db-table">БД · HTMX</a></li>
    <li><a href="#contact">Контакт</a></li>
  </ul>

  <button id="rainbow-btn" title="Увімкнути/вимкнути кольорові роздільники">
    ◈ Роздільники
  </button>

  <button id="burger-btn" aria-label="Меню">☰</button>
</nav>

<style>
  /* ── Кнопка роздільників ── */
  #rainbow-btn {
    background: transparent;
    border: 1px solid #334155;
    border-radius: 3px;
    color: #64748b;
    padding: 0.35rem 0.8rem;
    font-family: monospace;
    font-size: 0.75rem;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
    letter-spacing: 0.05em;
  }
  #rainbow-btn.rb-active {
    border-color: #22c55e;
    color: #22c55e;
    background: rgba(34,197,94,0.08);
  }

  /* ── Бургер ── */
  #burger-btn {
    display: none;
    background: transparent;
    border: 1px solid #334155;
    border-radius: 3px;
    color: #64748b;
    padding: 0.3rem 0.65rem;
    font-size: 1rem;
    cursor: pointer;
    flex-shrink: 0;
  }

  /* ── Медіа-запити навбару ── */
  @media (max-width: 700px) {
    nav { flex-wrap: wrap; gap: 0.5rem; padding: 0.75rem 1rem; }
    #burger-btn { display: block; margin-left: auto; }
    #rainbow-btn { order: 2; }
    .nav-links {
      display: none;
      width: 100%;
      flex-direction: column;
      background: #0b1018;
      border: 1px solid #1e293b;
      border-radius: 3px;
      padding: 0.4rem 0;
      order: 99;
    }
    .nav-links.open { display: flex !important; }
    .nav-links li { width: 100%; }
    .nav-links a { display: block; padding: 0.5rem 1rem; font-size: 0.82rem; }
  }

  /* ══════════════════════════════════════
     СТРІЧКА-РОЗДІЛЬНИК
     Логіка: JS вставляє <div class="rb-sep">
     між секціями. Коли кнопка увімкнена —
     додає клас rb-on → стрічка видима.
     Коли вимкнена — rb-on знімається →
     стрічка прихована (height:0).
  ══════════════════════════════════════ */

  /* Базовий стан — невидима лінія між секціями */
  .rb-sep {
    display: block;
    width: 100%;
    height: 0px;
    overflow: hidden;
    transition: height 0.3s ease;
    position: relative;
  }

  /* Увімкнений стан — стрічка видима */
  .rb-sep.rb-on {
    height: 6px;
    overflow: visible;
  }

  /* Переливний градієнт */
  .rb-sep.rb-on::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 6px;
    background: linear-gradient(90deg,
      #ef4444 0%,
      #f97316 14%,
      #eab308 28%,
      #22c55e 42%,
      #3b82f6 57%,
      #8b5cf6 71%,
      #ec4899 85%,
      #ef4444 100%
    );
    background-size: 200% 100%;
    animation: rb 2s linear infinite;
  }

  /* Світіння під стрічкою */
  .rb-sep.rb-on::after {
    content: '';
    position: absolute;
    top: -4px; left: 0; right: 0; height: 14px;
    background: linear-gradient(90deg,
      #ef4444, #f97316, #eab308,
      #22c55e, #3b82f6, #8b5cf6,
      #ec4899, #ef4444
    );
    background-size: 200% 100%;
    animation: rb 2s linear infinite;
    filter: blur(7px);
    opacity: 0.5;
  }

  @keyframes rb {
    0%   { background-position: 0% 50%; }
    100% { background-position: 200% 50%; }
  }
</style>

<!-- HERO -->
<div class="hero">
  <canvas id="hero-canvas"></canvas>
  <div class="hero-glow"></div>

  <p class="hero-label">Молекулярна генетика · Радіобіологія</p>
  <h1>Мутації та<br/><em>SNP-варіації</em></h1>
  <p class="hero-subtitle">
    Генетичні мутації — рушій еволюції та джерело хвороб.
    Від одного зміненого нуклеотиду до перебудови всього організму —
    дізнайтесь, як SNP змінюють медицину.
  </p>
  <div class="dna-strand" aria-hidden="true">
    <span class="base"></span><span class="base"></span>
    <span class="base"></span><span class="base"></span>
    <span class="base"></span><span class="base"></span>
    <span class="base"></span><span class="base"></span>
  </div>
</div>

<main>

<!-- ══════════════════════════════════════════
     SECTION 1 · MUTATIONS
════════════════════════════════════════════ -->
<section id="mutations">
<div class="container">
  <div class="reveal">
    <p class="section-tag">01 — Основи</p>
    <h2 class="section-title">Типи мутацій</h2>
    <p class="section-lead">
      Мутація — це стабільна зміна нуклеотидної послідовності ДНК.
      Залежно від масштабу та характеру зміни розрізняють три фундаментальних класи
      точкових мутацій, що впливають на кодування білків.
    </p>
  </div>

  <div class="big-stats reveal">
    <div class="big-stat">
      <div class="big-stat-num">~10⁹</div>
      <div class="big-stat-label">Нуклеотидів у геномі людини</div>
    </div>
    <div class="big-stat">
      <div class="big-stat-num">~4–5</div>
      <div class="big-stat-label">Нових мутацій на покоління на Мбп</div>
    </div>
    <div class="big-stat">
      <div class="big-stat-num">99.9%</div>
      <div class="big-stat-label">Ідентичності геномів двох людей</div>
    </div>
    <div class="big-stat">
      <div class="big-stat-num">~20K</div>
      <div class="big-stat-label">Генів у геномі людини</div>
    </div>
  </div>

  <div class="mutation-grid">

    <!-- SUBSTITUTION -->
    <div class="mutation-card subst reveal">
      <span class="card-type">Substitution</span>
      <h3 class="card-title">Заміна нуклеотиду</h3>
      <p class="card-body">
        Один нуклеотид замінюється іншим. Може бути
        <strong>транзицією</strong> (пурин↔пурин або піримідин↔піримідин)
        або <strong>трансверсією</strong> (пурин↔піримідин).
        Залежно від наслідків — missense, nonsense або silent-мутація.
      </p>
      <div class="seq-visual">
        <div class="seq-row">
          <span class="seq-label">Оригінал</span>
          <div class="seq-bases">
            <span class="base-char base-A">A</span>
            <span class="base-char base-T">T</span>
            <span class="base-char base-G">G</span>
            <span class="base-char base-A">A</span>
            <span class="base-char base-G">G</span>
            <span class="base-char base-T">T</span>
          </div>
        </div>
        <div class="seq-row">
          <span class="seq-label">Мутант</span>
          <div class="seq-bases">
            <span class="base-char base-A">A</span>
            <span class="base-char base-T">T</span>
            <span class="base-char base-G">G</span>
            <span class="base-char base-C base-mut">C</span>
            <span class="base-char base-G">G</span>
            <span class="base-char base-T">T</span>
          </div>
        </div>
      </div>
      <div class="info-box" style="margin-top:1rem">
        <span class="info-box-icon">💡</span>
        <div class="info-box-text">
          <strong>Приклад:</strong> Мутація GAG→GTG у гені β-глобіну (HBB)
          призводить до серпоподібноклітинної анемії. Замінюється лише один нуклеотид — Glu→Val.
        </div>
      </div>
    </div>

    <!-- INSERTION -->
    <div class="mutation-card insert reveal">
      <span class="card-type">Insertion</span>
      <h3 class="card-title">Вставка</h3>
      <p class="card-body">
        Один або кілька нуклеотидів вставляються в послідовність ДНК.
        Якщо кількість вставлених нуклеотидів не кратна трьом,
        відбувається <strong>зсув рамки зчитування</strong> (frameshift),
        що змінює всі наступні кодони.
      </p>
      <div class="seq-visual">
        <div class="seq-row">
          <span class="seq-label">Оригінал</span>
          <div class="seq-bases">
            <span class="base-char base-A">A</span>
            <span class="base-char base-T">T</span>
            <span class="base-char base-G">G</span>
            <span class="base-char base-A">A</span>
            <span class="base-char base-G">G</span>
            <span class="base-char base-T">T</span>
          </div>
        </div>
        <div class="seq-row">
          <span class="seq-label">Мутант</span>
          <div class="seq-bases">
            <span class="base-char base-A">A</span>
            <span class="base-char base-T">T</span>
            <span class="base-char base-G base-ins">C</span>
            <span class="base-char base-G">G</span>
            <span class="base-char base-A">A</span>
            <span class="base-char base-G">G</span>
            <span class="base-char base-T">T</span>
          </div>
        </div>
      </div>
      <div class="info-box" style="margin-top:1rem">
        <span class="info-box-icon">💡</span>
        <div class="info-box-text">
          <strong>Приклад:</strong> Вставка у гені BRCA1 спричиняє
          зсув рамки зчитування, що призводить до синтезу нефункціонального білка
          і значно підвищує ризик раку молочної залози.
        </div>
      </div>
    </div>

    <!-- DELETION -->
    <div class="mutation-card delet reveal">
      <span class="card-type">Deletion</span>
      <h3 class="card-title">Делеція</h3>
      <p class="card-body">
        Один або кілька нуклеотидів видаляються з послідовності.
        Мала делеція (frameshift) руйнує рамку зчитування.
        Велика делеція може охоплювати цілий ген чи кілька генів.
      </p>
      <div class="seq-visual">
        <div class="seq-row">
          <span class="seq-label">Оригінал</span>
          <div class="seq-bases">
            <span class="base-char base-A">A</span>
            <span class="base-char base-T">T</span>
            <span class="base-char base-G">G</span>
            <span class="base-char base-A">A</span>
            <span class="base-char base-G">G</span>
            <span class="base-char base-T">T</span>
          </div>
        </div>
        <div class="seq-row">
          <span class="seq-label">Мутант</span>
          <div class="seq-bases">
            <span class="base-char base-A">A</span>
            <span class="base-char base-T">T</span>
            <span class="base-char base-G base-del">G</span>
            <span class="base-char base-A">A</span>
            <span class="base-char base-G">G</span>
            <span class="base-char base-T">T</span>
          </div>
        </div>
      </div>
      <div class="info-box" style="margin-top:1rem">
        <span class="info-box-icon">💡</span>
        <div class="info-box-text">
          <strong>Приклад:</strong> Делеція 3 нуклеотидів у гені CFTR (ΔF508)
          — найпоширеніша причина муковісцидозу. Відсутність одного
          фенілаланіну (Phe508) руйнує фолдинг білка.
        </div>
      </div>
    </div>

  </div><!-- /mutation-grid -->
</div>
</section>

<!-- ══════════════════════════════════════════
     SECTION 2 · ONE MUTATION — ONE AMINO ACID
════════════════════════════════════════════ -->
<section id="example">
<div class="container">
  <div class="reveal">
    <p class="section-tag">02 — Реальний приклад</p>
    <h2 class="section-title">Одна мутація — інша амінокислота</h2>
    <p class="section-lead">
      Серпоподібноклітинна анемія — класичний приклад того, як
      заміна одного нуклеотиду змінює одну амінокислоту в білку і викликає
      тяжке захворювання. Цей приклад є фундаментом молекулярної медицини.
    </p>
  </div>

  <div class="sickle-grid reveal">
    <div class="sickle-col">
      <div class="sickle-label">Нормальний ген HBB</div>
      <div class="sickle-codon">
        <span class="base-char base-G">G</span>
        <span class="base-char base-A">A</span>
        <span class="base-char base-G">G</span>
      </div>
      <div class="aa-result normal">Глутамінова кислота (Glu)</div>
      <div class="aa-effect">Кодон GAG → Glu (6-а позиція β-ланцюга)</div>
      <div class="aa-effect">Гемоглобін A — нормальна функція</div>
    </div>

    <div class="sickle-arrow">→</div>

    <div class="sickle-col">
      <div class="sickle-label">Мутантний ген HBB (rs334)</div>
      <div class="sickle-codon">
        <span class="base-char base-G">G</span>
        <span class="base-char base-T base-mut">T</span>
        <span class="base-char base-G">G</span>
      </div>
      <div class="aa-result mutant">Валін (Val)</div>
      <div class="aa-effect">Кодон GTG → Val (A→T заміна)</div>
      <div class="aa-effect">Гемоглобін S → агрегація → серпи</div>
    </div>
  </div>

  <div class="info-box reveal">
    <span class="info-box-icon">🔬</span>
    <div class="info-box-text">
      SNP rs334 (chr11:5246696, A>T) — один із найбільш вивчених варіантів у людському геномі.
      <strong>Гетерозиготи</strong> мають відносний захист від малярії (балансуючий відбір).
      <strong>Гомозиготи (HbSS)</strong> страждають від тяжкої анемії, вазооклюзійних криз та ішемії органів.
      Частота алеля в Африці — до 15–20% у деяких популяціях.
      <br/><em>Джерело: Pauling L. et al. Science, 1949; Ingram V.M. Nature, 1957.</em>
    </div>
  </div>

  <!-- Interactive Simulator -->
  <div class="divider reveal">ІНТЕРАКТИВНИЙ СИМУЛЯТОР</div>
  <div class="sim-wrap reveal">
    <p class="sim-title">🧬 Симулятор точкової мутації</p>
    <div class="sim-controls">
      <div class="sim-field wide">
        <label>ДНК-послідовність (тільки A, T, G, C)</label>
        <input id="sim-input" type="text" placeholder="напр. ATGGAGACA..." spellcheck="false" />
      </div>
      <div class="sim-field">
        <label>Позиція (0-based)</label>
        <input id="sim-pos" type="number" value="4" min="0" style="width:80px" />
      </div>
      <div class="sim-field">
        <label>Новий нуклеотид</label>
        <input id="sim-base" type="text" value="T" maxlength="1" style="width:60px" />
      </div>
      <button id="sim-run" class="sim-run-btn">Симулювати</button>
    </div>
    <div id="sim-output" class="sim-output"></div>
  </div>

</div>
</section>

<!-- ══════════════════════════════════════════
     SECTION 3 · SNP & GWAS
════════════════════════════════════════════ -->
<section id="snp">
<div class="container">
  <div class="reveal">
    <p class="section-tag">03 — Персоналізована медицина</p>
    <h2 class="section-title">SNP як основа GWAS та персоналізованої медицини</h2>
    <p class="section-lead">
      Single Nucleotide Polymorphism (SNP, «снп») — позиція в геномі,
      де у різних людей зустрічаються різні нуклеотиди з частотою ≥1%.
      SNP є найпоширенішим видом генетичної варіації і ключовим інструментом
      для пошуку генів захворювань.
    </p>
    <div class="snp-badge-row">
      <span class="snp-badge snp-badge-gwas">GWAS</span>
      <span class="snp-badge snp-badge-radio">Радіочутливість</span>
      <span class="snp-badge snp-badge-pharm">Фармакогеноміка</span>
      <span class="snp-badge snp-badge-cancer">Онкологія</span>
    </div>
  </div>

  <div class="snp-layout">
    <div class="snp-info reveal">
      <h3>Що таке GWAS?</h3>
      <p>
        Genome-Wide Association Study (GWAS) — метод сканування геному
        мільйонів SNP одночасно у великих когортах хворих та здорових осіб
        для виявлення варіантів, асоційованих із захворюваннями або ознаками.
      </p>
      <p>
        Поріг значущості: <strong>p&nbsp;&lt;&nbsp;5×10⁻⁸</strong> (з урахуванням
        множинних порівнянь по всьому геному). На сьогодні GWAS-каталог NHGRI-EBI
        містить понад 500&nbsp;000 асоціацій для тисяч ознак.
      </p>
      <p>
        В радіобіології GWAS допомагає виявляти SNP у генах репарації ДНК
        (ATM, BRCA1/2, XRCC1, RAD51), що визначають <em>індивідуальну радіочутливість</em>
        пацієнтів при променевій терапії.
      </p>
      <div class="snp-stat-row">
        <div class="snp-stat">
          <span class="snp-stat-label">SNP у геномі людини</span>
          <span class="snp-stat-value">> 600 млн</span>
        </div>
        <div class="snp-stat">
          <span class="snp-stat-label">Зареєстровано у dbSNP (2024)</span>
          <span class="snp-stat-value">> 1.1 млрд варіантів</span>
        </div>
        <div class="snp-stat">
          <span class="snp-stat-label">Загальних SNP (MAF ≥ 1%)</span>
          <span class="snp-stat-value">~10–15 млн</span>
        </div>
        <div class="snp-stat">
          <span class="snp-stat-label">Записів у GWAS Catalog (2024)</span>
          <span class="snp-stat-value">> 500 000</span>
        </div>
      </div>
    </div>

    <div class="codon-demo reveal">
      <h4>SNP у кодуванні: три сценарії</h4>
      <div class="codon-flow">

        <div class="codon-step">
          <span class="codon-step-label">Вихідна мРНК</span>
          <div class="codon-seq">
            <div class="codon-block">
              <span class="base-char base-A">A</span>
              <span class="base-char base-U" style="background:rgba(245,158,11,0.2);color:#fcd34d">U</span>
              <span class="base-char base-G">G</span>
            </div>
            <div class="codon-block">
              <span class="base-char base-G">G</span>
              <span class="base-char base-A">A</span>
              <span class="base-char base-G">G</span>
            </div>
            <div class="codon-block">
              <span class="base-char base-A">A</span>
              <span class="base-char base-C" style="background:rgba(59,130,246,0.2);color:#93c5fd">C</span>
              <span class="base-char base-A">A</span>
            </div>
          </div>
          <div class="aa-row" style="margin-top:0.4rem">
            <span class="aa-chip">Met</span>
            <span class="aa-chip">Glu</span>
            <span class="aa-chip">Thr</span>
          </div>
        </div>

        <div class="step-arrow">▼ SNP у 2-му кодоні (GAG → GTG): Missense</div>

        <div class="codon-step">
          <span class="codon-step-label">Missense SNP — інша амінокислота</span>
          <div class="codon-seq">
            <div class="codon-block">
              <span class="base-char base-A">A</span>
              <span class="base-char base-U" style="background:rgba(245,158,11,0.2);color:#fcd34d">U</span>
              <span class="base-char base-G">G</span>
            </div>
            <div class="codon-block">
              <span class="base-char base-G">G</span>
              <span class="base-char base-T base-mut">U</span>
              <span class="base-char base-G">G</span>
            </div>
            <div class="codon-block">
              <span class="base-char base-A">A</span>
              <span class="base-char base-C" style="background:rgba(59,130,246,0.2);color:#93c5fd">C</span>
              <span class="base-char base-A">A</span>
            </div>
          </div>
          <div class="aa-row" style="margin-top:0.4rem">
            <span class="aa-chip">Met</span>
            <span class="aa-chip changed">Val ⚠</span>
            <span class="aa-chip">Thr</span>
          </div>
        </div>

        <div class="step-arrow">▼ SNP у 2-му кодоні (GAG → GAA): Silent</div>

        <div class="codon-step">
          <span class="codon-step-label">Silent SNP — амінокислота не змінюється</span>
          <div class="codon-seq">
            <div class="codon-block">
              <span class="base-char base-A">A</span>
              <span class="base-char base-U" style="background:rgba(245,158,11,0.2);color:#fcd34d">U</span>
              <span class="base-char base-G">G</span>
            </div>
            <div class="codon-block">
              <span class="base-char base-G">G</span>
              <span class="base-char base-A">A</span>
              <span class="base-char base-A base-ins">A</span>
            </div>
            <div class="codon-block">
              <span class="base-char base-A">A</span>
              <span class="base-char base-C" style="background:rgba(59,130,246,0.2);color:#93c5fd">C</span>
              <span class="base-char base-A">A</span>
            </div>
          </div>
          <div class="aa-row" style="margin-top:0.4rem">
            <span class="aa-chip">Met</span>
            <span class="aa-chip">Glu ✓</span>
            <span class="aa-chip">Thr</span>
          </div>
        </div>

        <div class="step-arrow">▼ SNP у 2-му кодоні (GAG → UAG): Nonsense</div>

        <div class="codon-step">
          <span class="codon-step-label">Nonsense SNP — передчасний стоп-кодон</span>
          <div class="codon-seq">
            <div class="codon-block">
              <span class="base-char base-A">A</span>
              <span class="base-char base-U" style="background:rgba(245,158,11,0.2);color:#fcd34d">U</span>
              <span class="base-char base-G">G</span>
            </div>
            <div class="codon-block" style="outline:1px solid rgba(239,68,68,0.5)">
              <span class="base-char base-U" style="background:rgba(239,68,68,0.35);color:#fca5a5">U</span>
              <span class="base-char base-A">A</span>
              <span class="base-char base-G">G</span>
            </div>
            <div class="codon-block" style="opacity:0.3">
              <span class="base-char base-A">A</span>
              <span class="base-char base-C" style="background:rgba(59,130,246,0.2);color:#93c5fd">C</span>
              <span class="base-char base-A">A</span>
            </div>
          </div>
          <div class="aa-row" style="margin-top:0.4rem">
            <span class="aa-chip">Met</span>
            <span class="aa-chip" style="background:rgba(239,68,68,0.2);border-color:rgba(239,68,68,0.4);color:#fca5a5">STOP ■</span>
            <span class="aa-chip" style="opacity:0.25;text-decoration:line-through">Thr</span>
          </div>
          <div style="font-family:var(--mono);font-size:0.72rem;color:#fca5a5;margin-top:0.5rem;padding:0.4rem 0.75rem;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:2px;">
            ✕ Трансляція зупиняється — синтезується скорочений, нефункціональний білок (truncation)
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Manhattan Plot -->
  <div class="manhattan-wrap reveal">
    <p class="manhattan-title">Manhattan Plot — симуляція GWAS результатів (радіочутливість)</p>
    <canvas id="manhattan" class="manhattan-chart"></canvas>
    <p style="font-family:var(--mono);font-size:0.68rem;color:var(--text-muted);margin-top:0.75rem;">
      Червона лінія: поріг genome-wide significance (p&nbsp;=&nbsp;5×10⁻⁸).
      Червоні точки: значущі локуси. Дані симульовані для ілюстрації.
    </p>
  </div>

</div>
</section>

<!-- ══════════════════════════════════════════
     SECTION 4 · PSEUDOCODE
════════════════════════════════════════════ -->
<section id="pseudocode">
<div class="container">
  <div class="reveal">
    <p class="section-tag">04 — Алгоритми</p>
    <h2 class="section-title">Псевдокод аналізу мутацій</h2>
    <p class="section-lead">
      Нижче наведено навчальні алгоритми для ключових задач
      геномного аналізу: пошуку SNP, трансляції та класифікації мутацій.
    </p>
  </div>

  <div class="tabs-wrap reveal">
    <div class="tab-buttons">
      <button class="tab-btn active" data-tab="tab-snp-detect">Пошук SNP</button>
      <button class="tab-btn" data-tab="tab-translate">Трансляція ДНК</button>
      <button class="tab-btn" data-tab="tab-classify">Класифікація</button>
      <button class="tab-btn" data-tab="tab-gwas">GWAS χ²-тест</button>
    </div>

    <!-- TAB 1: SNP Detection -->
    <div id="tab-snp-detect" class="tab-pane active">
      <div class="code-block">
        <div class="code-header">
          <span class="code-filename">snp_detection.pseudo</span>
          <div style="display:flex;gap:0.75rem;align-items:center">
            <span class="code-lang">Pseudocode</span>
            <button class="copy-btn">Копіювати</button>
          </div>
        </div>
        <div class="code-body"><pre>
<cm>// ── SNP Detection Algorithm ────────────────────────────────
// Знаходить однонуклеотидні поліморфізми між двома
// вирівняними геномними послідовностями</cm>

<kw>FUNCTION</kw> <fn>detect_SNPs</fn>(reference: <type>DNA_Sequence</type>, sample: <type>DNA_Sequence</type>) → <type>List[SNP]</type>:

    <cm>// Перевірка довжини (послідовності мають бути вирівняні)</cm>
    <kw>IF</kw> length(reference) ≠ length(sample):
        <kw>RAISE</kw> AlignmentError(<str>"Sequences must be aligned"</str>)
    
    snp_list ← <kw>empty list</kw>
    
    <kw>FOR</kw> position <kw>FROM</kw> <num>0</num> <kw>TO</kw> length(reference) - <num>1</num>:
        ref_base   ← reference[position]
        query_base ← sample[position]
        
        <cm>// Пропускаємо гепи (вирівнювання)</cm>
        <kw>IF</kw> ref_base = <str>'-'</str> <kw>OR</kw> query_base = <str>'-'</str>:
            <kw>CONTINUE</kw>
        
        <kw>IF</kw> ref_base ≠ query_base:
            snp ← <kw>new</kw> SNP {
                position   : position,
                ref_allele : ref_base,
                alt_allele : query_base,
                type       : <fn>classify_substitution</fn>(ref_base, query_base)
            }
            <fn>append</fn>(snp_list, snp)
    
    <kw>RETURN</kw> snp_list


<kw>FUNCTION</kw> <fn>classify_substitution</fn>(ref: <type>Char</type>, alt: <type>Char</type>) → <type>String</type>:

    purines     ← {<str>'A'</str>, <str>'G'</str>}
    pyrimidines ← {<str>'C'</str>, <str>'T'</str>}

    <kw>IF</kw> ref ∈ purines <kw>AND</kw> alt ∈ purines:
        <kw>RETURN</kw> <str>"transition (Ts)"</str>       <cm>// A↔G</cm>
    <kw>ELSE IF</kw> ref ∈ pyrimidines <kw>AND</kw> alt ∈ pyrimidines:
        <kw>RETURN</kw> <str>"transition (Ts)"</str>       <cm>// C↔T</cm>
    <kw>ELSE</kw>:
        <kw>RETURN</kw> <str>"transversion (Tv)"</str>     <cm>// пурин↔піримідин</cm>


<cm>// ── Використання ────────────────────────────────────────────</cm>
ref    ← <str>"ATGGAGACACCTCCTGCTATG"</str>
sample ← <str>"ATGGTGACACCTCCTGCTATG"</str>   <cm>// позиція 4: A→T</cm>

snps ← <fn>detect_SNPs</fn>(ref, sample)

<kw>FOR EACH</kw> snp <kw>IN</kw> snps:
    <fn>PRINT</fn> <str>f"pos {snp.position}: {snp.ref_allele}→{snp.alt_allele} [{snp.type}]"</str>
<cm>// Виведе: pos 4: A→T [transition (Ts)]  ← rs334 (серпоклітинна анемія)</cm>
</pre></div>
      </div>
    </div>

    <!-- TAB 2: Translation -->
    <div id="tab-translate" class="tab-pane">
      <div class="code-block">
        <div class="code-header">
          <span class="code-filename">translation.pseudo</span>
          <div style="display:flex;gap:0.75rem;align-items:center">
            <span class="code-lang">Pseudocode</span>
            <button class="copy-btn">Копіювати</button>
          </div>
        </div>
        <div class="code-body"><pre>
<cm>// ── DNA → Protein Translation ──────────────────────────────
// Транскрипція + трансляція: ДНК → мРНК → поліпептид</cm>

CODON_TABLE ← {
    <str>"UUU"</str>: <str>"Phe"</str>, <str>"UUC"</str>: <str>"Phe"</str>, <str>"UUA"</str>: <str>"Leu"</str>, <str>"UUG"</str>: <str>"Leu"</str>,
    <str>"CUU"</str>: <str>"Leu"</str>, <str>"CUC"</str>: <str>"Leu"</str>, <str>"CUA"</str>: <str>"Leu"</str>, <str>"CUG"</str>: <str>"Leu"</str>,
    <str>"AUU"</str>: <str>"Ile"</str>, <str>"AUC"</str>: <str>"Ile"</str>, <str>"AUA"</str>: <str>"Ile"</str>, <str>"AUG"</str>: <str>"Met"</str>,  <cm>// START</cm>
    <str>"GUU"</str>: <str>"Val"</str>, <str>"GUC"</str>: <str>"Val"</str>, <str>"GUA"</str>: <str>"Val"</str>, <str>"GUG"</str>: <str>"Val"</str>,
    <str>"UAA"</str>: <str>"Stop"</str>,<str>"UAG"</str>: <str>"Stop"</str>,<str>"UGA"</str>: <str>"Stop"</str>,  <cm>// STOP codons</cm>
    <cm>// ... (повна таблиця — 64 кодони)</cm>
}


<kw>FUNCTION</kw> <fn>transcribe</fn>(dna: <type>String</type>) → <type>String</type>:
    <cm>// ДНК → мРНК (T замінюється на U)</cm>
    <kw>RETURN</kw> replace(dna.uppercase(), <str>'T'</str>, <str>'U'</str>)


<kw>FUNCTION</kw> <fn>translate</fn>(mrna: <type>String</type>) → <type>List[AminoAcid]</type>:
    
    peptide  ← <kw>empty list</kw>
    started  ← <kw>false</kw>
    
    <kw>FOR</kw> i <kw>FROM</kw> <num>0</num> <kw>TO</kw> length(mrna) - <num>3</num> <kw>STEP</kw> <num>3</num>:
        codon ← mrna[i : i+<num>3</num>]
        aa    ← CODON_TABLE[codon]
        
        <kw>IF</kw> codon = <str>"AUG"</str> <kw>AND NOT</kw> started:
            started ← <kw>true</kw>        <cm>// Знайдено стартовий кодон</cm>
        
        <kw>IF</kw> started:
            <kw>IF</kw> aa = <str>"Stop"</str>:
                <kw>BREAK</kw>              <cm>// Термінаційний кодон</cm>
            <fn>append</fn>(peptide, aa)
    
    <kw>RETURN</kw> peptide


<kw>FUNCTION</kw> <fn>compare_proteins</fn>(dna_ref: <type>String</type>, dna_mut: <type>String</type>) → <type>MutationEffect</type>:
    
    prot_ref ← <fn>translate</fn>(<fn>transcribe</fn>(dna_ref))
    prot_mut ← <fn>translate</fn>(<fn>transcribe</fn>(dna_mut))
    
    <kw>FOR</kw> i <kw>FROM</kw> <num>0</num> <kw>TO</kw> min(length(prot_ref), length(prot_mut)) - <num>1</num>:
        <kw>IF</kw> prot_ref[i] ≠ prot_mut[i]:
            <kw>RETURN</kw> {
                type     : <str>"missense"</str>,
                position : i + <num>1</num>,
                ref_aa   : prot_ref[i],
                alt_aa   : prot_mut[i]
            }
    
    <kw>IF</kw> length(prot_ref) ≠ length(prot_mut):
        <kw>RETURN</kw> { type: <str>"truncation/extension"</str> }
    
    <kw>RETURN</kw> { type: <str>"silent"</str> }


<cm>// ── Приклад: HBB серпоклітинна анемія ───────────────────</cm>
dna_normal ← <str>"ATGGAGACAGACACACTCCTGCTAT..."</str>
dna_sickle ← <str>"ATGGTGACAGACACACTCCTGCTAT..."</str>  <cm>// GAG→GTG</cm>

effect ← <fn>compare_proteins</fn>(dna_normal, dna_sickle)
<cm>// → { type: "missense", position: 2, ref_aa: "Glu", alt_aa: "Val" }</cm>
<cm>// Клінічно: HbA → HbS → серпоподібноклітинна анемія</cm>
</pre></div>
      </div>
    </div>

    <!-- TAB 3: Classification -->
    <div id="tab-classify" class="tab-pane">
      <div class="code-block">
        <div class="code-header">
          <span class="code-filename">mutation_classifier.pseudo</span>
          <div style="display:flex;gap:0.75rem;align-items:center">
            <span class="code-lang">Pseudocode</span>
            <button class="copy-btn">Копіювати</button>
          </div>
        </div>
        <div class="code-body"><pre>
<cm>// ── Mutation Classifier ─────────────────────────────────────
// Класифікатор мутацій за типом та очікуваним наслідком
// Застосування: радіобіологія, онкологія, фармакогеноміка</cm>

<kw>FUNCTION</kw> <fn>classify_mutation</fn>(ref_seq: <type>String</type>, mut_seq: <type>String</type>) → <type>MutationReport</type>:

    len_diff ← length(mut_seq) - length(ref_seq)
    
    <cm>// 1. Визначення типу (indel vs substitution)</cm>
    <kw>IF</kw> len_diff = <num>0</num>:
        mutation_type ← <str>"substitution"</str>
    <kw>ELSE IF</kw> len_diff > <num>0</num>:
        mutation_type ← <str>"insertion"</str>
    <kw>ELSE</kw>:
        mutation_type ← <str>"deletion"</str>
    
    <cm>// 2. Frameshift аналіз (для indel)</cm>
    <kw>IF</kw> mutation_type ≠ <str>"substitution"</str>:
        <kw>IF</kw> abs(len_diff) mod <num>3</num> ≠ <num>0</num>:
            frameshift ← <kw>true</kw>
            severity   ← <str>"HIGH"</str>   <cm>// руйнує рамку зчитування</cm>
        <kw>ELSE</kw>:
            frameshift ← <kw>false</kw>
            severity   ← <str>"MODERATE"</str> <cm>// вставка/делеція цілого кодону</cm>
    
    <cm>// 3. Для substitution — наслідок для білка</cm>
    <kw>ELSE</kw>:
        effect ← <fn>compare_proteins</fn>(ref_seq, mut_seq)
        
        <kw>SWITCH</kw> effect.type:
            <kw>CASE</kw> <str>"silent"</str>:
                severity ← <str>"LOW"</str>
                frameshift ← <kw>false</kw>
            <kw>CASE</kw> <str>"missense"</str>:
                severity ← <fn>predict_pathogenicity</fn>(effect)
                frameshift ← <kw>false</kw>
            <kw>CASE</kw> <str>"nonsense"</str>:   <cm>// STOP-кодон</cm>
                severity ← <str>"HIGH"</str>
                frameshift ← <kw>false</kw>
    
    <kw>RETURN</kw> MutationReport {
        mutation_type : mutation_type,
        frameshift    : frameshift,
        severity      : severity,
        effect        : effect
    }


<kw>FUNCTION</kw> <fn>predict_pathogenicity</fn>(effect: <type>MutationEffect</type>) → <type>String</type>:
    <cm>// Спрощена SIFT/PolyPhen-подібна логіка</cm>
    
    conserved_positions ← <fn>load_conservation_scores</fn>()
    blosum_score        ← BLOSUM62[effect.ref_aa][effect.alt_aa]
    
    <kw>IF</kw> conserved_positions[effect.position] > <num>0.95</num> <kw>AND</kw> blosum_score < <num>-2</num>:
        <kw>RETURN</kw> <str>"HIGH"</str>         <cm>// патогенна з високою ймовірністю</cm>
    <kw>ELSE IF</kw> blosum_score < <num>0</num>:
        <kw>RETURN</kw> <str>"MODERATE"</str>
    <kw>ELSE</kw>:
        <kw>RETURN</kw> <str>"LOW"</str>


<cm>// ── Приклад: мутації генів репарації ДНК ────────────────</cm>
<cm>// (ATM — ген атаксії-телеангіектазії, радіочутливість)</cm>
ref_atm ← <fn>load_reference_sequence</fn>(<str>"ATM"</str>)
mut_atm ← <fn>load_patient_sequence</fn>(<str>"patient_001"</str>, gene=<str>"ATM"</str>)

report ← <fn>classify_mutation</fn>(ref_atm, mut_atm)
<fn>PRINT</fn> <str>f"Severity: {report.severity}, Frameshift: {report.frameshift}"</str>
</pre></div>
      </div>
    </div>

    <!-- TAB 4: GWAS -->
    <div id="tab-gwas" class="tab-pane">
      <div class="code-block">
        <div class="code-header">
          <span class="code-filename">gwas_chi2.pseudo</span>
          <div style="display:flex;gap:0.75rem;align-items:center">
            <span class="code-lang">Pseudocode</span>
            <button class="copy-btn">Копіювати</button>
          </div>
        </div>
        <div class="code-body"><pre>
<cm>// ── GWAS: χ²-тест для SNP-асоціації ──────────────────────
// Тест на зв'язок між генотипом SNP і фенотипом
// Застосовується: ідентифікація генів радіочутливості</cm>

<kw>FUNCTION</kw> <fn>gwas_chi2_test</fn>(snp_data: <type>Matrix[samples × genotypes]</type>,
                           phenotype: <type>List[Binary]</type>) → <type>List[AssocResult]</type>:

    results ← <kw>empty list</kw>
    
    <kw>FOR EACH</kw> snp <kw>IN</kw> columns(snp_data):        <cm>// 500 000–1M SNP</cm>
        
        <cm>// Таблиця 2×3: генотип {0,1,2} × хвороба {0,1}</cm>
        contingency ← <fn>build_contingency_table</fn>(snp, phenotype)
        <cm>//       AA   Aa   aa
        // case:  n11  n12  n13
        // ctrl:  n21  n22  n23</cm>
        
        <cm>// Застосовуємо спрощений χ² (additive model)</cm>
        expected ← <fn>compute_expected</fn>(contingency)
        chi2_stat ← <num>0</num>
        
        <kw>FOR</kw> i <kw>IN</kw> rows(contingency):
            <kw>FOR</kw> j <kw>IN</kw> cols(contingency):
                obs ← contingency[i][j]
                exp ← expected[i][j]
                <kw>IF</kw> exp > <num>0</num>:
                    chi2_stat ← chi2_stat + (obs - exp)² / exp
        
        <cm>// df = (рядки-1)×(стовпці-1) = 1×2 = 2</cm>
        p_value ← <fn>chi2_to_pvalue</fn>(chi2_stat, df=<num>2</num>)
        log_p   ← -log10(p_value)
        
        result ← AssocResult {
            snp_id     : snp.id,
            chromosome : snp.chr,
            position   : snp.bp,
            chi2       : chi2_stat,
            p_value    : p_value,
            neg_log_p  : log_p,
            significant: p_value < <num>5e-8</num>    <cm>// genome-wide threshold</cm>
        }
        <fn>append</fn>(results, result)
    
    <cm>// Корекція на множинні порівняння (Bonferroni або FDR)</cm>
    results ← <fn>apply_bonferroni_correction</fn>(results, alpha=<num>0.05</num>)
    
    <kw>RETURN</kw> <fn>sort_by</fn>(results, key=<str>"neg_log_p"</str>, descending=<kw>true</kw>)


<cm>// ── Приклад: GWAS радіочутливості ───────────────────────
// Когорта: пацієнти з раком голови та шиї, n=3000
// Фенотип: тяжка гостра радіотоксичність (grade ≥3) після ПТ</cm>

genotype_matrix ← <fn>load_SNP_array</fn>(<str>"cohort_radiotherapy.vcf"</str>)
toxicity        ← <fn>load_phenotype</fn>(<str>"toxicity_grades.csv"</str>, threshold=<num>3</num>)

associations ← <fn>gwas_chi2_test</fn>(genotype_matrix, toxicity)

<cm>// Топ-хіти: ATM rs1801516, XRCC1 rs25487, TP53 rs1042522</cm>
top_hits ← <fn>filter</fn>(associations, lambda r: r.significant = <kw>true</kw>)
<fn>visualize_manhattan_plot</fn>(associations)
<fn>export_results</fn>(top_hits, <str>"gwas_radiotoxicity_results.tsv"</str>)
</pre></div>
      </div>
    </div>

  </div><!-- /tabs-wrap -->
</div>
</section>

<!-- ══════════════════════════════════════════
     SECTION 5 · RESEARCH
════════════════════════════════════════════ -->
<section id="research">
<div class="container">
  <div class="reveal">
    <p class="section-tag">05 — Наукове джерело</p>
    <h2 class="section-title">Дослідження: GWAS та пізня токсичність радіотерапії</h2>
    <p class="section-lead">
      Нижче наведено вижимку реальної опублікованої роботи, що безпосередньо
      досліджувала зв'язок між SNP-варіантами та токсичністю після радіотерапії —
      найбільше на той момент GWAS-дослідження у радіогеноміці.
    </p>
  </div>

  <div class="research-list">

    <!-- MAIN PAPER CARD -->
    <div class="research-card reveal" style="border-left-color: var(--accent);">
      <div class="research-meta">
        <span class="research-year">2014</span>
        <span class="research-journal">Radiotherapy &amp; Oncology</span>
        <span class="research-tag">GWAS · Радіогеноміка · SNP</span>
        <span class="research-tag" style="color:var(--gold2);border-color:rgba(245,158,11,0.3);background:rgba(245,158,11,0.08);">Завантажений PDF</span>
      </div>
      <h3>A genome wide association study (GWAS) providing evidence of an association between common genetic variants and late radiotherapy toxicity</h3>
      <p style="color:var(--text-muted);font-family:var(--mono);font-size:0.75rem;margin-bottom:1rem;">
        Barnett GC, Thompson D, Fachal L, Kerns S, Talbot C, Elliott RM, et al. — Radiother Oncol. 2014;111(2):178–185
      </p>

      <!-- KEY POINTS GRID -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
        <div style="background:var(--bg3);border:1px solid var(--border);border-radius:2px;padding:1rem;">
          <div style="font-family:var(--mono);font-size:0.68rem;color:var(--accent);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Мета</div>
          <p style="font-size:0.88rem;color:var(--text-dim);line-height:1.7;">
            Виявити поширені SNP, асоційовані з токсичністю через 2 роки після радіотерапії,
            використовуючи дизайн GWAS із фазою реплікації.
          </p>
        </div>
        <div style="background:var(--bg3);border:1px solid var(--border);border-radius:2px;padding:1rem;">
          <div style="font-family:var(--mono);font-size:0.68rem;color:var(--accent);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Когорта</div>
          <p style="font-size:0.88rem;color:var(--text-dim);line-height:1.7;">
            <strong style="color:var(--text)">1 850 пацієнтів</strong> (RAPPER study): 1 217 — рак молочної залози, 633 — рак передміхурової залози.
            Реплікація: 3 незалежні когорти (RADIOGEN, Gene-PARE, LeND), ще ~1 730 пацієнтів.
          </p>
        </div>
        <div style="background:var(--bg3);border:1px solid var(--border);border-radius:2px;padding:1rem;">
          <div style="font-family:var(--mono);font-size:0.68rem;color:var(--accent);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Метод</div>
          <p style="font-size:0.88rem;color:var(--text-dim);line-height:1.7;">
            Генотипування на Illumina CytoSNP12. Аналіз <strong style="color:var(--text)">2 417 493 SNP</strong>
            (генотипованих та імпутованих). Лінійна регресія токсичності проти кількості мінорних алелів.
            Поріг значущості: p&nbsp;&lt;&nbsp;5×10⁻⁸.
          </p>
        </div>
        <div style="background:var(--bg3);border:1px solid var(--border);border-radius:2px;padding:1rem;">
          <div style="font-family:var(--mono);font-size:0.68rem;color:var(--accent);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Зв'язок із SNP/GWAS</div>
          <p style="font-size:0.88rem;color:var(--text-dim);line-height:1.7;">
            Саме це і є GWAS: сканування мільйонів SNP по всьому геному для пошуку варіантів,
            що визначають індивідуальну радіочутливість пацієнта без попередніх гіпотез про конкретні гени.
          </p>
        </div>
      </div>

      <!-- FINDINGS -->
      <div style="margin-bottom:1.25rem;">
        <div style="font-family:var(--mono);font-size:0.72rem;color:var(--gold2);text-transform:uppercase;letter-spacing:0.12em;margin-bottom:0.75rem;">Ключові результати</div>
        <ul style="list-style:none;display:flex;flex-direction:column;gap:0.75rem;">
          <li style="font-size:0.9rem;color:var(--text-dim);line-height:1.75;padding-left:1.4rem;position:relative;">
            <span style="color:var(--accent);position:absolute;left:0;top:0;">→</span>
            Q–Q plots показали значно більше асоціацій при p&nbsp;&lt;&nbsp;5×10⁻⁷, ніж очікується випадково: <strong style="color:var(--text)">164 vs. 9</strong> для раку простати і <strong style="color:var(--text)">29 vs. 4</strong> для молочної залози — пряме свідчення того, що поширені SNP справді впливають на ризик токсичності.
          </li>
          <li style="font-size:0.9rem;color:var(--text-dim);line-height:1.75;padding-left:1.4rem;position:relative;">
            <span style="color:var(--accent);position:absolute;left:0;top:0;">→</span>
            Найсильніші асоціації — <strong style="color:var(--text)">специфічні для локалізації пухлини</strong> (рак простати vs. молочна залоза), а не загальні для всіх тканин. Це контрастує з рідкісними варіантами (ATM, BRCA), що дають великий ефект незалежно від локалізації.
          </li>
          <li style="font-size:0.9rem;color:var(--text-dim);line-height:1.75;padding-left:1.4rem;position:relative;">
            <span style="color:var(--accent);position:absolute;left:0;top:0;">→</span>
            SNP <strong style="color:var(--text)">rs2788612</strong> у гені <em>KCND3</em> (потенціал-керований K⁺-канал) асоційований із ректальним нетриманням у простат-пацієнтів (p&nbsp;=&nbsp;1.05×10⁻¹²). Біологічно правдоподібно: ген експресується у гладком'язових клітинах сфінктера.
          </li>
          <li style="font-size:0.9rem;color:var(--text-dim);line-height:1.75;padding-left:1.4rem;position:relative;">
            <span style="color:var(--accent);position:absolute;left:0;top:0;">→</span>
            Для підтвердження на рівні genome-wide significance потрібні вибірки <strong style="color:var(--text)">≥3 000 пацієнтів</strong> на одну локалізацію. Автори заснували Radiogenomics Consortium (RGC) саме для цього.
          </li>
        </ul>
      </div>

      <!-- HOW SNP+GWAS CONNECT -->
      <div style="background:rgba(59,130,246,0.06);border:1px solid rgba(59,130,246,0.2);border-radius:2px;padding:1.25rem 1.5rem;margin-bottom:1.25rem;">
        <div style="font-family:var(--mono);font-size:0.7rem;color:var(--accent2);text-transform:uppercase;letter-spacing:0.12em;margin-bottom:0.6rem;">Як тут пов'язані SNP і GWAS</div>
        <p style="font-size:0.9rem;color:var(--text-dim);line-height:1.8;">
          Кожен пацієнт у цьому дослідженні має <strong style="color:var(--text)">мільйони SNP</strong> — позицій у геномі,
          де його нуклеотид відрізняється від референсного. GWAS перевіряє кожну таку позицію статистично:
          чи пацієнти з мінорним алелем (наприклад, <code style="font-family:var(--mono);color:var(--accent3)">T</code> замість <code style="font-family:var(--mono);color:var(--accent3)">A</code>)
          частіше мають тяжку токсичність? Чим менший p-value — тим сильніший зв'язок.
          Поріг p&nbsp;&lt;&nbsp;5×10⁻⁸ обраний для корекції на ~1 млн незалежних тестів (множинні порівняння).
          Знайдені SNP вказують на гени та біологічні шляхи, що контролюють радіочутливість,
          відкриваючи шлях до <strong style="color:var(--text)">персоналізованого дозування радіотерапії</strong>.
        </p>
      </div>

      <p class="doi">
        DOI: <a href="https://doi.org/10.1016/j.radonc.2014.02.012" target="_blank">10.1016/j.radonc.2014.02.012</a>
        &nbsp;·&nbsp; Barnett GC et al. <em>Radiother Oncol.</em> 2014;111(2):178–185.
        &nbsp;·&nbsp; RAPPER Study (UKCRN1471) · Radiogenomics Consortium (RGC)
      </p>
    </div>

  </div>

  <div class="info-box reveal" style="margin-top:2rem">
    <span class="info-box-icon">📚</span>
    <div class="info-box-text">
      <strong>Пов'язані ресурси:</strong><br/>
      <a href="https://www.ebi.ac.uk/gwas/" target="_blank" style="color:var(--accent3)">NHGRI-EBI GWAS Catalog</a> ·
      <a href="https://www.ncbi.nlm.nih.gov/snp/" target="_blank" style="color:var(--accent3)">NCBI dbSNP</a> ·
      <a href="https://radiogenomics.org" target="_blank" style="color:var(--accent3)">Radiogenomics Consortium</a> ·
      <a href="https://www.ncbi.nlm.nih.gov/clinvar/" target="_blank" style="color:var(--accent3)">ClinVar</a>
    </div>
  </div>

</div>
</section>

<!-- ══════════════════════════════════════════
     SECTION 6 · HTMX — запит до БД
════════════════════════════════════════════ -->
<section id="db-table">
<div class="container">
  <div class="reveal">
    <p class="section-tag">06 — База даних · HTMX</p>
    <h2 class="section-title">Таблиця SNP із бази даних</h2>
    <p class="section-lead">
      Дані завантажуються з MySQL через <strong>htmx</strong> — без перезавантаження сторінки.
      Введіть назву гену, rs-id або хворобу для фільтрації.
    </p>
  </div>

  <!-- Bootstrap Alert (елемент Bootstrap) -->
  <div class="alert bs-alert d-flex align-items-center gap-2 reveal" role="alert">
    <i class="bi bi-database-fill"></i>
    <div>
      <strong>HTMX + MySQL:</strong> рядки таблиці нижче — це HTML-фрагмент, повернутий сервером
      (<code>api.php?action=htmx_snp_table</code>). Bootstrap Alert використано для цього повідомлення.
    </div>
  </div>

  <div class="htmx-section reveal">
    <div class="htmx-header">
      <span class="htmx-title">⬡ snp_variants · genomics_db</span>
      <div class="htmx-search">
        <input
          type="search"
          id="htmx-search-input"
          name="search"
          placeholder="Пошук: ген, rs-id, хвороба…"
          hx-get="api.php?action=htmx_snp_table"
          hx-target="#snp-tbody"
          hx-trigger="keyup changed delay:400ms, search"
          hx-indicator="#htmx-spin"
        />
        <button
          hx-get="api.php?action=htmx_snp_table"
          hx-target="#snp-tbody"
          hx-trigger="click"
          hx-include="#htmx-search-input"
          hx-indicator="#htmx-spin"
        >
          <i class="bi bi-search"></i> Знайти
        </button>
      </div>
    </div>

    <span id="htmx-spin" class="htmx-indicator">⟳ Завантаження…</span>

    <div style="overflow-x:auto">
      <table class="snp-table">
        <thead>
          <tr>
            <th>rs ID</th>
            <th>Ген</th>
            <th>Хр.</th>
            <th>Ref → Alt</th>
            <th>MAF</th>
            <th>Наслідок</th>
            <th>Захворювання</th>
          </tr>
        </thead>
        <tbody id="snp-tbody"
          hx-get="api.php?action=htmx_snp_table"
          hx-trigger="load"
          hx-indicator="#htmx-spin">
          <tr><td colspan="7" style="text-align:center;padding:2rem;color:#64748b;font-family:monospace">Завантаження даних з БД…</td></tr>
        </tbody>
      </table>
    </div>

    <!-- Bootstrap Accordion з поясненням htmx -->
    <div class="accordion bs-accordion mt-4" id="htmxAccordion">
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button"
            data-bs-toggle="collapse" data-bs-target="#htmxExplain">
            <i class="bi bi-code-slash me-2"></i> Як це працює: HTMX + PHP + MySQL
          </button>
        </h2>
        <div id="htmxExplain" class="accordion-collapse collapse" data-bs-parent="#htmxAccordion">
          <div class="accordion-body">
            <p>При завантаженні сторінки елемент <code>&lt;tbody hx-trigger="load"&gt;</code> автоматично
            надсилає GET-запит на <code>api.php?action=htmx_snp_table</code>.</p>
            <p>Сервер виконує SQL-запит до таблиці <code>snp_variants</code> у MySQL
            і повертає готові HTML <code>&lt;tr&gt;</code> рядки — без JSON, без JavaScript вручну.</p>
            <p>При введенні пошукового запиту <code>hx-trigger="keyup delay:400ms"</code>
            надсилає новий запит із параметром <code>?search=...</code>, а сервер фільтрує через
            <code>WHERE rs_id LIKE ? OR gene LIKE ? OR disease LIKE ?</code>.</p>
            <div class="mt-2">
              <span class="badge bg-primary bs-badge me-1">HTMX 1.9</span>
              <span class="badge bg-success bs-badge me-1">PHP 8+</span>
              <span class="badge bg-warning text-dark bs-badge me-1">MySQL / PDO</span>
              <span class="badge bg-info text-dark bs-badge">Bootstrap 5.3</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div><!-- /htmx-section -->
</div>
</section>

<!-- ══════════════════════════════════════════
     SECTION 7 · AJAX FORM
════════════════════════════════════════════ -->
<section id="contact">
<div class="container">
  <div class="reveal">
    <p class="section-tag">07 — Зворотний зв'язок · AJAX</p>
    <h2 class="section-title">Контактна форма</h2>
    <p class="section-lead">
      Форма надсилається через <strong>AJAX</strong> (Fetch API) без перезавантаження сторінки.
      Дані зберігаються у таблиці <code>contact_requests</code> бази даних.
    </p>
  </div>

  <div class="ajax-form-section reveal">
    <div class="ajax-form-title">Написати нам</div>
    <div class="ajax-form-sub">AJAX POST → api.php?action=contact → MySQL INSERT</div>

    <div id="ajax-form-wrap">
      <div class="ajax-form">
        <div class="ajax-field">
          <label for="cf-name">Ім'я *</label>
          <input type="text" id="cf-name" placeholder="Іван Петренко" />
        </div>
        <div class="ajax-field">
          <label for="cf-email">Email *</label>
          <input type="email" id="cf-email" placeholder="ivan@example.com" />
        </div>
        <div class="ajax-field full">
          <label for="cf-subject">Тема</label>
          <input type="text" id="cf-subject" placeholder="Питання про SNP rs334…" />
        </div>
        <div class="ajax-field full">
          <label for="cf-message">Повідомлення *</label>
          <textarea id="cf-message" rows="4" placeholder="Ваше питання або коментар…"></textarea>
        </div>
        <div class="full">
          <button class="ajax-submit" id="ajax-submit-btn">
            <i class="bi bi-send"></i> Надіслати
          </button>
        </div>
      </div>
      <div id="ajax-status"></div>
    </div>
  </div>

</div>
</section>

</main>

<!-- Bootstrap Toast — з'являється при вмиканні роздільників -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999">
  <div id="rainbow-toast" class="toast" role="alert" aria-live="assertive"
       style="background:#0b1018;border:1px solid rgba(99,255,99,0.4);color:#e2e8f0;min-width:260px">
    <div class="toast-header" style="background:#0f1622;border-bottom:1px solid rgba(99,255,99,0.2);color:#6fff6f">
      <span style="margin-right:0.5rem">◈</span>
      <strong class="me-auto" style="font-family:monospace;font-size:0.8rem">Bootstrap Toast</strong>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
    </div>
    <div class="toast-body" style="font-size:0.85rem" id="rainbow-toast-msg">
      Роздільники увімкнено ✓
    </div>
  </div>
</div>

<footer>
  <div class="footer-logo">⬡ Mutations & SNP Variations</div>
  <p class="footer-note">
    Навчальний ресурс з молекулярної генетики та радіобіології.<br/>
    Дані симулятора наведені виключно в освітніх цілях.<br/>
    Наукові посилання відповідають реальним публікаціям у рецензованих журналах.<br/><br/>
    Стандарти номенклатури: <a href="https://varnomen.hgvs.org" target="_blank">HGVS</a> ·
    Генетичні терміни: <a href="https://www.omim.org" target="_blank">OMIM</a> ·
    Варіанти: <a href="https://www.ncbi.nlm.nih.gov/clinvar/" target="_blank">ClinVar</a>
  </p>
</footer>

<script src="script.js"></script>
<script>
/* ════════════════════════════════════════════
   КНОПКА «РОЗДІЛЬНИКИ»
   Як працює:
   1. При завантаженні JS вставляє <div class="rb-sep">
      між кожною парою сусідніх <section> у <main>
   2. Клік на кнопку: якщо вимкнено → додає клас rb-on
      на всі rb-sep → стрічка з'являється
   3. Повторний клік → знімає rb-on → стрічка зникає
════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function() {

  var btn = document.getElementById('rainbow-btn');
  var on  = false;

  /* --- Вставляємо роздільники між секціями --- */
  var sections = document.querySelectorAll('main > section');
  for (var i = 0; i < sections.length - 1; i++) {
    var sep = document.createElement('div');
    sep.className = 'rb-sep';
    sections[i].after(sep);
  }

  /* --- Клік по кнопці --- */
  btn.addEventListener('click', function() {
    on = !on;

    /* Стан кнопки */
    if (on) {
      btn.classList.add('rb-active');
      btn.textContent = '◈ Сховати';
    } else {
      btn.classList.remove('rb-active');
      btn.textContent = '◈ Роздільники';
    }

    /* Показуємо / ховаємо всі стрічки */
    var seps = document.querySelectorAll('.rb-sep');
    for (var j = 0; j < seps.length; j++) {
      if (on) {
        seps[j].classList.add('rb-on');
      } else {
        seps[j].classList.remove('rb-on');
      }
    }

    /* Bootstrap Toast */
    var toastEl = document.getElementById('rainbow-toast');
    var msgEl   = document.getElementById('rainbow-toast-msg');
    if (toastEl && msgEl && typeof bootstrap !== 'undefined') {
      msgEl.textContent = on ? '✓ Роздільники увімкнено' : 'Роздільники вимкнено';
      bootstrap.Toast.getOrCreateInstance(toastEl, {delay:2000}).show();
    }
  });

  /* --- Бургер меню --- */
  var burger = document.getElementById('burger-btn');
  var list   = document.getElementById('nav-links-list');
  if (burger && list) {
    burger.addEventListener('click', function() {
      var isOpen = list.classList.toggle('open');
      burger.textContent = isOpen ? '✕' : '☰';
    });
    list.querySelectorAll('a').forEach(function(a) {
      a.addEventListener('click', function() {
        list.classList.remove('open');
        burger.textContent = '☰';
      });
    });
  }

});

/* ── AJAX Contact Form ──────────────────────────────── */
(function () {
  const submitBtn = document.getElementById('ajax-submit-btn');
  const statusEl  = document.getElementById('ajax-status');
  if (!submitBtn) return;

  function showStatus(msg, type) {
    statusEl.textContent = msg;
    statusEl.className   = type; // 'success' | 'error'
  }

  submitBtn.addEventListener('click', async () => {
    const name    = document.getElementById('cf-name').value.trim();
    const email   = document.getElementById('cf-email').value.trim();
    const subject = document.getElementById('cf-subject').value.trim();
    const message = document.getElementById('cf-message').value.trim();

    /* Клієнтська валідація */
    if (!name)    { showStatus('⚠ Введіть ваше ім\'я', 'error'); return; }
    if (!email || !email.includes('@')) { showStatus('⚠ Введіть коректний email', 'error'); return; }
    if (message.length < 10) { showStatus('⚠ Повідомлення занадто коротке', 'error'); return; }

    submitBtn.disabled   = true;
    submitBtn.textContent = '⟳ Надсилання…';
    statusEl.className   = '';

    try {
      const res = await fetch('api.php?action=contact', {
        method : 'POST',
        headers: { 'Content-Type': 'application/json' },
        body   : JSON.stringify({ name, email, subject, message }),
      });
      const data = await res.json();

      if (data.status === 'ok') {
        showStatus('✓ ' + data.message, 'success');
        /* Очищаємо поля після успіху */
        ['cf-name','cf-email','cf-subject','cf-message'].forEach(id => {
          document.getElementById(id).value = '';
        });
      } else {
        const errs = Array.isArray(data.errors) ? data.errors.join('; ') : data.message;
        showStatus('✕ ' + errs, 'error');
      }
    } catch (err) {
      showStatus('✕ Помилка мережі: ' + err.message, 'error');
    } finally {
      submitBtn.disabled   = false;
      submitBtn.innerHTML  = '<i class="bi bi-send"></i> Надіслати';
    }
  });
})();
</script>
</body>
</html>