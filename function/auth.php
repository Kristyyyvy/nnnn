<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkRole($allowedRoles) {
    if (!isset($_SESSION['role'])) {
        header("Location: index.php");
        exit();
    }

    // Role owner has access to all pages/features
    if ($_SESSION['role'] === 'owner') {
        return;
    }

    if (!in_array($_SESSION['role'], $allowedRoles)) {
        echo "<script>alert('Anda tidak memiliki akses ke halaman ini!'); window.location='index.php';</script>";
        exit();
    }
}
?>
