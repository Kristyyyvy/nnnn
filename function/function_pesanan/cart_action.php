<?php 
session_start();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'add') {
        $id = intval($_GET['id'] ?? 0);
        $nama = htmlspecialchars(trim($_GET['nama'] ?? ''), ENT_QUOTES, 'UTF-8');
        $harga = intval($_GET['harga'] ?? 0);

        if ($id <= 0 || empty($nama) || $harga <= 0) {
            header("Location: ../../kasir.php");
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['jumlah']++;
        } else {
            $_SESSION['cart'][$id] = [
                'nama' => $nama,
                'harga' => $harga,
                'jumlah' => 1
            ];
        }
    } else if ($_GET['action'] == 'remove') {
        $id = intval($_GET['id'] ?? 0);
        unset($_SESSION['cart'][$id]);
    } else if ($_GET['action'] == 'clear') {
        unset($_SESSION['cart']);
    }
    header("Location: ../../kasir.php");
    exit;
}
?>
