<?php
// sections/alur-pendaftaran.php — v2
// FRAGMENT: jangan tambahkan <html>, <head>, atau <body> di file ini.
// Section Alur Pendaftaran — Stemba Parking · SMKN 7 Semarang
// Konsep: Dark + Amber · Editorial · Timeline Horizontal · CSS + IO Animation
// Dependensi: Bootstrap 5, AOS, Font Awesome 6, Google Fonts
?>

<style>
/* ============================================================
   SECTION DIVIDER — pemisah antara panduan & alur
   ============================================================ */
.al-section-divider {
  position: relative;
  height: 1px;
  background: transparent;
  overflow: visible;
  margin: 0;
  z-index: 10;
}
.al-section-divider::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(90deg, transparent, rgba(245,158,11,0.35), transparent);
}
/* label di tengah divider */
.al-divider-label {
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  background: #0d0d0d;
  border: 1px solid rgba(245,158,11,0.3);
  padding: 6px 20px;
  font-family: var(--font-sans, 'Outfit', sans-serif);
  font-size: 9px; font-weight: 800;
  letter-spacing: 0.22em; text-transform: uppercase;
  color: rgba(245,158,11,0.7);
  white-space: nowrap;
}

/* ============================================================
   ALUR PENDAFTARAN — DARK + AMBER · HORIZONTAL TIMELINE
   ============================================================ */

.al {
  background: #111;          /* sedikit lebih terang dari panduan (#0d0d0d) biar ada beda */
  font-family: var(--font-sans, 'Outfit', sans-serif);
  position: relative;
  overflow: hidden;
  --al-accent:      #f59e0b;
  --al-accent-dim:  rgba(245,158,11,0.18);
  --al-accent-glow: rgba(245,158,11,0.07);
  --al-border:      rgba(255,255,255,0.07);
  --al-border-acc:  rgba(245,158,11,0.28);
  --al-fg:          #ffffff;
  --al-fg-mid:      rgba(255,255,255,0.48);
  --al-fg-low:      rgba(255,255,255,0.2);
}

/* ── HEADER ──────────────────────────────────────────────────── */
.al-header {
  padding: 80px 0 0;
  border-bottom: 1px solid var(--al-border);
  position: relative; z-index: 2;
}

.al-bg-num {
  position: absolute; top: -20px; right: -20px;
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(200px, 28vw, 380px);
  font-weight: 400; line-height: 0.85; letter-spacing: -0.04em;
  color: transparent;
  -webkit-text-stroke: 1px rgba(245,158,11,0.05);
  pointer-events: none; user-select: none; z-index: 0;
  will-change: transform;
}

.al-header .container { position: relative; z-index: 2; }

/* section identity bar — paling atas, biar orang langsung tahu ini apa */
.al-identity-bar {
  display: flex; align-items: center; gap: 16px;
  padding-bottom: 28px;
  border-bottom: 1px solid var(--al-border);
  margin-bottom: 48px;
}
.al-identity-tag {
  display: inline-flex; align-items: center; gap: 8px;
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--al-accent); padding: 5px 12px;
  border: 1px solid var(--al-border-acc); border-radius: 3px;
  background: var(--al-accent-glow);
}
.al-identity-tag i { font-size: 10px; }
.al-identity-sep { width: 1px; height: 14px; background: var(--al-border); }
.al-identity-desc {
  font-size: 11px; font-weight: 600; letter-spacing: 0.08em;
  color: var(--al-fg-low);
}

.al-header-grid {
  display: grid; grid-template-columns: 1fr auto;
  gap: 48px; align-items: end;
  padding-bottom: 48px;
}

.al-eyebrow {
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.24em;
  text-transform: uppercase; color: var(--al-accent);
  margin-bottom: 20px; display: block;
}
.al-title {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(38px, 5.5vw, 76px);
  font-weight: 400; line-height: 0.94; letter-spacing: -0.03em;
  color: var(--al-fg); margin: 0;
}
.al-title em { font-style: italic; color: var(--al-accent); }

.al-header-aside { max-width: 300px; padding-bottom: 8px; }
.al-header-aside p { font-size: 13.5px; line-height: 1.8; color: var(--al-fg-mid); margin: 0; }
.al-header-aside strong { color: var(--al-fg); font-weight: 600; }

/* step count strip */
.al-step-strip { display: flex; border-top: 1px solid var(--al-border); }
.al-step-strip-item {
  flex: 1; padding: 18px 0;
  border-right: 1px solid var(--al-border);
  display: flex; align-items: center; gap: 12px;
  transition: background 0.2s; cursor: default;
}
.al-step-strip-item:last-child { border-right: none; padding-left: 0; }
.al-step-strip-item:first-child { padding-right: 0; }
.al-step-strip-item + .al-step-strip-item { padding-left: 24px; }
.al-step-strip-item:hover { background: rgba(245,158,11,0.03); }
.al-strip-num {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: 28px; font-weight: 400; color: var(--al-accent);
  opacity: 0.35; line-height: 1; flex-shrink: 0;
}
.al-strip-label {
  font-size: 11px; font-weight: 700; letter-spacing: 0.1em;
  text-transform: uppercase; color: var(--al-fg-low); line-height: 1.4;
}

/* ── TIMELINE ────────────────────────────────────────────────── */
.al-timeline-section {
  padding: 80px 0 80px;
  position: relative; z-index: 2;
  background: #111;
}

/* the line sits at vertical center of dots */
.al-progress-wrap { position: relative; }

.al-line-bg {
  position: absolute;
  top: 28px;
  left: calc(10%);
  right: calc(10%);
  height: 1px;
  background: var(--al-border);
  z-index: 0;
}
.al-line-fill {
  position: absolute;
  top: 28px;
  left: calc(10%);
  height: 1px;
  width: 0%;
  background: linear-gradient(90deg, var(--al-accent), #fde68a);
  z-index: 1;
  /* slower line — 2.4s */
  transition: width 2.4s cubic-bezier(0.16, 1, 0.3, 1);
  box-shadow: 0 0 10px rgba(245,158,11,0.35);
}
.al-line-fill.al-animated { width: 80%; }

.al-track {
  position: relative;
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 0;
}

/* ── STEP NODE ────────────────────────────────────────────── */
.al-step {
  display: flex; flex-direction: column; align-items: center;
  position: relative; z-index: 2;
  cursor: default;

  /* initial hidden state */
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.55s ease, transform 0.55s ease;
}
.al-step.al-visible { opacity: 1; transform: translateY(0); }

/* stagger via JS data attr — no nth-child needed */

/* dot container */
.al-dot-wrap {
  width: 56px; height: 56px;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 24px; position: relative;
}

/* outer ring — expands on done */
.al-dot-ring {
  position: absolute; inset: -7px;
  border: 1px solid transparent;
  transition: border-color 0.4s ease, inset 0.4s ease;
}
.al-step.al-done .al-dot-ring {
  border-color: rgba(245,158,11,0.22);
  inset: -5px;
}

/* pulsing glow ring — only on done state */
.al-dot-glow {
  position: absolute; inset: -12px; border-radius: 0;
  background: radial-gradient(circle, rgba(245,158,11,0.12) 0%, transparent 70%);
  opacity: 0;
  transform: scale(0.7);
  transition: opacity 0.5s ease, transform 0.5s ease;
}
.al-step.al-done .al-dot-glow { opacity: 1; transform: scale(1); }

.al-dot {
  width: 56px; height: 56px;
  border: 1px solid var(--al-border);
  background: #111;
  display: flex; align-items: center; justify-content: center;
  transition: border-color 0.4s, background 0.4s, transform 0.3s, box-shadow 0.4s;
  position: relative; z-index: 2;
}

/* icon starts dim, pops when done */
.al-dot i {
  font-size: 20px;
  color: var(--al-fg-low);
  transition: color 0.4s, transform 0.4s;
}

/* ── DONE STATE — icon + dot lights up ── */
.al-step.al-done .al-dot {
  border-color: var(--al-accent);
  background: rgba(245,158,11,0.08);
  box-shadow: 0 0 22px rgba(245,158,11,0.18), inset 0 0 12px rgba(245,158,11,0.06);
}
.al-step.al-done .al-dot i {
  color: var(--al-accent);
  transform: scale(1.15);   /* icon "pop" saat menyala */
}

/* hover on top of done */
.al-step:hover .al-dot {
  border-color: var(--al-accent);
  background: var(--al-accent-dim);
  transform: translateY(-5px);
  box-shadow: 0 10px 28px rgba(245,158,11,0.22);
}
.al-step:hover .al-dot i { color: var(--al-accent); transform: scale(1.2) rotate(-8deg); }

/* step text */
.al-step-content { text-align: center; padding: 0 10px; }
.al-step-num {
  font-size: 9px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--al-accent); opacity: 0.45; margin-bottom: 8px; display: block;
  transition: opacity 0.4s;
}
.al-step.al-done .al-step-num { opacity: 0.85; }
.al-step-label {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(14px, 1.3vw, 17px); font-weight: 400;
  color: rgba(255,255,255,0.55); line-height: 1.2; margin-bottom: 8px;
  letter-spacing: -0.01em;
  transition: color 0.4s;
}
.al-step.al-done .al-step-label { color: var(--al-fg); }
.al-step-desc { font-size: 11.5px; line-height: 1.7; color: var(--al-fg-low); margin: 0; transition: color 0.4s; }
.al-step.al-done .al-step-desc { color: var(--al-fg-mid); }

/* connector arrow between steps (decorative) */
.al-step-arrow {
  position: absolute;
  top: 17px;  /* center of dot */
  right: -6px;
  font-size: 9px; color: rgba(245,158,11,0.3);
  z-index: 3; pointer-events: none;
  transition: color 0.4s;
}
.al-step.al-done .al-step-arrow { color: rgba(245,158,11,0.7); }
.al-step:last-child .al-step-arrow { display: none; }

/* ── PROGRESS TEXT below timeline ── */
.al-progress-label {
  display: flex; align-items: center; justify-content: space-between;
  margin-top: 48px; padding-top: 24px;
  border-top: 1px solid var(--al-border);
}
.al-progress-label-left {
  font-size: 10px; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase;
  color: var(--al-fg-low); display: flex; align-items: center; gap: 10px;
}
.al-progress-label-left i { color: var(--al-accent); font-size: 10px; }
.al-progress-track-wrap { flex: 1; margin: 0 28px; }
.al-prog-track {
  height: 2px; background: var(--al-border); overflow: hidden;
}
.al-prog-fill {
  height: 100%;
  background: linear-gradient(90deg, var(--al-accent), #fde68a);
  width: 0%;
  transition: width 2.8s cubic-bezier(0.16, 1, 0.3, 1);
  box-shadow: 0 0 8px rgba(245,158,11,0.4);
}
.al-prog-fill.al-animated { width: 100%; }
.al-progress-label-right {
  font-size: 10px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;
  color: var(--al-accent); display: flex; align-items: center; gap: 8px;
}
.al-progress-label-right i { font-size: 10px; }

/* ── DETAIL CARDS ────────────────────────────────────────────── */
.al-cards-section {
  padding: 0 0 80px;
  background: #111;
  border-top: 1px solid var(--al-border);
}
.al-cards-header {
  padding: 40px 0 36px;
  display: flex; align-items: baseline; justify-content: space-between; gap: 20px;
  border-bottom: 1px solid var(--al-border); margin-bottom: 40px;
}
.al-cards-header-title {
  font-size: 9px; font-weight: 800; letter-spacing: 0.22em;
  text-transform: uppercase; color: var(--al-fg-low);
}
.al-cards-header-note { font-size: 12px; color: var(--al-fg-low); }

.al-cards-grid {
  display: grid; grid-template-columns: repeat(5, 1fr); gap: 0;
}
.al-card {
  padding: 28px 22px; border-right: 1px solid var(--al-border);
  background: transparent; transition: background 0.22s; cursor: default; position: relative;
}
.al-card:last-child { border-right: none; }
.al-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 0;
  background: var(--al-accent); transition: height 0.22s ease;
}
.al-card:hover { background: rgba(245,158,11,0.03); }
.al-card:hover::before { height: 2px; }

.al-card-step {
  font-size: 9px; font-weight: 800; letter-spacing: 0.2em; text-transform: uppercase;
  color: var(--al-accent); opacity: 0.5; margin-bottom: 16px; display: block;
}
.al-card-icon {
  width: 36px; height: 36px;
  border: 1px solid var(--al-border-acc); background: var(--al-accent-glow);
  display: flex; align-items: center; justify-content: center; margin-bottom: 14px;
  transition: background 0.22s, transform 0.22s;
}
.al-card-icon i { font-size: 14px; color: var(--al-accent); opacity: 0.7; transition: opacity 0.22s; }
.al-card:hover .al-card-icon { background: var(--al-accent-dim); transform: rotate(-8deg) scale(1.1); }
.al-card:hover .al-card-icon i { opacity: 1; }
.al-card-title { font-size: 13px; font-weight: 700; color: var(--al-fg); margin-bottom: 8px; line-height: 1.3; }
.al-card-desc  { font-size: 11.5px; line-height: 1.72; color: var(--al-fg-mid); margin: 0; }

/* stiker card special */
.al-card:last-child {
  background: rgba(245,158,11,0.04); border-right: none;
  border: 1px solid var(--al-border-acc); border-top: none;
}
.al-stiker-badge {
  display: inline-flex; align-items: center; gap: 6px;
  margin-top: 14px; padding: 6px 12px;
  border: 1px solid var(--al-border-acc); background: var(--al-accent-glow);
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase;
  color: var(--al-accent);
}
.al-stiker-badge i { font-size: 9px; }

/* ── CTA ──────────────────────────────────────────────────────── */
.al-cta {
  padding: 56px 0 100px; background: #111;
  border-top: 1px solid var(--al-border);
  display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap;
}
.al-cta-overline {
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--al-accent); margin-bottom: 12px; display: block;
}
.al-cta-title {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(26px, 3.5vw, 46px); font-weight: 400;
  color: var(--al-fg); line-height: 1; letter-spacing: -0.02em; margin: 0;
}
.al-cta-title em { font-style: italic; color: rgba(255,255,255,0.28); }

.btn-al-amber {
  display: inline-flex; align-items: center; gap: 10px;
  background: var(--al-accent); color: #0d0d0d; padding: 15px 32px; border-radius: 3px;
  font-family: var(--font-sans, 'Outfit', sans-serif);
  font-weight: 800; font-size: 12.5px; letter-spacing: 0.06em; text-transform: uppercase;
  text-decoration: none; white-space: nowrap; flex-shrink: 0;
  transition: background 0.2s, transform 0.22s, box-shadow 0.22s;
}
.btn-al-amber:hover {
  background: #fbbf24; color: #0d0d0d; text-decoration: none;
  transform: translateY(-3px); box-shadow: 0 14px 40px rgba(245,158,11,0.28);
}
.btn-al-amber i { transition: transform 0.22s; font-size: 11px; }
.btn-al-amber:hover i { transform: translateX(4px); }

.btn-al-ghost {
  display: inline-flex; align-items: center; gap: 8px;
  background: transparent; color: var(--al-fg-mid); padding: 14px 24px; border-radius: 3px;
  font-family: var(--font-sans, 'Outfit', sans-serif);
  font-weight: 700; font-size: 12.5px; letter-spacing: 0.06em; text-transform: uppercase;
  text-decoration: none; white-space: nowrap; border: 1px solid var(--al-border);
  transition: border-color 0.22s, color 0.22s, background 0.22s;
}
.btn-al-ghost:hover { border-color: var(--al-border-acc); color: var(--al-accent); background: var(--al-accent-glow); text-decoration: none; }
.btn-al-ghost i { font-size: 11px; }

/* ── RESPONSIVE ──────────────────────────────────────────────── */
@media (max-width: 1100px) {
  .al-cards-grid { grid-template-columns: repeat(3, 1fr); }
  .al-card:nth-child(3) { border-right: none; }
  .al-card:nth-child(4),
  .al-card:nth-child(5) { border-top: 1px solid var(--al-border); }
}
@media (max-width: 992px) {
  .al-header-grid { grid-template-columns: 1fr; gap: 24px; }
  .al-header-aside { max-width: 100%; }
  .al-track { grid-template-columns: 1fr 1fr; gap: 40px 24px; }
  .al-line-bg, .al-line-fill { display: none; }
  .al-step-arrow { display: none; }
  .al-step { opacity: 1 !important; transform: none !important; }
  .al-step-content { text-align: left; padding: 0; }
  .al-cards-grid { grid-template-columns: 1fr 1fr; }
  .al-card:nth-child(2) { border-right: none; }
  .al-card:nth-child(3),
  .al-card:nth-child(4),
  .al-card:nth-child(5) { border-top: 1px solid var(--al-border); }
  .al-card:nth-child(4) { border-right: none; }
  .al-cta { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 576px) {
  .al-track { grid-template-columns: 1fr; }
  .al-cards-grid { grid-template-columns: 1fr; }
  .al-card { border-right: none; border-bottom: 1px solid var(--al-border); }
  .al-card:last-child { border: 1px solid var(--al-border-acc); border-top: none; }
  .al-step-strip { flex-wrap: wrap; }
  .al-step-strip-item { min-width: 50%; }
  .al-divider-label { font-size: 7.5px; padding: 5px 14px; }
}

/* ── REDUCED MOTION ──────────────────────────────────────────── */
@media (prefers-reduced-motion: reduce) {
  .al-step { opacity: 1 !important; transform: none !important; transition: none !important; }
  .al-step.al-done .al-dot i { transform: none !important; }
  .al-line-fill, .al-prog-fill { transition: none !important; }
  .al-dot, .al-card-icon, .btn-al-amber { transition: none !important; }
  .al-step:hover .al-dot, .btn-al-amber:hover { transform: none !important; }
}
</style>

<!-- ══ SECTION DIVIDER — pemisah panduan & alur ══════════════ -->
<div class="al-section-divider" aria-hidden="true">
  <span class="al-divider-label">
    <i class="fa-solid fa-chevron-down" style="font-size:8px;margin-right:6px;"></i>
    Selanjutnya · Alur Pendaftaran
  </span>
</div>

<section id="alur" class="al" aria-label="Alur Pendaftaran Stemba Parking">

  <!-- ── HEADER ───────────────────────────────────────────────── -->
  <div class="al-header">
    <div class="al-bg-num" aria-hidden="true" id="al-bgnum">04</div>
    <div class="container">

      <!-- identity bar — section labeling yang jelas -->
      <div class="al-identity-bar" data-aos="fade-up" data-aos-duration="500">
        <span class="al-identity-tag">
          <i class="fa-solid fa-route"></i>
          Alur Pendaftaran
        </span>
        <span class="al-identity-sep" aria-hidden="true"></span>
        <span class="al-identity-desc">Ikuti 5 langkah berikut untuk mendaftarkan kendaraanmu</span>
      </div>

      <div class="al-header-grid" data-aos="fade-up" data-aos-delay="60" data-aos-duration="700">
        <div>
          <span class="al-eyebrow">Step by Step · Panduan Resmi</span>
          <h2 class="al-title">
            Lima langkah<br>menuju <em>parkir resmi</em>
          </h2>
        </div>
        <div class="al-header-aside">
          <p>
            Proses pendaftaran dirancang sederhana dan bisa dilakukan dari mana saja.
            Setelah disetujui, kendaraanmu mendapat <strong>stiker fisik resmi</strong> sebagai
            tanda izin parkir di lingkungan SMKN 7 Semarang.
          </p>
        </div>
      </div>

      <div class="al-step-strip" data-aos="fade-up" data-aos-delay="100" data-aos-duration="600">
        <div class="al-step-strip-item">
          <span class="al-strip-num">5</span>
          <span class="al-strip-label">Langkah<br>Mudah</span>
        </div>
        <div class="al-step-strip-item">
          <span class="al-strip-num">3</span>
          <span class="al-strip-label">Dokumen<br>Dibutuhkan</span>
        </div>
        <div class="al-step-strip-item">
          <span class="al-strip-num">1–3</span>
          <span class="al-strip-label">Hari<br>Verifikasi</span>
        </div>
        <div class="al-step-strip-item">
          <span class="al-strip-num">1</span>
          <span class="al-strip-label">Stiker<br>Fisik Resmi</span>
        </div>
      </div>

    </div>
  </div>

  <!-- ── TIMELINE ─────────────────────────────────────────────── -->
  <div class="al-timeline-section">
    <div class="container">

      <div class="al-progress-wrap">
        <div class="al-line-bg"   aria-hidden="true"></div>
        <div class="al-line-fill" aria-hidden="true" id="al-line"></div>

        <div class="al-track" id="al-track" role="list" aria-label="Langkah-langkah pendaftaran">

          <!-- Step 1 -->
          <div class="al-step" role="listitem" data-delay="0">
            <div class="al-dot-wrap">
              <div class="al-dot-glow"  aria-hidden="true"></div>
              <div class="al-dot-ring"  aria-hidden="true"></div>
              <div class="al-dot">
                <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
              </div>
            </div>
            <div class="al-step-content">
              <span class="al-step-num">01</span>
              <div class="al-step-label">Login Akun Siswa</div>
              <p class="al-step-desc">Masuk dengan akun sekolah yang sudah terdaftar di sistem</p>
            </div>
            <i class="fa-solid fa-chevron-right al-step-arrow" aria-hidden="true"></i>
          </div>

          <!-- Step 2 -->
          <div class="al-step" role="listitem" data-delay="480">
            <div class="al-dot-wrap">
              <div class="al-dot-glow"  aria-hidden="true"></div>
              <div class="al-dot-ring"  aria-hidden="true"></div>
              <div class="al-dot">
                <i class="fa-solid fa-file-pen" aria-hidden="true"></i>
              </div>
            </div>
            <div class="al-step-content">
              <span class="al-step-num">02</span>
              <div class="al-step-label">Isi Form Kendaraan</div>
              <p class="al-step-desc">Lengkapi data merek, tipe, warna, dan nomor plat kendaraan</p>
            </div>
            <i class="fa-solid fa-chevron-right al-step-arrow" aria-hidden="true"></i>
          </div>

          <!-- Step 3 -->
          <div class="al-step" role="listitem" data-delay="960">
            <div class="al-dot-wrap">
              <div class="al-dot-glow"  aria-hidden="true"></div>
              <div class="al-dot-ring"  aria-hidden="true"></div>
              <div class="al-dot">
                <i class="fa-solid fa-file-arrow-up" aria-hidden="true"></i>
              </div>
            </div>
            <div class="al-step-content">
              <span class="al-step-num">03</span>
              <div class="al-step-label">Upload Dokumen</div>
              <p class="al-step-desc">Unggah foto kendaraan, SIM, dan kartu pelajar aktif</p>
            </div>
            <i class="fa-solid fa-chevron-right al-step-arrow" aria-hidden="true"></i>
          </div>

          <!-- Step 4 -->
          <div class="al-step" role="listitem" data-delay="1440">
            <div class="al-dot-wrap">
              <div class="al-dot-glow"  aria-hidden="true"></div>
              <div class="al-dot-ring"  aria-hidden="true"></div>
              <div class="al-dot">
                <i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i>
              </div>
            </div>
            <div class="al-step-content">
              <span class="al-step-num">04</span>
              <div class="al-step-label">Menunggu Verifikasi</div>
              <p class="al-step-desc">Admin sekolah meninjau data dalam 1–3 hari kerja</p>
            </div>
            <i class="fa-solid fa-chevron-right al-step-arrow" aria-hidden="true"></i>
          </div>

          <!-- Step 5 -->
          <div class="al-step" role="listitem" data-delay="1920">
            <div class="al-dot-wrap">
              <div class="al-dot-glow"  aria-hidden="true"></div>
              <div class="al-dot-ring"  aria-hidden="true"></div>
              <div class="al-dot">
                <i class="fa-solid fa-certificate" aria-hidden="true"></i>
              </div>
            </div>
            <div class="al-step-content">
              <span class="al-step-num">05</span>
              <div class="al-step-label">Disetujui &amp; Stiker</div>
              <p class="al-step-desc">Ambil stiker parkir fisik resmi di TU sekolah</p>
            </div>
          </div>

        </div><!-- /.al-track -->

        <!-- progress label below timeline -->
        <div class="al-progress-label" data-aos="fade-up" data-aos-delay="80" data-aos-duration="500">
          <span class="al-progress-label-left">
            <i class="fa-solid fa-circle-info"></i>
            Mulai dari login
          </span>
          <div class="al-progress-track-wrap">
            <div class="al-prog-track">
              <div class="al-prog-fill" id="al-prog"></div>
            </div>
          </div>
          <span class="al-progress-label-right">
            <i class="fa-solid fa-tag"></i>
            Selesai · Stiker Resmi
          </span>
        </div>

      </div>
    </div>
  </div>

  <!-- ── DETAIL CARDS ─────────────────────────────────────────── -->
  <div class="al-cards-section">
    <div class="container">

      <div class="al-cards-header" data-aos="fade-up" data-aos-duration="500">
        <span class="al-cards-header-title">Detail Setiap Langkah</span>
        <span class="al-cards-header-note">Ikuti urutan agar proses berjalan lancar</span>
      </div>

      <div class="al-cards-grid" data-aos="fade-up" data-aos-delay="60" data-aos-duration="700">

        <div class="al-card">
          <span class="al-card-step">Langkah 01</span>
          <div class="al-card-icon"><i class="fa-solid fa-right-to-bracket"></i></div>
          <div class="al-card-title">Login Akun Siswa</div>
          <p class="al-card-desc">Gunakan email sekolah dan password yang sudah diberikan saat registrasi awal. Belum punya akun? Hubungi admin atau MPK kelasmu.</p>
        </div>

        <div class="al-card">
          <span class="al-card-step">Langkah 02</span>
          <div class="al-card-icon"><i class="fa-solid fa-file-pen"></i></div>
          <div class="al-card-title">Isi Form Kendaraan</div>
          <p class="al-card-desc">Isi merek, tipe, tahun, warna, dan nomor plat kendaraan dengan benar. Semua data harus sesuai dengan STNK asli kendaraanmu.</p>
        </div>

        <div class="al-card">
          <span class="al-card-step">Langkah 03</span>
          <div class="al-card-icon"><i class="fa-solid fa-file-arrow-up"></i></div>
          <div class="al-card-title">Upload Dokumen</div>
          <p class="al-card-desc">Upload tiga foto: kendaraan tampak depan/samping, SIM aktif tampak penuh, dan kartu pelajar SMKN 7 Semarang yang masih berlaku.</p>
        </div>

        <div class="al-card">
          <span class="al-card-step">Langkah 04</span>
          <div class="al-card-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
          <div class="al-card-title">Menunggu Verifikasi</div>
          <p class="al-card-desc">Admin sekolah akan memverifikasi kelengkapan dan keaslian dokumen. Proses 1–3 hari kerja. Pantau status di dashboard siswa kapan saja.</p>
        </div>

        <div class="al-card">
          <span class="al-card-step">Langkah 05</span>
          <div class="al-card-icon"><i class="fa-solid fa-certificate"></i></div>
          <div class="al-card-title">Disetujui &amp; Ambil Stiker</div>
          <p class="al-card-desc">Setelah disetujui, ambil stiker parkir fisik resmi di TU sekolah dan tempelkan di kendaraanmu sebagai tanda izin masuk area parkir.</p>
          <span class="al-stiker-badge">
            <i class="fa-solid fa-tag"></i> Stiker Fisik Resmi
          </span>
        </div>

      </div>
    </div>
  </div>

  <!-- ── CTA ──────────────────────────────────────────────────── -->
  <div class="container">
    <div class="al-cta" data-aos="fade-up" data-aos-duration="600">
      <div>
        <span class="al-cta-overline">Siap Mulai?</span>
        <h3 class="al-cta-title">Lima langkah,<br><em>satu tujuan.</em></h3>
      </div>
      <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <a class="btn-al-amber" href="register.php">
          Mulai Daftar <i class="fa-solid fa-arrow-right"></i>
        </a>
        <a class="btn-al-ghost" href="#panduan">
          <i class="fa-solid fa-book-open"></i> Baca Panduan
        </a>
      </div>
    </div>
  </div>

</section>

<script>
(function () {
  'use strict';
  var noMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ── parallax bg number ── */
  if (!noMotion) {
    var bgNum   = document.getElementById('al-bgnum');
    var ticking = false;
    window.addEventListener('scroll', function () {
      if (!ticking) {
        requestAnimationFrame(function () {
          if (bgNum) bgNum.style.transform = 'translateY(' + (window.scrollY * 0.08) + 'px)';
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  }

  /* ── timeline: line fill + sequential step reveal ── */
  var track    = document.getElementById('al-track');
  var lineFill = document.getElementById('al-line');
  var progFill = document.getElementById('al-prog');
  if (!track) return;

  var steps = Array.prototype.slice.call(track.querySelectorAll('.al-step'));

  function animateTimeline() {
    /* 1. start line immediately */
    if (lineFill && !noMotion) lineFill.classList.add('al-animated');
    if (progFill && !noMotion) progFill.classList.add('al-animated');

    /* 2. reveal + "done" each step using data-delay — line reaches icon THEN it lights up */
    steps.forEach(function (step) {
      var delay    = parseInt(step.getAttribute('data-delay') || '0', 10);
      var revDelay = noMotion ? 0 : delay;            /* same as line position delay */
      var doneDelay= noMotion ? 0 : delay + 260;      /* icon pop 260ms after line arrives */

      setTimeout(function () { step.classList.add('al-visible'); }, revDelay);
      setTimeout(function () { step.classList.add('al-done');    }, doneDelay);
    });
  }

  /* trigger via IntersectionObserver */
  if ('IntersectionObserver' in window && !noMotion) {
    var obs = new IntersectionObserver(function (entries, o) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { animateTimeline(); o.disconnect(); }
      });
    }, { threshold: 0.2 });
    obs.observe(track);
  } else {
    steps.forEach(function (s) { s.classList.add('al-visible', 'al-done'); });
    if (lineFill) lineFill.classList.add('al-animated');
    if (progFill) progFill.classList.add('al-animated');
  }

})();
</script>