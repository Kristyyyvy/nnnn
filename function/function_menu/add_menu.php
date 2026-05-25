<?php 
//function/function_menu/add_menu.php
include '../auth.php';
checkRole(['admin']);
include '../connect.php';

if (isset($_POST['add_menu'])) {
    $nama_menu = trim($_POST['nama_menu'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $harga = intval($_POST['harga'] ?? 0);
    $stok = intval($_POST['stok'] ?? 0);

    // Validasi input
    if (empty($nama_menu) || empty($kategori)) {
        echo "<script>alert('Nama menu dan kategori wajib diisi!'); window.location='../../admin.php?tab=menu';</script>";
        exit;
    }

    // Validasi kategori yang diizinkan
    if (!in_array($kategori, ['makanan', 'minuman'])) {
        echo "<script>alert('Kategori tidak valid!'); window.location='../../admin.php?tab=menu';</script>";
        exit;
    }

    if ($harga <= 0) {
        echo "<script>alert('Harga harus lebih besar dari 0!'); window.location='../../admin.php?tab=menu';</script>";
        exit;
    }

    if ($stok < 0) {
        echo "<script>alert('Stok tidak boleh negatif!'); window.location='../../admin.php?tab=menu';</script>";
        exit;
    }

    $stmt = mysqli_prepare($koneksi, "INSERT INTO tb_menu (nama_menu, kategori, harga, stok) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssii", $nama_menu, $kategori, $harga, $stok);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        echo "<script>alert('Menu berhasil ditambahkan!'); window.location='../../admin.php?tab=menu';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan menu!'); window.location='../../admin.php?tab=menu';</script>";
    }
} else {
    header("Location: ../../admin.php");
    exit;
}
?>
