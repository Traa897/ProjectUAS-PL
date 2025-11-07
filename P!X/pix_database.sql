-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Okt 2025 pada 15.18
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pix_database`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `genres`
--

INSERT INTO `genres` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Action', 'action', '2025-10-27 04:59:12'),
(2, 'Horror', 'horror', '2025-10-27 04:59:12'),
(3, 'Drama', 'drama', '2025-10-27 04:59:12'),
(4, 'Comedy', 'comedy', '2025-10-27 04:59:12'),
(5, 'Sci-Fi', 'sci-fi', '2025-10-27 04:59:12'),
(6, 'Thriller', 'thriller', '2025-10-27 04:59:12'),
(7, 'Romance', 'romance', '2025-10-27 04:59:12'),
(8, 'Fantasy', 'fantasy', '2025-10-27 04:59:12'),
(9, 'Animation', 'animation', '2025-10-27 04:59:12'),
(10, 'Documentary', 'documentary', '2025-10-27 04:59:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `director` varchar(100) NOT NULL,
  `genre_id` int(11) NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'Durasi dalam menit',
  `release_date` date NOT NULL,
  `status` enum('akan_tayang','sedang_tayang','telah_tayang') NOT NULL,
  `rating` decimal(3,1) DEFAULT NULL COMMENT 'Rating 0.0 - 10.0',
  `synopsis` text DEFAULT NULL,
  `poster_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `movies`
--

INSERT INTO `movies` (`id`, `title`, `director`, `genre_id`, `duration`, `release_date`, `status`, `rating`, `synopsis`, `poster_url`, `created_at`, `updated_at`) VALUES
(1, 'IT: Welcome to Derry', 'Andy Muschietti', 2, 120, '2025-11-15', 'akan_tayang', 7.2, 'Prequel dari IT yang menceritakan awal mula teror Pennywise di kota Derry.', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/nyy3BITeIjviv6PFIXtqvc8i6xi.jpg', '2025-10-27 14:00:19', '2025-10-27 14:02:15'),
(2, 'A House of Dynamite', 'Michael Bay', 1, 135, '2024-10-28', 'sedang_tayang', 6.6, 'Film action penuh ledakan dan aksi spektakuler dari sutradara Michael Bay.', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/AiJ8L90ftPAwVf3SDx7Fj9IMZoy.jpg', '2025-10-27 14:00:19', '2025-10-27 14:01:18'),
(3, 'Good Boy', 'Viljar Bøe', 6, 110, '2024-10-25', 'sedang_tayang', 7.0, 'Thriller psikologis tentang seorang pria yang terjebak dalam situasi berbahaya.', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/pvMHRi09ur2L1drXh2dXFtuMFgl.jpg', '2025-10-27 14:00:19', '2025-10-27 14:01:50'),
(4, 'Mayor of Kingstown', 'Taylor Sheridan', 3, 125, '2024-10-20', 'sedang_tayang', 7.8, 'Drama kriminal tentang keluarga yang menjadi mediator antara polisi dan penjahat.', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/bCoVQckqnqVtiAZua0EO17eI2Y1.jpg', '2025-10-27 14:00:19', '2025-10-27 14:06:49'),
(5, 'SpiderMan Across the spider-verse', 'Francis Lawrence', 8, 140, '2025-12-20', 'telah_tayang', 6.9, '', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/8Vt6mWEReuy4Of61Lnj5Xj704m8.jpg', '2025-10-27 14:00:19', '2025-10-27 14:05:31'),
(6, 'TRON: Ares', 'Joachim Rønning', 5, 130, '2025-10-10', 'akan_tayang', 6.4, 'Sekuel TRON yang membawa petualangan baru di dunia digital.', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/chpWmskl3aKm1aTZqUHRCtviwPy.jpg', '2025-10-27 14:00:19', '2025-10-27 14:03:14'),
(7, 'Weapons', 'Zach Cregger', 2, 115, '2024-11-01', 'sedang_tayang', 7.4, 'Film horor thriller tentang senjata misterius yang mengubah pemiliknya.', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/cpf7vsRZ0MYRQcnLWteD5jK9ymT.jpg', '2025-10-27 14:00:19', '2025-10-27 14:04:10'),
(8, 'Dead of Winter', 'Brian ', 4, 113, '2024-09-15', 'telah_tayang', 5.1, 'dingin tapi tidak kejam ihh takotnyee', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/5DcrN62sGAiRJxt8rXSRlSRLwIE.jpg', '2025-10-27 04:59:14', '2025-10-27 14:08:18'),
(9, 'Gladiator II', 'Ridley Scott', 1, 155, '2024-11-15', 'telah_tayang', 7.8, 'Sekuel epik dari Gladiator yang legendaris dengan pertarungan spektakuler.', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/2cxhvwyEwRlysAmRH4iodkvo0z5.jpg', '2025-10-27 14:00:19', '2025-10-27 14:03:45'),
(10, 'Dune: Part Three', 'Denis Villeneuve', 5, 165, '2026-12-18', 'akan_tayang', 8.5, 'Kelanjutan epik dari saga Dune yang memukau dengan visual luar biasa.', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/d1Xd1R45mz2iFAenTs7ofDp0OIv.jpg', '2025-10-27 14:00:19', '2025-10-27 14:02:44'),
(13, 'Abadi Nan Jaya', 'Kimo Stamboel', 2, 158, '2025-03-21', 'sedang_tayang', 5.7, '', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/qD8RChlG2mqvIwGFxq7xNR4sa8s.jpg', '2025-10-27 09:27:33', '2025-10-27 09:27:33'),
(14, 'The Fantastic 4: First Steps', 'Matt Shakman', 8, 155, '2025-10-10', 'telah_tayang', 9.1, '', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/cm8TNGBGG0aBfWj0LgrESHv8tir.jpg', '2025-10-27 09:53:02', '2025-10-27 09:53:02'),
(15, 'Avatar', 'Herwin Novianto', 8, 140, '2025-08-10', 'sedang_tayang', 8.9, '', 'https://media.themoviedb.org/t/p/w440_and_h660_face/t6HIqrRAclMCA60NsSmeqe9RmNV.jpg', '2025-10-27 09:55:59', '2025-10-27 09:55:59'),
(16, 'Rangga &amp;amp; Cinta', 'Riri riza', 3, 159, '2025-10-28', 'sedang_tayang', 9.4, '', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/iutKnkc5dPHVxwrCB5ri26r86PF.jpg', '2025-10-27 13:51:30', '2025-10-27 13:52:47'),
(17, 'The Conjuring Last rites', 'Michael Chaves', 6, 215, '2025-09-03', 'sedang_tayang', 7.0, '', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/7JzOmJ1fIU43I3gLHYsY8UzNzjG.jpg', '2025-10-27 13:55:16', '2025-10-27 13:55:16'),
(18, 'Black Phone 2', 'Scott Derrickson', 6, 154, '2025-10-15', 'sedang_tayang', 7.1, '', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/evbguUd1BwExQgXMjG9n9AHMQpN.jpg', '2025-10-27 13:57:21', '2025-10-27 13:57:21'),
(19, 'Chainsaw Man The Movie', 'Tatsuya Yoshihara', 8, 140, '2025-09-26', 'akan_tayang', 8.1, '', 'https://media.themoviedb.org/t/p/w600_and_h900_bestv2/xdzLBZjCVSEsic7m7nJc4jNJZVW.jpg', '2025-10-27 13:58:50', '2025-10-27 13:58:50');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indeks untuk tabel `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_genre` (`genre_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `fk_genre` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
