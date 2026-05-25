<?php
include '../auth.php';
checkRole(['admin']);
include '../connect.php';

if (isset($_POST['add_meja'])) {
    $nomor_meja = intval($_POST['nomor_meja'] ?? 0);

    if ($nomor_meja <= 0) {
        echo "<script>alert('Nomor meja harus lebih besar dari 0!'); window.location='../../admin.php?tab=meja';</script>";
        exit;
    }

    // Cek apakah nomor meja sudah terdaftar
    $check = mysqli_query($koneksi, "SELECT * FROM tb_meja WHERE nomor_meja = $nomor_meja");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Nomor meja sudah terdaftar!'); window.location='../../admin.php?tab=meja';</script>";
        exit;
    }

    // Insert ke database
    $query = "INSERT INTO tb_meja (nomor_meja, status) VALUES ($nomor_meja, 'kosong')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>alert('Meja berhasil ditambahkan!'); window.location='../../admin.php?tab=meja';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan meja!'); window.location='../../admin.php?tab=meja';</script>";
    }
} else {
    header("Location: ../../admin.php?tab=meja");
}
