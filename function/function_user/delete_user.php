<?php 
include '../auth.php';
checkRole(['admin']);
include '../connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Jangan izinkan hapus diri sendiri
    if (isset($_SESSION['id_user']) && $id == $_SESSION['id_user']) {
        echo "<script>alert('Tidak bisa menghapus akun sendiri!'); window.location='../../admin.php';</script>";
        exit;
    }
    
    $stmt = mysqli_prepare($koneksi, "DELETE FROM tb_user WHERE id_user = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        echo "<script>alert('User berhasil dihapus!'); window.location='../../admin.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus user!'); window.location='../../admin.php';</script>";
    }
} else {
    header("Location: ../../admin.php");
    exit;
}
?>
