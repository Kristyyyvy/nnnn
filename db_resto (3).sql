-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2026 at 05:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_detail_pesanan`
--

INSERT INTO `tb_detail_pesanan` (`id_detail`, `id_pesanan`, `id_menu`, `jumlah`, `subtotal`) VALUES
(3, 10, 2, 1, 18000),
(4, 10, 7, 1, 17000),
(5, 11, 6, 1, 20000),
(6, 11, 8, 1, 19000),
(7, 12, 1, 1, 15000),
(8, 12, 3, 1, 22000),
(9, 12, 4, 1, 16000),
(10, 12, 6, 1, 20000),
(12, 14, 10, 1, 20000),
(13, 15, 1, 1, 15000),
(14, 16, 8, 1, 19000),
(15, 17, 7, 1, 17000),
(16, 18, 4, 1, 16000),
(17, 19, 4, 1, 16000);

-- --------------------------------------------------------

--
-- Table structure for table `tb_meja`
--

CREATE TABLE `tb_meja` (
  `id_meja` int(11) NOT NULL,
  `nomor_meja` int(11) NOT NULL,
  `status` enum('kosong','terisi') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 'Cinnamon Roll', 'makanan', 22000, 9),
(4, 'Matcha Muffin', 'makanan', 16000, 2),
(6, 'Americano', 'minuman', 20000, 17),
(7, 'Lychee Tea', 'minuman', 17000, 13),
(8, 'Matcha Latte', 'minuman', 19000, 8),
(9, 'Almond Croissant', 'makanan', 24000, 10),
(10, 'Cheese Danish', 'makanan', 20000, 4),
(11, 'Red Velvet Muffin', 'makanan', 18000, 7),
(12, 'Banana Bread', 'makanan', 12000, 13),
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
  `nama_kasir` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pesanan`
--

INSERT INTO `tb_pesanan` (`id_pesanan`, `nama_pelanggan`, `no_meja`, `total_harga`, `status_bayar`, `status_pesanan`, `tgl_pesanan`, `uang_bayar`, `nama_kasir`) VALUES
(10, 'Kristy', 2, 35000, 'lunas', 'selesai', '2026-05-18 01:02:00', 0, ''),
(11, 'Ajeng', 4, 39000, 'lunas', 'selesai', '2026-05-18 06:58:34', 0, ''),
(12, 'Adinda', 5, 73000, 'lunas', 'selesai', '2026-05-18 06:59:36', 0, ''),
(14, 'Kai', 6, 20000, 'lunas', 'selesai', '2026-05-19 21:04:51', 0, ''),
(15, 'Alan', 2, 15000, 'lunas', 'selesai', '2026-05-19 21:23:51', 0, ''),
(16, 'Lily', 2, 19000, 'lunas', 'selesai', '2026-05-19 21:53:37', 0, ''),
(17, 'Cally', 3, 17000, 'lunas', 'selesai', '2026-05-20 03:06:37', 0, ''),
(18, 'sitta', 4, 16000, 'lunas', 'selesai', '2026-05-20 03:38:23', 0, ''),
(19, 'sitta', 2, 16000, 'lunas', 'proses', '2026-05-20 03:39:09', 0, '');

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
(1, 'Owner', 'user_owner', 'Owner'),
(2, 'Admin', 'user_admin', 'Admin'),
(3, 'Kasir', 'user_kasir', 'Kasir'),
(4, 'Dapur', 'user_dapur', 'Dapur');

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
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_meja`
--
ALTER TABLE `tb_meja`
  MODIFY `id_meja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_menu`
--
ALTER TABLE `tb_menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
