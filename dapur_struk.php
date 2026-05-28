<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'function/auth.php';
// Allow kasir, owner, admin, dapur roles
checkRole(['kasir', 'owner', 'admin', 'dapur']);
include 'function/connect.php';

$id = intval($_GET['id'] ?? 0);
$pesanan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE id_pesanan=$id"));
if (!$pesanan) {
    echo "Pesanan tidak ditemukan.";
    exit;
}
// Fetch id_menu, quantity, nama_menu, and notes by joining tb_menu
$details = mysqli_query(
    $koneksi, 
    "SELECT d.id_menu, d.jumlah, m.nama_menu, d.catatan 
     FROM tb_detail_pesanan d 
     JOIN tb_menu m ON d.id_menu = m.id_menu 
     WHERE d.id_pesanan = $id"
);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Dapur #<?= $pesanan['id_pesanan'] ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            background: #fff;
            padding: 0;
        }
        .no-print {
            padding: 10px;
            background: #f5f5f5;
            border-bottom: 1px solid #ddd;
            display: flex;
            gap: 8px;
        }
        .no-print button,
        .no-print a {
            padding: 6px 14px;
            font-size: 12px;
            border-radius: 3px;
            cursor: pointer;
            border: 1px solid #ccc;
            background: #fff;
            text-decoration: none;
            color: #333;
            font-family: sans-serif;
        }
        .no-print button.cetak {
            background: #333;
            color: #fff;
            border-color: #333;
        }
        .struk {
            width: 300px;
            margin: 20px auto;
            padding: 12px;
            border: 1px dashed #ccc;
        }
        .judul {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .garis {
            border: none;
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 2px;
        }
        .item {
            font-size: 12px;
            margin-bottom: 6px;
        }
        .item-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }
        .item-catatan {
            font-size: 11px;
            color: #dc2626; /* Red color for high visibility in kitchen */
            font-weight: bold;
            font-style: italic;
            margin-left: 8px;
            margin-top: 2px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
            }
            .struk {
                margin: 0;
                width: 100%;
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <a href="dapur.php">← Kembali ke Dapur</a>
        <button class="cetak" onclick="window.print()">🖨 Print Struk</button>
    </div>

    <div class="struk">
        <div class="judul">STRUK DAPUR (KITCHEN)</div>
        <hr class="garis">
        
        <div class="info-row"><span>No. Pesanan:</span> <span>#<?= $pesanan['id_pesanan'] ?></span></div>
        <div class="info-row"><span>Pelanggan:</span> <span><?= htmlspecialchars($pesanan['nama_pelanggan']) ?></span></div>
        <div class="info-row"><span>No. Meja:</span> <span><?= htmlspecialchars($pesanan['no_meja']) ?></span></div>
        <div class="info-row"><span>Waktu:</span> <span><?= htmlspecialchars($pesanan['tgl_pesanan']) ?></span></div>
        
        <hr class="garis">
        
        <?php while ($row = mysqli_fetch_assoc($details)): ?>
            <div class="item">
                <div class="item-header">
                    <span><?= htmlspecialchars($row['nama_menu']) ?></span>
                    <span>x<?= $row['jumlah'] ?></span>
                </div>
                <?php if (!empty($row['catatan'])): ?>
                    <div class="item-catatan">
                        Catatan: <?= htmlspecialchars($row['catatan']) ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
        
        <hr class="garis">
        <div style="text-align: center; font-size: 10px; margin-top: 8px; color: #555;">
            Mohon sajikan dengan cepat dan higienis!
        </div>
    </div>
</body>
</html>
