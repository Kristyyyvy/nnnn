<?php 
session_start();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'add') {
        $id = $_GET['id'];
        $nama = $_GET['nama'];
        $harga = $_GET['harga'];

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
        $id = $_GET['id'];
        unset($_SESSION['cart'][$id]);
    } else if ($_GET['action'] == 'clear') {
        unset($_SESSION['cart']);
    }
    header("Location: ../../pelayanan.php"); //ini kan pelayanan udh ga ada, gmna dong td juga error eehhehee
}
?>
