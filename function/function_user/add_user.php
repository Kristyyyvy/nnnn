<?php 
include '../connect.php';

if(isset($_POST['add_user'])){
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);

    $query = "INSERT INTO tb_user (username, password, role) VALUES ('$username', '$password', '$role')";
    $result = mysqli_query($koneksi, $query);

    if($result){
        echo "<script>alert('User berhasil ditambahkan!'); window.location='../../index.php';</script>";
    } else {
        echo "Data gagal ditambahkan: " . mysqli_error($koneksi);
    }
}
?>