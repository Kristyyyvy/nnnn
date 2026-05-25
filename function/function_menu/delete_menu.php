<?php 
include '../auth.php';
checkRole(['admin']);
include '../connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = mysqli_prepare($koneksi, "DELETE FROM tb_menu WHERE id_menu = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        echo "<script>alert('Menu berhasil dihapus!'); window.location='../../admin.php?tab=menu';</script>";
    } else {
        echo "<script>alert('Gagal menghapus menu!'); window.location='../../admin.php?tab=menu';</script>";
    }
} else {
    header("Location: ../../admin.php");
    exit;
}
?>
