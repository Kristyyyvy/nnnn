<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../auth.php';
checkRole(['kasir']);
include '../connect.php';

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
    $tgl = date('Y-m-d H:i:s');

    // Insert ke tb_pesanan
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO tb_pesanan (nama_pelanggan, no_meja, total_harga, status_bayar, status_pesanan, tgl_pesanan)
         VALUES (?, ?, ?, 'lunas', 'proses', ?)"
    );
    mysqli_stmt_bind_param($stmt, 'siis', $nama, $meja, $total, $tgl);
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
            "INSERT INTO tb_detail_pesanan (id_pesanan, id_menu, jumlah, subtotal) VALUES (?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($s2, 'iiii', $id_pesanan, $id_menu, $jumlah, $subtotal);
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
