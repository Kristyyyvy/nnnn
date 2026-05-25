<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'function/auth.php';
checkRole(['admin']);
include 'function/connect.php';

$tab = $_GET['tab'] ?? 'dashboard';
$page_title = match ($tab) {
  'menu'    => 'Kelola Menu',
  'user'    => 'Kelola User',
  'pesanan' => 'Pesanan',
  'laporan' => 'Laporan',
  default   => 'Dashboard',
};
$active = $tab === 'dashboard' ? 'dashboard' : $tab;
include '_layout.php';
?>

<?php if ($tab === 'dashboard'): ?>

  <?php
  $omzet    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT IFNULL(SUM(total_harga),0) AS n FROM tb_pesanan WHERE status_bayar='lunas' AND DATE(tgl_pesanan)=CURDATE()"))['n'];
  $trx      = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS n FROM tb_pesanan WHERE DATE(tgl_pesanan)=CURDATE()"))['n'];
  $belum    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS n FROM tb_pesanan WHERE status_bayar='belum_bayar'"))['n'];
  $tot_menu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS n FROM tb_menu"))['n'];
  ?>

  <div class="stat-grid">
    <div class="stat-box">
      <div class="stat-label">Omzet Hari Ini</div>
      <div class="stat-value">Rp <?= number_format($omzet, 0, ',', '.') ?></div>
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

  <div class="kc-card">
    <div class="kc-card-header"><span><i class='bx bx-receipt'></i> Pesanan Terbaru</span></div>
    <table class="kc-table w-100">
      <thead>
        <tr>
          <th>#</th>
          <th>Pelanggan</th>
          <th>Meja</th>
          <th>Total</th>
          <th>Status Bayar</th>
          <th>Status Pesanan</th>
        </tr>
      </thead>
      <tbody>
        <?php $q = mysqli_query($koneksi, "SELECT * FROM tb_pesanan ORDER BY id_pesanan DESC LIMIT 10");
        while ($r = mysqli_fetch_assoc($q)): ?>
          <tr>
            <td style="color:#a07850"><?= $r['id_pesanan'] ?></td>
            <td><?= htmlspecialchars($r['nama_pelanggan']) ?></td>
            <td>Meja <?= $r['no_meja'] ?></td>
            <td>Rp <?= number_format($r['total_harga'], 0, ',', '.') ?></td>
            <td><span class="kc-badge <?= $r['status_bayar'] === 'lunas' ? 'kc-badge-green' : 'kc-badge-yellow' ?>"><?= $r['status_bayar'] ?></span></td>
            <td><span class="kc-badge <?= $r['status_pesanan'] === 'selesai' ? 'kc-badge-brown' : 'kc-badge-blue' ?>"><?= $r['status_pesanan'] ?></span></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

<?php elseif ($tab === 'menu'): ?>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="kc-card">
        <div class="kc-card-header"><i class='bx bx-plus-circle'></i> Tambah Menu</div>
        <div class="kc-card-body">
          <form action="function/function_menu/add_menu.php" method="POST">
            <div class="mb-2"><label class="form-label">Nama menu</label><input type="text" name="nama_menu" class="form-control form-control-sm" placeholder="Contoh: Nasi Goreng" required></div>
            <div class="mb-2"><label class="form-label">Kategori</label>
              <select name="kategori" class="form-select form-select-sm" required>
                <option value="makanan">Makanan</option>
                <option value="minuman">Minuman</option>
              </select>
            </div>
            <div class="mb-2"><label class="form-label">Harga (Rp)</label><input type="number" name="harga" class="form-control form-control-sm" placeholder="0" required></div>
            <div class="mb-3"><label class="form-label">Stok</label><input type="number" name="stok" class="form-control form-control-sm" placeholder="0" required></div>
            <button type="submit" name="add_menu" class="btn-kc btn-kc-sm"><i class='bx bx-save'></i> Simpan</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="kc-card">
        <div class="kc-card-header"><i class='bx bx-list-ul'></i> Daftar Menu</div>
        <table class="kc-table w-100">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Kategori</th>
              <th>Harga</th>
              <th>Stok</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
            $q = mysqli_query($koneksi, "SELECT * FROM tb_menu ORDER BY id_menu DESC");
            while ($d = mysqli_fetch_assoc($q)): ?>
              <tr>
                <td style="color:#a07850"><?= $no++ ?></td>
                <td><?= htmlspecialchars($d['nama_menu']) ?></td>
                <td><span class="kc-badge <?= $d['kategori'] === 'makanan' ? 'kc-badge-brown' : 'kc-badge-blue' ?>"><?= ucfirst($d['kategori']) ?></span></td>
                <td>Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
                <td <?= $d['stok'] <= 5 ? 'style="color:#dc2626;font-weight:600"' : '' ?>><?= $d['stok'] ?></td>
                <td>
                  <a href="edit_menu.php?id=<?= $d['id_menu'] ?>" class="btn-kc-outline"><i class='bx bx-edit'></i></a>
                  <a href="function/function_menu/delete_menu.php?id=<?= $d['id_menu'] ?>" class="btn-kc-danger ms-1" onclick="return confirm('Hapus menu ini?')"><i class='bx bx-trash'></i></a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<?php elseif ($tab === 'user'): ?>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="kc-card">
        <div class="kc-card-header"><i class='bx bx-user-plus'></i> Tambah User</div>
        <div class="kc-card-body">
          <form action="function/function_user/add_user.php" method="POST">
            <div class="mb-2"><label class="form-label">Username</label><input type="text" name="username" class="form-control form-control-sm" placeholder="Username" required></div>
            <div class="mb-2"><label class="form-label">Password</label><input type="password" name="password" class="form-control form-control-sm" placeholder="Password" required></div>
            <div class="mb-3"><label class="form-label">Role</label>
              <select name="role" class="form-select form-select-sm" required>
                <option value="admin">Admin</option>
                <option value="kasir">Kasir</option>
                <option value="dapur">Dapur</option>
              </select>
            </div>
            <button type="submit" name="add_user" class="btn-kc btn-kc-sm"><i class='bx bx-save'></i> Simpan</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="kc-card">
        <div class="kc-card-header"><i class='bx bx-group'></i> Daftar User</div>
        <table class="kc-table w-100">
          <thead>
            <tr>
              <th>#</th>
              <th>Username</th>
              <th>Role</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
            $q = mysqli_query($koneksi, "SELECT * FROM tb_user ORDER BY id_user");
            while ($d = mysqli_fetch_assoc($q)): ?>
              <tr>
                <td style="color:#a07850"><?= $no++ ?></td>
                <td><?= htmlspecialchars($d['username']) ?></td>
                <td><span class="kc-badge kc-badge-gray"><?= ucfirst($d['role']) ?></span></td>
                <td>
                  <a href="edit_user.php?id=<?= $d['id_user'] ?>" class="btn-kc-outline"><i class='bx bx-edit'></i></a>
                  <a href="function/function_user/delete_user.php?id=<?= $d['id_user'] ?>" class="btn-kc-danger ms-1" onclick="return confirm('Hapus user ini?')"><i class='bx bx-trash'></i></a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<?php elseif ($tab === 'pesanan'): ?>

  <div class="kc-card">
    <div class="kc-card-header"><i class='bx bx-receipt'></i> Semua Pesanan</div>
    <table class="kc-table w-100">
      <thead>
        <tr>
          <th>#</th>
          <th>Pelanggan</th>
          <th>Meja</th>
          <th>Total</th>
          <th>Status Bayar</th>
          <th>Status Pesanan</th>
          <th>Tanggal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $q = mysqli_query($koneksi, "SELECT * FROM tb_pesanan ORDER BY id_pesanan DESC");
        while ($r = mysqli_fetch_assoc($q)): ?>
          <tr>
            <td style="color:#a07850"><?= $r['id_pesanan'] ?></td>
            <td><?= htmlspecialchars($r['nama_pelanggan']) ?></td>
            <td>Meja <?= $r['no_meja'] ?></td>
            <td>Rp <?= number_format($r['total_harga'], 0, ',', '.') ?></td>
            <td><span class="kc-badge <?= $r['status_bayar'] === 'lunas' ? 'kc-badge-green' : 'kc-badge-yellow' ?>"><?= $r['status_bayar'] ?></span></td>
            <td><span class="kc-badge <?= $r['status_pesanan'] === 'selesai' ? 'kc-badge-brown' : 'kc-badge-blue' ?>"><?= $r['status_pesanan'] ?></span></td>
            <td style="font-size:11px;color:#a07850"><?= $r['tgl_pesanan'] ?></td>
            <td><a href="function/function_pesanan/delete_pesanan.php?id=<?= $r['id_pesanan'] ?>" class="btn-kc-danger" onclick="return confirm('Hapus pesanan ini?')"><i class='bx bx-trash'></i></a></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

<?php elseif ($tab === 'laporan'): ?>

<?php
// Date filter for laporan
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$filter_clause = '';
if ($start_date && $end_date) {
    $filter_clause = "WHERE tgl_pesanan BETWEEN '$start_date' AND '$end_date'";
}
?>
<div class="kc-card mb-3">
  <div class="kc-card-body p-3" style="display:flex;gap:8px;align-items:center;">
    <input type="date" id="start-date" class="form-control form-control-sm" placeholder="Tanggal mulai" value="<?= htmlspecialchars($start_date) ?>"/>
    <input type="date" id="end-date" class="form-control form-control-sm" placeholder="Tanggal akhir" value="<?= htmlspecialchars($end_date) ?>"/>
    <button class="btn btn-primary btn-sm" onclick="filterLaporan()">Filter</button>
  </div>
</div>
<script>
function filterLaporan() {
  const start = document.getElementById('start-date').value;
  const end = document.getElementById('end-date').value;
  const url = new URL(window.location.href);
  url.searchParams.set('start_date', start);
  url.searchParams.set('end_date', end);
  window.location = url.toString();
}
</script>
  <?php
  $omzet_total = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT IFNULL(SUM(total_harga),0) AS n FROM tb_pesanan WHERE status_bayar='lunas' $filter_clause"))['n'];
  $cnt_lunas   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS n FROM tb_pesanan WHERE status_bayar='lunas' $filter_clause"))['n'];
  $cnt_belum   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS n FROM tb_pesanan WHERE status_bayar='belum_bayar' $filter_clause"))['n'];
  ?>

  <div class="stat-grid" style="grid-template-columns:repeat(3,1fr)">
    <div class="stat-box">
      <div class="stat-label">Total Pendapatan</div>
      <div class="stat-value">Rp <?= number_format($omzet_total, 0, ',', '.') ?></div>
    </div>
    <div class="stat-box">
      <div class="stat-label">Pesanan Lunas</div>
      <div class="stat-value dark"><?= $cnt_lunas ?></div>
    </div>
    <div class="stat-box">
      <div class="stat-label">Belum Bayar</div>
      <div class="stat-value blue"><?= $cnt_belum ?></div>
    </div>
  </div>

  <div class="kc-card">
    <div class="kc-card-header"><i class='bx bx-bar-chart-alt-2'></i> Riwayat Transaksi</div>
    <table class="kc-table w-100">
      <thead>
        <tr>
          <th>#</th>
          <th>Tanggal</th>
          <th>Pelanggan</th>
          <th>Meja</th>
          <th>Total</th>
          <th>Status Bayar</th>
          <th>Status Pesanan</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1;
        $q = mysqli_query($koneksi, "SELECT * FROM tb_pesanan ORDER BY id_pesanan DESC");
        while ($r = mysqli_fetch_assoc($q)): ?>
          <tr>
            <td style="color:#a07850"><?= $no++ ?></td>
            <td style="font-size:11px;color:#a07850"><?= $r['tgl_pesanan'] ?></td>
            <td><?= htmlspecialchars($r['nama_pelanggan']) ?></td>
            <td>Meja <?= $r['no_meja'] ?></td>
            <td>Rp <?= number_format($r['total_harga'], 0, ',', '.') ?></td>
            <td><span class="kc-badge <?= $r['status_bayar'] === 'lunas' ? 'kc-badge-green' : 'kc-badge-yellow' ?>"><?= $r['status_bayar'] ?></span></td>
            <td><span class="kc-badge <?= $r['status_pesanan'] === 'selesai' ? 'kc-badge-brown' : 'kc-badge-blue' ?>"><?= $r['status_pesanan'] ?></span></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

<?php endif; ?>

<?php include '_layout_end.php'; ?>