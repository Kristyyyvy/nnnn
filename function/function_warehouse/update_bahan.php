<?php
// function/function_warehouse/edit_bahan.php
include '../auth.php';
checkRole(['admin']);
include '../connect.php';

if (isset($_POST['update_bahan'])) {
    $id_bahan     = intval($_POST['id_bahan'] ?? 0);
    $nama_bahan   = trim($_POST['nama_bahan'] ?? '');
    $kategori     = trim($_POST['kategori'] ?? '');
    $stok_minimum = floatval($_POST['stok_minimum'] ?? 0);
    $satuan       = trim($_POST['satuan'] ?? '');
    $harga_modal  = floatval($_POST['harga_modal'] ?? 0);

    // input
    if ($id_bahan <= 0 || empty($nama_bahan) || empty($satuan)) {
        echo "<script>alert('Data tidak valid!'); window.location='../../warehouse.php';</script>";
        exit;
    }

    // Validasi satuan yang diizinkan
    if (!in_array($satuan, ['gram', 'ml', 'pcs', 'kg', 'liter'])) {
        echo "<script>alert('Satuan tidak valid!'); window.location='../../warehouse.php';</script>";
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

    // Stok tidak ikut diupdate di sini — stok hanya berubah lewat stok_masuk, stok_keluar, atau auto_kurang_stok
    $stmt = mysqli_prepare($koneksi, "UPDATE tb_bahan SET nama_bahan = ?, kategori = ?, stok_minimum = ?, satuan = ?, harga_modal = ? WHERE id_bahan = ?");
    mysqli_stmt_bind_param($stmt, "ssdsdi", $nama_bahan, $kategori, $stok_minimum, $satuan, $harga_modal, $id_bahan);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        echo "<script>alert('Bahan berhasil diperbarui!'); window.location='../../warehouse.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui bahan!'); window.location='../../warehouse.php';</script>";
    }
} else {
    header("Location: ../../warehouse.php");
    exit;
}
