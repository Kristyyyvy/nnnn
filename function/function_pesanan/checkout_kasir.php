<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../auth.php';
checkRole(['kasir']);
include '../connect.php';

// Added handling for nama_kasir and metode_bayar
$nama_kasir = $_SESSION['username'] ?? '';
$metode_bayar = $_POST['metode_bayar'] ?? '';

header('Content-Type: application/json');

$nama  = trim($_POST['nama_pelanggan'] ?? '');
$meja  = intval($_POST['no_meja'] ?? 0);
$bayar = intval($_POST['bayar'] ?? 0);
$cart  = json_decode($_POST['cart'] ?? '[]', true);

if (!$nama || $meja <= 0 || empty($cart)) {
    echo json_encode(['ok' => false, 'msg' => 'Data tidak lengkap']);
    exit;
}

$total = 0;
foreach ($cart as $item) {
    $total += $item['harga'] * $item['qty'];
}

if ($bayar < $total) {
    echo json_encode(['ok' => false, 'msg' => 'Uang bayar kurang']);
    exit;
}

mysqli_begin_transaction($koneksi);

try {
    // Ensure metode_bayar column exists (run once during deployment; optional at runtime)
    $result = mysqli_query($koneksi, "SHOW COLUMNS FROM tb_pesanan LIKE 'metode_bayar'");
    if (mysqli_num_rows($result) == 0) {
        mysqli_query($koneksi, "ALTER TABLE tb_pesanan ADD COLUMN metode_bayar ENUM('tunai','qris','transfer','kartu') NOT NULL DEFAULT 'tunai'");
    }
    // Ensure catatan column exists in tb_detail_pesanan
    $catResult = mysqli_query($koneksi, "SHOW COLUMNS FROM tb_detail_pesanan LIKE 'catatan'");
    if (mysqli_num_rows($catResult) == 0) {
        mysqli_query($koneksi, "ALTER TABLE tb_detail_pesanan ADD COLUMN catatan TEXT NULL");
    }
    // Set current timestamp for order
    $tgl = date('Y-m-d H:i:s');

    // Insert ke tb_pesanan
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO tb_pesanan (nama_pelanggan, no_meja, total_harga, status_bayar, status_pesanan, tgl_pesanan, uang_bayar, nama_kasir, metode_bayar)
         VALUES (?, ?, ?, 'lunas', 'proses', ?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, 'siisiss', $nama, $meja, $total, $tgl, $bayar, $nama_kasir, $metode_bayar);
    mysqli_stmt_execute($stmt);
    $id_pesanan = mysqli_insert_id($koneksi);
    mysqli_stmt_close($stmt);

    // Insert detail ke tb_detail_pesanan + kurangi stok
    foreach ($cart as $item) {
        $id_menu  = intval($item['id']);
        $jumlah   = intval($item['qty']);
        $subtotal = $item['harga'] * $jumlah;

        $s2 = mysqli_prepare(
            $koneksi,
            "INSERT INTO tb_detail_pesanan (id_pesanan, id_menu, jumlah, subtotal, catatan) VALUES (?, ?, ?, ?, ?)"
        );
        $catatan = $item['catatan'] ?? null;
        mysqli_stmt_bind_param($s2, 'iiiis', $id_pesanan, $id_menu, $jumlah, $subtotal, $catatan);
        mysqli_stmt_execute($s2);
        mysqli_stmt_close($s2);

        $s3 = mysqli_prepare($koneksi, "UPDATE tb_menu SET stok = stok - ? WHERE id_menu = ?");
        mysqli_stmt_bind_param($s3, 'ii', $jumlah, $id_menu);
        mysqli_stmt_execute($s3);
        mysqli_stmt_close($s3);
    }

    mysqli_commit($koneksi);

    echo json_encode([
        'ok'         => true,
        'id_pesanan' => $id_pesanan,
        'total'      => $total,
        'kembalian'  => $bayar - $total,
    ]);
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    echo json_encode(['ok' => false, 'msg' => $e->getMessage()]);
}
