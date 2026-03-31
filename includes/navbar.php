<?php
/**
 * navbar.php — Glassmorphism · Creamy Latte (Light)
 * Putih susu · warm cream · latte brown accent
 * Dependensi: Bootstrap 5, Font Awesome 6
 */
?>

<style>
/* ============================================================
   NAVBAR — GLASSMORPHISM · CREAMY LATTE
   ============================================================ */

:root {
  --nav-font:         'DM Sans', 'Outfit', sans-serif;
  --nav-font-serif:   'Playfair Display', Georgia, serif;

  /* Accent: latte/caramel warm brown */
  --nav-accent: #B8935A;
  --nav-accent-light: #C8A876;
  --nav-accent-dim:   rgba(160,120,58,0.10);
  --nav-accent-glow:  rgba(160,120,58,0.06);

  /* Glass: putih susu translucent */
  --nav-bg-glass: rgba(255,253,245,0.75);
  --nav-bg-scrolled: rgba(255,251,240,0.9);

  /* Borders */
  --nav-border: rgba(200,180,140,0.15);
  --nav-border-acc: rgba(200,180,140,0.22);

  /* Foreground */
  --nav-fg: #3D2E1A;
  --nav-fg-mid: rgba(61,46,26,0.72);
  --nav-fg-low: rgba(61,46,26,0.25);

  /* Blur */
  --nav-blur:         24px;
  --nav-h:            68px;

  /* Cream tones */
  --cream-100:        #FFFCF8;
  --cream-200:        #FAF4EA;
  --cream-300:        #F0E5D0;
  --latte-100: #D4A96A;
  --latte-200: #C09050;
  --latte-300: #B8823A;
}

/* ── BASE ─────────────────────────────────────────────────── */
.nav-glass {
  position: fixed; top: 0; left: 0; right: 0; z-index: 999;
  height: var(--nav-h);
  background: var(--nav-bg-glass);
  backdrop-filter: blur(var(--nav-blur)) saturate(180%) brightness(1.04);
  -webkit-backdrop-filter: blur(var(--nav-blur)) saturate(180%) brightness(1.04);
  border-bottom: 1px solid var(--nav-border);
  box-shadow:
    0 1px 0 rgba(255,255,255,0.9),
    0 4px 24px rgba(140,100,50,0.08),
    0 1px 6px rgba(140,100,50,0.05);
  font-family: var(--nav-font);
  transition: background 0.3s, box-shadow 0.3s;
}

/* warm caramel gradient line at top */
.nav-glass::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0; height: 1.5px;
  background: linear-gradient(90deg,
    transparent 0%,
    rgba(160,120,58,0.30) 25%,
    rgba(198,154,92,0.60) 50%,
    rgba(160,120,58,0.30) 75%,
    transparent 100%);
  pointer-events: none;
}

/* subtle warm paper texture overlay */
.nav-glass::after {
  content: '';
  position: absolute; inset: 0; pointer-events: none; z-index: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
  background-size: 180px 180px; opacity: 0.6;
}

/* scrolled state — richer cream */
.nav-glass.nav-scrolled {
  background: var(--nav-bg-scrolled);
  box-shadow:
    0 1px 0 rgba(255,255,255,0.95),
    0 8px 40px rgba(120,80,30,0.10),
    0 2px 12px rgba(120,80,30,0.06);
}

/* ── INNER ────────────────────────────────────────────────── */
.nav-glass .nav-inner {
  position: relative; z-index: 1;
  max-width: 1280px; margin: 0 auto;
  padding: 0 24px;
  height: var(--nav-h);
  display: flex; align-items: center; gap: 0;
}

/* ── BRAND ────────────────────────────────────────────────── */
.nav-brand {
  display: flex; align-items: center; gap: 12px;
  text-decoration: none; flex-shrink: 0; margin-right: 48px;
}
.nav-brand-logo {
  width: 38px; height: 38px; border-radius: 6px;
  border: 1px solid var(--nav-border-acc);
  background: linear-gradient(135deg, var(--cream-200), var(--cream-300));
  overflow: hidden; display: flex; align-items: center; justify-content: center;
  box-shadow: 0 2px 8px rgba(140,100,50,0.12), inset 0 1px 0 rgba(255,255,255,0.8);
  transition: border-color 0.2s, box-shadow 0.2s;
}
.nav-brand-logo img { width: 100%; height: 100%; object-fit: cover; }
.nav-brand:hover .nav-brand-logo {
  border-color: var(--nav-accent);
  box-shadow: 0 4px 16px rgba(140,100,50,0.20), inset 0 1px 0 rgba(255,255,255,0.9);
}
.nav-brand-text { line-height: 1.1; }
.nav-brand-name {
  display: block;
  font-size: 13px; font-weight: 800; letter-spacing: 0.05em;
  text-transform: uppercase; color: var(--nav-fg);
}
.nav-brand-sub {
  display: block;
  font-size: 9.5px; font-weight: 700; letter-spacing: 0.18em;
  text-transform: uppercase; color: var(--nav-accent);
}

/* ── NAV LINKS ────────────────────────────────────────────── */
.nav-links {
  display: flex; align-items: center; gap: 2px;
  list-style: none; margin: 0; padding: 0; flex: 1;
}
.nav-links .nl-link {
  display: flex; align-items: center; gap: 7px;
  padding: 8px 14px; border-radius: 5px;
  font-size: 11.5px; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase;
  color: var(--nav-fg-mid); text-decoration: none;
  border: 1px solid transparent;
  transition: color 0.2s, background 0.2s, border-color 0.2s, box-shadow 0.2s;
  white-space: nowrap;
}
.nav-links .nl-link i { font-size: 10px; opacity: 0.6; transition: opacity 0.2s; }
.nav-links .nl-link:hover {
  color: var(--nav-fg);
  background: rgba(160,120,58,0.06);
  border-color: var(--nav-border-acc);
  box-shadow: 0 1px 4px rgba(140,100,50,0.06);
}
.nav-links .nl-link:hover i { opacity: 1; }
.nav-links .nl-link.active {
  color: var(--nav-accent);
  background: var(--nav-accent-dim);
  border-color: var(--nav-border-acc);
}

/* ── AUTH AREA ────────────────────────────────────────────── */
.nav-auth {
  display: flex; align-items: center; gap: 10px;
  flex-shrink: 0; margin-left: auto; padding-left: 24px;
  border-left: 1px solid var(--nav-border);
}

/* ghost btn */
.nav-btn-ghost {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 8px 18px; border-radius: 5px;
  font-family: var(--nav-font); font-size: 11.5px; font-weight: 700;
  letter-spacing: 0.07em; text-transform: uppercase;
  color: var(--nav-fg-mid); text-decoration: none;
  border: 1px solid var(--nav-border);
  background: rgba(255,252,248,0.4);
  transition: color 0.2s, border-color 0.2s, background 0.2s, box-shadow 0.2s;
}
.nav-btn-ghost:hover {
  color: var(--nav-fg); border-color: var(--nav-border-acc);
  background: rgba(160,120,58,0.05);
  box-shadow: 0 2px 8px rgba(140,100,50,0.08);
  text-decoration: none;
}
.nav-btn-ghost i { font-size: 10px; }

/* latte btn (CTA) */
.nav-btn-amber {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 8px 20px; border-radius: 5px;
  font-family: var(--nav-font); font-size: 11.5px; font-weight: 800;
  letter-spacing: 0.07em; text-transform: uppercase;
  color: #fff; text-decoration: none;
  background: linear-gradient(135deg, var(--latte-100) 0%, var(--latte-200) 60%, var(--latte-300) 100%);
  border: 1px solid rgba(120,80,25,0.20);
  box-shadow: 0 2px 10px rgba(140,100,50,0.20), inset 0 1px 0 rgba(255,255,255,0.18);
  transition: transform 0.2s, box-shadow 0.2s, filter 0.2s;
}
.nav-btn-amber:hover {
  color: #fff; text-decoration: none;
  transform: translateY(-2px);
  filter: brightness(1.08);
  box-shadow: 0 8px 28px rgba(140,100,50,0.30), inset 0 1px 0 rgba(255,255,255,0.22);
}
.nav-btn-amber i { font-size: 10px; }

/* ── AVATAR DROPDOWN ─────────────────────────────────────── */
.nav-avatar-wrap { position: relative; }
.nav-avatar-btn {
  width: 38px; height: 38px; border-radius: 6px;
  border: 1.5px solid var(--nav-border-acc);
  background: linear-gradient(135deg, var(--cream-200), var(--cream-300));
  overflow: hidden; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 2px 8px rgba(140,100,50,0.10), inset 0 1px 0 rgba(255,255,255,0.9);
  transition: border-color 0.2s, box-shadow 0.2s;
  text-decoration: none;
}
.nav-avatar-btn:hover {
  border-color: var(--nav-accent);
  box-shadow: 0 0 0 3px rgba(160,120,58,0.12), 0 4px 12px rgba(140,100,50,0.14);
}
.nav-avatar-btn img { width: 100%; height: 100%; object-fit: cover; }
.nav-avatar-btn .nav-avatar-icon { font-size: 15px; color: var(--nav-accent); }

/* online indicator */
.nav-avatar-btn::after {
  content: '';
  position: absolute; bottom: -2px; right: -2px;
  width: 9px; height: 9px; border-radius: 50%;
  background: #6bbf84;
  border: 1.5px solid var(--cream-100);
}

/* dropdown */
.nav-dropdown {
  position: absolute; top: calc(100% + 12px); right: 0;
  min-width: 244px;
  background: rgba(255,252,248,0.92);
  backdrop-filter: blur(28px) saturate(200%) brightness(1.05);
  -webkit-backdrop-filter: blur(28px) saturate(200%) brightness(1.05);
  border: 1px solid var(--nav-border);
  border-top: 1px solid var(--nav-border-acc);
  border-radius: 8px;
  box-shadow:
    0 24px 60px rgba(100,70,30,0.14),
    0 4px 16px rgba(100,70,30,0.08),
    0 0 0 1px rgba(255,255,255,0.6) inset;
  opacity: 0; visibility: hidden; transform: translateY(-6px);
  transition: opacity 0.22s, transform 0.22s, visibility 0.22s;
  z-index: 100;
}
.nav-avatar-wrap:hover .nav-dropdown,
.nav-avatar-wrap:focus-within .nav-dropdown {
  opacity: 1; visibility: visible; transform: translateY(0);
}

/* warm glint */
.nav-dropdown::before {
  content: '';
  position: absolute; top: 0; left: 20px; right: 20px; height: 1px;
  background: linear-gradient(90deg, transparent, rgba(198,154,92,0.6), transparent);
}

.nav-dd-head {
  padding: 20px; text-align: center;
  border-bottom: 1px solid var(--nav-border);
  background: linear-gradient(180deg, rgba(240,229,208,0.3) 0%, transparent 100%);
  border-radius: 8px 8px 0 0;
}
.nav-dd-avatar {
  width: 56px; height: 56px; border-radius: 6px; margin: 0 auto 10px;
  border: 2px solid var(--nav-border-acc);
  background: linear-gradient(135deg, var(--cream-200), var(--cream-300));
  box-shadow: 0 4px 12px rgba(140,100,50,0.12);
  overflow: hidden; display: flex; align-items: center; justify-content: center;
}
.nav-dd-avatar img { width: 100%; height: 100%; object-fit: cover; }
.nav-dd-avatar i { font-size: 22px; color: var(--nav-accent); }
.nav-dd-name {
  font-size: 13px; font-weight: 800; color: var(--nav-fg);
  letter-spacing: 0.02em;
}
.nav-dd-meta {
  font-size: 10px; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase;
  color: var(--nav-fg-low); margin-top: 3px;
}
.nav-dd-tag {
  display: inline-block; margin-top: 8px;
  font-size: 9px; font-weight: 800; letter-spacing: 0.13em; text-transform: uppercase;
  color: var(--nav-accent); padding: 3px 10px;
  border: 1px solid var(--nav-border-acc); border-radius: 3px;
  background: var(--nav-accent-dim);
}

.nav-dd-list { list-style: none; margin: 0; padding: 6px 0; }
.nav-dd-list li { margin: 0; padding: 0; }
.nav-dd-list a {
  display: flex; align-items: center; gap: 11px;
  padding: 10px 18px;
  font-size: 12px; font-weight: 600; letter-spacing: 0.03em;
  color: var(--nav-fg-mid); text-decoration: none;
  transition: background 0.15s, color 0.15s, padding-left 0.15s;
}
.nav-dd-list a i {
  width: 16px; text-align: center; font-size: 11px;
  color: var(--nav-fg-low); transition: color 0.15s;
}
.nav-dd-list a:hover {
  background: rgba(160,120,58,0.06); color: var(--nav-fg);
  padding-left: 22px;
}
.nav-dd-list a:hover i { color: var(--nav-accent); }
.nav-dd-list a.nav-dd-danger { color: rgba(185,60,60,0.85); }
.nav-dd-list a.nav-dd-danger:hover { background: rgba(220,60,60,0.06); color: #c0392b; }
.nav-dd-list a.nav-dd-danger:hover i { color: #c0392b; }
.nav-dd-divider { height: 1px; background: var(--nav-border); margin: 4px 0; }

/* ── HAMBURGER (mobile) ──────────────────────────────────── */
.nav-hamburger {
  display: none;
  flex-direction: column; gap: 5px;
  width: 36px; height: 36px; padding: 8px;
  background: rgba(160,120,58,0.04);
  border: 1px solid var(--nav-border); border-radius: 5px;
  cursor: pointer; margin-left: auto;
  transition: background 0.2s, border-color 0.2s;
}
.nav-hamburger:hover { background: var(--nav-accent-dim); border-color: var(--nav-border-acc); }
.nav-hamburger span {
  display: block; height: 1.5px; width: 100%;
  background: var(--nav-fg-mid);
  transition: transform 0.28s, opacity 0.28s, background 0.2s;
  transform-origin: center;
}
.nav-hamburger.is-open span:nth-child(1) { transform: translateY(6.5px) rotate(45deg); background: var(--nav-accent); }
.nav-hamburger.is-open span:nth-child(2) { opacity: 0; }
.nav-hamburger.is-open span:nth-child(3) { transform: translateY(-6.5px) rotate(-45deg); background: var(--nav-accent); }

/* ── MOBILE DRAWER ───────────────────────────────────────── */
.nav-mobile-drawer {
  position: fixed; top: var(--nav-h); left: 0; right: 0;
  background: rgba(255,252,248,0.96);
  backdrop-filter: blur(24px) saturate(180%);
  -webkit-backdrop-filter: blur(24px) saturate(180%);
  border-bottom: 1px solid var(--nav-border-acc);
  box-shadow: 0 12px 40px rgba(100,70,30,0.12);
  padding: 24px;
  display: none; flex-direction: column; gap: 4px;
  z-index: 998;
  transform: translateY(-8px); opacity: 0;
  transition: transform 0.3s, opacity 0.3s;
}
.nav-mobile-drawer.is-open {
  display: flex; transform: translateY(0); opacity: 1;
}
.nav-mobile-drawer .nm-link {
  display: flex; align-items: center; gap: 12px;
  padding: 12px 16px; border-radius: 5px;
  font-size: 12px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
  color: var(--nav-fg-mid); text-decoration: none;
  border: 1px solid transparent;
  transition: all 0.2s;
}
.nav-mobile-drawer .nm-link i { font-size: 12px; color: var(--nav-fg-low); }
.nav-mobile-drawer .nm-link:hover {
  color: var(--nav-fg); background: var(--nav-accent-dim);
  border-color: var(--nav-border-acc); padding-left: 20px;
}
.nav-mobile-drawer .nm-link:hover i { color: var(--nav-accent); }

.nm-divider { height: 1px; background: var(--nav-border); margin: 12px 0; }
.nm-auth { display: flex; flex-direction: column; gap: 8px; }
.nm-auth .nav-btn-amber,
.nm-auth .nav-btn-ghost { width: 100%; justify-content: center; padding: 12px; font-size: 12px; }

/* ── RESPONSIVE ─────────────────────────────────────────── */
@media (max-width: 992px) {
  .nav-links, .nav-auth { display: none !important; }
  .nav-hamburger { display: flex; }
  .nav-brand { margin-right: auto; }
}

@media (prefers-reduced-motion: reduce) {
  .nav-glass, .nav-btn-amber, .nav-avatar-btn,
  .nav-dropdown, .nav-dd-list a, .nav-hamburger span { transition: none !important; }
}
</style>

<!-- ═══════════════════════════════════════════════════════════
     NAVBAR — CREAMY LATTE GLASS
     ═══════════════════════════════════════════════════════════ -->
<nav class="nav-glass" id="mainNav" role="navigation" aria-label="Navigasi utama">
  <div class="nav-inner">

    <!-- ── BRAND ──────────────────────────────────────────── -->
    <a class="nav-brand" href="index.php" aria-label="Stemba Parking — Halaman Utama">
      <div class="nav-brand-logo">
        <img src="assets/img/3.png" alt="Logo SMKN 7">
      </div>
      <div class="nav-brand-text">
        <span class="nav-brand-name">SMK Negeri 7</span>
        <span class="nav-brand-sub">Stemba Parking</span>
      </div>
    </a>

    <!-- ── NAV LINKS ──────────────────────────────────────── -->
    <ul class="nav-links" role="list">
      <li class="nl-item">
        <a class="nl-link" href="#tentang">
          <i class="fas fa-info-circle" aria-hidden="true"></i>Tentang
        </a>
      </li>
      <li class="nl-item">
        <a class="nl-link" href="#panduan">
          <i class="fas fa-book" aria-hidden="true"></i>Panduan Kendaraan
        </a>
      </li>
      <li class="nl-item">
        <a class="nl-link" href="#alur">
          <i class="fas fa-route" aria-hidden="true"></i>Alur Pendaftaran
        </a>
      </li>
      <li class="nl-item">
        <a class="nl-link" href="#kontak">
          <i class="fas fa-phone" aria-hidden="true"></i>Kontak
        </a>
      </li>
    </ul>

    <!-- ── AUTH AREA ──────────────────────────────────────── -->
    <div class="nav-auth">

      <?php if (isset($_SESSION['user_id'])): ?>
        <!-- ── LOGGED IN: Avatar + Dropdown ──────────────── -->
        <div class="nav-avatar-wrap">
          <a href="#" class="nav-avatar-btn" aria-label="Menu profil pengguna" aria-haspopup="true">
            <?php if (!empty($_SESSION['foto'])): ?>
              <img src="assets/img/profile/<?= htmlspecialchars($_SESSION['foto']) ?>"
                   alt="Foto profil <?= htmlspecialchars($_SESSION['username']) ?>">
            <?php else: ?>
              <i class="fas fa-user nav-avatar-icon" aria-hidden="true"></i>
            <?php endif; ?>
          </a>

          <!-- glass dropdown -->
          <div class="nav-dropdown" role="menu">

            <!-- head -->
            <div class="nav-dd-head">
              <div class="nav-dd-avatar">
                <?php if (!empty($_SESSION['foto'])): ?>
                  <img src="assets/img/profile/<?= htmlspecialchars($_SESSION['foto']) ?>" alt="">
                <?php else: ?>
                  <i class="fas fa-user-circle" aria-hidden="true"></i>
                <?php endif; ?>
              </div>
              <div class="nav-dd-name"><?= htmlspecialchars($_SESSION['username']) ?></div>
              <div class="nav-dd-meta">
                Kelas <?= htmlspecialchars($_SESSION['kelas'] ?? '—') ?>
                &nbsp;·&nbsp;
                <?= htmlspecialchars($_SESSION['jurusan'] ?? '—') ?>
              </div>
              <span class="nav-dd-tag">
                <?= ($_SESSION['role'] === 'admin') ? 'Administrator' : 'Siswa' ?>
              </span>
            </div>

            <!-- links -->
            <ul class="nav-dd-list" role="menu">
              <?php if ($_SESSION['role'] === 'admin'): ?>
              <li>
                <a href="admin/index.php" role="menuitem">
                  <i class="fas fa-user-shield" aria-hidden="true"></i>Admin Panel
                </a>
              </li>
              <li><div class="nav-dd-divider" aria-hidden="true"></div></li>
              <?php endif; ?>
              <li>
                <a href="edit-profile.php" role="menuitem">
                  <i class="fas fa-user-edit" aria-hidden="true"></i>Edit Profil
                </a>
              </li>
              <li>
                <a href="dashboard/dashboard-user.php" role="menuitem">
                  <i class="fas fa-tachometer-alt" aria-hidden="true"></i>Dashboard
                </a>
              </li>
              <li><div class="nav-dd-divider" aria-hidden="true"></div></li>
              <li>
                <a href="logout.php" class="nav-dd-danger" role="menuitem">
                  <i class="fas fa-sign-out-alt" aria-hidden="true"></i>Logout
                </a>
              </li>
            </ul>

          </div><!-- /nav-dropdown -->
        </div><!-- /nav-avatar-wrap -->

      <?php else: ?>
        <!-- ── GUEST: Login + Register ────────────────────── -->
        <a href="login.php" class="nav-btn-ghost">
          <i class="fas fa-sign-in-alt" aria-hidden="true"></i>Login
        </a>
        <a href="register.php" class="nav-btn-amber">
          <i class="fas fa-user-plus" aria-hidden="true"></i>Daftar
        </a>
      <?php endif; ?>

    </div><!-- /nav-auth -->

    <!-- ── HAMBURGER ──────────────────────────────────────── -->
    <button class="nav-hamburger" id="navHamburger"
            aria-label="Toggle menu" aria-expanded="false">
      <span></span>
      <span></span>
      <span></span>
    </button>

  </div><!-- /nav-inner -->
</nav>

<!-- ── MOBILE DRAWER ──────────────────────────────────────── -->
<div class="nav-mobile-drawer" id="navMobileDrawer" role="dialog" aria-label="Menu mobile">

  <a class="nm-link" href="#tentang">
    <i class="fas fa-info-circle" aria-hidden="true"></i>Tentang
  </a>
  <a class="nm-link" href="#panduan">
    <i class="fas fa-book" aria-hidden="true"></i>Panduan Kendaraan
  </a>
  <a class="nm-link" href="#alur">
    <i class="fas fa-route" aria-hidden="true"></i>Alur Pendaftaran
  </a>
  <a class="nm-link" href="#kontak">
    <i class="fas fa-phone" aria-hidden="true"></i>Kontak
  </a>

  <div class="nm-divider" aria-hidden="true"></div>

  <div class="nm-auth">
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="dashboard/dashboard-user.php" class="nav-btn-amber">
        <i class="fas fa-tachometer-alt" aria-hidden="true"></i>Dashboard
      </a>
      <a href="logout.php" class="nav-btn-ghost">
        <i class="fas fa-sign-out-alt" aria-hidden="true"></i>Logout
      </a>
    <?php else: ?>
      <a href="register.php" class="nav-btn-amber">
        <i class="fas fa-user-plus" aria-hidden="true"></i>Daftar Kendaraan
      </a>
      <a href="login.php" class="nav-btn-ghost">
        <i class="fas fa-sign-in-alt" aria-hidden="true"></i>Login
      </a>
    <?php endif; ?>
  </div>

</div>

<!-- spacer so content isn't hidden behind fixed navbar -->
<div style="height: var(--nav-h);" aria-hidden="true"></div>

<script>
(function () {
  'use strict';

  var nav      = document.getElementById('mainNav');
  var burger   = document.getElementById('navHamburger');
  var drawer   = document.getElementById('navMobileDrawer');
  var isOpen   = false;

  /* ── scroll: deeper glass ───────────────────────────────── */
  var ticking = false;
  window.addEventListener('scroll', function () {
    if (!ticking) {
      requestAnimationFrame(function () {
        nav.classList.toggle('nav-scrolled', window.scrollY > 30);
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });

  /* ── hamburger ──────────────────────────────────────────── */
  burger.addEventListener('click', function () {
    isOpen = !isOpen;
    burger.classList.toggle('is-open', isOpen);
    burger.setAttribute('aria-expanded', isOpen);

    if (isOpen) {
      drawer.style.display = 'flex';
      requestAnimationFrame(function () { drawer.classList.add('is-open'); });
    } else {
      drawer.classList.remove('is-open');
      drawer.addEventListener('transitionend', function onEnd() {
        drawer.removeEventListener('transitionend', onEnd);
        if (!isOpen) drawer.style.display = 'none';
      });
    }
  });

  /* ── close drawer on link click ─────────────────────────── */
  drawer.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () {
      if (!isOpen) return;
      isOpen = false;
      burger.classList.remove('is-open');
      burger.setAttribute('aria-expanded', 'false');
      drawer.classList.remove('is-open');
      setTimeout(function () { drawer.style.display = 'none'; }, 300);
    });
  });

  /* ── active link on scroll ──────────────────────────────── */
  var sections = document.querySelectorAll('section[id], div[id]');
  var links    = document.querySelectorAll('.nl-link[href^="#"]');

  if (sections.length && links.length) {
    var obs = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          links.forEach(function (l) {
            l.classList.toggle('active', l.getAttribute('href') === '#' + e.target.id);
          });
        }
      });
    }, { rootMargin: '-40% 0px -55% 0px', threshold: 0 });
    sections.forEach(function (s) { obs.observe(s); });
  }
})();
</script> 