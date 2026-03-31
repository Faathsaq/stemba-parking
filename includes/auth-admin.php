<?php
session_start();
require 'koneksi.php'; // sesuaikan path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin' LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id']   = $user['id'];
        $_SESSION['admin_name'] = $user['username'];
        header('Location: dashboard-admin.php');
        exit;
    } else {
        header('Location: login-admin.php?error=1');
        exit;
    }
}