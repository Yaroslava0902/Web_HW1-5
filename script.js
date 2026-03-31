/* ============================================================
   script.js — Mutations & SNP Variations — Interactive Logic
   ============================================================ */

'use strict';

// ── Scroll Reveal ──────────────────────────────────────────
const revealObserver = new IntersectionObserver(
  (entries) => entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
      revealObserver.unobserve(e.target);
    }
  }),
  { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
);
document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

// ── Codon Mutation Simulator ───────────────────────────────
const CODON_TABLE = {
  'UUU':'Phe','UUC':'Phe','UUA':'Leu','UUG':'Leu',
  'CUU':'Leu','CUC':'Leu','CUA':'Leu','CUG':'Leu',
  'AUU':'Ile','AUC':'Ile','AUA':'Ile','AUG':'Met',
  'GUU':'Val','GUC':'Val','GUA':'Val','GUG':'Val',
  'UCU':'Ser','UCC':'Ser','UCA':'Ser','UCG':'Ser',
  'CCU':'Pro','CCC':'Pro','CCA':'Pro','CCG':'Pro',
  'ACU':'Thr','ACC':'Thr','ACA':'Thr','ACG':'Thr',
  'GCU':'Ala','GCC':'Ala','GCA':'Ala','GCG':'Ala',
  'UAU':'Tyr','UAC':'Tyr','UAA':'Stop','UAG':'Stop',
  'CAU':'His','CAC':'His','CAA':'Gln','CAG':'Gln',
  'AAU':'Asn','AAC':'Asn','AAA':'Lys','AAG':'Lys',
  'GAU':'Asp','GAC':'Asp','GAA':'Glu','GAG':'Glu',
  'UGU':'Cys','UGC':'Cys','UGA':'Stop','UGG':'Trp',
  'CGU':'Arg','CGC':'Arg','CGA':'Arg','CGG':'Arg',
  'AGU':'Ser','AGC':'Ser','AGA':'Arg','AGG':'Arg',
  'GGU':'Gly','GGC':'Gly','GGA':'Gly','GGG':'Gly',
};

function dnaToRna(dna) {
  return dna.toUpperCase().replace(/T/g, 'U');
}

function splitCodons(rna) {
  const codons = [];
  for (let i = 0; i + 2 < rna.length; i += 3) codons.push(rna.slice(i, i+3));
  return codons;
}

function translateCodons(codons) {
  return codons.map(c => CODON_TABLE[c] || '???');
}

function buildSimulator() {
  const input = document.getElementById('sim-input');
  const posEl = document.getElementById('sim-pos');
  const baseEl = document.getElementById('sim-base');
  const runBtn = document.getElementById('sim-run');
  const output = document.getElementById('sim-output');

  if (!input || !runBtn) return;

  function render(original, mutated, pos, newBase) {
    const origRna = splitCodons(dnaToRna(original));
    const mutRna  = splitCodons(dnaToRna(mutated));
    const origAA  = translateCodons(origRna);
    const mutAA   = translateCodons(mutRna);

    const changedCodon = Math.floor(pos / 3);

    let html = `
      <div class="sim-result">
        <div class="sim-row-label">ДНК оригінал</div>
        <div class="sim-seq">${[...original].map((b,i) =>
          `<span class="base-char base-${b} ${i===pos?'base-mut':''}">${b}</span>`
        ).join('')}</div>
        <div class="sim-row-label" style="margin-top:0.5rem">ДНК мутант (pos ${pos}: ${original[pos]}→${newBase})</div>
        <div class="sim-seq">${[...mutated].map((b,i) =>
          `<span class="base-char base-${b} ${i===pos?'base-mut':''}">${b}</span>`
        ).join('')}</div>
        <div class="sim-divider">↓ трансляція</div>
        <div class="sim-row-label">Амінокислоти оригінал</div>
        <div class="aa-row">${origAA.map((aa,i) =>
          `<span class="aa-chip ${i===changedCodon?'normal':''}">${aa}</span>`
        ).join('')}</div>
        <div class="sim-row-label" style="margin-top:0.5rem">Амінокислоти мутант</div>
        <div class="aa-row">${mutAA.map((aa,i) =>
          `<span class="aa-chip ${i===changedCodon?'changed':''}">${aa}</span>`
        ).join('')}</div>
        ${origAA[changedCodon] !== mutAA[changedCodon]
          ? `<div class="sim-change-notice">⚠ Missense: ${origAA[changedCodon]} → ${mutAA[changedCodon]} (кодон ${changedCodon+1})</div>`
          : `<div class="sim-silent-notice">✓ Silent mutation: амінокислота не змінилась (${origAA[changedCodon]})</div>`
        }
      </div>`;
    output.innerHTML = html;
  }

  runBtn.addEventListener('click', () => {
    const seq = input.value.trim().toUpperCase().replace(/[^ATGC]/g,'');
    const pos = parseInt(posEl.value);
    const newBase = baseEl.value.toUpperCase();

    if (seq.length < 3) { output.innerHTML = '<p class="sim-error">Введіть щонайменше 3 нуклеотиди.</p>'; return; }
    if (pos < 0 || pos >= seq.length) { output.innerHTML = '<p class="sim-error">Позиція виходить за межі послідовності.</p>'; return; }
    if (!'ATGC'.includes(newBase)) { output.innerHTML = '<p class="sim-error">Введіть коректний нуклеотид (A, T, G, C).</p>'; return; }

    const mutated = seq.slice(0, pos) + newBase + seq.slice(pos+1);
    render(seq, mutated, pos, newBase);
  });

  // Default demo
  input.value = 'ATGGAGACAGACACACTCCTGCTATGGGTACTGCTGCTCTGGGTTCCAGGTTCCACTGGT';
  posEl.value = '4';
  baseEl.value = 'T';
  runBtn.click();
}

// ── Manhattan Plot (Canvas) ────────────────────────────────
function drawManhattan() {
  const canvas = document.getElementById('manhattan');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  canvas.width = canvas.offsetWidth * window.devicePixelRatio;
  canvas.height = canvas.offsetHeight * window.devicePixelRatio;
  ctx.scale(window.devicePixelRatio, window.devicePixelRatio);

  const W = canvas.offsetWidth;
  const H = canvas.offsetHeight;

  ctx.clearRect(0, 0, W, H);

  const chromosomes = 22;
  const threshold = 7.3; // -log10(5e-8)
  const suggestive = 6;

  // Colors per chromosome alternating
  const cols = ['rgba(59,130,246,0.7)', 'rgba(96,165,250,0.7)'];

  const seed = 42;
  function lcg(s) { return ((1664525 * s + 1013904223) >>> 0) / 4294967296; }

  // Draw threshold lines
  ctx.strokeStyle = 'rgba(239,68,68,0.5)';
  ctx.setLineDash([4, 4]);
  ctx.lineWidth = 1;
  const thY = H - (threshold / 9) * (H - 30) - 10;
  ctx.beginPath(); ctx.moveTo(40, thY); ctx.lineTo(W - 10, thY); ctx.stroke();

  ctx.strokeStyle = 'rgba(245,158,11,0.3)';
  const sugY = H - (suggestive / 9) * (H - 30) - 10;
  ctx.beginPath(); ctx.moveTo(40, sugY); ctx.lineTo(W - 10, sugY); ctx.stroke();
  ctx.setLineDash([]);

  // Threshold labels
  ctx.fillStyle = 'rgba(239,68,68,0.7)';
  ctx.font = `${9 * 1}px JetBrains Mono, monospace`;
  ctx.fillText('p = 5×10⁻⁸', W - 80, thY - 4);

  // Draw points
  const chrWidth = (W - 50) / chromosomes;
  let s = seed;

  for (let chr = 0; chr < chromosomes; chr++) {
    const nSnps = 20 + Math.floor(lcg(s++) * 30);
    const color = cols[chr % 2];

    for (let i = 0; i < nSnps; i++) {
      s = (s * 1664525 + 1013904223) >>> 0;
      const x = 45 + chr * chrWidth + lcg(s) * chrWidth * 0.85;
      s = (s * 1664525 + 1013904223) >>> 0;

      // Most SNPs have low significance, some high
      let logP;
      const r = lcg(s);
      if (r > 0.97) {
        s = (s * 1664525 + 1013904223) >>> 0;
        logP = 7 + lcg(s) * 3; // genome-wide significant
      } else if (r > 0.9) {
        logP = 5 + lcg(s) * 2;
      } else {
        logP = lcg(s) * 5;
      }

      const y = H - 10 - (logP / 10) * (H - 30);

      ctx.beginPath();
      ctx.arc(x, y, logP > threshold ? 4 : 2.5, 0, Math.PI * 2);

      if (logP > threshold) {
        ctx.fillStyle = 'rgba(239,68,68,0.9)';
        // Glow
        ctx.shadowColor = '#ef4444';
        ctx.shadowBlur = 8;
      } else {
        ctx.fillStyle = color;
        ctx.shadowBlur = 0;
      }
      ctx.fill();
      ctx.shadowBlur = 0;
    }

    // Chr label
    if ((chr + 1) % 4 === 1 || chr === 0 || chr === chromosomes - 1) {
      ctx.fillStyle = 'rgba(100,116,139,0.8)';
      ctx.font = `${8}px JetBrains Mono, monospace`;
      ctx.textAlign = 'center';
      ctx.fillText(chr + 1, 45 + chr * chrWidth + chrWidth / 2, H - 1);
    }
  }

  // Y-axis labels
  ctx.textAlign = 'right';
  ctx.fillStyle = 'rgba(100,116,139,0.8)';
  ctx.font = `${8}px JetBrains Mono, monospace`;
  for (let v = 0; v <= 9; v += 3) {
    const y = H - 10 - (v / 9) * (H - 30);
    ctx.fillText(v, 36, y + 3);
  }

  // Axis label
  ctx.save();
  ctx.translate(12, H / 2);
  ctx.rotate(-Math.PI / 2);
  ctx.textAlign = 'center';
  ctx.fillStyle = 'rgba(100,116,139,0.6)';
  ctx.font = `8px JetBrains Mono, monospace`;
  ctx.fillText('−log₁₀(p)', 0, 0);
  ctx.restore();

  // X label
  ctx.textAlign = 'center';
  ctx.fillStyle = 'rgba(100,116,139,0.6)';
  ctx.font = `8px JetBrains Mono, monospace`;
  ctx.fillText('Chromosome', W / 2, H + 2);
}

// ── Tabs ──────────────────────────────────────────────────
function initTabs() {
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const group = btn.closest('.tabs-wrap');
      group.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      group.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      const target = document.getElementById(btn.dataset.tab);
      if (target) target.classList.add('active');
    });
  });
}

// ── Copy Code ─────────────────────────────────────────────
function initCopyButtons() {
  document.querySelectorAll('.copy-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const pre = btn.closest('.code-block').querySelector('pre');
      navigator.clipboard.writeText(pre.innerText).then(() => {
        btn.textContent = 'Скопійовано!';
        setTimeout(() => btn.textContent = 'Копіювати', 2000);
      });
    });
  });
}

// ── Nav highlight ─────────────────────────────────────────
function initNavHighlight() {
  const sections = document.querySelectorAll('section[id]');
  const links = document.querySelectorAll('.nav-links a');

  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        links.forEach(l => l.classList.remove('active'));
        const link = document.querySelector(`.nav-links a[href="#${e.target.id}"]`);
        if (link) link.classList.add('active');
      }
    });
  }, { rootMargin: '-40% 0px -55% 0px' });

  sections.forEach(s => obs.observe(s));
}

// ── Particle DNA helix animation ─────────────────────────
function initHeroCanvas() {
  const canvas = document.getElementById('hero-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  let W, H, raf;

  function resize() {
    W = canvas.width = canvas.offsetWidth;
    H = canvas.height = canvas.offsetHeight;
  }

  function draw(t) {
    ctx.clearRect(0, 0, W, H);
    const amp = 60;
    const freq = 0.008;
    const speed = 0.0008;

    for (let x = 0; x < W; x += 6) {
      const y1 = H/2 + amp * Math.sin(freq * x + t);
      const y2 = H/2 + amp * Math.sin(freq * x + t + Math.PI);
      const prog = x / W;
      const alpha = 0.12 + 0.1 * Math.sin(prog * Math.PI);

      ctx.beginPath();
      ctx.arc(x, y1, 2, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(59,130,246,${alpha})`;
      ctx.fill();

      ctx.beginPath();
      ctx.arc(x, y2, 2, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(245,158,11,${alpha * 0.7})`;
      ctx.fill();

      if (x % 18 === 0) {
        ctx.beginPath();
        ctx.moveTo(x, y1); ctx.lineTo(x, y2);
        ctx.strokeStyle = `rgba(139,92,246,${alpha * 0.5})`;
        ctx.lineWidth = 1;
        ctx.stroke();
      }
    }
    raf = requestAnimationFrame(tt => draw(tt * speed));
  }

  resize();
  window.addEventListener('resize', resize);
  draw(0);
}

// ── Init ─────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  initHeroCanvas();
  buildSimulator();
  drawManhattan();
  initTabs();
  initCopyButtons();
  initNavHighlight();

  window.addEventListener('resize', drawManhattan);
});