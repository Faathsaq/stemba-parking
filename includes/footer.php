<?php
// sections/footer.php
// FRAGMENT: jangan tambahkan <html>, <head>, atau <body> di file ini.
// Footer — Stemba Parking · SMKN 7 Semarang
// Konsep: Medium · Dark + Amber · Multi kolom · Logo + Sosmed
// Dependensi: Bootstrap 5, AOS, Font Awesome 6, Google Fonts
?>

<style>
/* ============================================================
   FOOTER — MEDIUM · DARK + AMBER · MULTI COLUMN
   ============================================================ */

.ft {
  background: #080808;
  font-family: var(--font-sans, 'Outfit', sans-serif);
  position: relative;
  --ft-accent:      #f59e0b;
  --ft-accent-dim:  rgba(245,158,11,0.15);
  --ft-accent-glow: rgba(245,158,11,0.06);
  --ft-border:      rgba(255,255,255,0.06);
  --ft-border-acc:  rgba(245,158,11,0.24);
  --ft-fg:          #ffffff;
  --ft-fg-mid:      rgba(255,255,255,0.44);
  --ft-fg-low:      rgba(255,255,255,0.18);
}

/* ── TOP AMBER LINE ──────────────────────────────────────────── */
.ft::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
  background: linear-gradient(90deg, transparent, rgba(245,158,11,0.4), transparent);
}

/* ── MAIN FOOTER BODY ────────────────────────────────────────── */
.ft-body {
  padding: 72px 0 56px;
  display: grid;
  grid-template-columns: 1.6fr 1fr 1fr 1fr;
  gap: 0;
  border-bottom: 1px solid var(--ft-border);
}

/* column dividers */
.ft-col {
  padding: 0 48px;
  border-right: 1px solid var(--ft-border);
}
.ft-col:first-child { padding-left: 0; }
.ft-col:last-child  { border-right: none; padding-right: 0; }

/* ── COL 1 — brand ───────────────────────────────────────────── */
.ft-brand {}

/* logo block */
.ft-logo {
  display: flex; align-items: center; gap: 14px;
  margin-bottom: 20px; text-decoration: none;
}
.ft-logo-img {
  width: 44px; height: 44px; object-fit: contain;
  /* shown when image loads */
}
.ft-logo-fallback {
  width: 44px; height: 44px; flex-shrink: 0;
  border: 1px solid var(--ft-border-acc);
  background: var(--ft-accent-glow);
  display: flex; align-items: center; justify-content: center;
  /* shown when image is missing / as overlay */
}
.ft-logo-fallback i { font-size: 18px; color: var(--ft-accent); opacity: 0.8; }
.ft-logo-text {}
.ft-logo-name {
  font-family: var(--font-serif, 'Instrument Serif', serif);
  font-size: 18px; font-weight: 400; color: var(--ft-fg);
  line-height: 1.1; letter-spacing: -0.02em; display: block;
}
.ft-logo-sub {
  font-size: 9px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase;
  color: var(--ft-fg-low); display: block; margin-top: 2px;
}

.ft-brand-desc {
  font-size: 13px; line-height: 1.78; color: var(--ft-fg-mid);
  margin: 0 0 28px; max-width: 34ch;
}

/* sosmed icons */
.ft-socials { display: flex; gap: 8px; }
.ft-social {
  width: 36px; height: 36px;
  border: 1px solid var(--ft-border);
  background: transparent;
  display: flex; align-items: center; justify-content: center;
  text-decoration: none;
  transition: border-color 0.22s, background 0.22s, transform 0.22s;
}
.ft-social i { font-size: 14px; color: var(--ft-fg-low); transition: color 0.22s; }
.ft-social:hover {
  border-color: var(--ft-border-acc);
  background: var(--ft-accent-glow);
  transform: translateY(-3px);
}
.ft-social:hover i { color: var(--ft-accent); }

/* ── SHARED COL STYLE ────────────────────────────────────────── */
.ft-col-label {
  font-size: 9px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--ft-accent); opacity: 0.6;
  padding-bottom: 16px; border-bottom: 1px solid var(--ft-border);
  margin-bottom: 20px; display: block;
}

/* nav links */
.ft-nav { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0; }
.ft-nav li {}
.ft-nav a {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 0;
  font-size: 13px; font-weight: 600; color: var(--ft-fg-mid);
  text-decoration: none; border-bottom: 1px solid var(--ft-border);
  transition: color 0.2s, padding-left 0.2s;
  position: relative;
}
.ft-nav li:last-child a { border-bottom: none; }
.ft-nav a::before {
  content: ''; width: 0; height: 1px; background: var(--ft-accent);
  transition: width 0.22s; flex-shrink: 0;
}
.ft-nav a:hover { color: var(--ft-fg); padding-left: 4px; }
.ft-nav a:hover::before { width: 12px; }

/* ── COL 4 — kontak info ─────────────────────────────────────── */
.ft-contact-item {
  display: flex; align-items: flex-start; gap: 12px;
  padding: 12px 0; border-bottom: 1px solid var(--ft-border);
}
.ft-contact-item:last-of-type { border-bottom: none; }
.ft-contact-icon {
  width: 28px; height: 28px; flex-shrink: 0;
  border: 1px solid var(--ft-border-acc); background: var(--ft-accent-glow);
  display: flex; align-items: center; justify-content: center;
  margin-top: 1px;
}
.ft-contact-icon i { font-size: 10px; color: var(--ft-accent); opacity: 0.7; }
.ft-contact-info {}
.ft-contact-label {
  font-size: 8.5px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase;
  color: var(--ft-fg-low); display: block; margin-bottom: 3px;
}
.ft-contact-val {
  font-size: 12.5px; font-weight: 600; color: var(--ft-fg-mid);
  word-break: break-all; line-height: 1.4;
}

/* ── BOTTOM BAR ──────────────────────────────────────────────── */
.ft-bottom {
  padding: 22px 0;
  display: flex; align-items: center; justify-content: space-between; gap: 20px;
  flex-wrap: wrap;
}
.ft-copyright {
  font-size: 11px; color: var(--ft-fg-low); line-height: 1.6;
}
.ft-copyright strong { color: var(--ft-fg-mid); font-weight: 600; }
.ft-copyright a { color: var(--ft-accent); opacity: 0.7; text-decoration: none; }
.ft-copyright a:hover { opacity: 1; }

.ft-badge {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: 9px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase;
  color: var(--ft-fg-low); padding: 5px 12px;
  border: 1px solid var(--ft-border);
}
.ft-badge i { font-size: 8px; color: var(--ft-accent); opacity: 0.6; }

/* ── RESPONSIVE ──────────────────────────────────────────────── */
@media (max-width: 1100px) {
  .ft-body { grid-template-columns: 1fr 1fr; gap: 48px 0; }
  .ft-col { border-right: none; padding: 0; }
  .ft-col:nth-child(1),
  .ft-col:nth-child(2) { padding-bottom: 40px; border-bottom: 1px solid var(--ft-border); }
  .ft-col:nth-child(2) { padding-left: 40px; }
  .ft-col:nth-child(4) { padding-left: 40px; }
}
@media (max-width: 640px) {
  .ft-body { grid-template-columns: 1fr; gap: 36px; }
  .ft-col { padding: 0 !important; border-bottom: 1px solid var(--ft-border); padding-bottom: 32px !important; }
  .ft-col:last-child { border-bottom: none; padding-bottom: 0 !important; }
  .ft-bottom { flex-direction: column; align-items: flex-start; }
}

/* ── REDUCED MOTION ──────────────────────────────────────────── */
@media (prefers-reduced-motion: reduce) {
  .ft-social:hover { transform: none !important; }
}
</style>

<footer id="footer" class="ft" role="contentinfo" aria-label="Footer Stemba Parking">
  <div class="container">

    <!-- ── MAIN BODY ──────────────────────────────────────────── -->
    <div class="ft-body" data-aos="fade-up" data-aos-duration="700">

      <!-- COL 1 — Brand -->
      <div class="ft-col ft-brand">

        <a href="#beranda" class="ft-logo" aria-label="Stemba Parking — kembali ke atas">
          <!-- logo gambar — ganti src dengan path logo asli -->
          <img
            src="assets/img/logo-smkn7.png"
            alt="Logo SMKN 7 Semarang"
            class="ft-logo-img"
            onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
          >
          <!-- fallback jika gambar tidak ada -->
          <div class="ft-logo-fallback" style="display:none;" aria-hidden="true">
            <i class="fa-solid fa-school"></i>
          </div>
          <div class="ft-logo-text">
            <span class="ft-logo-name">Stemba Parking</span>
            <span class="ft-logo-sub">SMKN 7 Semarang</span>
          </div>
        </a>

        <p class="ft-brand-desc">
          Sistem pendataan kendaraan siswa yang terpusat, aman, dan terverifikasi
          untuk lingkungan SMKN 7 Semarang.
        </p>

        <!-- sosmed -->
        <div class="ft-socials" aria-label="Media sosial sekolah">
          <a class="ft-social" href="#" aria-label="Instagram SMKN 7 Semarang" target="_blank" rel="noopener">
            <i class="fa-brands fa-instagram" aria-hidden="true"></i>
          </a>
          <a class="ft-social" href="#" aria-label="TikTok SMKN 7 Semarang" target="_blank" rel="noopener">
            <i class="fa-brands fa-tiktok" aria-hidden="true"></i>
          </a>
          <a class="ft-social" href="#" aria-label="YouTube SMKN 7 Semarang" target="_blank" rel="noopener">
            <i class="fa-brands fa-youtube" aria-hidden="true"></i>
          </a>
        </div>

      </div>

      <!-- COL 2 — Navigasi -->
      <div class="ft-col">
        <span class="ft-col-label">Navigasi</span>
        <ul class="ft-nav">
          <li><a href="#beranda">Beranda</a></li>
          <li><a href="#tentang">Tentang Sistem</a></li>
          <li><a href="#panduan">Panduan Kendaraan</a></li>
          <li><a href="#alur">Alur Pendaftaran</a></li>
          <li><a href="#kontak">Kontak</a></li>
        </ul>
      </div>

      <!-- COL 3 — Akun -->
      <div class="ft-col">
        <span class="ft-col-label">Akun Siswa</span>
        <ul class="ft-nav">
          <li><a href="register.php">Daftar Kendaraan</a></li>
          <li><a href="dashboard/dashboard-user.php">Masuk Dashboard</a></li>
          <li><a href="status.php">Cek Status</a></li>
          <li><a href="#panduan">Baca Panduan</a></li>
          <li><a href="#kontak">Hubungi Admin</a></li>
        </ul>
      </div>

      <!-- COL 4 — Kontak -->
      <div class="ft-col">
        <span class="ft-col-label">Kontak</span>

        <div class="ft-contact-item">
          <div class="ft-contact-icon"><i class="fa-solid fa-envelope" aria-hidden="true"></i></div>
          <div class="ft-contact-info">
            <span class="ft-contact-label">Email Resmi</span>
            <span class="ft-contact-val">admin@smkn7semarang.sch.id</span>
          </div>
        </div>

        <div class="ft-contact-item">
          <div class="ft-contact-icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></div>
          <div class="ft-contact-info">
            <span class="ft-contact-label">Alamat</span>
            <span class="ft-contact-val">Jl. Simpang Lima, Semarang, Jawa Tengah</span>
          </div>
        </div>

        <div class="ft-contact-item">
          <div class="ft-contact-icon"><i class="fa-solid fa-clock" aria-hidden="true"></i></div>
          <div class="ft-contact-info">
            <span class="ft-contact-label">Jam Layanan</span>
            <span class="ft-contact-val">Sen – Jum · 07.00 – 15.00 WIB</span>
          </div>
        </div>

      </div>

    </div><!-- /.ft-body -->

    <!-- ── BOTTOM BAR ──────────────────────────────────────────── -->
    <div class="ft-bottom">
      <p class="ft-copyright">
        &copy; <?= date('Y') ?> <strong>Stemba Parking</strong> &nbsp;·&nbsp;
        Sistem Pendataan Kendaraan Siswa
        <strong><a href="https://smkn7semarang.sch.id" target="_blank" rel="noopener">SMKN 7 Semarang</a></strong>
        &nbsp;·&nbsp; Dikembangkan oleh MPK &amp; OSIS
      </p>
      <span class="ft-badge">
        <i class="fa-solid fa-circle" aria-hidden="true"></i>
        Stemba Parking v1.0
      </span>
    </div>

  </div>
</footer>