<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../../auth.php';
checkRole(['admin', 'owner']);
include '../../connect.php';

$page_title = 'Edit Bahan Baku';
$active     = 'warehouse';

$errors = [];

// kategori bahan buat cafe bakery
$kategori_list = ['bahan_baku', 'minuman', 'topping', 'kemasan'];

// ambil data bahan berdasarkan id
if (!isset($_GET['id']) || intval($_GET['id']) <= 0) {
    header("Location: index.php");
    exit;
}

$id_bahan = intval($_GET['id']);
$stmt_get = mysqli_prepare($koneksi, "SELECT * FROM tb_bahan WHERE id_bahan = ?");
mysqli_stmt_bind_param($stmt_get, 'i', $id_bahan);
mysqli_stmt_execute($stmt_get);
$res   = mysqli_stmt_get_result($stmt_get);
$bahan = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt_get);

if (!$bahan) {
    header("Location: index.php");
    exit;
}

// isi form dari data existing
$form = [
    'nama_bahan'   => $bahan['nama_bahan'],
    'kategori'     => $bahan['kategori'],
    'stok_minimum' => $bahan['stok_minimum'],
    'satuan'       => $bahan['satuan'],
    'harga_modal'  => $bahan['harga_modal'],
];

// proses submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_bahan'])) {
    // ambil input
    $form['nama_bahan']   = trim($_POST['nama_bahan'] ?? '');
    $form['kategori']     = trim($_POST['kategori'] ?? '');
    $form['stok_minimum'] = trim($_POST['stok_minimum'] ?? '');
    $form['satuan']       = trim($_POST['satuan'] ?? '');
    $form['harga_modal']  = trim($_POST['harga_modal'] ?? '');

    // validasi
    if ($form['nama_bahan'] === '') {
        $errors['nama_bahan'] = 'Nama bahan wajib diisi.';
    }
    if (!in_array($form['kategori'], $kategori_list)) {
        $errors['kategori'] = 'Pilih kategori yang valid.';
    }
    if ($form['stok_minimum'] === '' || !is_numeric($form['stok_minimum'])) {
        $errors['stok_minimum'] = 'Stok minimum wajib diisi (angka).';
    } elseif ((float)$form['stok_minimum'] < 0) {
        $errors['stok_minimum'] = 'Stok minimum tidak boleh negatif.';
    }
    if ($form['satuan'] === '') {
        $errors['satuan'] = 'Satuan wajib diisi.';
    }
    if ($form['harga_modal'] === '' || !is_numeric($form['harga_modal'])) {
        $errors['harga_modal'] = 'Harga modal wajib diisi (angka).';
    } elseif ((float)$form['harga_modal'] < 0) {
        $errors['harga_modal'] = 'Harga modal tidak boleh negatif.';
    }

    // simpan kalau ga ada error
    if (empty($errors)) {
        $nama_bahan   = $form['nama_bahan'];
        $kategori     = $form['kategori'];
        $stok_minimum = (float) $form['stok_minimum'];
        $satuan       = $form['satuan'];
        $harga_modal  = (float) $form['harga_modal'];

        // stok tidak diubah di sini, diatur lewat stok_masuk & stok_keluar
        $stmt = mysqli_prepare($koneksi, "UPDATE tb_bahan SET nama_bahan = ?, kategori = ?, stok_minimum = ?, satuan = ?, harga_modal = ? WHERE id_bahan = ?");
        mysqli_stmt_bind_param($stmt, 'ssdsdi', $nama_bahan, $kategori, $stok_minimum, $satuan, $harga_modal, $id_bahan);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($result) {
            header("Location: index.php?success=Bahan+" . urlencode($nama_bahan) . "+berhasil+diperbarui");
            exit;
        } else {
            $errors['global'] = 'Gagal memperbarui bahan.';
        }
    }
}

include '../../../_layout.php';
?>

<!-- Flash error global -->
<?php if (!empty($errors['global'])): ?>
    <div class="alert alert-danger py-2 mb-3" style="font-size:12px;">
        <i class='bx bx-error-circle'></i> <?= htmlspecialchars($errors['global']) ?>
    </div>
<?php endif; ?>

<!-- Flash success dari redirect -->
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success py-2 mb-3" style="font-size:12px;">
        <i class='bx bx-check-circle'></i> <?= htmlspecialchars($_GET['success']) ?>
    </div>
<?php endif; ?>

<div class="row g-3">
    <div class="col-md-7 col-lg-6">
        <div class="kc-card">
            <div class="kc-card-header">
                <span><i class='bx bx-edit'></i> Form Edit Bahan Baku</span>
            </div>
            <div class="kc-card-body">
                <form method="POST" id="form-edit-bahan" novalidate>
                    <!-- Nama Bahan -->
                    <div class="mb-3">
                        <label class="form-label" for="nama_bahan">Nama Bahan <span style="color:#964261">*</span></label>
                        <input
                            type="text"
                            id="nama_bahan"
                            name="nama_bahan"
                            class="form-control form-control-sm <?= isset($errors['nama_bahan']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($form['nama_bahan']) ?>"
                            placeholder="Contoh: Tepung Terigu"
                            required>
                        <?php if (isset($errors['nama_bahan'])): ?>
                            <div class="invalid-feedback"><?= $errors['nama_bahan'] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label class="form-label" for="kategori">Kategori <span style="color:#964261">*</span></label>
                        <select
                            id="kategori"
                            name="kategori"
                            class="form-select form-select-sm <?= isset($errors['kategori']) ? 'is-invalid' : '' ?>"
                            required>
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

                    <!-- Satuan -->
                    <div class="mb-3">
                        <label class="form-label" for="satuan">Satuan <span style="color:#964261">*</span></label>
                        <input
                            type="text"
                            id="satuan"
                            name="satuan"
                            class="form-control form-control-sm <?= isset($errors['satuan']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($form['satuan']) ?>"
                            placeholder="kg / pcs"
                            required>
                        <?php if (isset($errors['satuan'])): ?>
                            <div class="invalid-feedback"><?= $errors['satuan'] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Stok Minimum -->
                    <div class="mb-3">
                        <label class="form-label" for="stok_minimum">Stok Minimum <span style="color:#964261">*</span></label>
                        <input
                            type="number"
                            id="stok_minimum"
                            name="stok_minimum"
                            step="0.01"
                            min="0"
                            class="form-control form-control-sm <?= isset($errors['stok_minimum']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($form['stok_minimum']) ?>"
                            placeholder="0"
                            required>
                        <div style="font-size:10px;color:#a07850;margin-top:3px;">
                            Alert akan muncul jika stok ≤ nilai ini
                        </div>
                        <?php if (isset($errors['stok_minimum'])): ?>
                            <div class="invalid-feedback"><?= $errors['stok_minimum'] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Harga Modal -->
                    <div class="mb-3">
                        <label class="form-label" for="harga_modal">Harga Modal (Rp) <span style="color:#964261">*</span></label>
                        <input
                            type="number"
                            id="harga_modal"
                            name="harga_modal"
                            step="1"
                            min="0"
                            class="form-control form-control-sm <?= isset($errors['harga_modal']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($form['harga_modal']) ?>"
                            placeholder="0"
                            required>
                        <?php if (isset($errors['harga_modal'])): ?>
                            <div class="invalid-feedback"><?= $errors['harga_modal'] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Tombol Aksi -->
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

    <!-- Info tambahan di sebelah kanan -->
    <div class="col-md-5 col-lg-6">
        <div class="kc-card">
            <div class="kc-card-header"><i class='bx bx-info-circle'></i> Info</div>
            <div class="kc-card-body" style="font-size:12px;color:#534247;line-height:1.7;">
                <p>• <strong>Stok</strong> tidak bisa diubah di sini, gunakan menu stok masuk atau stok keluar.</p>
                <p>• <strong>Stok Minimum</strong> digunakan untuk trigger notifikasi bahan hampir habis.</p>
                <p>• Gunakan satuan yang sesuai, misal: gr, kg, ml, liter, pcs, lembar.</p>
                <p>• Harga modal digunakan untuk menghitung nilai stok pada laporan.</p>
            </div>
        </div>
    </div>
</div>

<?php include '../../../_layout_end.php'; ?>