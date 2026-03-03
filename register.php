<?php
include 'includes/config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Harap isi semua field!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);

            if ($stmt->rowCount() > 0) {
                $error = "Username atau email sudah digunakan!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password]);

                $success = "Registrasi berhasil! Silakan login.";
            }
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan sistem!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Stemba Parking · SMKN 7 Semarang</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* ============================================================
           GLOBAL VARIABLES - MENGIKUTI NAVBAR
           ============================================================ */
        :root {
            --font-serif: 'Instrument Serif', Georgia, serif;
            --font-sans: 'Outfit', sans-serif;
            --accent: #f59e0b;
            --accent-dim: rgba(245,158,11,0.15);
            --accent-glow: rgba(245,158,11,0.08);
            --bg-glass: rgba(13,13,13,0.62);
            --border: rgba(255,255,255,0.07);
            --border-acc: rgba(245,158,11,0.28);
            --fg: #ffffff;
            --fg-mid: rgba(255,255,255,0.55);
            --fg-low: rgba(255,255,255,0.22);
            --blur: 18px;
            --nav-h: 68px;
            --bg-dark: #0d0d0d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--fg);
            font-family: var(--font-sans);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Noise Texture */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: -1; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            background-size: 160px 160px;
            opacity: 0.5;
        }

        /* Amber Glows */
        .glow-top {
            position: fixed; z-index: -1; pointer-events: none;
            top: -200px; left: 50%; transform: translateX(-50%);
            width: 900px; height: 500px; border-radius: 50%;
            background: radial-gradient(ellipse, rgba(245,158,11,0.09) 0%, transparent 65%);
            filter: blur(40px);
        }

        .glow-side {
            position: fixed; z-index: -1; pointer-events: none;
            bottom: 10%; right: -200px;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(ellipse, rgba(245,158,11,0.05) 0%, transparent 65%);
            filter: blur(60px);
            animation: drift 24s ease-in-out infinite alternate;
        }

        @keyframes drift { 
            from { transform: translate(0, 0); } 
            to { transform: translate(-30px, 40px); } 
        }

        /* Issue Watermark */
        .issue-watermark {
            position: fixed;
            bottom: -40px; left: -30px; z-index: -1;
            font-family: var(--font-serif);
            font-size: clamp(260px, 35vw, 520px);
            font-weight: 400; line-height: 0.85;
            color: transparent;
            -webkit-text-stroke: 1px rgba(245,158,11,0.06);
            pointer-events: none; user-select: none; letter-spacing: -0.04em;
        }

        /* Scan Line */
        .scanline {
            position: fixed; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(245,158,11,0.12), transparent);
            z-index: 2; pointer-events: none;
            animation: scan 8s ease-in-out infinite;
        }

        @keyframes scan {
            0%   { top: -2px; opacity: 0; }
            5%   { opacity: 1; }
            95%  { opacity: 0.6; }
            100% { top: 100%; opacity: 0; }
        }

        /* ============================================================
           NAVBAR - LANGSUNG IMPORT DARI NAVBAR.PHP
           ============================================================ */
        .nav-glass {
            position: fixed; top: 0; left: 0; right: 0; z-index: 999;
            height: var(--nav-h);
            background: var(--bg-glass);
            backdrop-filter: blur(var(--blur)) saturate(160%);
            -webkit-backdrop-filter: blur(var(--blur)) saturate(160%);
            border-bottom: 1px solid var(--border);
            font-family: var(--font-sans);
            transition: background 0.3s, box-shadow 0.3s;
        }

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

        .nav-glass::after {
            content: '';
            position: absolute; inset: 0; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            background-size: 160px 160px; opacity: 0.5; z-index: 0;
        }

        .nav-glass.nav-scrolled {
            background: rgba(10,10,10,0.80);
            box-shadow: 0 1px 0 var(--border), 0 8px 32px rgba(0,0,0,0.45), 0 2px 12px rgba(245,158,11,0.04);
        }

        .nav-inner {
            position: relative; z-index: 1;
            max-width: 1280px; margin: 0 auto;
            padding: 0 24px;
            height: var(--nav-h);
            display: flex; align-items: center; gap: 0;
        }

        .nav-brand {
            display: flex; align-items: center; gap: 12px;
            text-decoration: none; flex-shrink: 0; margin-right: 48px;
            position: relative;
        }

        .nav-brand-logo {
            width: 38px; height: 38px; border-radius: 4px;
            border: 1px solid var(--border-acc);
            background: var(--accent-glow);
            overflow: hidden; display: flex; align-items: center; justify-content: center;
            transition: border-color 0.2s, background 0.2s;
        }

        .nav-brand-logo img { 
            width: 100%; height: 100%; object-fit: cover; 
            filter: brightness(0) invert(1);
        }

        .nav-brand:hover .nav-brand-logo {
            border-color: var(--accent); background: var(--accent-dim);
        }

        .nav-brand-text { line-height: 1.1; }

        .nav-brand-name {
            display: block;
            font-size: 13px; font-weight: 800; letter-spacing: 0.06em;
            text-transform: uppercase; color: var(--fg);
        }

        .nav-brand-sub {
            display: block;
            font-size: 9.5px; font-weight: 700; letter-spacing: 0.18em;
            text-transform: uppercase; color: var(--accent);
        }

        .nav-btn-ghost {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 18px; border-radius: 3px;
            font-family: var(--font-sans); font-size: 11.5px; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase;
            color: var(--fg-mid); text-decoration: none;
            border: 1px solid var(--border);
            background: transparent;
            transition: color 0.2s, border-color 0.2s, background 0.2s;
        }

        .nav-btn-ghost:hover {
            color: var(--fg); border-color: rgba(255,255,255,0.18);
            background: rgba(255,255,255,0.04); text-decoration: none;
        }

        .nav-btn-ghost i { font-size: 10px; }

        .spacer {
            height: var(--nav-h);
        }

        /* ============================================================
           REGISTER CONTAINER
           ============================================================ */
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
            max-width: 480px;
            width: 100%;
            background: var(--bg-glass);
            backdrop-filter: blur(var(--blur)) saturate(160%);
            -webkit-backdrop-filter: blur(var(--blur)) saturate(160%);
            border: 1px solid var(--border);
            border-radius: 4px;
            position: relative;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .auth-card:hover {
            border-color: var(--border-acc);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(0,0,0,0.2);
        }

        /* Top accent line */
        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg,
                transparent 0%,
                rgba(245,158,11,0.45) 30%,
                rgba(245,158,11,0.7) 50%,
                rgba(245,158,11,0.45) 70%,
                transparent 100%);
            pointer-events: none;
        }

        /* Card Header */
        .auth-header {
            padding: 32px 32px 16px;
            border-bottom: 1px solid var(--border);
            position: relative;
            text-align: center;
        }

        .auth-issue {
            font-family: var(--font-serif);
            font-size: 11px;
            color: var(--accent);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            display: block;
            margin-bottom: 12px;
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
            font-size: 14px;
            margin: 0;
            letter-spacing: 0.02em;
        }

        /* Card Body */
        .auth-body {
            padding: 32px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

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
            transition: all 0.22s ease;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 3px;
        }

        .input-group:focus-within {
            border-color: var(--border-acc);
            background: var(--accent-glow);
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
            font-weight: 400;
            font-style: italic;
        }

        /* Error Message */
        .error-message {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            padding: 14px 16px;
            margin-bottom: 24px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.02em;
            border-radius: 3px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-message i {
            color: #fca5a5;
            font-size: 14px;
        }

        /* Success Message */
        .success-message {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
            padding: 14px 16px;
            margin-bottom: 24px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.02em;
            border-radius: 3px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .success-message i {
            color: #6ee7b7;
            font-size: 14px;
        }

        /* Register Button */
        .btn-register {
            width: 100%;
            background: var(--accent);
            color: #0d0d0d;
            border: none;
            padding: 14px 24px;
            font-weight: 800;
            font-size: 12px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: all 0.22s ease;
            margin-top: 16px;
            cursor: pointer;
            font-family: var(--font-sans);
        }

        .btn-register:hover {
            background: #fbbf24;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(245,158,11,0.28);
            color: #0d0d0d;
        }

        .btn-register i {
            font-size: 11px;
            transition: transform 0.22s ease;
        }

        .btn-register:hover i {
            transform: translateX(4px);
        }

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
            letter-spacing: 0.02em;
            transition: all 0.22s ease;
            position: relative;
        }

        .auth-links a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--accent);
            transform: scaleX(0);
            transition: transform 0.22s ease;
        }

        .auth-links a:hover {
            color: #fbbf24;
        }

        .auth-links a:hover::after {
            transform: scaleX(1);
        }

        /* Password Hint */
        .password-hint {
            font-size: 9px;
            color: var(--fg-low);
            margin-top: 6px;
            letter-spacing: 0.02em;
        }

        .password-hint i {
            color: var(--accent);
            margin-right: 4px;
            font-size: 8px;
        }

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

        .auth-bottom-bar i {
            font-size: 4px;
            color: var(--accent);
        }

        /* Responsive */
        @media (max-width: 576px) {
            .auth-header h3 {
                font-size: 36px;
            }
            
            .auth-body {
                padding: 24px;
            }
            
            .auth-header {
                padding: 24px 24px 12px;
            }
            
            .auth-card {
                margin: 0 16px;
            }
        }

        /* Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            .glow-side,
            .scanline,
            .btn-register:hover,
            .auth-card:hover {
                animation: none !important;
                transform: none !important;
            }
        }
    </style>
</head>

<body>

    <!-- Background Elements -->
    <div class="glow-top" aria-hidden="true"></div>
    <div class="glow-side" aria-hidden="true"></div>
    <div class="scanline" aria-hidden="true"></div>
    <div class="issue-watermark" aria-hidden="true">03</div>

    <!-- Navigation - Sama persis dengan navbar.php -->
    <nav class="nav-glass" id="mainNav">
        <div class="nav-inner">
            <!-- Brand -->
            <a class="nav-brand" href="index.php">
                <div class="nav-brand-logo">
                    <img src="assets/img/logo-white.png" alt="Logo SMKN 7">
                </div>
                <div class="nav-brand-text">
                    <span class="nav-brand-name">SMK Negeri 7</span>
                    <span class="nav-brand-sub">Stemba Parking</span>
                </div>
            </a>

            <!-- Empty nav-links for spacing -->
            <div style="flex: 1;"></div>

            <!-- Back button sebagai ghost btn -->
            <a href="index.php" class="nav-btn-ghost">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </nav>

    <!-- Spacer -->
    <div class="spacer" aria-hidden="true"></div>

    <!-- Register Form -->
    <section class="auth-container">
        <div class="auth-card" data-aos="fade-up" data-aos-duration="800">
            
            <div class="auth-header">
                <h3>Daftar</h3>
                <p>Buat akun Stemba Parking baru</p>
            </div>

            <div class="auth-body">
                
                <?php if (!empty($error)): ?>
                    <div class="error-message" data-aos="fade-in" data-aos-duration="300">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="success-message" data-aos="fade-in" data-aos-duration="300">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $success; ?>
                        <br>
                        
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group" data-aos="fade-up" data-aos-delay="100">
                        <label for="username" class="form-label">
                            Username
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="contoh: johndoe" required>
                        </div>
                    </div>

                    <div class="form-group" data-aos="fade-up" data-aos-delay="120">
                        <label for="email" class="form-label">
                            Email
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="contoh: john@example.com" required>
                        </div>
                    </div>

                    <div class="form-group" data-aos="fade-up" data-aos-delay="140">
                        <label for="password" class="form-label">
                            Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Minimal 6 karakter" required>
                        </div>
                        <div class="password-hint">
                            <i class="fas fa-circle"></i> Minimal 6 karakter
                        </div>
                    </div>

                    <div class="form-group" data-aos="fade-up" data-aos-delay="160">
                        <label for="confirm_password" class="form-label">
                            Konfirmasi Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-register" data-aos="fade-up" data-aos-delay="200">
                        <span>Buat Akun</span>
                        <i class="fa-solid fa-user-plus"></i>
                    </button>
                </form>

                <div class="auth-links" data-aos="fade-up" data-aos-delay="250">
                    <p>Sudah punya akun? <a href="login.php">Login di sini →</a></p>
                </div>

                <div class="auth-bottom-bar" data-aos="fade-up" data-aos-delay="300">
                    <span>SISTEM PENDATAAN</span>
                    <i class="fas fa-circle"></i>
                    <span>KENDARAAN SISWA</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        (function() {
            'use strict';
            
            // Initialize AOS
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof AOS !== 'undefined') {
                    AOS.init({
                        once: true,
                        offset: 20,
                        easing: 'ease-out-quart',
                        duration: 700
                    });
                }
            });

            // Scroll effect untuk navbar (sama dengan navbar.php)
            const nav = document.getElementById('mainNav');
            let ticking = false;
            
            window.addEventListener('scroll', function () {
                if (!ticking) {
                    requestAnimationFrame(function () {
                        nav.classList.toggle('nav-scrolled', window.scrollY > 30);
                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });

            // Parallax untuk issue watermark
            const issue = document.querySelector('.issue-watermark');
            if (issue) {
                let tickingParallax = false;
                window.addEventListener('scroll', () => {
                    if (!tickingParallax) {
                        requestAnimationFrame(() => {
                            issue.style.transform = `translateY(${window.scrollY * 0.05}px)`;
                            tickingParallax = false;
                        });
                        tickingParallax = true;
                    }
                }, { passive: true });
            }

            // Real-time password match validation (optional)
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            if (password && confirmPassword) {
                function validatePassword() {
                    if (confirmPassword.value.length > 0) {
                        if (password.value === confirmPassword.value) {
                            confirmPassword.style.borderBottom = '2px solid #10b981';
                        } else {
                            confirmPassword.style.borderBottom = '2px solid #ef4444';
                        }
                    } else {
                        confirmPassword.style.borderBottom = 'none';
                    }
                }

                password.addEventListener('keyup', validatePassword);
                confirmPassword.addEventListener('keyup', validatePassword);
            }
        })();
    </script>

</body>
</html> 