<?php
// sections/panduan.php — v2 EDITORIAL
// FRAGMENT: jangan tambahkan <html>, <head>, atau <body> di file ini.
// Section Panduan Kendaraan — Stemba Parking · SMKN 7 Semarang
// Konsep: Editorial / Magazine · Dark + Amber Accent · Timeline Layout
// Dependensi: Bootstrap 5, AOS, Font Awesome 6, Google Fonts
?>

<style>
/* ============================================================
   PANDUAN — EDITORIAL MAGAZINE · DARK + AMBER · TIMELINE
   ============================================================ */

.pd { background: #0d0d0d; font-family: var(--font-sans, 'Outfit', sans-serif); position: relative; }

:root {
  --pd-accent:      #f59e0b;
  --pd-accent-dim:  rgba(245,158,11,0.18);
  --pd-accent-glow: rgba(245,158,11,0.08);
  --pd-fg:          #ffffff;
  --pd-fg-mid:      rgba(255,255,255,0.52);
  --pd-fg-low:      rgba(255,255,255,0.22);
  --pd-border:      rgba(255,255,255,0.08);
  --pd-border-acc:  rgba(245,158,11,0.35);
}

/* ── SECTION DIVIDER ─────────────────────────────────────────── */
.pd-section-divider {
  position: relative; height: 1px; background: transparent;
  overflow: visible; margin: 0; z-index: 10;
}
.pd-section-divider::before {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(90deg, transparent, rgba(245,158,11,0.35), transparent);
}
.pd-divider-label {
  position: absolute; top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  background: #0d0d0d;
  border: 1px solid rgba(245,158,11,0.3);
  padding: 6px 20px;
  font-family: var(--font-sans, 'Outfit', sans-serif);
  font-size: 9px; font-weight: 800;
  letter-spacing: 0.22em; text-transform: uppercase;
  color: rgba(245,158,11,0.7); white-space: nowrap;
}

/* ── IDENTITY BAR ────────────────────────────────────────────── */
.pd-identity-bar {
  display: flex; align-items: center; gap: 16px;
  padding-bottom: 24px;
  border-bottom: 1px solid var(--pd-border);
  margin-bottom: 32px;
}
.pd-identity-tag {
  display: inline-flex; align-items: center; gap: 8px;
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--pd-accent); padding: 5px 12px;
  border: 1px solid var(--pd-border-acc); border-radius: 3px;
  background: rgba(245,158,11,0.07);
}
.pd-identity-tag i { font-size: 10px; }
.pd-identity-sep { width: 1px; height: 14px; background: var(--pd-border); }
.pd-identity-desc {
  font-size: 11px; font-weight: 600; letter-spacing: 0.08em;
  color: var(--pd-fg-low);
}

/* ── HEADER ──────────────────────────────────────────────────── */
.pd-header { padding: 80px 0 0; position: relative; overflow: hidden; }

.pd-issue {
  position: absolute; top: 0; left: 0;
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(200px, 28vw, 400px);
  font-weight: 400; line-height: 0.85;
  color: transparent; -webkit-text-stroke: 1px rgba(255,255,255,0.04);
  pointer-events: none; user-select: none; letter-spacing: -0.04em;
  will-change: transform;
}

.pd-header .container { position: relative; z-index: 2; }

.pd-rule-top {
  display: flex; align-items: center; gap: 16px;
  padding-bottom: 28px;
  border-bottom: 1px solid var(--pd-border); margin-bottom: 48px;
}
.pd-section-tag {
  font-size: 10px; font-weight: 800; letter-spacing: 0.2em;
  text-transform: uppercase; color: var(--pd-accent);
  padding: 4px 10px; border: 1px solid var(--pd-border-acc);
  border-radius: 4px; background: var(--pd-accent-glow);
}
.pd-issue-label {
  font-size: 10px; font-weight: 700; letter-spacing: 0.16em;
  text-transform: uppercase; color: var(--pd-fg-low); margin-left: auto;
}

.pd-headline-row {
  display: grid; grid-template-columns: 1fr auto;
  gap: 40px; align-items: end;
  padding-bottom: 56px; border-bottom: 1px solid var(--pd-border);
}
.pd-main-title {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(48px, 7.5vw, 108px); font-weight: 400;
  line-height: 0.92; color: var(--pd-fg); letter-spacing: -0.03em; margin: 0;
}
.pd-main-title .pd-accent-word { color: var(--pd-accent); font-style: italic; }

.pd-headline-aside { max-width: 260px; padding-bottom: 8px; }
.pd-headline-aside p { font-size: 13px; line-height: 1.84; color: var(--pd-fg-mid); margin: 0; }
.pd-headline-aside strong { color: var(--pd-fg); font-weight: 600; }

.pd-info-strip { display: flex; gap: 0; border-bottom: 1px solid var(--pd-border); }
.pd-info-cell {
  flex: 1; padding: 22px 0;
  border-right: 1px solid var(--pd-border);
  display: flex; flex-direction: column; gap: 4px;
}
.pd-info-cell:last-child  { border-right: none; padding-left: 32px; }
.pd-info-cell:first-child { padding-right: 32px; }
.pd-info-cell + .pd-info-cell { padding-left: 32px; }
.pd-info-cell-label {
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.2em;
  text-transform: uppercase; color: var(--pd-fg-low);
}
.pd-info-cell-val { font-size: 14px; font-weight: 600; color: var(--pd-fg); }

/* ── SYARAT ──────────────────────────────────────────────────── */
.pd-syarat { padding: 100px 0; position: relative; }

.pd-pull-num {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(80px, 11vw, 160px); font-weight: 400;
  line-height: 1; color: var(--pd-accent); opacity: 0.12;
  display: block; margin-bottom: -24px; letter-spacing: -0.04em;
}
.pd-col-label {
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.22em;
  text-transform: uppercase; color: var(--pd-fg-low);
  padding-bottom: 20px; border-bottom: 1px solid var(--pd-border); margin-bottom: 32px;
}
.pd-syarat-title {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(32px, 4vw, 52px); font-weight: 400;
  color: var(--pd-fg); line-height: 1.08; letter-spacing: -0.02em; margin-bottom: 24px;
}
.pd-syarat-title em { font-style: italic; color: var(--pd-accent); }
.pd-syarat-body { font-size: 15px; line-height: 1.82; color: var(--pd-fg-mid); max-width: 46ch; }
.pd-syarat-body strong { color: var(--pd-fg); }

.pd-syarat-cards {
  display: flex; flex-direction: column; gap: 1px;
  border: 1px solid var(--pd-border); border-radius: 2px; overflow: hidden;
}
.pd-syarat-card {
  display: grid; grid-template-columns: 72px 1fr;
  background: rgba(255,255,255,0.02); transition: background 0.22s; cursor: default;
}
.pd-syarat-card + .pd-syarat-card { border-top: 1px solid var(--pd-border); }
.pd-syarat-card:hover { background: rgba(255,255,255,0.045); }

.pd-syarat-card-index {
  display: flex; align-items: flex-start; justify-content: center; padding-top: 28px;
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: 13px; font-weight: 400; color: var(--pd-accent); opacity: 0.7;
  border-right: 1px solid var(--pd-border);
}
.pd-syarat-card-body { padding: 24px 28px; }
.pd-syarat-card-icon {
  width: 32px; height: 32px; border-radius: 6px;
  background: var(--pd-accent-dim); border: 1px solid var(--pd-border-acc);
  display: flex; align-items: center; justify-content: center; margin-bottom: 14px;
  transition: transform 0.22s, background 0.22s;
}
.pd-syarat-card-icon i { font-size: 13px; color: var(--pd-accent); }
.pd-syarat-card:hover .pd-syarat-card-icon { transform: rotate(-8deg) scale(1.1); background: rgba(245,158,11,0.28); }
.pd-syarat-card-title { font-size: 15px; font-weight: 700; color: var(--pd-fg); margin-bottom: 7px; }
.pd-syarat-card-desc  { font-size: 12.5px; line-height: 1.72; color: var(--pd-fg-mid); margin: 0; }

/* ── DOKUMEN TIMELINE ────────────────────────────────────────── */
.pd-dokumen { padding: 0 0 100px; }

.pd-dok-header-row {
  padding: 56px 0;
  border-top: 1px solid var(--pd-border); border-bottom: 1px solid var(--pd-border);
  margin-bottom: 80px;
  display: flex; align-items: baseline; gap: 28px; flex-wrap: wrap;
}
.pd-dok-eyebrow {
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.22em;
  text-transform: uppercase; color: var(--pd-accent);
}
.pd-dok-big-title {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(40px, 5.5vw, 72px); font-weight: 400;
  color: var(--pd-fg); letter-spacing: -0.03em; line-height: 0.95; margin: 0;
}
.pd-dok-big-title em { font-style: italic; color: var(--pd-fg-mid); }
.pd-dok-subtitle {
  font-size: 13.5px; color: var(--pd-fg-mid); line-height: 1.72;
  max-width: 38ch; margin-left: auto; align-self: center;
}

/* timeline */
.pd-timeline { position: relative; }
.pd-timeline::before {
  content: ''; position: absolute;
  left: 35px; top: 0; bottom: 0; width: 1px;
  background: linear-gradient(to bottom, var(--pd-accent) 0%, rgba(245,158,11,0.08) 100%);
}
.pd-tl-item {
  display: grid; grid-template-columns: 70px 1fr;
  position: relative;
}
.pd-tl-node {
  display: flex; flex-direction: column; align-items: center;
  padding-top: 4px; position: relative; z-index: 1;
}
.pd-tl-dot {
  width: 14px; height: 14px; border-radius: 50%;
  border: 2px solid var(--pd-accent); background: #0d0d0d;
  box-shadow: 0 0 0 4px var(--pd-accent-glow); flex-shrink: 0;
  transition: box-shadow 0.3s, background 0.3s;
}
.pd-tl-item:hover .pd-tl-dot { background: var(--pd-accent); box-shadow: 0 0 0 6px var(--pd-accent-dim); }

.pd-tl-content { padding: 0 0 64px 32px; }
.pd-tl-item:last-child .pd-tl-content { padding-bottom: 0; }

.pd-tl-step-num {
  font-size: 10px; font-weight: 800; letter-spacing: 0.18em;
  text-transform: uppercase; color: var(--pd-accent); opacity: 0.7;
  margin-bottom: 10px; display: block;
}
.pd-tl-title {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(24px, 3vw, 36px); font-weight: 400;
  color: var(--pd-fg); line-height: 1.1; margin-bottom: 16px; letter-spacing: -0.02em;
}
.pd-tl-desc { font-size: 14px; line-height: 1.8; color: var(--pd-fg-mid); margin: 0 0 20px; max-width: 52ch; }
.pd-tl-desc strong { color: var(--pd-fg); }

.pd-tl-tags { display: flex; flex-wrap: wrap; gap: 8px; }
.pd-tl-tag {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
  padding: 5px 12px; border-radius: 4px;
  border: 1px solid var(--pd-border-acc); color: var(--pd-accent); background: var(--pd-accent-glow);
  transition: background 0.2s;
}
.pd-tl-tag:hover { background: var(--pd-accent-dim); }
.pd-tl-tag i { font-size: 9px; }

.pd-tl-card {
  margin-top: 24px; padding: 22px 24px;
  background: rgba(255,255,255,0.025); border: 1px solid var(--pd-border);
  border-left: 3px solid var(--pd-accent); border-radius: 0 8px 8px 0;
  font-size: 13px; line-height: 1.7; color: var(--pd-fg-mid); max-width: 52ch;
}
.pd-tl-card i { color: var(--pd-accent); margin-right: 8px; font-size: 11px; }

/* ── KETENTUAN ────────────────────────────────────────────────── */
.pd-ketentuan { padding: 0 0 120px; }

.pd-ket-header {
  padding: 56px 0; border-top: 1px solid var(--pd-border); margin-bottom: 64px;
  display: flex; align-items: flex-end; justify-content: space-between; gap: 40px; flex-wrap: wrap;
}
.pd-ket-title {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(36px, 5vw, 68px); font-weight: 400;
  color: var(--pd-fg); letter-spacing: -0.03em; line-height: 0.95; margin: 0;
}
.pd-ket-title em { font-style: italic; color: var(--pd-accent); }
.pd-ket-count {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: 80px; font-weight: 400; color: var(--pd-accent);
  opacity: 0.18; line-height: 1; letter-spacing: -0.04em;
}

.pd-ket-row {
  display: grid; grid-template-columns: 56px 1fr auto; gap: 24px; align-items: start;
  padding: 24px 0; border-bottom: 1px solid var(--pd-border);
  transition: padding-left 0.22s; cursor: default;
}
.pd-ket-row:first-child { border-top: 1px solid var(--pd-border); }
.pd-ket-row:hover { padding-left: 10px; }
.pd-ket-num {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: 13px; color: var(--pd-accent); opacity: 0.5; padding-top: 3px;
}
.pd-ket-rule { font-size: 15px; font-weight: 700; color: var(--pd-fg); margin-bottom: 5px; }
.pd-ket-sub  { font-size: 12.5px; line-height: 1.7; color: var(--pd-fg-mid); margin: 0; }
.pd-ket-icon {
  width: 36px; height: 36px; border-radius: 8px;
  border: 1px solid var(--pd-border); background: rgba(255,255,255,0.03);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; margin-top: 2px; transition: border-color 0.22s, background 0.22s;
}
.pd-ket-icon i { font-size: 13px; color: var(--pd-fg-low); transition: color 0.22s; }
.pd-ket-row:hover .pd-ket-icon { border-color: var(--pd-border-acc); background: var(--pd-accent-glow); }
.pd-ket-row:hover .pd-ket-icon i { color: var(--pd-accent); }

/* ── CTA ─────────────────────────────────────────────────────── */
.pd-cta {
  padding: 72px 0 100px; border-top: 1px solid var(--pd-border);
  display: flex; align-items: center; justify-content: space-between; gap: 40px; flex-wrap: wrap;
}
.pd-cta-overline {
  font-size: 10px; font-weight: 800; letter-spacing: 0.22em;
  text-transform: uppercase; color: var(--pd-accent); margin-bottom: 14px; display: block;
}
.pd-cta-title {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(28px, 4vw, 52px); font-weight: 400;
  color: var(--pd-fg); line-height: 0.98; letter-spacing: -0.03em; margin: 0;
}
.pd-cta-title em { font-style: italic; color: var(--pd-fg-mid); }

.btn-pd-primary {
  display: inline-flex; align-items: center; gap: 12px;
  background: var(--pd-accent); color: #0d0d0d;
  padding: 16px 36px; border-radius: 4px;
  font-family: var(--font-sans, 'Outfit', sans-serif);
  font-weight: 800; font-size: 13px; letter-spacing: 0.05em;
  text-transform: uppercase; text-decoration: none; white-space: nowrap; flex-shrink: 0;
  transition: background 0.2s, transform 0.22s, box-shadow 0.22s;
}
.btn-pd-primary:hover {
  background: #fbbf24; color: #0d0d0d; text-decoration: none;
  transform: translateY(-3px); box-shadow: 0 16px 40px rgba(245,158,11,0.28);
}
.btn-pd-primary i { transition: transform 0.22s; }
.btn-pd-primary:hover i { transform: translateX(5px); }

/* ── RESPONSIVE ──────────────────────────────────────────────── */
@media (max-width: 992px) {
  .pd-headline-row { grid-template-columns: 1fr; }
  .pd-headline-aside { max-width: 100%; }
  .pd-dok-header-row { flex-direction: column; gap: 20px; }
  .pd-dok-subtitle { margin-left: 0; }
  .pd-ket-header { flex-direction: column; align-items: flex-start; }
  .pd-cta { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 768px) {
  .pd-info-strip { flex-direction: column; }
  .pd-info-cell { border-right: none; border-bottom: 1px solid var(--pd-border); padding: 18px 0 !important; }
  .pd-info-cell:last-child { border-bottom: none; }
  .pd-timeline::before { left: 28px; }
  .pd-tl-item { grid-template-columns: 56px 1fr; }
  .pd-ket-row { grid-template-columns: 40px 1fr; }
  .pd-ket-icon { display: none; }
}
@media (prefers-reduced-motion: reduce) {
  .pd-tl-item:hover .pd-tl-dot, .pd-ket-row:hover,
  .btn-pd-primary:hover, .pd-syarat-card:hover .pd-syarat-card-icon { transform: none !important; }
}
</style>

<!-- ══ SECTION DIVIDER — pemisah antara tentang/hero & panduan ══ -->
<div class="pd-section-divider" aria-hidden="true">
  <span class="pd-divider-label">
    <i class="fa-solid fa-chevron-down" style="font-size:8px;margin-right:6px;"></i>
    Selanjutnya · Panduan Kendaraan
  </span>
</div>

<section id="panduan" class="pd" aria-label="Panduan Kendaraan Stemba Parking">
  <div class="container">

    <!-- ══ HEADER ════════════════════════════════════════════════ -->
    <div class="pd-header">
      <div class="pd-issue" aria-hidden="true" id="pd-issue">03</div>

      <!-- identity bar — kejelasan section -->
      <div class="pd-identity-bar" data-aos="fade-up" data-aos-duration="500">
        <span class="pd-identity-tag">
          <i class="fa-solid fa-book-open-reader"></i>
          Panduan Kendaraan
        </span>
        <span class="pd-identity-sep" aria-hidden="true"></span>
        <span class="pd-identity-desc">Baca syarat, siapkan dokumen, pahami aturan sebelum mendaftar</span>
      </div>

      <div class="pd-headline-row" data-aos="fade-up" data-aos-delay="60" data-aos-duration="700">
        <h2 class="pd-main-title">
          Syarat,<br>
          Dokumen<br>
          &amp; <span class="pd-accent-word">Aturan</span>
        </h2>
        <div class="pd-headline-aside">
          <p>
            Panduan lengkap bagi siswa yang ingin mendaftarkan kendaraannya ke sistem
            Stemba Parking. Baca seluruh ketentuan sebelum mulai mengisi formulir
            <strong>agar proses verifikasi berjalan lancar.</strong>
          </p>
        </div>
      </div>

      <div class="pd-info-strip" data-aos="fade-up" data-aos-delay="120" data-aos-duration="600">
        <div class="pd-info-cell">
          <span class="pd-info-cell-label">Syarat Utama</span>
          <span class="pd-info-cell-val">3 Ketentuan</span>
        </div>
        <div class="pd-info-cell">
          <span class="pd-info-cell-label">Dokumen Wajib</span>
          <span class="pd-info-cell-val">3 Foto Upload</span>
        </div>
        <div class="pd-info-cell">
          <span class="pd-info-cell-label">Aturan Parkir</span>
          <span class="pd-info-cell-val">8 Ketentuan</span>
        </div>
        <div class="pd-info-cell">
          <span class="pd-info-cell-label">Berlaku Sejak</span>
          <span class="pd-info-cell-val">T.A. 2025 / 2026</span>
        </div>
      </div>
    </div>

    <!-- ══ SYARAT KENDARAAN ════════════════════════════════════ -->
    <div class="pd-syarat">
      <div class="row g-5 align-items-start">

        <div class="col-lg-5" data-aos="fade-right" data-aos-duration="700">
          <span class="pd-col-label">Syarat Kendaraan</span>
          <span class="pd-pull-num" aria-hidden="true">03</span>
          <h3 class="pd-syarat-title">
            Kendaraan yang boleh<br>masuk harus <em>memenuhi</em><br>tiga syarat ini
          </h3>
          <p class="pd-syarat-body">
            Tidak semua kendaraan otomatis mendapatkan izin parkir. Sekolah menerapkan
            standar minimum demi keamanan dan ketertiban seluruh pengguna area parkir.
            Pastikan kendaraanmu <strong>lolos ketiga syarat</strong> di bawah sebelum mendaftar.
          </p>
        </div>

        <div class="col-lg-6 offset-lg-1" data-aos="fade-left" data-aos-delay="100" data-aos-duration="700">
          <div class="pd-syarat-cards">

            <div class="pd-syarat-card">
              <div class="pd-syarat-card-index" aria-hidden="true">01</div>
              <div class="pd-syarat-card-body">
                <div class="pd-syarat-card-icon">
                  <i class="fa-solid fa-id-card" aria-hidden="true"></i>
                </div>
                <div class="pd-syarat-card-title">Memiliki SIM Aktif</div>
                <p class="pd-syarat-card-desc">SIM yang masih berlaku dan sesuai jenis kendaraan wajib dimiliki. Ini adalah bukti legal bahwa siswa sudah layak mengemudikan kendaraan di jalan umum.</p>
              </div>
            </div>

            <div class="pd-syarat-card">
              <div class="pd-syarat-card-index" aria-hidden="true">02</div>
              <div class="pd-syarat-card-body">
                <div class="pd-syarat-card-icon">
                  <i class="fa-solid fa-motorcycle" aria-hidden="true"></i>
                </div>
                <div class="pd-syarat-card-title">Kendaraan Layak Jalan</div>
                <p class="pd-syarat-card-desc">Rem berfungsi, lampu lengkap, kondisi mesin tidak membahayakan. Kendaraan yang terlihat rusak atau tidak terawat akan diminta tidak memasuki area sekolah.</p>
              </div>
            </div>

            <div class="pd-syarat-card">
              <div class="pd-syarat-card-index" aria-hidden="true">03</div>
              <div class="pd-syarat-card-body">
                <div class="pd-syarat-card-icon">
                  <i class="fa-solid fa-hashtag" aria-hidden="true"></i>
                </div>
                <div class="pd-syarat-card-title">TNKB Sesuai Dokumen</div>
                <p class="pd-syarat-card-desc">Plat nomor harus terbaca jelas dan identik dengan yang tertera di STNK. Plat tidak sesuai, rusak, atau tidak terpasang akan menyebabkan penolakan pendaftaran.</p>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>

    <!-- ══ DOKUMEN — TIMELINE ════════════════════════════════════ -->
    <div class="pd-dokumen">

      <div class="pd-dok-header-row" data-aos="fade-up" data-aos-duration="600">
        <div>
          <span class="pd-dok-eyebrow">Dokumen yang Dibutuhkan</span>
          <h3 class="pd-dok-big-title">
            Apa yang perlu<br><em>kamu siapkan</em>
          </h3>
        </div>
        <p class="pd-dok-subtitle">
          Tiga foto ini wajib diunggah saat mengisi formulir pendaftaran online.
          Pastikan foto jelas, tidak buram, dan semua informasi terbaca.
        </p>
      </div>

      <div class="row">
        <div class="col-lg-9 offset-lg-1">
          <div class="pd-timeline">

            <div class="pd-tl-item" data-aos="fade-up" data-aos-duration="600">
              <div class="pd-tl-node"><div class="pd-tl-dot"></div></div>
              <div class="pd-tl-content">
                <span class="pd-tl-step-num">Dokumen 01</span>
                <h4 class="pd-tl-title">Foto Kendaraan</h4>
                <p class="pd-tl-desc">
                  Ambil foto kendaraan dari tampak <strong>depan atau samping</strong> yang memperlihatkan
                  plat nomor secara utuh. Gunakan pencahayaan cukup dan pastikan gambar tidak gelap atau terpotong.
                </p>
                <div class="pd-tl-tags">
                  <span class="pd-tl-tag"><i class="fa-solid fa-camera"></i> Tampak Depan / Samping</span>
                  <span class="pd-tl-tag"><i class="fa-solid fa-hashtag"></i> Plat Nomor Terlihat</span>
                  <span class="pd-tl-tag"><i class="fa-solid fa-image"></i> Min. 300KB</span>
                </div>
                <div class="pd-tl-card">
                  <i class="fa-solid fa-lightbulb"></i>
                  Tips: Foto di tempat terang saat pagi atau siang hari. Hindari foto malam hari atau backlight dari lampu di belakang kendaraan.
                </div>
              </div>
            </div>

            <div class="pd-tl-item" data-aos="fade-up" data-aos-delay="80" data-aos-duration="600">
              <div class="pd-tl-node"><div class="pd-tl-dot"></div></div>
              <div class="pd-tl-content">
                <span class="pd-tl-step-num">Dokumen 02</span>
                <h4 class="pd-tl-title">Foto SIM</h4>
                <p class="pd-tl-desc">
                  Foto SIM yang masih aktif — tampak <strong>depan, seluruh kartu terlihat</strong>,
                  tidak ada bagian yang terpotong. Nama di SIM harus sesuai dengan nama akun siswa yang terdaftar di sistem.
                </p>
                <div class="pd-tl-tags">
                  <span class="pd-tl-tag"><i class="fa-solid fa-id-card"></i> SIM Masih Berlaku</span>
                  <span class="pd-tl-tag"><i class="fa-solid fa-user"></i> Nama Sesuai Akun</span>
                  <span class="pd-tl-tag"><i class="fa-solid fa-eye"></i> Tampak Penuh</span>
                </div>
                <div class="pd-tl-card">
                  <i class="fa-solid fa-lightbulb"></i>
                  Tips: Letakkan SIM di atas permukaan datar berwarna gelap agar kontras dan mudah dibaca oleh admin verifikasi.
                </div>
              </div>
            </div>

            <div class="pd-tl-item" data-aos="fade-up" data-aos-delay="160" data-aos-duration="600">
              <div class="pd-tl-node"><div class="pd-tl-dot"></div></div>
              <div class="pd-tl-content">
                <span class="pd-tl-step-num">Dokumen 03</span>
                <h4 class="pd-tl-title">Kartu Pelajar</h4>
                <p class="pd-tl-desc">
                  Foto kartu pelajar aktif <strong>SMKN 7 Semarang</strong> sebagai bukti identitas siswa.
                  Kartu harus menampilkan nama, kelas, dan tahun ajaran yang masih berlaku sesuai semester pendaftaran.
                </p>
                <div class="pd-tl-tags">
                  <span class="pd-tl-tag"><i class="fa-solid fa-address-card"></i> SMKN 7 Semarang</span>
                  <span class="pd-tl-tag"><i class="fa-solid fa-calendar"></i> T.A. Aktif</span>
                  <span class="pd-tl-tag"><i class="fa-solid fa-school"></i> Kelas Tertera</span>
                </div>
                <div class="pd-tl-card">
                  <i class="fa-solid fa-lightbulb"></i>
                  Tips: Pastikan hologram atau tanda keabsahan kartu terlihat. Kartu yang sudah lewat masa berlakunya akan ditolak sistem.
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- ══ KETENTUAN PARKIR ══════════════════════════════════════ -->
    <div class="pd-ketentuan">

      <div class="pd-ket-header" data-aos="fade-up" data-aos-duration="600">
        <h3 class="pd-ket-title">Ketentuan parkir<br><em>di sekolah</em></h3>
        <span class="pd-ket-count" aria-hidden="true">08</span>
      </div>

      <div data-aos="fade-up" data-aos-delay="60" data-aos-duration="700">
        <div class="pd-ket-row">
          <span class="pd-ket-num">01</span>
          <div class="pd-ket-body">
            <div class="pd-ket-rule">Parkir sesuai jam operasional sekolah</div>
            <p class="pd-ket-sub">Kendaraan tidak boleh ditinggal di area parkir di luar jam sekolah tanpa izin tertulis dari pihak sekolah.</p>
          </div>
          <div class="pd-ket-icon"><i class="fa-solid fa-clock" aria-hidden="true"></i></div>
        </div>
        <div class="pd-ket-row">
          <span class="pd-ket-num">02</span>
          <div class="pd-ket-body">
            <div class="pd-ket-rule">Parkir hanya di zona yang telah ditentukan</div>
            <p class="pd-ket-sub">Dilarang memarkir di koridor, depan kelas, atau area non-parkir meskipun hanya sementara.</p>
          </div>
          <div class="pd-ket-icon"><i class="fa-solid fa-map-pin" aria-hidden="true"></i></div>
        </div>
        <div class="pd-ket-row">
          <span class="pd-ket-num">03</span>
          <div class="pd-ket-body">
            <div class="pd-ket-rule">Turun dan tuntun kendaraan dari gerbang</div>
            <p class="pd-ket-sub">Siswa wajib turun di gerbang masuk dan menuntun kendaraan hingga ke area parkir — tidak boleh dikendarai di dalam komplek sekolah.</p>
          </div>
          <div class="pd-ket-icon"><i class="fa-solid fa-person-walking" aria-hidden="true"></i></div>
        </div>
        <div class="pd-ket-row">
          <span class="pd-ket-num">04</span>
          <div class="pd-ket-body">
            <div class="pd-ket-rule">Helm SNI wajib digunakan saat masuk dan keluar</div>
            <p class="pd-ket-sub">Siswa tanpa helm standar SNI dapat diminta tidak memasuki area sekolah dengan kendaraan bermotor.</p>
          </div>
          <div class="pd-ket-icon"><i class="fa-solid fa-helmet-safety" aria-hidden="true"></i></div>
        </div>
        <div class="pd-ket-row">
          <span class="pd-ket-num">05</span>
          <div class="pd-ket-body">
            <div class="pd-ket-rule">Kunci kendaraan selalu dibawa pemilik</div>
            <p class="pd-ket-sub">Sekolah tidak bertanggung jawab atas kendaraan yang ditinggal tanpa dikunci. Pastikan kendaraan terkunci dengan benar.</p>
          </div>
          <div class="pd-ket-icon"><i class="fa-solid fa-key" aria-hidden="true"></i></div>
        </div>
        <div class="pd-ket-row">
          <span class="pd-ket-num">06</span>
          <div class="pd-ket-body">
            <div class="pd-ket-rule">Hanya pemilik terdaftar yang berhak mengambil</div>
            <p class="pd-ket-sub">Kendaraan hanya boleh dikeluarkan oleh siswa yang namanya tercatat di sistem. Penitipan kendaraan orang lain tidak diperbolehkan.</p>
          </div>
          <div class="pd-ket-icon"><i class="fa-solid fa-user-check" aria-hidden="true"></i></div>
        </div>
        <div class="pd-ket-row">
          <span class="pd-ket-num">07</span>
          <div class="pd-ket-body">
            <div class="pd-ket-rule">Dilarang servis atau modifikasi di area parkir</div>
            <p class="pd-ket-sub">Kegiatan membongkar, memperbaiki, atau memodifikasi kendaraan di dalam area parkir sekolah dilarang keras dalam kondisi apapun.</p>
          </div>
          <div class="pd-ket-icon"><i class="fa-solid fa-ban" aria-hidden="true"></i></div>
        </div>
        <div class="pd-ket-row">
          <span class="pd-ket-num">08</span>
          <div class="pd-ket-body">
            <div class="pd-ket-rule">Laporkan setiap insiden segera</div>
            <p class="pd-ket-sub">Kehilangan, kerusakan, atau kejadian mencurigakan di area parkir harus segera dilaporkan kepada admin sekolah, MPK, atau OSIS.</p>
          </div>
          <div class="pd-ket-icon"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i></div>
        </div>
      </div>
    </div>

  </div><!-- /.container -->
</section>

<script>
(function () {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  var issue = document.getElementById('pd-issue');
  var ticking = false;
  window.addEventListener('scroll', function () {
    if (!ticking) {
      requestAnimationFrame(function () {
        if (issue) issue.style.transform = 'translateY(' + (window.scrollY * 0.12) + 'px)';
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });
})();
</script>