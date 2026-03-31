<?php
// admin/index.php — Landing Page Admin · Stemba Parking
// Guard: hanya admin yang bisa akses
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// ── PAGINATION ────────────────────────────────────────────
$per_page = 9;
$page     = max(1, (int)($_GET['page'] ?? 1));
$offset   = ($page - 1) * $per_page;

// filter status
$filter = $_GET['status'] ?? 'semua';
$where  = '';
$params = [];
if (in_array($filter, ['menunggu','disetujui','ditolak'])) {
    $where  = 'WHERE status = ?';
    $params = [$filter];
}

// total
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM pendaftaran $where");
$count_stmt->execute($params);
$total       = (int)$count_stmt->fetchColumn();
$total_pages = max(1, ceil($total / $per_page));
$page        = min($page, $total_pages);
$offset      = ($page - 1) * $per_page;

// data — semua kolom dari tabel pendaftaran
$sql = "SELECT * FROM pendaftaran $where ORDER BY created_at DESC LIMIT $per_page OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$kendaraan = $stmt->fetchAll();

// stats
$stats = $pdo->query("
    SELECT
      COUNT(*)                    AS total,
      SUM(status='menunggu')      AS menunggu,
      SUM(status='disetujui')     AS disetujui,
      SUM(status='ditolak')       AS ditolak
    FROM pendaftaran
")->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel — Stemba Parking · SMKN 7 Semarang</title>

  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
/* ============================================================
   ADMIN — CREAMY LATTE · EDITORIAL (selaras navbar.php)
   ============================================================ */
:root {
  --font-serif:   'Instrument Serif', Georgia, serif;
  --font-sans:    'Outfit', sans-serif;

  /* Accent: Latte/caramel — identik dengan navbar.php */
  --accent:       #B8935A;
  --accent-lt:    #C8A876;
  --accent-dim:   rgba(160,120,58,0.10);
  --accent-glow:  rgba(160,120,58,0.06);

  /* Background — cream/ivory */
  --bg:           #FFFCF8;
  --bg-card:      #ffffff;

  /* Foreground — warm dark brown, identik navbar.php */
  --fg:           #3D2E1A;
  --fg-mid:       rgba(61,46,26,0.72);
  --fg-low:       rgba(61,46,26,0.35);

  /* Borders — warm cream */
  --border:       rgba(200,180,140,0.20);
  --border-acc:   rgba(200,180,140,0.35);

  --nav-h:        68px;

  /* Latte tones — button & icon, identik navbar.php */
  --latte-100:    #D4A96A;
  --latte-200:    #C09050;
  --latte-300:    #B8823A;

  /* Status colors */
  --green:        #4a7c59;
  --green-bg:     rgba(74,124,89,0.09);
  --yellow:       #92700a;
  --yellow-bg:    rgba(146,112,10,0.09);
  --red:          #9b2c2c;
  --red-bg:       rgba(155,44,44,0.08);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html, body {
  background: var(--bg);
  color: var(--fg);
  font-family: var(--font-sans);
  min-height: 100vh;
}

/* ── NOISE — warm paper texture, identik navbar.php ─────── */
body::before {
  content: '';
  position: fixed; inset: 0; z-index: 0; pointer-events: none;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
  background-size: 180px; opacity: 0.6;
}

/* ── GLOW — warm latte ambient ──────────────────────────── */
.adm-glow {
  position: fixed; z-index: 0; pointer-events: none;
  top: -180px; right: -120px;
  width: 700px; height: 700px; border-radius: 50%;
  background: radial-gradient(ellipse, rgba(160,120,58,0.07) 0%, transparent 65%);
  filter: blur(60px);
}

/* ── TOPBAR — glass cream, identik dengan nav-glass ─────── */
.adm-topbar {
  position: fixed; top: 0; left: 0; right: 0; z-index: 999;
  height: var(--nav-h);
  background: rgba(255,253,245,0.75);
  backdrop-filter: blur(24px) saturate(180%) brightness(1.04);
  -webkit-backdrop-filter: blur(24px) saturate(180%) brightness(1.04);
  border-bottom: 1px solid var(--border);
  box-shadow:
    0 1px 0 rgba(255,255,255,0.9),
    0 4px 24px rgba(140,100,50,0.08),
    0 1px 6px rgba(140,100,50,0.05);
  transition: background 0.3s, box-shadow 0.3s;
}

/* warm caramel gradient line — identik navbar::before */
.adm-topbar::before {
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

/* warm paper texture overlay — identik navbar::after */
.adm-topbar::after {
  content: '';
  position: absolute; inset: 0; pointer-events: none; z-index: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
  background-size: 180px 180px; opacity: 0.6;
}

/* scrolled state */
.adm-topbar.nav-scrolled {
  background: rgba(255,251,240,0.9);
  box-shadow:
    0 1px 0 rgba(255,255,255,0.95),
    0 8px 40px rgba(120,80,30,0.10),
    0 2px 12px rgba(120,80,30,0.06);
}

.adm-topbar-inner {
  position: relative; z-index: 1;
  max-width: 1400px; margin: 0 auto; padding: 0 32px;
  height: var(--nav-h);
  display: flex; align-items: center; gap: 0;
}

/* ── BRAND — identik .nav-brand ─────────────────────────── */
.adm-brand {
  display: flex; align-items: center; gap: 12px;
  text-decoration: none; flex-shrink: 0; margin-right: 20px;
}
.adm-brand-logo {
  width: 38px; height: 38px; border-radius: 6px;
  border: 1px solid var(--border-acc);
  background: linear-gradient(135deg, #FAF4EA, #F0E5D0);
  overflow: hidden; display: flex; align-items: center; justify-content: center;
  box-shadow: 0 2px 8px rgba(140,100,50,0.12), inset 0 1px 0 rgba(255,255,255,0.8);
  transition: border-color 0.2s, box-shadow 0.2s;
}
.adm-brand-logo img { width: 100%; height: 100%; object-fit: cover; }
.adm-brand:hover .adm-brand-logo {
  border-color: var(--accent);
  box-shadow: 0 4px 16px rgba(140,100,50,0.20), inset 0 1px 0 rgba(255,255,255,0.9);
}
.adm-brand-name {
  display: block;
  font-size: 13px; font-weight: 800; letter-spacing: 0.05em;
  text-transform: uppercase; color: var(--fg);
}
.adm-brand-sub {
  display: block;
  font-size: 9.5px; font-weight: 700; letter-spacing: 0.18em;
  text-transform: uppercase; color: var(--accent);
}

/* ── ADMIN TAG — identik .nav-dd-tag ────────────────────── */
.adm-tag {
  font-size: 8.5px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase;
  color: var(--accent); padding: 3px 10px;
  border: 1px solid var(--border-acc); border-radius: 3px;
  background: var(--accent-dim); flex-shrink: 0;
}

/* ── NAV MENU — identik .nav-links ──────────────────────── */
.adm-nav {
  display: flex; gap: 2px; margin-left: 20px;
}
.adm-nav-link {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 8px 14px; border-radius: 5px;
  font-size: 11.5px; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase;
  text-decoration: none; color: var(--fg-mid);
  border: 1px solid transparent;
  transition: color 0.2s, background 0.2s, border-color 0.2s, box-shadow 0.2s;
  white-space: nowrap;
}
.adm-nav-link i { font-size: 10px; opacity: 0.6; transition: opacity 0.2s; }
.adm-nav-link:hover {
  color: var(--fg); background: rgba(160,120,58,0.06);
  border-color: var(--border-acc);
  box-shadow: 0 1px 4px rgba(140,100,50,0.06);
  text-decoration: none;
}
.adm-nav-link:hover i { opacity: 1; }
.adm-nav-link.active {
  color: var(--accent);
  background: var(--accent-dim);
  border-color: var(--border-acc);
}

/* ── TOPBAR RIGHT ───────────────────────────────────────── */
.adm-topbar-right {
  margin-left: auto; display: flex; align-items: center;
  gap: 14px; padding-left: 24px;
  border-left: 1px solid var(--border);
}
.adm-admin-info { text-align: right; }
.adm-admin-name {
  font-size: 13px; font-weight: 700; color: var(--fg); display: block;
}
.adm-admin-role {
  font-size: 9px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase;
  color: var(--accent); display: block;
}

/* ── LOGOUT — identik .nav-btn-ghost ────────────────────── */
.adm-logout {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 8px 18px; border-radius: 5px;
  font-family: var(--font-sans); font-size: 11.5px; font-weight: 700;
  letter-spacing: 0.07em; text-transform: uppercase;
  color: var(--fg-mid); text-decoration: none;
  border: 1px solid var(--border);
  background: rgba(255,252,248,0.4);
  transition: color 0.2s, border-color 0.2s, background 0.2s, box-shadow 0.2s;
}
.adm-logout:hover {
  color: var(--red); border-color: rgba(155,44,44,0.25);
  background: var(--red-bg);
  box-shadow: 0 2px 8px rgba(155,44,44,0.08);
  text-decoration: none;
}
.adm-logout i { font-size: 10px; }

/* ── MAIN WRAP ──────────────────────────────────────────── */
.adm-wrap {
  position: relative; z-index: 1;
  max-width: 1400px; margin: 0 auto;
  padding: calc(var(--nav-h) + 48px) 32px 80px;
}

/* ── PAGE HEADER ────────────────────────────────────────── */
.adm-page-header {
  border-bottom: 1px solid var(--border);
  padding-bottom: 32px;
  margin-bottom: 40px;
}
.adm-overline {
  font-size: 9px; font-weight: 800; letter-spacing: 0.24em; text-transform: uppercase;
  color: var(--accent); display: block; margin-bottom: 12px;
}
.adm-page-title {
  font-family: var(--font-serif);
  font-size: clamp(36px, 5vw, 64px);
  font-weight: 400; line-height: 0.95; letter-spacing: -0.02em;
  color: var(--fg); margin-bottom: 12px;
}
.adm-page-title em { color: var(--accent); font-style: italic; }
.adm-page-desc {
  font-size: 14px; color: var(--fg-mid); line-height: 1.7; max-width: 52ch;
}

/* ── STAT STRIP ─────────────────────────────────────────── */
.adm-stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  border: 1px solid var(--border);
  margin-bottom: 40px;
  background: var(--bg-card);
}
.adm-stat {
  padding: 24px 28px;
  border-right: 1px solid var(--border);
  transition: background 0.2s;
}
.adm-stat:last-child { border-right: none; }
.adm-stat:hover { background: var(--accent-dim); }
.adm-stat-num {
  font-family: var(--font-serif);
  font-size: 44px; font-weight: 400; line-height: 1;
  letter-spacing: -0.02em; display: block;
}
.adm-stat-num.acc    { color: var(--accent); }
.adm-stat-num.green  { color: var(--green); }
.adm-stat-num.yellow { color: var(--yellow); }
.adm-stat-num.red    { color: var(--red); }
.adm-stat-lbl {
  font-size: 9px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase;
  color: var(--fg-low); display: block; margin-top: 6px;
}

/* ── FILTER BAR ─────────────────────────────────────────── */
.adm-filter-bar {
  display: flex; align-items: center; justify-content: space-between;
  gap: 16px; margin-bottom: 28px; flex-wrap: wrap;
}
.adm-filter-label {
  font-size: 9px; font-weight: 800; letter-spacing: 0.2em; text-transform: uppercase;
  color: var(--fg-low);
}
.adm-filter-tabs { display: flex; gap: 4px; }
.adm-filter-tab {
  padding: 8px 16px; border-radius: 5px;
  font-size: 11.5px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase;
  text-decoration: none; color: var(--fg-mid);
  border: 1px solid var(--border);
  background: transparent;
  transition: all 0.18s;
}
.adm-filter-tab:hover {
  color: var(--fg); background: rgba(160,120,58,0.06);
  border-color: var(--border-acc); text-decoration: none;
}
.adm-filter-tab.active {
  color: var(--accent); background: var(--accent-dim); border-color: var(--border-acc);
}
.adm-results-info {
  font-size: 11px; color: var(--fg-low); font-weight: 600;
}

/* ── CARD GRID ──────────────────────────────────────────── */
.adm-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1px;
  background: var(--border);
  border: 1px solid var(--border);
  margin-bottom: 1px;
}

/* ── VEHICLE CARD ───────────────────────────────────────── */
.adm-vcard {
  background: var(--bg-card);
  padding: 0;
  position: relative; overflow: hidden;
  transition: background 0.2s;
  display: flex; flex-direction: column;
}
.adm-vcard:hover { background: #fdfcf5; }
.adm-vcard:hover .adm-vcard-accent { opacity: 1; }

.adm-vcard-accent {
  position: absolute; top: 0; left: 0; right: 0; height: 2px;
  background: linear-gradient(90deg, var(--latte-100), var(--latte-200));
  opacity: 0; transition: opacity 0.2s;
}

/* foto */
.adm-vcard-foto {
  width: 100%; aspect-ratio: 16/9; overflow: hidden;
  background: var(--accent-dim);
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  position: relative;
}
.adm-vcard-foto img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform 0.4s ease;
}
.adm-vcard:hover .adm-vcard-foto img { transform: scale(1.04); }
.adm-vcard-foto-placeholder {
  font-size: 32px; color: rgba(160,120,58,0.22);
}
.adm-vcard-foto-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to bottom, transparent 60%, rgba(61,46,26,0.12) 100%);
}

/* badge status */
.adm-vcard-status {
  position: absolute; top: 12px; right: 12px;
  font-size: 8.5px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase;
  padding: 4px 10px; border-radius: 3px;
  border: 1px solid transparent;
}
.vs-disetujui { background: var(--green-bg); color: var(--green); border-color: rgba(74,124,89,0.2); }
.vs-menunggu  { background: var(--yellow-bg); color: var(--yellow); border-color: rgba(146,112,10,0.2); }
.vs-ditolak   { background: var(--red-bg); color: var(--red); border-color: rgba(155,44,44,0.18); }

/* body */
.adm-vcard-body { padding: 20px 22px; flex: 1; display: flex; flex-direction: column; gap: 0; }

.adm-vcard-merek {
  font-family: var(--font-serif);
  font-size: 20px; font-weight: 400; color: var(--fg);
  line-height: 1.1; margin-bottom: 4px; letter-spacing: -0.01em;
}
.adm-vcard-tipe {
  font-size: 11px; color: var(--fg-low); font-weight: 600;
  letter-spacing: 0.04em; margin-bottom: 16px;
}

.adm-vcard-row {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 0;
  border-top: 1px solid var(--border);
  font-size: 11.5px;
}
.adm-vcard-row:first-of-type { border-top: none; padding-top: 0; }
.adm-vcard-row i {
  width: 14px; text-align: center;
  font-size: 10px; color: var(--accent); flex-shrink: 0;
}
.adm-vcard-row-lbl { color: var(--fg-low); font-weight: 600; min-width: 60px; font-size: 10.5px; }
.adm-vcard-row-val { color: var(--fg); font-weight: 700; flex: 1; }

/* plat */
.adm-vcard-plat {
  font-family: ui-monospace, 'Courier New', monospace;
  font-size: 13px; font-weight: 700; color: var(--accent);
  letter-spacing: 0.1em;
}

/* footer */
.adm-vcard-foot {
  padding: 12px 22px;
  border-top: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
  background: rgba(61,46,26,0.015);
}
.adm-vcard-date {
  font-size: 10px; color: var(--fg-low); font-weight: 600;
  letter-spacing: 0.04em;
}
.adm-vcard-action {
  font-size: 10px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase;
  color: var(--accent); text-decoration: none;
  display: flex; align-items: center; gap: 5px;
  transition: gap 0.2s, color 0.2s;
}
.adm-vcard-action:hover { gap: 8px; text-decoration: none; color: var(--accent-lt); }
.adm-vcard-action i { font-size: 9px; }

/* empty state */
.adm-empty {
  grid-column: 1/-1;
  padding: 80px 40px; text-align: center; background: var(--bg-card);
}
.adm-empty-icon { font-size: 40px; color: rgba(160,120,58,0.18); margin-bottom: 16px; }
.adm-empty-title {
  font-family: var(--font-serif); font-size: 28px; color: var(--fg-low);
  margin-bottom: 8px;
}
.adm-empty-sub { font-size: 13px; color: var(--fg-low); }

/* ── PAGINATION ─────────────────────────────────────────── */
.adm-pagination {
  border: 1px solid var(--border);
  border-top: none;
  background: var(--bg-card);
  padding: 20px 28px;
  display: flex; align-items: center; justify-content: space-between; gap: 16px;
  flex-wrap: wrap;
}
.adm-page-info {
  font-size: 11px; color: var(--fg-low); font-weight: 600; letter-spacing: 0.04em;
}
.adm-page-info strong { color: var(--fg); }
.adm-page-nav { display: flex; gap: 4px; }
.adm-page-btn {
  display: inline-flex; align-items: center; justify-content: center;
  min-width: 36px; height: 36px; padding: 0 10px;
  border-radius: 5px; border: 1px solid var(--border);
  font-size: 11.5px; font-weight: 700; text-decoration: none;
  color: var(--fg-mid); background: transparent;
  transition: all 0.18s;
}
.adm-page-btn:hover {
  color: var(--accent); border-color: var(--border-acc);
  background: var(--accent-dim); text-decoration: none;
}
.adm-page-btn.active {
  color: var(--accent); background: var(--accent-dim); border-color: var(--border-acc);
}
.adm-page-btn.disabled { opacity: 0.35; pointer-events: none; }
.adm-page-btn i { font-size: 10px; }

/* ── RESPONSIVE ─────────────────────────────────────────── */
@media (max-width: 1100px) {
  .adm-grid { grid-template-columns: repeat(2, 1fr); }
  .adm-stats { grid-template-columns: repeat(2, 1fr); }
  .adm-stats .adm-stat:nth-child(2) { border-right: none; }
  .adm-stats .adm-stat:nth-child(3),
  .adm-stats .adm-stat:nth-child(4) { border-top: 1px solid var(--border); }
}
@media (max-width: 900px) {
  .adm-nav { display: none; }
}
@media (max-width: 768px) {
  .adm-wrap { padding: calc(var(--nav-h) + 28px) 20px 60px; }
  .adm-grid { grid-template-columns: 1fr; }
  .adm-stats { grid-template-columns: repeat(2, 1fr); }
  .adm-topbar-inner { padding: 0 20px; }
  .adm-page-title { font-size: 36px; }
}
@media (max-width: 480px) {
  .adm-stats { grid-template-columns: 1fr 1fr; }
  .adm-filter-tabs { flex-wrap: wrap; }
}

@media (prefers-reduced-motion: reduce) {
  .adm-topbar, .adm-nav-link, .adm-logout,
  .adm-vcard, .adm-vcard-foto img { transition: none !important; }
}
</style>
</head>
<body>

<div class="adm-glow" aria-hidden="true"></div>

<!-- ── TOPBAR ─────────────────────────────────────────────── -->
<header class="adm-topbar" id="admTopbar">
  <div class="adm-topbar-inner">

    <!-- BRAND — struktur identik .nav-brand di navbar.php -->
    <a class="adm-brand" href="../index.php" aria-label="Stemba Parking — Halaman Utama">
      <div class="adm-brand-logo">
        <img src="../assets/img/3.png" alt="Logo SMKN 7">
      </div>
      <div>
        <span class="adm-brand-name">SMK Negeri 7</span>
        <span class="adm-brand-sub">Stemba Parking</span>
      </div>
    </a>

    <span class="adm-tag">Admin Panel</span>

    <!-- NAV MENU -->
    <nav class="adm-nav" aria-label="Navigasi admin">
      <a href="index.php" class="adm-nav-link active">
        <i class="fas fa-car" aria-hidden="true"></i> Kendaraan
      </a>
      <a href="Kelola-user.php" class="adm-nav-link">
        <i class="fas fa-users" aria-hidden="true"></i> Kelola User
      </a>
    </nav>

    <!-- RIGHT: info + logout -->
    <div class="adm-topbar-right">
      <div class="adm-admin-info">
        <span class="adm-admin-name"><?= htmlspecialchars($_SESSION['username']) ?></span>
        <span class="adm-admin-role">Administrator</span>
      </div>
      <a href="../logout.php" class="adm-logout" aria-label="Logout">
        <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
      </a>
    </div>

  </div>
</header>

<!-- ── MAIN ───────────────────────────────────────────────── -->
<main class="adm-wrap">

  <!-- PAGE HEADER -->
  <div class="adm-page-header" data-aos="fade-up" data-aos-duration="600">
    <span class="adm-overline">Panel Administrator · Edisi 2026</span>
    <h1 class="adm-page-title">
      Data<br>Kendaraan <em>Siswa</em>
    </h1>
    <p class="adm-page-desc">
      Kelola dan pantau semua pendaftaran kendaraan siswa SMKN 7 Semarang.
      Verifikasi dokumen, ubah status, dan monitor aktivitas parkir secara real-time.
    </p>
  </div>

  <!-- STAT STRIP -->
  <div class="adm-stats" data-aos="fade-up" data-aos-delay="80" data-aos-duration="600">
    <div class="adm-stat">
      <span class="adm-stat-num acc"><?= $stats['total'] ?></span>
      <span class="adm-stat-lbl">Total Kendaraan</span>
    </div>
    <div class="adm-stat">
      <span class="adm-stat-num green"><?= $stats['disetujui'] ?></span>
      <span class="adm-stat-lbl">Disetujui</span>
    </div>
    <div class="adm-stat">
      <span class="adm-stat-num yellow"><?= $stats['menunggu'] ?></span>
      <span class="adm-stat-lbl">Menunggu</span>
    </div>
    <div class="adm-stat">
      <span class="adm-stat-num red"><?= $stats['ditolak'] ?></span>
      <span class="adm-stat-lbl">Ditolak</span>
    </div>
  </div>

  <!-- FILTER BAR -->
  <div class="adm-filter-bar" data-aos="fade-up" data-aos-delay="120" data-aos-duration="500">
    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
      <span class="adm-filter-label">Filter Status</span>
      <div class="adm-filter-tabs">
        <?php foreach (['semua'=>'Semua','menunggu'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak'] as $val=>$lbl): ?>
          <a href="?status=<?= $val ?>&page=1"
             class="adm-filter-tab <?= $filter===$val ? 'active' : '' ?>">
            <?= $lbl ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <span class="adm-results-info">
      <?= $total ?> kendaraan ditemukan &nbsp;·&nbsp; Hal. <?= $page ?> / <?= $total_pages ?>
    </span>
  </div>

  <!-- CARD GRID -->
  <div class="adm-grid" data-aos="fade-up" data-aos-delay="160" data-aos-duration="600">

    <?php if (empty($kendaraan)): ?>
      <div class="adm-empty">
        <div class="adm-empty-icon"><i class="fas fa-car-side"></i></div>
        <div class="adm-empty-title">Tidak ada kendaraan</div>
        <p class="adm-empty-sub">Belum ada data kendaraan untuk filter ini.</p>
      </div>
    <?php else: ?>
      <?php foreach ($kendaraan as $k): ?>
      <?php
        $status_class = match($k['status']) {
          'disetujui' => 'vs-disetujui',
          'ditolak'   => 'vs-ditolak',
          default     => 'vs-menunggu',
        };
        $status_label = match($k['status']) {
          'disetujui' => 'Disetujui',
          'ditolak'   => 'Ditolak',
          default     => 'Menunggu',
        };
        $jenis_icon = match($k['jenis'] ?? '') {
          'mobil'   => 'fas fa-car',
          'sepeda'  => 'fas fa-bicycle',
          default   => 'fas fa-motorcycle',
        };
        $foto_path = !empty($k['foto_kendaraan'])
          ? '../' . htmlspecialchars($k['foto_kendaraan'])
          : null;
      ?>
      <div class="adm-vcard">
        <div class="adm-vcard-accent"></div>

        <!-- FOTO -->
        <div class="adm-vcard-foto">
          <?php if ($foto_path): ?>
            <img src="<?= $foto_path ?>" alt="Foto <?= htmlspecialchars($k['nama_kendaraan']) ?>">
            <div class="adm-vcard-foto-overlay"></div>
          <?php else: ?>
            <div class="adm-vcard-foto-placeholder">
              <i class="<?= $jenis_icon ?>"></i>
            </div>
          <?php endif; ?>
          <span class="adm-vcard-status <?= $status_class ?>"><?= $status_label ?></span>
        </div>

        <!-- BODY -->
        <div class="adm-vcard-body">
          <div class="adm-vcard-merek"><?= htmlspecialchars($k['nama_kendaraan']) ?></div>
          <div class="adm-vcard-tipe"><?= htmlspecialchars($k['jenis']) ?></div>

          <div class="adm-vcard-row">
            <i class="fas fa-id-card-clip"></i>
            <span class="adm-vcard-row-lbl">No. TNKB</span>
            <span class="adm-vcard-row-val adm-vcard-plat"><?= htmlspecialchars($k['nomor_tnkb']) ?></span>
          </div>
          <div class="adm-vcard-row">
            <i class="fas fa-user"></i>
            <span class="adm-vcard-row-lbl">Nama</span>
            <span class="adm-vcard-row-val"><?= htmlspecialchars($k['nama_lengkap']) ?></span>
          </div>
          <div class="adm-vcard-row">
            <i class="fas fa-school"></i>
            <span class="adm-vcard-row-lbl">Kelas</span>
            <span class="adm-vcard-row-val"><?= htmlspecialchars($k['kelas'] ?? '—') ?></span>
          </div>
          <div class="adm-vcard-row">
            <i class="fas fa-id-badge"></i>
            <span class="adm-vcard-row-lbl">NIS</span>
            <span class="adm-vcard-row-val"><?= htmlspecialchars($k['nis']) ?></span>
          </div>
          <div class="adm-vcard-row">
            <i class="fas fa-phone"></i>
            <span class="adm-vcard-row-lbl">Telp</span>
            <span class="adm-vcard-row-val"><?= htmlspecialchars($k['no_telepon']) ?></span>
          </div>
          <?php if (!empty($k['catatan_admin'])): ?>
          <div class="adm-vcard-row">
            <i class="fas fa-note-sticky"></i>
            <span class="adm-vcard-row-lbl">Catatan</span>
            <span class="adm-vcard-row-val" style="color:var(--fg-mid)"><?= htmlspecialchars($k['catatan_admin']) ?></span>
          </div>
          <?php endif; ?>
        </div>

        <!-- FOOTER -->
        <div class="adm-vcard-foot">
          <span class="adm-vcard-date">
            <i class="fas fa-clock" style="font-size:9px;margin-right:4px;opacity:0.5"></i>
            <?= date('d M Y', strtotime($k['created_at'])) ?>
          </span>
          <a href="detail-pendaftaran.php?id=<?= $k['id'] ?>" class="adm-vcard-action">
            Detail <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </div><!-- /adm-grid -->

  <!-- PAGINATION -->
  <?php if ($total_pages > 1): ?>
  <div class="adm-pagination">
    <span class="adm-page-info">
      Menampilkan <strong><?= count($kendaraan) ?></strong> dari <strong><?= $total ?></strong> kendaraan
    </span>
    <nav class="adm-page-nav" aria-label="Pagination">
      <a href="?status=<?= $filter ?>&page=<?= $page-1 ?>"
         class="adm-page-btn <?= $page<=1 ? 'disabled' : '' ?>">
        <i class="fas fa-chevron-left"></i>
      </a>

      <?php
        $range = 2;
        $start = max(1, $page - $range);
        $end   = min($total_pages, $page + $range);
        if ($start > 1): ?>
          <a href="?status=<?= $filter ?>&page=1" class="adm-page-btn">1</a>
          <?php if ($start > 2): ?><span class="adm-page-btn disabled">…</span><?php endif; ?>
      <?php endif; ?>

      <?php for ($i = $start; $i <= $end; $i++): ?>
        <a href="?status=<?= $filter ?>&page=<?= $i ?>"
           class="adm-page-btn <?= $i===$page ? 'active' : '' ?>"><?= $i ?></a>
      <?php endfor; ?>

      <?php if ($end < $total_pages): ?>
          <?php if ($end < $total_pages-1): ?><span class="adm-page-btn disabled">…</span><?php endif; ?>
          <a href="?status=<?= $filter ?>&page=<?= $total_pages ?>" class="adm-page-btn"><?= $total_pages ?></a>
      <?php endif; ?>

      <a href="?status=<?= $filter ?>&page=<?= $page+1 ?>"
         class="adm-page-btn <?= $page>=$total_pages ? 'disabled' : '' ?>">
        <i class="fas fa-chevron-right"></i>
      </a>
    </nav>
  </div>
  <?php endif; ?>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  if (typeof AOS !== 'undefined') {
    AOS.init({ once: true, offset: 40, easing: 'ease-out-quart', duration: 600 });
  }

  // scroll: deeper glass — identik dengan navbar.php
  var topbar  = document.getElementById('admTopbar');
  var ticking = false;
  window.addEventListener('scroll', function () {
    if (!ticking) {
      requestAnimationFrame(function () {
        topbar.classList.toggle('nav-scrolled', window.scrollY > 30);
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });
});
</script>
</body>
</html>