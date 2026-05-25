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
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        redirectTo($baseUrl . "/index.php?error=1");
    }

    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM tb_user WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data   = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Verifikasi password (mendukung hash dan plain text untuk backward compat)
    $password_valid = false;
    if ($data) {
        if (password_verify($password, $data['password'])) {
            $password_valid = true;
        } elseif ($data['password'] === $password) {
            // Backward compatibility: plain text password
            // Auto-hash password lama agar lebih aman
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $update_stmt = mysqli_prepare($koneksi, "UPDATE tb_user SET password = ? WHERE id_user = ?");
            mysqli_stmt_bind_param($update_stmt, "si", $hashed, $data['id_user']);
            mysqli_stmt_execute($update_stmt);
            mysqli_stmt_close($update_stmt);
            $password_valid = true;
        }
    }

    if ($password_valid) {
        // Regenerate session ID untuk mencegah session fixation
        session_regenerate_id(true);
        
        $role = strtolower(trim($data['role']));
        $_SESSION['username'] = $data['username'];
        $_SESSION['role']     = $role;
        $_SESSION['id_user']  = $data['id_user'];
        
        if ($role === 'admin') {
            redirectTo($baseUrl . "/admin.php");
        } elseif ($role === 'kasir') {
            redirectTo($baseUrl . "/kasir.php");
        } elseif ($role === 'dapur') {
            redirectTo($baseUrl . "/dapur.php");
        } elseif ($role === 'owner') {
            redirectTo($baseUrl . "/owner.php");
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
