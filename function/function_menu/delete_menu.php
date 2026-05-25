<?php 
include '../connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "DELETE FROM tb_menu WHERE id_menu = '$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>alert('Menu berhasil dihapus!'); window.location='../../admin.php';</script>";
    } else {
        echo "Gagal menghapus menu: " . mysqli_error($koneksi);
    }
}
?>
