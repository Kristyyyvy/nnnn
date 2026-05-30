<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'function/auth.php';
checkRole(['owner']);
include 'function/connect.php';

$type = $_GET['type'] ?? '';
$where = "";
$params = [];
$title = "";
$period_text = "";

if ($type === 'day' && !empty($_GET['date'])) {
    $date = $_GET['date'];
    $where = "DATE(tgl_pesanan) = ?";
    $params[] = $date;
    $title = "Laporan Pendapatan Harian";
    $period_text = date('d F Y', strtotime($date));
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
    $title = "Laporan Pendapatan Mingguan";
    $period_text = date('d F Y', strtotime($start)) . " - " . date('d F Y', strtotime($end)) . " (Minggu ke-" . $week . ", " . $year . ")";
} else {
    echo "Parameter tidak valid.";
    exit;
}

// Fetch aggregate data
$stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) AS total_orders, IFNULL(SUM(total_harga),0) AS omzet FROM tb_pesanan WHERE $where AND status_bayar='lunas'");
if ($type === 'day') {
    mysqli_stmt_bind_param($stmt, "s", $params[0]);
} else {
    mysqli_stmt_bind_param($stmt, "ss", $params[0], $params[1]);
}
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$summary = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

// Fetch all order list for details
$stmt_list = mysqli_prepare($koneksi, "SELECT * FROM tb_pesanan WHERE $where AND status_bayar='lunas' ORDER BY id_pesanan ASC");
if ($type === 'day') {
    mysqli_stmt_bind_param($stmt_list, "s", $params[0]);
} else {
    mysqli_stmt_bind_param($stmt_list, "ss", $params[0], $params[1]);
}
mysqli_stmt_execute($stmt_list);
$orders = mysqli_stmt_get_result($stmt_list);
mysqli_stmt_close($stmt_list);

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?> - <?= htmlspecialchars($period_text) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1c1c17;
            background: #fff;
            padding: 40px;
            font-size: 11px;
            line-height: 1.5;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #ece8df;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }

        .brand {
            font-size: 20px;
            font-weight: 800;
            color: #1c1c17;
            letter-spacing: -0.5px;
        }

        .brand span {
            color: #964261;
        }

        .brand-sub {
            font-size: 9px;
            color: #867277;
            text-transform: uppercase;
            font-weight: 600;
            margin-top: 2px;
        }

        .doc-info {
            text-align: right;
        }

        .doc-title {
            font-size: 14px;
            font-weight: 700;
            color: #1c1c17;
            margin-bottom: 4px;
        }

        .doc-period {
            font-size: 10px;
            color: #867277;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-box {
            background: #fdf9f0;
            border: 1px solid #ece8df;
            border-radius: 12px;
            padding: 14px 16px;
        }

        .summary-label {
            font-size: 9px;
            color: #867277;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 18px;
            font-weight: 700;
            color: #964261;
        }

        .summary-value.dark {
            color: #1c1c17;
        }

        .table-title {
            font-size: 11px;
            font-weight: 700;
            color: #534247;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background: #fdf5ec;
            color: #964261;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9px;
            padding: 8px 12px;
            border-bottom: 1px solid #ece8df;
            text-align: left;
        }

        td {
            padding: 8px 12px;
            border-bottom: 1px solid #f7f3ea;
            font-size: 10px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .footer {
            margin-top: 40px;
            border-top: 1px solid #ece8df;
            padding-top: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #867277;
            font-size: 9px;
        }

        .btn-print-box {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #964261;
            color: #fff;
            padding: 10px 18px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 11px;
            border: none;
            cursor: pointer;
        }

        .btn-print-box:hover {
            background: #fdf0f4;
            color: #964261;
            border: 1px solid #f48fb1;
        }

        @media print {
            .btn-print-box {
                display: none;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>

    <button class="btn-print-box" onclick="window.print()">🖨 Cetak / Simpan PDF</button>

    <div class="header">
        <div>
            <div class="brand">Kristy<span>Crumbs</span></div>
            <div class="brand-sub">Sistem POS Restoran</div>
        </div>
        <div class="doc-info">
            <div class="doc-title"><?= htmlspecialchars($title) ?></div>
            <div class="doc-period"><?= htmlspecialchars($period_text) ?></div>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-box">
            <div class="summary-label">Total Transaksi</div>
            <div class="summary-value dark"><?= number_format($summary['total_orders'], 0, ',', '.') ?></div>
        </div>
        <div class="summary-box">
            <div class="summary-label">Total Omzet Pendapatan</div>
            <div class="summary-value">Rp <?= number_format($summary['omzet'], 0, ',', '.') ?></div>
        </div>
    </div>

    <div class="table-title">Rincian Transaksi Lunas</div>
    <table>
        <thead>
            <tr>
                <th style="width: 50px;">ID</th>
                <th>Waktu Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>No. Meja</th>
                <th>Metode Bayar</th>
                <th>Nama Kasir</th>
                <th style="text-align: right;">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($orders) === 0):
            ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 20px;">Tidak ada transaksi pada periode ini.</td>
                </tr>
                <?php
            else:
                while ($r = mysqli_fetch_assoc($orders)):
                ?>
                    <tr>
                        <td>#<?= $r['id_pesanan'] ?></td>
                        <td><?= htmlspecialchars($r['tgl_pesanan']) ?></td>
                        <td><strong><?= htmlspecialchars($r['nama_pelanggan']) ?></strong></td>
                        <td>Meja <?= htmlspecialchars($r['no_meja']) ?></td>
                        <td><?= ucfirst(htmlspecialchars($r['metode_bayar'] ?? 'tunai')) ?></td>
                        <td><?= htmlspecialchars($r['nama_kasir'] ?: '-') ?></td>
                        <td style="text-align: right; font-weight: 600;">Rp <?= number_format($r['total_harga'], 0, ',', '.') ?></td>
                    </tr>
            <?php
                endwhile;
            endif;
            ?>
        </tbody>
    </table>

    <div class="footer">
        <span>Dicetak secara otomatis oleh Sistem POS KristyCrumbs pada: <?= date('d-m-Y H:i:s') ?></span>
        <span>Halaman 1 dari 1</span>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>

</html>