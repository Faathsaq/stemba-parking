<?php
include 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

$stmt = $pdo->prepare("SELECT username, kelas, jurusan, foto FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kelas   = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];
    $fotoName = $user['foto'];

    if (!empty($_POST['foto_cropped'])) {
        $data = explode(',', $_POST['foto_cropped']);
        $img = base64_decode($data[1]);
        if (!is_dir('assets/img/profile')) mkdir('assets/img/profile', 0777, true);
        $fotoName = uniqid() . '.png';
        file_put_contents("assets/img/profile/" . $fotoName, $img);
    }

    $update = $pdo->prepare("UPDATE users SET kelas = ?, jurusan = ?, foto = ? WHERE id = ?");
    $update->execute([$kelas, $jurusan, $fotoName, $user_id]);

    $_SESSION['kelas']   = $kelas;
    $_SESSION['jurusan'] = $jurusan;
    $_SESSION['foto']    = $fotoName;

    $success = "Profil berhasil diperbarui!";
    $user['kelas']   = $kelas;
    $user['jurusan'] = $jurusan;
    $user['foto']    = $fotoName;
}

$jurusanList = [
    'TKR'  => 'Teknik Kendaraan Ringan',
    'TITL' => 'Teknik Instalasi Tenaga Listrik',
    'TME'  => 'Teknik Mekatronika',
    'TEK'  => 'Teknik Elektronika',
    'TFLM' => 'Teknik Fabrikasi Logam',
    'KGSP' => 'Konstruksi Gedung dan Sanitasi',
    'KJIJ' => 'Konstruksi Jalan dan Jembatan',
    'SIJA' => 'Sistem Informasi Jaringan dan Aplikasi',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Profil — Stemba Parking</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

  <style>
  /* ============================================================
     EDIT PROFILE — ALABASTER + BLUE SLATE
     ============================================================ */
  :root {
    --font-serif:     'Instrument Serif', Georgia, serif;
    --font-sans:      'Outfit', sans-serif;

    /* Accent: Blue Slate — sama dengan navbar & about */
    --accent:         #536878;
    --accent-light:   #6b8394;
    --accent-dim:     rgba(83,104,120,0.18);
    --accent-glow:    rgba(83,104,120,0.07);
    --accent-border:  rgba(83,104,120,0.28);

    /* Glass: ivory/alabaster */
    --bg-glass:       rgba(255,253,247,0.75);
    --bg-base:        #fffdf7;

    /* Borders — sama dengan about */
    --border:         rgba(10,10,10,0.09);
    --border-acc:     rgba(83,104,120,0.28);

    /* Foreground — sama dengan about */
    --fg:             #0a0a0a;
    --fg-mid:         rgba(10,10,10,0.55);
    --fg-low:         rgba(10,10,10,0.35);

    /* Surface */
    --surface:        #f5f2ea;

    /* Slate tones — untuk CTA button */
    --slate-100:      #7a94a6;
    --slate-200:      #536878;
    --slate-300:      #3d5060;

    --nav-h: 68px;
    --blur:  24px;
  }

  *, *::before, *::after { box-sizing: border-box; }

  html, body {
    margin: 0; padding: 0;
    background-color: var(--bg-base);
    color: var(--fg);
    font-family: var(--font-sans);
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
  }

  /* subtle paper texture */
  body::before {
    content: '';
    position: fixed; inset: 0; z-index: 0; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
    background-size: 180px 180px;
    opacity: 0.5;
  }

  /* ambient slate glows */
  .ep-glow-1 {
    position: fixed; pointer-events: none; z-index: 0;
    top: -200px; left: 50%; transform: translateX(-50%);
    width: 900px; height: 500px; border-radius: 50%;
    background: radial-gradient(ellipse, rgba(83,104,120,0.08) 0%, transparent 65%);
    filter: blur(40px);
  }
  .ep-glow-2 {
    position: fixed; pointer-events: none; z-index: 0;
    bottom: -100px; right: -200px;
    width: 600px; height: 600px; border-radius: 50%;
    background: radial-gradient(ellipse, rgba(83,104,120,0.05) 0%, transparent 65%);
    filter: blur(60px);
  }

  /* ── PAGE LAYOUT ─────────────────────────────────────────── */
  .ep-page {
    position: relative; z-index: 1;
    min-height: 100vh;
    padding: calc(var(--nav-h) + 40px) 24px 60px;
    display: flex; flex-direction: column; align-items: center;
  }

  /* ── BREADCRUMB ──────────────────────────────────────────── */
  .ep-breadcrumb {
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 36px; align-self: flex-start;
    max-width: 660px; width: 100%;
  }
  .ep-breadcrumb a {
    font-size: 11px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase;
    color: var(--fg-low); text-decoration: none;
    transition: color 0.2s;
  }
  .ep-breadcrumb a:hover { color: var(--accent); }
  .ep-breadcrumb span { font-size: 11px; color: var(--fg-low); }
  .ep-breadcrumb .ep-bc-cur {
    font-size: 11px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase;
    color: var(--accent);
  }

  /* ── MAIN CARD ───────────────────────────────────────────── */
  .ep-card {
    width: 100%; max-width: 660px;
    background: var(--bg-glass);
    backdrop-filter: blur(var(--blur)) saturate(180%) brightness(1.04);
    -webkit-backdrop-filter: blur(var(--blur)) saturate(180%) brightness(1.04);
    border: 1px solid var(--border);
    border-radius: 6px;
    position: relative; overflow: hidden;
    box-shadow:
      0 1px 0 rgba(255,255,255,0.9),
      0 4px 24px rgba(83,104,120,0.08);
    transition: border-color 0.3s, box-shadow 0.3s;
  }

  .ep-card:hover {
    border-color: var(--border-acc);
    box-shadow:
      0 1px 0 rgba(255,255,255,0.95),
      0 20px 48px rgba(83,104,120,0.12),
      0 4px 16px rgba(83,104,120,0.06);
  }

  /* top slate glint */
  .ep-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg,
      transparent 0%,
      rgba(83,104,120,0.30) 25%,
      rgba(83,104,120,0.60) 50%,
      rgba(83,104,120,0.30) 75%,
      transparent 100%);
    pointer-events: none;
  }

  /* ── CARD HEADER ─────────────────────────────────────────── */
  .ep-card-header {
    padding: 32px 36px 28px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: flex-start; justify-content: space-between; gap: 16px;
    background: linear-gradient(180deg, rgba(83,104,120,0.05) 0%, transparent 100%);
  }
  .ep-header-tag {
    font-size: 9px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
    color: var(--accent); padding: 4px 10px;
    border: 1px solid var(--accent-border); border-radius: 2px;
    background: var(--accent-glow); display: inline-block; margin-bottom: 14px;
  }
  .ep-header-title {
    font-family: var(--font-serif);
    font-size: 36px; font-weight: 400; line-height: 1; letter-spacing: -0.02em;
    color: var(--fg); margin: 0;
  }
  .ep-header-title em { color: var(--accent); font-style: italic; }
  .ep-header-sub {
    font-size: 13px; color: var(--fg-mid); margin-top: 8px; line-height: 1.6;
  }

  /* current user badge */
  .ep-user-badge {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 16px;
    background: rgba(255,253,247,0.5);
    border: 1px solid var(--border); flex-shrink: 0;
    border-radius: 4px;
  }
  .ep-user-avatar {
    width: 44px; height: 44px; border-radius: 3px;
    border: 1.5px solid var(--accent-border);
    background: var(--accent-glow);
    overflow: hidden; display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .ep-user-avatar img { width: 100%; height: 100%; object-fit: cover; }
  .ep-user-avatar i   { font-size: 18px; color: var(--accent); }
  .ep-user-name  { font-size: 13px; font-weight: 700; color: var(--fg); line-height: 1.2; }
  .ep-user-meta  { font-size: 10px; color: var(--fg-low); margin-top: 2px; letter-spacing: 0.06em; }

  /* ── CARD BODY ───────────────────────────────────────────── */
  .ep-card-body { padding: 32px 36px; }

  /* ── SECTION LABEL ───────────────────────────────────────── */
  .ep-section-label {
    font-size: 9px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase;
    color: var(--fg-low); padding-bottom: 12px;
    border-bottom: 1px solid var(--border); margin-bottom: 20px;
    display: flex; align-items: center; gap: 10px;
  }
  .ep-section-label::after {
    content: ''; flex: 1; height: 1px; background: var(--border);
  }

  /* ── PHOTO UPLOAD ────────────────────────────────────────── */
  .ep-photo-zone {
    display: flex; gap: 20px; align-items: flex-start; margin-bottom: 28px;
  }

  .ep-current-avatar {
    width: 80px; height: 80px; flex-shrink: 0; border-radius: 4px;
    border: 1.5px solid var(--accent-border);
    background: var(--accent-glow);
    overflow: hidden; display: flex; align-items: center; justify-content: center;
    position: relative;
  }
  .ep-current-avatar img { width: 100%; height: 100%; object-fit: cover; }
  .ep-current-avatar i   { font-size: 28px; color: var(--accent); }
  .ep-avatar-overlay {
    position: absolute; inset: 0;
    background: rgba(10,10,10,0.40);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity 0.2s; cursor: pointer;
  }
  .ep-current-avatar:hover .ep-avatar-overlay { opacity: 1; }
  .ep-avatar-overlay i { font-size: 18px; color: #fff; }

  .ep-photo-info { flex: 1; }
  .ep-photo-title { font-size: 13px; font-weight: 700; color: var(--fg); margin-bottom: 4px; }
  .ep-photo-hint  { font-size: 11px; color: var(--fg-low); line-height: 1.6; margin-bottom: 12px; }

  .ep-file-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 18px; border-radius: 4px; cursor: pointer;
    font-size: 11.5px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
    color: var(--fg-mid);
    border: 1px solid var(--border);
    background: rgba(255,253,247,0.5);
    transition: all 0.2s;
  }
  .ep-file-btn:hover {
    border-color: var(--accent-border); color: var(--accent);
    background: var(--accent-glow);
  }
  .ep-file-btn i { font-size: 11px; }
  #fotoInput { display: none; }

  /* cropper container */
  .ep-cropper-wrap {
    display: none; margin-top: 20px;
    border: 1px solid var(--border);
    background: rgba(83,104,120,0.05);
    overflow: hidden; max-height: 320px; border-radius: 4px;
  }
  .ep-cropper-wrap.active { display: block; }
  .ep-cropper-wrap img { max-width: 100%; display: block; }

  /* ── FORM FIELDS ─────────────────────────────────────────── */
  .ep-field { margin-bottom: 20px; }
  .ep-label {
    display: block; font-size: 10px; font-weight: 800; letter-spacing: 0.18em;
    text-transform: uppercase; color: var(--fg-mid); margin-bottom: 8px;
  }

  .ep-select {
    width: 100%; padding: 12px 16px;
    background: rgba(255,253,247,0.50);
    border: 1px solid var(--border);
    color: var(--fg); font-family: var(--font-sans); font-size: 14px;
    border-radius: 4px; outline: none; cursor: pointer;
    appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='rgba(10,10,10,0.35)' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 14px center;
    padding-right: 36px;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
  }
  .ep-select:focus {
    border-color: var(--accent-border);
    background-color: var(--accent-glow);
    box-shadow: 0 0 0 3px rgba(83,104,120,0.08);
  }
  .ep-select option { background: #f5f2ea; color: var(--fg); }

  /* ── ALERT ───────────────────────────────────────────────── */
  .ep-alert {
    padding: 14px 18px; margin-bottom: 24px;
    border-radius: 4px; display: flex; align-items: center; gap: 12px;
    font-size: 13px; font-weight: 600;
  }
  .ep-alert-success {
    background: rgba(72,140,96,0.08);
    border: 1px solid rgba(72,140,96,0.25);
    color: #2d6b46;
  }
  .ep-alert-success i { font-size: 14px; }

  /* ── CARD FOOTER ─────────────────────────────────────────── */
  .ep-card-footer {
    padding: 24px 36px;
    border-top: 1px solid var(--border);
    display: flex; gap: 12px; align-items: center;
    background: linear-gradient(0deg, rgba(83,104,120,0.04) 0%, transparent 100%);
  }

  /* slate submit button */
  .ep-btn-amber {
    flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 10px;
    background: linear-gradient(135deg, var(--slate-100) 0%, var(--slate-200) 60%, var(--slate-300) 100%);
    color: #fff;
    padding: 14px 28px; border-radius: 4px;
    border: 1px solid rgba(40,60,80,0.20);
    cursor: pointer;
    font-family: var(--font-sans); font-weight: 800;
    font-size: 12px; letter-spacing: 0.08em; text-transform: uppercase;
    box-shadow: 0 2px 10px rgba(83,104,120,0.20), inset 0 1px 0 rgba(255,255,255,0.18);
    transition: transform 0.22s, box-shadow 0.22s, filter 0.22s;
  }
  .ep-btn-amber:hover {
    transform: translateY(-2px);
    filter: brightness(1.08);
    box-shadow: 0 8px 28px rgba(83,104,120,0.30), inset 0 1px 0 rgba(255,255,255,0.22);
  }
  .ep-btn-amber i { font-size: 11px; }

  /* ghost back */
  .ep-btn-ghost {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 13px 22px; border-radius: 4px;
    font-family: var(--font-sans); font-size: 12px; font-weight: 700;
    letter-spacing: 0.08em; text-transform: uppercase;
    color: var(--fg-mid); text-decoration: none;
    border: 1px solid var(--border);
    background: rgba(255,253,247,0.5);
    transition: all 0.2s;
  }
  .ep-btn-ghost:hover {
    border-color: var(--accent-border); color: var(--accent);
    background: var(--accent-glow); text-decoration: none;
  }
  .ep-btn-ghost i { font-size: 10px; }

  /* ── INFO STRIP ──────────────────────────────────────────── */
  .ep-info-strip {
    width: 100%; max-width: 660px;
    margin-top: 16px;
    display: flex; gap: 12px;
  }
  .ep-info-item {
    flex: 1; padding: 14px 18px;
    background: var(--bg-glass);
    border: 1px solid var(--border);
    border-radius: 4px;
    display: flex; align-items: center; gap: 10px;
    box-shadow: 0 1px 0 rgba(255,255,255,0.8);
  }
  .ep-info-item i { font-size: 11px; color: var(--accent); flex-shrink: 0; }
  .ep-info-item span { font-size: 11px; color: var(--fg-mid); line-height: 1.5; font-weight: 500; }

  /* ── RESPONSIVE ───────────────────────────────────────────── */
  @media (max-width: 640px) {
    .ep-card-header { flex-direction: column; padding: 24px; }
    .ep-card-body   { padding: 24px; }
    .ep-card-footer { flex-direction: column; padding: 20px 24px; }
    .ep-btn-ghost   { width: 100%; justify-content: center; }
    .ep-info-strip  { flex-direction: column; }
    .ep-photo-zone  { flex-direction: column; }
    .ep-header-title { font-size: 28px; }
  }
  </style>
</head>

<body>
<?php include 'includes/navbar.php'; ?>

<!-- ambient glows -->
<div class="ep-glow-1" aria-hidden="true"></div>
<div class="ep-glow-2" aria-hidden="true"></div>

<div class="ep-page">

  <!-- breadcrumb -->
  <nav class="ep-breadcrumb" aria-label="Breadcrumb">
    <a href="index.php"><i class="fas fa-home"></i> Beranda</a>
    <span>/</span>
    <span class="ep-bc-cur">Edit Profil</span>
  </nav>

  <!-- main card -->
  <div class="ep-card">

    <!-- card header -->
    <div class="ep-card-header">
      <div>
        <span class="ep-header-tag">Stemba Parking · Profil Siswa</span>
        <h1 class="ep-header-title">Edit <em>Profil</em></h1>
        <p class="ep-header-sub">Perbarui foto, kelas, dan jurusan kamu di sini.</p>
      </div>

      <div class="ep-user-badge">
        <div class="ep-user-avatar">
          <?php if (!empty($user['foto'])): ?>
            <img src="assets/img/profile/<?= htmlspecialchars($user['foto']) ?>" alt="">
          <?php else: ?>
            <i class="fas fa-user"></i>
          <?php endif; ?>
        </div>
        <div>
          <div class="ep-user-name"><?= htmlspecialchars($_SESSION['username']) ?></div>
          <div class="ep-user-meta">
            <?= htmlspecialchars($user['kelas'] ?? '—') ?> &nbsp;·&nbsp;
            <?= htmlspecialchars($user['jurusan'] ?? '—') ?>
          </div>
        </div>
      </div>
    </div>

    <!-- card body -->
    <div class="ep-card-body">

      <?php if ($success): ?>
        <div class="ep-alert ep-alert-success">
          <i class="fas fa-circle-check"></i>
          <?= htmlspecialchars($success) ?>
        </div>
      <?php endif; ?>

      <form method="POST" id="profileForm">

        <!-- ── FOTO ───────────────────────────────────────── -->
        <div class="ep-section-label">Foto Profil</div>

        <div class="ep-photo-zone">
          <div class="ep-current-avatar" onclick="document.getElementById('fotoInput').click()">
            <?php if (!empty($user['foto'])): ?>
              <img src="assets/img/profile/<?= htmlspecialchars($user['foto']) ?>" alt="Foto profil" id="avatarDisplay">
            <?php else: ?>
              <i class="fas fa-user" id="avatarIcon"></i>
            <?php endif; ?>
            <div class="ep-avatar-overlay"><i class="fas fa-camera"></i></div>
          </div>

          <div class="ep-photo-info">
            <div class="ep-photo-title">Unggah Foto Baru</div>
            <p class="ep-photo-hint">Format JPG, PNG. Maks 2MB.<br>Foto akan dipotong jadi persegi 300×300px.</p>
            <label class="ep-file-btn" for="fotoInput">
              <i class="fas fa-upload"></i> Pilih Foto
            </label>
            <input type="file" id="fotoInput" accept="image/*">
            <input type="hidden" name="foto_cropped" id="fotoCropped">
          </div>
        </div>

        <!-- cropper -->
        <div class="ep-cropper-wrap" id="cropperWrap">
          <img id="cropperImg" src="" alt="Crop preview">
        </div>

        <!-- ── KELAS & JURUSAN ────────────────────────────── -->
        <div class="ep-section-label" style="margin-top: 28px;">Data Akademik</div>

        <div class="ep-field">
          <label class="ep-label" for="kelasSelect">Kelas</label>
          <select name="kelas" id="kelasSelect" class="ep-select" required>
            <option value="">— Pilih Kelas —</option>
            <?php foreach (['X','XI','XII'] as $k): ?>
              <option value="<?= $k ?>" <?= ($user['kelas'] === $k) ? 'selected' : '' ?>>
                Kelas <?= $k ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="ep-field">
          <label class="ep-label" for="jurusanSelect">Jurusan</label>
          <select name="jurusan" id="jurusanSelect" class="ep-select" required>
            <option value="">— Pilih Jurusan —</option>
            <?php foreach ($jurusanList as $key => $val): ?>
              <option value="<?= $key ?>" <?= ($user['jurusan'] === $key) ? 'selected' : '' ?>>
                <?= $val ?> (<?= $key ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

      </form>
    </div><!-- /card-body -->

    <!-- card footer -->
    <div class="ep-card-footer">
      <a href="index.php" class="ep-btn-ghost">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
      <button type="submit" form="profileForm" class="ep-btn-amber">
        <i class="fas fa-save"></i> Simpan Perubahan
      </button>
    </div>

  </div><!-- /ep-card -->

  <!-- info strip -->
  <div class="ep-info-strip">
    <div class="ep-info-item">
      <i class="fas fa-shield-halved"></i>
      <span>Data kamu tersimpan aman di database sekolah dan tidak dibagikan ke pihak luar.</span>
    </div>
    <div class="ep-info-item">
      <i class="fas fa-circle-info"></i>
      <span>Perubahan profil berlaku langsung setelah disimpan tanpa perlu login ulang.</span>
    </div>
  </div>

</div><!-- /ep-page -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
(function () {
  'use strict';

  var input       = document.getElementById('fotoInput');
  var cropperWrap = document.getElementById('cropperWrap');
  var cropperImg  = document.getElementById('cropperImg');
  var croppedInput= document.getElementById('fotoCropped');
  var form        = document.getElementById('profileForm');
  var cropper;

  input.addEventListener('change', function (e) {
    var file = e.target.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (ev) {
      cropperImg.src = ev.target.result;
      cropperWrap.classList.add('active');

      if (cropper) cropper.destroy();
      cropper = new Cropper(cropperImg, {
        aspectRatio: 1,
        viewMode: 1,
        autoCropArea: 1,
        background: false,
      });
    };
    reader.readAsDataURL(file);
  });

  form.addEventListener('submit', function () {
    if (cropper) {
      croppedInput.value = cropper
        .getCroppedCanvas({ width: 300, height: 300 })
        .toDataURL('image/png');
    }
  });

})();
</script>
</body>
</html>