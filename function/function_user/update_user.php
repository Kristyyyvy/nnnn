<?php 
include '../auth.php';
checkRole(['admin']);
include '../connect.php';

if (isset($_POST['update_user'])) {
    $id = intval($_POST['id'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $role = trim($_POST['role'] ?? '');
    
    // Validasi input
    if ($id <= 0 || empty($username) || empty($role)) {
        echo "<script>alert('Data tidak valid!'); window.location='../../admin.php?tab=user';</script>";
        exit;
    }

    // Validasi role yang diizinkan
    $allowed_roles = ['admin', 'kasir', 'dapur', 'owner'];
    if (!in_array($role, $allowed_roles)) {
        echo "<script>alert('Role tidak valid!'); window.location='../../admin.php?tab=user';</script>";
        exit;
    }

    // Cek username duplikat (kecuali user sendiri)
    $check = mysqli_prepare($koneksi, "SELECT id_user FROM tb_user WHERE username = ? AND id_user != ?");
    mysqli_stmt_bind_param($check, "si", $username, $id);
    mysqli_stmt_execute($check);
    $check_result = mysqli_stmt_get_result($check);
    if (mysqli_num_rows($check_result) > 0) {
        mysqli_stmt_close($check);
        echo "<script>alert('Username sudah digunakan oleh user lain!'); window.location='../../admin.php?tab=user';</script>";
        exit;
    }
    mysqli_stmt_close($check);

    // Cek klo pw dh updt
    if (!empty($_POST['password'])) {
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($koneksi, "UPDATE tb_user SET username = ?, password = ?, role = ? WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $username, $hashed_password, $role, $id);
    } else {
        $stmt = mysqli_prepare($koneksi, "UPDATE tb_user SET username = ?, role = ? WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $username, $role, $id);
    }
    
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        echo "<script>alert('User berhasil diperbarui!'); window.location='../../admin.php?tab=user';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui user!'); window.location='../../admin.php?tab=user';</script>";
    }
} else {
    header("Location: ../../admin.php");
    exit;
}
?>
