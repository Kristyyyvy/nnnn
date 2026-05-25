<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'function/auth.php';
checkRole(['owner']);
include 'function/connect.php';
$page_title = 'Dashboard Owner';
$active = 'owner';
include '_layout.php';
?>
<?php
$omzet = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT IFNULL(SUM(total_harga),0) AS n FROM tb_pesanan WHERE status_bayar='lunas'"))['n'];
$trx = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS n FROM tb_pesanan"))['n'];
$belum = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS n FROM tb_pesanan WHERE status_bayar='belum_bayar'"))['n'];
$tot_menu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS n FROM tb_menu"))['n'];
?>
<div class="stat-grid">
  <div class="stat-box">
    <div class="stat-label">Omzet Hari Ini</div>
    <div class="stat-value">Rp <?= number_format($omzet,0,',','.') ?></div>
  </div>
  <div class="stat-box">
    <div class="stat-label">Transaksi Hari Ini</div>
    <div class="stat-value dark"><?= $trx ?></div>
  </div>
  <div class="stat-box">
    <div class="stat-label">Belum Bayar</div>
    <div class="stat-value blue"><?= $belum ?></div>
  </div>
  <div class="stat-box">
    <div class="stat-label">Total Menu</div>
    <div class="stat-value dark"><?= $tot_menu ?></div>
  </div>
</div>
<div style="margin-top:20px;">
    <a href="owner_report.php" class="btn btn-primary">Laporan Harian/Mingguan</a>
</div>
<?php include '_layout_end.php'; ?>
