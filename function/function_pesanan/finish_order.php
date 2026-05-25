<?php
include '../connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil no_meja dari pesanan lalu update status meja menjadi kosong
    $pesanan_res = mysqli_query($koneksi, "SELECT no_meja FROM tb_pesanan WHERE id_pesanan = '$id'");
    if ($pesanan_res) {
        $pesanan_data = mysqli_fetch_assoc($pesanan_res);
        if ($pesanan_data) {
            $no_meja = intval($pesanan_data['no_meja']);
            mysqli_query($koneksi, "UPDATE tb_meja SET status = 'kosong' WHERE id_meja = $no_meja");
        }
    }

    $query  = "UPDATE tb_pesanan SET status_pesanan = 'selesai' WHERE id_pesanan = '$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>alert('Pesanan telah siap saji!'); window.location='../../dapur.php';</script>";
    } else {
        echo "Gagal memperbarui status pesanan: " . mysqli_error($koneksi);
    }
}
