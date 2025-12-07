-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Des 2025 pada 12.27
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
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `role` enum('admin') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`, `role`, `created_at`) VALUES
(1, 'admin', 'admin123', 'Administrator P!X', 'admin', '2025-12-02 07:51:08'),
(2, 'admin1', 'admin123', 'Operator Bioskop', 'admin', '2025-12-02 07:51:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bioskop`
--

CREATE TABLE `bioskop` (
  `id_bioskop` int(11) NOT NULL,
  `nama_bioskop` varchar(100) NOT NULL,
  `kota` varchar(50) NOT NULL,
  `alamat_bioskop` text NOT NULL,
  `jumlah_studio` int(11) NOT NULL DEFAULT 4,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `logo_url` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bioskop`
--

INSERT INTO `bioskop` (`id_bioskop`, `nama_bioskop`, `kota`, `alamat_bioskop`, `jumlah_studio`, `created_at`, `updated_at`, `logo_url`) VALUES
(1, 'CGV Balikpapan Plaza', 'Balikpapan', 'Balikpapan Plaza Lt.3, Jl. Jend. Sudirman No.1', 6, '2025-12-02 07:51:04', '2025-12-07 07:00:46', 'assets/FOTO BIOSKOP/CGV bpp.png'),
(2, 'XXI E-Walk Balikpapan', 'Balikpapan', 'E-Walk Supermall Lt.2, Jl. MT. Haryono', 5, '2025-12-02 07:51:04', '2025-12-07 07:15:46', 'assets/FOTO BIOSKOP/Bsb.png'),
(3, 'Cinepolis Balikpapan Pentacity', 'Balikpapan', 'Balikpapan Pentacity Lt.3, Jl. Soekarno Hatta', 4, '2025-12-02 07:51:04', '2025-12-07 07:10:37', 'assets/FOTO BIOSKOP/Cinepolis bpp.png'),
(4, 'Go Mall CGV', 'Samarinda', 'GO Mall Samarinda Lt.3, Jl. Panglima Batur', 5, '2025-12-02 07:51:04', '2025-12-07 07:12:19', 'assets/FOTO BIOSKOP/Go mall.png'),
(5, 'XXI Central Plaza', 'Samarinda', 'Lembuswana Mall Lt.2, Jl. Basuki Rahmat', 4, '2025-12-02 07:51:04', '2025-12-07 07:14:47', 'assets/FOTO BIOSKOP/Central.png'),
(6, 'XXI Bigmall', 'Samarinda', 'Big Mall Samarinda Lt.2, Jl. Pramuka', 5, '2025-12-02 07:51:04', '2025-12-07 07:13:51', 'assets/FOTO BIOSKOP/Big mall.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_jadwal_tayang` int(11) NOT NULL,
  `nomor_kursi` varchar(10) NOT NULL,
  `harga_tiket` decimal(10,2) NOT NULL,
  `jenis_tiket` enum('reguler','vip') DEFAULT 'reguler',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_jadwal_tayang`, `nomor_kursi`, `harga_tiket`, `jenis_tiket`, `created_at`) VALUES
(1, 1, 7, 'B1', 500000.00, 'reguler', '2025-12-07 11:02:22'),
(2, 2, 8, 'H9', 40000.00, 'reguler', '2025-12-07 11:17:09'),
(3, 3, 8, 'E6', 40000.00, 'reguler', '2025-12-07 11:18:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `film`
--

CREATE TABLE `film` (
  `id_film` int(11) NOT NULL,
  `judul_film` varchar(200) NOT NULL,
  `tahun_rilis` year(4) NOT NULL,
  `durasi_menit` int(11) NOT NULL,
  `sipnosis` text DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `poster_url` varchar(500) DEFAULT 'https://via.placeholder.com/300x450',
  `id_genre` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `film`
--

INSERT INTO `film` (`id_film`, `judul_film`, `tahun_rilis`, `durasi_menit`, `sipnosis`, `rating`, `poster_url`, `id_genre`, `created_at`, `updated_at`) VALUES
(1, 'Pengabdi Setan 2: Communion', '2022', 119, 'Rini dan keluarganya berusaha bertahan dari teror ibunya yang telah meninggal. Mereka pindah ke apartemen kumuh di Jakarta, namun teror justru semakin mengintensif.', 7.5, 'https://media.themoviedb.org/t/p/w188_and_h282_face/zzpxuEXS1azwhqfvtacLtScixbS.jpg', 4, '2025-12-02 07:51:03', '2025-12-02 09:06:27'),
(2, 'Dilan 1990', '2018', 110, 'Kisah cinta remaja antara Dilan dan Milea di tahun 1990 di Bandung. Dilan adalah seorang siswa SMA yang cerdas dan unik dalam mendekati wanita.', 8.0, 'https://media.themoviedb.org/t/p/w600_and_h900_face/9jvJd0zbN166hA3caTiTTQ61h6G.jpg', 6, '2025-12-02 07:51:03', '2025-12-07 07:22:34'),
(4, 'Keluarga Cemara 2', '2022', 110, 'Keluarga yang dulunya kaya raya harus bangkit dari keterpurukan ekonomi. Mereka belajar arti kebahagiaan yang sesungguhnya.', 7.8, 'https://media.themoviedb.org/t/p/w600_and_h900_face/kj2zoNB6UhNjp3J6wEEpLsEYPcI.jpg', 2, '2025-12-02 07:51:03', '2025-12-07 07:23:51'),
(5, 'Gundala', '2019', 123, 'Saka, seorang pemuda biasa yang mendapat kekuatan petir setelah tersambar. Ia menjadi superhero Indonesia pertama yang melawan kejahatan.', 7.0, 'https://media.themoviedb.org/t/p/w600_and_h900_face/ohONagShzXKYZ7H9wrwnwQcaF3k.jpg', 5, '2025-12-02 07:51:03', '2025-12-07 07:24:17'),
(6, 'Zootopia 2', '2025', 148, 'Kartun lucu', 8.2, 'https://media.themoviedb.org/t/p/w440_and_h660_face/nT1tuba9NBCzraZRwTvFpoSJZk.jpg', 8, '2025-12-02 07:51:03', '2025-12-07 10:37:54'),
(7, 'SORE : Istri Masa Depan mu', '2025', 159, 'Kita tidak bisa merubah diri orang lain demi kebaikannya justru itu akan memperburuk dirinya, yang bisa merubah dirinya dia sendiri', 9.0, 'https://media.themoviedb.org/t/p/w600_and_h900_face/u4pNXPmBuYeTtksakUCZgJ1zpSB.jpg', 6, '2025-12-02 07:51:03', '2025-12-07 07:28:40'),
(9, 'Agak Laen : Menyalah pantiku', '2025', 154, 'KOCAK BJIRR', 9.0, 'https://media.themoviedb.org/t/p/w440_and_h660_face/nMJqvtrgX6dqZNcNfAJvS5o7wd1.jpg', 3, '2025-12-07 10:43:52', '2025-12-07 10:43:52'),
(10, 'Fantastic 4 ', '2025', 144, 'FIlm bagus intinya ', 8.0, 'https://media.themoviedb.org/t/p/w440_and_h660_face/pZPJsaFKWheTOerVhLnpP8TPp4B.jpg', 1, '2025-12-07 10:46:00', '2025-12-07 10:46:00'),
(11, 'Superman ', '2025', 146, 'suupermannya gacor nontnn aja ', 9.0, 'https://media.themoviedb.org/t/p/w440_and_h660_face/vqwxWFADURzHOD7gpErhrY994T.jpg', 1, '2025-12-07 10:46:56', '2025-12-07 10:46:56'),
(12, 'Jurassic World', '2025', 156, 'Mengerikan ', 9.0, 'https://media.themoviedb.org/t/p/w440_and_h660_face/1RICxzeoNCAO5NpcRMIgg1XT6fm.jpg', 4, '2025-12-07 10:49:16', '2025-12-07 10:49:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `genre`
--

CREATE TABLE `genre` (
  `id_genre` int(11) NOT NULL,
  `nama_genre` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `genre`
--

INSERT INTO `genre` (`id_genre`, `nama_genre`, `deskripsi`, `created_at`) VALUES
(1, 'Action', 'Film dengan adegan aksi dan pertarungan', '2025-12-02 07:51:01'),
(2, 'Drama', 'Film dengan cerita emosional dan konflik', '2025-12-02 07:51:01'),
(3, 'Comedy', 'Film yang menghibur dan lucu', '2025-12-02 07:51:01'),
(4, 'Horror', 'Film menakutkan dan menegangkan', '2025-12-02 07:51:01'),
(5, 'Sci-Fi', 'Film fiksi ilmiah dan teknologi masa depan', '2025-12-02 07:51:01'),
(6, 'Romance', 'Film dengan tema percintaan', '2025-12-02 07:51:01'),
(7, 'Thriller', 'Film menegangkan dan penuh misteri', '2025-12-02 07:51:01'),
(8, 'Animation', 'Film animasi untuk semua usia', '2025-12-02 07:51:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_tayang`
--

CREATE TABLE `jadwal_tayang` (
  `id_tayang` int(11) NOT NULL,
  `id_film` int(11) NOT NULL,
  `id_bioskop` int(11) NOT NULL,
  `nama_tayang` varchar(100) DEFAULT NULL,
  `tanggal_tayang` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `harga_tiket` decimal(10,2) NOT NULL,
  `kapasitas_total` int(11) DEFAULT 100,
  `kapasitas_tersedia` int(11) DEFAULT 100,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jadwal_tayang`
--

INSERT INTO `jadwal_tayang` (`id_tayang`, `id_film`, `id_bioskop`, `nama_tayang`, `tanggal_tayang`, `jam_mulai`, `jam_selesai`, `harga_tiket`, `kapasitas_total`, `kapasitas_tersedia`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Premiere Night', '2025-12-05', '19:00:00', '21:00:00', 50000.00, 100, 100, '2025-12-02 07:51:05', '2025-12-02 07:51:05'),
(2, 1, 2, 'Weekend Special', '2025-12-06', '20:30:00', '22:30:00', 45000.00, 100, 100, '2025-12-02 07:51:05', '2025-12-02 07:51:05'),
(3, 2, 1, 'Regular Show', '2025-12-05', '14:00:00', '16:00:00', 40000.00, 100, 100, '2025-12-02 07:51:05', '2025-12-02 07:51:05'),
(5, 4, 4, 'Family Time', '2025-12-06', '15:00:00', '17:00:00', 35000.00, 100, 100, '2025-12-02 07:51:05', '2025-12-02 07:51:05'),
(6, 5, 5, 'Superhero Show', '2025-12-08', '18:00:00', '20:30:00', 45000.00, 100, 100, '2025-12-02 07:51:05', '2025-12-02 07:51:05'),
(7, 7, 6, 'Family TIME', '2025-12-09', '10:00:00', '22:00:00', 500000.00, 100, 100, '2025-12-07 10:55:59', '2025-12-07 10:55:59'),
(8, 6, 2, 'Weekend ', '2025-12-07', '13:00:00', '20:00:00', 40000.00, 100, 100, '2025-12-07 10:57:48', '2025-12-07 10:57:48'),
(9, 9, 5, 'Premier Night', '2025-12-09', '12:00:00', '20:00:00', 40000.00, 100, 100, '2025-12-07 10:58:24', '2025-12-07 10:58:24'),
(10, 10, 4, 'Premier Night', '2025-12-12', '13:00:00', '20:00:00', 45000.00, 100, 100, '2025-12-07 10:58:55', '2025-12-07 10:58:55'),
(11, 11, 4, 'Superhero Show', '2025-12-07', '12:00:00', '19:00:00', 300000.00, 100, 100, '2025-12-07 10:59:49', '2025-12-07 10:59:49'),
(12, 12, 5, 'Weekend ', '2025-12-08', '12:00:00', '13:25:00', 40000.00, 100, 100, '2025-12-07 11:01:05', '2025-12-07 11:01:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `kode_booking` varchar(50) NOT NULL,
  `tanggal_transaksi` datetime DEFAULT current_timestamp(),
  `jumlah_tiket` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `metode_pembayaran` enum('transfer','e-wallet','kartu_kredit','tunai') DEFAULT 'tunai',
  `status_pembayaran` enum('pending','berhasil','gagal','expired') DEFAULT 'pending',
  `tanggal_pembayaran` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `id_admin`, `kode_booking`, `tanggal_transaksi`, `jumlah_tiket`, `total_harga`, `metode_pembayaran`, `status_pembayaran`, `tanggal_pembayaran`) VALUES
(1, 2, NULL, 'BKG20251207120222709', '2025-12-07 19:02:22', 1, 500000.00, 'transfer', 'berhasil', '2025-12-07 12:02:22'),
(2, 2, NULL, 'BKG20251207121709324', '2025-12-07 19:17:09', 1, 40000.00, 'transfer', 'berhasil', '2025-12-07 12:17:09'),
(3, 2, NULL, 'BKG20251207121856169', '2025-12-07 19:18:56', 1, 40000.00, 'transfer', 'berhasil', '2025-12-07 12:18:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `no_telpon` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_akun` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `email`, `password`, `nama_lengkap`, `no_telpon`, `tanggal_lahir`, `alamat`, `tanggal_daftar`, `status_akun`) VALUES
(1, 'Patraa', 'patran05534@gmail.com', '$2y$10$mrqG5lqVRv/f4wuy0Fh0Pu4M6XdtX9pHZTsuhpTXk7DBiJ8eGbvLy', 'patra ananda', '081351319657', '0000-00-00', 'jl.soekarno.hatta km 21 rt 41', '2025-12-02 07:51:32', 'aktif'),
(2, 'patra', 'pata253@gmail.com', '$2y$10$xpAxcj1J1sfgM5t8TufvHuB4af3Jo/Wy95l5OGlrbQzz/o..mBa32', 'patraa ananda', '08765454566', '2199-10-26', 'Jl. Soekarno Hatta No.KM 15, Karang Joang, Kec. Balikpapan Utara, Kota Balikpapan, Kalimantan Timur 76127', '2025-12-02 07:52:40', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `bioskop`
--
ALTER TABLE `bioskop`
  ADD PRIMARY KEY (`id_bioskop`);

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_jadwal_tayang` (`id_jadwal_tayang`);

--
-- Indeks untuk tabel `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`id_film`),
  ADD KEY `id_genre` (`id_genre`);

--
-- Indeks untuk tabel `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id_genre`);

--
-- Indeks untuk tabel `jadwal_tayang`
--
ALTER TABLE `jadwal_tayang`
  ADD PRIMARY KEY (`id_tayang`),
  ADD KEY `id_film` (`id_film`),
  ADD KEY `id_bioskop` (`id_bioskop`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD UNIQUE KEY `kode_booking` (`kode_booking`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `bioskop`
--
ALTER TABLE `bioskop`
  MODIFY `id_bioskop` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `film`
--
ALTER TABLE `film`
  MODIFY `id_film` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `genre`
--
ALTER TABLE `genre`
  MODIFY `id_genre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `jadwal_tayang`
--
ALTER TABLE `jadwal_tayang`
  MODIFY `id_tayang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_jadwal_tayang`) REFERENCES `jadwal_tayang` (`id_tayang`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `film`
--
ALTER TABLE `film`
  ADD CONSTRAINT `film_ibfk_1` FOREIGN KEY (`id_genre`) REFERENCES `genre` (`id_genre`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `jadwal_tayang`
--
ALTER TABLE `jadwal_tayang`
  ADD CONSTRAINT `jadwal_tayang_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_tayang_ibfk_2` FOREIGN KEY (`id_bioskop`) REFERENCES `bioskop` (`id_bioskop`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
