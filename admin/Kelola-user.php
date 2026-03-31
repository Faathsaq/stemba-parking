<?php
// admin/Kelola-user.php — Manajemen User · Stemba Parking
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$errors  = [];
$success = '';

// ── HAPUS USER ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'hapus') {
    $hapus_id = (int)($_POST['hapus_id'] ?? 0);
    if ($hapus_id === (int)$_SESSION['user_id']) {
        $errors[] = 'Anda tidak dapat menghapus akun sendiri.';
    } else {
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$hapus_id]);
        $success = 'User berhasil dihapus.';
    }
}

// ── EDIT USER ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
    $edit_id       = (int)($_POST['edit_id'] ?? 0);
    $edit_username = trim($_POST['edit_username'] ?? '');
    $edit_email    = trim($_POST['edit_email']    ?? '');
    $edit_role     = in_array($_POST['edit_role'] ?? '', ['admin','user']) ? $_POST['edit_role'] : 'user';
    $edit_kelas    = trim($_POST['edit_kelas']    ?? '');
    $edit_jurusan  = trim($_POST['edit_jurusan']  ?? '');
    $edit_password = trim($_POST['edit_password'] ?? '');

    if (!$edit_username)            $errors[] = 'Username tidak boleh kosong.';
    if (strlen($edit_username) < 3) $errors[] = 'Username minimal 3 karakter.';
    if ($edit_email && !filter_var($edit_email, FILTER_VALIDATE_EMAIL))
                                    $errors[] = 'Format email tidak valid.';

    if (!$errors) {
        $chk = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $chk->execute([$edit_username, $edit_id]);
        if ($chk->fetch()) $errors[] = 'Username sudah dipakai user lain.';
    }
    if (!$errors && $edit_email) {
        $chk2 = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $chk2->execute([$edit_email, $edit_id]);
        if ($chk2->fetch()) $errors[] = 'Email sudah dipakai user lain.';
    }

    if (!$errors) {
        if ($edit_password !== '') {
            if (strlen($edit_password) < 6) {
                $errors[] = 'Password minimal 6 karakter.';
            } else {
                $hash = password_hash($edit_password, PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE users SET username=?, email=?, password=?, role=?, kelas=?, jurusan=? WHERE id=?")
                    ->execute([$edit_username, $edit_email, $hash, $edit_role, $edit_kelas ?: null, $edit_jurusan ?: null, $edit_id]);
                $success = 'User berhasil diperbarui (termasuk password).';
            }
        } else {
            $pdo->prepare("UPDATE users SET username=?, email=?, role=?, kelas=?, jurusan=? WHERE id=?")
                ->execute([$edit_username, $edit_email, $edit_role, $edit_kelas ?: null, $edit_jurusan ?: null, $edit_id]);
            $success = 'User berhasil diperbarui.';
        }
    }
}

// ── PAGINATION ────────────────────────────────────────────
$per_page = 10;
$page     = max(1, (int)($_GET['page'] ?? 1));

$filter = $_GET['role'] ?? 'semua';
$where  = '';
$params = [];
if (in_array($filter, ['admin','user'])) {
    $where  = 'WHERE role = ?';
    $params = [$filter];
}

$search = trim($_GET['q'] ?? '');
if ($search !== '') {
    $like     = "%$search%";
    $where    = $where
        ? "$where AND (username LIKE ? OR email LIKE ?)"
        : 'WHERE (username LIKE ? OR email LIKE ?)';
    $params[] = $like;
    $params[] = $like;
}

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM users $where");
$count_stmt->execute($params);
$total       = (int)$count_stmt->fetchColumn();
$total_pages = max(1, ceil($total / $per_page));
$page        = min($page, $total_pages);
$offset      = ($page - 1) * $per_page;

$sql  = "SELECT id, username, email, role, kelas, jurusan, foto, created_at
         FROM users $where ORDER BY created_at DESC LIMIT $per_page OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();

$stats = $pdo->query("
    SELECT COUNT(*)           AS total,
           SUM(role='admin')  AS admin_count,
           SUM(role='user')   AS user_count
    FROM users
")->fetch();

$edit_user = null;
if (isset($_GET['edit'])) {
    $eu = $pdo->prepare("SELECT id, username, email, role, kelas, jurusan FROM users WHERE id=?");
    $eu->execute([(int)$_GET['edit']]);
    $edit_user = $eu->fetch();
}

$qs = http_build_query(['role' => $filter, 'q' => $search, 'page' => $page]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola User — Stemba Parking · SMKN 7 Semarang</title>

  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
/* ============================================================
   KELOLA USER — CREAMY LATTE · EDITORIAL (selaras navbar.php)
   ============================================================ */
:root {
  --font-serif:   'Instrument Serif', Georgia, serif;
  --font-sans:    'Outfit', sans-serif;

  /* Accent: Latte/caramel — identik navbar.php */
  --accent:       #B8935A;
  --accent-lt:    #C8A876;
  --accent-dim:   rgba(160,120,58,0.10);
  --accent-glow:  rgba(160,120,58,0.06);

  /* Background — cream/ivory */
  --bg:           #FFFCF8;
  --bg-card:      #ffffff;

  /* Foreground — warm dark brown */
  --fg:           #3D2E1A;
  --fg-mid:       rgba(61,46,26,0.72);
  --fg-low:       rgba(61,46,26,0.35);

  /* Borders — warm cream */
  --border:       rgba(200,180,140,0.20);
  --border-acc:   rgba(200,180,140,0.35);

  --nav-h:        68px;

  /* Latte tones — button */
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
html, body { background: var(--bg); color: var(--fg); font-family: var(--font-sans); min-height: 100vh; }

/* warm paper texture — identik navbar.php */
body::before {
  content: ''; position: fixed; inset: 0; z-index: 0; pointer-events: none;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
  background-size: 180px; opacity: 0.6;
}

/* warm latte ambient glow */
.adm-glow {
  position: fixed; z-index: 0; pointer-events: none;
  top: -180px; right: -120px; width: 700px; height: 700px; border-radius: 50%;
  background: radial-gradient(ellipse, rgba(160,120,58,0.07) 0%, transparent 65%);
  filter: blur(60px);
}

/* ── TOPBAR — glass cream, identik nav-glass ────────────── */
.adm-topbar {
  position: fixed; top: 0; left: 0; right: 0; z-index: 999; height: var(--nav-h);
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
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1.5px;
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
  content: ''; position: absolute; inset: 0; pointer-events: none; z-index: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
  background-size: 180px 180px; opacity: 0.6;
}

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
  height: var(--nav-h); display: flex; align-items: center;
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
  display: block; font-size: 13px; font-weight: 800;
  letter-spacing: 0.05em; text-transform: uppercase; color: var(--fg);
}
.adm-brand-sub {
  display: block; font-size: 9.5px; font-weight: 700;
  letter-spacing: 0.18em; text-transform: uppercase; color: var(--accent);
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
  box-shadow: 0 1px 4px rgba(140,100,50,0.06); text-decoration: none;
}
.adm-nav-link:hover i { opacity: 1; }
.adm-nav-link.active {
  color: var(--accent); background: var(--accent-dim); border-color: var(--border-acc);
}

/* ── TOPBAR RIGHT — identik nav-auth ────────────────────── */
.adm-topbar-right {
  margin-left: auto; display: flex; align-items: center;
  gap: 14px; padding-left: 24px; border-left: 1px solid var(--border);
}
.adm-admin-name { font-size: 13px; font-weight: 700; color: var(--fg); display: block; text-align: right; }
.adm-admin-role { font-size: 9px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--accent); display: block; text-align: right; }

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
  box-shadow: 0 2px 8px rgba(155,44,44,0.08); text-decoration: none;
}
.adm-logout i { font-size: 10px; }

/* ── MAIN WRAP ──────────────────────────────────────────── */
.adm-wrap {
  position: relative; z-index: 1; max-width: 1400px; margin: 0 auto;
  padding: calc(var(--nav-h) + 48px) 32px 80px;
}

/* ── PAGE HEADER ────────────────────────────────────────── */
.adm-page-header { border-bottom: 1px solid var(--border); padding-bottom: 32px; margin-bottom: 40px; }
.adm-overline { font-size: 9px; font-weight: 800; letter-spacing: 0.24em; text-transform: uppercase; color: var(--accent); display: block; margin-bottom: 12px; }
.adm-page-title { font-family: var(--font-serif); font-size: clamp(36px, 5vw, 64px); font-weight: 400; line-height: 0.95; letter-spacing: -0.02em; color: var(--fg); margin-bottom: 12px; }
.adm-page-title em { color: var(--accent); font-style: italic; }
.adm-page-desc { font-size: 14px; color: var(--fg-mid); line-height: 1.7; max-width: 52ch; }

/* ── BREADCRUMB ─────────────────────────────────────────── */
.adm-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
.adm-breadcrumb a { font-size: 11px; font-weight: 700; color: var(--fg-low); text-decoration: none; letter-spacing: 0.06em; text-transform: uppercase; transition: color 0.18s; }
.adm-breadcrumb a:hover { color: var(--accent); }
.adm-breadcrumb span { font-size: 10px; color: var(--fg-low); }
.adm-breadcrumb-cur { font-size: 11px; font-weight: 700; color: var(--accent); letter-spacing: 0.06em; text-transform: uppercase; }

/* ── STATS ──────────────────────────────────────────────── */
.adm-stats { display: grid; grid-template-columns: repeat(3,1fr); border: 1px solid var(--border); margin-bottom: 40px; background: var(--bg-card); }
.adm-stat { padding: 24px 28px; border-right: 1px solid var(--border); transition: background 0.2s; }
.adm-stat:last-child { border-right: none; }
.adm-stat:hover { background: var(--accent-dim); }
.adm-stat-num { font-family: var(--font-serif); font-size: 44px; font-weight: 400; line-height: 1; letter-spacing: -0.02em; display: block; }
.adm-stat-num.acc    { color: var(--accent); }
.adm-stat-num.yellow { color: var(--yellow); }
.adm-stat-num.green  { color: var(--green); }
.adm-stat-lbl { font-size: 9px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase; color: var(--fg-low); display: block; margin-top: 6px; }

/* ── TOOLBAR ────────────────────────────────────────────── */
.adm-toolbar {
  display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
  padding: 16px 20px; border: 1px solid var(--border); border-bottom: none; background: var(--bg-card);
}
.adm-filter-label { font-size: 9px; font-weight: 800; letter-spacing: 0.2em; text-transform: uppercase; color: var(--fg-low); }
.adm-filter-tabs { display: flex; gap: 4px; }
.adm-filter-tab {
  padding: 8px 16px; border-radius: 5px; font-size: 11.5px; font-weight: 700;
  letter-spacing: 0.06em; text-transform: uppercase; text-decoration: none;
  color: var(--fg-mid); border: 1px solid var(--border); background: transparent; transition: all 0.18s;
}
.adm-filter-tab:hover { color: var(--fg); background: rgba(160,120,58,0.06); border-color: var(--border-acc); text-decoration: none; }
.adm-filter-tab.active { color: var(--accent); background: var(--accent-dim); border-color: var(--border-acc); }

/* search */
.adm-search-form { display: flex; }
.adm-search-input {
  padding: 8px 14px; font-size: 12px; font-family: var(--font-sans); font-weight: 600;
  color: var(--fg); border: 1px solid var(--border); border-right: none;
  border-radius: 5px 0 0 5px; background: var(--bg); outline: none; width: 220px; transition: border-color 0.18s, background 0.18s;
}
.adm-search-input:focus { border-color: var(--border-acc); background: #fff; }
.adm-search-input::placeholder { color: var(--fg-low); font-style: italic; }
.adm-search-btn {
  padding: 8px 14px; border: 1px solid var(--border); background: var(--bg);
  border-radius: 0 5px 5px 0; cursor: pointer; color: var(--fg-mid); font-size: 11px; transition: all 0.18s;
}
.adm-search-btn:hover { background: var(--accent-dim); border-color: var(--border-acc); color: var(--accent); }

/* ── TABLE ──────────────────────────────────────────────── */
.adm-table-wrap { border: 1px solid var(--border); background: var(--bg-card); overflow-x: auto; }
table.adm-table { width: 100%; border-collapse: collapse; }
.adm-table th {
  padding: 12px 16px; font-size: 8.5px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase;
  color: var(--fg-low); background: rgba(200,180,140,0.06); border-bottom: 1px solid var(--border);
  white-space: nowrap; text-align: left;
}
.adm-table td {
  padding: 13px 16px; font-size: 13px; color: var(--fg);
  border-bottom: 1px solid var(--border); vertical-align: middle;
}
.adm-table tr:last-child td { border-bottom: none; }
.adm-table tbody tr { transition: background 0.15s; }
.adm-table tbody tr:hover { background: var(--accent-glow); }

/* avatar */
.adm-avatar {
  width: 36px; height: 36px; border-radius: 50%; object-fit: cover;
  border: 2px solid var(--border); background: var(--accent-dim);
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  font-size: 14px; color: var(--accent); font-weight: 800; overflow: hidden;
}
.adm-avatar img { width: 100%; height: 100%; object-fit: cover; }

/* badges */
.role-badge {
  display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 3px;
  font-size: 8.5px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; border: 1px solid transparent;
}
.role-admin { background: var(--accent-dim); color: var(--accent); border-color: var(--border-acc); }
.role-user  { background: var(--green-bg);   color: var(--green);  border-color: rgba(74,124,89,0.2); }

.self-badge {
  display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 3px;
  font-size: 8px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase;
  background: var(--yellow-bg); color: var(--yellow); border: 1px solid rgba(146,112,10,0.2); margin-left: 6px;
}

/* action buttons */
.adm-btn-edit {
  display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 5px;
  font-size: 10px; font-weight: 800; letter-spacing: 0.09em; text-transform: uppercase;
  color: var(--accent); border: 1px solid var(--border-acc); background: var(--accent-dim);
  text-decoration: none; transition: all 0.18s; margin-right: 4px; white-space: nowrap;
}
.adm-btn-edit:hover { background: var(--accent); color: #fff; text-decoration: none; border-color: var(--accent); }
.adm-btn-del {
  display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 5px;
  font-size: 10px; font-weight: 800; letter-spacing: 0.09em; text-transform: uppercase;
  color: var(--red); border: 1px solid rgba(155,44,44,0.2); background: var(--red-bg);
  cursor: pointer; transition: all 0.18s; font-family: var(--font-sans); white-space: nowrap;
}
.adm-btn-del:hover { background: var(--red); color: #fff; border-color: var(--red); }
.adm-btn-del:disabled { opacity: 0.35; cursor: not-allowed; }

.uname { font-family: ui-monospace,'Courier New',monospace; font-weight: 700; font-size: 13px; }
.email-cell { font-size: 12px; color: var(--fg-mid); }

/* ── EMPTY ──────────────────────────────────────────────── */
.adm-empty { padding: 80px 40px; text-align: center; }
.adm-empty-icon { font-size: 40px; color: rgba(160,120,58,0.18); margin-bottom: 16px; }
.adm-empty-title { font-family: var(--font-serif); font-size: 28px; color: var(--fg-low); margin-bottom: 8px; }
.adm-empty-sub { font-size: 13px; color: var(--fg-low); }

/* ── PAGINATION ─────────────────────────────────────────── */
.adm-pagination {
  border: 1px solid var(--border); border-top: none; background: var(--bg-card);
  padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
}
.adm-page-info { font-size: 11px; color: var(--fg-low); font-weight: 600; }
.adm-page-info strong { color: var(--fg); }
.adm-page-nav { display: flex; gap: 4px; }
.adm-page-btn {
  display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px;
  padding: 0 10px; border-radius: 5px; border: 1px solid var(--border); font-size: 11.5px; font-weight: 700;
  text-decoration: none; color: var(--fg-mid); background: transparent; transition: all 0.18s;
}
.adm-page-btn:hover { color: var(--accent); border-color: var(--border-acc); background: var(--accent-dim); text-decoration: none; }
.adm-page-btn.active { color: var(--accent); background: var(--accent-dim); border-color: var(--border-acc); }
.adm-page-btn.disabled { opacity: 0.35; pointer-events: none; }
.adm-page-btn i { font-size: 10px; }

/* ── ALERTS ─────────────────────────────────────────────── */
.adm-alert {
  padding: 14px 20px; border-radius: 5px; margin-bottom: 24px; font-size: 13px; font-weight: 600;
  display: flex; align-items: flex-start; gap: 10px; border: 1px solid transparent;
}
.adm-alert i { margin-top: 1px; flex-shrink: 0; }
.adm-alert-success { background: var(--green-bg); color: var(--green); border-color: rgba(74,124,89,0.2); }
.adm-alert-error   { background: var(--red-bg);   color: var(--red);   border-color: rgba(155,44,44,0.18); }

/* ── MODAL ──────────────────────────────────────────────── */
.adm-modal-backdrop {
  position: fixed; inset: 0; z-index: 1050;
  background: rgba(61,46,26,0.40);
  backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; padding: 20px;
  opacity: 0; pointer-events: none; transition: opacity 0.25s;
}
.adm-modal-backdrop.open { opacity: 1; pointer-events: auto; }
.adm-modal {
  background: var(--bg-card); border: 1px solid var(--border); width: 100%; max-width: 520px;
  border-radius: 6px;
  box-shadow: 0 24px 60px rgba(100,70,30,0.14), 0 4px 16px rgba(100,70,30,0.08);
  transform: translateY(20px) scale(0.97); transition: transform 0.25s cubic-bezier(.22,1,.36,1); position: relative;
  overflow: hidden;
}
/* caramel glint on modal */
.adm-modal::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
  background: linear-gradient(90deg, transparent, rgba(198,154,92,0.6), transparent);
  pointer-events: none;
}
.adm-modal-backdrop.open .adm-modal { transform: translateY(0) scale(1); }

.adm-modal-header {
  padding: 24px 28px 20px; border-bottom: 1px solid var(--border);
  display: flex; align-items: flex-start; justify-content: space-between;
  background: linear-gradient(180deg, rgba(240,229,208,0.25) 0%, transparent 100%);
}
.adm-modal-title { font-family: var(--font-serif); font-size: 24px; font-weight: 400; color: var(--fg); line-height: 1.1; }
.adm-modal-title em { color: var(--accent); font-style: italic; }
.adm-modal-close {
  background: none; border: none; cursor: pointer; padding: 2px 6px;
  color: var(--fg-low); font-size: 14px; transition: color 0.18s; font-family: var(--font-sans);
}
.adm-modal-close:hover { color: var(--fg); }
.adm-modal-body { padding: 24px 28px; max-height: 70vh; overflow-y: auto; }
.adm-modal-foot {
  padding: 16px 28px 24px; display: flex; gap: 8px; justify-content: flex-end;
  border-top: 1px solid var(--border);
}

/* form fields */
.adm-field { margin-bottom: 16px; }
.adm-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.adm-label { display: block; font-size: 9px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase; color: var(--fg-low); margin-bottom: 7px; }
.adm-input {
  width: 100%; padding: 10px 14px; font-size: 13px; font-family: var(--font-sans); font-weight: 600; color: var(--fg);
  border: 1px solid var(--border); border-radius: 4px; background: rgba(255,252,248,0.6); outline: none;
  transition: border-color 0.18s, background 0.18s, box-shadow 0.18s;
}
.adm-input:focus { border-color: var(--border-acc); background: #fff; box-shadow: 0 0 0 3px rgba(160,120,58,0.08); }
.adm-select {
  width: 100%; padding: 10px 14px; font-size: 13px; font-family: var(--font-sans); font-weight: 600; color: var(--fg);
  border: 1px solid var(--border); border-radius: 4px; background: rgba(255,252,248,0.6); outline: none; cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%233D2E1A' opacity='.4'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right 14px center; transition: border-color 0.18s;
}
.adm-select:focus { border-color: var(--border-acc); }
.adm-field-hint { font-size: 11px; color: var(--fg-low); margin-top: 5px; font-style: italic; }
.adm-pw-wrap { position: relative; }
.adm-pw-toggle {
  position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
  background: none; border: none; cursor: pointer; color: var(--fg-low); font-size: 12px; padding: 2px; transition: color 0.18s;
}
.adm-pw-toggle:hover { color: var(--accent); }

/* modal buttons — latte style */
.adm-btn-save {
  padding: 9px 24px; border-radius: 5px; border: 1px solid rgba(120,80,25,0.20); cursor: pointer;
  font-size: 11px; font-weight: 800; letter-spacing: 0.09em; text-transform: uppercase;
  background: linear-gradient(135deg, var(--latte-100) 0%, var(--latte-200) 60%, var(--latte-300) 100%);
  color: #fff; font-family: var(--font-sans);
  box-shadow: 0 2px 10px rgba(140,100,50,0.20), inset 0 1px 0 rgba(255,255,255,0.18);
  transition: filter 0.18s, box-shadow 0.18s, transform 0.18s;
}
.adm-btn-save:hover {
  filter: brightness(1.08);
  box-shadow: 0 6px 20px rgba(140,100,50,0.28), inset 0 1px 0 rgba(255,255,255,0.22);
  transform: translateY(-1px);
}
.adm-btn-cancel {
  padding: 9px 20px; border-radius: 5px; font-size: 11px; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase;
  color: var(--fg-mid); border: 1px solid var(--border); background: rgba(255,252,248,0.4); cursor: pointer;
  font-family: var(--font-sans); transition: all 0.18s;
}
.adm-btn-cancel:hover { background: var(--accent-dim); color: var(--fg); border-color: var(--border-acc); }

/* confirm delete modal */
.adm-confirm-icon { text-align: center; padding: 8px 0 20px; }
.adm-confirm-icon i { font-size: 36px; color: var(--red); opacity: 0.8; }
.adm-confirm-text { text-align: center; }
.adm-confirm-text p { font-size: 14px; color: var(--fg-mid); line-height: 1.6; }
.adm-confirm-text strong { color: var(--fg); font-size: 15px; }
.adm-btn-del-confirm {
  padding: 9px 24px; border-radius: 5px; border: none; cursor: pointer;
  font-size: 11px; font-weight: 800; letter-spacing: 0.09em; text-transform: uppercase;
  background: var(--red); color: #fff; font-family: var(--font-sans); transition: background 0.18s, transform 0.18s;
}
.adm-btn-del-confirm:hover { background: #7f1d1d; transform: translateY(-1px); }

/* ── RESPONSIVE ─────────────────────────────────────────── */
@media (max-width: 900px) {
  .adm-toolbar { flex-direction: column; align-items: flex-start; }
  .adm-nav { display: none; }
}
@media (max-width: 768px) {
  .adm-wrap { padding: calc(var(--nav-h) + 28px) 20px 60px; }
  .adm-topbar-inner { padding: 0 20px; }
  .adm-page-title { font-size: 36px; }
  td.hide-sm, th.hide-sm { display: none; }
  .adm-field-row { grid-template-columns: 1fr; }
}
@media (max-width: 520px) {
  .adm-stats { grid-template-columns: 1fr 1fr; }
  .adm-stats .adm-stat:last-child { grid-column: 1/-1; border-top: 1px solid var(--border); border-right: none; }
}
@media (prefers-reduced-motion: reduce) {
  .adm-topbar, .adm-nav-link, .adm-logout,
  .adm-modal, .adm-btn-save, .adm-btn-del-confirm { transition: none !important; }
}
</style>
</head>
<body>

<div class="adm-glow" aria-hidden="true"></div>

<!-- ── TOPBAR ─────────────────────────────────────────────── -->
<header class="adm-topbar" id="admTopbar">
  <div class="adm-topbar-inner">

    <!-- BRAND -->
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
      <a href="index.php" class="adm-nav-link">
        <i class="fas fa-car" aria-hidden="true"></i> Kendaraan
      </a>
      <a href="Kelola-user.php" class="adm-nav-link active">
        <i class="fas fa-users" aria-hidden="true"></i> Kelola User
      </a>
    </nav>

    <!-- RIGHT -->
    <div class="adm-topbar-right">
      <div>
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

  <!-- BREADCRUMB -->
  <div class="adm-breadcrumb" data-aos="fade-up" data-aos-duration="400">
    <a href="index.php"><i class="fas fa-home" style="font-size:10px"></i> Dashboard</a>
    <span>›</span>
    <span class="adm-breadcrumb-cur">Kelola User</span>
  </div>

  <!-- PAGE HEADER -->
  <div class="adm-page-header" data-aos="fade-up" data-aos-duration="600">
    <span class="adm-overline">Panel Administrator · Manajemen Akun</span>
    <h1 class="adm-page-title">
      Kelola<br>Akun <em>Pengguna</em>
    </h1>
    <p class="adm-page-desc">
      Lihat, edit, dan hapus akun admin maupun user siswa yang terdaftar di sistem Stemba Parking.
      Gunakan filter atau kolom pencarian untuk menemukan akun dengan cepat.
    </p>
  </div>

  <!-- ALERTS -->
  <?php if ($success): ?>
    <div class="adm-alert adm-alert-success" data-aos="fade-up">
      <i class="fas fa-check-circle"></i>
      <span><?= htmlspecialchars($success) ?></span>
    </div>
  <?php endif; ?>
  <?php if ($errors): ?>
    <div class="adm-alert adm-alert-error" data-aos="fade-up">
      <i class="fas fa-exclamation-triangle"></i>
      <div><?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?></div>
    </div>
  <?php endif; ?>

  <!-- STATS -->
  <div class="adm-stats" data-aos="fade-up" data-aos-delay="80">
    <div class="adm-stat">
      <span class="adm-stat-num acc"><?= $stats['total'] ?></span>
      <span class="adm-stat-lbl">Total User</span>
    </div>
    <div class="adm-stat">
      <span class="adm-stat-num yellow"><?= $stats['admin_count'] ?></span>
      <span class="adm-stat-lbl">Administrator</span>
    </div>
    <div class="adm-stat">
      <span class="adm-stat-num green"><?= $stats['user_count'] ?></span>
      <span class="adm-stat-lbl">User / Siswa</span>
    </div>
  </div>

  <!-- TOOLBAR -->
  <div class="adm-toolbar" data-aos="fade-up" data-aos-delay="120">
    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
      <span class="adm-filter-label">Filter Role</span>
      <div class="adm-filter-tabs">
        <?php foreach (['semua'=>'Semua','admin'=>'Admin','user'=>'User'] as $val=>$lbl): ?>
          <a href="?role=<?= $val ?>&q=<?= urlencode($search) ?>&page=1"
             class="adm-filter-tab <?= $filter===$val ? 'active':'' ?>"><?= $lbl ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <form class="adm-search-form" method="GET" action="">
      <input type="hidden" name="role" value="<?= htmlspecialchars($filter) ?>">
      <input type="hidden" name="page" value="1">
      <input class="adm-search-input" type="text" name="q"
             placeholder="Cari username / email…"
             value="<?= htmlspecialchars($search) ?>">
      <button class="adm-search-btn" type="submit"><i class="fas fa-search"></i></button>
    </form>
  </div>

  <!-- TABLE -->
  <div class="adm-table-wrap" data-aos="fade-up" data-aos-delay="160">
    <?php if (empty($users)): ?>
      <div class="adm-empty">
        <div class="adm-empty-icon"><i class="fas fa-users-slash"></i></div>
        <div class="adm-empty-title">Tidak ada user</div>
        <p class="adm-empty-sub">Tidak ditemukan akun untuk filter atau pencarian ini.</p>
      </div>
    <?php else: ?>
    <table class="adm-table">
      <thead>
        <tr>
          <th>#</th>
          <th></th>
          <th>Username</th>
          <th class="hide-sm">Email</th>
          <th>Role</th>
          <th class="hide-sm">Kelas / Jurusan</th>
          <th class="hide-sm">Terdaftar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $i => $u):
          $is_self  = ((int)$u['id'] === (int)$_SESSION['user_id']);
          $inisial  = strtoupper(substr($u['username'], 0, 1));
          $foto_src = !empty($u['foto']) ? '../uploads/' . htmlspecialchars($u['foto']) : null;
        ?>
        <tr>
          <td style="color:var(--fg-low);font-size:12px;font-weight:700;width:40px;">
            <?= $offset + $i + 1 ?>
          </td>
          <td style="width:48px;">
            <div class="adm-avatar">
              <?php if ($foto_src): ?>
                <img src="<?= $foto_src ?>" alt="">
              <?php else: ?>
                <?= $inisial ?>
              <?php endif; ?>
            </div>
          </td>
          <td>
            <span class="uname"><?= htmlspecialchars($u['username']) ?></span>
            <?php if ($is_self): ?>
              <span class="self-badge"><i class="fas fa-user" style="font-size:7px"></i> Anda</span>
            <?php endif; ?>
          </td>
          <td class="hide-sm">
            <span class="email-cell"><?= htmlspecialchars($u['email'] ?? '—') ?></span>
          </td>
          <td>
            <span class="role-badge role-<?= $u['role'] ?>">
              <i class="fas <?= $u['role']==='admin' ? 'fa-shield-halved' : 'fa-user' ?>" style="font-size:8px"></i>
              <?= $u['role'] === 'admin' ? 'Admin' : 'User' ?>
            </span>
          </td>
          <td class="hide-sm" style="font-size:12px;color:var(--fg-mid);">
            <?php
              $kj = array_filter([$u['kelas'] ?? '', $u['jurusan'] ?? '']);
              echo $kj ? htmlspecialchars(implode(' · ', $kj)) : '—';
            ?>
          </td>
          <td class="hide-sm" style="font-size:12px;color:var(--fg-low);">
            <?= date('d M Y', strtotime($u['created_at'])) ?>
          </td>
          <td style="white-space:nowrap;">
            <a href="?role=<?= $filter ?>&q=<?= urlencode($search) ?>&page=<?= $page ?>&edit=<?= $u['id'] ?>"
               class="adm-btn-edit">
              <i class="fas fa-pen" style="font-size:9px"></i> Edit
            </a>
            <?php if (!$is_self): ?>
            <button class="adm-btn-del"
                    onclick="openConfirmDel(<?= $u['id'] ?>, '<?= htmlspecialchars(addslashes($u['username'])) ?>')">
              <i class="fas fa-trash" style="font-size:9px"></i> Hapus
            </button>
            <?php else: ?>
            <button class="adm-btn-del" disabled title="Tidak bisa hapus akun sendiri">
              <i class="fas fa-trash" style="font-size:9px"></i> Hapus
            </button>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>

  <!-- PAGINATION -->
  <?php if ($total_pages > 1): ?>
  <div class="adm-pagination">
    <span class="adm-page-info">
      Menampilkan <strong><?= count($users) ?></strong> dari <strong><?= $total ?></strong> user
    </span>
    <nav class="adm-page-nav">
      <a href="?role=<?= $filter ?>&q=<?= urlencode($search) ?>&page=<?= $page-1 ?>"
         class="adm-page-btn <?= $page<=1 ? 'disabled':'' ?>">
        <i class="fas fa-chevron-left"></i>
      </a>
      <?php
        $range = 2; $start = max(1,$page-$range); $end = min($total_pages,$page+$range);
        if ($start > 1):
      ?><a href="?role=<?= $filter ?>&q=<?= urlencode($search) ?>&page=1" class="adm-page-btn">1</a>
        <?php if ($start > 2): ?><span class="adm-page-btn disabled">…</span><?php endif; ?>
      <?php endif;
        for ($i=$start;$i<=$end;$i++):
      ?><a href="?role=<?= $filter ?>&q=<?= urlencode($search) ?>&page=<?= $i ?>"
           class="adm-page-btn <?= $i===$page?'active':'' ?>"><?= $i ?></a>
      <?php endfor;
        if ($end < $total_pages):
          if ($end < $total_pages-1): ?><span class="adm-page-btn disabled">…</span><?php endif;
      ?><a href="?role=<?= $filter ?>&q=<?= urlencode($search) ?>&page=<?= $total_pages ?>" class="adm-page-btn"><?= $total_pages ?></a>
      <?php endif; ?>
      <a href="?role=<?= $filter ?>&q=<?= urlencode($search) ?>&page=<?= $page+1 ?>"
         class="adm-page-btn <?= $page>=$total_pages?'disabled':'' ?>">
        <i class="fas fa-chevron-right"></i>
      </a>
    </nav>
  </div>
  <?php endif; ?>

</main>

<!-- ═══════════════════════════════════
     MODAL: EDIT USER
     ═══════════════════════════════════ -->
<div class="adm-modal-backdrop <?= $edit_user ? 'open':'' ?>" id="modalEdit">
  <div class="adm-modal" role="dialog" aria-modal="true">
    <div class="adm-modal-header">
      <div>
        <div class="adm-modal-title">Edit <em>Akun</em></div>
        <div style="font-size:11px;color:var(--fg-low);margin-top:4px;">
          Perbarui data akun · ID #<?= $edit_user['id'] ?? '—' ?>
        </div>
      </div>
      <a href="?<?= $qs ?>" class="adm-modal-close" aria-label="Tutup">✕</a>
    </div>

    <form method="POST" action="">
      <input type="hidden" name="action"  value="edit">
      <input type="hidden" name="edit_id" value="<?= $edit_user['id'] ?? '' ?>">

      <div class="adm-modal-body">
        <div class="adm-field-row">
          <div class="adm-field">
            <label class="adm-label" for="edit_username">Username</label>
            <input class="adm-input" type="text" id="edit_username" name="edit_username"
                   value="<?= htmlspecialchars($edit_user['username'] ?? '') ?>"
                   placeholder="Username…" autocomplete="off" required>
          </div>
          <div class="adm-field">
            <label class="adm-label" for="edit_email">Email</label>
            <input class="adm-input" type="email" id="edit_email" name="edit_email"
                   value="<?= htmlspecialchars($edit_user['email'] ?? '') ?>"
                   placeholder="email@domain.com" autocomplete="off">
          </div>
        </div>

        <div class="adm-field">
          <label class="adm-label" for="edit_role">Role</label>
          <select class="adm-select" id="edit_role" name="edit_role">
            <option value="user"  <?= ($edit_user['role'] ?? '') === 'user'  ? 'selected':'' ?>>User (Siswa)</option>
            <option value="admin" <?= ($edit_user['role'] ?? '') === 'admin' ? 'selected':'' ?>>Admin</option>
          </select>
        </div>

        <div class="adm-field-row">
          <div class="adm-field">
            <label class="adm-label" for="edit_kelas">Kelas</label>
            <input class="adm-input" type="text" id="edit_kelas" name="edit_kelas"
                   value="<?= htmlspecialchars($edit_user['kelas'] ?? '') ?>"
                   placeholder="Contoh: XI">
          </div>
          <div class="adm-field">
            <label class="adm-label" for="edit_jurusan">Jurusan</label>
            <input class="adm-input" type="text" id="edit_jurusan" name="edit_jurusan"
                   value="<?= htmlspecialchars($edit_user['jurusan'] ?? '') ?>"
                   placeholder="Contoh: RPL">
          </div>
        </div>

        <div class="adm-field" style="margin-bottom:0;">
          <label class="adm-label" for="edit_password">Password Baru</label>
          <div class="adm-pw-wrap">
            <input class="adm-input" type="password" id="edit_password" name="edit_password"
                   placeholder="Kosongkan jika tidak ingin mengubah"
                   autocomplete="new-password" style="padding-right:40px;">
            <button type="button" class="adm-pw-toggle" onclick="togglePw()">
              <i class="fas fa-eye" id="pwIcon"></i>
            </button>
          </div>
          <p class="adm-field-hint">Minimal 6 karakter. Kosongkan jika tidak mengubah password.</p>
        </div>
      </div>

      <div class="adm-modal-foot">
        <a href="?<?= $qs ?>" class="adm-btn-cancel">Batal</a>
        <button type="submit" class="adm-btn-save">
          <i class="fas fa-check" style="font-size:10px;margin-right:5px"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ═══════════════════════════════════
     MODAL: KONFIRMASI HAPUS
     ═══════════════════════════════════ -->
<div class="adm-modal-backdrop" id="modalDel">
  <div class="adm-modal" role="dialog" aria-modal="true">
    <div class="adm-modal-header">
      <div class="adm-modal-title">Hapus <em>Akun</em></div>
      <button class="adm-modal-close" onclick="closeConfirmDel()">✕</button>
    </div>
    <div class="adm-modal-body">
      <div class="adm-confirm-icon"><i class="fas fa-triangle-exclamation"></i></div>
      <div class="adm-confirm-text">
        <p>Anda yakin ingin menghapus akun<br>
          <strong id="delUsername">—</strong>?
        </p>
        <p style="margin-top:10px;font-size:12px;color:var(--red);">
          Tindakan ini tidak dapat dibatalkan.
        </p>
      </div>
    </div>
    <form method="POST" action="">
      <input type="hidden" name="action"   value="hapus">
      <input type="hidden" name="hapus_id" id="delHapusId" value="">
      <div class="adm-modal-foot">
        <button type="button" class="adm-btn-cancel" onclick="closeConfirmDel()">Batal</button>
        <button type="submit" class="adm-btn-del-confirm">
          <i class="fas fa-trash" style="font-size:10px;margin-right:5px"></i> Ya, Hapus
        </button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  if (typeof AOS !== 'undefined') {
    AOS.init({ once: true, offset: 40, easing: 'ease-out-quart', duration: 600 });
  }

  // scroll: deeper glass — identik navbar.php
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

// Klik backdrop luar modal edit = tutup
document.getElementById('modalEdit').addEventListener('click', function(e) {
  if (e.target === this) window.location.href = '?<?= $qs ?>';
});

// Toggle password
function togglePw() {
  const inp  = document.getElementById('edit_password');
  const icon = document.getElementById('pwIcon');
  if (inp.type === 'password') { inp.type = 'text'; icon.className = 'fas fa-eye-slash'; }
  else                         { inp.type = 'password'; icon.className = 'fas fa-eye'; }
}

// Modal hapus
function openConfirmDel(id, username) {
  document.getElementById('delHapusId').value        = id;
  document.getElementById('delUsername').textContent = username;
  document.getElementById('modalDel').classList.add('open');
}
function closeConfirmDel() {
  document.getElementById('modalDel').classList.remove('open');
}
document.getElementById('modalDel').addEventListener('click', function(e) {
  if (e.target === this) closeConfirmDel();
});

// Escape = tutup semua modal
document.addEventListener('keydown', function(e) {
  if (e.key !== 'Escape') return;
  closeConfirmDel();
  const url = new URL(window.location.href);
  if (url.searchParams.has('edit')) {
    url.searchParams.delete('edit');
    window.location.href = url.toString();
  }
});
</script>
</body>
</html>