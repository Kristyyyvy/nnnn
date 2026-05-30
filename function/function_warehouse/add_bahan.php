<?php
// function/function_warehouse/add_bahan.php
include '../auth.php';
checkRole(['admin']);
include '../connect.php';

if (isset($_POST['add_bahan'])) {
    $nama_bahan   = trim($_POST['nama_bahan'] ?? '');
    $kategori     = trim($_POST['kategori'] ?? '');
    $stok         = floatval($_POST['stok'] ?? 0);
    $stok_minimum = floatval($_POST['stok_minimum'] ?? 0);
    $satuan       = trim($_POST['satuan'] ?? '');
    $harga_modal  = floatval($_POST['harga_modal'] ?? 0);

    // wajib input
    if (empty($nama_bahan) || empty($satuan)) {
        echo "<script>alert('Nama bahan dan satuan wajib diisi!'); window.location='../../warehouse.php';</script>";
        exit;
    }

    // Validasi satuan yang diizinkan
    if (!in_array($satuan, ['gram', 'ml', 'pcs', 'kg', 'liter'])) {
        echo "<script>alert('Satuan tidak valid!'); window.location='../../warehouse.php';</script>";
        exit;
    }

    if ($stok < 0) {
        echo "<script>alert('Stok tidak boleh negatif!'); window.location='../../warehouse.php';</script>";
        exit;
    }

    if ($stok_minimum < 0) {
        echo "<script>alert('Stok minimum tidak boleh negatif!'); window.location='../../warehouse.php';</script>";
        exit;
    }

    if ($harga_modal < 0) {
        echo "<script>alert('Harga modal tidak boleh negatif!'); window.location='../../warehouse.php';</script>";
        exit;
    }

    // Insert bahan baru
    $stmt = mysqli_prepare($koneksi, "INSERT INTO tb_bahan (nama_bahan, kategori, stok, stok_minimum, satuan, harga_modal) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssddsd", $nama_bahan, $kategori, $stok, $stok_minimum, $satuan, $harga_modal);
    $result = mysqli_stmt_execute($stmt);
    $id_bahan_baru = mysqli_insert_id($koneksi);
    mysqli_stmt_close($stmt);

    if ($result) {
        // Kalau stok awal > 0, catat ke tb_stok_log 'masuk'
        if ($stok > 0) {
            $keterangan = "Stok awal saat penambahan bahan";
            $stmt_log = mysqli_prepare($koneksi, "INSERT INTO tb_stok_log (id_bahan, jenis, jumlah, keterangan) VALUES (?, 'masuk', ?, ?)");
            mysqli_stmt_bind_param($stmt_log, "ids", $id_bahan_baru, $stok, $keterangan);
            mysqli_stmt_execute($stmt_log);
            mysqli_stmt_close($stmt_log);
        }

        echo "<script>alert('Bahan berhasil ditambahkan!'); window.location='../../warehouse.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan bahan!'); window.location='../../warehouse.php';</script>";
    }
} else {
    header("Location: ../../warehouse.php");
    exit;
}
