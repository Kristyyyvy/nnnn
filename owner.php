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
<hr style="border-top: 1px dashed #e8d5b8; margin: 24px 0;">

<div class="kc-card" style="margin-bottom:20px;">
    <div class="kc-card-header">
        <span><i class='bx bx-bar-chart-square'></i> Filter Laporan Harian/Mingguan</span>
    </div>
    <div class="kc-card-body">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <select name="type" class="form-select form-select-sm" required>
                    <option value="day" <?= (isset($_GET['type']) && $_GET['type']=='day') ? 'selected' : '' ?>>Hari</option>
                    <option value="week" <?= (isset($_GET['type']) && $_GET['type']=='week') ? 'selected' : '' ?>>Minggu</option>
                </select>
            </div>
            <div class="col-auto" id="day-input" style="display:none;">
                <input type="date" name="date" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['date'] ?? date('Y-m-d')) ?>" />
            </div>
            <div class="col-auto" id="week-input" style="display:none;">
                <div class="d-flex gap-2">
                    <input type="number" name="year" min="2000" max="2100" placeholder="Tahun" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['year'] ?? date('Y')) ?>" />
                    <input type="number" name="week" min="1" max="53" placeholder="Minggu" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['week'] ?? date('W')) ?>" />
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn-kc btn-kc-sm">Tampilkan</button>
            </div>
        </form>
    </div>
</div>

<?php
if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $where = "";
    $params = [];

    if ($type === 'day' && !empty($_GET['date'])) {
        $date = $_GET['date'];
        $where = "DATE(tgl_pesanan) = ?";
        $params[] = $date;
    } elseif ($type === 'week' && !empty($_GET['year']) && !empty($_GET['week'])) {
        $year = (int)$_GET['year'];
        $week = (int)$_GET['week'];
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $start = $dto->format('Y-m-d');
        $end = $dto->modify('+6 days')->format('Y-m-d');
        $where = "DATE(tgl_pesanan) BETWEEN ? AND ?";
        $params[] = $start;
        $params[] = $end;
    }

    if ($where) {
        $stmt = mysqli_prepare(
            $koneksi,
            "SELECT COUNT(*) AS total_orders, IFNULL(SUM(total_harga),0) AS omzet FROM tb_pesanan WHERE $where AND status_bayar='lunas'"
        );
        if ($type === 'day') {
            mysqli_stmt_bind_param($stmt, "s", $params[0]);
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $params[0], $params[1]);
        }
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
        ?>
        <div class="kc-card">
            <div class="kc-card-header">
                <span><i class='bx bx-receipt'></i> Laporan <?= $type === 'day' ? 'Harian' : 'Mingguan' ?></span>
            </div>
            <div class="kc-card-body">
                <div class="mb-3">
                    <?php if ($type === 'day'): ?>
                        <div style="font-size: 12px; color: #a07850;">Tanggal: <strong><?= htmlspecialchars($date) ?></strong></div>
                    <?php else: ?>
                        <div style="font-size: 12px; color: #a07850;">Rentang: <strong><?= htmlspecialchars($start) ?> s/d <?= htmlspecialchars($end) ?></strong> (Minggu ke-<?= $week ?>, <?= $year ?>)</div>
                    <?php endif; ?>
                </div>
                <div class="stat-grid" style="grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 14px;">
                    <div class="stat-box" style="background: #fdf5ec;">
                        <div class="stat-label">Total Transaksi</div>
                        <div class="stat-value dark"><?= $data['total_orders'] ?></div>
                    </div>
                    <div class="stat-box" style="background: #fdf5ec;">
                        <div class="stat-label">Total Omzet</div>
                        <div class="stat-value">Rp <?= number_format($data['omzet'],0,',','.') ?></div>
                    </div>
                </div>
                <button onclick="window.print()" class="btn-kc-outline"><i class='bx bx-printer'></i> Cetak Laporan</button>
            </div>
        </div>
        <?php
    }
}
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const typeSelect = document.querySelector('select[name="type"]');
    const dayInput = document.getElementById('day-input');
    const weekInput = document.getElementById('week-input');

    function toggleInputs() {
        if (typeSelect.value === 'day') {
            dayInput.style.display = 'block';
            weekInput.style.display = 'none';
        } else if (typeSelect.value === 'week') {
            dayInput.style.display = 'none';
            weekInput.style.display = 'block';
        }
    }

    typeSelect.addEventListener('change', toggleInputs);
    toggleInputs();
});
</script>

<?php include '_layout_end.php'; ?>
