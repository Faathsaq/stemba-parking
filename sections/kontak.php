<?php
// sections/kontak.php
// FRAGMENT: jangan tambahkan <html>, <head>, atau <body> di file ini.
// Section Kontak — Stemba Parking · SMKN 7 Semarang
// Konsep: Minimalis · Dark + Amber · No Frills
// Dependensi: Bootstrap 5, AOS, Font Awesome 6, Google Fonts
?>

<style>
/* ============================================================
   KONTAK — MINIMALIS · DARK + AMBER
   ============================================================ */

.kt {
  background: #0d0d0d;
  font-family: var(--font-sans, 'Outfit', sans-serif);
  --kt-accent:      #f59e0b;
  --kt-accent-dim:  rgba(245,158,11,0.18);
  --kt-accent-glow: rgba(245,158,11,0.07);
  --kt-border:      rgba(255,255,255,0.07);
  --kt-border-acc:  rgba(245,158,11,0.28);
  --kt-fg:          #ffffff;
  --kt-fg-mid:      rgba(255,255,255,0.48);
  --kt-fg-low:      rgba(255,255,255,0.2);
}

/* ── SECTION DIVIDER ─────────────────────────────────────────── */
.kt-section-divider {
  position: relative; height: 1px; background: transparent;
  overflow: visible; margin: 0; z-index: 10;
}
.kt-section-divider::before {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(90deg, transparent, rgba(245,158,11,0.35), transparent);
}
.kt-divider-label {
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

/* ── WRAPPER ─────────────────────────────────────────────────── */
.kt-wrap {
  padding: 100px 0 120px;
  position: relative;
}

/* top border full width */
.kt-wrap::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
  background: linear-gradient(90deg, transparent, var(--kt-border-acc), transparent);
}

/* ── IDENTITY BAR ────────────────────────────────────────────── */
.kt-identity-bar {
  display: flex; align-items: center; gap: 16px;
  padding-bottom: 24px;
  border-bottom: 1px solid var(--kt-border);
  margin-bottom: 80px;
}
.kt-identity-tag {
  display: inline-flex; align-items: center; gap: 8px;
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--kt-accent); padding: 5px 12px;
  border: 1px solid var(--kt-border-acc);
  background: var(--kt-accent-glow);
}
.kt-identity-tag i { font-size: 10px; }
.kt-identity-sep { width: 1px; height: 14px; background: var(--kt-border); }
.kt-identity-desc {
  font-size: 11px; font-weight: 600; letter-spacing: 0.08em;
  color: var(--kt-fg-low);
}

/* ── MAIN LAYOUT ─────────────────────────────────────────────── */
.kt-main {
  display: grid;
  grid-template-columns: 1fr 1px 1fr;
  gap: 0;
  align-items: start;
}
.kt-divider-v {
  background: var(--kt-border); width: 1px; align-self: stretch; margin: 0 64px;
}

/* ── LEFT — headline ─────────────────────────────────────────── */
.kt-left {}
.kt-eyebrow {
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.24em; text-transform: uppercase;
  color: var(--kt-accent); opacity: 0.7; margin-bottom: 20px; display: block;
}
.kt-title {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: clamp(36px, 5vw, 68px);
  font-weight: 400; line-height: 0.95; letter-spacing: -0.03em;
  color: var(--kt-fg); margin: 0 0 28px;
}
.kt-title em { font-style: italic; color: var(--kt-accent); }
.kt-desc {
  font-size: 15px; line-height: 1.82; color: var(--kt-fg-mid);
  max-width: 40ch; margin: 0;
}
.kt-desc strong { color: var(--kt-fg); font-weight: 600; }

/* ── RIGHT — kontak info ─────────────────────────────────────── */
.kt-right {}
.kt-col-label {
  font-size: 9px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--kt-fg-low); padding-bottom: 18px;
  border-bottom: 1px solid var(--kt-border); margin-bottom: 28px; display: block;
}

/* email row */
.kt-email-row {
  display: flex; align-items: flex-start; gap: 16px;
  padding: 24px 20px;
  border: 1px solid var(--kt-border);
  background: rgba(255,255,255,0.02);
  margin-bottom: 12px;
  transition: border-color 0.22s, background 0.22s;
  position: relative;
}
.kt-email-row::before {
  content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 0;
  background: var(--kt-accent); transition: width 0.22s;
}
.kt-email-row:hover { border-color: var(--kt-border-acc); background: rgba(245,158,11,0.03); }
.kt-email-row:hover::before { width: 3px; }

.kt-email-icon {
  width: 40px; height: 40px; flex-shrink: 0;
  border: 1px solid var(--kt-border-acc); background: var(--kt-accent-glow);
  display: flex; align-items: center; justify-content: center;
  transition: background 0.22s, transform 0.22s;
}
.kt-email-icon i { font-size: 15px; color: var(--kt-accent); opacity: 0.8; }
.kt-email-row:hover .kt-email-icon { background: var(--kt-accent-dim); transform: rotate(-8deg) scale(1.1); }

.kt-email-info {}
.kt-email-label {
  font-size: 9px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase;
  color: var(--kt-fg-low); margin-bottom: 5px; display: block;
}
.kt-email-val {
  font-size: 14px; font-weight: 600; color: var(--kt-fg);
  word-break: break-all; line-height: 1.3;
}
.kt-email-sub {
  font-size: 11px; color: var(--kt-fg-low); margin-top: 4px; display: block;
}

/* info notes */
.kt-notes {
  display: flex; flex-direction: column; gap: 0;
  border: 1px solid var(--kt-border);
  margin-bottom: 28px;
}
.kt-note {
  display: flex; align-items: flex-start; gap: 12px;
  padding: 14px 18px;
  border-bottom: 1px solid var(--kt-border);
  font-size: 12px; line-height: 1.6; color: var(--kt-fg-mid);
  transition: background 0.18s;
}
.kt-note:last-child { border-bottom: none; }
.kt-note:hover { background: rgba(255,255,255,0.02); }
.kt-note i { font-size: 11px; color: var(--kt-accent); opacity: 0.6; margin-top: 2px; flex-shrink: 0; }

/* ── BUTTONS ─────────────────────────────────────────────────── */
.kt-actions { display: flex; gap: 10px; flex-wrap: wrap; }

.btn-kt-amber {
  display: inline-flex; align-items: center; gap: 10px;
  background: var(--kt-accent); color: #0d0d0d;
  padding: 14px 28px;
  font-family: var(--font-sans, 'Outfit', sans-serif);
  font-weight: 800; font-size: 12.5px; letter-spacing: 0.06em; text-transform: uppercase;
  text-decoration: none; white-space: nowrap;
  transition: background 0.2s, transform 0.22s, box-shadow 0.22s;
}
.btn-kt-amber:hover {
  background: #fbbf24; color: #0d0d0d; text-decoration: none;
  transform: translateY(-3px); box-shadow: 0 12px 32px rgba(245,158,11,0.28);
}
.btn-kt-amber i { font-size: 11px; transition: transform 0.22s; }
.btn-kt-amber:hover i { transform: translateX(4px); }

.btn-kt-ghost {
  display: inline-flex; align-items: center; gap: 8px;
  background: transparent; color: var(--kt-fg-mid);
  padding: 13px 22px;
  font-family: var(--font-sans, 'Outfit', sans-serif);
  font-weight: 700; font-size: 12.5px; letter-spacing: 0.06em; text-transform: uppercase;
  text-decoration: none; white-space: nowrap;
  border: 1px solid var(--kt-border);
  transition: border-color 0.22s, color 0.22s, background 0.22s;
}
.btn-kt-ghost:hover {
  border-color: var(--kt-border-acc); color: var(--kt-accent);
  background: var(--kt-accent-glow); text-decoration: none;
}
.btn-kt-ghost i { font-size: 11px; }

/* ── FOOTER STRIP ────────────────────────────────────────────── */
.kt-footer-strip {
  margin-top: 80px; padding-top: 32px;
  border-top: 1px solid var(--kt-border);
  display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;
}
.kt-footer-left {
  font-size: 11px; color: var(--kt-fg-low); line-height: 1.6;
}
.kt-footer-left strong { color: var(--kt-fg-mid); font-weight: 600; }
.kt-footer-right {
  font-size: 9.5px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase;
  color: var(--kt-fg-low);
  display: flex; align-items: center; gap: 10px;
}
.kt-footer-right::before {
  content: ''; width: 20px; height: 1px; background: var(--kt-border-acc);
}

/* ── RESPONSIVE ──────────────────────────────────────────────── */
@media (max-width: 992px) {
  .kt-main { grid-template-columns: 1fr; }
  .kt-divider-v { display: none; }
  .kt-left { margin-bottom: 56px; }
  .kt-desc { max-width: 100%; }
  .kt-footer-strip { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 576px) {
  .kt-wrap { padding: 72px 0 100px; }
  .kt-identity-bar { flex-wrap: wrap; }
  .kt-divider-label { font-size: 7.5px; padding: 5px 14px; }
}

/* ── REDUCED MOTION ──────────────────────────────────────────── */
@media (prefers-reduced-motion: reduce) {
  .btn-kt-amber:hover, .kt-email-row:hover .kt-email-icon { transform: none !important; }
}
</style>

<!-- ══ SECTION DIVIDER ════════════════════════════════════════ -->
<div class="kt-section-divider" aria-hidden="true">
  <span class="kt-divider-label">
    <i class="fa-solid fa-chevron-down" style="font-size:8px;margin-right:6px;"></i>
    Selanjutnya · Kontak
  </span>
</div>

<section id="kontak" class="kt" aria-label="Kontak Stemba Parking">
  <div class="kt-wrap">
    <div class="container">

      <!-- identity bar -->
      <div class="kt-identity-bar" data-aos="fade-up" data-aos-duration="500">
        <span class="kt-identity-tag">
          <i class="fa-solid fa-envelope"></i>
          Kontak
        </span>
        <span class="kt-identity-sep" aria-hidden="true"></span>
        <span class="kt-identity-desc">Hubungi admin sekolah untuk pertanyaan seputar sistem parkir</span>
      </div>

      <!-- main -->
      <div class="kt-main">

        <!-- LEFT -->
        <div class="kt-left" data-aos="fade-right" data-aos-duration="700">
          <span class="kt-eyebrow">Butuh Bantuan?</span>
          <h2 class="kt-title">
            Ada pertanyaan?<br>
            <em>Hubungi kami.</em>
          </h2>
          <p class="kt-desc">
            Tim admin sekolah siap membantu pertanyaan seputar pendaftaran,
            verifikasi dokumen, atau kendala penggunaan sistem.
            <strong>Respon biasanya dalam 1 hari kerja.</strong>
          </p>
        </div>

        <!-- vertical divider -->
        <div class="kt-divider-v" aria-hidden="true"></div>

        <!-- RIGHT -->
        <div class="kt-right" data-aos="fade-left" data-aos-delay="100" data-aos-duration="700">

          <span class="kt-col-label">Informasi Kontak</span>

          <!-- email -->
          <div class="kt-email-row">
            <div class="kt-email-icon">
              <i class="fa-solid fa-envelope" aria-hidden="true"></i>
            </div>
            <div class="kt-email-info">
              <span class="kt-email-label">Email Resmi Sekolah</span>
              <div class="kt-email-val">admin@smkn7semarang.sch.id</div>
              <span class="kt-email-sub">Untuk pertanyaan umum &amp; pendaftaran</span>
            </div>
          </div>

          <!-- info notes -->
          <div class="kt-notes">
            <div class="kt-note">
              <i class="fa-solid fa-clock" aria-hidden="true"></i>
              <span>Jam layanan <strong style="color:rgba(255,255,255,0.72)">Senin – Jumat, 07.00 – 15.00 WIB</strong>. Di luar jam tersebut respon mungkin tertunda.</span>
            </div>
            <div class="kt-note">
              <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
              <span>Sertakan <strong style="color:rgba(255,255,255,0.72)">nama lengkap, kelas, dan nomor induk</strong> saat menghubungi admin agar prosesnya lebih cepat.</span>
            </div>
            <div class="kt-note">
              <i class="fa-solid fa-school" aria-hidden="true"></i>
              <span>Untuk urusan mendesak, dapat langsung datang ke <strong style="color:rgba(255,255,255,0.72)">Tata Usaha SMKN 7 Semarang</strong> pada jam sekolah.</span>
            </div>
          </div>

          <!-- buttons -->
          <div class="kt-actions">
            <a class="btn-kt-amber" href="#PLACEHOLDER_ADMIN_URL">
              Hubungi Admin
              <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
            <a class="btn-kt-ghost" href="mailto:admin@smkn7semarang.sch.id">
              <i class="fa-solid fa-envelope" aria-hidden="true"></i>
              Kirim Email
            </a>
          </div>

        </div>
      </div>

      <!-- footer strip -->
      <div class="kt-footer-strip" data-aos="fade-up" data-aos-delay="80" data-aos-duration="600">
        <p class="kt-footer-left">
          &copy; <?= date('Y') ?> <strong>Stemba Parking</strong> &nbsp;·&nbsp;
          Sistem Pendataan Kendaraan Siswa SMKN 7 Semarang &nbsp;·&nbsp;
          Dikembangkan oleh MPK &amp; OSIS
        </p>
        <span class="kt-footer-right">Stemba Parking v1.0</span>
      </div>

    </div>
  </div>
</section>