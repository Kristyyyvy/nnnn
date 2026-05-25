<?php 
include '../connect.php';

if (isset($_POST['update_menu'])) {
    $id = $_POST['id'];
    $nama_menu = mysqli_real_escape_string($koneksi, $_POST['nama_menu']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);

    $query = "UPDATE tb_menu SET 
              nama_menu = '$nama_menu', 
              kategori = '$kategori', 
              harga = '$harga', 
              stok = '$stok' 
              WHERE id_menu = '$id'";
    
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>alert('Menu berhasil diperbarui!'); window.location='../../admin.php';</script>";
    } else {
        echo "Gagal memperbarui menu: " . mysqli_error($koneksi);
    }
}
?>
