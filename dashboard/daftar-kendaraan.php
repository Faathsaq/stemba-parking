<?php
// dashboard/daftar-kendaraan.php
// FIX: Custom dark dropdown kelas + jumlah kelas dikoreksi + umur min 17
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
if ($_SESSION['role'] === 'admin') { header('Location: ../admin/index.php'); exit; }

$uid     = $_SESSION['user_id'];
$error   = '';
$success = false;

// ── DATA KELAS (BENAR) ────────────────────────────────────────
// KGSP:2, KJIJ:2, TKR:3, TFLM:2, TEK:2, TME:5, SIJA:3
$jurusan_count = [
  'KGSP' => 2, 'KJIJ' => 2, 'TKR' => 3,
  'TFLM' => 2, 'TEK'  => 2, 'TME' => 5, 'SIJA' => 3,
];
$kelas_list = [];
for ($tingkat = 10; $tingkat <= 12; $tingkat++) {
  foreach ($jurusan_count as $jurusan => $jml) {
    for ($k = 1; $k <= $jml; $k++) {
      $kelas_list[] = "$tingkat $jurusan $k";
    }
  }
}

// ── HANDLE SUBMIT ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = [
    'nama_lengkap'   => trim($_POST['nama_lengkap']   ?? ''),
    'nis'            => trim($_POST['nis']             ?? ''),
    'umur'           => (int)($_POST['umur']           ?? 0),
    'kelas'          => trim($_POST['kelas']           ?? ''),
    'email'          => trim($_POST['email']           ?? ''),
    'no_telepon'     => trim($_POST['no_telepon']      ?? ''),
    'nama_kendaraan' => trim($_POST['nama_kendaraan']  ?? ''),
    'nomor_tnkb'     => strtoupper(trim($_POST['nomor_tnkb'] ?? '')),
    'jenis'          => trim($_POST['jenis']           ?? 'motor'),
  ];

  $required = array_filter($data, fn($v) => $v === '' || $v === 0);
  if (!empty($required)) {
    $error = 'Semua field wajib diisi dengan benar.';
  } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $error = 'Format email tidak valid.';
  } elseif ($data['umur'] < 17 || $data['umur'] > 25) {
    $error = 'Umur harus antara 17–25 tahun.';
  } elseif (!in_array($data['kelas'], $kelas_list)) {
    $error = 'Kelas tidak valid.';
  } else {
    $chk = $pdo->prepare("SELECT id FROM pendaftaran WHERE nomor_tnkb = ?");
    $chk->execute([$data['nomor_tnkb']]);
    if ($chk->fetch()) $error = 'Nomor TNKB sudah terdaftar di sistem.';
  }

  $upload_dir = '../uploads/kendaraan/';
  if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

  $foto = ['foto_kendaraan' => null, 'foto_sim' => null, 'foto_kartu' => null];
  $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
  $max_size = 2 * 1024 * 1024;

  if (empty($error)) {
    foreach (['foto_kendaraan', 'foto_sim', 'foto_kartu'] as $key) {
      if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES[$key];
        if (!in_array($file['type'], $allowed_types)) { $error = 'File harus berformat JPG, PNG, atau WebP.'; break; }
        if ($file['size'] > $max_size) { $error = 'Ukuran file maksimal 2MB.'; break; }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $uid . '_' . $key . '_' . time() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $upload_dir . $filename);
        $foto[$key] = 'uploads/kendaraan/' . $filename;
      } else { $error = 'Semua foto wajib diunggah.'; break; }
    }
  }

  if (empty($error)) {
    try {
      $stmt = $pdo->prepare("INSERT INTO pendaftaran (user_id, nama_lengkap, nis, umur, kelas, email, no_telepon, nama_kendaraan, nomor_tnkb, jenis, foto_kendaraan, foto_sim, foto_kartu) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
      $stmt->execute([$uid, $data['nama_lengkap'], $data['nis'], $data['umur'], $data['kelas'], $data['email'], $data['no_telepon'], $data['nama_kendaraan'], $data['nomor_tnkb'], $data['jenis'], $foto['foto_kendaraan'], $foto['foto_sim'], $foto['foto_kartu']]);
      $success = true;
    } catch (PDOException $e) {
      $error = 'Gagal menyimpan data. Coba lagi.';
    }
  }
}

$sel_kelas = $_POST['kelas'] ?? '';
$kelas_json = json_encode($kelas_list);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Kendaraan — Stemba Parking</title>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --font-serif: 'Instrument Serif', Georgia, serif;
    --font-sans:  'Outfit', sans-serif;
    --bg:         #0d0d0d;
    --bg2:        #111111;
    --bg3:        #161616;
    --accent:     #f59e0b;
    --accent-dim: rgba(245,158,11,0.18);
    --accent-glow:rgba(245,158,11,0.07);
    --border:     rgba(255,255,255,0.07);
    --border-acc: rgba(245,158,11,0.28);
    --fg:         #ffffff;
    --fg-mid:     rgba(255,255,255,0.5);
    --fg-low:     rgba(255,255,255,0.25);
    --green:      #86efac;
    --green-bg:   rgba(134,239,172,0.08);
    --green-bdr:  rgba(134,239,172,0.22);
    --red:        #f87171;
    --red-bg:     rgba(248,113,113,0.08);
    --red-bdr:    rgba(248,113,113,0.25);
  }
  html, body { min-height: 100vh; background: var(--bg); font-family: var(--font-sans); color: var(--fg); }

  /* ── TOPNAV ── */
  .nav { position: sticky; top: 0; z-index: 200; background: rgba(13,13,13,0.94); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); }
  .nav-inner { display: flex; align-items: center; justify-content: space-between; padding: 0 40px; height: 60px; max-width: 960px; margin: 0 auto; }
  .nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
  .nav-brand-icon { width: 32px; height: 32px; border: 1px solid var(--border-acc); background: var(--accent-glow); display: flex; align-items: center; justify-content: center; }
  .nav-brand-icon i { font-size: 14px; color: var(--accent); }
  .nav-brand-name { font-family: var(--font-serif); font-size: 17px; color: var(--fg); letter-spacing: -0.02em; }
  .nav-brand-sub { font-size: 9px; font-weight: 800; letter-spacing: 0.16em; text-transform: uppercase; color: var(--fg-low); display: block; margin-top: 1px; }
  .nav-back { display: inline-flex; align-items: center; gap: 7px; font-size: 11.5px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--fg-low); text-decoration: none; border: 1px solid var(--border); padding: 7px 14px; transition: color 0.2s, border-color 0.2s; }
  .nav-back:hover { color: var(--accent); border-color: var(--border-acc); }
  .nav-back i { font-size: 10px; }

  /* ── PAGE ── */
  .page { max-width: 760px; margin: 0 auto; padding: 52px 24px 100px; }
  .page-eyebrow { font-size: 9.5px; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase; color: var(--accent); opacity: 0.7; margin-bottom: 12px; display: block; }
  .page-title { font-family: var(--font-serif); font-size: clamp(28px, 4vw, 46px); font-weight: 400; color: var(--fg); letter-spacing: -0.02em; line-height: 1; margin-bottom: 8px; }
  .page-title em { font-style: italic; color: var(--accent); }
  .page-sub { font-size: 13px; color: var(--fg-low); margin-bottom: 48px; }

  /* ── STEP INDICATOR ── */
  .step-indicator { display: flex; align-items: center; margin-bottom: 52px; border: 1px solid var(--border); padding: 20px 28px; background: var(--bg2); position: relative; overflow: hidden; }
  .step-indicator::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, var(--accent), #fde68a); }
  .step-item { display: flex; align-items: center; gap: 10px; flex: 1; cursor: default; position: relative; }
  .step-item:not(:last-child)::after { content: ''; position: absolute; right: 0; top: 50%; transform: translateY(-50%); width: 1px; height: 28px; background: var(--border); }
  .step-num { width: 32px; height: 32px; flex-shrink: 0; border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; color: var(--fg-low); transition: border-color 0.3s, background 0.3s, color 0.3s; }
  .step-label { font-size: 8.5px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase; color: var(--fg-low); display: block; transition: color 0.3s; }
  .step-name { font-size: 12px; font-weight: 700; color: var(--fg-low); transition: color 0.3s; }
  .step-item.active .step-num { border-color: var(--accent); background: var(--accent-glow); color: var(--accent); }
  .step-item.active .step-label { color: var(--accent); opacity: 0.7; }
  .step-item.active .step-name { color: var(--fg); }
  .step-item.done .step-num { border-color: var(--green-bdr); background: var(--green-bg); color: var(--green); }
  .step-item.done .step-name { color: var(--fg-mid); }

  /* ── FORM CARD ── */
  .form-card { background: var(--bg2); border: 1px solid var(--border); padding: 40px; position: relative; overflow: visible; }
  .form-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--accent), transparent); }

  /* ── STEP PANELS ── */
  .step-panel { display: none; }
  .step-panel.active { display: block; animation: panelIn 0.38s cubic-bezier(0.23,1,0.32,1) both; }
  @keyframes panelIn { from{opacity:0;transform:translateX(24px)} to{opacity:1;transform:translateX(0)} }
  .step-panel.reverse.active { animation-name: panelInRev; }
  @keyframes panelInRev { from{opacity:0;transform:translateX(-24px)} to{opacity:1;transform:translateX(0)} }
  .step-panel-title { font-family: var(--font-serif); font-size: 22px; font-weight: 400; color: var(--fg); letter-spacing: -0.02em; margin-bottom: 6px; }
  .step-panel-title em { font-style: italic; color: var(--accent); }
  .step-panel-sub { font-size: 12.5px; color: var(--fg-low); margin-bottom: 32px; }

  /* ── FORM FIELDS ── */
  .field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .field-grid .field-full { grid-column: 1 / -1; }
  .field { margin-bottom: 0; }
  .field-label { font-size: 9px; font-weight: 800; letter-spacing: 0.2em; text-transform: uppercase; color: var(--fg-low); display: flex; align-items: center; gap: 6px; margin-bottom: 8px; }
  .field-label .req { color: var(--accent); font-size: 11px; }
  .field-label .hint { color: var(--fg-low); font-weight: 400; font-size: 8px; opacity: 0.7; text-transform: none; letter-spacing: 0; margin-left: auto; }
  .field-input-wrap { position: relative; }
  .field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 12px; color: var(--fg-low); pointer-events: none; transition: color 0.2s; z-index: 1; }
  .field-input {
    width: 100%; padding: 12px 14px 12px 38px;
    background: rgba(255,255,255,0.03); border: 1px solid var(--border);
    color: var(--fg); font-family: var(--font-sans); font-size: 13.5px;
    outline: none; border-radius: 0; appearance: none;
    transition: border-color 0.2s, background 0.2s;
  }
  .field-input::placeholder { color: var(--fg-low); }
  .field-input:focus { border-color: var(--border-acc); background: rgba(245,158,11,0.03); }
  .field-input:focus + .field-icon,
  .field-input-wrap:focus-within .field-icon { color: var(--accent); }
  .field-input.valid { border-color: var(--green-bdr); }
  .field-input.invalid { border-color: var(--red-bdr); }
  .field-msg { font-size: 10.5px; margin-top: 5px; display: none; align-items: center; gap: 5px; }
  .field-msg.show { display: flex; }
  .field-msg.ok { color: var(--green); }
  .field-msg.err { color: var(--red); }
  .field-msg i { font-size: 9px; }

  /* ── HIDDEN REAL SELECT (for form submit) ── */
  .real-select { display: none; }

  /* ── CUSTOM DROPDOWN ── */
  .custom-select-wrap { position: relative; }
  .custom-select-trigger {
    width: 100%; padding: 12px 38px 12px 38px;
    background: rgba(255,255,255,0.03); border: 1px solid var(--border);
    color: var(--fg); font-family: var(--font-sans); font-size: 13.5px;
    cursor: pointer; display: flex; align-items: center; justify-content: space-between;
    user-select: none; transition: border-color 0.2s, background 0.2s;
    position: relative;
  }
  .custom-select-trigger:hover { border-color: rgba(255,255,255,0.14); background: rgba(255,255,255,0.05); }
  .custom-select-trigger.open { border-color: var(--border-acc); background: rgba(245,158,11,0.03); }
  .custom-select-trigger.placeholder { color: var(--fg-low); }
  .custom-select-trigger.valid { border-color: var(--green-bdr); }
  .custom-select-trigger.invalid { border-color: var(--red-bdr); }

  .cs-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 12px; color: var(--fg-low); pointer-events: none; }
  .custom-select-trigger.open .cs-icon { color: var(--accent); }
  .cs-arrow { font-size: 10px; color: var(--fg-low); transition: transform 0.2s; flex-shrink: 0; }
  .custom-select-trigger.open .cs-arrow { transform: rotate(180deg); color: var(--accent); }

  /* dropdown panel */
  .custom-select-panel {
    position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: #1a1a1a; border: 1px solid rgba(255,255,255,0.12);
    z-index: 999; display: none; flex-direction: column;
    max-height: 280px; overflow: hidden;
    box-shadow: 0 16px 48px rgba(0,0,0,0.7);
  }
  .custom-select-panel.open { display: flex; animation: ddOpen 0.18s ease both; }
  @keyframes ddOpen { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }

  /* search inside dropdown */
  .cs-search-wrap { padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.07); flex-shrink: 0; position: relative; }
  .cs-search {
    width: 100%; padding: 8px 10px 8px 30px;
    background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
    color: var(--fg); font-family: var(--font-sans); font-size: 12px;
    outline: none; border-radius: 0;
  }
  .cs-search::placeholder { color: var(--fg-low); }
  .cs-search:focus { border-color: var(--border-acc); }
  .cs-search-icon { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); font-size: 11px; color: var(--fg-low); pointer-events: none; }

  /* options list */
  .cs-options { overflow-y: auto; flex: 1; }
  .cs-options::-webkit-scrollbar { width: 4px; }
  .cs-options::-webkit-scrollbar-track { background: transparent; }
  .cs-options::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 2px; }

  /* group headers */
  .cs-group-header {
    padding: 8px 14px 4px;
    font-size: 9px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase;
    color: var(--accent); opacity: 0.6; border-top: 1px solid rgba(255,255,255,0.05);
  }
  .cs-group-header:first-child { border-top: none; }

  .cs-option {
    padding: 10px 14px 10px 28px;
    font-size: 13px; color: rgba(255,255,255,0.65);
    cursor: pointer; display: flex; align-items: center; justify-content: space-between;
    transition: background 0.14s, color 0.14s;
    font-family: var(--font-sans);
  }
  .cs-option:hover { background: rgba(255,255,255,0.07); color: var(--fg); }
  .cs-option.selected { background: var(--accent-glow); color: var(--accent); }
  .cs-option.selected::after { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; font-size: 10px; }
  .cs-option.hidden { display: none; }

  /* ── UPLOAD AREA ── */
  .upload-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
  .upload-area { border: 1px dashed var(--border-acc); background: var(--accent-glow); padding: 24px 12px; text-align: center; cursor: pointer; position: relative; transition: border-color 0.2s, background 0.2s; min-height: 130px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
  .upload-area:hover, .upload-area.dragover { border-color: var(--accent); background: var(--accent-dim); }
  .upload-area input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
  .upload-icon { font-size: 22px; color: var(--accent); opacity: 0.6; margin-bottom: 10px; }
  .upload-label { font-size: 10px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase; color: var(--fg-low); display: block; margin-bottom: 4px; }
  .upload-hint { font-size: 10px; color: var(--fg-low); }
  .upload-preview { position: absolute; inset: 0; background: var(--bg2); display: none; align-items: center; justify-content: center; overflow: hidden; }
  .upload-preview.show { display: flex; }
  .upload-preview img { width: 100%; height: 100%; object-fit: cover; }
  .upload-preview-del { position: absolute; top: 6px; right: 6px; width: 22px; height: 22px; background: rgba(0,0,0,0.75); border: 1px solid rgba(255,255,255,0.1); cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--red); font-size: 9px; z-index: 2; }

  /* ── REVIEW ── */
  .review-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; border: 1px solid var(--border); }
  .review-item { padding: 14px 18px; border-bottom: 1px solid var(--border); border-right: 1px solid var(--border); }
  .review-item:nth-child(even) { border-right: none; }
  .review-item:nth-last-child(-n+2) { border-bottom: none; }
  .review-item-key { font-size: 8.5px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase; color: var(--fg-low); margin-bottom: 4px; display: block; }
  .review-item-val { font-size: 13px; font-weight: 600; color: var(--fg); }

  /* ── SUCCESS ── */
  .success-state { text-align: center; padding: 48px 24px; }
  .success-icon { width: 72px; height: 72px; margin: 0 auto 24px; border: 1px solid var(--green-bdr); background: var(--green-bg); display: flex; align-items: center; justify-content: center; }
  .success-icon i { font-size: 28px; color: var(--green); }
  .success-title { font-family: var(--font-serif); font-size: 32px; font-weight: 400; color: var(--fg); margin-bottom: 10px; letter-spacing: -0.02em; }
  .success-title em { font-style: italic; color: var(--green); }
  .success-desc { font-size: 14px; color: var(--fg-low); line-height: 1.7; margin-bottom: 32px; }

  /* ── ERROR ── */
  .alert-err { display: flex; align-items: flex-start; gap: 12px; background: var(--red-bg); border: 1px solid var(--red-bdr); padding: 14px 18px; margin-bottom: 24px; font-size: 13px; color: var(--red); line-height: 1.5; }
  .alert-err i { font-size: 13px; flex-shrink: 0; margin-top: 1px; }

  /* ── BUTTONS ── */
  .form-nav { display: flex; justify-content: space-between; align-items: center; margin-top: 36px; padding-top: 28px; border-top: 1px solid var(--border); }
  .btn-prev, .btn-next, .btn-submit { display: inline-flex; align-items: center; gap: 9px; padding: 13px 28px; font-family: var(--font-sans); font-weight: 800; font-size: 12px; letter-spacing: 0.06em; text-transform: uppercase; border: none; cursor: pointer; transition: background 0.2s, transform 0.2s, box-shadow 0.2s; }
  .btn-prev { background: transparent; color: var(--fg-low); border: 1px solid var(--border); }
  .btn-prev:hover { border-color: var(--border-acc); color: var(--accent); background: var(--accent-glow); }
  .btn-next, .btn-submit { background: var(--accent); color: #0d0d0d; margin-left: auto; }
  .btn-next:hover, .btn-submit:hover { background: #fbbf24; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(245,158,11,0.28); }
  .btn-dashboard { display: inline-flex; align-items: center; gap: 9px; background: var(--accent); color: #0d0d0d; padding: 13px 28px; font-family: var(--font-sans); font-weight: 800; font-size: 12px; letter-spacing: 0.06em; text-transform: uppercase; text-decoration: none; transition: background 0.2s; }
  .btn-dashboard:hover { background: #fbbf24; color: #0d0d0d; text-decoration: none; }
  .step-counter { font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--fg-low); }
  .step-counter span { color: var(--accent); }

  /* ── RESPONSIVE ── */
  @media (max-width: 720px) {
    .field-grid { grid-template-columns: 1fr; }
    .upload-grid { grid-template-columns: 1fr; }
    .review-grid { grid-template-columns: 1fr; }
    .review-item { border-right: none; }
    .form-card { padding: 24px 18px; }
    .nav-inner { padding: 0 16px; }
    .page { padding: 36px 16px 80px; }
    .step-indicator { padding: 16px; }
  }
  @media (prefers-reduced-motion: reduce) {
    .step-panel.active { animation: none !important; }
    .btn-next:hover, .btn-submit:hover { transform: none !important; }
  }
  </style>
</head>
<body>

<nav class="nav">
  <div class="nav-inner">
    <a href="dashboard-user.php" class="nav-brand">
      <div class="nav-brand-icon"><i class="fa-solid fa-square-parking"></i></div>
      <div>
        <span class="nav-brand-name">Stemba Parking</span>
        <span class="nav-brand-sub">SMKN 7 Semarang</span>
      </div>
    </a>
    <a href="dashboard-user.php" class="nav-back">
      <i class="fa-solid fa-arrow-left"></i> Dashboard
    </a>
  </div>
</nav>

<main class="page">

  <span class="page-eyebrow">Pendaftaran Kendaraan</span>
  <h1 class="page-title">Daftarkan <em>Kendaraanmu</em></h1>
  <p class="page-sub">Isi data dengan benar dan lengkap. Proses verifikasi 1–3 hari kerja.</p>

  <?php if ($success): ?>
  <div class="form-card">
    <div class="success-state">
      <div class="success-icon"><i class="fa-solid fa-circle-check"></i></div>
      <h2 class="success-title">Pendaftaran <em>Berhasil!</em></h2>
      <p class="success-desc">
        Kendaraanmu berhasil didaftarkan dan sedang menunggu verifikasi admin.<br>
        Pantau status di dashboard — biasanya selesai dalam <strong>1–3 hari kerja.</strong>
      </p>
      <a href="dashboard-user.php" class="btn-dashboard">
        <i class="fa-solid fa-gauge-high"></i> Kembali ke Dashboard
      </a>
    </div>
  </div>

  <?php else: ?>

  <?php if ($error): ?>
  <div class="alert-err">
    <i class="fa-solid fa-circle-exclamation"></i>
    <?= htmlspecialchars($error) ?>
  </div>
  <?php endif; ?>

  <!-- STEP INDICATOR -->
  <div class="step-indicator">
    <div class="step-item active" data-step="1">
      <div class="step-num" id="snum-1">1</div>
      <div class="step-info">
        <span class="step-label">Langkah 01</span>
        <span class="step-name">Data Diri</span>
      </div>
    </div>
    <div class="step-item" data-step="2">
      <div class="step-num" id="snum-2">2</div>
      <div class="step-info">
        <span class="step-label">Langkah 02</span>
        <span class="step-name">Data Kendaraan</span>
      </div>
    </div>
    <div class="step-item" data-step="3">
      <div class="step-num" id="snum-3">3</div>
      <div class="step-info">
        <span class="step-label">Langkah 03</span>
        <span class="step-name">Upload Foto</span>
      </div>
    </div>
  </div>

  <form id="reg-form" method="POST" enctype="multipart/form-data" novalidate>
  <!-- hidden real select untuk submit -->
  <select name="kelas" class="real-select" id="kelas-real">
    <option value="">-- Pilih Kelas --</option>
    <?php foreach ($kelas_list as $kl): ?>
    <option value="<?= $kl ?>" <?= $sel_kelas === $kl ? 'selected' : '' ?>><?= $kl ?></option>
    <?php endforeach; ?>
  </select>

  <div class="form-card">

    <!-- ══ STEP 1 ══ -->
    <div class="step-panel active" id="panel-1">
      <h2 class="step-panel-title">Data <em>Diri</em></h2>
      <p class="step-panel-sub">Isi informasi pribadi sesuai kartu pelajar dan dokumen resmi.</p>

      <div class="field-grid">

        <div class="field field-full">
          <label class="field-label" for="nama_lengkap">Nama Lengkap <span class="req">*</span></label>
          <div class="field-input-wrap">
            <input class="field-input" type="text" id="nama_lengkap" name="nama_lengkap"
              placeholder="Sesuai kartu pelajar" autocomplete="name"
              value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>">
            <i class="fa-solid fa-user field-icon"></i>
          </div>
          <div class="field-msg" id="msg-nama_lengkap"></div>
        </div>

        <div class="field">
          <label class="field-label" for="nis">NISN <span class="req">*</span></label>
          <div class="field-input-wrap">
            <input class="field-input" type="text" id="nis" name="nis" minlength="9"
              placeholder="Nomor Induk Siswa Nasional" maxlength="20"
              value="<?= htmlspecialchars($_POST['nis'] ?? '') ?>">
            <i class="fa-solid fa-id-badge field-icon"></i>
          </div>
          <div class="field-msg" id="msg-nis"></div>
        </div>

        <div class="field">
          <label class="field-label" for="umur">
            Umur <span class="req">*</span>
            <span class="hint">Min. 17 tahun</span>
          </label>
          <div class="field-input-wrap">
            <input class="field-input" type="number" id="umur" name="umur"
              placeholder="Usia" min="17" max="25"
              value="<?= htmlspecialchars($_POST['umur'] ?? '') ?>">
            <i class="fa-solid fa-cake-candles field-icon"></i>
          </div>
          <div class="field-msg" id="msg-umur"></div>
        </div>

        <!-- KELAS — custom dark dropdown -->
        <div class="field">
          <label class="field-label">Kelas <span class="req">*</span></label>
          <div class="custom-select-wrap" id="cs-kelas-wrap">
            <div class="custom-select-trigger placeholder" id="cs-kelas-trigger" tabindex="0" role="combobox" aria-haspopup="listbox" aria-expanded="false">
              <i class="fa-solid fa-users cs-icon"></i>
              <span id="cs-kelas-text">-- Pilih Kelas --</span>
              <i class="fa-solid fa-chevron-down cs-arrow"></i>
            </div>
            <div class="custom-select-panel" id="cs-kelas-panel" role="listbox">
              <div class="cs-search-wrap">
                <i class="fa-solid fa-magnifying-glass cs-search-icon"></i>
                <input class="cs-search" type="text" id="cs-kelas-search" placeholder="Cari kelas..." autocomplete="off">
              </div>
              <div class="cs-options" id="cs-kelas-options">
                <?php
                $prev_tingkat = '';
                foreach ($kelas_list as $kl):
                  $parts = explode(' ', $kl);
                  $tingkat_label = 'Kelas ' . $parts[0];
                  if ($tingkat_label !== $prev_tingkat):
                    $prev_tingkat = $tingkat_label;
                ?>
                <div class="cs-group-header"><?= $tingkat_label ?></div>
                <?php endif; ?>
                <div class="cs-option" data-value="<?= $kl ?>" role="option"
                  <?= $sel_kelas === $kl ? 'class="cs-option selected"' : '' ?>>
                  <?= $kl ?>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <div class="field-msg" id="msg-kelas"></div>
        </div>

        <div class="field">
          <label class="field-label" for="email">Email <span class="req">*</span></label>
          <div class="field-input-wrap">
            <input class="field-input" type="email" id="email" name="email"
              placeholder="email@contoh.com" autocomplete="email"
              value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <i class="fa-solid fa-envelope field-icon"></i>
          </div>
          <div class="field-msg" id="msg-email"></div>
        </div>

        <div class="field">
          <label class="field-label" for="no_telepon">Nomor Telepon <span class="req">*</span></label>
          <div class="field-input-wrap">
            <input class="field-input" type="tel" id="no_telepon" name="no_telepon"
              placeholder="08xxxxxxxxxx" maxlength="15"
              value="<?= htmlspecialchars($_POST['no_telepon'] ?? '') ?>">
            <i class="fa-solid fa-phone field-icon"></i>
          </div>
          <div class="field-msg" id="msg-no_telepon"></div>
        </div>

      </div>

      <div class="form-nav">
        <span class="step-counter">Langkah <span>1</span> dari 3</span>
        <button type="button" class="btn-next" onclick="goNext(1)">
          Lanjut <i class="fa-solid fa-arrow-right"></i>
        </button>
      </div>
    </div>

    <!-- ══ STEP 2 ══ -->
    <div class="step-panel" id="panel-2">
      <h2 class="step-panel-title">Data <em>Kendaraan</em></h2>
      <p class="step-panel-sub">Isi informasi kendaraan sesuai STNK. Semua field wajib diisi.</p>

      <div class="field-grid">

        <div class="field field-full">
          <label class="field-label" for="nama_kendaraan">
            Nama Kendaraan <span class="req">*</span>
            <span class="hint">contoh: Honda Vario 125</span>
          </label>
          <div class="field-input-wrap">
            <input class="field-input" type="text" id="nama_kendaraan" name="nama_kendaraan"
              placeholder="Merek dan tipe kendaraan"
              value="<?= htmlspecialchars($_POST['nama_kendaraan'] ?? '') ?>">
            <i class="fa-solid fa-motorcycle field-icon"></i>
          </div>
          <div class="field-msg" id="msg-nama_kendaraan"></div>
        </div>

        <div class="field">
          <label class="field-label" for="nomor_tnkb">
            Nomor TNKB <span class="req">*</span>
            <span class="hint">Nomor plat kendaraan</span>
          </label>
          <div class="field-input-wrap">
            <input class="field-input" type="text" id="nomor_tnkb" name="nomor_tnkb"
              placeholder="H 1234 AB" maxlength="15"
              style="text-transform:uppercase;font-family:ui-monospace,monospace;letter-spacing:0.08em;"
              value="<?= htmlspecialchars($_POST['nomor_tnkb'] ?? '') ?>">
            <i class="fa-solid fa-hashtag field-icon"></i>
          </div>
          <div class="field-msg" id="msg-nomor_tnkb"></div>
        </div>

        <!-- Jenis kendaraan — custom dropdown juga -->
        <div class="field">
          <label class="field-label">Jenis Kendaraan <span class="req">*</span></label>
          <div class="custom-select-wrap" id="cs-jenis-wrap">
            <div class="custom-select-trigger" id="cs-jenis-trigger" tabindex="0" role="combobox" aria-haspopup="listbox" aria-expanded="false">
              <i class="fa-solid fa-car-side cs-icon"></i>
              <span id="cs-jenis-text">Motor</span>
              <i class="fa-solid fa-chevron-down cs-arrow"></i>
            </div>
            <div class="custom-select-panel" id="cs-jenis-panel" role="listbox">
              <div class="cs-options" id="cs-jenis-options">
                <div class="cs-option selected" data-value="motor" role="option">🏍️&nbsp; Motor</div>
                <div class="cs-option" data-value="mobil"  role="option">🚗&nbsp; Mobil</div>
              </div>
            </div>
          </div>
          <!-- hidden real select untuk jenis -->
          <select name="jenis" class="real-select" id="jenis-real">
            <option value="motor"  <?= ($_POST['jenis'] ?? 'motor') === 'motor'  ? 'selected' : '' ?>>Motor</option>
            <option value="mobil"  <?= ($_POST['jenis'] ?? '') === 'mobil'  ? 'selected' : '' ?>>Mobil</option>
          </select>
        </div>

      </div>

      <div class="form-nav">
        <button type="button" class="btn-prev" onclick="goPrev(2)">
          <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>
        <span class="step-counter">Langkah <span>2</span> dari 3</span>
        <button type="button" class="btn-next" onclick="goNext(2)">
          Lanjut <i class="fa-solid fa-arrow-right"></i>
        </button>
      </div>
    </div>

    <!-- ══ STEP 3 ══ -->
    <div class="step-panel" id="panel-3">
      <h2 class="step-panel-title">Upload <em>Dokumen</em></h2>
      <p class="step-panel-sub">Upload 3 foto berikut. Format JPG/PNG/WebP, maks. 2MB per file.</p>

      <div class="upload-grid">
        <div>
          <div class="field-label" style="margin-bottom:10px;">Foto Kendaraan <span class="req">*</span></div>
          <div class="upload-area" id="area-kendaraan">
            <input type="file" name="foto_kendaraan" id="file-kendaraan" accept="image/jpeg,image/png,image/webp" required>
            <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
            <span class="upload-label">Foto Kendaraan</span>
            <span class="upload-hint">Plat nomor harus terlihat</span>
            <div class="upload-preview" id="prev-kendaraan">
              <img id="previmg-kendaraan" src="" alt="">
              <button type="button" class="upload-preview-del" onclick="clearFile('kendaraan')"><i class="fa-solid fa-xmark"></i></button>
            </div>
          </div>
          <div class="field-msg" id="msg-foto_kendaraan"></div>
        </div>
        <div>
          <div class="field-label" style="margin-bottom:10px;">Foto SIM <span class="req">*</span></div>
          <div class="upload-area" id="area-sim">
            <input type="file" name="foto_sim" id="file-sim" accept="image/jpeg,image/png,image/webp" required>
            <div class="upload-icon"><i class="fa-solid fa-id-card"></i></div>
            <span class="upload-label">Foto SIM</span>
            <span class="upload-hint">SIM aktif, tampak penuh</span>
            <div class="upload-preview" id="prev-sim">
              <img id="previmg-sim" src="" alt="">
              <button type="button" class="upload-preview-del" onclick="clearFile('sim')"><i class="fa-solid fa-xmark"></i></button>
            </div>
          </div>
          <div class="field-msg" id="msg-foto_sim"></div>
        </div>
        <div>
          <div class="field-label" style="margin-bottom:10px;">Foto Kartu Pelajar <span class="req">*</span></div>
          <div class="upload-area" id="area-kartu">
            <input type="file" name="foto_kartu" id="file-kartu" accept="image/jpeg,image/png,image/webp" required>
            <div class="upload-icon"><i class="fa-solid fa-school-flag"></i></div>
            <span class="upload-label">Kartu Pelajar</span>
            <span class="upload-hint">Masih berlaku, terbaca jelas</span>
            <div class="upload-preview" id="prev-kartu">
              <img id="previmg-kartu" src="" alt="">
              <button type="button" class="upload-preview-del" onclick="clearFile('kartu')"><i class="fa-solid fa-xmark"></i></button>
            </div>
          </div>
          <div class="field-msg" id="msg-foto_kartu"></div>
        </div>
      </div>

      <div style="margin-top:32px;padding-top:28px;border-top:1px solid var(--border);">
        <div class="field-label" style="margin-bottom:14px;">Ringkasan Data</div>
        <div class="review-grid" id="review-summary"></div>
      </div>

      <div class="form-nav">
        <button type="button" class="btn-prev" onclick="goPrev(3)">
          <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>
        <span class="step-counter">Langkah <span>3</span> dari 3</span>
        <button type="submit" class="btn-submit">
          <i class="fa-solid fa-paper-plane"></i> Kirim Pendaftaran
        </button>
      </div>
    </div>

  </div>
  </form>

  <?php endif; ?>
</main>

<script>
(function(){
'use strict';

/* ── data kelas dari PHP ── */
var KELAS_LIST = <?= $kelas_json ?>;
var selectedKelas = <?= json_encode($sel_kelas) ?>;

/* ── current step ── */
var currentStep = 1;
var formData    = {};

/* ════════════════════════════════════════
   CUSTOM SELECT — generic factory
   ════════════════════════════════════════ */
function initCustomSelect(cfg) {
  // cfg: { triggerId, textId, panelId, optionsId, searchId, realSelectId, msgId, onSelect }
  var trigger    = document.getElementById(cfg.triggerId);
  var textEl     = document.getElementById(cfg.textId);
  var panel      = document.getElementById(cfg.panelId);
  var optionsEl  = document.getElementById(cfg.optionsId);
  var searchEl   = cfg.searchId ? document.getElementById(cfg.searchId) : null;
  var realSelect = cfg.realSelectId ? document.getElementById(cfg.realSelectId) : null;
  var msgEl      = cfg.msgId ? document.getElementById(cfg.msgId) : null;

  if (!trigger || !panel) return;

  function open() {
    trigger.classList.add('open');
    trigger.setAttribute('aria-expanded', 'true');
    panel.classList.add('open');
    if (searchEl) { searchEl.value = ''; filterOptions(''); searchEl.focus(); }
  }
  function close() {
    trigger.classList.remove('open');
    trigger.setAttribute('aria-expanded', 'false');
    panel.classList.remove('open');
  }
  function toggle() { panel.classList.contains('open') ? close() : open(); }

  trigger.addEventListener('click', toggle);
  trigger.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); }
    if (e.key === 'Escape') close();
  });

  /* click outside */
  document.addEventListener('click', function(e) {
    if (!trigger.closest('.custom-select-wrap').contains(e.target)) close();
  });

  /* search filter */
  function filterOptions(q) {
    if (!optionsEl) return;
    var opts   = optionsEl.querySelectorAll('.cs-option');
    var groups = optionsEl.querySelectorAll('.cs-group-header');
    q = q.toLowerCase();
    opts.forEach(function(o) {
      var match = o.dataset.value.toLowerCase().includes(q);
      o.classList.toggle('hidden', !match);
    });
    /* hide group headers if all children hidden */
    groups.forEach(function(g) {
      var next = g.nextElementSibling;
      var anyVisible = false;
      while (next && !next.classList.contains('cs-group-header')) {
        if (!next.classList.contains('hidden')) anyVisible = true;
        next = next.nextElementSibling;
      }
      g.style.display = anyVisible ? '' : 'none';
    });
  }

  if (searchEl) {
    searchEl.addEventListener('input', function() { filterOptions(this.value); });
    searchEl.addEventListener('keydown', function(e) { if (e.key === 'Escape') close(); });
  }

  /* option click */
  optionsEl && optionsEl.addEventListener('click', function(e) {
    var opt = e.target.closest('.cs-option');
    if (!opt) return;
    var val = opt.dataset.value;

    /* deselect all */
    optionsEl.querySelectorAll('.cs-option').forEach(function(o) { o.classList.remove('selected'); });
    opt.classList.add('selected');

    textEl.textContent = opt.textContent.trim();
    trigger.classList.remove('placeholder', 'invalid');
    trigger.classList.add('valid');

    if (realSelect) { realSelect.value = val; }
    if (msgEl) { msgEl.className = 'field-msg show ok'; msgEl.innerHTML = '<i class="fa-solid fa-circle-check"></i> OK'; }
    if (cfg.onSelect) cfg.onSelect(val, opt.textContent.trim());

    close();
  });
}

/* ── init kelas dropdown ── */
initCustomSelect({
  triggerId:    'cs-kelas-trigger',
  textId:       'cs-kelas-text',
  panelId:      'cs-kelas-panel',
  optionsId:    'cs-kelas-options',
  searchId:     'cs-kelas-search',
  realSelectId: 'kelas-real',
  msgId:        'msg-kelas',
});

/* set initial value if coming back from server */
if (selectedKelas) {
  var initOpt = document.querySelector('#cs-kelas-options .cs-option[data-value="' + selectedKelas + '"]');
  if (initOpt) {
    document.getElementById('cs-kelas-text').textContent = selectedKelas;
    document.getElementById('cs-kelas-trigger').classList.remove('placeholder');
  }
}

/* ── init jenis dropdown ── */
initCustomSelect({
  triggerId:    'cs-jenis-trigger',
  textId:       'cs-jenis-text',
  panelId:      'cs-jenis-panel',
  optionsId:    'cs-jenis-options',
  realSelectId: 'jenis-real',
  onSelect: function(val, text) {
    document.getElementById('cs-jenis-text').textContent = text;
  }
});

/* ════════════════════════════════════════
   STEP NAVIGATION
   ════════════════════════════════════════ */
function goNext(from) {
  if (!validateStep(from)) return;
  collectData(from);
  switchStep(from, from + 1, false);
}
function goPrev(from) {
  switchStep(from, from - 1, true);
}
window.goNext = goNext;
window.goPrev = goPrev;

function switchStep(from, to, reverse) {
  var pFrom = document.getElementById('panel-' + from);
  var pTo   = document.getElementById('panel-' + to);
  if (!pFrom || !pTo) return;
  pFrom.classList.remove('active');
  if (reverse) pTo.classList.add('reverse'); else pTo.classList.remove('reverse');
  pTo.classList.add('active');
  currentStep = to;
  updateIndicator();
  if (to === 3) buildReview();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateIndicator() {
  document.querySelectorAll('.step-item').forEach(function(el) {
    var s = +el.dataset.step;
    el.classList.remove('active','done');
    var snum = document.getElementById('snum-' + s);
    if (s < currentStep) {
      el.classList.add('done');
      snum.innerHTML = '<i class="fa-solid fa-check" style="font-size:11px"></i>';
    } else if (s === currentStep) {
      el.classList.add('active');
      snum.textContent = s;
    } else {
      snum.textContent = s;
    }
  });
}

/* ════════════════════════════════════════
   VALIDATION
   ════════════════════════════════════════ */
function validateStep(step) {
  var ok = true;
  if (step === 1) {
    ok &= vf('nama_lengkap', function(v){ return v.length>=3?'':'Nama minimal 3 karakter'; });
    ok &= vf('nis',          function(v){ return /^\d{9,10}$/.test(v)?'':'NISN harus angka, 9–10 digit'; });
    ok &= vf('umur',         function(v){ return (+v>=17&&+v<=25)?'':'Umur minimal 17 tahun (maks. 25)'; });
    /* kelas — validate custom dropdown */
    var kr = document.getElementById('kelas-real');
    var msg = document.getElementById('msg-kelas');
    var trig = document.getElementById('cs-kelas-trigger');
    if (!kr || !kr.value) {
      if (trig) trig.classList.add('invalid');
      if (msg)  { msg.className='field-msg show err'; msg.innerHTML='<i class="fa-solid fa-circle-exclamation"></i> Pilih kelas terlebih dahulu'; }
      ok = false;
    }
    ok &= vf('email',      function(v){ return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)?'':'Format email tidak valid'; });
    ok &= vf('no_telepon', function(v){ return /^08\d{8,12}$/.test(v)?'':'Format: 08xxxxxxxxxx (10–13 digit)'; });
  }
  if (step === 2) {
    ok &= vf('nama_kendaraan', function(v){ return v.length>=3?'':'Nama kendaraan minimal 3 karakter'; });
    ok &= vf('nomor_tnkb',     function(v){ return /^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{1,3}$/.test(v.toUpperCase())?'':'Format plat tidak valid (contoh: H 1234 AB)'; });
  }
  return !!ok;
}

function vf(id, rule) {
  var el  = document.getElementById(id);
  var msg = document.getElementById('msg-' + id);
  if (!el) return true;
  var err = rule(el.value.trim());
  if (err) {
    el.classList.add('invalid'); el.classList.remove('valid');
    if (msg) { msg.className='field-msg show err'; msg.innerHTML='<i class="fa-solid fa-circle-exclamation"></i> '+err; }
    el.focus(); return false;
  }
  el.classList.remove('invalid'); el.classList.add('valid');
  if (msg) { msg.className='field-msg show ok'; msg.innerHTML='<i class="fa-solid fa-circle-check"></i> OK'; }
  return true;
}

/* real-time clear + blur validate */
var RULES = {
  nama_lengkap:   function(v){ return v.length>=3?'':'Nama minimal 3 karakter'; },
  nis:            function(v){ return /^\d{9,10}$/.test(v)?'':'NISN harus angka, 9–10 digit'; },
  umur:           function(v){ return (+v>=17&&+v<=25)?'':'Umur minimal 17 tahun (maks. 25)'; },
  email:          function(v){ return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)?'':'Format email tidak valid'; },
  no_telepon:     function(v){ return /^08\d{8,12}$/.test(v)?'':'Format: 08xxxxxxxxxx'; },
  nama_kendaraan: function(v){ return v.length>=3?'':'Minimal 3 karakter'; },
  nomor_tnkb:     function(v){ return /^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{1,3}$/.test(v.toUpperCase())?'':'Format tidak valid (contoh: H 1234 AB)'; },
};
Object.keys(RULES).forEach(function(id) {
  var el = document.getElementById(id);
  if (!el) return;
  el.addEventListener('input', function() {
    el.classList.remove('invalid','valid');
    var m = document.getElementById('msg-'+id);
    if (m) { m.className='field-msg'; m.innerHTML=''; }
  });
  el.addEventListener('blur', function() {
    if (!el.value.trim()) return;
    vf(id, RULES[id]);
  });
});

/* uppercase TNKB */
var tnkb = document.getElementById('nomor_tnkb');
if (tnkb) tnkb.addEventListener('input', function(){ this.value = this.value.toUpperCase(); });

/* ════════════════════════════════════════
   REVIEW SUMMARY
   ════════════════════════════════════════ */
function collectData(step) {
  if (step===1) {
    ['nama_lengkap','nis','umur','email','no_telepon'].forEach(function(id){
      var el=document.getElementById(id); if(el) formData[id]=el.value;
    });
    formData['kelas'] = document.getElementById('kelas-real').value;
  }
  if (step===2) {
    ['nama_kendaraan','nomor_tnkb'].forEach(function(id){
      var el=document.getElementById(id); if(el) formData[id]=el.value;
    });
    formData['jenis'] = document.getElementById('jenis-real').value;
  }
}
function buildReview() {
  var box = document.getElementById('review-summary');
  if (!box) return;
  var labels = { nama_lengkap:'Nama Lengkap', nis:'NISN', umur:'Umur', kelas:'Kelas', email:'Email', no_telepon:'No. Telepon', nama_kendaraan:'Kendaraan', nomor_tnkb:'Nomor TNKB', jenis:'Jenis' };
  var html='';
  Object.keys(labels).forEach(function(k){
    var v=formData[k]||'-';
    if(k==='umur'&&v!=='-') v=v+' tahun';
    if(k==='jenis') v={motor:'🏍️ Motor',mobil:'🚗 Mobil'}[v]||v;
    html+='<div class="review-item"><span class="review-item-key">'+labels[k]+'</span><span class="review-item-val">'+v+'</span></div>';
  });
  box.innerHTML=html;
}

/* ════════════════════════════════════════
   UPLOAD PREVIEW + DRAG DROP
   ════════════════════════════════════════ */
['kendaraan','sim','kartu'].forEach(function(key){
  var inp  = document.getElementById('file-'+key);
  var area = document.getElementById('area-'+key);
  var prev = document.getElementById('prev-'+key);
  var img  = document.getElementById('previmg-'+key);
  if (!inp) return;

  inp.addEventListener('change', function(){ showPreview(this, prev, img, key); });
  area.addEventListener('dragover',  function(e){ e.preventDefault(); area.classList.add('dragover'); });
  area.addEventListener('dragleave', function(){ area.classList.remove('dragover'); });
  area.addEventListener('drop', function(e){
    e.preventDefault(); area.classList.remove('dragover');
    if (e.dataTransfer.files.length){ inp.files=e.dataTransfer.files; showPreview(inp,prev,img,key); }
  });
});

function showPreview(inp, prev, img, key) {
  var file=inp.files[0]; if(!file) return;
  var msgEl=document.getElementById('msg-foto_'+key);
  if (file.size > 2*1024*1024) {
    if(msgEl){msgEl.className='field-msg show err';msgEl.innerHTML='<i class="fa-solid fa-circle-exclamation"></i> Ukuran maks. 2MB';}
    inp.value=''; return;
  }
  if(msgEl){msgEl.className='field-msg show ok';msgEl.innerHTML='<i class="fa-solid fa-circle-check"></i> '+file.name;}
  var reader=new FileReader();
  reader.onload=function(e){ img.src=e.target.result; prev.classList.add('show'); };
  reader.readAsDataURL(file);
}

function clearFile(key) {
  var inp=document.getElementById('file-'+key);
  var prev=document.getElementById('prev-'+key);
  var img=document.getElementById('previmg-'+key);
  var msg=document.getElementById('msg-foto_'+key);
  if(inp) inp.value='';
  if(prev) prev.classList.remove('show');
  if(img) img.src='';
  if(msg){msg.className='field-msg';msg.innerHTML='';}
}
window.clearFile=clearFile;

})();
</script>
</body>
</html> 