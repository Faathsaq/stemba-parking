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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `kelas` varchar(20) DEFAULT NULL,
  `jurusan` varchar(50) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `kelas`, `jurusan`, `foto`) VALUES
(1, 'admin', 'admin@stemba.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-10-12 05:57:30', NULL, NULL, NULL),
(2, 'Iqbalpratm', 'iibrrot.15@gmail.com', '$2y$10$hNQmr.XModKQzThaA0vifO8ZzJfQGJeg05suK0N2.ZYR8lVo3R9Ri', 'user', '2025-10-12 07:34:23', NULL, NULL, NULL),
(4, 'admin2', 'admin@smkn7semarang.com', '$2y$10$0Q6.I39oCO3nHl9Tn4BaN.QNaBUvHQ34xOGmJ9KEEeb9Guv54igcq', 'admin', '2025-10-12 08:27:43', NULL, NULL, NULL),
(5, 'iqbal2', 'iqbalpratm07@gmail.com', '$2y$10$BrNLtTAmCSrCqjsVQNj4h.lnRH5gPDPSAnAK79Jv.DNGYTjOYV6d6', 'user', '2025-10-12 10:22:32', NULL, NULL, NULL),
(6, 'user1', 'volhouden168@gmail.com', '$2y$10$VtEG1XOAmXgVTcxcOhzdPuhsSCUFaYEDKvyw1LFeD1vauXbTGw4RW', 'user', '2025-10-18 14:05:25', NULL, NULL, NULL),
(7, 'User', 'user3@gmail.com', '$2y$10$1evQEwKq/S9k/WSvC24DbuluwXmU75d1DV8aPFRE4gaQqfbg4jVTO', 'user', '2025-10-19 03:41:45', NULL, NULL, NULL),
(8, 'Faiz', 'faiz@gmail.com', '$2y$10$eQiAPiteJcsI6mD.8Z2Vj.XQgWU0AHu/bpt9V6HZhXaCt/Y//ilay', 'user', '2025-10-19 03:42:31', NULL, NULL, NULL),
(9, 'atha', 'faizf8497@gmail.com', '$2y$10$2cX2EEXWinSUg0RPPgD4U.y2U6P5IbXZTEsUheYrD8oKOihC3oqie', 'user', '2026-01-14 13:00:56', 'XI', 'TEK', '696ee97d5ea2f.png'),
(10, 'chooo.so', 'faiz.athaillah170@smk.belajar.id', '$2y$10$.8immVi1j3OuPw.EY33sj.hnw.92H04lRzoZU192cwnM9Q26.goci', 'admin', '2026-01-20 01:51:31', NULL, NULL, NULL),
(11, 'athahahay', 'akymbo367@gmail.com', '$2y$10$aWodiZ2bb/8bHiQVKKrxu.z/1hmBimp0sy7C6k7nW.RZ8JN5kzmXO', 'user', '2026-02-23 22:16:23', 'XI', 'SIJA', '699ce4736e56e.png'),
(12, 'vito', 'vitopvga@gmail.com', '$2y$10$UH0N13mn/wAZbgppmo/OZOFnjnehhQzHlBa0pRtV4HV6emQAZxJoq', 'user', '2026-02-24 02:19:28', NULL, NULL, NULL),
(13, 'pik', 'pik@gmail.com', '$2y$10$yyG3ONjX9QpOQWjrE0pfPeCqC26448TeA239YRRbuxgRKjbhdiZ9i', 'user', '2026-02-24 02:21:42', 'X', 'TITL', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
