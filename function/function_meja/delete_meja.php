<?php
include '../auth.php';
checkRole(['admin']);
include '../connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $result = mysqli_query($koneksi, "DELETE FROM tb_meja WHERE id_meja = $id");

    if ($result) {
        echo "<script>alert('Meja berhasil dihapus!'); window.location='../../admin.php?tab=meja';</script>";
    } else {
        echo "<script>alert('Gagal menghapus meja!'); window.location='../../admin.php?tab=meja';</script>";
    }
} else {
    header("Location: ../../admin.php?tab=meja");
}
