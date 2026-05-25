<?php 
//function/function_menu/add_menu.php
include '../connect.php';

if (isset($_POST['add_menu'])) {
    $nama_menu = mysqli_real_escape_string($koneksi, $_POST['nama_menu']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);

    $query = "INSERT INTO tb_menu (nama_menu, kategori, harga, stok) VALUES ('$nama_menu', '$kategori', '$harga', '$stok')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>alert('Menu berhasil ditambahkan!'); window.location='../../admin.php';</script>";
    } else {
        echo "Gagal menambahkan menu: " . mysqli_error($koneksi);
    }
}
?>
