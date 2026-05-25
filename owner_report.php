<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'function/auth.php';
checkRole(['owner']);
include 'function/connect.php';

$page_title = 'Laporan Harian/Mingguan';
$active = 'owner';
include '_layout.php';
?>

<div class="kc-card" style="margin-bottom:20px;">
    <div class="kc-card-body">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <select name="type" class="form-select" required>
                    <option value="day" <?= (isset($_GET['type']) && $_GET['type']=='day') ? 'selected' : '' ?>>Hari</option>
                    <option value="week" <?= (isset($_GET['type']) && $_GET['type']=='week') ? 'selected' : '' ?>>Minggu</option>
                </select>
            </div>
            <div class="col-auto" id="day-input" style="display:none;">
                <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>" />
            </div>
            <div class="col-auto" id="week-input" style="display:none;">
                <input type="number" name="year" min="2000" max="2100" placeholder="Tahun" class="form-control" value="<?= htmlspecialchars($_GET['year'] ?? date('Y')) ?>" />
                <input type="number" name="week" min="1" max="53" placeholder="Minggu" class="form-control" style="margin-top:4px;" value="<?= htmlspecialchars($_GET['week'] ?? date('W')) ?>" />
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
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
        // Get Monday of the given ISO week
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $start = $dto->format('Y-m-d');
        $end = $dto->modify('+6 days')->format('Y-m-d');
        $where = "DATE(tgl_pesanan) BETWEEN ? AND ?";
        $params[] = $start;
        $params[] = $end;
    

    if ($where) {
        // Prepare statement dynamically
        $stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) AS total_orders, IFNULL(SUM(total_harga),0) AS omzet FROM tb_pesanan WHERE $where AND status_bayar='lunas'");
        // Bind parameters based on type
        if ($type === 'day') {
            mysqli_stmt_bind_param($stmt, "s", $params[0]);
        } else { // week
            // Bind start and end dates for week range
            mysqli_stmt_bind_param($stmt, "ss", $params[0], $params[1]);
        }
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
        ?>
        <div class="kc-card">
            <div class="kc-card-body">
                <h5>Laporan <?= $type === 'day' ? 'Hari' : 'Minggu' ?></h5>
                <p>Total Pesanan: <?= $data['total_orders'] ?></p>
                <p>Omzet: Rp <?= number_format($data['omzet'],0,',','.') ?></p>
                <button onclick="window.print()" class="btn btn-secondary">Cetak</button>
            </div>
        </div>
        <?php
    }
}
?>

<?php include '_layout_end.php'; ?>
