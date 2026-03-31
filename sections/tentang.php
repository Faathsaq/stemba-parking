<?php
// sections/about.php — v3 ALABASTER EDITION
// FRAGMENT: jangan tambahkan <html>, <head>, atau <body> di file ini.
// Section Tentang — Stemba Parking · SMKN 7 Semarang
// Dependensi: Bootstrap 5, AOS, Font Awesome 6, Google Fonts
?>

<style>
/* ============================================================
   ABOUT — ALABASTER + ONYX + BLUE SLATE
   ============================================================ */

.ab {
  position: relative;
  background: #fffdf7;
  font-family: var(--font-sans, 'Outfit', sans-serif);
  overflow: hidden;
}

.ab {
  --ab-accent:      #536878;                    /* Blue Slate */
  --ab-accent-dim:  rgba(83,104,120,0.18);
  --ab-accent-glow: rgba(83,104,120,0.07);
  --ab-bg:          #fffdf7;                    /* Cream / Ivory */
  --ab-surface:     #f5f2ea;                    /* Warm Cream *//
  --ab-fg:          #0a0a0a;                    /* Onyx */
  --ab-fg-mid:      rgba(10,10,10,0.55);
  --ab-fg-low:      rgba(10,10,10,0.35);
  --ab-green:       #488c60;                    /* Sage Green */
  --ab-border:      rgba(10,10,10,0.09);
  --ab-border-acc:  rgba(83,104,120,0.28);
}

/* ── INTRO ─────────────────────────────────────────────────── */
.ab-intro {
  position: relative;
  padding: 120px 0 100px;
  overflow: hidden;
}

.ab-watermark {
  position: absolute;
  top: -30px; right: -10px;
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(140px, 20vw, 280px);
  font-weight: 400;
  color: transparent;
  -webkit-text-stroke: 1px rgba(83,104,120,0.07);
  line-height: 1;
  pointer-events: none; user-select: none;
  z-index: 0; white-space: nowrap;
  will-change: transform;
}

.ab-grid {
  position: absolute; inset: 0; z-index: 0;
  background-image:
    linear-gradient(rgba(10,10,10,0.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(10,10,10,0.04) 1px, transparent 1px);
  background-size: 60px 60px;
  pointer-events: none;
}

/* slate glow blob */
.ab-blob {
  position: absolute; border-radius: 50%;
  filter: blur(80px); pointer-events: none; z-index: 0;
}
.ab-blob-1 {
  width: 480px; height: 480px; top: -100px; left: -140px;
  background: radial-gradient(circle, rgba(83,104,120,0.07), transparent 65%);
  animation: ab-float-a 18s ease-in-out infinite alternate;
}
.ab-blob-2 {
  width: 340px; height: 340px; bottom: -80px; right: -100px;
  background: radial-gradient(circle, rgba(83,104,120,0.05), transparent 65%);
  animation: ab-float-b 22s ease-in-out infinite alternate;
}
@keyframes ab-float-a { from{transform:translate(0,0) scale(1)} to{transform:translate(32px,22px) scale(1.06)} }
@keyframes ab-float-b { from{transform:translate(0,0) scale(1)} to{transform:translate(-24px,-16px) scale(1.04)} }

.ab-intro .container { position: relative; z-index: 10; }

/* chip — slate */
.ab-chip {
  display: inline-flex; align-items: center; gap: 8px;
  border: 1px solid var(--ab-border-acc); border-radius: 999px;
  padding: 7px 16px; margin-bottom: 32px;
  background: var(--ab-accent-glow);
}
.ab-chip-dot {
  width: 7px; height: 7px; border-radius: 50%; background: var(--ab-accent);
  animation: ab-pulse 2.4s ease-in-out infinite;
}
@keyframes ab-pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.3;transform:scale(0.7)} }
.ab-chip span {
  font-size: 11px; font-weight: 700; letter-spacing: 0.1em;
  text-transform: uppercase; color: var(--ab-accent);
}

/* headline */
.ab-intro-title {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(36px, 5.2vw, 68px);
  font-weight: 400; line-height: 1.06;
  color: var(--ab-fg); margin: 0; letter-spacing: -1px;
}
.ab-intro-title em { font-style: italic; color: var(--ab-accent); }

/* marquee — slate tint */
.ab-marquee-wrap {
  width: 100%; overflow: hidden;
  border-top: 1px solid var(--ab-border-acc);
  border-bottom: 1px solid var(--ab-border-acc);
  padding: 13px 0; margin: 48px 0;
  background: var(--ab-accent-glow);
}
.ab-marquee-track {
  display: flex; width: max-content;
  animation: ab-marquee 28s linear infinite;
}
@keyframes ab-marquee { from{transform:translateX(0)} to{transform:translateX(-50%)} }
.ab-marquee-item {
  display: inline-flex; align-items: center; gap: 12px;
  padding: 0 28px; font-size: 11.5px; font-weight: 700;
  letter-spacing: 0.1em; text-transform: uppercase;
  color: rgba(83,104,120,0.6); white-space: nowrap;
  border-right: 1px solid var(--ab-border-acc);
}
.ab-marquee-item i { color: rgba(83,104,120,0.4); font-size: 10px; }

/* body text */
.ab-intro-body {
  font-size: 17px; line-height: 1.84;
  color: var(--ab-fg-mid); max-width: 58ch;
}
.ab-intro-body strong { color: var(--ab-fg); font-weight: 600; }

/* ── SHARED EYEBROW ─────────────────────────────────────────── */
.ab-eyebrow-row { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
.ab-eyebrow-line { width: 28px; height: 1px; background: var(--ab-accent); opacity: 0.5; }
.ab-eyebrow-row span {
  font-size: 11px; font-weight: 700; letter-spacing: 0.14em;
  text-transform: uppercase; color: var(--ab-accent);
}
.ab-section-title {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(28px, 3.6vw, 46px);
  font-weight: 400; line-height: 1.1;
  color: var(--ab-fg); margin-bottom: 52px; letter-spacing: -0.5px;
}
.ab-section-title em { font-style: italic; color: var(--ab-accent); }

/* ── PROBLEMS ────────────────────────────────────────────────── */
.ab-problems {
  position: relative; padding: 100px 0; background: var(--ab-surface);
}
.ab-problems::before, .ab-problems::after {
  content: ''; position: absolute; left: 0; right: 0; height: 1px;
  background: linear-gradient(90deg, transparent, rgba(83,104,120,0.25), transparent);
}
.ab-problems::before { top: 0; }
.ab-problems::after  { bottom: 0; }

.prob-card {
  position: relative; background: var(--ab-bg);
  border: 1px solid var(--ab-border); border-radius: 2px;
  padding: 36px 26px; height: 100%; overflow: hidden;
  transition: transform 0.32s cubic-bezier(0.23,1,0.32,1),
              border-color 0.3s, background 0.3s;
  cursor: default;
}
.prob-card::after {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
  background: linear-gradient(90deg, transparent, var(--ab-accent), transparent);
  transform: scaleX(0); transform-origin: left; transition: transform 0.35s ease;
}
.prob-card::before {
  content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 0;
  background: var(--ab-accent);
  transition: width 0.25s ease;
}
.prob-card:hover {
  transform: translateY(-10px);
  border-color: var(--ab-border-acc);
  background: rgba(83,104,120,0.04);
}
.prob-card:hover::after  { transform: scaleX(1); }
.prob-card:hover::before { width: 3px; }

.prob-num {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: 54px; font-weight: 400;
  color: rgba(83,104,120,0.10); line-height: 1;
  margin-bottom: 18px; display: block;
  transition: color 0.3s;
}
.prob-card:hover .prob-num { color: rgba(83,104,120,0.22); }

.prob-icon-wrap {
  width: 46px; height: 46px; border-radius: 0;
  border: 1px solid var(--ab-border-acc);
  background: var(--ab-accent-glow);
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 18px;
  transition: background 0.3s, transform 0.3s;
}
.prob-icon-wrap i {
  font-size: 18px; color: var(--ab-accent);
  transition: opacity 0.3s;
}
.prob-card:hover .prob-icon-wrap {
  background: var(--ab-accent-dim);
  transform: rotate(-8deg) scale(1.12);
}

.prob-title { font-size: 16px; font-weight: 700; color: var(--ab-fg); margin-bottom: 10px; }
.prob-desc  { font-size: 13.5px; line-height: 1.74; color: var(--ab-fg-low); margin: 0; }

/* ── SOLUTION ─────────────────────────────────────────────────── */
.ab-solution {
  position: relative; padding: 100px 0; background: var(--ab-bg); overflow: hidden;
}
.ab-sol-bg-text {
  position: absolute; bottom: -40px; left: -10px;
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(90px, 13vw, 190px); font-weight: 400;
  color: transparent; -webkit-text-stroke: 1px rgba(83,104,120,0.06);
  line-height: 1; pointer-events: none; user-select: none;
  white-space: nowrap; will-change: transform;
}

/* feature list — slate */
.ab-feat-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 2px; }
.ab-feat-list li {
  display: flex; align-items: flex-start; gap: 14px;
  padding: 16px 18px;
  border: 1px solid var(--ab-border);
  border-radius: 0;
  background: var(--ab-surface);
  font-size: 14px; color: var(--ab-fg-mid); line-height: 1.64;
  transition: background 0.22s, border-color 0.22s, transform 0.22s, color 0.22s;
  position: relative;
}
.ab-feat-list li::before {
  content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 0;
  background: var(--ab-accent); transition: width 0.22s ease;
}
.ab-feat-list li:hover {
  background: rgba(83,104,120,0.06); border-color: var(--ab-border-acc);
  transform: translateX(7px); color: var(--ab-fg);
}
.ab-feat-list li:hover::before { width: 3px; }
.ab-feat-list li strong { color: var(--ab-fg); font-weight: 600; }

.ab-feat-icon {
  width: 30px; height: 30px; border-radius: 0;
  background: var(--ab-accent-glow); border: 1px solid var(--ab-border-acc);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; margin-top: 1px;
  transition: background 0.22s;
}
.ab-feat-icon i { font-size: 13px; color: var(--ab-accent); transition: opacity 0.22s; }
.ab-feat-list li:hover .ab-feat-icon { background: var(--ab-accent-dim); }

/* compare table */
.ab-compare {
  background: var(--ab-surface); border: 1px solid var(--ab-border);
  border-radius: 0; overflow: hidden;
}
.ab-compare-head {
  display: grid; grid-template-columns: 1.1fr 1fr 1fr;
  background: rgba(83,104,120,0.08);
  border-bottom: 1px solid var(--ab-border-acc);
}
.ab-compare-head div {
  padding: 12px 16px; font-size: 9.5px; font-weight: 800;
  letter-spacing: 0.16em; text-transform: uppercase;
  color: var(--ab-accent);
  border-right: 1px solid var(--ab-border-acc);
}
.ab-compare-head div:last-child { border-right: none; }
.ab-compare-row {
  display: grid; grid-template-columns: 1.1fr 1fr 1fr;
  border-bottom: 1px solid var(--ab-border);
  transition: background 0.18s;
}
.ab-compare-row:last-child { border-bottom: none; }
.ab-compare-row:hover { background: rgba(83,104,120,0.04); }
.ab-compare-row div {
  padding: 13px 16px; font-size: 13px; line-height: 1.5;
  color: var(--ab-fg-low); border-right: 1px solid var(--ab-border);
  display: flex; align-items: center; gap: 8px;
}
.ab-compare-row div:last-child { border-right: none; }
.ab-compare-row div:first-child { color: var(--ab-fg); font-weight: 600; font-size: 12.5px; }

.ci-bad { color: #c0392b; font-size: 12px; }
.ci-ok  { color: var(--ab-green); font-size: 12px; }
.ci-mid { color: var(--ab-accent); font-size: 12px; }

/* ── STAKEHOLDERS ─────────────────────────────────────────────── */
.ab-stakeholders {
  position: relative; padding: 100px 0 120px; background: var(--ab-surface); overflow: hidden;
}
.ab-stakeholders::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
  background: linear-gradient(90deg, transparent, rgba(83,104,120,0.25), transparent);
}

.ab-stake-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }

.stake-card {
  position: relative; background: var(--ab-bg);
  border: 1px solid var(--ab-border); border-radius: 0;
  padding: 36px 28px; overflow: hidden;
  transition: transform 0.32s cubic-bezier(0.23,1,0.32,1),
              border-color 0.3s, background 0.3s;
  cursor: default;
}
.stake-card::after {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 0;
  background: var(--ab-accent);
  transition: height 0.25s ease;
}
.stake-card:hover {
  transform: translateY(-10px);
  border-color: var(--ab-border-acc);
  background: rgba(83,104,120,0.04);
}
.stake-card:hover::after { height: 3px; }

.stake-bg-num {
  position: absolute; bottom: -12px; right: 18px;
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: 96px; font-weight: 400; color: transparent;
  -webkit-text-stroke: 1px rgba(83,104,120,0.08);
  line-height: 1; pointer-events: none; user-select: none;
  transition: -webkit-text-stroke-color 0.3s;
}
.stake-card:hover .stake-bg-num { -webkit-text-stroke-color: rgba(83,104,120,0.18); }

.stake-icon-wrap {
  width: 54px; height: 54px; border-radius: 0;
  border: 1px solid var(--ab-border-acc);
  background: var(--ab-accent-glow);
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 22px;
  transition: transform 0.3s, background 0.3s;
}
.stake-icon-wrap i {
  font-size: 22px; color: var(--ab-accent);
  transition: opacity 0.3s;
}
.stake-card:hover .stake-icon-wrap {
  transform: rotate(-8deg) scale(1.15);
  background: var(--ab-accent-dim);
}

.stake-role {
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.2em;
  text-transform: uppercase; color: var(--ab-accent); opacity: 0.6; margin-bottom: 8px;
}
.stake-name {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: 26px; font-weight: 400; color: var(--ab-fg); margin-bottom: 14px; line-height: 1.15;
}
.stake-desc {
  font-size: 13.5px; line-height: 1.76; color: var(--ab-fg-low); margin: 0; position: relative; z-index: 1;
}

/* CTA strip */
.ab-cta-strip {
  margin-top: 72px; padding: 40px 48px;
  background: rgba(83,104,120,0.06); border: 1px solid var(--ab-border-acc);
  border-radius: 0; display: flex; align-items: center;
  justify-content: space-between; gap: 24px; flex-wrap: wrap;
  position: relative; overflow: hidden;
  transition: border-color 0.3s, background 0.3s;
}
.ab-cta-strip::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
  background: linear-gradient(90deg, transparent, var(--ab-accent), transparent);
}
.ab-cta-strip:hover { background: rgba(83,104,120,0.09); }
.ab-cta-text {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(20px, 2.4vw, 28px); font-weight: 400;
  color: var(--ab-fg); line-height: 1.3;
}
.ab-cta-text em { font-style: italic; color: var(--ab-accent); }

/* PRIMARY button — Onyx fill → hover Blue Slate */
.btn-ab-primary {
  display: inline-flex; align-items: center; gap: 10px;
  background: var(--ab-fg); color: var(--ab-bg);
  padding: 14px 30px; border-radius: 6px;
  font-family: var(--font-sans, 'Outfit', sans-serif);
  font-weight: 800; font-size: 13px; letter-spacing: 0.06em;
  text-transform: uppercase; text-decoration: none;
  white-space: nowrap; flex-shrink: 0; border: none;
  transition: background 0.25s ease, color 0.25s ease, transform 0.22s;
}
.btn-ab-primary:hover {
  background: var(--ab-accent); color: #fff; text-decoration: none;
  transform: translateY(-2px);
}
.btn-ab-primary i { transition: transform 0.22s; font-size: 11px; }
.btn-ab-primary:hover i { transform: translateX(4px); }

/* ── SECTION DIVIDER ──────────────────────────────────────────── */
.ab-section-divider {
  position: relative; height: 1px; background: transparent;
  overflow: visible; margin: 0; z-index: 10;
}
.ab-section-divider::before {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(90deg, transparent, rgba(83,104,120,0.3), transparent);
}
.ab-divider-label {
  position: absolute; top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  background: var(--ab-bg);
  border: 1px solid var(--ab-border-acc);
  padding: 6px 20px;
  font-family: var(--font-sans, 'Outfit', sans-serif);
  font-size: 9px; font-weight: 800;
  letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--ab-accent); white-space: nowrap;
}

/* ── IDENTITY BAR ─────────────────────────────────────────────── */
.ab-identity-bar {
  display: flex; align-items: center; gap: 16px;
  padding-bottom: 24px;
  border-bottom: 1px solid var(--ab-border);
  margin-bottom: 36px;
}
.ab-identity-tag {
  display: inline-flex; align-items: center; gap: 8px;
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--ab-accent); padding: 5px 12px;
  border: 1px solid var(--ab-border-acc); border-radius: 3px;
  background: var(--ab-accent-glow);
}
.ab-identity-tag i { font-size: 10px; }
.ab-identity-sep { width: 1px; height: 14px; background: var(--ab-border); }
.ab-identity-desc {
  font-size: 11px; font-weight: 600; letter-spacing: 0.08em;
  color: var(--ab-fg-low);
}

/* ── RESPONSIVE ───────────────────────────────────────────────── */
@media (max-width: 992px) {
  .ab-stake-grid { grid-template-columns: 1fr; max-width: 480px; margin: 0 auto; }
  .ab-cta-strip  { flex-direction: column; text-align: center; }
}
@media (max-width: 768px) {
  .ab-intro, .ab-problems, .ab-solution, .ab-stakeholders { padding: 70px 0; }
  .ab-marquee-wrap { margin: 32px 0; }
  .ab-watermark { font-size: 120px; }
}

/* ── REDUCED MOTION ───────────────────────────────────────────── */
@media (prefers-reduced-motion: reduce) {
  .ab-blob-1, .ab-blob-2, .ab-marquee-track, .ab-chip-dot { animation: none !important; }
  .prob-card:hover, .stake-card:hover,
  .ab-feat-list li:hover, .btn-ab-primary:hover { transform: none !important; }
  .prob-card::after, .prob-card::before { transition: none !important; }
}
</style>

<!-- ══ SECTION DIVIDER ═══════════════════════════════════════ -->
<div class="ab-section-divider" aria-hidden="true">
  <span class="ab-divider-label">
    <i class="fa-solid fa-chevron-down" style="font-size:8px;margin-right:6px;"></i>
    Selanjutnya · Tentang Sistem
  </span>
</div>

<section id="tentang" class="ab" aria-label="Tentang Stemba Parking">

  <!-- ══ INTRO ══════════════════════════════════════════════════ -->
  <div class="ab-intro">
    <div class="ab-watermark" aria-hidden="true" id="ab-wm">Tentang</div>
    <div class="ab-grid"     aria-hidden="true"></div>
    <div class="ab-blob ab-blob-1" aria-hidden="true"></div>
    <div class="ab-blob ab-blob-2" aria-hidden="true"></div>

    <div class="container">

      <!-- identity bar -->
      <div class="ab-identity-bar" data-aos="fade-up" data-aos-duration="500">
        <span class="ab-identity-tag">
          <i class="fa-solid fa-circle-info"></i>
          Tentang Sistem
        </span>
        <span class="ab-identity-sep" aria-hidden="true"></span>
        <span class="ab-identity-desc">Latar belakang, masalah, solusi, dan pihak yang terlibat</span>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <h2 class="ab-intro-title"
              data-aos="fade-up" data-aos-delay="80" data-aos-duration="800">
            Parkir sekolah lebih dari<br>
            sekadar <em>tempat menaruh kendaraan</em>
          </h2>
        </div>
      </div>

      <!-- marquee -->
      <div class="ab-marquee-wrap"
           data-aos="fade-up" data-aos-delay="160" data-aos-duration="600"
           aria-hidden="true">
        <div class="ab-marquee-track">
          <span class="ab-marquee-item"><i class="fa-solid fa-shield-halved"></i> Keamanan Kendaraan</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-database"></i> Pendataan Terpusat</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-file-circle-check"></i> Verifikasi Dokumen</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-circle-check"></i> Persetujuan Digital</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-school"></i> SMKN 7 Semarang</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-square-parking"></i> Stemba Parking</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-users"></i> MPK &amp; OSIS</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-chart-line"></i> Real-time Status</span>
          <!-- duplicate -->
          <span class="ab-marquee-item"><i class="fa-solid fa-shield-halved"></i> Keamanan Kendaraan</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-database"></i> Pendataan Terpusat</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-file-circle-check"></i> Verifikasi Dokumen</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-circle-check"></i> Persetujuan Digital</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-school"></i> SMKN 7 Semarang</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-square-parking"></i> Stemba Parking</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-users"></i> MPK &amp; OSIS</span>
          <span class="ab-marquee-item"><i class="fa-solid fa-chart-line"></i> Real-time Status</span>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-7"
             data-aos="fade-up" data-aos-delay="200" data-aos-duration="700">
          <p class="ab-intro-body">
            Jumlah kendaraan siswa yang masuk ke area sekolah terus bertambah setiap tahun.
            Tanpa sistem yang tertib, parkir sekolah rentan terhadap <strong>kepadatan,
            kehilangan, dan kesulitan identifikasi kepemilikan</strong> saat terjadi insiden.
            Pengelolaan yang baik bukan sekadar ketertiban — ini soal <strong>keamanan dan
            tanggung jawab bersama</strong> antara siswa, sekolah, MPK, dan OSIS.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- ══ PROBLEMS ═══════════════════════════════════════════════ -->
  <div class="ab-problems">
    <div class="container">

      <div data-aos="fade-up" data-aos-duration="600">
        <div class="ab-eyebrow-row">
          <span class="ab-eyebrow-line" aria-hidden="true"></span>
          <span>Masalah yang Ada</span>
        </div>
        <h3 class="ab-section-title">
          Sistem lama <em>tidak cukup</em><br>untuk kebutuhan sekarang
        </h3>
      </div>

      <div class="row g-4">

        <div class="col-md-4" data-aos="fade-up" data-aos-delay="0" data-aos-duration="700">
          <div class="prob-card">
            <span class="prob-num" aria-hidden="true">01</span>
            <div class="prob-icon-wrap">
              <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
            </div>
            <div class="prob-title">Pencatatan Manual</div>
            <p class="prob-desc">Data dicatat tangan di buku atau kertas lepas. Rawan hilang, sulit dicari, dan tidak bisa diakses bersamaan oleh beberapa pihak.</p>
          </div>
        </div>

        <div class="col-md-4" data-aos="fade-up" data-aos-delay="120" data-aos-duration="700">
          <div class="prob-card">
            <span class="prob-num" aria-hidden="true">02</span>
            <div class="prob-icon-wrap">
              <i class="fa-brands fa-google" aria-hidden="true"></i>
            </div>
            <div class="prob-title">Google Form Tanpa Alur</div>
            <p class="prob-desc">Formulir digital tanpa validasi, tanpa sistem persetujuan, tanpa notifikasi. Data menumpuk tanpa tindak lanjut yang jelas.</p>
          </div>
        </div>

        <div class="col-md-4" data-aos="fade-up" data-aos-delay="240" data-aos-duration="700">
          <div class="prob-card">
            <span class="prob-num" aria-hidden="true">03</span>
            <div class="prob-icon-wrap">
              <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            </div>
            <div class="prob-title">Tidak Ada Verifikasi</div>
            <p class="prob-desc">Tidak ada proses verifikasi dokumen seperti SIM dan kartu pelajar. Siapa saja bisa mendaftar tanpa konfirmasi identitas.</p>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- ══ SOLUTION ════════════════════════════════════════════════ -->
  <div class="ab-solution">
    <div class="ab-sol-bg-text" aria-hidden="true" id="ab-sol-bg">Solusi</div>
    <div class="container" style="position:relative;z-index:1;">

      <div class="row align-items-start g-5">

        <div class="col-lg-5" data-aos="fade-right" data-aos-duration="800">

          <div class="ab-eyebrow-row mb-3">
            <span class="ab-eyebrow-line"></span>
            <span>Solusi Kami</span>
          </div>
          <h3 class="ab-section-title mb-4">
            Sistem terkomputerisasi<br><em>terpusat &amp; aman</em>
          </h3>

          <ul class="ab-feat-list">
            <li data-aos="fade-right" data-aos-delay="60" data-aos-duration="600">
              <span class="ab-feat-icon">
                <i class="fa-solid fa-mobile-screen-button" aria-hidden="true"></i>
              </span>
              <span><strong>Pendaftaran online</strong> — upload dokumen langsung dari rumah, kapan saja</span>
            </li>
            <li data-aos="fade-right" data-aos-delay="140" data-aos-duration="600">
              <span class="ab-feat-icon">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
              </span>
              <span><strong>Persetujuan berjenjang</strong> — admin sekolah memverifikasi setiap data masuk</span>
            </li>
            <li data-aos="fade-right" data-aos-delay="220" data-aos-duration="600">
              <span class="ab-feat-icon">
                <i class="fa-solid fa-gauge-high" aria-hidden="true"></i>
              </span>
              <span><strong>Dashboard real-time</strong> — status kendaraan bisa dipantau kapan saja</span>
            </li>
            <li data-aos="fade-right" data-aos-delay="300" data-aos-duration="600">
              <span class="ab-feat-icon">
                <i class="fa-solid fa-lock" aria-hidden="true"></i>
              </span>
              <span><strong>Data aman &amp; terpusat</strong> — tidak ada data tercecer atau duplikat lagi</span>
            </li>
          </ul>

        </div>

        <div class="col-lg-7" data-aos="fade-left" data-aos-delay="120" data-aos-duration="800">
          <div class="ab-compare">
            <div class="ab-compare-head">
              <div>Aspek</div><div>Sistem Lama</div><div>Stemba Parking</div>
            </div>
            <div class="ab-compare-row">
              <div>Pendaftaran</div>
              <div><i class="fa-solid fa-xmark ci-bad"></i> Manual / Google Form</div>
              <div><i class="fa-solid fa-check ci-ok"></i> Online terstruktur</div>
            </div>
            <div class="ab-compare-row">
              <div>Verifikasi Dokumen</div>
              <div><i class="fa-solid fa-xmark ci-bad"></i> Tidak ada</div>
              <div><i class="fa-solid fa-check ci-ok"></i> Upload SIM &amp; KTP Pelajar</div>
            </div>
            <div class="ab-compare-row">
              <div>Alur Persetujuan</div>
              <div><i class="fa-solid fa-xmark ci-bad"></i> Tidak ada</div>
              <div><i class="fa-solid fa-check ci-ok"></i> Berjenjang oleh admin</div>
            </div>
            <div class="ab-compare-row">
              <div>Akses Data</div>
              <div><i class="fa-solid fa-minus ci-mid"></i> Terbatas, fisik</div>
              <div><i class="fa-solid fa-check ci-ok"></i> Real-time, multi-user</div>
            </div>
            <div class="ab-compare-row">
              <div>Keamanan Data</div>
              <div><i class="fa-solid fa-xmark ci-bad"></i> Rawan hilang</div>
              <div><i class="fa-solid fa-check ci-ok"></i> Tersimpan di database</div>
            </div>
            <div class="ab-compare-row">
              <div>Pantau Status</div>
              <div><i class="fa-solid fa-xmark ci-bad"></i> Tidak bisa</div>
              <div><i class="fa-solid fa-check ci-ok"></i> Via dashboard siswa</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- ══ STAKEHOLDERS ════════════════════════════════════════════ -->
  <div class="ab-stakeholders">
    <div class="container">

      <div class="row mb-5">
        <div class="col-lg-6" data-aos="fade-up" data-aos-duration="600">
          <div class="ab-eyebrow-row mb-3">
            <span class="ab-eyebrow-line"></span>
            <span>Pihak Terkait</span>
          </div>
          <h3 class="ab-section-title mb-0">
            Tiga pihak yang<br><em>berkolaborasi</em> bersama
          </h3>
        </div>
        <div class="col-lg-5 offset-lg-1 d-flex align-items-end"
             data-aos="fade-up" data-aos-delay="100" data-aos-duration="700">
          <p style="font-size:15px;line-height:1.8;color:var(--ab-fg-mid);margin-bottom:0;">
            Sistem ini melibatkan tiga pihak utama yang saling berkoordinasi untuk
            memastikan pengelolaan kendaraan berjalan tertib dan akuntabel.
          </p>
        </div>
      </div>

      <div class="ab-stake-grid">

        <div class="stake-card" data-aos="fade-up" data-aos-delay="0" data-aos-duration="700">
          <span class="stake-bg-num" aria-hidden="true">01</span>
          <div class="stake-icon-wrap">
            <i class="fa-solid fa-school" aria-hidden="true"></i>
          </div>
          <div class="stake-role">Pihak Pertama</div>
          <div class="stake-name">Sekolah</div>
          <p class="stake-desc">Bertindak sebagai pengelola utama sistem. Admin sekolah berwenang memverifikasi dan menyetujui atau menolak pendaftaran kendaraan siswa, serta memantau seluruh data.</p>
        </div>

        <div class="stake-card" data-aos="fade-up" data-aos-delay="150" data-aos-duration="700">
          <span class="stake-bg-num" aria-hidden="true">02</span>
          <div class="stake-icon-wrap">
            <i class="fa-solid fa-scale-balanced" aria-hidden="true"></i>
          </div>
          <div class="stake-role">Pihak Kedua</div>
          <div class="stake-name">MPK</div>
          <p class="stake-desc">Majelis Perwakilan Kelas berperan sebagai pengawas dan koordinator di level siswa. MPK membantu memastikan prosedur pendaftaran diikuti dengan benar.</p>
        </div>

        <div class="stake-card" data-aos="fade-up" data-aos-delay="300" data-aos-duration="700">
          <span class="stake-bg-num" aria-hidden="true">03</span>
          <div class="stake-icon-wrap">
            <i class="fa-solid fa-people-group" aria-hidden="true"></i>
          </div>
          <div class="stake-role">Pihak Ketiga</div>
          <div class="stake-name">OSIS</div>
          <p class="stake-desc">Organisasi Siswa Intra Sekolah berperan dalam sosialisasi sistem, edukasi penggunaan platform, dan menjaga ketertiban di area parkir sekolah sehari-hari.</p>
        </div>

      </div>


    </div>
  </div>

</section>

<script>
(function () {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  var wm  = document.getElementById('ab-wm');
  var sol = document.getElementById('ab-sol-bg');
  var ticking = false;
  window.addEventListener('scroll', function () {
    if (!ticking) {
      requestAnimationFrame(function () {
        var sy = window.scrollY;
        if (wm)  wm.style.transform  = 'translateY(' + (sy * 0.16) + 'px)';
        if (sol) sol.style.transform = 'translateY(' + (sy * -0.07) + 'px)';
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });
})();
</script>