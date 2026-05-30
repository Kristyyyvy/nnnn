<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../../auth.php';
checkRole(['admin', 'owner']);
include '../../connect.php';

// ─── Ambil ID bahan dari URL ─────────────────────────────────────────────────
$id_bahan = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id_bahan <= 0) {
    header("Location: index.php");
    exit;
}

// ─── Ambil data bahan yang akan diedit ───────────────────────────────────────
$stmt_get = mysqli_prepare($koneksi, "SELECT * FROM tb_bahan WHERE id_bahan = ?");
mysqli_stmt_bind_param($stmt_get, 'i', $id_bahan);
mysqli_stmt_execute($stmt_get);
$result_get = mysqli_stmt_get_result($stmt_get);
$bahan = mysqli_fetch_assoc($result_get);
mysqli_stmt_close($stmt_get);

if (!$bahan) {
    header("Location: index.php?error=Bahan+tidak+ditemukan");
    exit;
}

$page_title = 'Edit Bahan: ' . htmlspecialchars($bahan['nama_bahan']);
$active     = 'warehouse';

$errors = [];
// Pre-fill form dengan data yang ada
$form = [
    'nama_bahan'   => $bahan['nama_bahan'],
    'kategori'     => $bahan['kategori'],
    'stok'         => $bahan['stok'],
    'stok_minimum' => $bahan['stok_minimum'],
    'satuan'       => $bahan['satuan'],
    'harga_modal'  => $bahan['harga_modal'],
];

$kategori_list = ['karbohidrat', 'protein', 'sayur', 'bumbu', 'minyak_saus', 'lainnya'];

// ─── Proses Form Submit ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_bahan'])) {
    $form['nama_bahan']   = trim($_POST['nama_bahan'] ?? '');
    $form['kategori']     = trim($_POST['kategori'] ?? '');
    $form['stok']         = trim($_POST['stok'] ?? '');
    $form['stok_minimum'] = trim($_POST['stok_minimum'] ?? '');
    $form['satuan']       = trim($_POST['satuan'] ?? '');
    $form['harga_modal']  = trim($_POST['harga_modal'] ?? '');
    $catatan_edit         = trim($_POST['catatan_edit'] ?? '');

    // ── Validasi ──────────────────────────────────────────────────────────────
    if ($form['nama_bahan'] === '') $errors['nama_bahan'] = 'Nama bahan wajib diisi.';
    if (!in_array($form['kategori'], $kategori_list)) $errors['kategori'] = 'Pilih kategori yang valid.';
    if ($form['stok'] === '' || !is_numeric($form['stok'])) {
        $errors['stok'] = 'Stok wajib diisi (angka).';
    } elseif ((float)$form['stok'] < 0) {
        $errors['stok'] = 'Stok tidak boleh negatif.';
    }
    if ($form['stok_minimum'] === '' || !is_numeric($form['stok_minimum'])) {
        $errors['stok_minimum'] = 'Stok minimum wajib diisi (angka).';
    } elseif ((float)$form['stok_minimum'] < 0) {
        $errors['stok_minimum'] = 'Stok minimum tidak boleh negatif.';
    }
    if ($form['satuan'] === '') $errors['satuan'] = 'Satuan wajib diisi.';
    if ($form['harga_modal'] === '' || !is_numeric($form['harga_modal'])) {
        $errors['harga_modal'] = 'Harga modal wajib diisi (angka).';
    } elseif ((float)$form['harga_modal'] < 0) {
        $errors['harga_modal'] = 'Harga modal tidak boleh negatif.';
    }

    // ── Simpan jika valid ─────────────────────────────────────────────────────
    if (empty($errors)) {
        $nama_bahan   = $form['nama_bahan'];
        $kategori     = $form['kategori'];
        $stok_baru    = (float) $form['stok'];
        $stok_minimum = (float) $form['stok_minimum'];
        $satuan       = $form['satuan'];
        $harga_modal  = (float) $form['harga_modal'];
        $stok_lama    = (float) $bahan['stok'];

        mysqli_begin_transaction($koneksi);
        try {
            // Update data bahan — tipe: s=string, d=double/float, i=int
            $stmt_upd = mysqli_prepare(
                $koneksi,
                "UPDATE tb_bahan SET nama_bahan=?, kategori=?, stok=?, stok_minimum=?, satuan=?, harga_modal=?
                 WHERE id_bahan=?"
            );
            // ss=nama+kategori, dd=stok+stok_min, s=satuan, d=harga_modal, i=id_bahan
            mysqli_stmt_bind_param($stmt_upd, 'ssddsdi',
                $nama_bahan, $kategori, $stok_baru, $stok_minimum, $satuan, $harga_modal, $id_bahan
            );
            mysqli_stmt_execute($stmt_upd);
            mysqli_stmt_close($stmt_upd);

            // Jika stok berubah, catat selisih di log stok
            $selisih = $stok_baru - $stok_lama;
            if (abs($selisih) > 0.001) {
                // Positif = stok bertambah (masuk), negatif = stok berkurang (keluar)
                $jenis_log  = ($selisih > 0) ? 'masuk' : 'keluar';
                $jumlah_log = abs($selisih);
                $ket_log    = $catatan_edit !== ''
                    ? "Edit manual: " . $catatan_edit
                    : "Edit manual oleh " . ($_SESSION['username'] ?? 'admin');

                // i=id_bahan, s=jenis, d=jumlah, s=keterangan
                $stmt_log = mysqli_prepare(
                    $koneksi,
                    "INSERT INTO tb_stok_log (id_bahan, jenis, jumlah, keterangan) VALUES (?, ?, ?, ?)"
                );
                mysqli_stmt_bind_param($stmt_log, 'isds', $id_bahan, $jenis_log, $jumlah_log, $ket_log);
                mysqli_stmt_execute($stmt_log);
                mysqli_stmt_close($stmt_log);
            }

            // Refresh session alert stok
            $q_alert = mysqli_query($koneksi, "SELECT COUNT(*) AS total_menipis FROM tb_bahan WHERE stok <= stok_minimum");
            $_SESSION['alert_stok'] = (int) mysqli_fetch_assoc($q_alert)['total_menipis'];

            mysqli_commit($koneksi);
            header("Location: index.php?success=Bahan+" . urlencode($nama_bahan) . "+berhasil+diupdate");
            exit;

        } catch (Exception $e) {
            mysqli_rollback($koneksi);
            $errors['global'] = 'Gagal menyimpan perubahan: ' . $e->getMessage();
        }
    }
}

include '../../../_layout.php';
?>

<?php if (!empty($errors['global'])): ?>
  <div class="alert alert-danger py-2 mb-3" style="font-size:12px;">
    <i class='bx bx-error-circle'></i> <?= htmlspecialchars($errors['global']) ?>
  </div>
<?php endif; ?>

<div class="row g-3">
  <div class="col-md-7 col-lg-6">
    <div class="kc-card">
      <div class="kc-card-header">
        <span><i class='bx bx-edit'></i> Edit Bahan Baku</span>
        <span style="font-size:11px;color:#a07850;font-weight:400;">ID #<?= $id_bahan ?></span>
      </div>
      <div class="kc-card-body">
        <form method="POST" id="form-edit-bahan" novalidate>
          <!-- Nama Bahan -->
          <div class="mb-3">
            <label class="form-label" for="nama_bahan">Nama Bahan <span style="color:#dc2626">*</span></label>
            <input
              type="text" id="nama_bahan" name="nama_bahan"
              class="form-control form-control-sm <?= isset($errors['nama_bahan']) ? 'is-invalid' : '' ?>"
              value="<?= htmlspecialchars($form['nama_bahan']) ?>"
              required
            >
            <?php if (isset($errors['nama_bahan'])): ?>
              <div class="invalid-feedback"><?= $errors['nama_bahan'] ?></div>
            <?php endif; ?>
          </div>

          <!-- Kategori -->
          <div class="mb-3">
            <label class="form-label" for="kategori">Kategori <span style="color:#dc2626">*</span></label>
            <select id="kategori" name="kategori" class="form-select form-select-sm <?= isset($errors['kategori']) ? 'is-invalid' : '' ?>" required>
              <option value="">-- Pilih Kategori --</option>
              <?php foreach ($kategori_list as $kat): ?>
                <option value="<?= $kat ?>" <?= $form['kategori'] === $kat ? 'selected' : '' ?>>
                  <?= ucwords(str_replace('_', ' ', $kat)) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['kategori'])): ?>
              <div class="invalid-feedback"><?= $errors['kategori'] ?></div>
            <?php endif; ?>
          </div>

          <!-- Stok & Satuan -->
          <div class="row g-2 mb-3">
            <div class="col-8">
              <label class="form-label" for="stok">Stok <span style="color:#dc2626">*</span></label>
              <input
                type="number" id="stok" name="stok" step="0.01" min="0"
                class="form-control form-control-sm <?= isset($errors['stok']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($form['stok']) ?>"
                required
              >
              <?php if (isset($errors['stok'])): ?>
                <div class="invalid-feedback"><?= $errors['stok'] ?></div>
              <?php endif; ?>
            </div>
            <div class="col-4">
              <label class="form-label" for="satuan">Satuan <span style="color:#dc2626">*</span></label>
              <input
                type="text" id="satuan" name="satuan"
                class="form-control form-control-sm <?= isset($errors['satuan']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($form['satuan']) ?>"
                required
              >
              <?php if (isset($errors['satuan'])): ?>
                <div class="invalid-feedback"><?= $errors['satuan'] ?></div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Stok Minimum -->
          <div class="mb-3">
            <label class="form-label" for="stok_minimum">Stok Minimum <span style="color:#dc2626">*</span></label>
            <input
              type="number" id="stok_minimum" name="stok_minimum" step="0.01" min="0"
              class="form-control form-control-sm <?= isset($errors['stok_minimum']) ? 'is-invalid' : '' ?>"
              value="<?= htmlspecialchars($form['stok_minimum']) ?>"
              required
            >
            <?php if (isset($errors['stok_minimum'])): ?>
              <div class="invalid-feedback"><?= $errors['stok_minimum'] ?></div>
            <?php endif; ?>
          </div>

          <!-- Harga Modal -->
          <div class="mb-3">
            <label class="form-label" for="harga_modal">Harga Modal (Rp) <span style="color:#dc2626">*</span></label>
            <input
              type="number" id="harga_modal" name="harga_modal" step="1" min="0"
              class="form-control form-control-sm <?= isset($errors['harga_modal']) ? 'is-invalid' : '' ?>"
              value="<?= htmlspecialchars($form['harga_modal']) ?>"
              required
            >
            <?php if (isset($errors['harga_modal'])): ?>
              <div class="invalid-feedback"><?= $errors['harga_modal'] ?></div>
            <?php endif; ?>
          </div>

          <!-- Catatan edit (untuk log) -->
          <div class="mb-3">
            <label class="form-label" for="catatan_edit">Catatan Perubahan (opsional)</label>
            <input
              type="text" id="catatan_edit" name="catatan_edit"
              class="form-control form-control-sm"
              placeholder="Misal: Restock dari supplier A"
            >
            <div style="font-size:10px;color:#a07850;margin-top:3px;">
              Dicatat di log jika stok berubah.
            </div>
          </div>

          <div style="display:flex;gap:8px;margin-top:16px;">
            <button type="submit" name="update_bahan" class="btn-kc btn-kc-sm" id="btn-update-bahan">
              <i class='bx bx-save'></i> Simpan Perubahan
            </button>
            <a href="index.php" class="btn-kc-outline">
              <i class='bx bx-arrow-back'></i> Batal
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Info stok saat ini -->
  <div class="col-md-5 col-lg-6">
    <div class="kc-card">
      <div class="kc-card-header"><i class='bx bx-info-circle'></i> Info Stok Saat Ini</div>
      <div class="kc-card-body" style="font-size:12px;">
        <?php
          $stok_now = (float)$bahan['stok'];
          $stok_min = (float)$bahan['stok_minimum'];
          $habis    = $stok_now <= 0;
          $menipis  = $stok_now <= $stok_min;
        ?>
        <p><strong>Nama:</strong> <?= htmlspecialchars($bahan['nama_bahan']) ?></p>
        <p><strong>Stok Sekarang:</strong>
          <span style="<?= $habis ? 'color:#dc2626;font-weight:700;' : ($menipis ? 'color:#d97706;font-weight:600;' : 'color:#15803d;') ?>">
            <?= number_format($stok_now, 2, ',', '.') ?> <?= htmlspecialchars($bahan['satuan']) ?>
          </span>
        </p>
        <p><strong>Stok Minimum:</strong> <?= number_format($stok_min, 2, ',', '.') ?> <?= htmlspecialchars($bahan['satuan']) ?></p>
        <p><strong>Status:</strong>
          <?php if ($habis): ?>
            <span class="kc-badge kc-badge-red">🔴 Habis</span>
          <?php elseif ($menipis): ?>
            <span class="kc-badge kc-badge-yellow">⚠️ Menipis</span>
          <?php else: ?>
            <span class="kc-badge kc-badge-green">✅ Aman</span>
          <?php endif; ?>
        </p>
        <hr style="border-color:#e8d5b8;margin:8px 0;">
        <p style="color:#a07850;">Jika stok diubah, selisih akan otomatis dicatat di log stok.</p>
      </div>
    </div>
    <!-- Link ke log bahan ini -->
    <div style="margin-top:10px;">
      <a href="../laporan/log.php?id_bahan=<?= $id_bahan ?>" class="btn-kc-outline" style="font-size:11px;">
        <i class='bx bx-history'></i> Lihat Riwayat Log Stok
      </a>
    </div>
  </div>
</div>

<?php include '../../../_layout_end.php'; ?>
