<?php
include '../connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query  = "UPDATE tb_pesanan SET status_pesanan = 'selesai' WHERE id_pesanan = '$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>alert('Pesanan telah siap saji!'); window.location='../../dapur.php';</script>";
    } else {
        echo "Gagal memperbarui status pesanan: " . mysqli_error($koneksi);
    }
}
