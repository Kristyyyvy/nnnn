<?php 
include '../connect.php';

if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    
    // Cek klo pw dh updt
    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($koneksi, $_POST['password']);
        $query = "UPDATE tb_user SET 
                  username = '$username', 
                  password = '$password', 
                  role = '$role' 
                  WHERE id_user = '$id'";
    } else {
        $query = "UPDATE tb_user SET 
                  username = '$username', 
                  role = '$role' 
                  WHERE id_user = '$id'";
    }
    
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>alert('User berhasil diperbarui!'); window.location='../../admin.php';</script>";
    } else {
        echo "Gagal memperbarui user: " . mysqli_error($koneksi);
    }
}
?>
