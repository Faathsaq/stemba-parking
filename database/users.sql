-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2026 at 06:48 AM
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
-- Database: `stembareport`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@stemba.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-10-12 05:57:30'),
(2, 'Iqbalpratm', 'iibrrot.15@gmail.com', '$2y$10$hNQmr.XModKQzThaA0vifO8ZzJfQGJeg05suK0N2.ZYR8lVo3R9Ri', 'user', '2025-10-12 07:34:23'),
(4, 'admin2', 'admin@smkn7semarang.com', '$2y$10$0Q6.I39oCO3nHl9Tn4BaN.QNaBUvHQ34xOGmJ9KEEeb9Guv54igcq', 'admin', '2025-10-12 08:27:43'),
(5, 'iqbal2', 'iqbalpratm07@gmail.com', '$2y$10$BrNLtTAmCSrCqjsVQNj4h.lnRH5gPDPSAnAK79Jv.DNGYTjOYV6d6', 'user', '2025-10-12 10:22:32'),
(6, 'user1', 'volhouden168@gmail.com', '$2y$10$VtEG1XOAmXgVTcxcOhzdPuhsSCUFaYEDKvyw1LFeD1vauXbTGw4RW', 'user', '2025-10-18 14:05:25'),
(7, 'User', 'user3@gmail.com', '$2y$10$1evQEwKq/S9k/WSvC24DbuluwXmU75d1DV8aPFRE4gaQqfbg4jVTO', 'user', '2025-10-19 03:41:45'),
(8, 'Faiz', 'faiz@gmail.com', '$2y$10$eQiAPiteJcsI6mD.8Z2Vj.XQgWU0AHu/bpt9V6HZhXaCt/Y//ilay', 'user', '2025-10-19 03:42:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
