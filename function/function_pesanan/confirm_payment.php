<?php 
include '../auth.php';
checkRole(['kasir', 'admin']);
include '../connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = mysqli_prepare($koneksi, "UPDATE tb_pesanan SET status_bayar = 'lunas' WHERE id_pesanan = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        echo "<script>alert('Pembayaran berhasil dikonfirmasi!'); window.location='../../kasir.php';</script>";
    } else {
        echo "<script>alert('Gagal mengonfirmasi pembayaran!'); window.location='../../kasir.php';</script>";
    }
} else {
    header("Location: ../../kasir.php");
    exit;
}
?>
