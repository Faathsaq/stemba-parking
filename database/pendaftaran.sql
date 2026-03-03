-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2026 at 03:28 AM
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
-- Database: `stembaaspirasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `umur` tinyint(4) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `nama_kendaraan` varchar(100) NOT NULL,
  `nomor_tnkb` varchar(15) NOT NULL,
  `jenis` enum('motor','mobil','sepeda') NOT NULL DEFAULT 'motor',
  `foto_kendaraan` varchar(255) DEFAULT NULL,
  `foto_sim` varchar(255) DEFAULT NULL,
  `foto_kartu` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','disetujui','ditolak') NOT NULL DEFAULT 'menunggu',
  `catatan_admin` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendaftaran`
--

INSERT INTO `pendaftaran` (`id`, `user_id`, `nama_lengkap`, `nis`, `umur`, `kelas`, `email`, `no_telepon`, `nama_kendaraan`, `nomor_tnkb`, `jenis`, `foto_kendaraan`, `foto_sim`, `foto_kartu`, `status`, `catatan_admin`, `created_at`, `updated_at`) VALUES
(1, 11, 'Faiz Athaillah Tsaqif', '234119239', 18, '10 AK 1', 'faizf8497@gmail.com', '082131677934', 'AMG-GT 63S', 'H1489B', 'mobil', 'uploads/kendaraan/11_foto_kendaraan_1771886192.jpeg', 'uploads/kendaraan/11_foto_sim_1771886192.jpeg', 'uploads/kendaraan/11_foto_kartu_1771886192.jpeg', 'menunggu', NULL, '2026-02-23 22:36:32', '2026-02-23 22:36:32'),
(2, 11, 'Faiz Athaillah Tsaqif', '234119239', 17, '12 SIJA 2', 'faizf8497@gmail.com', '082131677934', 'AMG-GT 63S', 'H2928B', 'mobil', 'uploads/kendaraan/11_foto_kendaraan_1771888291.jpg', 'uploads/kendaraan/11_foto_sim_1771888291.jpg', 'uploads/kendaraan/11_foto_kartu_1771888291.png', 'menunggu', NULL, '2026-02-23 23:11:31', '2026-02-23 23:11:31'),
(3, 11, 'Faiz Athaillah Tsaqif', '122222221', 17, '12 SIJA 2', 'faizf8497@gmail.com', '082131677934', 'Icikiwir', 'H 1298 B', 'sepeda', 'uploads/kendaraan/11_foto_kendaraan_1771888468.jpg', 'uploads/kendaraan/11_foto_sim_1771888468.jpg', 'uploads/kendaraan/11_foto_kartu_1771888468.png', 'menunggu', NULL, '2026-02-23 23:14:28', '2026-02-23 23:14:28'),
(4, 11, 'ignacio lauda vito wibisono', '008265746', 19, '12 SIJA 2', 'ignaciogantengvito@gmail.com', '08127168776', 'Mio Trondol', 'H 5758 TY', 'motor', 'uploads/kendaraan/11_foto_kendaraan_1771893446.png', 'uploads/kendaraan/11_foto_sim_1771893446.png', 'uploads/kendaraan/11_foto_kartu_1771893446.png', 'menunggu', NULL, '2026-02-24 00:37:26', '2026-02-24 00:37:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_tnkb` (`nomor_tnkb`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `pendaftaran_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
