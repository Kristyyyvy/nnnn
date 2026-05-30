<?php
/**
 * auto_kurang_stok.php
 * Fungsi inti integrasi POS ↔ Warehouse
 * 
 * Dipanggil setelah status_pesanan berhasil diupdate menjadi 'selesai'.
 * Mengurangi stok bahan baku berdasarkan resep tiap menu dalam pesanan.
 * 
 * @param mysqli $koneksi   - Koneksi database yang sudah ada
 * @param int    $id_pesanan - ID pesanan yang baru selesai
 * @return array ['success' => bool, 'message' => string, 'warnings' => array]
 */
function autoKurangStok(mysqli $koneksi, int $id_pesanan): array
{
    $warnings = [];

    // ─── MULAI TRANSACTION ────────────────────────────────────────────────────
    // Semua operasi stok harus atomik: jika satu gagal, semuanya dibatalkan
    mysqli_begin_transaction($koneksi);

    try {
        // ── LANGKAH 1: Ambil semua item dalam pesanan ini ──────────────────────
        $stmt_detail = mysqli_prepare(
            $koneksi,
            "SELECT id_menu, jumlah FROM tb_detail_pesanan WHERE id_pesanan = ?"
        );
        if (!$stmt_detail) {
            throw new Exception("Prepare detail_pesanan gagal: " . mysqli_error($koneksi));
        }
        mysqli_stmt_bind_param($stmt_detail, "i", $id_pesanan);
        mysqli_stmt_execute($stmt_detail);
        $result_detail = mysqli_stmt_get_result($stmt_detail);
        $items = [];
        while ($row = mysqli_fetch_assoc($result_detail)) {
            $items[] = $row;
        }
        mysqli_stmt_close($stmt_detail);

        // Jika pesanan tidak ada item, keluar tanpa error
        if (empty($items)) {
            mysqli_commit($koneksi);
            return ['success' => true, 'message' => 'Tidak ada item pesanan.', 'warnings' => []];
        }

        // ── LANGKAH 2: Untuk setiap item, ambil resep & kurangi stok ──────────
        foreach ($items as $item) {
            $id_menu       = (int) $item['id_menu'];
            $jumlah_pesan  = (float) $item['jumlah']; // berapa porsi dipesan

            // Ambil daftar bahan untuk menu ini dari tabel resep
            $stmt_resep = mysqli_prepare(
                $koneksi,
                "SELECT r.id_bahan, r.jumlah AS jumlah_per_porsi, b.nama_bahan, b.stok
                 FROM tb_resep r
                 JOIN tb_bahan b ON b.id_bahan = r.id_bahan
                 WHERE r.id_menu = ?"
            );
            if (!$stmt_resep) {
                throw new Exception("Prepare tb_resep gagal: " . mysqli_error($koneksi));
            }
            mysqli_stmt_bind_param($stmt_resep, "i", $id_menu);
            mysqli_stmt_execute($stmt_resep);
            $result_resep = mysqli_stmt_get_result($stmt_resep);

            while ($bahan = mysqli_fetch_assoc($result_resep)) {
                $id_bahan        = (int) $bahan['id_bahan'];
                $stok_sekarang   = (float) $bahan['stok'];
                // Hitung total bahan yang dipakai: jumlah_per_porsi × jumlah_pesan
                $jumlah_kurang   = (float) $bahan['jumlah_per_porsi'] * $jumlah_pesan;
                $nama_bahan      = $bahan['nama_bahan'];

                // ── Cek apakah stok akan menjadi negatif ──────────────────────
                $stok_baru = $stok_sekarang - $jumlah_kurang;
                if ($stok_baru < 0) {
                    // Catat warning tapi TETAP LANJUTKAN (tidak block pesanan)
                    $warnings[] = "⚠️ Stok {$nama_bahan} minus ({$stok_sekarang} → {$stok_baru})";
                    $keterangan_log = "Stok minus - cek supplier (Pesanan #{$id_pesanan})";
                } else {
                    $keterangan_log = "Auto dari pesanan #{$id_pesanan}";
                }

                // ── UPDATE stok bahan baku ──────────────────────────────────────
                // Stok boleh minus (tidak diblock), sesuai spesifikasi bisnis
                $stmt_update = mysqli_prepare(
                    $koneksi,
                    "UPDATE tb_bahan SET stok = stok - ? WHERE id_bahan = ?"
                );
                if (!$stmt_update) {
                    throw new Exception("Prepare UPDATE tb_bahan gagal: " . mysqli_error($koneksi));
                }
                mysqli_stmt_bind_param($stmt_update, "di", $jumlah_kurang, $id_bahan);
                $ok = mysqli_stmt_execute($stmt_update);
                mysqli_stmt_close($stmt_update);

                if (!$ok) {
                    throw new Exception("Gagal update stok bahan ID {$id_bahan}: " . mysqli_error($koneksi));
                }

                // ── INSERT log penggunaan stok ──────────────────────────────────
                $stmt_log = mysqli_prepare(
                    $koneksi,
                    "INSERT INTO tb_stok_log (id_bahan, jenis, jumlah, keterangan, id_pesanan)
                     VALUES (?, 'terpakai', ?, ?, ?)"
                );
                if (!$stmt_log) {
                    throw new Exception("Prepare INSERT tb_stok_log gagal: " . mysqli_error($koneksi));
                }
                mysqli_stmt_bind_param($stmt_log, "idsi", $id_bahan, $jumlah_kurang, $keterangan_log, $id_pesanan);
                $ok_log = mysqli_stmt_execute($stmt_log);
                mysqli_stmt_close($stmt_log);

                if (!$ok_log) {
                    throw new Exception("Gagal insert stok log bahan ID {$id_bahan}: " . mysqli_error($koneksi));
                }
            } // end foreach bahan
            mysqli_stmt_close($stmt_resep);
        } // end foreach item

        // ── LANGKAH 3: Refresh session badge alert stok ────────────────────────
        // Hitung ulang berapa bahan yang stoknya menipis/habis
        $q_alert = mysqli_query($koneksi, "SELECT COUNT(*) AS total_menipis FROM tb_bahan WHERE stok <= stok_minimum");
        if ($q_alert) {
            $row_alert = mysqli_fetch_assoc($q_alert);
            $_SESSION['alert_stok'] = (int) $row_alert['total_menipis'];
        }

        // ─── COMMIT: Semua berhasil ────────────────────────────────────────────
        mysqli_commit($koneksi);

        return [
            'success'  => true,
            'message'  => 'Stok berhasil dikurangi.',
            'warnings' => $warnings,
        ];

    } catch (Exception $e) {
        // ─── ROLLBACK: Ada yang gagal, batalkan semua perubahan stok ──────────
        mysqli_rollback($koneksi);
        return [
            'success'  => false,
            'message'  => 'Gagal kurangi stok: ' . $e->getMessage(),
            'warnings' => $warnings,
        ];
    }
}
