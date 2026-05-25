<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'function/auth.php';
checkRole(['dapur']);
include 'function/connect.php';

$page_title = 'Antrian Dapur';
$active = 'dapur';
include '_layout.php';

$result = mysqli_query($koneksi, "SELECT p.*, m.status AS meja_status FROM tb_pesanan p JOIN tb_meja m ON p.no_meja = m.id_meja WHERE p.status_pesanan='proses' ORDER BY p.id_pesanan ASC");
$cnt = $result ? mysqli_num_rows($result) : 0;
?>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
    <div>
        <?php if ($cnt > 0): ?>
            <span class="kc-badge kc-badge-yellow"><?= $cnt ?> pesanan menunggu</span>
        <?php else: ?>
            <span class="kc-badge kc-badge-green">Semua pesanan selesai</span>
        <?php endif; ?>
    </div>
</div>

<?php if ($cnt == 0): ?>

    <div class="kc-card">
        <div class="kc-card-body" style="text-align:center;padding:40px">
            <i class='bx bx-check-circle' style="font-size:36px;color:#15803d"></i>
            <div style="margin-top:10px;font-weight:600;color:#1c1007">Semua beres!</div>
            <div style="font-size:12px;color:#a07850;margin-top:4px">Tidak ada pesanan yang perlu dimasak.</div>
        </div>
    </div>

<?php else: ?>

<div class="row g-3">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-6 col-xl-4">
                <?php $border = ($row['meja_status']=='kosong') ? '#28a745' : '#dc3545'; ?>
                <div class="kc-card" style="border-left:3px solid <?= $border ?>">
                    <div class="kc-card-header" style="background:#fef9c3;border-bottom:1px solid #fef08a">
                        <div>
                            <?php
    // Meja line
    echo '<div style="font-size:10px;color:#a16207;font-weight:600">Meja ' . $row['no_meja'] . ' &mdash; #' . $row['id_pesanan'] . '</div>';
    // Table status badge
    $bg = ($row['meja_status'] == 'kosong') ? '#d4edda' : '#f8d7da';
    $color = ($row['meja_status'] == 'kosong') ? '#155724' : '#721c24';
    echo '<div style="font-size:10px;color:#a16207;font-weight:600">Status Meja: <span style="padding:2px 6px;border-radius:4px;background:' . $bg . ';color:' . $color . ';">' . htmlspecialchars($row['meja_status']) . '</span></div>';
?>
                            <div style="font-size:13px;font-weight:700;color:#1c1007"><?= htmlspecialchars($row['nama_pelanggan']) ?></div>
                        </div>
                        <span class="kc-badge kc-badge-yellow">Proses</span>
                    </div>
                    <div class="kc-card-body" style="padding:12px 14px">
                        <?php
                        $d = mysqli_query($koneksi, "SELECT d.id_menu, d.jumlah, m.nama_menu, d.catatan FROM tb_detail_pesanan d JOIN tb_menu m ON d.id_menu = m.id_menu WHERE d.id_pesanan='{$row['id_pesanan']}'");
                        while ($item = mysqli_fetch_assoc($d)):
                        ?>
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                                <div style="width:26px;height:26px;border-radius:50%;background:#fde8cc;color:#7c3a0e;font-weight:700;font-size:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0"><?= $item['jumlah'] ?></div>
                                 <div>
                                     <div style="font-size:12px;font-weight:600;color:#1c1007"><?= htmlspecialchars($item['nama_menu']) ?></div>
                                     <?php if (!empty($item['catatan'])): ?>
                                         <div style="font-size:10px;color:#dc2626;font-style:italic;">Catatan: <?= htmlspecialchars($item['catatan']) ?></div>
                                     <?php endif; ?>
                                 </div>
                                </div>
                        <?php endwhile; ?>
                        <div style="font-size:10px;color:#a07850;margin-top:6px"><?= $row['tgl_pesanan'] ?></div>
                    </div>
                    <div style="padding:10px 14px;border-top:1px solid #e8d5b8;background:#fdf5ec">
                        <a href="function/function_pesanan/finish_order.php?id=<?= $row['id_pesanan'] ?>"
                            onclick="return confirm('Pesanan ini sudah siap saji?')"
                            class="btn-kc btn-kc-sm w-100 justify-content-center">
                            <i class='bx bx-check-double'></i> Siap Saji
                        </a>
                        <a href="dapur_struk.php?id=<?= $row['id_pesanan'] ?>" target="_blank" class="btn-kc btn-kc-sm w-100 justify-content-center mt-2">
                            <i class='bx bx-receipt'></i> Struk Dapur
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

<?php endif; ?>

<?php include '_layout_end.php'; ?>