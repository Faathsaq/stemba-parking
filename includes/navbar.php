<?php
/**
 * navbar.php — Glassmorphism · Dark + Amber
 * Selaras dengan hero.php (Editorial Magazine · Dark + Amber)
 * Dependensi: Bootstrap 5, Font Awesome 6
 */
?>

<style>
/* ============================================================
   NAVBAR — GLASSMORPHISM · DARK + AMBER
   ============================================================ */

:root {
  --nav-font:         'Outfit', sans-serif;
  --nav-font-serif:   'Instrument Serif', Georgia, serif;
  --nav-accent:       #f59e0b;
  --nav-accent-dim:   rgba(245,158,11,0.15);
  --nav-accent-glow:  rgba(245,158,11,0.08);
  --nav-bg-glass:     rgba(13,13,13,0.62);
  --nav-border:       rgba(255,255,255,0.07);
  --nav-border-acc:   rgba(245,158,11,0.28);
  --nav-fg:           #ffffff;
  --nav-fg-mid:       rgba(255,255,255,0.55);
  --nav-fg-low:       rgba(255,255,255,0.22);
  --nav-blur:         18px;
  --nav-h:            68px;
}

/* ── BASE ─────────────────────────────────────────────────── */
.nav-glass {
  position: fixed; top: 0; left: 0; right: 0; z-index: 999;
  height: var(--nav-h);
  background: var(--nav-bg-glass);
  backdrop-filter: blur(var(--nav-blur)) saturate(160%);
  -webkit-backdrop-filter: blur(var(--nav-blur)) saturate(160%);
  border-bottom: 1px solid var(--nav-border);
  font-family: var(--nav-font);
  transition: background 0.3s, box-shadow 0.3s;
}

/* subtle amber line at the very top */
.nav-glass::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0; height: 1px;
  background: linear-gradient(90deg,
    transparent 0%,
    rgba(245,158,11,0.45) 30%,
    rgba(245,158,11,0.7)  50%,
    rgba(245,158,11,0.45) 70%,
    transparent 100%);
  pointer-events: none;
}

/* noise texture overlay */
.nav-glass::after {
  content: '';
  position: absolute; inset: 0; pointer-events: none;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
  background-size: 160px 160px; opacity: 0.5; z-index: 0;
}

/* scrolled state — deeper glass */
.nav-glass.nav-scrolled {
  background: rgba(10,10,10,0.80);
  box-shadow:
    0 1px 0 var(--nav-border),
    0 8px 32px rgba(0,0,0,0.45),
    0 2px 12px rgba(245,158,11,0.04);
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
  position: relative;
}
.nav-brand-logo {
  width: 38px; height: 38px; border-radius: 4px;
  border: 1px solid var(--nav-border-acc);
  background: var(--nav-accent-glow);
  overflow: hidden; display: flex; align-items: center; justify-content: center;
  transition: border-color 0.2s, background 0.2s;
}
.nav-brand-logo img { width: 100%; height: 100%; object-fit: cover; }
.nav-brand:hover .nav-brand-logo {
  border-color: var(--nav-accent); background: var(--nav-accent-dim);
}
.nav-brand-text { line-height: 1.1; }
.nav-brand-name {
  display: block;
  font-size: 13px; font-weight: 800; letter-spacing: 0.06em;
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
.nav-links .nl-item {}
.nav-links .nl-link {
  display: flex; align-items: center; gap: 7px;
  padding: 8px 14px; border-radius: 3px;
  font-size: 11.5px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
  color: var(--nav-fg-mid); text-decoration: none;
  border: 1px solid transparent;
  transition: color 0.2s, background 0.2s, border-color 0.2s;
  white-space: nowrap;
}
.nav-links .nl-link i { font-size: 10px; opacity: 0.7; transition: opacity 0.2s; }
.nav-links .nl-link:hover {
  color: var(--nav-fg); background: rgba(255,255,255,0.04);
  border-color: var(--nav-border);
}
.nav-links .nl-link:hover i { opacity: 1; }
.nav-links .nl-link.active {
  color: var(--nav-accent); background: var(--nav-accent-glow);
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
  padding: 8px 18px; border-radius: 3px;
  font-family: var(--nav-font); font-size: 11.5px; font-weight: 700;
  letter-spacing: 0.08em; text-transform: uppercase;
  color: var(--nav-fg-mid); text-decoration: none;
  border: 1px solid var(--nav-border);
  background: transparent;
  transition: color 0.2s, border-color 0.2s, background 0.2s;
}
.nav-btn-ghost:hover {
  color: var(--nav-fg); border-color: rgba(255,255,255,0.18);
  background: rgba(255,255,255,0.04); text-decoration: none;
}
.nav-btn-ghost i { font-size: 10px; }

/* amber btn */
.nav-btn-amber {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 8px 20px; border-radius: 3px;
  font-family: var(--nav-font); font-size: 11.5px; font-weight: 800;
  letter-spacing: 0.08em; text-transform: uppercase;
  color: #0d0d0d; text-decoration: none;
  background: var(--nav-accent);
  border: 1px solid transparent;
  transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
}
.nav-btn-amber:hover {
  background: #fbbf24; color: #0d0d0d; text-decoration: none;
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(245,158,11,0.28);
}
.nav-btn-amber i { font-size: 10px; }

/* ── AVATAR DROPDOWN ─────────────────────────────────────── */
.nav-avatar-wrap { position: relative; }
.nav-avatar-btn {
  width: 38px; height: 38px; border-radius: 4px;
  border: 1.5px solid var(--nav-border-acc);
  background: var(--nav-accent-glow);
  overflow: hidden; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: border-color 0.2s, box-shadow 0.2s;
  text-decoration: none;
}
.nav-avatar-btn:hover {
  border-color: var(--nav-accent);
  box-shadow: 0 0 0 3px rgba(245,158,11,0.12);
}
.nav-avatar-btn img { width: 100%; height: 100%; object-fit: cover; }
.nav-avatar-btn .nav-avatar-icon {
  font-size: 15px; color: var(--nav-accent);
}

/* pulsing amber ring when logged in */
.nav-avatar-btn::after {
  content: '';
  position: absolute; bottom: -2px; right: -2px;
  width: 9px; height: 9px; border-radius: 50%;
  background: #86efac;
  border: 1.5px solid #0d0d0d;
}

/* dropdown */
.nav-dropdown {
  position: absolute; top: calc(100% + 12px); right: 0;
  min-width: 240px;
  background: rgba(16,16,16,0.92);
  backdrop-filter: blur(24px) saturate(160%);
  -webkit-backdrop-filter: blur(24px) saturate(160%);
  border: 1px solid var(--nav-border);
  border-top: 1px solid var(--nav-border-acc);
  border-radius: 4px;
  box-shadow: 0 24px 60px rgba(0,0,0,0.6), 0 0 0 1px rgba(0,0,0,0.2);
  opacity: 0; visibility: hidden; transform: translateY(-6px);
  transition: opacity 0.22s, transform 0.22s, visibility 0.22s;
  z-index: 100;
}
.nav-avatar-wrap:hover .nav-dropdown,
.nav-avatar-wrap:focus-within .nav-dropdown {
  opacity: 1; visibility: visible; transform: translateY(0);
}

/* glass glint on dropdown */
.nav-dropdown::before {
  content: '';
  position: absolute; top: 0; left: 20px; right: 20px; height: 1px;
  background: linear-gradient(90deg, transparent, var(--nav-accent), transparent);
  opacity: 0.5;
}

.nav-dd-head {
  padding: 20px; text-align: center;
  border-bottom: 1px solid var(--nav-border);
}
.nav-dd-avatar {
  width: 56px; height: 56px; border-radius: 4px; margin: 0 auto 10px;
  border: 2px solid var(--nav-border-acc);
  background: var(--nav-accent-glow);
  overflow: hidden; display: flex; align-items: center; justify-content: center;
}
.nav-dd-avatar img { width: 100%; height: 100%; object-fit: cover; }
.nav-dd-avatar i { font-size: 22px; color: var(--nav-accent); }
.nav-dd-name {
  font-size: 13px; font-weight: 800; color: var(--nav-fg);
  letter-spacing: 0.03em;
}
.nav-dd-meta {
  font-size: 10px; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase;
  color: var(--nav-fg-low); margin-top: 3px;
}
.nav-dd-tag {
  display: inline-block; margin-top: 8px;
  font-size: 9px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase;
  color: var(--nav-accent); padding: 3px 10px;
  border: 1px solid var(--nav-border-acc); border-radius: 2px;
  background: var(--nav-accent-glow);
}

.nav-dd-list { list-style: none; margin: 0; padding: 6px 0; }
.nav-dd-list li { margin: 0; padding: 0; }
.nav-dd-list a {
  display: flex; align-items: center; gap: 11px;
  padding: 10px 18px;
  font-size: 12px; font-weight: 600; letter-spacing: 0.04em;
  color: var(--nav-fg-mid); text-decoration: none;
  transition: background 0.15s, color 0.15s, padding-left 0.15s;
}
.nav-dd-list a i {
  width: 16px; text-align: center; font-size: 11px;
  color: var(--nav-fg-low); transition: color 0.15s;
}
.nav-dd-list a:hover {
  background: rgba(255,255,255,0.04); color: var(--nav-fg);
  padding-left: 22px;
}
.nav-dd-list a:hover i { color: var(--nav-accent); }
.nav-dd-list a.nav-dd-danger { color: rgba(252,165,165,0.7); }
.nav-dd-list a.nav-dd-danger:hover { background: rgba(239,68,68,0.08); color: #fca5a5; }
.nav-dd-list a.nav-dd-danger:hover i { color: #fca5a5; }
.nav-dd-divider { height: 1px; background: var(--nav-border); margin: 4px 0; }

/* ── HAMBURGER (mobile) ──────────────────────────────────── */
.nav-hamburger {
  display: none;
  flex-direction: column; gap: 5px;
  width: 36px; height: 36px; padding: 8px;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--nav-border); border-radius: 3px;
  cursor: pointer; margin-left: auto;
  transition: background 0.2s, border-color 0.2s;
}
.nav-hamburger:hover { background: rgba(255,255,255,0.08); border-color: var(--nav-border-acc); }
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
  background: rgba(10,10,10,0.96);
  backdrop-filter: blur(24px) saturate(160%);
  -webkit-backdrop-filter: blur(24px) saturate(160%);
  border-bottom: 1px solid var(--nav-border-acc);
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
  padding: 12px 16px; border-radius: 3px;
  font-size: 12px; font-weight: 700; letter-spacing: 0.09em; text-transform: uppercase;
  color: var(--nav-fg-mid); text-decoration: none;
  border: 1px solid transparent;
  transition: all 0.2s;
}
.nav-mobile-drawer .nm-link i { font-size: 12px; color: var(--nav-fg-low); }
.nav-mobile-drawer .nm-link:hover {
  color: var(--nav-fg); background: rgba(255,255,255,0.04);
  border-color: var(--nav-border); padding-left: 20px;
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
     NAVBAR
     ═══════════════════════════════════════════════════════════ -->
<nav class="nav-glass" id="mainNav" role="navigation" aria-label="Navigasi utama">
  <div class="nav-inner">

    <!-- ── BRAND ──────────────────────────────────────────── -->
    <a class="nav-brand" href="index.php" aria-label="Stemba Parking — Halaman Utama">
      <div class="nav-brand-logo">
        <img src="assets/img/logo-white.png" alt="Logo SMKN 7">
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
                  <img src="assets/img/profile/<?= htmlspecialchars($_SESSION['foto']) ?>"
                       alt="">
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
      // next frame so transition plays
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