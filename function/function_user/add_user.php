<?php 
include '../auth.php';
checkRole(['admin']);
include '../connect.php';

if (isset($_POST['add_user'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');

    // Validasi input
    if (empty($username) || empty($password) || empty($role)) {
        echo "<script>alert('Semua field wajib diisi!'); window.location='../../admin.php?tab=user';</script>";
        exit;
    }

    // Validasi role yang diizinkan
    $allowed_roles = ['admin', 'kasir', 'dapur', 'owner'];
    if (!in_array($role, $allowed_roles)) {
        echo "<script>alert('Role tidak valid!'); window.location='../../admin.php?tab=user';</script>";
        exit;
    }

    // Cek username sudah ada
    $check = mysqli_prepare($koneksi, "SELECT id_user FROM tb_user WHERE username = ?");
    mysqli_stmt_bind_param($check, "s", $username);
    mysqli_stmt_execute($check);
    $check_result = mysqli_stmt_get_result($check);
    if (mysqli_num_rows($check_result) > 0) {
        mysqli_stmt_close($check);
        echo "<script>alert('Username sudah digunakan!'); window.location='../../admin.php?tab=user';</script>";
        exit;
    }
    mysqli_stmt_close($check);

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($koneksi, "INSERT INTO tb_user (username, password, role) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $username, $hashed_password, $role);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        echo "<script>alert('User berhasil ditambahkan!'); window.location='../../admin.php?tab=user';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan user!'); window.location='../../admin.php?tab=user';</script>";
    }
} else {
    header("Location: ../../admin.php");
    exit;
}
?>