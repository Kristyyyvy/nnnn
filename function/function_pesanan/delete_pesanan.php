<?php 
include '../connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    
    $details = mysqli_query($koneksi, "SELECT * FROM tb_detail_pesanan WHERE id_pesanan = '$id'");
    while($row = mysqli_fetch_assoc($details)){
        $id_menu = $row['id_menu'];
        $jumlah = $row['jumlah'];
        mysqli_query($koneksi, "UPDATE tb_menu SET stok = stok + $jumlah WHERE id_menu = '$id_menu'");
    }

    
    mysqli_query($koneksi, "DELETE FROM tb_detail_pesanan WHERE id_pesanan = '$id'");

    
    $result = mysqli_query($koneksi, "DELETE FROM tb_pesanan WHERE id_pesanan = '$id'");

    if ($result) {
        echo "<script>alert('Pesanan berhasil dibatalkan dan stok dikembalikan!'); window.location='../../admin.php';</script>";
    } else {
        echo "Gagal membatalkan pesanan: " . mysqli_error($koneksi);
    }
}
?>
