<?php
// dashboard/kendaraan.php
// Daftar Semua Kendaraan Siswa — Stemba Parking · SMKN 7 Semarang

session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
if ($_SESSION['role'] === 'admin') { header('Location: ../admin/index.php'); exit; }

$uid = $_SESSION['user_id'];

// ambil data user
$stmt = $pdo->prepare("SELECT id, username, kelas, jurusan, foto FROM users WHERE id = ?");
$stmt->execute([$uid]);
$user    = $stmt->fetch();
$inisial = strtoupper(substr($user['username'], 0, 1));

// ── FILTER & PAGINATION ──────────────────────────────────────
$filter   = $_GET['status'] ?? 'semua';
$search   = trim($_GET['q'] ?? '');
$page     = max(1, (int)($_GET['page'] ?? 1));
$per_page = 8;
$offset   = ($page - 1) * $per_page;

$allowed_filters = ['semua', 'menunggu', 'disetujui', 'ditolak'];
if (!in_array($filter, $allowed_filters)) $filter = 'semua';

// build query
$where  = "WHERE k.user_id = ?";
$params = [$uid];

if ($filter !== 'semua') {
  $where   .= " AND k.status = ?";
  $params[] = $filter;
}
if ($search !== '') {
  $where   .= " AND (k.nama_kendaraan LIKE ? OR k.nomor_tnkb LIKE ?)";
  $params[] = "%$search%";
  $params[] = "%$search%";
}

// total count
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM pendaftaran k $where");
$count_stmt->execute($params);
$total      = (int)$count_stmt->fetchColumn();
$total_page = max(1, ceil($total / $per_page));
$page       = min($page, $total_page);

// fetch data — LIMIT & OFFSET langsung di query (sudah di-cast int, aman dari injection)
$per_page_int = (int)$per_page;
$offset_int   = (int)$offset;
$stmt = $pdo->prepare("
  SELECT k.*
  FROM pendaftaran k
  $where
  ORDER BY k.created_at DESC
  LIMIT $per_page_int OFFSET $offset_int
");
$stmt->execute($params);
$kendaraan_list = $stmt->fetchAll();

// ── STAT COUNTS (all, ignore filter/search) ──────────────────
$stat_stmt = $pdo->prepare("
  SELECT
    COUNT(*)                         AS total,
    SUM(status = 'menunggu')         AS menunggu,
    SUM(status = 'disetujui')        AS disetujui,
    SUM(status = 'ditolak')          AS ditolak
  FROM pendaftaran WHERE user_id = ?
");
$stat_stmt->execute([$uid]);
$stats = $stat_stmt->fetch();
foreach ($stats as $k => $v) $stats[$k] = (int)$v;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kendaraanku — Stemba Parking</title>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --font-serif: 'Instrument Serif', Georgia, serif;
    --font-sans:  'Outfit', sans-serif;
    --bg:         #0d0d0d;
    --bg2:        #111111;
    --border:     rgba(255,255,255,0.07);
    --border-acc: rgba(245,158,11,0.28);
    --accent:     #f59e0b;
    --accent-dim: rgba(245,158,11,0.18);
    --accent-glow:rgba(245,158,11,0.07);
    --fg:         #ffffff;
    --fg-mid:     rgba(255,255,255,0.48);
    --fg-low:     rgba(255,255,255,0.22);
    --green:      #86efac;
    --green-bg:   rgba(134,239,172,0.08);
    --green-bdr:  rgba(134,239,172,0.2);
    --yellow:     #fde047;
    --yellow-bg:  rgba(253,224,71,0.08);
    --yellow-bdr: rgba(253,224,71,0.2);
    --red:        #f87171;
    --red-bg:     rgba(248,113,113,0.08);
    --red-bdr:    rgba(248,113,113,0.2);
  }
  html, body { min-height: 100vh; background: var(--bg); font-family: var(--font-sans); color: var(--fg); }

  /* ── TOPNAV ── */
  .nav { position: sticky; top: 0; z-index: 100; background: rgba(13,13,13,0.93); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); }
  .nav-inner { display: flex; align-items: center; justify-content: space-between; padding: 0 40px; height: 60px; gap: 20px; max-width: 1280px; margin: 0 auto; }
  .nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
  .nav-brand-icon { width: 32px; height: 32px; border: 1px solid var(--border-acc); background: var(--accent-glow); display: flex; align-items: center; justify-content: center; }
  .nav-brand-icon i { font-size: 14px; color: var(--accent); }
  .nav-brand-name { font-family: var(--font-serif); font-size: 17px; color: var(--fg); letter-spacing: -0.02em; line-height: 1; }
  .nav-brand-sub  { font-size: 9px; font-weight: 800; letter-spacing: 0.16em; text-transform: uppercase; color: var(--fg-low); display: block; margin-top: 1px; }
  .nav-center { display: flex; align-items: center; gap: 2px; }
  .nav-link { display: inline-flex; align-items: center; gap: 7px; padding: 7px 14px; font-size: 12px; font-weight: 600; letter-spacing: 0.04em; color: var(--fg-low); text-decoration: none; border-bottom: 2px solid transparent; transition: color 0.2s, border-color 0.2s; }
  .nav-link:hover { color: var(--fg); }
  .nav-link.active { color: var(--accent); border-bottom-color: var(--accent); }
  .nav-link i { font-size: 11px; }
  .nav-right { display: flex; align-items: center; gap: 10px; }
  .db-avatar { width: 34px; height: 34px; border: 1px solid var(--border-acc); background: var(--accent-glow); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; color: var(--accent); text-transform: uppercase; overflow: hidden; }
  .db-avatar img { width: 100%; height: 100%; object-fit: cover; }
  .btn-logout { display: inline-flex; align-items: center; gap: 7px; padding: 7px 14px; font-size: 11.5px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: var(--fg-low); background: transparent; border: 1px solid var(--border); cursor: pointer; text-decoration: none; transition: border-color 0.2s, color 0.2s, background 0.2s; }
  .btn-logout:hover { border-color: var(--red-bdr); color: var(--red); background: var(--red-bg); }

  /* ── PAGE ── */
  .page { max-width: 1280px; margin: 0 auto; padding: 48px 40px 80px; }

  /* ── PAGE HEADER ── */
  .page-header {
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 20px; margin-bottom: 40px; padding-bottom: 32px;
    border-bottom: 1px solid var(--border); flex-wrap: wrap;
  }
  .page-eyebrow { font-size: 9.5px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase; color: var(--accent); opacity: 0.7; margin-bottom: 10px; display: block; }
  .page-title { font-family: var(--font-serif); font-size: clamp(26px, 3.5vw, 42px); font-weight: 400; color: var(--fg); letter-spacing: -0.02em; line-height: 1; }
  .page-title em { font-style: italic; color: var(--accent); }
  .btn-primary { display: inline-flex; align-items: center; gap: 9px; background: var(--accent); color: #0d0d0d; padding: 12px 24px; font-family: var(--font-sans); font-weight: 800; font-size: 12px; letter-spacing: 0.06em; text-transform: uppercase; text-decoration: none; transition: background 0.2s, transform 0.2s, box-shadow 0.2s; }
  .btn-primary:hover { background: #fbbf24; color: #0d0d0d; text-decoration: none; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(245,158,11,0.28); }
  .btn-primary i { font-size: 10px; transition: transform 0.2s; }
  .btn-primary:hover i { transform: translateX(3px); }

  /* ── MINI STAT STRIP ── */
  .stat-strip {
    display: flex; gap: 0;
    border: 1px solid var(--border); margin-bottom: 28px; overflow: hidden;
  }
  .stat-strip-item {
    flex: 1; padding: 18px 22px; border-right: 1px solid var(--border);
    display: flex; align-items: center; gap: 14px;
    text-decoration: none; cursor: pointer;
    transition: background 0.2s; position: relative;
  }
  .stat-strip-item:last-child { border-right: none; }
  .stat-strip-item::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; opacity: 0; transition: opacity 0.2s; }
  .stat-strip-item:hover::before, .stat-strip-item.active::before { opacity: 1; }
  .stat-strip-item:hover { background: rgba(255,255,255,0.025); }
  .stat-strip-item.active { background: rgba(255,255,255,0.03); }

  .ssi-total::before  { background: var(--accent); }
  .ssi-wait::before   { background: var(--yellow); }
  .ssi-ok::before     { background: var(--green); }
  .ssi-reject::before { background: var(--red); }

  .ssi-icon { width: 36px; height: 36px; border: 1px solid; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: transform 0.25s; }
  .stat-strip-item:hover .ssi-icon, .stat-strip-item.active .ssi-icon { transform: rotate(-6deg) scale(1.08); }
  .ssi-total .ssi-icon  { border-color: var(--border-acc); background: var(--accent-glow); }
  .ssi-wait  .ssi-icon  { border-color: var(--yellow-bdr); background: var(--yellow-bg); }
  .ssi-ok    .ssi-icon  { border-color: var(--green-bdr);  background: var(--green-bg); }
  .ssi-reject .ssi-icon { border-color: var(--red-bdr);    background: var(--red-bg); }
  .ssi-total .ssi-icon i  { color: var(--accent);  font-size: 14px; }
  .ssi-wait  .ssi-icon i  { color: var(--yellow);  font-size: 14px; }
  .ssi-ok    .ssi-icon i  { color: var(--green);   font-size: 14px; }
  .ssi-reject .ssi-icon i { color: var(--red);     font-size: 14px; }

  .ssi-info {}
  .ssi-num { font-family: var(--font-serif); font-size: 30px; font-weight: 400; line-height: 1; display: block; }
  .ssi-total  .ssi-num { color: var(--fg); }
  .ssi-wait   .ssi-num { color: var(--yellow); }
  .ssi-ok     .ssi-num { color: var(--green); }
  .ssi-reject .ssi-num { color: var(--red); }
  .ssi-label { font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--fg-low); margin-top: 3px; display: block; }

  /* ── TOOLBAR ── */
  .toolbar {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 20px; flex-wrap: wrap;
  }

  /* search */
  .search-wrap { position: relative; flex: 1; min-width: 200px; max-width: 360px; }
  .search-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); font-size: 12px; color: var(--fg-low); pointer-events: none; }
  .search-input {
    width: 100%; padding: 10px 14px 10px 36px;
    background: rgba(255,255,255,0.03); border: 1px solid var(--border);
    color: var(--fg); font-family: var(--font-sans); font-size: 13px;
    outline: none; transition: border-color 0.2s, background 0.2s;
  }
  .search-input::placeholder { color: var(--fg-low); }
  .search-input:focus { border-color: var(--border-acc); background: rgba(245,158,11,0.02); }

  /* filter pills */
  .filter-pills { display: flex; gap: 6px; flex-wrap: wrap; }
  .filter-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; font-size: 11px; font-weight: 700;
    letter-spacing: 0.06em; text-transform: uppercase;
    border: 1px solid var(--border); background: transparent;
    color: var(--fg-low); text-decoration: none; cursor: pointer;
    transition: background 0.18s, border-color 0.18s, color 0.18s;
  }
  .filter-pill:hover { border-color: rgba(255,255,255,0.18); color: var(--fg); background: rgba(255,255,255,0.04); }
  .filter-pill.active-all    { border-color: var(--border-acc); color: var(--accent); background: var(--accent-glow); }
  .filter-pill.active-wait   { border-color: var(--yellow-bdr); color: var(--yellow); background: var(--yellow-bg); }
  .filter-pill.active-ok     { border-color: var(--green-bdr);  color: var(--green);  background: var(--green-bg); }
  .filter-pill.active-reject { border-color: var(--red-bdr);    color: var(--red);    background: var(--red-bg); }
  .filter-pill i { font-size: 9px; }

  /* result info */
  .result-info { font-size: 11px; color: var(--fg-low); margin-left: auto; white-space: nowrap; }
  .result-info strong { color: var(--fg-mid); }

  /* ── TABLE ── */
  .table-wrap { border: 1px solid var(--border); overflow-x: auto; margin-bottom: 28px; }
  .table-head {
    display: grid;
    grid-template-columns: 2.5fr 1.3fr 1fr 1.2fr 1.3fr 110px;
    background: rgba(255,255,255,0.03);
    border-bottom: 1px solid var(--border);
    min-width: 700px;
  }
  .th {
    padding: 12px 18px; font-size: 8.5px; font-weight: 800;
    letter-spacing: 0.2em; text-transform: uppercase; color: var(--fg-low);
    border-right: 1px solid var(--border);
  }
  .th:last-child { border-right: none; }

  .table-row {
    display: grid;
    grid-template-columns: 2.5fr 1.3fr 1fr 1.2fr 1.3fr 110px;
    border-bottom: 1px solid var(--border);
    min-width: 700px;
    position: relative;
    transition: background 0.18s;
  }
  .table-row:last-child { border-bottom: none; }
  .table-row::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 0; background: var(--accent); transition: width 0.2s; }
  .table-row:hover { background: rgba(255,255,255,0.025); }
  .table-row:hover::before { width: 3px; }

  .td {
    padding: 16px 18px; font-size: 13px; color: var(--fg-mid);
    border-right: 1px solid var(--border); display: flex; align-items: center; gap: 10px;
  }
  .td:last-child { border-right: none; }

  .v-icon { width: 34px; height: 34px; flex-shrink: 0; border: 1px solid var(--border); background: rgba(255,255,255,0.04); display: flex; align-items: center; justify-content: center; font-size: 15px; }
  .v-name { font-weight: 700; color: var(--fg); font-size: 13px; line-height: 1.2; }
  .v-plat { font-family: ui-monospace,'Courier New',monospace; font-size: 10px; color: var(--fg-low); letter-spacing: 0.08em; margin-top: 2px; }

  .badge { font-size: 8.5px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; padding: 4px 10px; border: 1px solid; white-space: nowrap; }
  .badge-ok     { color: var(--green);  background: var(--green-bg);  border-color: var(--green-bdr); }
  .badge-wait   { color: var(--yellow); background: var(--yellow-bg); border-color: var(--yellow-bdr); }
  .badge-reject { color: var(--red);    background: var(--red-bg);    border-color: var(--red-bdr); }

  .date-text { font-size: 12px; color: var(--fg-low); }

  .row-action { font-size: 11px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--fg-low); text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: color 0.2s; }
  .row-action:hover { color: var(--accent); }
  .row-action i { font-size: 9px; }

  /* ── EMPTY STATE ── */
  .empty-state {
    padding: 64px 24px; text-align: center;
    border: 1px dashed var(--border); background: rgba(255,255,255,0.01);
  }
  .empty-icon { width: 64px; height: 64px; border: 1px solid var(--border); background: rgba(255,255,255,0.03); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
  .empty-icon i { font-size: 24px; color: var(--fg-low); }
  .empty-title { font-family: var(--font-serif); font-size: 24px; font-weight: 400; color: var(--fg); margin-bottom: 8px; }
  .empty-title em { font-style: italic; color: var(--fg-low); }
  .empty-desc { font-size: 13px; color: var(--fg-low); margin-bottom: 24px; }

  /* ── PAGINATION ── */
  .pagination { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
  .pagination-info { font-size: 11px; color: var(--fg-low); }
  .pagination-info strong { color: var(--fg-mid); }
  .pagination-btns { display: flex; gap: 4px; }
  .page-btn {
    width: 34px; height: 34px; border: 1px solid var(--border); background: transparent;
    color: var(--fg-low); font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; text-decoration: none; transition: background 0.18s, border-color 0.18s, color 0.18s;
  }
  .page-btn:hover { border-color: rgba(255,255,255,0.18); color: var(--fg); background: rgba(255,255,255,0.04); }
  .page-btn.active { border-color: var(--border-acc); background: var(--accent-glow); color: var(--accent); }
  .page-btn.disabled { opacity: 0.3; pointer-events: none; }
  .page-btn i { font-size: 10px; }

  /* ── RESPONSIVE ── */
  @media (max-width: 992px) {
    .nav-inner { padding: 0 20px; }
    .nav-center { display: none; }
    .page { padding: 32px 20px 60px; }
    .stat-strip { flex-wrap: wrap; }
    .stat-strip-item { flex: 0 0 50%; border-bottom: 1px solid var(--border); }
    .stat-strip-item:nth-child(2) { border-right: none; }
  }
  @media (max-width: 576px) {
    .page-header { flex-direction: column; align-items: flex-start; }
    .stat-strip-item { flex: 0 0 100%; border-right: none; }
    .toolbar { flex-direction: column; align-items: flex-start; }
    .search-wrap { max-width: 100%; width: 100%; }
    .result-info { margin-left: 0; }
  }
  @media (prefers-reduced-motion: reduce) {
    .btn-primary:hover, .stat-strip-item.active .ssi-icon { transform: none !important; }
  }
  </style>
</head>
<body>

<!-- TOPNAV -->
<nav class="nav">
  <div class="nav-inner">
    <a href="../index.php" class="nav-brand">
      <div class="nav-brand-icon"><i class="fa-solid fa-square-parking"></i></div>
      <div>
        <span class="nav-brand-name">Stemba Parking</span>
        <span class="nav-brand-sub">SMKN 7 Semarang</span>
      </div>
    </a>
    <div class="nav-center">
      <a class="nav-link" href="dashboard-user.php"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
      <a class="nav-link active" href="kendaraan.php"><i class="fa-solid fa-motorcycle"></i> Kendaraan</a>
      <a class="nav-link" href="daftar-kendaraan.php"><i class="fa-solid fa-plus"></i> Daftarkan</a>
    </div>
    <div class="nav-right">
      <div class="db-avatar" title="<?= htmlspecialchars($user['username']) ?>">
        <?php if ($user['foto']): ?>
          <img src="../<?= htmlspecialchars($user['foto']) ?>" alt="">
        <?php else: ?>
          <?= $inisial ?>
        <?php endif; ?>
      </div>
      <a class="btn-logout" href="../logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Keluar
      </a>
    </div>
  </div>
</nav>

<main class="page">

  <!-- PAGE HEADER -->
  <div class="page-header" data-aos="fade-up" data-aos-duration="600">
    <div>
      <span class="page-eyebrow">Kendaraanku</span>
      <h1 class="page-title">Semua <em>Kendaraan</em></h1>
    </div>
    <a class="btn-primary" href="daftar-kendaraan.php">
      <i class="fa-solid fa-plus"></i> Daftarkan Baru
    </a>
  </div>

  <!-- STAT STRIP — clickable filter -->
  <div class="stat-strip" data-aos="fade-up" data-aos-delay="60" data-aos-duration="700">
    <a href="?status=semua<?= $search ? '&q='.urlencode($search) : '' ?>"
       class="stat-strip-item ssi-total <?= $filter === 'semua' ? 'active' : '' ?>">
      <div class="ssi-icon"><i class="fa-solid fa-car-side"></i></div>
      <div class="ssi-info">
        <span class="ssi-num"><?= $stats['total'] ?></span>
        <span class="ssi-label">Semua</span>
      </div>
    </a>
    <a href="?status=menunggu<?= $search ? '&q='.urlencode($search) : '' ?>"
       class="stat-strip-item ssi-wait <?= $filter === 'menunggu' ? 'active' : '' ?>">
      <div class="ssi-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
      <div class="ssi-info">
        <span class="ssi-num"><?= $stats['menunggu'] ?></span>
        <span class="ssi-label">Menunggu</span>
      </div>
    </a>
    <a href="?status=disetujui<?= $search ? '&q='.urlencode($search) : '' ?>"
       class="stat-strip-item ssi-ok <?= $filter === 'disetujui' ? 'active' : '' ?>">
      <div class="ssi-icon"><i class="fa-solid fa-circle-check"></i></div>
      <div class="ssi-info">
        <span class="ssi-num"><?= $stats['disetujui'] ?></span>
        <span class="ssi-label">Disetujui</span>
      </div>
    </a>
    <a href="?status=ditolak<?= $search ? '&q='.urlencode($search) : '' ?>"
       class="stat-strip-item ssi-reject <?= $filter === 'ditolak' ? 'active' : '' ?>">
      <div class="ssi-icon"><i class="fa-solid fa-circle-xmark"></i></div>
      <div class="ssi-info">
        <span class="ssi-num"><?= $stats['ditolak'] ?></span>
        <span class="ssi-label">Ditolak</span>
      </div>
    </a>
  </div>

  <!-- TOOLBAR -->
  <div class="toolbar" data-aos="fade-up" data-aos-delay="100" data-aos-duration="600">

    <!-- search -->
    <form method="GET" action="" style="display:contents;">
      <input type="hidden" name="status" value="<?= htmlspecialchars($filter) ?>">
      <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input class="search-input" type="text" name="q"
          placeholder="Cari nama atau nomor plat..."
          value="<?= htmlspecialchars($search) ?>"
          id="search-input">
      </div>
    </form>

    <!-- filter pills -->
    <div class="filter-pills">
      <?php
      $pills = [
        'semua'    => ['label' => 'Semua',    'cls' => 'active-all',    'icon' => 'fa-list'],
        'menunggu' => ['label' => 'Menunggu', 'cls' => 'active-wait',   'icon' => 'fa-clock-rotate-left'],
        'disetujui'=> ['label' => 'Disetujui','cls' => 'active-ok',     'icon' => 'fa-circle-check'],
        'ditolak'  => ['label' => 'Ditolak',  'cls' => 'active-reject', 'icon' => 'fa-circle-xmark'],
      ];
      foreach ($pills as $val => $p):
        $is_active = $filter === $val;
        $cls = $is_active ? $p['cls'] : '';
        $url = '?status=' . $val . ($search ? '&q='.urlencode($search) : '');
      ?>
      <a href="<?= $url ?>" class="filter-pill <?= $cls ?>">
        <i class="fa-solid <?= $p['icon'] ?>"></i> <?= $p['label'] ?>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- result count -->
    <span class="result-info">
      <strong><?= $total ?></strong> kendaraan ditemukan
    </span>

  </div>

  <!-- TABLE -->
  <?php if (empty($kendaraan_list)): ?>
  <div class="empty-state" data-aos="fade-up">
    <div class="empty-icon"><i class="fa-solid fa-motorcycle"></i></div>
    <h2 class="empty-title">
      <?= $search ? 'Tidak <em>ditemukan</em>' : 'Belum ada <em>kendaraan</em>' ?>
    </h2>
    <p class="empty-desc">
      <?= $search
        ? 'Coba kata kunci lain atau hapus filter pencarian.'
        : 'Kamu belum mendaftarkan kendaraan apapun.' ?>
    </p>
    <?php if (!$search): ?>
    <a class="btn-primary" href="daftar-kendaraan.php">
      <i class="fa-solid fa-plus"></i> Daftarkan Sekarang
    </a>
    <?php else: ?>
    <a class="btn-primary" href="kendaraan.php">
      <i class="fa-solid fa-arrow-left"></i> Lihat Semua
    </a>
    <?php endif; ?>
  </div>

  <?php else: ?>

  <div class="table-wrap" data-aos="fade-up" data-aos-delay="80" data-aos-duration="700">
    <div class="table-head">
      <div class="th">Kendaraan</div>
      <div class="th">Nomor Plat</div>
      <div class="th">Jenis</div>
      <div class="th">Status</div>
      <div class="th">Didaftarkan</div>
      <div class="th">Aksi</div>
    </div>

    <?php foreach ($kendaraan_list as $k):
      $icon = match($k['jenis'] ?? '') {
        'motor'  => '🏍️', 'mobil' => '🚗', => '🚲', default => '🚗'
      };
      $badge_class = match($k['status']) {
        'disetujui' => 'badge-ok',
        'menunggu'  => 'badge-wait',
        'ditolak'   => 'badge-reject',
        default     => 'badge-wait'
      };
      $badge_text = match($k['status']) {
        'disetujui' => 'Disetujui',
        'menunggu'  => 'Menunggu',
        'ditolak'   => 'Ditolak',
        default     => 'Menunggu'
      };
      $tgl = date('d M Y', strtotime($k['created_at']));
    ?>
    <div class="table-row">
      <div class="td">
        <div class="v-icon"><?= $icon ?></div>
        <div>
          <div class="v-name"><?= htmlspecialchars($k['nama_kendaraan']) ?></div>
          <div class="v-plat"><?= htmlspecialchars($k['nomor_tnkb']) ?></div>
        </div>
      </div>
      <div class="td">
        <span style="font-family:ui-monospace,monospace;font-size:12px;letter-spacing:0.08em;">
          <?= htmlspecialchars($k['nomor_tnkb']) ?>
        </span>
      </div>
      <div class="td"><?= ucfirst($k['jenis'] ?? '-') ?></div>
      <div class="td"><span class="badge <?= $badge_class ?>"><?= $badge_text ?></span></div>
      <div class="td"><span class="date-text"><?= $tgl ?></span></div>
      <div class="td">
        <a href="detail-kendaraan.php?id=<?= $k['id'] ?>" class="row-action">
          Detail <i class="fa-solid fa-chevron-right"></i>
        </a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- PAGINATION -->
  <?php if ($total_page > 1): ?>
  <div class="pagination" data-aos="fade-up" data-aos-duration="500">
    <span class="pagination-info">
      Halaman <strong><?= $page ?></strong> dari <strong><?= $total_page ?></strong>
      &nbsp;·&nbsp; <strong><?= $total ?></strong> total kendaraan
    </span>
    <div class="pagination-btns">
      <!-- prev -->
      <?php
      $base_url = '?status=' . urlencode($filter) . ($search ? '&q='.urlencode($search) : '');
      ?>
      <a href="<?= $base_url ?>&page=<?= max(1,$page-1) ?>"
         class="page-btn <?= $page <= 1 ? 'disabled' : '' ?>">
        <i class="fa-solid fa-chevron-left"></i>
      </a>
      <!-- page numbers -->
      <?php
      $start = max(1, $page - 2);
      $end   = min($total_page, $page + 2);
      if ($start > 1): ?>
        <a href="<?= $base_url ?>&page=1" class="page-btn">1</a>
        <?php if ($start > 2): ?><span class="page-btn" style="pointer-events:none;opacity:0.3;">…</span><?php endif; ?>
      <?php endif;
      for ($i = $start; $i <= $end; $i++): ?>
        <a href="<?= $base_url ?>&page=<?= $i ?>"
           class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
      <?php endfor;
      if ($end < $total_page): ?>
        <?php if ($end < $total_page - 1): ?><span class="page-btn" style="pointer-events:none;opacity:0.3;">…</span><?php endif; ?>
        <a href="<?= $base_url ?>&page=<?= $total_page ?>" class="page-btn"><?= $total_page ?></a>
      <?php endif; ?>
      <!-- next -->
      <a href="<?= $base_url ?>&page=<?= min($total_page,$page+1) ?>"
         class="page-btn <?= $page >= $total_page ? 'disabled' : '' ?>">
        <i class="fa-solid fa-chevron-right"></i>
      </a>
    </div>
  </div>
  <?php endif; ?>

  <?php endif; ?>

</main>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({ once: true, offset: 40, easing: 'ease-out-quart', duration: 650 });

/* search submit on enter / debounce */
(function(){
  var inp = document.getElementById('search-input');
  if (!inp) return;
  var timer;
  inp.addEventListener('input', function() {
    clearTimeout(timer);
    timer = setTimeout(function() { inp.closest('form').submit(); }, 500);
  });
})();
</script>
</body>
</html>