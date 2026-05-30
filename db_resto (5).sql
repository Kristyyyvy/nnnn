-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 30, 2026 at 06:35 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

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
  `id_detail` int NOT NULL,
  `id_pesanan` int NOT NULL,
  `id_menu` int NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` int NOT NULL,
  `catatan` text COLLATE utf8mb4_general_ci
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
(23, 24, 9, 1, 24000, 'n'),
(24, 25, 13, 2, 44000, NULL),
(25, 26, 12, 1, 12000, NULL),
(26, 27, 9, 1, 24000, NULL),
(27, 28, 9, 1, 24000, 'extra almond'),
(28, 29, 14, 1, 25000, 'esnya dikit'),
(29, 30, 6, 1, 20000, 'extra ice'),
(30, 31, 10, 1, 20000, NULL),
(31, 32, 3, 1, 22000, NULL),
(32, 33, 10, 1, 20000, NULL),
(33, 34, 10, 1, 20000, NULL),
(34, 35, 10, 1, 20000, NULL),
(35, 36, 9, 1, 24000, NULL),
(36, 37, 2, 1, 18000, NULL),
(37, 38, 4, 1, 16000, 'add cream'),
(38, 39, 1, 1, 15000, NULL);

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
(1, 'Sandwich', 'makanan', 15000, 17),
(2, 'Croissant Butter', 'makanan', 18000, 13),
(3, 'Cinnamon Roll', 'makanan', 22000, 6),
(4, 'Matcha Muffin', 'makanan', 16000, 19),
(6, 'Americano', 'minuman', 20000, 15),
(7, 'Lychee Tea', 'minuman', 17000, 13),
(8, 'Matcha Latte', 'minuman', 19000, 8),
(9, 'Almond Croissant', 'makanan', 24000, 6),
(10, 'Cheese Danish', 'makanan', 20000, 10),
(11, 'Red Velvet Muffin', 'makanan', 18000, 7),
(12, 'Banana Bread', 'makanan', 12000, 11),
(13, 'Cappucino', 'makanan', 22000, 10),
(14, 'Vanilla Latte', 'minuman', 25000, 9),
(15, 'Mocha', 'minuman', 26000, 22);

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
(24, 'aa', 6, 24000, 'lunas', 'selesai', '2026-05-26 15:16:34', 100908, 'Kasir', 'tunai'),
(25, 'Adinda', 3, 44000, 'lunas', 'selesai', '2026-05-28 03:22:49', 50000, 'Kasir', 'tunai'),
(26, 'Sam', 1, 12000, 'lunas', 'selesai', '2026-05-28 04:01:21', 50000, 'Kasir', 'tunai'),
(27, 'Kristyyy', 1, 24000, 'lunas', 'selesai', '2026-05-28 04:04:55', 100000, 'Kasir', 'tunai'),
(28, 'X', 2, 24000, 'lunas', 'selesai', '2026-05-28 04:15:15', 100000, 'Kasir', 'tunai'),
(29, 'Adinda', 4, 25000, 'lunas', 'selesai', '2026-05-28 04:20:05', 100000, 'Kasir', 'tunai'),
(30, 'Alin', 6, 20000, 'lunas', 'selesai', '2026-05-28 04:44:45', 50000, 'Kasir', 'tunai'),
(31, 'van', 1, 20000, 'lunas', 'selesai', '2026-05-28 05:12:06', 50000, 'Kasir', 'tunai'),
(32, 'a', 5, 22000, 'lunas', 'selesai', '2026-05-28 09:27:01', 50000, 'Kasir', 'tunai'),
(33, 'a', 7, 20000, 'lunas', 'selesai', '2026-05-28 09:44:35', 20000, 'Kasir', 'tunai'),
(34, 'asoy', 7, 20000, 'lunas', 'selesai', '2026-05-28 09:46:08', 20000, 'Kasir', 'tunai'),
(35, 'a', 1, 20000, 'lunas', 'selesai', '2026-05-28 09:47:48', 20000, 'Kasir', 'tunai'),
(36, 'IHIR', 7, 24000, 'lunas', 'selesai', '2026-05-28 09:48:45', 50000, 'Kasir', 'tunai'),
(37, 'h', 8, 18000, 'lunas', 'selesai', '2026-05-28 10:31:25', 50000, 'Kasir', 'tunai'),
(38, 'Kenanga', 1, 16000, 'lunas', 'selesai', '2026-05-29 06:15:29', 50000, 'Kasir', 'tunai'),
(39, 'Dinda', 1, 15000, 'lunas', 'selesai', '2026-05-30 02:33:33', 50000, 'Owner', 'tunai');

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
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tb_meja`
--
ALTER TABLE `tb_meja`
  MODIFY `id_meja` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `tb_menu`
--
ALTER TABLE `tb_menu`
  MODIFY `id_menu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
