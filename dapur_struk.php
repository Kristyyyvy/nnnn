<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'function/auth.php';
// Allow kasir, owner, admin, dapur roles
checkRole(['kasir', 'owner', 'admin', 'dapur']);
include 'function/connect.php';

$id = intval($_GET['id'] ?? 0);
$pesanan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_pesanan, no_meja FROM tb_pesanan WHERE id_pesanan=$id"));
if (!$pesanan) {
    echo "Pesanan tidak ditemukan.";
    exit;
}
// Fetch only id_menu and quantity
$details = mysqli_query($koneksi, "SELECT d.id_menu, d.jumlah FROM tb_detail_pesanan d WHERE d.id_pesanan=$id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Dapur #<?= $pesanan['id_pesanan'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .info { font-size: 14px; margin-bottom: 8px; }
        .item { font-size: 13px; margin-bottom: 4px; }
        hr { border: none; border-top: 1px dashed #000; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="info"><strong>No. Pesanan:</strong> <?= $pesanan['id_pesanan'] ?></div>
    <div class="info"><strong>Meja:</strong> <?= $pesanan['no_meja'] ?></div>
    <hr>
    <?php while ($row = mysqli_fetch_assoc($details)): ?>
        <div class="item">ID Menu: <?= $row['id_menu'] ?> — Qty: <?= $row['jumlah'] ?></div>
    <?php endwhile; ?>
</body>
</html>
