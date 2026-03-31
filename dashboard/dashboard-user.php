<?php
// dashboard/dashboard-user.php — v2 CREAM EDITION
// Dashboard Siswa — Stemba Parking · SMKN 7 Semarang

session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ../login.php');
  exit;
}
if ($_SESSION['role'] === 'admin') {
  header('Location: ../admin/index.php');
  exit;
}

$uid = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT id, username, kelas, jurusan, foto FROM users WHERE id = ?");
$stmt->execute([$uid]);
$user = $stmt->fetch();

$user['kelas']   = $_SESSION['kelas']   ?: ($user['kelas']   ?: '-');
$user['jurusan'] = $_SESSION['jurusan'] ?: ($user['jurusan'] ?: '-');
$user['foto']    = $_SESSION['foto']    ?: $user['foto'];

$inisial = strtoupper(substr($user['username'], 0, 1));

$stmt = $pdo->prepare("
  SELECT
    COUNT(*)                          AS total,
    SUM(status = 'menunggu')          AS menunggu,
    SUM(status = 'disetujui')         AS disetujui,
    SUM(status = 'ditolak')           AS ditolak
  FROM kendaraan WHERE user_id = ?
");
$stmt->execute([$uid]);
$stats = $stmt->fetch();
$stats['total']     = (int)$stats['total'];
$stats['menunggu']  = (int)$stats['menunggu'];
$stats['disetujui'] = (int)$stats['disetujui'];
$stats['ditolak']   = (int)$stats['ditolak'];

$stmt = $pdo->prepare("
  SELECT k.*, d.foto_kendaraan
  FROM kendaraan k
  LEFT JOIN dokumen d ON d.kendaraan_id = k.id
  WHERE k.user_id = ?
  ORDER BY k.created_at DESC
  LIMIT 5
");
$stmt->execute([$uid]);
$kendaraan_list = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM kendaraan WHERE user_id = ? AND status = 'ditolak'");
$stmt->execute([$uid]);
$ada_tolak = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — Stemba Parking</title>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --font-serif:        'Instrument Serif', Georgia, serif;
    --font-sans:         'Outfit', sans-serif;

    /* Palet utama */
    --db-bg:             #fffdf7;              /* Cream / Ivory */
    --db-surface:        #f5f2ea;              /* Warm Cream — card/panel */
    --db-accent:         #536878;              /* Blue Slate */
    --db-accent-dim:     rgba(83,104,120,0.18);
    --db-accent-glow:    rgba(83,104,120,0.07);
    --db-fg:             #0a0a0a;              /* Onyx */
    --db-fg-mid:         rgba(10,10,10,0.55);
    --db-fg-low:         rgba(10,10,10,0.32);
    --db-border:         rgba(10,10,10,0.08);
    --db-border-acc:     rgba(83,104,120,0.25);

    /* Status */
    --db-green:          #488c60;              /* Sage Green — disetujui */
    --db-green-bg:       rgba(72,140,96,0.08);
    --db-green-bdr:      rgba(72,140,96,0.22);
    --db-yellow:         #8a6e20;              /* Amber Onyx — menunggu */
    --db-yellow-bg:      rgba(138,110,32,0.07);
    --db-yellow-bdr:     rgba(138,110,32,0.20);
    --db-red:            #b03a2e;              /* Red Onyx — ditolak */
    --db-red-bg:         rgba(176,58,46,0.07);
    --db-red-bdr:        rgba(176,58,46,0.20);
  }

  html, body {
    min-height: 100vh;
    background: var(--db-bg);
    font-family: var(--font-sans);
    color: var(--db-fg);
  }

  /* ── TOPNAV ──────────────────────────────────────────────── */
  .db-nav {
    position: sticky; top: 0; z-index: 100;
    background: rgba(255,253,247,0.92);
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--db-border);
    box-shadow: 0 1px 0 rgba(255,255,255,0.8), 0 4px 20px rgba(83,104,120,0.06);
  }
  .db-nav::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1.5px;
    background: linear-gradient(90deg, transparent, rgba(83,104,120,0.35), transparent);
  }
  .db-nav-inner {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 40px; height: 62px; gap: 20px; max-width: 1280px; margin: 0 auto;
  }

  /* brand */
  .db-nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
  .db-nav-brand-icon {
    width: 32px; height: 32px;
    border: 1px solid var(--db-border-acc); background: var(--db-accent-glow);
    display: flex; align-items: center; justify-content: center;
  }
  .db-nav-brand-icon i { font-size: 14px; color: var(--db-accent); }
  .db-nav-brand-name {
    font-family: var(--font-serif); font-size: 17px;
    color: var(--db-fg); letter-spacing: -0.02em; line-height: 1;
  }
  .db-nav-brand-sub {
    font-size: 9px; font-weight: 800; letter-spacing: 0.16em; text-transform: uppercase;
    color: var(--db-fg-low); display: block; margin-top: 1px;
  }

  /* nav links */
  .db-nav-center { display: flex; align-items: center; gap: 2px; }
  .db-nav-link {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 7px 14px; font-size: 12px; font-weight: 600; letter-spacing: 0.04em;
    color: var(--db-fg-mid); text-decoration: none;
    border-bottom: 2px solid transparent;
    transition: color 0.2s, border-color 0.2s;
  }
  .db-nav-link:hover { color: var(--db-fg); }
  .db-nav-link.active { color: var(--db-accent); border-bottom-color: var(--db-accent); }
  .db-nav-link i { font-size: 11px; }

  /* right area */
  .db-nav-right { display: flex; align-items: center; gap: 10px; }

  .db-notif-btn {
    width: 34px; height: 34px; border: 1px solid var(--db-border);
    background: transparent; display: flex; align-items: center; justify-content: center;
    cursor: pointer; position: relative; transition: border-color 0.2s, background 0.2s;
  }
  .db-notif-btn:hover { border-color: var(--db-border-acc); background: var(--db-accent-glow); }
  .db-notif-btn i { font-size: 13px; color: var(--db-fg-low); }
  .db-notif-dot {
    position: absolute; top: 6px; right: 6px;
    width: 6px; height: 6px; border-radius: 50%; background: var(--db-yellow);
    animation: db-pulse 2s ease-in-out infinite;
  }
  @keyframes db-pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(0.6)} }

  .db-avatar {
    width: 34px; height: 34px; border: 1px solid var(--db-border-acc);
    background: var(--db-accent-glow); overflow: hidden;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800; color: var(--db-accent);
    text-transform: uppercase; flex-shrink: 0;
  }
  .db-avatar img { width: 100%; height: 100%; object-fit: cover; }

  .btn-db-logout {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 7px 14px; font-size: 11.5px; font-weight: 700;
    letter-spacing: 0.05em; text-transform: uppercase;
    color: var(--db-fg-low); background: transparent;
    border: 1px solid var(--db-border); cursor: pointer; text-decoration: none;
    transition: border-color 0.2s, color 0.2s, background 0.2s;
  }
  .btn-db-logout:hover {
    border-color: var(--db-red-bdr); color: var(--db-red); background: var(--db-red-bg);
    text-decoration: none;
  }
  .btn-db-logout i { font-size: 10px; }

  /* ── PAGE ────────────────────────────────────────────────── */
  .db-page { padding: 48px 40px 80px; max-width: 1280px; margin: 0 auto; }

  /* ── WELCOME ─────────────────────────────────────────────── */
  .db-welcome {
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 24px; margin-bottom: 48px; flex-wrap: wrap;
    padding-bottom: 36px; border-bottom: 1px solid var(--db-border);
  }
  .db-welcome-eyebrow {
    font-size: 9.5px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
    color: var(--db-accent); margin-bottom: 10px; display: block;
  }
  .db-welcome-title {
    font-family: var(--font-serif); font-size: clamp(28px, 3.5vw, 46px);
    font-weight: 400; color: var(--db-fg); line-height: 1; letter-spacing: -0.02em; margin: 0;
  }
  .db-welcome-title em { font-style: italic; color: var(--db-accent); }
  .db-welcome-sub {
    font-size: 13px; color: var(--db-fg-low); margin-top: 10px;
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
  }
  .db-welcome-sub span { display: flex; align-items: center; gap: 6px; }
  .db-welcome-sub i { font-size: 10px; color: var(--db-accent); opacity: 0.6; }

  /* primary button — Onyx → hover Blue Slate */
  .btn-db-primary {
    display: inline-flex; align-items: center; justify-content: center;
    background: var(--db-fg); color: var(--db-bg);
    padding: 12px 28px; font-family: var(--font-sans);
    font-weight: 800; font-size: 12px; letter-spacing: 0.06em; text-transform: uppercase;
    text-decoration: none; border: none; cursor: pointer;
    transition: background 0.25s ease, color 0.25s ease, transform 0.22s;
  }
  .btn-db-primary:hover {
    background: var(--db-accent); color: #fff; text-decoration: none;
    transform: translateY(-2px);
  }

  /* ── ALERT DITOLAK ───────────────────────────────────────── */
  .db-alert-reject {
    display: flex; align-items: flex-start; gap: 12px;
    background: var(--db-red-bg); border: 1px solid var(--db-red-bdr);
    border-left: 3px solid var(--db-red);
    padding: 16px 20px; margin-bottom: 32px;
    font-size: 13px; color: var(--db-red); line-height: 1.6;
  }
  .db-alert-reject i { font-size: 14px; flex-shrink: 0; margin-top: 1px; }
  .db-alert-reject strong { font-weight: 700; }

  /* ── STAT CARDS ──────────────────────────────────────────── */
  .db-cards {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 16px; margin-bottom: 48px;
  }

  .db-card {
    background: var(--db-surface); border: 1px solid var(--db-border);
    padding: 32px 28px; position: relative; overflow: hidden; cursor: default;
    transition: border-color 0.25s, background 0.25s, transform 0.25s;
  }
  .db-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
  }
  .db-card:hover { transform: translateY(-4px); }

  /* total */
  .db-card-total::before                { background: var(--db-accent); }
  .db-card-total:hover                  { border-color: var(--db-border-acc); background: rgba(83,104,120,0.05); }
  .db-card-total .db-card-bgnum         { -webkit-text-stroke: 1px rgba(83,104,120,0.08); }
  .db-card-total .db-card-icon          { border-color: var(--db-border-acc); background: var(--db-accent-glow); }
  .db-card-total .db-card-icon i        { color: var(--db-accent); }
  .db-card-total .db-card-num           { color: var(--db-fg); }
  .db-card-total .db-card-desc i        { color: var(--db-accent); }

  /* menunggu */
  .db-card-wait::before                 { background: var(--db-yellow); }
  .db-card-wait:hover                   { border-color: var(--db-yellow-bdr); background: var(--db-yellow-bg); }
  .db-card-wait .db-card-bgnum          { -webkit-text-stroke: 1px rgba(138,110,32,0.10); }
  .db-card-wait .db-card-icon           { border-color: var(--db-yellow-bdr); background: var(--db-yellow-bg); }
  .db-card-wait .db-card-icon i         { color: var(--db-yellow); }
  .db-card-wait .db-card-num            { color: var(--db-yellow); }
  .db-card-wait .db-card-desc i         { color: var(--db-yellow); }

  /* disetujui */
  .db-card-ok::before                   { background: var(--db-green); }
  .db-card-ok:hover                     { border-color: var(--db-green-bdr); background: var(--db-green-bg); }
  .db-card-ok .db-card-bgnum            { -webkit-text-stroke: 1px rgba(72,140,96,0.10); }
  .db-card-ok .db-card-icon             { border-color: var(--db-green-bdr); background: var(--db-green-bg); }
  .db-card-ok .db-card-icon i           { color: var(--db-green); }
  .db-card-ok .db-card-num              { color: var(--db-green); }
  .db-card-ok .db-card-desc i           { color: var(--db-green); }

  .db-card-bgnum {
    position: absolute; bottom: -16px; right: 16px;
    font-family: var(--font-serif); font-size: 90px; font-weight: 400;
    color: transparent; line-height: 1; pointer-events: none; user-select: none;
  }
  .db-card-top {
    display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px;
  }
  .db-card-label {
    font-size: 9.5px; font-weight: 800; letter-spacing: 0.2em;
    text-transform: uppercase; color: var(--db-fg-low); display: block;
  }
  .db-card-icon {
    width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
    border: 1px solid; transition: transform 0.3s;
  }
  .db-card:hover .db-card-icon { transform: rotate(-8deg) scale(1.1); }
  .db-card-icon i { font-size: 14px; }
  .db-card-num {
    font-family: var(--font-serif); font-size: 64px; font-weight: 400;
    line-height: 1; letter-spacing: -0.03em; display: block;
    margin-bottom: 8px; position: relative; z-index: 1;
  }
  .db-card-desc {
    font-size: 12px; color: var(--db-fg-low);
    position: relative; z-index: 1;
    display: flex; align-items: center; gap: 6px;
  }
  .db-card-desc i { font-size: 10px; opacity: 0.6; }

  /* ── SECTION LABEL ───────────────────────────────────────── */
  .db-section-label {
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px; margin-bottom: 16px;
  }
  .db-section-label-left { display: flex; align-items: center; gap: 12px; }
  .db-section-tag {
    font-size: 9px; font-weight: 800; letter-spacing: 0.2em; text-transform: uppercase;
    color: var(--db-accent); padding: 4px 10px;
    border: 1px solid var(--db-border-acc); background: var(--db-accent-glow);
  }
  .db-section-title-sm {
    font-size: 11px; font-weight: 700; letter-spacing: 0.1em;
    text-transform: uppercase; color: var(--db-fg-low);
  }
  .db-section-action {
    font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
    color: var(--db-fg-low); text-decoration: none;
    display: flex; align-items: center; gap: 6px; transition: color 0.2s;
  }
  .db-section-action:hover { color: var(--db-accent); }
  .db-section-action i { font-size: 9px; }

  /* ── TABLE ───────────────────────────────────────────────── */
  .db-table-wrap {
    border: 1px solid var(--db-border); margin-bottom: 40px; overflow-x: auto;
  }
  .db-table-head {
    display: grid; grid-template-columns: 2fr 1.2fr 1fr 1.2fr 100px;
    background: var(--db-surface); border-bottom: 1px solid var(--db-border);
    min-width: 600px;
  }
  .db-th {
    padding: 12px 18px; font-size: 8.5px; font-weight: 800;
    letter-spacing: 0.2em; text-transform: uppercase; color: var(--db-fg-low);
    border-right: 1px solid var(--db-border);
  }
  .db-th:last-child { border-right: none; }

  .db-table-row {
    display: grid; grid-template-columns: 2fr 1.2fr 1fr 1.2fr 100px;
    border-bottom: 1px solid var(--db-border);
    transition: background 0.18s; cursor: default;
    position: relative; min-width: 600px; background: var(--db-bg);
  }
  .db-table-row:last-child { border-bottom: none; }
  .db-table-row::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 0;
    background: var(--db-accent); transition: width 0.2s;
  }
  .db-table-row:hover { background: rgba(83,104,120,0.04); }
  .db-table-row:hover::before { width: 3px; }

  .db-td {
    padding: 16px 18px; font-size: 13px; color: var(--db-fg-mid);
    border-right: 1px solid var(--db-border);
    display: flex; align-items: center; gap: 10px;
  }
  .db-td:last-child { border-right: none; }

  .db-vehicle-icon {
    width: 32px; height: 32px; flex-shrink: 0;
    border: 1px solid var(--db-border); background: var(--db-surface);
    display: flex; align-items: center; justify-content: center; font-size: 14px;
  }
  .db-vehicle-name { font-weight: 700; color: var(--db-fg); font-size: 13px; line-height: 1.2; }
  .db-vehicle-plat {
    font-family: ui-monospace, 'Courier New', monospace;
    font-size: 10px; color: var(--db-fg-low); letter-spacing: 0.08em; margin-top: 2px;
  }

  /* badges */
  .db-badge {
    font-size: 8.5px; font-weight: 800; letter-spacing: 0.1em;
    text-transform: uppercase; padding: 4px 10px; border: 1px solid; white-space: nowrap;
  }
  .db-badge-ok     { color: var(--db-green);  background: var(--db-green-bg);  border-color: var(--db-green-bdr); }
  .db-badge-wait   { color: var(--db-yellow); background: var(--db-yellow-bg); border-color: var(--db-yellow-bdr); }
  .db-badge-reject { color: var(--db-red);    background: var(--db-red-bg);    border-color: var(--db-red-bdr); }

  .db-row-action {
    font-size: 11px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase;
    color: var(--db-fg-low); text-decoration: none;
    display: flex; align-items: center; gap: 5px; transition: color 0.2s;
  }
  .db-row-action:hover { color: var(--db-accent); }

  /* empty state */
  .db-empty {
    padding: 56px 24px; text-align: center;
    border: 1px dashed var(--db-border); margin-bottom: 40px;
    background: var(--db-surface);
  }
  .db-empty i { font-size: 32px; color: var(--db-fg-low); margin-bottom: 14px; display: block; }
  .db-empty p { font-size: 13px; color: var(--db-fg-low); margin-bottom: 20px; }

  /* ── BOTTOM GRID ─────────────────────────────────────────── */
  .db-bottom-grid { display: grid; grid-template-columns: 1fr 320px; gap: 16px; }

  .db-info-panel {
    border: 1px solid var(--db-border-acc);
    background: var(--db-surface);
  }
  .db-info-panel-head {
    padding: 14px 20px; border-bottom: 1px solid var(--db-border-acc);
    background: var(--db-accent-glow);
    font-size: 9px; font-weight: 800; letter-spacing: 0.2em;
    text-transform: uppercase; color: var(--db-accent);
  }
  .db-info-panel-body { padding: 4px 20px 8px; }
  .db-info-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 12px 0; border-bottom: 1px solid var(--db-border); font-size: 12.5px;
  }
  .db-info-row:last-child { border-bottom: none; }
  .db-info-row-key { color: var(--db-fg-low); font-weight: 600; }
  .db-info-row-val {
    color: var(--db-fg); font-weight: 700;
    text-align: right; max-width: 60%; word-break: break-word;
  }

  /* ── RESPONSIVE ──────────────────────────────────────────── */
  @media (max-width: 1100px) { .db-bottom-grid { grid-template-columns: 1fr; } }
  @media (max-width: 992px) {
    .db-nav-inner { padding: 0 20px; }
    .db-nav-center { display: none; }
    .db-page { padding: 32px 20px 60px; }
    .db-cards { grid-template-columns: 1fr; gap: 12px; }
  }
  @media (max-width: 576px) {
    .db-welcome { flex-direction: column; align-items: flex-start; }
  }
  @media (prefers-reduced-motion: reduce) {
    .db-card:hover, .btn-db-primary:hover { transform: none !important; }
    .db-notif-dot { animation: none !important; }
  }
  </style>
</head>
<body>

<!-- ── TOPNAV ──────────────────────────────────────────────── -->
<nav class="db-nav">
  <div class="db-nav-inner">

    <a href="../index.php" class="db-nav-brand">
      <div class="db-nav-brand-icon">
        <i class="fa-solid fa-square-parking"></i>
      </div>
      <div>
        <span class="db-nav-brand-name">Stemba Parking</span>
        <span class="db-nav-brand-sub">SMKN 7 Semarang</span>
      </div>
    </a>

    <div class="db-nav-center">
      <a class="db-nav-link active" href="dashboard-user.php">
        <i class="fa-solid fa-gauge-high"></i> Dashboard
      </a>
      <a class="db-nav-link" href="kendaraan.php">
        <i class="fa-solid fa-motorcycle"></i> Kendaraan
      </a>
      <a class="db-nav-link" href="daftar-kendaraan.php">
        <i class="fa-solid fa-plus"></i> Daftarkan
      </a>
    </div>

    <div class="db-nav-right">
      <?php if ($stats['menunggu'] > 0): ?>
      <button class="db-notif-btn" title="Ada kendaraan menunggu persetujuan" aria-label="Notifikasi">
        <i class="fa-solid fa-bell"></i>
        <span class="db-notif-dot"></span>
      </button>
      <?php endif; ?>

      <div class="db-avatar" title="<?= htmlspecialchars($user['username']) ?>">
        <?php if ($user['foto']): ?>
          <img src="../<?= htmlspecialchars($user['foto']) ?>" alt="Foto profil">
        <?php else: ?>
          <?= $inisial ?>
        <?php endif; ?>
      </div>

      <a class="btn-db-logout" href="../logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Keluar
      </a>
    </div>

  </div>
</nav>

<!-- ── PAGE ────────────────────────────────────────────────── -->
<main class="db-page">

  <!-- Alert ditolak -->
  <?php if ($ada_tolak > 0): ?>
  <div class="db-alert-reject" data-aos="fade-down" data-aos-duration="500">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <span>
      Kamu memiliki <strong><?= $ada_tolak ?> kendaraan yang ditolak</strong>.
      Periksa kembali dokumen dan daftarkan ulang.
    </span>
  </div>
  <?php endif; ?>

  <!-- Welcome -->
  <div class="db-welcome" data-aos="fade-up" data-aos-duration="600">
    <div>
      <span class="db-welcome-eyebrow">Dashboard Siswa</span>
      <h1 class="db-welcome-title">
        Halo, <em><?= htmlspecialchars($user['username']) ?></em>
      </h1>
      <div class="db-welcome-sub">
        <?php if ($user['kelas'] !== '-'): ?>
        <span><i class="fa-solid fa-user-graduate"></i> <?= htmlspecialchars($user['kelas']) ?></span>
        <?php endif; ?>
        <?php if ($user['jurusan'] !== '-'): ?>
        <span><i class="fa-solid fa-school"></i> <?= htmlspecialchars($user['jurusan']) ?></span>
        <?php endif; ?>
      </div>
    </div>
    <a class="btn-db-primary" href="daftar-kendaraan.php">
      Daftarkan Kendaraan
    </a>
  </div>

  <!-- Stat Cards -->
  <div class="db-cards" data-aos="fade-up" data-aos-delay="60" data-aos-duration="700">

    <div class="db-card db-card-total">
      <span class="db-card-bgnum" aria-hidden="true"><?= $stats['total'] ?></span>
      <div class="db-card-top">
        <span class="db-card-label">Total Kendaraan</span>
        <div class="db-card-icon"><i class="fa-solid fa-car-side"></i></div>
      </div>
      <span class="db-card-num count" data-target="<?= $stats['total'] ?>">0</span>
      <p class="db-card-desc"><i class="fa-solid fa-circle-dot"></i> Semua kendaraan atas namamu</p>
    </div>

    <div class="db-card db-card-wait">
      <span class="db-card-bgnum" aria-hidden="true"><?= $stats['menunggu'] ?></span>
      <div class="db-card-top">
        <span class="db-card-label">Menunggu Persetujuan</span>
        <div class="db-card-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
      </div>
      <span class="db-card-num count" data-target="<?= $stats['menunggu'] ?>">0</span>
      <p class="db-card-desc"><i class="fa-solid fa-triangle-exclamation"></i> Sedang ditinjau admin</p>
    </div>

    <div class="db-card db-card-ok">
      <span class="db-card-bgnum" aria-hidden="true"><?= $stats['disetujui'] ?></span>
      <div class="db-card-top">
        <span class="db-card-label">Disetujui</span>
        <div class="db-card-icon"><i class="fa-solid fa-circle-check"></i></div>
      </div>
      <span class="db-card-num count" data-target="<?= $stats['disetujui'] ?>">0</span>
      <p class="db-card-desc"><i class="fa-solid fa-shield-check"></i> Aktif dengan izin parkir</p>
    </div>

  </div>

  <!-- Vehicle Table -->
  <div class="db-section-label" data-aos="fade-up" data-aos-duration="500">
    <div class="db-section-label-left">
      <span class="db-section-tag">Kendaraan</span>
      <span class="db-section-title-sm">Daftar Kendaraan Terdaftar</span>
    </div>
    <a href="kendaraan.php" class="db-section-action">
      Lihat Semua <i class="fa-solid fa-arrow-right"></i>
    </a>
  </div>

  <?php if (empty($kendaraan_list)): ?>
  <div class="db-empty" data-aos="fade-up">
    <i class="fa-solid fa-motorcycle"></i>
    <p>Belum ada kendaraan terdaftar. Daftarkan kendaraanmu sekarang!</p>
    <a class="btn-db-primary" href="daftar-kendaraan.php">
      Daftarkan Sekarang
    </a>
  </div>

  <?php else: ?>
  <div class="db-table-wrap" data-aos="fade-up" data-aos-delay="60" data-aos-duration="700">
    <div class="db-table-head">
      <div class="db-th">Kendaraan</div>
      <div class="db-th">Nomor Plat</div>
      <div class="db-th">Jenis</div>
      <div class="db-th">Status</div>
      <div class="db-th">Aksi</div>
    </div>

    <?php foreach ($kendaraan_list as $k):
      $icon = match($k['jenis']) {
        'motor'  => '🏍️',
        'mobil'  => '🚗',
        'sepeda' => '🚲',
        default  => '🚗'
      };
      $badge_class = match($k['status']) {
        'disetujui' => 'db-badge-ok',
        'menunggu'  => 'db-badge-wait',
        'ditolak'   => 'db-badge-reject',
        default     => 'db-badge-wait'
      };
      $badge_text = match($k['status']) {
        'disetujui' => 'Disetujui',
        'menunggu'  => 'Menunggu',
        'ditolak'   => 'Ditolak',
        default     => 'Menunggu'
      };
    ?>
    <div class="db-table-row">
      <div class="db-td">
        <div class="db-vehicle-icon"><?= $icon ?></div>
        <div>
          <div class="db-vehicle-name"><?= htmlspecialchars($k['merek'] . ' ' . $k['tipe']) ?></div>
          <div class="db-vehicle-plat"><?= htmlspecialchars($k['plat']) ?></div>
        </div>
      </div>
      <div class="db-td">
        <span style="font-family:ui-monospace,monospace;font-size:12px;letter-spacing:0.08em;">
          <?= htmlspecialchars($k['plat']) ?>
        </span>
      </div>
      <div class="db-td"><?= ucfirst($k['jenis']) ?></div>
      <div class="db-td">
        <span class="db-badge <?= $badge_class ?>"><?= $badge_text ?></span>
      </div>
      <div class="db-td">
        <a href="detail-kendaraan.php?id=<?= $k['id'] ?>" class="db-row-action">
          Detail <i class="fa-solid fa-chevron-right"></i>
        </a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Info Akun -->
  <div class="db-bottom-grid" data-aos="fade-up" data-aos-delay="60" data-aos-duration="700">
    <div></div>
    <div class="db-info-panel">
      <div class="db-info-panel-head">Info Akun</div>
      <div class="db-info-panel-body">

        <div class="db-info-row">
          <span class="db-info-row-key">Username</span>
          <span class="db-info-row-val"><?= htmlspecialchars($user['username']) ?></span>
        </div>

        <?php if ($user['kelas'] !== '-'): ?>
        <div class="db-info-row">
          <span class="db-info-row-key">Kelas</span>
          <span class="db-info-row-val"><?= htmlspecialchars($user['kelas']) ?></span>
        </div>
        <?php endif; ?>

        <?php if ($user['jurusan'] !== '-'): ?>
        <div class="db-info-row">
          <span class="db-info-row-key">Jurusan</span>
          <span class="db-info-row-val"><?= htmlspecialchars($user['jurusan']) ?></span>
        </div>
        <?php endif; ?>

        <div class="db-info-row">
          <span class="db-info-row-key">Status</span>
          <span class="db-badge db-badge-ok">Aktif</span>
        </div>

        <div class="db-info-row">
          <span class="db-info-row-key">T.A.</span>
          <span class="db-info-row-val">2025 / 2026</span>
        </div>

      </div>
    </div>
  </div>

</main>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
(function () {
  'use strict';
  var noMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  AOS.init({ once: true, offset: 40, easing: 'ease-out-quart', duration: 650 });

  /* counter animation */
  var counters = document.querySelectorAll('.count');
  function runCount(el) {
    var target = +(el.dataset.target || 0);
    if (target === 0) { el.textContent = '0'; return; }
    var t0 = performance.now();
    (function tick(now) {
      var p = Math.min((now - t0) / 1200, 1), ease = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(target * ease);
      if (p < 1) requestAnimationFrame(tick); else el.textContent = target;
    })(t0);
  }
  if ('IntersectionObserver' in window && !noMotion) {
    var obs = new IntersectionObserver(function (entries, o) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { runCount(en.target); o.unobserve(en.target); }
      });
    }, { threshold: 0.5 });
    counters.forEach(function (el) { obs.observe(el); });
  } else {
    counters.forEach(function (el) { el.textContent = el.dataset.target; });
  }
})();
</script>
</body>
</html>