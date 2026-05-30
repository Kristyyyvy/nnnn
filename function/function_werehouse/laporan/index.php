<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../../auth.php';
checkRole(['admin', 'owner']);
include '../../connect.php';

// ─── Filter ──────────────────────────────────────────────────────────────────
$filter_kategori = $_GET['kategori'] ?? '';
$filter_status   = $_GET['status'] ?? '';   // aman / menipis / habis
$kategori_list   = ['karbohidrat', 'protein', 'sayur', 'bumbu', 'minyak_saus', 'lainnya'];

// ─── Export CSV ───────────────────────────────────────────────────────────────
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $q_csv = mysqli_query($koneksi, "SELECT * FROM tb_bahan ORDER BY nama_bahan ASC");
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="laporan_stok_' . date('Ymd_His') . '.csv"');
    header('Pragma: no-cache');

    $out = fopen('php://output', 'w');
    // BOM untuk Excel agar UTF-8 terbaca
    fputs($out, "\xEF\xBB\xBF");
    fputcsv($out, ['No', 'Nama Bahan', 'Kategori', 'Stok', 'Satuan', 'Stok Min', 'Harga Modal (Rp)', 'Nilai Stok (Rp)', 'Status']);
    $no = 1;
    while ($row = mysqli_fetch_assoc($q_csv)) {
        $habis   = (float)$row['stok'] <= 0;
        $menipis = (float)$row['stok'] <= (float)$row['stok_minimum'];
        $status  = $habis ? 'Habis' : ($menipis ? 'Menipis' : 'Aman');
        $nilai   = (float)$row['stok'] * (float)$row['harga_modal'];
        fputcsv($out, [
            $no++,
            $row['nama_bahan'],
            ucwords(str_replace('_', ' ', $row['kategori'])),
            number_format((float)$row['stok'], 2, ',', '.'),
            $row['satuan'],
            number_format((float)$row['stok_minimum'], 2, ',', '.'),
            number_format((float)$row['harga_modal'], 0, ',', '.'),
            number_format($nilai, 0, ',', '.'),
            $status,
        ]);
    }
    fclose($out);
    exit;
}

// ─── Query summary stats ──────────────────────────────────────────────────────
$stats_q  = mysqli_query($koneksi, "
    SELECT
        COUNT(*) AS total_bahan,
        SUM(stok * harga_modal) AS total_nilai,
        SUM(CASE WHEN stok <= stok_minimum AND stok > 0 THEN 1 ELSE 0 END) AS total_menipis,
        SUM(CASE WHEN stok <= 0 THEN 1 ELSE 0 END) AS total_habis
    FROM tb_bahan
");
$stats = mysqli_fetch_assoc($stats_q);

// ─── Build query dengan filter ───────────────────────────────────────────────
$where_parts = [];
$params      = [];
$types       = '';

if ($filter_kategori !== '') {
    $where_parts[] = 'kategori = ?';
    $params[]      = $filter_kategori;
    $types        .= 's';
}
// Filter status menggunakan HAVING-like logic via WHERE
if ($filter_status === 'habis') {
    $where_parts[] = 'stok <= 0';
} elseif ($filter_status === 'menipis') {
    $where_parts[] = 'stok <= stok_minimum AND stok > 0';
} elseif ($filter_status === 'aman') {
    $where_parts[] = 'stok > stok_minimum';
}

$sql = "SELECT *, (stok * harga_modal) AS nilai_stok FROM tb_bahan";
if ($where_parts) {
    $sql .= ' WHERE ' . implode(' AND ', $where_parts);
}
$sql .= ' ORDER BY stok ASC, nama_bahan ASC';

$stmt = mysqli_prepare($koneksi, $sql);
if ($params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result  = mysqli_stmt_get_result($stmt);
$bahans  = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bahans[] = $row;
}
mysqli_stmt_close($stmt);

// ─── Layout ──────────────────────────────────────────────────────────────────
$page_title = 'Laporan Stok Bahan';
$active     = 'warehouse';
include '../../../_layout.php';
?>

<!-- ─── Summary Cards ─────────────────────────────────────────────────────────── -->
<div class="stat-grid" style="margin-bottom:14px;">
  <div class="stat-box">
    <div class="stat-label">Total Jenis Bahan</div>
    <div class="stat-value dark"><?= number_format((int)$stats['total_bahan']) ?></div>
  </div>
  <div class="stat-box">
    <div class="stat-label">Total Nilai Stok</div>
    <div class="stat-value">Rp <?= number_format((float)$stats['total_nilai'], 0, ',', '.') ?></div>
  </div>
  <div class="stat-box" style="border-color:#fed7aa;">
    <div class="stat-label" style="color:#d97706;">⚠️ Menipis</div>
    <div class="stat-value" style="color:#d97706;"><?= (int)$stats['total_menipis'] ?></div>
  </div>
  <div class="stat-box" style="border-color:#fca5a5;">
    <div class="stat-label" style="color:#dc2626;">🔴 Habis</div>
    <div class="stat-value" style="color:#dc2626;"><?= (int)$stats['total_habis'] ?></div>
  </div>
</div>

<!-- ─── Toolbar ──────────────────────────────────────────────────────────────── -->
<div class="kc-card mb-3">
  <div class="kc-card-body" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;padding:10px 12px;">
    <form method="GET" style="display:flex;gap:8px;flex:1;flex-wrap:wrap;align-items:center;">
      <!-- Filter Kategori -->
      <select name="kategori" class="form-select form-select-sm" style="max-width:180px;">
        <option value="">Semua Kategori</option>
        <?php foreach ($kategori_list as $kat): ?>
          <option value="<?= $kat ?>" <?= $filter_kategori === $kat ? 'selected' : '' ?>>
            <?= ucwords(str_replace('_', ' ', $kat)) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <!-- Filter Status -->
      <select name="status" class="form-select form-select-sm" style="max-width:150px;">
        <option value="">Semua Status</option>
        <option value="aman"    <?= $filter_status === 'aman'    ? 'selected' : '' ?>>✅ Aman</option>
        <option value="menipis" <?= $filter_status === 'menipis' ? 'selected' : '' ?>>⚠️ Menipis</option>
        <option value="habis"   <?= $filter_status === 'habis'   ? 'selected' : '' ?>>🔴 Habis</option>
      </select>
      <button type="submit" class="btn-kc btn-kc-sm"><i class='bx bx-filter-alt'></i> Filter</button>
      <?php if ($filter_kategori || $filter_status): ?>
        <a href="index.php" class="btn-kc-outline">Reset</a>
      <?php endif; ?>
    </form>
    <!-- Export CSV -->
    <a href="index.php?export=csv" class="btn-kc-outline" id="btn-export-csv" style="font-size:11px;">
      <i class='bx bx-export'></i> Export CSV
    </a>
  </div>
</div>

<!-- ─── Tabel Laporan ─────────────────────────────────────────────────────────── -->
<div class="kc-card">
  <div class="kc-card-header">
    <span><i class='bx bx-bar-chart-alt-2'></i> Ringkasan Stok Semua Bahan</span>
    <span style="font-size:11px;color:#a07850;font-weight:400;"><?= count($bahans) ?> bahan</span>
  </div>
  <table class="kc-table w-100">
    <thead>
      <tr>
        <th style="width:40px;">No</th>
        <th>Nama Bahan</th>
        <th>Kategori</th>
        <th>Stok</th>
        <th>Harga Modal</th>
        <th>Nilai Stok</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($bahans)): ?>
        <tr>
          <td colspan="7" style="text-align:center;color:#a07850;padding:20px;">
            Tidak ada data dengan filter yang dipilih.
          </td>
        </tr>
      <?php endif; ?>
      <?php $no = 1; foreach ($bahans as $b): ?>
        <?php
          $habis   = (float)$b['stok'] <= 0;
          $menipis = (float)$b['stok'] <= (float)$b['stok_minimum'];
          $nilai   = (float)$b['nilai_stok'];
        ?>
        <tr>
          <td style="color:#a07850;"><?= $no++ ?></td>
          <td><strong><?= htmlspecialchars($b['nama_bahan']) ?></strong></td>
          <td>
            <span class="kc-badge kc-badge-gray">
              <?= ucwords(str_replace('_', ' ', $b['kategori'])) ?>
            </span>
          </td>
          <td>
            <span style="<?= $habis ? 'color:#dc2626;font-weight:700;' : ($menipis ? 'color:#d97706;font-weight:600;' : '') ?>">
              <?= number_format((float)$b['stok'], 2, ',', '.') ?>
              <small style="color:#a07850;"><?= htmlspecialchars($b['satuan']) ?></small>
            </span>
          </td>
          <td>Rp <?= number_format((float)$b['harga_modal'], 0, ',', '.') ?></td>
          <td style="font-weight:600;">Rp <?= number_format($nilai, 0, ',', '.') ?></td>
          <td>
            <?php if ($habis): ?>
              <span class="kc-badge kc-badge-red">🔴 Habis</span>
            <?php elseif ($menipis): ?>
              <span class="kc-badge kc-badge-yellow">⚠️ Menipis</span>
            <?php else: ?>
              <span class="kc-badge kc-badge-green">✅ Aman</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <!-- Footer total nilai -->
    <?php if (!empty($bahans)): ?>
      <?php $total_nilai_filter = array_sum(array_column($bahans, 'nilai_stok')); ?>
      <tfoot>
        <tr style="background:#fdf5ec;">
          <td colspan="5" style="text-align:right;font-weight:700;font-size:12px;padding:8px 10px;color:#7c3a0e;">
            Total Nilai (filter ini):
          </td>
          <td style="font-weight:700;font-size:12px;padding:8px 10px;color:#92400e;">
            Rp <?= number_format($total_nilai_filter, 0, ',', '.') ?>
          </td>
          <td></td>
        </tr>
      </tfoot>
    <?php endif; ?>
  </table>
</div>

<?php include '../../../_layout_end.php'; ?>
