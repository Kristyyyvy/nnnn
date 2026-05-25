<?php 
include '../connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "DELETE FROM tb_user WHERE id_user = '$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>alert('User berhasil dihapus!'); window.location='../../admin.php';</script>";
    } else {
        echo "Gagal menghapus user: " . mysqli_error($koneksi);
    }
}
?>
