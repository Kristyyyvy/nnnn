<?php 
// function/function_warehouse/delete_bahan.php
include '../auth.php';
checkRole(['admin']);
include '../connect.php';

if (isset($_GET['id'])) {
    $id_bahan = intval($_GET['id']);

    // Cek apa bahan ini dipake di resep
    $cek = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tb_resep WHERE id_bahan = $id_bahan");
    $row = mysqli_fetch_assoc($cek);

    if ($row['total'] > 0) {
        echo "<script>alert('Bahan tidak bisa dihapus karena masih dipakai di resep menu!'); window.location='../../warehouse.php';</script>";
        exit;
    }

    // hapus bahan
    $stmt = mysqli_prepare($koneksi, "DELETE FROM tb_bahan WHERE id_bahan = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_bahan);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        echo "<script>alert('Bahan berhasil dihapus!'); window.location='../../warehouse.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus bahan!'); window.location='../../warehouse.php';</script>";
    }
} else {
    header("Location: ../../warehouse.php");
    exit;
}
?>