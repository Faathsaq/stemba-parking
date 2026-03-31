<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

<<<<<<< HEAD
$host    = 'localhost';
$db      = 'stembaaspirasi';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
=======
$host = 'localhost';
$dbname = 'stemba_parking';
$username = 'stemba_user';
$password = 'stemba123';
>>>>>>> 259f585721ac34a9cbd5c11af5e25d1d037ca953

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Koneksi gagal: ' . $e->getMessage());
}
?>