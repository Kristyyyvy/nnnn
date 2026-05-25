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

    
    // Ambil no_meja dari pesanan lalu update status meja menjadi kosong
    $pesanan_res = mysqli_query($koneksi, "SELECT no_meja FROM tb_pesanan WHERE id_pesanan = '$id'");
    if ($pesanan_res) {
        $pesanan_data = mysqli_fetch_assoc($pesanan_res);
        if ($pesanan_data) {
            $no_meja = intval($pesanan_data['no_meja']);
            mysqli_query($koneksi, "UPDATE tb_meja SET status = 'kosong' WHERE id_meja = $no_meja");
        }
    }

    $result = mysqli_query($koneksi, "DELETE FROM tb_pesanan WHERE id_pesanan = '$id'");

    if ($result) {
        echo "<script>alert('Pesanan berhasil dibatalkan dan stok dikembalikan!'); window.location='../../admin.php';</script>";
    } else {
        echo "Gagal membatalkan pesanan: " . mysqli_error($koneksi);
    }
}
?>
