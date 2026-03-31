<?php
// sections/hero.php — v4 ALABASTER EDITION
// FRAGMENT: jangan tambahkan <html>, <head>, atau <body> di file ini.
?>

<style>

html, body {
  margin: 0;
  padding: 0;
  background-color: #fffdf7;
  color: #0a0a0a;
}

:root {
  --font-serif:    'Instrument Serif', Georgia, serif;
  --font-sans:     'Outfit', sans-serif;

  /* ── PALET BARU ─────────────────────────── */
  --hr-accent:      #536878;                    /* Blue Slate */
  --hr-accent-dim:  rgba(83,104,120,0.14);
  --hr-accent-glow: rgba(83,104,120,0.07);
  --hr-bg:          #fffdf7;                    /* Cream / Ivory */
  --hr-surface:     #f5f2ea;                    /* Warm Cream — card/panel */
  --hr-fg:          #0a0a0a;                    /* Onyx */
  --hr-fg-mid:      rgba(10,10,10,0.55);
  --hr-fg-low:      rgba(10,10,10,0.35);
  --hr-border:      rgba(10,10,10,0.09);
  --hr-border-acc:  rgba(83,104,120,0.28);
  --hr-green:       #488c60;                    /* Sage Green — status approved */
}

/* ── BASE ─────────────────────────────────────────────────── */
.hr-hero {
  position: relative;
  background: var(--hr-bg);
  font-family: var(--font-sans);
  overflow: hidden;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* ── NOISE TEXTURE ────────────────────────────────────────── */
.hr-hero::before {
  content: '';
  position: absolute; inset: 0; z-index: 0; pointer-events: none;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");
  background-size: 180px 180px;
  opacity: 0.5;
}

/* ── SLATE GLOW ───────────────────────────────────────────── */
.hr-glow-top {
  position: absolute; z-index: 0; pointer-events: none;
  top: -200px; left: 50%; transform: translateX(-50%);
  width: 900px; height: 500px; border-radius: 50%;
  background: radial-gradient(ellipse, rgba(83,104,120,0.07) 0%, transparent 65%);
  filter: blur(40px);
}
.hr-glow-side {
  position: absolute; z-index: 0; pointer-events: none;
  top: 20%; right: -200px;
  width: 600px; height: 600px; border-radius: 50%;
  background: radial-gradient(ellipse, rgba(83,104,120,0.05) 0%, transparent 65%);
  filter: blur(60px);
  animation: hr-drift 24s ease-in-out infinite alternate;
}
@keyframes hr-drift { from{transform:translate(0,0)} to{transform:translate(-30px,40px)} }

/* ── ISSUE WATERMARK ──────────────────────────────────────── */
.hr-issue {
  position: absolute;
  top: -40px; left: -30px; z-index: 0;
  font-family: var(--font-serif);
  font-size: clamp(260px, 35vw, 520px);
  font-weight: 400; line-height: 0.85;
  color: transparent;
  -webkit-text-stroke: 1px rgba(83,104,120,0.07);
  pointer-events: none; user-select: none; letter-spacing: -0.04em;
  will-change: transform;
}

/* ── TOP META BAR ─────────────────────────────────────────── */
.hr-meta-bar {
  position: relative; z-index: 10;
  display: flex; align-items: center; justify-content: space-between; gap: 16px;
  padding: 24px 0;
  border-bottom: 1px solid var(--hr-border);
  flex-wrap: wrap;
}
.hr-meta-left { display: flex; align-items: center; gap: 20px; }
.hr-meta-tag {
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--hr-accent); padding: 4px 10px;
  border: 1px solid var(--hr-border-acc); border-radius: 3px;
  background: var(--hr-accent-glow);
}
.hr-meta-sep { width: 1px; height: 14px; background: var(--hr-border); }
.hr-meta-txt {
  font-size: 10px; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase;
  color: var(--hr-fg-low);
}
.hr-meta-right { display: flex; align-items: center; gap: 16px; }
.hr-live-badge {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase;
  color: var(--hr-green); padding: 4px 10px;
  border: 1px solid rgba(72,140,96,0.25); border-radius: 3px;
  background: rgba(72,140,96,0.08);
}
.hr-live-dot {
  width: 5px; height: 5px; border-radius: 50%; background: var(--hr-green);
  animation: hr-pulse 2s ease-in-out infinite;
}
@keyframes hr-pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.3;transform:scale(0.6)} }
.hr-meta-num {
  font-size: 10px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;
  color: var(--hr-fg-low);
}

/* ── MAIN CONTENT ─────────────────────────────────────────── */
.hr-main {
  position: relative; z-index: 10; flex: 1;
  display: grid;
  grid-template-columns: 1fr 420px;
  gap: 0;
  padding: 0;
  border-bottom: 1px solid var(--hr-border);
}

/* left editorial column */
.hr-col-left {
  padding: 72px 60px 72px 0;
  border-right: 1px solid var(--hr-border);
  display: flex; flex-direction: column; justify-content: space-between;
}

/* overline */
.hr-overline {
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.24em; text-transform: uppercase;
  color: var(--hr-accent); margin-bottom: 24px; display: block;
}

/* headline */
.hr-title {
  font-family: var(--font-serif);
  font-size: clamp(52px, 7.5vw, 112px);
  font-weight: 400; line-height: 0.9; letter-spacing: -0.03em;
  color: var(--hr-fg); margin: 0 0 32px;
}
.hr-title .hr-t-accent { color: var(--hr-accent); font-style: italic; }
.hr-title .hr-t-dim    { color: rgba(10,10,10,0.18); }

/* desc */
.hr-desc {
  font-size: 16px; line-height: 1.84; color: var(--hr-fg-mid);
  max-width: 46ch; margin-bottom: 48px;
}
.hr-desc strong { color: var(--hr-fg); font-weight: 600; }

/* horizontal divider rule */
.hr-rule {
  display: flex; align-items: center; gap: 14px; margin-bottom: 40px;
}
.hr-rule-line { flex: 1; height: 1px; background: var(--hr-border); }
.hr-rule-txt {
  font-size: 9px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--hr-fg-low); white-space: nowrap;
}

/* ── ACTION BUTTONS ───────────────────────────────────────── */
.hr-actions { display: flex; gap: 12px; flex-wrap: wrap; }

/* PRIMARY — Onyx fill → hover tetap Onyx (gelap, bold) */
.btn-hr-primary {
  display: inline-flex; align-items: center; gap: 10px;
  background: var(--hr-fg); color: var(--hr-bg);
  padding: 15px 32px; border-radius: 6px;
  font-family: var(--font-sans); font-weight: 800;
  font-size: 12.5px; letter-spacing: 0.06em; text-transform: uppercase;
  text-decoration: none; white-space: nowrap; border: none;
  transition: background 0.25s ease, color 0.25s ease, transform 0.22s;
}
.btn-hr-primary:hover {
  background: var(--hr-accent); color: #ffffff; text-decoration: none;
  transform: translateY(-2px);
}
.btn-hr-primary i { transition: transform 0.22s; font-size: 12px; }
.btn-hr-primary:hover i { transform: translateX(4px); }

/* SECONDARY — Alabaster fill + Slate border → hover fill Slate */
.btn-hr-secondary {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--hr-bg); color: var(--hr-accent);
  padding: 14px 24px; border-radius: 6px;
  font-family: var(--font-sans); font-weight: 700;
  font-size: 12.5px; letter-spacing: 0.06em; text-transform: uppercase;
  text-decoration: none; white-space: nowrap;
  border: 1.5px solid var(--hr-accent);
  transition: background 0.25s ease, color 0.25s ease, border-color 0.25s ease;
}
.btn-hr-secondary:hover {
  background: var(--hr-accent); color: #ffffff; text-decoration: none;
  border-color: var(--hr-accent);
}
.btn-hr-secondary i { font-size: 11px; }

/* ── STAT STRIP ───────────────────────────────────────────── */
.hr-stat-strip {
  display: flex; gap: 0;
  border-top: 1px solid var(--hr-border);
  margin-top: 56px; padding-top: 32px;
}
.hr-stat-item {
  flex: 1; padding-right: 28px;
  border-right: 1px solid var(--hr-border);
}
.hr-stat-item:last-child { border-right: none; padding-right: 0; padding-left: 28px; }
.hr-stat-item + .hr-stat-item:not(:last-child) { padding-left: 28px; }
.hr-stat-num {
  font-family: var(--font-serif);
  font-size: 40px; font-weight: 400; color: var(--hr-fg);
  line-height: 1; display: block; letter-spacing: -0.02em;
}
.hr-stat-acc { color: var(--hr-accent); }
.hr-stat-lbl {
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase;
  color: var(--hr-fg-low); display: block; margin-top: 6px;
}
.hr-stat-sub { font-size: 12px; color: var(--hr-fg-low); display: block; margin-top: 3px; }

/* ── RIGHT COLUMN ─────────────────────────────────────────── */
.hr-col-right {
  padding: 40px 0 40px 40px;
  display: flex; flex-direction: column; gap: 12px;
  position: relative;
}

.hr-col-label {
  font-size: 9px; font-weight: 800; letter-spacing: 0.24em; text-transform: uppercase;
  color: var(--hr-fg-low); padding-bottom: 14px;
  border-bottom: 1px solid var(--hr-border); margin-bottom: 8px;
}

/* vehicle card */
.hr-vcard {
  background: var(--hr-surface);
  border: 1px solid var(--hr-border);
  border-radius: 0;
  padding: 18px 20px;
  display: flex; align-items: center; gap: 16px;
  transition: background 0.2s, border-color 0.2s, transform 0.22s;
  cursor: default; position: relative; overflow: hidden;
}
.hr-vcard::before {
  content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
  background: transparent;
  transition: background 0.22s;
}
.hr-vcard:hover {
  background: rgba(83,104,120,0.06);
  border-color: var(--hr-border-acc);
  transform: translateX(4px);
}
.hr-vcard:hover::before { background: var(--hr-accent); }

.hr-vcard-icon {
  width: 42px; height: 42px;
  border: 1px solid var(--hr-border);
  background: var(--hr-accent-glow);
  display: flex; align-items: center; justify-content: center;
  font-size: 18px; flex-shrink: 0;
  color: var(--hr-accent);
}
.hr-vcard-info { flex: 1; }
.hr-vcard-type { font-size: 13px; font-weight: 700; color: var(--hr-fg); line-height: 1.2; }
.hr-vcard-plat {
  font-family: ui-monospace, 'Courier New', monospace;
  font-size: 10.5px; color: var(--hr-fg-low); margin-top: 3px;
  letter-spacing: 0.08em;
}
.hr-badge {
  font-size: 9px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase;
  padding: 4px 10px; border-radius: 2px; white-space: nowrap;
  border: 1px solid transparent;
}
.hb-ok   { background: rgba(72,140,96,0.10);  color: var(--hr-green); border-color: rgba(72,140,96,0.22); }
.hb-wait { background: rgba(83,104,120,0.10); color: var(--hr-accent); border-color: var(--hr-border-acc); }
.hb-rev  { background: rgba(10,10,10,0.05);   color: var(--hr-fg-low); border-color: var(--hr-border); }

/* progress card */
.hr-prog-card {
  background: var(--hr-surface);
  border: 1px solid var(--hr-border-acc);
  padding: 20px;
}
.hr-prog-header {
  display: flex; justify-content: space-between; align-items: baseline;
  margin-bottom: 14px;
}
.hr-prog-label {
  font-size: 9px; font-weight: 800; letter-spacing: 0.2em; text-transform: uppercase;
  color: var(--hr-accent);
}
.hr-prog-val {
  font-family: var(--font-serif);
  font-size: 28px; font-weight: 400; color: var(--hr-fg);
  line-height: 1; letter-spacing: -0.02em;
}
.hr-prog-val span { font-size: 14px; color: var(--hr-fg-low); }
.hr-prog-track {
  height: 3px; background: rgba(10,10,10,0.08); overflow: hidden;
}
.hr-prog-fill {
  height: 100%;
  background: linear-gradient(90deg, #3d5566, var(--hr-accent), #7a9aad);
  width: 0; transition: width 1.8s cubic-bezier(0.23,1,0.32,1);
}
.hr-prog-sub { font-size: 10.5px; color: var(--hr-fg-low); margin-top: 10px; }

/* info card */
.hr-info-card {
  background: var(--hr-surface);
  border: 1px solid var(--hr-border);
  padding: 16px 20px;
  display: flex; gap: 14px; align-items: flex-start;
}
.hr-info-icon {
  width: 30px; height: 30px; flex-shrink: 0;
  background: var(--hr-accent-glow); border: 1px solid var(--hr-border-acc);
  display: flex; align-items: center; justify-content: center;
}
.hr-info-icon i { font-size: 11px; color: var(--hr-accent); }
.hr-info-title { font-size: 11px; font-weight: 700; color: var(--hr-fg); margin-bottom: 3px; }
.hr-info-text  { font-size: 11px; line-height: 1.6; color: var(--hr-fg-low); margin: 0; }

/* ── BOTTOM BAR ───────────────────────────────────────────── */
.hr-bottom-bar {
  position: relative; z-index: 10;
  border-top: 1px solid var(--hr-border);
}
.hr-bottom-inner { display: flex; }
.hr-bottom-cell {
  flex: 1; padding: 28px 36px;
  border-right: 1px solid var(--hr-border);
  transition: background 0.2s;
  cursor: default;
}
.hr-bottom-cell:first-child { padding-left: 0; }
.hr-bottom-cell:last-child  { border-right: none; }
.hr-bottom-cell:hover { background: rgba(83,104,120,0.05); }
.hr-bottom-cell-label {
  font-size: 8.5px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--hr-fg-low); margin-bottom: 8px; display: block;
}
.hr-bottom-cell-num {
  font-family: var(--font-serif);
  font-size: 38px; font-weight: 400; color: var(--hr-fg);
  line-height: 1; display: block; letter-spacing: -0.02em;
}
.hr-bottom-cell-num .cell-acc { color: var(--hr-accent); }
.hr-bottom-cell-sub { font-size: 11px; color: var(--hr-fg-low); margin-top: 5px; display: block; }

/* ── SCAN LINE ────────────────────────────────────────────── */
.hr-scanline {
  position: absolute; top: 0; left: 0; right: 0; height: 1px;
  background: linear-gradient(90deg, transparent, rgba(83,104,120,0.18), transparent);
  z-index: 2; pointer-events: none;
  animation: hr-scan 8s ease-in-out infinite;
}
@keyframes hr-scan {
  0%   { top: -2px; opacity: 0; }
  5%   { opacity: 1; }
  95%  { opacity: 0.6; }
  100% { top: 100%; opacity: 0; }
}

/* ── RESPONSIVE ──────────────────────────────────────────── */
@media (max-width: 1100px) {
  .hr-main { grid-template-columns: 1fr 360px; }
  .hr-col-left { padding-right: 40px; }
}
@media (max-width: 992px) {
  .hr-main { grid-template-columns: 1fr; }
  .hr-col-left { padding: 56px 0; border-right: none; border-bottom: 1px solid var(--hr-border); }
  .hr-col-right { padding: 40px 0; }
  .hr-bottom-inner { flex-wrap: wrap; }
  .hr-bottom-cell { min-width: 50%; }
  .hr-bottom-cell:nth-child(2) { border-right: none; }
  .hr-bottom-cell:nth-child(3) { border-top: 1px solid var(--hr-border); border-right: none; padding-left: 0; }
  .hr-bottom-cell:last-child   { border-top: 1px solid var(--hr-border); }
  .hr-stat-strip { flex-direction: column; gap: 20px; }
  .hr-stat-item { border-right: none; padding: 0 !important; }
}
@media (max-width: 576px) {
  .hr-title { font-size: 52px; }
  .hr-bottom-cell { min-width: 100%; border-right: none; border-top: 1px solid var(--hr-border); }
  .hr-bottom-cell:first-child { border-top: none; }
}

/* ── REDUCED MOTION ──────────────────────────────────────── */
@media (prefers-reduced-motion: reduce) {
  .hr-live-dot, .hr-scanline, .hr-glow-side { animation: none !important; }
  .hr-vcard:hover, .btn-hr-primary:hover, .btn-hr-secondary:hover { transform: none !important; }
  .hr-prog-fill { transition: none !important; }
}
</style>

<section class="hr-hero" id="beranda" aria-label="Beranda — Stemba Parking SMKN 7 Semarang">

  <div class="hr-glow-top"  aria-hidden="true"></div>
  <div class="hr-glow-side" aria-hidden="true"></div>
  <div class="hr-scanline"  aria-hidden="true"></div>
  <div class="hr-issue"     aria-hidden="true" id="hr-issue">01</div>

  <div class="container" style="position:relative;z-index:10;flex:1;display:flex;flex-direction:column;">

    <!-- MAIN GRID -->
    <div class="hr-main">

      <!-- LEFT -->
      <div class="hr-col-left">
        <div>
          <span class="hr-overline" data-aos="fade-up" data-aos-duration="500">
            Sistem Pendataan Kendaraan &nbsp;—&nbsp; Edisi 2026
          </span>

          <h1 class="hr-title" data-aos="fade-up" data-aos-delay="60" data-aos-duration="800">
            Parkir<br>
            Lebih <span class="hr-t-accent">Tertib,</span><br>
            <span class="hr-t-dim">Lebih Aman</span>
          </h1>

          <p class="hr-desc" data-aos="fade-up" data-aos-delay="130" data-aos-duration="700">
            Platform pendataan kendaraan siswa <strong>terpusat dan terverifikasi</strong>
            untuk SMKN 7 Semarang. Daftar online, pantau status persetujuan,
            dan kelola semua data kendaraan dalam satu sistem.
          </p>

          <div class="hr-rule" data-aos="fade-up" data-aos-delay="180" data-aos-duration="500">
            <span class="hr-rule-line" aria-hidden="true"></span>
            <span class="hr-rule-txt">Mulai dari sini</span>
            <span class="hr-rule-line" aria-hidden="true"></span>
          </div>

          <div class="hr-actions" data-aos="fade-up" data-aos-delay="220" data-aos-duration="700">
            <a class="btn-hr-primary" href="dashboard/kendaraan.php">
              Daftar Kendaraan
              <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
            <a class="btn-hr-secondary" href="dashboard/dashboard-user.php">
              <i class="fa-solid fa-lock" aria-hidden="true"></i>
              Masuk Dashboard
            </a>
          </div>
        </div>

        <!-- stat strip -->
        <div class="hr-stat-strip" data-aos="fade-up" data-aos-delay="280" data-aos-duration="700">
          <div class="hr-stat-item">
            <span class="hr-stat-num count" data-target="247">0</span>
            <span class="hr-stat-lbl">Kendaraan Terdaftar</span>
            <span class="hr-stat-sub">Motor &amp; mobil</span>
          </div>
          <div class="hr-stat-item">
            <span class="hr-stat-num count hr-stat-acc" data-target="92" data-suffix="%">0</span>
            <span class="hr-stat-lbl">Tingkat Persetujuan</span>
            <span class="hr-stat-sub">Dari total pendaftar</span>
          </div>
          <div class="hr-stat-item">
            <span class="hr-stat-num count" data-target="24" data-suffix="/7">0</span>
            <span class="hr-stat-lbl">Layanan Aktif</span>
            <span class="hr-stat-sub">Akses kapan saja</span>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- BOTTOM BAR -->
  <div class="hr-bottom-bar" data-aos="fade-up" data-aos-delay="320" data-aos-duration="700">
    <div class="container">
      <div class="hr-bottom-inner">
        <div class="hr-bottom-cell">
          <span class="hr-bottom-cell-label">Pendaftaran Online</span>
          <span class="hr-bottom-cell-num">Cepat <span class="cell-acc">&amp;</span> Mudah</span>
          <span class="hr-bottom-cell-sub">Upload dokumen dari rumah, kapan saja</span>
        </div>
        <div class="hr-bottom-cell">
          <span class="hr-bottom-cell-label">Verifikasi Dokumen</span>
          <span class="hr-bottom-cell-num">SIM <span class="cell-acc">&amp;</span> KTP</span>
          <span class="hr-bottom-cell-sub">Sistem persetujuan berjenjang oleh admin</span>
        </div>
        <div class="hr-bottom-cell">
          <span class="hr-bottom-cell-label">Status Kendaraan</span>
          <span class="hr-bottom-cell-num">Real<span class="cell-acc">-</span>time</span>
          <span class="hr-bottom-cell-sub">Pantau via dashboard siswa kapan saja</span>
        </div>
        <div class="hr-bottom-cell">
          <span class="hr-bottom-cell-label">Data Terpusat</span>
          <span class="hr-bottom-cell-num">100<span class="cell-acc">%</span></span>
          <span class="hr-bottom-cell-sub">Aman tersimpan di database sekolah</span>
        </div>
      </div>
    </div>
  </div>

</section>

<script>
(function () {
  'use strict';
  var noMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  document.addEventListener('DOMContentLoaded', function () {
    if (typeof AOS !== 'undefined') {
      AOS.init({ once: true, offset: 50, easing: 'ease-out-quart', duration: 700 });
    }
    initCounters();
    initProgress();
    if (!noMotion) initParallax();
  });

  function initCounters() {
    var els = document.querySelectorAll('#beranda .count');
    if (!els.length) return;
    function run(el) {
      var target = +(el.dataset.target || 0), suffix = el.dataset.suffix || '';
      var t0 = performance.now();
      (function tick(now) {
        var p = Math.min((now - t0) / 1800, 1), e = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.round(target * e) + suffix;
        if (p < 1) requestAnimationFrame(tick); else el.textContent = target + suffix;
      })(t0);
    }
    if ('IntersectionObserver' in window) {
      var obs = new IntersectionObserver(function(entries, o) {
        entries.forEach(function(en) { if (en.isIntersecting) { run(en.target); o.unobserve(en.target); } });
      }, { threshold: 0.4 });
      els.forEach(function(el) { obs.observe(el); });
    } else { els.forEach(run); }
  }

  function initProgress() {
    var fill = document.getElementById('hr-prog-fill');
    var val  = document.getElementById('hr-prog-val');
    if (!fill) return;
    var target = +(fill.dataset.target || 0);
    function run() {
      var t0 = performance.now();
      (function tick(now) {
        var p = Math.min((now - t0) / 1600, 1), e = 1 - Math.pow(1 - p, 3), v = Math.round(target * e);
        fill.style.width = v + '%';
        if (val) val.innerHTML = v + '<span>%</span>';
        if (p < 1) requestAnimationFrame(tick);
        else { fill.style.width = target + '%'; if (val) val.innerHTML = target + '<span>%</span>'; }
      })(t0);
    }
    if ('IntersectionObserver' in window) {
      var obs = new IntersectionObserver(function(entries, o) {
        entries.forEach(function(en) { if (en.isIntersecting) { run(); o.unobserve(en.target); } });
      }, { threshold: 0.4 });
      obs.observe(fill);
    } else { run(); }
  }

  function initParallax() {
    var issue = document.getElementById('hr-issue');
    if (!issue) return;
    var ticking = false;
    window.addEventListener('scroll', function () {
      if (!ticking) {
        requestAnimationFrame(function () {
          issue.style.transform = 'translateY(' + (window.scrollY * 0.1) + 'px)';
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  }

})();
</script>