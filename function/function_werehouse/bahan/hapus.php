<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../../auth.php';
checkRole(['admin', 'owner']);
include '../../connect.php';

$id_bahan = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id_bahan <= 0) {
    header("Location: index.php");
    exit;
}

// Cek bahan ada & ambil nama untuk konfirmasi
$stmt_cek = mysqli_prepare($koneksi, "SELECT nama_bahan FROM tb_bahan WHERE id_bahan = ?");
mysqli_stmt_bind_param($stmt_cek, 'i', $id_bahan);
mysqli_stmt_execute($stmt_cek);
$res_cek = mysqli_stmt_get_result($stmt_cek);
$bahan   = mysqli_fetch_assoc($res_cek);
mysqli_stmt_close($stmt_cek);

if (!$bahan) {
    header("Location: index.php?error=Bahan+tidak+ditemukan");
    exit;
}

// ─── Cek apakah bahan digunakan di resep ──────────────────────────────────────
$stmt_resep = mysqli_prepare($koneksi, "SELECT COUNT(*) AS jumlah FROM tb_resep WHERE id_bahan = ?");
mysqli_stmt_bind_param($stmt_resep, 'i', $id_bahan);
mysqli_stmt_execute($stmt_resep);
$jumlah_resep = (int) mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_resep))['jumlah'];
mysqli_stmt_close($stmt_resep);

// ─── Konfirmasi: tampilkan halaman konfirmasi dahulu ─────────────────────────
// Jika GET request → tampilkan form konfirmasi
// Jika POST dengan konfirmasi → lakukan hapus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['konfirmasi_hapus'])) {
    // ── Lakukan hapus ────────────────────────────────────────────────────────
    mysqli_begin_transaction($koneksi);
    try {
        // Hapus log stok terkait bahan ini terlebih dahulu (FK constraint)
        $stmt_del_log = mysqli_prepare($koneksi, "DELETE FROM tb_stok_log WHERE id_bahan = ?");
        mysqli_stmt_bind_param($stmt_del_log, 'i', $id_bahan);
        mysqli_stmt_execute($stmt_del_log);
        mysqli_stmt_close($stmt_del_log);

        // Hapus dari resep jika ada
        $stmt_del_resep = mysqli_prepare($koneksi, "DELETE FROM tb_resep WHERE id_bahan = ?");
        mysqli_stmt_bind_param($stmt_del_resep, 'i', $id_bahan);
        mysqli_stmt_execute($stmt_del_resep);
        mysqli_stmt_close($stmt_del_resep);

        // Hapus bahan
        $stmt_del = mysqli_prepare($koneksi, "DELETE FROM tb_bahan WHERE id_bahan = ?");
        mysqli_stmt_bind_param($stmt_del, 'i', $id_bahan);
        mysqli_stmt_execute($stmt_del);
        mysqli_stmt_close($stmt_del);

        // Refresh session alert stok
        $q_alert = mysqli_query($koneksi, "SELECT COUNT(*) AS total_menipis FROM tb_bahan WHERE stok <= stok_minimum");
        $_SESSION['alert_stok'] = (int) mysqli_fetch_assoc($q_alert)['total_menipis'];

        mysqli_commit($koneksi);
        header("Location: index.php?success=Bahan+" . urlencode($bahan['nama_bahan']) . "+berhasil+dihapus");
        exit;

    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        $error_hapus = 'Gagal menghapus bahan: ' . $e->getMessage();
    }
}

// ─── Tampilan Halaman Konfirmasi ─────────────────────────────────────────────
$page_title = 'Hapus Bahan';
$active     = 'warehouse';
include '../../../_layout.php';
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="kc-card">
      <div class="kc-card-header" style="background:#fff5f5;border-color:#fca5a5;">
        <span style="color:#dc2626;"><i class='bx bx-trash'></i> Konfirmasi Hapus Bahan</span>
      </div>
      <div class="kc-card-body" style="text-align:center;padding:24px 16px;">
        <?php if (isset($error_hapus)): ?>
          <div class="alert alert-danger py-2 mb-3" style="font-size:12px;text-align:left;">
            <i class='bx bx-error-circle'></i> <?= htmlspecialchars($error_hapus) ?>
          </div>
        <?php endif; ?>

        <div style="font-size:32px;margin-bottom:12px;">⚠️</div>
        <p style="font-size:14px;font-weight:700;color:#1c1007;margin-bottom:6px;">
          Hapus bahan ini?
        </p>
        <p style="font-size:13px;color:#92400e;margin-bottom:4px;">
          <strong>"<?= htmlspecialchars($bahan['nama_bahan']) ?>"</strong>
        </p>

        <?php if ($jumlah_resep > 0): ?>
          <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:4px;padding:10px 14px;margin:12px 0;text-align:left;font-size:12px;color:#92400e;">
            <strong>⚠️ Peringatan:</strong> Bahan ini digunakan dalam
            <strong><?= $jumlah_resep ?> resep menu</strong>.
            Menghapus bahan ini akan menghapus juga entri resep tersebut dan seluruh log stoknya.
          </div>
        <?php else: ?>
          <p style="font-size:12px;color:#a07850;margin-top:8px;">
            Seluruh log stok bahan ini juga akan dihapus.
          </p>
        <?php endif; ?>

        <form method="POST" style="display:inline-flex;gap:10px;margin-top:16px;">
          <button type="submit" name="konfirmasi_hapus" class="btn-kc-danger" id="btn-konfirmasi-hapus"
            style="padding:6px 16px;font-size:12px;">
            <i class='bx bx-trash'></i> Ya, Hapus
          </button>
          <a href="index.php" class="btn-kc-outline" style="padding:6px 16px;font-size:12px;">
            <i class='bx bx-x'></i> Batal
          </a>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../../../_layout_end.php'; ?>
