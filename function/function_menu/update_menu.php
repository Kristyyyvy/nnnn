<?php 
include '../auth.php';
checkRole(['admin']);
include '../connect.php';

if (isset($_POST['update_menu'])) {
    $id = intval($_POST['id'] ?? 0);
    $nama_menu = trim($_POST['nama_menu'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $harga = intval($_POST['harga'] ?? 0);
    $stok = intval($_POST['stok'] ?? 0);

    // Validasi input
    if ($id <= 0 || empty($nama_menu) || empty($kategori)) {
        echo "<script>alert('Data tidak valid!'); window.location='../../admin.php?tab=menu';</script>";
        exit;
    }

    if (!in_array($kategori, ['makanan', 'minuman'])) {
        echo "<script>alert('Kategori tidak valid!'); window.location='../../admin.php?tab=menu';</script>";
        exit;
    }

    if ($harga <= 0) {
        echo "<script>alert('Harga harus lebih besar dari 0!'); window.location='../../admin.php?tab=menu';</script>";
        exit;
    }

    $stmt = mysqli_prepare($koneksi, "UPDATE tb_menu SET nama_menu = ?, kategori = ?, harga = ?, stok = ? WHERE id_menu = ?");
    mysqli_stmt_bind_param($stmt, "ssiii", $nama_menu, $kategori, $harga, $stok, $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        echo "<script>alert('Menu berhasil diperbarui!'); window.location='../../admin.php?tab=menu';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui menu!'); window.location='../../admin.php?tab=menu';</script>";
    }
} else {
    header("Location: ../../admin.php");
    exit;
}
?>
