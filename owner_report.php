<?php
// Mulai sesi jika belum ada (untuk mengelola autentikasi)
if (session_status() === PHP_SESSION_NONE) session_start(); // Pastikan sesi aktif

// Memuat fungsi autentikasi
include 'function/auth.php'; // File berisi fungsi login dan pengecekan
// Memastikan user memiliki peran 'owner'
checkRole(['owner']); // Hanya pemilik yang dapat mengakses halaman ini

// Memuat koneksi database
include 'function/connect.php'; // Variabel $koneksi untuk koneksi MySQL

// Metadata halaman
$page_title = 'Laporan Harian/Mingguan'; // Judul yang ditampilkan di header
$active = 'owner'; // Menandai menu 'owner' sebagai aktif
include '_layout.php'; // Header, navigasi, dan layout umum
?>

<!-- Kontainer utama untuk form filter laporan -->
<div class="kc-card" style="margin-bottom:20px;">
    <div class="kc-card-body">
        <!-- Form pilih tipe laporan (hari atau minggu) dengan input yang sesuai -->
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <!-- Dropdown untuk memilih laporan harian atau mingguan -->
                <select name="type" class="form-select" required>
                    <option value="day" <?= (isset($_GET['type']) && $_GET['type']=='day') ? 'selected' : '' ?>>Hari</option>
                    <option value="week" <?= (isset($_GET['type']) && $_GET['type']=='week') ? 'selected' : '' ?>>Minggu</option>
                </select>
            </div>
            <!-- Input tanggal muncul bila tipe 'day' dipilih (diatur via JS) -->
            <div class="col-auto" id="day-input" style="display:none;">
                <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>" />
            </div>
            <!-- Input tahun dan minggu muncul bila tipe 'week' dipilih -->
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
// Proses permintaan laporan setelah form disubmit
if (isset($_GET['type'])) {
    $type = $_GET['type']; // Nilai 'day' atau 'week'
    $where = ""; // Placeholder klausa WHERE SQL yang akan dibangun
    $params = []; // Array untuk menampung nilai parameter prepared statement

    if ($type === 'day' && !empty($_GET['date'])) {
        // Laporan harian: filter data berdasarkan tanggal yang dipilih
        $date = $_GET['date'];
        $where = "DATE(tgl_pesanan) = ?"; // Membandingkan hanya bagian tanggal
        $params[] = $date; // Parameter tanggal untuk binding
    } elseif ($type === 'week' && !empty($_GET['year']) && !empty($_GET['week'])) {
        // Laporan mingguan: hitung tanggal Senin dan Minggu pada minggu ISO yang dipilih
        $year = (int)$_GET['year'];
        $week = (int)$_GET['week'];
        $dto = new DateTime();
        $dto->setISODate($year, $week); // Set ke Senin minggu tersebut
        $start = $dto->format('Y-m-d'); // Tanggal Senin
        $end = $dto->modify('+6 days')->format('Y-m-d'); // Tanggal Minggu
        $where = "DATE(tgl_pesanan) BETWEEN ? AND ?"; // Rentang antara Senin s/d Minggu
        $params[] = $start; // Parameter awal (Senin)
        $params[] = $end;   // Parameter akhir (Minggu)
    }

    if ($where) {
        // Buat prepared statement untuk menghitung total pesanan dan omzet (total_harga) yang sudah lunas
        $stmt = mysqli_prepare(
            $koneksi,
            "SELECT COUNT(*) AS total_orders, IFNULL(SUM(total_harga),0) AS omzet FROM tb_pesanan WHERE $where AND status_bayar='lunas'"
        );
        // Bind nilai parameter sesuai tipe laporan
        if ($type === 'day') {
            mysqli_stmt_bind_param($stmt, "s", $params[0]); // Satu parameter tanggal
        } else { // week
            mysqli_stmt_bind_param($stmt, "ss", $params[0], $params[1]); // Dua parameter: tanggal awal & akhir
        }
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($res); // Dapatkan hasil agregasi
        mysqli_stmt_close($stmt);
        ?>
        <div class="kc-card">
            <div class="kc-card-body">
                <h5>Laporan <?= $type === 'day' ? 'Hari' : 'Minggu' ?></h5>
                <p>Omzet: Rp <?= number_format($data['omzet'],0,',','.') ?></p>
                <a href="owner_report_pdf.php?<?= http_build_query($_GET) ?>" target="_blank" class="btn btn-danger"><i class='bx bxs-file-pdf'></i> Cetak / Simpan PDF</a>
            </div>
        </div>
        <?php
    }
}
?>
<?php include '_layout_end.php'; ?>
