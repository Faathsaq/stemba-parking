<?php
// index.php
include 'includes/config.php';

/* =======================
   LOGIC (JANGAN DIUBAH)
   ======================= */
try {
    $search = $_GET['search'] ?? '';
    $status_filter = $_GET['status'] ?? '';
    $date_filter = $_GET['date'] ?? '';

    $sql = "SELECT r.*, u.username 
            FROM reports r 
            JOIN users u ON r.user_id = u.id 
            WHERE 1=1";

    $params = [];

    if ($search) {
        $sql .= " AND (r.title LIKE ? OR r.description LIKE ? OR r.location LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if ($status_filter) {
        $sql .= " AND r.status = ?";
        $params[] = $status_filter;
    }

    if ($date_filter) {
        $sql .= " AND r.date_lost = ?";
        $params[] = $date_filter;
    }

    $sql .= " ORDER BY r.created_at DESC LIMIT 6";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $reports = $stmt->fetchAll();
} catch (PDOException $e) {
    $reports = [];
}

function timeAgo($timestamp)
{
    $diff = time() - strtotime($timestamp);
    if ($diff < 60) return 'Baru saja';
    if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
    if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
    return floor($diff / 86400) . ' hari lalu';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stemba Parking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<?php include 'includes/navbar.php'; ?>

<!-- CONTENT -->
<main>
    <?php include 'sections/hero.php'; ?>
    <?php include 'sections/tentang.php'; ?>
    <?php include 'sections/panduan.php'; ?>
    <?php include 'sections/alur.php'; ?>
    <?php include 'sections/kontak.php'; ?>

</main>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>