<?php
session_start();
include 'connect.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query  = "SELECT * FROM tb_user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($koneksi, $query);
    $data   = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['username'] = $data['username'];
        $_SESSION['role']     = $data['role'];

        if ($data['role'] == 'admin') {
            header("Location: ../admin.php");
        } else if ($data['role'] == 'kasir') {
            header("Location: ../kasir.php");
        } else if ($data['role'] == 'dapur') {
            header("Location: ../dapur.php");
        }
    } else {
        echo "<script>alert('Username atau Password salah!'); window.location='../index.php';</script>";
    }
}
