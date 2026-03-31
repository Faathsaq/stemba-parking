<?php
include 'includes/config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $pdo->prepare(
                "SELECT id, username, password, role, kelas, jurusan, foto 
                 FROM users 
                 WHERE username = ? OR email = ?"
            );
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {

                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role'];
                $_SESSION['kelas']    = $user['kelas'];
                $_SESSION['jurusan']  = $user['jurusan'];
                $_SESSION['foto']     = $user['foto'];

                if ($user['role'] === 'admin') {
                    header("Location: admin/index.php");
                } else {
                    header("Location: index.php");
                }
                exit();

            } else {
                $error = "Username/email atau password salah!";
            }
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan sistem!";
        }
    } else {
        $error = "Harap isi semua field!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Stemba Parking · SMKN 7 Semarang</title>

    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        /* ============================================================
           LOGIN — CREAMY LATTE (selaras dengan navbar.php)
           ============================================================ */
        :root {
            --font-serif:   'Instrument Serif', Georgia, serif;
            --font-sans:    'Outfit', sans-serif;

            /* Accent: Latte/caramel — sama dengan navbar */
            --accent:       #B8935A;
            --accent-light: #C8A876;
            --accent-dim:   rgba(160,120,58,0.10);
            --accent-glow:  rgba(160,120,58,0.06);

            /* Glass: ivory/cream — sama dengan navbar */
            --bg-glass:     rgba(255,253,245,0.75);
            --bg-base:      #FFFCF8;

            /* Borders — warm cream */
            --border:       rgba(200,180,140,0.15);
            --border-acc:   rgba(200,180,140,0.28);

            /* Foreground — dark warm brown, sama dengan navbar */
            --fg:           #3D2E1A;
            --fg-mid:       rgba(61,46,26,0.72);
            --fg-low:       rgba(61,46,26,0.35);

            /* Latte tones — untuk button, sama dengan .nav-btn-amber */
            --latte-100:    #D4A96A;
            --latte-200:    #C09050;
            --latte-300:    #B8823A;

            --nav-h:        68px;
            --blur:         24px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg-base);
            color: var(--fg);
            font-family: var(--font-sans);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* subtle warm paper texture — sama dengan navbar */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: -1; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
            background-size: 180px 180px;
            opacity: 0.6;
        }

        /* Warm latte ambient glows */
        .glow-top {
            position: fixed; z-index: -1; pointer-events: none;
            top: -200px; left: 50%; transform: translateX(-50%);
            width: 900px; height: 500px; border-radius: 50%;
            background: radial-gradient(ellipse, rgba(160,120,58,0.07) 0%, transparent 65%);
            filter: blur(40px);
        }

        .glow-side {
            position: fixed; z-index: -1; pointer-events: none;
            bottom: 10%; right: -200px;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(ellipse, rgba(160,120,58,0.05) 0%, transparent 65%);
            filter: blur(60px);
            animation: drift 24s ease-in-out infinite alternate;
        }

        @keyframes drift {
            from { transform: translate(0, 0); }
            to   { transform: translate(-30px, 40px); }
        }

        /* Watermark */
        .issue-watermark {
            position: fixed;
            bottom: -40px; left: -30px; z-index: -1;
            font-family: var(--font-serif);
            font-size: clamp(260px, 35vw, 520px);
            font-weight: 400; line-height: 0.85;
            color: transparent;
            -webkit-text-stroke: 1px rgba(160,120,58,0.06);
            pointer-events: none; user-select: none; letter-spacing: -0.04em;
        }

        /* Scan line — warm latte tint */
        .scanline {
            position: fixed; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(160,120,58,0.14), transparent);
            z-index: 2; pointer-events: none;
            animation: scan 10s ease-in-out infinite;
        }

        @keyframes scan {
            0%   { top: -2px; opacity: 0; }
            5%   { opacity: 1; }
            95%  { opacity: 0.5; }
            100% { top: 100%; opacity: 0; }
        }

        /* ── AUTH CONTAINER ─────────────────────────────────── */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: calc(var(--nav-h) + 40px) 20px 60px;
            position: relative;
            z-index: 10;
        }

        .auth-card {
            max-width: 440px;
            width: 100%;
            background: var(--bg-glass);
            backdrop-filter: blur(var(--blur)) saturate(180%) brightness(1.04);
            -webkit-backdrop-filter: blur(var(--blur)) saturate(180%) brightness(1.04);
            border: 1px solid var(--border);
            border-radius: 6px;
            position: relative;
            overflow: hidden;
            box-shadow:
                0 1px 0 rgba(255,255,255,0.9),
                0 4px 24px rgba(140,100,50,0.08);
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .auth-card:hover {
            border-color: var(--border-acc);
            box-shadow:
                0 1px 0 rgba(255,255,255,0.95),
                0 20px 48px rgba(140,100,50,0.12),
                0 4px 16px rgba(140,100,50,0.06);
        }

        /* top warm caramel glint — sama dengan navbar::before */
        .auth-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg,
                transparent 0%,
                rgba(160,120,58,0.30) 25%,
                rgba(198,154,92,0.60) 50%,
                rgba(160,120,58,0.30) 75%,
                transparent 100%);
            pointer-events: none;
        }

        /* Card Header */
        .auth-header {
            padding: 32px 32px 16px;
            border-bottom: 1px solid var(--border);
            text-align: center;
            background: linear-gradient(180deg, rgba(240,229,208,0.3) 0%, transparent 100%);
        }

        .auth-header h3 {
            font-family: var(--font-serif);
            font-size: 42px;
            font-weight: 400;
            color: var(--fg);
            margin: 0 0 8px;
            line-height: 1;
        }

        .auth-header p {
            color: var(--fg-mid);
            font-size: 13px;
            margin: 0;
            letter-spacing: 0.02em;
        }

        /* Card Body */
        .auth-body { padding: 32px; }

        /* Form Elements */
        .form-group { margin-bottom: 24px; }

        .form-label {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--fg-mid);
            margin-bottom: 8px;
            display: block;
        }

        .input-group {
            border: 1px solid var(--border);
            transition: border-color 0.22s, background 0.22s, box-shadow 0.22s;
            background: rgba(255,252,248,0.4);
            border-radius: 4px;
        }

        .input-group:focus-within {
            border-color: var(--border-acc);
            background: var(--accent-glow);
            box-shadow: 0 0 0 3px rgba(160,120,58,0.08);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: var(--fg-mid);
            padding: 12px 16px;
        }

        .form-control {
            background: transparent;
            border: none;
            color: var(--fg);
            font-size: 14px;
            padding: 12px 16px 12px 0;
            font-family: var(--font-sans);
        }

        .form-control:focus {
            background: transparent;
            box-shadow: none;
            color: var(--fg);
        }

        .form-control::placeholder {
            color: var(--fg-low);
            font-size: 13px;
            font-style: italic;
        }

        /* Error Message */
        .error-message {
            background: rgba(185,60,60,0.07);
            border: 1px solid rgba(185,60,60,0.20);
            color: rgba(185,60,60,0.85);
            padding: 14px 16px;
            margin-bottom: 24px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.02em;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-message i {
            color: rgba(185,60,60,0.85);
            font-size: 14px;
        }

        /* Login Button — latte gradient, persis .nav-btn-amber */
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--latte-100) 0%, var(--latte-200) 60%, var(--latte-300) 100%);
            color: #fff;
            border: 1px solid rgba(120,80,25,0.20);
            padding: 14px 24px;
            font-weight: 800;
            font-size: 12px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: transform 0.22s, box-shadow 0.22s, filter 0.22s;
            margin-top: 16px;
            cursor: pointer;
            font-family: var(--font-sans);
            box-shadow: 0 2px 10px rgba(140,100,50,0.20), inset 0 1px 0 rgba(255,255,255,0.18);
        }

        .btn-login:hover {
            color: #fff;
            transform: translateY(-2px);
            filter: brightness(1.08);
            box-shadow: 0 8px 28px rgba(140,100,50,0.30), inset 0 1px 0 rgba(255,255,255,0.22);
        }

        .btn-login i {
            font-size: 11px;
            transition: transform 0.22s;
        }

        .btn-login:hover i { transform: translateX(4px); }

        /* Auth Links */
        .auth-links {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .auth-links p {
            color: var(--fg-mid);
            font-size: 12px;
            margin: 0;
        }

        .auth-links a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
            position: relative;
        }

        .auth-links a::after {
            content: '';
            position: absolute; bottom: -2px; left: 0; right: 0; height: 1px;
            background: var(--accent);
            transform: scaleX(0);
            transition: transform 0.22s;
        }

        .auth-links a:hover { color: var(--accent-light); }
        .auth-links a:hover::after { transform: scaleX(1); }

        /* Bottom Bar */
        .auth-bottom-bar {
            margin-top: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            color: var(--fg-low);
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .auth-bottom-bar i { font-size: 4px; color: var(--accent); }

        /* Responsive */
        @media (max-width: 576px) {
            .auth-header h3 { font-size: 36px; }
            .auth-body { padding: 24px; }
            .auth-header { padding: 24px 24px 12px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .glow-side, .scanline,
            .btn-login:hover, .auth-card:hover {
                animation: none !important;
                transform: none !important;
            }
        }
    </style>
</head>

<body>

    <div class="glow-top" aria-hidden="true"></div>
    <div class="glow-side" aria-hidden="true"></div>
    <div class="scanline" aria-hidden="true"></div>
    <div class="issue-watermark" aria-hidden="true">02</div>

    <?php include 'includes/navbar.php'; ?>

    <section class="auth-container">
        <div class="auth-card" data-aos="fade-up" data-aos-duration="800">

            <div class="auth-header">
                <h3>Login</h3>
                <p>Masuk ke dashboard Stemba Parking</p>
            </div>

            <div class="auth-body">

                <?php if (!empty($error)): ?>
                    <div class="error-message" data-aos="fade-in" data-aos-duration="300">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group" data-aos="fade-up" data-aos-delay="100">
                        <label for="username" class="form-label">Username atau Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="contoh: johndoe" required
                                value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group" data-aos="fade-up" data-aos-delay="150">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" data-aos="fade-up" data-aos-delay="200">
                        <span>Masuk ke Dashboard</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-links" data-aos="fade-up" data-aos-delay="250">
                    <p>Belum punya akun? <a href="register.php">Daftar sekarang →</a></p>
                </div>

                <div class="auth-bottom-bar" data-aos="fade-up" data-aos-delay="300">
                    <span>SISTEM PENDATAAN</span>
                    <i class="fas fa-circle"></i>
                    <span>KENDARAAN SISWA</span>
                </div>

            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        (function () {
            'use strict';

            document.addEventListener('DOMContentLoaded', function () {
                if (typeof AOS !== 'undefined') {
                    AOS.init({ once: true, offset: 20, easing: 'ease-out-quart', duration: 700 });
                }
            });

            const issue = document.querySelector('.issue-watermark');
            if (issue) {
                let ticking = false;
                window.addEventListener('scroll', () => {
                    if (!ticking) {
                        requestAnimationFrame(() => {
                            issue.style.transform = `translateY(${window.scrollY * 0.05}px)`;
                            ticking = false;
                        });
                        ticking = true;
                    }
                }, { passive: true });
            }
        })();
    </script>

</body>
</html>