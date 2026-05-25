<?php
ob_start();
session_start();
include 'connect.php';

// Deteksi base URL secara dinamis
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'];
$basePath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
$baseUrl  = $protocol . '://' . $host . $basePath;

function redirectTo($url) {
    header("Location: " . $url);
    echo "<script>window.location.href='" . $url . "';</script>";
    echo "<noscript><meta http-equiv='refresh' content='0;url=" . $url . "'></noscript>";
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query  = "SELECT * FROM tb_user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($koneksi, $query);
    $data   = mysqli_fetch_assoc($result);

    if ($result && mysqli_num_rows($result) > 0) {
        $role = strtolower(trim($data['role']));
        $_SESSION['username'] = $data['username'];
        $_SESSION['role']     = $role;
        if ($role === 'admin') {
            redirectTo($baseUrl . "/admin.php");
        } elseif ($role === 'kasir') {
            redirectTo($baseUrl . "/kasir.php");
        } elseif ($role === 'dapur') {
            redirectTo($baseUrl . "/dapur.php");
        } else {
            redirectTo($baseUrl . "/index.php?error=role");
        }
    } else {
        redirectTo($baseUrl . "/index.php?error=1");
    }
} else {
    // Akses langsung tanpa POST — balik ke login
    redirectTo($baseUrl . "/index.php");
}

