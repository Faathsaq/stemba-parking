<?php
// admin/detail-pendaftaran.php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

// ── PROSES AKSI ───────────────────────────────────────────
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi'])) {
    $aksi    = $_POST['aksi'];
    $catatan = trim($_POST['catatan'] ?? '');

    if (in_array($aksi, ['disetujui', 'ditolak'])) {
        $stmt = $pdo->prepare("
            UPDATE pendaftaran
            SET status = ?, catatan_admin = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$aksi, $catatan ?: null, $id]);
        $success = $aksi === 'disetujui'
            ? 'Pendaftaran berhasil disetujui.'
            : 'Pendaftaran berhasil ditolak.';
    }
}

// ── FETCH DATA ────────────────────────────────────────────
$stmt = $pdo->prepare("SELECT * FROM pendaftaran WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { header('Location: index.php'); exit; }

$status_cfg = [
  'disetujui' => ['label'=>'Disetujui', 'cls'=>'vs-disetujui', 'icon'=>'fas fa-check-circle'],
  'ditolak'   => ['label'=>'Ditolak',   'cls'=>'vs-ditolak',   'icon'=>'fas fa-times-circle'],
  'menunggu'  => ['label'=>'Menunggu',  'cls'=>'vs-menunggu',  'icon'=>'fas fa-clock'],
];
$sc = $status_cfg[$p['status']] ?? $status_cfg['menunggu'];

$jenis_icon = match($p['jenis'] ?? '') {
  'mobil'  => 'fas fa-car',
  'sepeda' => 'fas fa-bicycle',
  default  => 'fas fa-motorcycle',
};
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Pendaftaran #<?= $id ?> — Admin Stemba Parking</title>

  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
:root {
  --font-serif: 'Instrument Serif', Georgia, serif;
  --font-sans:  'Outfit', sans-serif;
  --accent:     #3D45AA;
  --accent-lt:  #5460cc;
  --accent-dim: rgba(61,69,170,0.10);
  --accent-glow:rgba(61,69,170,0.05);
  --bg:         #f7f6f2;
  --bg-card:    #ffffff;
  --fg:         #1a1208;
  --fg-mid:     rgba(26,18,8,0.55);
  --fg-low:     rgba(26,18,8,0.28);
  --border:     rgba(26,18,8,0.08);
  --border-acc: rgba(61,69,170,0.20);
  --nav-h:      68px;
  --green:      #4a7c59;
  --green-bg:   rgba(74,124,89,0.09);
  --yellow:     #92700a;
  --yellow-bg:  rgba(146,112,10,0.09);
  --red:        #9b2c2c;
  --red-bg:     rgba(155,44,44,0.08);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { background: var(--bg); color: var(--fg); font-family: var(--font-sans); min-height: 100vh; }

body::before {
  content: '';
  position: fixed; inset: 0; z-index: 0; pointer-events: none;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
  background-size: 180px; opacity: 0.7;
}

/* ── TOPBAR ─────────────────────────────────────────────── */
.adm-topbar {
  position: fixed; top: 0; left: 0; right: 0; z-index: 999;
  height: var(--nav-h);
  background: rgba(247,246,242,0.90);
  backdrop-filter: blur(28px) saturate(160%);
  border-bottom: 1px solid var(--border);
}
.adm-topbar::after {
  content: '';
  position: absolute; bottom: 0; left: 0; right: 0; height: 1px;
  background: linear-gradient(90deg, transparent, rgba(61,69,170,0.22) 50%, transparent);
}
.adm-topbar-inner {
  max-width: 1200px; margin: 0 auto; padding: 0 32px;
  height: var(--nav-h); display: flex; align-items: center; gap: 16px;
}
.adm-back {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 7px 16px; border-radius: 2px;
  font-size: 11px; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase;
  color: var(--fg-mid); text-decoration: none;
  border: 1px solid var(--border); background: transparent;
  transition: all 0.18s;
}
.adm-back:hover { color: var(--accent); border-color: var(--border-acc); background: var(--accent-dim); text-decoration: none; }
.adm-back i { font-size: 10px; }
.adm-topbar-title {
  font-size: 12px; font-weight: 800; letter-spacing: 0.06em;
  text-transform: uppercase; color: var(--fg);
}
.adm-topbar-id {
  font-size: 11px; color: var(--fg-low); font-weight: 600;
}
.adm-topbar-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }

/* ── WRAP ───────────────────────────────────────────────── */
.adm-wrap {
  position: relative; z-index: 1;
  max-width: 1200px; margin: 0 auto;
  padding: calc(var(--nav-h) + 40px) 32px 80px;
}

/* ── ALERT ──────────────────────────────────────────────── */
.adm-alert {
  padding: 14px 20px; margin-bottom: 28px;
  font-size: 12.5px; font-weight: 700; letter-spacing: 0.02em;
  border-radius: 2px; display: flex; align-items: center; gap: 10px;
  border: 1px solid transparent;
}
.adm-alert-success { background: var(--green-bg); color: var(--green); border-color: rgba(74,124,89,0.2); }
.adm-alert-error   { background: var(--red-bg);   color: var(--red);   border-color: rgba(155,44,44,0.18); }

/* ── LAYOUT ─────────────────────────────────────────────── */
.adm-layout {
  display: grid;
  grid-template-columns: 1fr 360px;
  gap: 1px;
  background: var(--border);
  border: 1px solid var(--border);
  margin-bottom: 1px;
}

/* ── PANEL ──────────────────────────────────────────────── */
.adm-panel {
  background: var(--bg-card);
  padding: 32px;
}
.adm-panel-label {
  font-size: 8.5px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--fg-low); display: block; margin-bottom: 20px;
  padding-bottom: 12px; border-bottom: 1px solid var(--border);
}

/* ── PAGE HEADER ────────────────────────────────────────── */
.adm-detail-header { margin-bottom: 32px; }
.adm-detail-overline {
  font-size: 9px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
  color: var(--accent); display: block; margin-bottom: 10px;
}
.adm-detail-title {
  font-family: var(--font-serif);
  font-size: clamp(28px, 4vw, 48px);
  font-weight: 400; line-height: 1; letter-spacing: -0.02em;
  color: var(--fg); margin-bottom: 10px;
}
.adm-detail-title em { color: var(--accent); font-style: italic; }

/* status badge */
.adm-status-badge {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: 10px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase;
  padding: 6px 14px; border-radius: 2px; border: 1px solid transparent;
}
.vs-disetujui { background: var(--green-bg); color: var(--green); border-color: rgba(74,124,89,0.2); }
.vs-menunggu  { background: var(--yellow-bg); color: var(--yellow); border-color: rgba(146,112,10,0.2); }
.vs-ditolak   { background: var(--red-bg); color: var(--red); border-color: rgba(155,44,44,0.18); }

/* ── INFO ROWS ──────────────────────────────────────────── */
.adm-info-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: 0;
  border: 1px solid var(--border); margin-bottom: 24px;
}
.adm-info-cell {
  padding: 16px 20px;
  border-right: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
}
.adm-info-cell:nth-child(even) { border-right: none; }
.adm-info-cell-lbl {
  font-size: 9px; font-weight: 800; letter-spacing: 0.16em; text-transform: uppercase;
  color: var(--fg-low); display: block; margin-bottom: 5px;
}
.adm-info-cell-val {
  font-size: 13.5px; font-weight: 700; color: var(--fg);
}
.adm-info-cell-val.mono {
  font-family: ui-monospace, 'Courier New', monospace;
  font-size: 14px; color: var(--accent); letter-spacing: 0.08em;
}

/* ── FOTO SECTION ───────────────────────────────────────── */
.adm-foto-grid {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px;
  background: var(--border); border: 1px solid var(--border);
  margin-bottom: 0;
}
.adm-foto-item {
  background: var(--accent-dim);
  aspect-ratio: 4/3; overflow: hidden;
  display: flex; align-items: center; justify-content: center;
  position: relative; cursor: pointer;
}
.adm-foto-item img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform 0.3s ease;
}
.adm-foto-item:hover img { transform: scale(1.06); }
.adm-foto-item-placeholder {
  font-size: 28px; color: rgba(61,69,170,0.18);
  display: flex; flex-direction: column; align-items: center; gap: 8px;
}
.adm-foto-item-placeholder span {
  font-size: 9px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;
  color: var(--fg-low);
}
.adm-foto-overlay {
  position: absolute; inset: 0; background: rgba(26,18,8,0);
  display: flex; align-items: center; justify-content: center;
  transition: background 0.2s;
}
.adm-foto-item:hover .adm-foto-overlay { background: rgba(26,18,8,0.18); }
.adm-foto-overlay i { color: white; font-size: 20px; opacity: 0; transition: opacity 0.2s; }
.adm-foto-item:hover .adm-foto-overlay i { opacity: 1; }
.adm-foto-lbl {
  position: absolute; bottom: 8px; left: 10px;
  font-size: 8px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase;
  color: white; text-shadow: 0 1px 4px rgba(0,0,0,0.5);
}

/* ── SIDEBAR ────────────────────────────────────────────── */
.adm-sidebar { display: flex; flex-direction: column; gap: 1px; background: var(--border); }
.adm-sidebar-panel { background: var(--bg-card); padding: 28px; }

/* action buttons */
.adm-action-btn {
  width: 100%; padding: 13px 20px;
  font-family: var(--font-sans); font-size: 12px; font-weight: 800;
  letter-spacing: 0.08em; text-transform: uppercase;
  border-radius: 2px; border: 1px solid transparent;
  display: flex; align-items: center; justify-content: center; gap: 9px;
  cursor: pointer; transition: all 0.2s; margin-bottom: 8px;
}
.adm-action-btn:last-child { margin-bottom: 0; }
.adm-action-btn i { font-size: 11px; }

.btn-approve {
  background: var(--green); color: #fff; border-color: var(--green);
}
.btn-approve:hover {
  background: #3d6b4a; box-shadow: 0 6px 20px rgba(74,124,89,0.28);
  transform: translateY(-2px);
}
.btn-reject {
  background: transparent; color: var(--red); border-color: rgba(155,44,44,0.30);
}
.btn-reject:hover {
  background: var(--red-bg); border-color: var(--red);
}
.btn-disabled {
  background: rgba(26,18,8,0.05); color: var(--fg-low);
  border-color: var(--border); cursor: not-allowed; opacity: 0.6;
}

/* timeline */
.adm-timeline { display: flex; flex-direction: column; gap: 0; }
.adm-tl-item {
  display: flex; gap: 14px; padding: 14px 0;
  border-bottom: 1px solid var(--border);
  font-size: 12px;
}
.adm-tl-item:last-child { border-bottom: none; padding-bottom: 0; }
.adm-tl-dot {
  width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
  margin-top: 4px;
}
.dot-green  { background: var(--green); }
.dot-yellow { background: var(--yellow); }
.dot-red    { background: var(--red); }
.dot-grey   { background: var(--fg-low); }
.adm-tl-body {}
.adm-tl-title { font-weight: 700; color: var(--fg); font-size: 12px; }
.adm-tl-sub   { font-size: 10.5px; color: var(--fg-low); margin-top: 2px; }

/* catatan box */
.adm-catatan-box {
  background: var(--accent-dim); border: 1px solid var(--border-acc);
  padding: 14px 16px; margin-top: 16px;
  font-size: 12px; color: var(--fg-mid); line-height: 1.6;
  font-style: italic;
}
.adm-catatan-box strong { color: var(--accent); font-style: normal; font-size: 9px; letter-spacing: 0.14em; text-transform: uppercase; display: block; margin-bottom: 6px; }

/* ── LIGHTBOX ───────────────────────────────────────────── */
.adm-lightbox {
  position: fixed; inset: 0; z-index: 9999;
  background: rgba(10,8,4,0.92);
  backdrop-filter: blur(12px);
  display: none; align-items: center; justify-content: center;
}
.adm-lightbox.is-open { display: flex; }
.adm-lightbox img {
  max-width: 90vw; max-height: 85vh;
  object-fit: contain; border: 1px solid rgba(255,255,255,0.12);
}
.adm-lightbox-close {
  position: absolute; top: 24px; right: 24px;
  width: 40px; height: 40px; border-radius: 2px;
  background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);
  color: white; font-size: 16px; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background 0.2s;
}
.adm-lightbox-close:hover { background: rgba(255,255,255,0.18); }

/* ── MODAL ──────────────────────────────────────────────── */
.adm-modal-overlay {
  position: fixed; inset: 0; z-index: 8888;
  background: rgba(10,8,4,0.65);
  backdrop-filter: blur(8px);
  display: none; align-items: center; justify-content: center;
  padding: 20px;
}
.adm-modal-overlay.is-open { display: flex; }
.adm-modal {
  background: var(--bg-card);
  border: 1px solid var(--border);
  max-width: 440px; width: 100%;
  position: relative;
  box-shadow: 0 32px 80px rgba(0,0,0,0.25);
}
.adm-modal::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
}
.adm-modal.modal-approve::before { background: var(--green); }
.adm-modal.modal-reject::before  { background: var(--red); }

.adm-modal-head {
  padding: 24px 28px 16px;
  border-bottom: 1px solid var(--border);
}
.adm-modal-title {
  font-family: var(--font-serif);
  font-size: 26px; font-weight: 400; color: var(--fg); margin-bottom: 6px;
}
.adm-modal-sub { font-size: 12px; color: var(--fg-mid); }
.adm-modal-body { padding: 24px 28px; }
.adm-modal-label {
  font-size: 9px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase;
  color: var(--fg-low); display: block; margin-bottom: 8px;
}
.adm-modal-textarea {
  width: 100%; padding: 12px 14px;
  background: var(--bg); border: 1px solid var(--border);
  color: var(--fg); font-family: var(--font-sans); font-size: 13px;
  line-height: 1.6; resize: vertical; min-height: 90px;
  transition: border-color 0.18s;
  border-radius: 2px;
}
.adm-modal-textarea:focus {
  outline: none; border-color: var(--border-acc);
  background: var(--accent-glow);
}
.adm-modal-textarea::placeholder { color: var(--fg-low); font-style: italic; }
.adm-modal-foot {
  padding: 16px 28px 24px;
  display: flex; gap: 8px;
}
.adm-modal-btn {
  flex: 1; padding: 11px 16px;
  font-family: var(--font-sans); font-size: 11.5px; font-weight: 800;
  letter-spacing: 0.08em; text-transform: uppercase;
  border-radius: 2px; border: 1px solid transparent;
  cursor: pointer; transition: all 0.18s;
  display: flex; align-items: center; justify-content: center; gap: 7px;
}
.adm-modal-btn i { font-size: 10px; }
.btn-modal-confirm-approve {
  background: var(--green); color: #fff; border-color: var(--green);
}
.btn-modal-confirm-approve:hover { background: #3d6b4a; }
.btn-modal-confirm-reject {
  background: var(--red); color: #fff; border-color: var(--red);
}
.btn-modal-confirm-reject:hover { background: #7f2020; }
.btn-modal-cancel {
  background: transparent; color: var(--fg-mid); border-color: var(--border);
  flex: 0 0 auto; padding: 11px 20px;
}
.btn-modal-cancel:hover { background: rgba(26,18,8,0.04); color: var(--fg); }

/* ── RESPONSIVE ─────────────────────────────────────────── */
@media (max-width: 900px) {
  .adm-layout { grid-template-columns: 1fr; }
  .adm-info-grid { grid-template-columns: 1fr; }
  .adm-info-cell { border-right: none !important; }
}
@media (max-width: 600px) {
  .adm-wrap { padding: calc(var(--nav-h) + 24px) 16px 60px; }
  .adm-foto-grid { grid-template-columns: 1fr 1fr; }
  .adm-topbar-inner { padding: 0 16px; }
}
</style>
</head>
<body>

<!-- LIGHTBOX -->
<div class="adm-lightbox" id="lightbox">
  <button class="adm-lightbox-close" id="lightboxClose"><i class="fas fa-times"></i></button>
  <img src="" id="lightboxImg" alt="Preview foto">
</div>

<!-- MODAL APPROVE -->
<div class="adm-modal-overlay" id="modalApprove">
  <div class="adm-modal modal-approve">
    <div class="adm-modal-head">
      <div class="adm-modal-title">Setujui Pendaftaran</div>
      <p class="adm-modal-sub">
        Kendaraan <strong><?= htmlspecialchars($p['nama_kendaraan']) ?></strong>
        atas nama <strong><?= htmlspecialchars($p['nama_lengkap']) ?></strong>
        akan disetujui.
      </p>
    </div>
    <form method="POST">
      <input type="hidden" name="aksi" value="disetujui">
      <div class="adm-modal-body">
        <label class="adm-modal-label">Catatan Admin <span style="color:var(--fg-low);font-weight:400;text-transform:none;letter-spacing:0">(opsional)</span></label>
        <textarea name="catatan" class="adm-modal-textarea"
          placeholder="Tambahkan catatan atau pesan untuk siswa..."><?= htmlspecialchars($p['catatan_admin'] ?? '') ?></textarea>
      </div>
      <div class="adm-modal-foot">
        <button type="button" class="adm-modal-btn btn-modal-cancel" onclick="closeModal('modalApprove')">
          Batal
        </button>
        <button type="submit" class="adm-modal-btn btn-modal-confirm-approve">
          <i class="fas fa-check"></i> Ya, Setujui
        </button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL REJECT -->
<div class="adm-modal-overlay" id="modalReject">
  <div class="adm-modal modal-reject">
    <div class="adm-modal-head">
      <div class="adm-modal-title">Tolak Pendaftaran</div>
      <p class="adm-modal-sub">
        Kendaraan <strong><?= htmlspecialchars($p['nama_kendaraan']) ?></strong>
        atas nama <strong><?= htmlspecialchars($p['nama_lengkap']) ?></strong>
        akan ditolak.
      </p>
    </div>
    <form method="POST">
      <input type="hidden" name="aksi" value="ditolak">
      <div class="adm-modal-body">
        <label class="adm-modal-label">Alasan Penolakan <span style="color:var(--red);font-size:10px">*</span></label>
        <textarea name="catatan" class="adm-modal-textarea" required
          placeholder="Tuliskan alasan penolakan untuk siswa..."><?= htmlspecialchars($p['catatan_admin'] ?? '') ?></textarea>
      </div>
      <div class="adm-modal-foot">
        <button type="button" class="adm-modal-btn btn-modal-cancel" onclick="closeModal('modalReject')">
          Batal
        </button>
        <button type="submit" class="adm-modal-btn btn-modal-confirm-reject">
          <i class="fas fa-times"></i> Ya, Tolak
        </button>
      </div>
    </form>
  </div>
</div>

<!-- TOPBAR -->
<header class="adm-topbar">
  <div class="adm-topbar-inner">
    <a href="index.php" class="adm-back">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <span class="adm-topbar-title">Detail Pendaftaran</span>
    <span class="adm-topbar-id">#<?= str_pad($id, 4, '0', STR_PAD_LEFT) ?></span>
    <div class="adm-topbar-right">
      <span class="adm-status-badge <?= $sc['cls'] ?>">
        <i class="<?= $sc['icon'] ?>"></i>
        <?= $sc['label'] ?>
      </span>
    </div>
  </div>
</header>

<!-- MAIN -->
<main class="adm-wrap">

  <?php if ($success): ?>
  <div class="adm-alert adm-alert-success">
    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
  </div>
  <?php endif; ?>
  <?php if ($error): ?>
  <div class="adm-alert adm-alert-error">
    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
  </div>
  <?php endif; ?>

  <div class="adm-layout">

    <!-- KOLOM KIRI: detail -->
    <div class="adm-panel">
      <span class="adm-panel-label">Informasi Pendaftaran</span>

      <div class="adm-detail-header">
        <span class="adm-detail-overline">ID #<?= str_pad($id, 4, '0', STR_PAD_LEFT) ?> · <?= date('d M Y', strtotime($p['created_at'])) ?></span>
        <h1 class="adm-detail-title">
          <?= htmlspecialchars($p['nama_kendaraan']) ?><br>
          <em><?= htmlspecialchars($p['nomor_tnkb']) ?></em>
        </h1>
      </div>

      <!-- INFO GRID -->
      <div class="adm-info-grid">
        <div class="adm-info-cell">
          <span class="adm-info-cell-lbl">Nama Lengkap</span>
          <span class="adm-info-cell-val"><?= htmlspecialchars($p['nama_lengkap']) ?></span>
        </div>
        <div class="adm-info-cell">
          <span class="adm-info-cell-lbl">NIS</span>
          <span class="adm-info-cell-val mono"><?= htmlspecialchars($p['nis']) ?></span>
        </div>
        <div class="adm-info-cell">
          <span class="adm-info-cell-lbl">Kelas</span>
          <span class="adm-info-cell-val"><?= htmlspecialchars($p['kelas']) ?></span>
        </div>
        <div class="adm-info-cell">
          <span class="adm-info-cell-lbl">Umur</span>
          <span class="adm-info-cell-val"><?= htmlspecialchars($p['umur']) ?> tahun</span>
        </div>
        <div class="adm-info-cell">
          <span class="adm-info-cell-lbl">Email</span>
          <span class="adm-info-cell-val"><?= htmlspecialchars($p['email']) ?></span>
        </div>
        <div class="adm-info-cell">
          <span class="adm-info-cell-lbl">No. Telepon</span>
          <span class="adm-info-cell-val"><?= htmlspecialchars($p['no_telepon']) ?></span>
        </div>
        <div class="adm-info-cell">
          <span class="adm-info-cell-lbl">Nama Kendaraan</span>
          <span class="adm-info-cell-val"><?= htmlspecialchars($p['nama_kendaraan']) ?></span>
        </div>
        <div class="adm-info-cell">
          <span class="adm-info-cell-lbl">Jenis</span>
          <span class="adm-info-cell-val" style="text-transform:capitalize">
            <i class="<?= $jenis_icon ?>" style="color:var(--accent);margin-right:6px;font-size:11px"></i>
            <?= htmlspecialchars($p['jenis']) ?>
          </span>
        </div>
        <div class="adm-info-cell" style="grid-column:1/-1">
          <span class="adm-info-cell-lbl">Nomor TNKB / Plat</span>
          <span class="adm-info-cell-val mono" style="font-size:18px"><?= htmlspecialchars($p['nomor_tnkb']) ?></span>
        </div>
      </div>

      <!-- FOTO -->
      <span class="adm-panel-label" style="margin-top:28px">Dokumen & Foto</span>
      <div class="adm-foto-grid">
        <?php
          $fotos = [
            'foto_kendaraan' => 'Foto Kendaraan',
            'foto_sim'       => 'Foto SIM',
            'foto_kartu'     => 'Foto Kartu',
          ];
          foreach ($fotos as $col => $lbl):
            $src = !empty($p[$col]) ? '../' . htmlspecialchars($p[$col]) : null;
        ?>
        <div class="adm-foto-item" <?= $src ? "onclick=\"openLightbox('$src')\"" : '' ?>>
          <?php if ($src): ?>
            <img src="<?= $src ?>" alt="<?= $lbl ?>">
            <div class="adm-foto-overlay"><i class="fas fa-expand-alt"></i></div>
          <?php else: ?>
            <div class="adm-vcard-foto-placeholder">
              <div class="adm-foto-item-placeholder">
                <i class="fas fa-image"></i>
                <span><?= $lbl ?></span>
              </div>
            </div>
          <?php endif; ?>
          <span class="adm-foto-lbl"><?= $lbl ?></span>
        </div>
        <?php endforeach; ?>
      </div>

    </div><!-- /adm-panel -->

    <!-- KOLOM KANAN: sidebar -->
    <div class="adm-sidebar">

      <!-- AKSI -->
      <div class="adm-sidebar-panel">
        <span class="adm-panel-label">Aksi Admin</span>

        <?php if ($p['status'] === 'menunggu'): ?>
          <button class="adm-action-btn btn-approve" onclick="openModal('modalApprove')">
            <i class="fas fa-check-circle"></i> Setujui Pendaftaran
          </button>
          <button class="adm-action-btn btn-reject" onclick="openModal('modalReject')">
            <i class="fas fa-times-circle"></i> Tolak Pendaftaran
          </button>

        <?php elseif ($p['status'] === 'disetujui'): ?>
          <button class="adm-action-btn btn-disabled" disabled>
            <i class="fas fa-check-circle"></i> Sudah Disetujui
          </button>
          <button class="adm-action-btn btn-reject" onclick="openModal('modalReject')">
            <i class="fas fa-times-circle"></i> Ubah ke Ditolak
          </button>

        <?php else: ?>
          <button class="adm-action-btn btn-approve" onclick="openModal('modalApprove')">
            <i class="fas fa-check-circle"></i> Ubah ke Disetujui
          </button>
          <button class="adm-action-btn btn-disabled" disabled>
            <i class="fas fa-times-circle"></i> Sudah Ditolak
          </button>
        <?php endif; ?>

        <?php if (!empty($p['catatan_admin'])): ?>
        <div class="adm-catatan-box">
          <strong>Catatan Admin</strong>
          <?= htmlspecialchars($p['catatan_admin']) ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- TIMELINE -->
      <div class="adm-sidebar-panel">
        <span class="adm-panel-label">Riwayat Status</span>
        <div class="adm-timeline">
          <div class="adm-tl-item">
            <div class="adm-tl-dot dot-yellow"></div>
            <div class="adm-tl-body">
              <div class="adm-tl-title">Pendaftaran Dibuat</div>
              <div class="adm-tl-sub"><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></div>
            </div>
          </div>
          <?php if ($p['status'] !== 'menunggu'): ?>
          <div class="adm-tl-item">
            <div class="adm-tl-dot <?= $p['status']==='disetujui' ? 'dot-green' : 'dot-red' ?>"></div>
            <div class="adm-tl-body">
              <div class="adm-tl-title">
                <?= $p['status']==='disetujui' ? 'Disetujui Admin' : 'Ditolak Admin' ?>
              </div>
              <div class="adm-tl-sub"><?= date('d M Y, H:i', strtotime($p['updated_at'])) ?></div>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- META -->
      <div class="adm-sidebar-panel">
        <span class="adm-panel-label">Meta Data</span>
        <div class="adm-timeline">
          <div class="adm-tl-item">
            <div class="adm-tl-dot dot-grey"></div>
            <div class="adm-tl-body">
              <div class="adm-tl-title">ID Pendaftaran</div>
              <div class="adm-tl-sub" style="font-family:monospace">#<?= str_pad($id, 4, '0', STR_PAD_LEFT) ?></div>
            </div>
          </div>
          <div class="adm-tl-item">
            <div class="adm-tl-dot dot-grey"></div>
            <div class="adm-tl-body">
              <div class="adm-tl-title">User ID Siswa</div>
              <div class="adm-tl-sub" style="font-family:monospace"><?= $p['user_id'] ?></div>
            </div>
          </div>
          <div class="adm-tl-item">
            <div class="adm-tl-dot dot-grey"></div>
            <div class="adm-tl-body">
              <div class="adm-tl-title">Terakhir Diperbarui</div>
              <div class="adm-tl-sub"><?= date('d M Y, H:i', strtotime($p['updated_at'])) ?></div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /adm-sidebar -->

  </div><!-- /adm-layout -->

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Lightbox
function openLightbox(src) {
  document.getElementById('lightboxImg').src = src;
  document.getElementById('lightbox').classList.add('is-open');
}
document.getElementById('lightboxClose').addEventListener('click', function () {
  document.getElementById('lightbox').classList.remove('is-open');
});
document.getElementById('lightbox').addEventListener('click', function (e) {
  if (e.target === this) this.classList.remove('is-open');
});

// Modal
function openModal(id) {
  document.getElementById(id).classList.add('is-open');
}
function closeModal(id) {
  document.getElementById(id).classList.remove('is-open');
}
// close modal on overlay click
document.querySelectorAll('.adm-modal-overlay').forEach(function (el) {
  el.addEventListener('click', function (e) {
    if (e.target === this) this.classList.remove('is-open');
  });
});
// ESC key
document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') {
    document.querySelectorAll('.adm-modal-overlay.is-open, .adm-lightbox.is-open')
      .forEach(function (el) { el.classList.remove('is-open'); });
  }
});
</script>
</body>
</html>