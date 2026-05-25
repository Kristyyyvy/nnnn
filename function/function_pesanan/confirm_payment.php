<?php 
include '../connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "UPDATE tb_pesanan SET status_bayar = 'lunas' WHERE id_pesanan = '$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>alert('Pembayaran berhasil dikonfirmasi!'); window.location='../../kasir.php';</script>";
    } else {
        echo "Gagal mengonfirmasi pembayaran: " . mysqli_error($koneksi);
    }
}
?>
