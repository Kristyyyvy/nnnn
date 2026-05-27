-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2026 at 05:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `tb_detail_pesanan`
--

CREATE TABLE `tb_detail_pesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_detail_pesanan`
--

INSERT INTO `tb_detail_pesanan` (`id_detail`, `id_pesanan`, `id_menu`, `jumlah`, `subtotal`, `catatan`) VALUES
(3, 10, 2, 1, 18000, NULL),
(4, 10, 7, 1, 17000, NULL),
(5, 11, 6, 1, 20000, NULL),
(6, 11, 8, 1, 19000, NULL),
(7, 12, 1, 1, 15000, NULL),
(8, 12, 3, 1, 22000, NULL),
(9, 12, 4, 1, 16000, NULL),
(10, 12, 6, 1, 20000, NULL),
(12, 14, 10, 1, 20000, NULL),
(13, 15, 1, 1, 15000, NULL),
(14, 16, 8, 1, 19000, NULL),
(15, 17, 7, 1, 17000, NULL),
(16, 18, 4, 1, 16000, NULL),
(17, 19, 4, 1, 16000, NULL),
(18, 20, 12, 1, 12000, NULL),
(19, 21, 3, 1, 22000, NULL),
(20, 21, 6, 1, 20000, NULL),
(21, 22, 3, 1, 22000, NULL),
(23, 24, 9, 1, 24000, 'n');

-- --------------------------------------------------------

--
-- Table structure for table `tb_meja`
--

CREATE TABLE `tb_meja` (
  `id_meja` int(11) NOT NULL,
  `nomor_meja` int(11) NOT NULL,
  `status` enum('kosong','terisi') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_meja`
--

INSERT INTO `tb_meja` (`id_meja`, `nomor_meja`, `status`) VALUES
(1, 1, 'kosong'),
(2, 2, 'kosong'),
(3, 3, 'kosong'),
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
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `kategori` enum('makanan','minuman') NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_menu`
--

INSERT INTO `tb_menu` (`id_menu`, `nama_menu`, `kategori`, `harga`, `stok`) VALUES
(1, 'Sandwich', 'makanan', 15000, 18),
(2, 'Croissant Butter', 'makanan', 18000, 14),
(3, 'Cinnamon Roll', 'makanan', 22000, 7),
(4, 'Matcha Muffin', 'makanan', 16000, 2),
(6, 'Americano', 'minuman', 20000, 16),
(7, 'Lychee Tea', 'minuman', 17000, 13),
(8, 'Matcha Latte', 'minuman', 19000, 8),
(9, 'Almond Croissant', 'makanan', 24000, 9),
(10, 'Cheese Danish', 'makanan', 20000, 4),
(11, 'Red Velvet Muffin', 'makanan', 18000, 7),
(12, 'Banana Bread', 'makanan', 12000, 12),
(13, 'Cappucino', 'makanan', 22000, 7),
(14, 'Vanilla Latte', 'minuman', 25000, 10),
(15, 'Mocha', 'minuman', 26000, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pesanan`
--

CREATE TABLE `tb_pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `nama_pelanggan` varchar(200) NOT NULL,
  `no_meja` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `status_bayar` enum('belum_bayar','lunas') NOT NULL,
  `status_pesanan` enum('proses','selesai') NOT NULL,
  `tgl_pesanan` varchar(200) NOT NULL,
  `uang_bayar` int(11) NOT NULL,
  `nama_kasir` varchar(200) NOT NULL,
  `metode_bayar` enum('tunai','qris','transfer','kartu') NOT NULL DEFAULT 'tunai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pesanan`
--

INSERT INTO `tb_pesanan` (`id_pesanan`, `nama_pelanggan`, `no_meja`, `total_harga`, `status_bayar`, `status_pesanan`, `tgl_pesanan`, `uang_bayar`, `nama_kasir`, `metode_bayar`) VALUES
(10, 'Kristy', 2, 35000, 'lunas', 'selesai', '2026-05-18 01:02:00', 0, '', 'tunai'),
(11, 'Ajeng', 4, 39000, 'lunas', 'selesai', '2026-05-18 06:58:34', 0, '', 'tunai'),
(12, 'Adinda', 5, 73000, 'lunas', 'selesai', '2026-05-18 06:59:36', 0, '', 'tunai'),
(14, 'Kai', 6, 20000, 'lunas', 'selesai', '2026-05-19 21:04:51', 0, '', 'tunai'),
(15, 'Alan', 2, 15000, 'lunas', 'selesai', '2026-05-19 21:23:51', 0, '', 'tunai'),
(16, 'Lily', 2, 19000, 'lunas', 'selesai', '2026-05-19 21:53:37', 0, '', 'tunai'),
(17, 'Cally', 3, 17000, 'lunas', 'selesai', '2026-05-20 03:06:37', 0, '', 'tunai'),
(18, 'sitta', 4, 16000, 'lunas', 'selesai', '2026-05-20 03:38:23', 0, '', 'tunai'),
(19, 'sitta', 2, 16000, 'lunas', 'selesai', '2026-05-20 03:39:09', 0, 'Kasir', 'tunai'),
(20, 'Lalala', 3, 12000, 'lunas', 'selesai', '2026-05-25 19:56:02', 15000, 'Kasir', 'tunai'),
(21, 'Hoho', 1, 42000, 'lunas', 'selesai', '2026-05-25 20:17:43', 50000, 'Kasir', 'tunai'),
(22, 'Aselole', 3, 22000, 'lunas', 'selesai', '2026-05-26 05:30:29', 50000, 'Kasir', 'tunai'),
(24, 'aa', 6, 24000, 'lunas', 'selesai', '2026-05-26 15:16:34', 100908, 'Kasir', 'tunai');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` enum('Owner','Admin','Kasir','Dapur') NOT NULL
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
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_detail_pesanan`
--
ALTER TABLE `tb_detail_pesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tb_meja`
--
ALTER TABLE `tb_meja`
  MODIFY `id_meja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `tb_menu`
--
ALTER TABLE `tb_menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
