-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 30, 2026 at 01:13 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_resto`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_bahan`
--

CREATE TABLE `tb_bahan` (
  `id_bahan` int NOT NULL,
  `nama_bahan` varchar(200) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `stok` decimal(10,2) DEFAULT '0.00',
  `stok_minimum` decimal(10,2) DEFAULT '0.00',
  `satuan` varchar(50) DEFAULT NULL,
  `harga_modal` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_bahan`
--

INSERT INTO `tb_bahan` (`id_bahan`, `nama_bahan`, `kategori`, `stok`, `stok_minimum`, `satuan`, `harga_modal`) VALUES
(1, 'Beras', 'karbohidrat', 50.00, 10.00, 'kg', 12000.00),
(2, 'Mie Kering', 'karbohidrat', 20.00, 5.00, 'kg', 18000.00),
(3, 'Lontong (bungkus)', 'karbohidrat', 80.00, 20.00, 'pcs', 2500.00),
(4, 'Ayam Potong', 'protein', 15.00, 5.00, 'kg', 35000.00),
(5, 'Daging Sapi', 'protein', 10.00, 3.00, 'kg', 120000.00),
(6, 'Ikan Lele', 'protein', 12.00, 3.00, 'kg', 22000.00),
(7, 'Ikan Bakar (fillet)', 'protein', 8.00, 2.00, 'kg', 45000.00),
(8, 'Telur Ayam', 'protein', 100.00, 30.00, 'butir', 2000.00),
(9, 'Tahu Putih', 'protein', 50.00, 15.00, 'pcs', 1500.00),
(10, 'Tempe', 'protein', 40.00, 10.00, 'pcs', 2000.00),
(11, 'Kangkung', 'sayur', 10.00, 3.00, 'ikat', 3000.00),
(12, 'Kol', 'sayur', 8.00, 2.00, 'kg', 6000.00),
(13, 'Taoge', 'sayur', 5.00, 1.00, 'kg', 8000.00),
(14, 'Tomat', 'sayur', 5.00, 1.00, 'kg', 10000.00),
(15, 'Bawang Merah', 'bumbu', 10.00, 2.00, 'kg', 30000.00),
(16, 'Bawang Putih', 'bumbu', 10.00, 2.00, 'kg', 28000.00),
(17, 'Cabai Merah', 'bumbu', 5.00, 1.00, 'kg', 50000.00),
(18, 'Kecap Manis', 'bumbu', 10.00, 2.00, 'liter', 18000.00),
(19, 'Minyak Goreng', 'minyak_saus', 20.00, 5.00, 'liter', 17000.00),
(20, 'Santan Kara', 'minyak_saus', 30.00, 10.00, 'pcs', 4500.00);

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_pesanan`
--

CREATE TABLE `tb_detail_pesanan` (
  `id_detail` int NOT NULL,
  `id_pesanan` int NOT NULL,
  `id_menu` int NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` int NOT NULL,
  `catatan` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_meja`
--

CREATE TABLE `tb_meja` (
  `id_meja` int NOT NULL,
  `nomor_meja` int NOT NULL,
  `status` enum('kosong','terisi') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_meja`
--

INSERT INTO `tb_meja` (`id_meja`, `nomor_meja`, `status`) VALUES
(1, 1, 'kosong'),
(2, 2, 'kosong'),
(3, 3, 'terisi'),
(4, 4, 'kosong'),
(5, 5, 'kosong'),
(6, 6, 'kosong'),
(7, 7, 'kosong'),
(8, 8, 'kosong'),
(9, 9, 'kosong'),
(10, 10, 'kosong'),
(11, 11, 'kosong'),
(12, 12, 'kosong'),
(13, 13, 'kosong'),
(14, 14, 'kosong'),
(15, 15, 'kosong'),
(16, 16, 'kosong'),
(17, 17, 'kosong'),
(18, 18, 'kosong'),
(19, 19, 'kosong'),
(20, 20, 'kosong'),
(21, 21, 'kosong'),
(22, 22, 'kosong'),
(23, 23, 'kosong'),
(24, 24, 'kosong'),
(25, 25, 'kosong'),
(26, 26, 'kosong'),
(27, 27, 'kosong'),
(28, 28, 'kosong'),
(29, 29, 'kosong'),
(30, 30, 'kosong'),
(31, 31, 'kosong'),
(32, 32, 'kosong'),
(33, 33, 'kosong'),
(34, 34, 'kosong'),
(35, 35, 'kosong'),
(36, 36, 'kosong'),
(37, 37, 'kosong'),
(38, 38, 'kosong'),
(39, 39, 'kosong'),
(40, 40, 'kosong'),
(41, 41, 'kosong'),
(42, 42, 'kosong'),
(43, 43, 'kosong'),
(44, 44, 'kosong'),
(45, 45, 'kosong'),
(46, 46, 'kosong'),
(47, 47, 'kosong'),
(48, 48, 'kosong'),
(49, 49, 'kosong'),
(50, 50, 'kosong'),
(51, 51, 'kosong'),
(52, 52, 'kosong'),
(53, 53, 'kosong'),
(54, 54, 'kosong'),
(55, 55, 'kosong'),
(56, 56, 'kosong'),
(57, 57, 'kosong'),
(58, 58, 'kosong'),
(59, 59, 'kosong'),
(60, 60, 'kosong'),
(61, 61, 'kosong'),
(62, 62, 'kosong'),
(63, 63, 'kosong'),
(64, 64, 'kosong'),
(65, 65, 'kosong'),
(66, 66, 'kosong'),
(67, 67, 'kosong'),
(68, 68, 'kosong'),
(69, 69, 'kosong'),
(70, 70, 'kosong'),
(71, 71, 'kosong'),
(72, 72, 'kosong'),
(73, 73, 'kosong'),
(74, 74, 'kosong'),
(75, 75, 'kosong'),
(76, 76, 'kosong'),
(77, 77, 'kosong'),
(78, 78, 'kosong'),
(79, 79, 'kosong'),
(80, 80, 'kosong'),
(81, 81, 'kosong'),
(82, 82, 'kosong'),
(83, 83, 'kosong'),
(84, 84, 'kosong'),
(85, 85, 'kosong'),
(86, 86, 'kosong'),
(87, 87, 'kosong'),
(88, 88, 'kosong'),
(89, 89, 'kosong'),
(90, 90, 'kosong'),
(91, 91, 'kosong'),
(92, 92, 'kosong'),
(93, 93, 'kosong'),
(94, 94, 'kosong'),
(95, 95, 'kosong'),
(96, 96, 'kosong'),
(97, 97, 'kosong'),
(98, 98, 'kosong'),
(99, 99, 'kosong'),
(100, 100, 'kosong');

-- --------------------------------------------------------

--
-- Table structure for table `tb_menu`
--

CREATE TABLE `tb_menu` (
  `id_menu` int NOT NULL,
  `nama_menu` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` enum('makanan','minuman') COLLATE utf8mb4_general_ci NOT NULL,
  `harga` int NOT NULL,
  `stok` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_menu`
--

INSERT INTO `tb_menu` (`id_menu`, `nama_menu`, `kategori`, `harga`, `stok`) VALUES
(17, 'Nasi Goreng', 'makanan', 15000, 50),
(18, 'Mie Goreng', 'makanan', 14000, 50),
(19, 'Ayam Goreng', 'makanan', 18000, 50),
(20, 'Ayam Bakar', 'makanan', 20000, 50),
(21, 'Sate Ayam', 'makanan', 22000, 50),
(22, 'Bakso', 'makanan', 13000, 50),
(23, 'Soto Ayam', 'makanan', 15000, 50),
(24, 'Nasi Uduk', 'makanan', 12000, 50),
(25, 'Nasi Kuning', 'makanan', 13000, 50),
(26, 'Gado-Gado', 'makanan', 14000, 50),
(27, 'Rendang', 'makanan', 25000, 50),
(28, 'Rawon', 'makanan', 23000, 50),
(29, 'Pempek', 'makanan', 20000, 50),
(30, 'Lontong Sayur', 'makanan', 12000, 50),
(31, 'Tahu Tek', 'makanan', 11000, 50),
(32, 'Ketoprak', 'makanan', 11000, 50),
(33, 'Nasi Campur', 'makanan', 18000, 50),
(34, 'Ayam Penyet', 'makanan', 17000, 50),
(35, 'Lele Goreng', 'makanan', 15000, 50),
(36, 'Ikan Bakar', 'makanan', 21000, 50);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pesanan`
--

CREATE TABLE `tb_pesanan` (
  `id_pesanan` int NOT NULL,
  `nama_pelanggan` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `no_meja` int NOT NULL,
  `total_harga` int NOT NULL,
  `status_bayar` enum('belum_bayar','lunas') COLLATE utf8mb4_general_ci NOT NULL,
  `status_pesanan` enum('proses','selesai') COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_pesanan` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `uang_bayar` int NOT NULL,
  `nama_kasir` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `metode_bayar` enum('tunai','qris','transfer','kartu') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'tunai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_resep`
--

CREATE TABLE `tb_resep` (
  `id_resep` int NOT NULL,
  `id_menu` int NOT NULL,
  `id_bahan` int NOT NULL,
  `jumlah` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_resep`
--

INSERT INTO `tb_resep` (`id_resep`, `id_menu`, `id_bahan`, `jumlah`) VALUES
(1, 17, 1, 0.25),
(2, 17, 8, 1.00),
(3, 17, 15, 0.03),
(4, 17, 16, 0.02),
(5, 17, 18, 0.02),
(6, 17, 19, 0.03),
(7, 18, 2, 0.10),
(8, 18, 8, 1.00),
(9, 18, 12, 0.05),
(10, 18, 15, 0.02),
(11, 18, 16, 0.02),
(12, 18, 19, 0.03),
(13, 19, 4, 0.25),
(14, 19, 15, 0.03),
(15, 19, 16, 0.02),
(16, 19, 17, 0.02),
(17, 19, 19, 0.05),
(18, 20, 4, 0.30),
(19, 20, 16, 0.02),
(20, 20, 18, 0.03),
(21, 20, 17, 0.02),
(22, 21, 4, 0.20),
(23, 21, 15, 0.02),
(24, 21, 18, 0.03),
(25, 21, 16, 0.01),
(26, 22, 5, 0.15),
(27, 22, 16, 0.02),
(28, 22, 14, 0.05),
(29, 23, 4, 0.20),
(30, 23, 15, 0.02),
(31, 23, 16, 0.02),
(32, 23, 14, 0.05),
(33, 23, 13, 0.03),
(34, 24, 1, 0.25),
(35, 24, 20, 0.10),
(36, 24, 16, 0.01),
(37, 25, 1, 0.25),
(38, 25, 20, 0.10),
(39, 25, 15, 0.01),
(40, 26, 9, 1.00),
(41, 26, 10, 1.00),
(42, 26, 13, 0.05),
(43, 26, 12, 0.05),
(44, 26, 8, 1.00),
(45, 27, 5, 0.30),
(46, 27, 20, 0.20),
(47, 27, 15, 0.05),
(48, 27, 17, 0.03),
(49, 28, 5, 0.25),
(50, 28, 15, 0.03),
(51, 28, 16, 0.02),
(52, 28, 14, 0.05),
(53, 33, 1, 0.25),
(54, 33, 4, 0.15),
(55, 33, 9, 1.00),
(56, 33, 10, 1.00),
(57, 35, 6, 0.25),
(58, 35, 16, 0.02),
(59, 35, 17, 0.02),
(60, 35, 19, 0.05),
(61, 36, 7, 0.25),
(62, 36, 18, 0.03),
(63, 36, 17, 0.02),
(64, 36, 15, 0.02);

-- --------------------------------------------------------

--
-- Table structure for table `tb_stok_log`
--

CREATE TABLE `tb_stok_log` (
  `id_log` int NOT NULL,
  `id_bahan` int NOT NULL,
  `jenis` enum('masuk','keluar','terpakai') NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `keterangan` varchar(300) DEFAULT NULL,
  `id_pesanan` int DEFAULT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int NOT NULL,
  `username` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('Owner','Admin','Kasir','Dapur') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'Owner', '$2y$10$SjJlpUQZiDiMuLUZtPA/Euj7NaG3VO28XoSb0UAOw58n2duJ4aiBu', 'Owner'),
(2, 'Admin', '$2y$10$CGnSl1rtHXNW9b/P.6a1DevfYZBDdCA9p0lVKr3fVSdJScLmbeRPa', 'Admin'),
(3, 'Kasir', '$2y$10$NO06qewOIHg71qoS/VrLveOPxZ5gPWMGe/SRyzt1xk7uTTDVcA5g6', 'Kasir'),
(4, 'Dapur', '$2y$10$pUN.eI8E45IjgjJfVi8rBO1yVK/73yyPZOCM8U08ykIaevIgoO.ke', 'Dapur');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_bahan`
--
ALTER TABLE `tb_bahan`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indexes for table `tb_detail_pesanan`
--
ALTER TABLE `tb_detail_pesanan`
  ADD PRIMARY KEY (`id_detail`);

--
-- Indexes for table `tb_meja`
--
ALTER TABLE `tb_meja`
  ADD PRIMARY KEY (`id_meja`);

--
-- Indexes for table `tb_menu`
--
ALTER TABLE `tb_menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  ADD PRIMARY KEY (`id_pesanan`);

--
-- Indexes for table `tb_resep`
--
ALTER TABLE `tb_resep`
  ADD PRIMARY KEY (`id_resep`),
  ADD KEY `id_menu` (`id_menu`),
  ADD KEY `id_bahan` (`id_bahan`);

--
-- Indexes for table `tb_stok_log`
--
ALTER TABLE `tb_stok_log`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_bahan` (`id_bahan`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_bahan`
--
ALTER TABLE `tb_bahan`
  MODIFY `id_bahan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_detail_pesanan`
--
ALTER TABLE `tb_detail_pesanan`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tb_meja`
--
ALTER TABLE `tb_meja`
  MODIFY `id_meja` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `tb_menu`
--
ALTER TABLE `tb_menu`
  MODIFY `id_menu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tb_resep`
--
ALTER TABLE `tb_resep`
  MODIFY `id_resep` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tb_stok_log`
--
ALTER TABLE `tb_stok_log`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_resep`
--
ALTER TABLE `tb_resep`
  ADD CONSTRAINT `tb_resep_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `tb_menu` (`id_menu`),
  ADD CONSTRAINT `tb_resep_ibfk_2` FOREIGN KEY (`id_bahan`) REFERENCES `tb_bahan` (`id_bahan`);

--
-- Constraints for table `tb_stok_log`
--
ALTER TABLE `tb_stok_log`
  ADD CONSTRAINT `tb_stok_log_ibfk_1` FOREIGN KEY (`id_bahan`) REFERENCES `tb_bahan` (`id_bahan`),
  ADD CONSTRAINT `tb_stok_log_ibfk_2` FOREIGN KEY (`id_pesanan`) REFERENCES `tb_pesanan` (`id_pesanan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
