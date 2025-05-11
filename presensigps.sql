-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Bulan Mei 2025 pada 15.07
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
-- Database: `presensigps`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `departemen`
--

CREATE TABLE `departemen` (
  `kode_dept` char(3) NOT NULL,
  `nama_dept` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `departemen`
--

INSERT INTO `departemen` (`kode_dept`, `nama_dept`) VALUES
('ACT', 'Accounting'),
('DG', 'Design Grafis'),
('HRD', 'Human Resource Development'),
('IT', 'Information Technology'),
('MKT', 'Marketing');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `nik` char(5) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `no_hp` varchar(13) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `kode_dept` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`nik`, `nama_lengkap`, `jabatan`, `no_hp`, `password`, `remember_token`, `foto`, `kode_dept`) VALUES
('12345', 'MUCHAMMAD KHUDORI', 'DESIGN GRAFIS', '085730073022', '$2y$12$TfXkoSayDwVlOu0zQAPXiuLTD3zrfS297p8kpT1Otb.KBTTE4.uHC', NULL, '12345.jpeg', 'DG'),
('19191', 'MOH KHUSNUL MUBAROK', 'IT PROGRAMMER', '08988765', '$2y$12$BLuGpI0BkPdX6xBKXH8loOuAylpXSD17In5UNgG.GCFvfoqQKDV1W', NULL, '19191.jpg', 'IT'),
('24080', 'SUZAN MEGA VIRGINIA T', 'DESIGN GRAFIS', '08819191929', '$2y$12$TfXkoSayDwVlOu0zQAPXiuLTD3zrfS297p8kpT1Otb.KBTTE4.uHC', NULL, '24080.jpeg', 'DG'),
('29070', 'DENNIS PUTRA HILMANSYAH', 'AI ENGINEER', '0881036466459', '$2y$12$862eJyABojiQBmOYKfevhOkf5xde1k45aNFwLZG85RmBkFb4C..K2', NULL, '29070.jpeg', 'IT'),
('31010', 'ALIF YUSTIAN FIRMANSYAH', 'HRD MANAGER', '082122977762', '$2y$12$TfXkoSayDwVlOu0zQAPXiuLTD3zrfS297p8kpT1Otb.KBTTE4.uHC', NULL, '31010.jpg', 'HRD'),
('54321', 'AHMAD RIO AFANDI', 'MARKETING MANAGER', '0895325281040', '$2y$12$TfXkoSayDwVlOu0zQAPXiuLTD3zrfS297p8kpT1Otb.KBTTE4.uHC', NULL, '54321.jpeg', 'MKT'),
('56789', 'AFAUDDIN JUHDA AZMI', 'IT PROGRAMMER', '082230962607', '$2y$12$TfXkoSayDwVlOu0zQAPXiuLTD3zrfS297p8kpT1Otb.KBTTE4.uHC', NULL, NULL, 'IT'),
('97979', 'NAUFAL FARIS SAHASIKA', 'IT PROGRAMMER', '085645866212', '$2y$12$xreJlWUWCaQU8IqsImdZRuXIfWz4aGdQvZg1YGlPi9anD2jzhraGu', NULL, '97979.jpg', 'IT');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konfigurasi_lokasi`
--

CREATE TABLE `konfigurasi_lokasi` (
  `id` int(11) NOT NULL,
  `lokasi_kantor` varchar(255) NOT NULL,
  `radius` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `konfigurasi_lokasi`
--

INSERT INTO `konfigurasi_lokasi` (`id`, `lokasi_kantor`, `radius`) VALUES
(1, '-7.36185431381209,112.7597290882825', 100);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_izin`
--

CREATE TABLE `pengajuan_izin` (
  `id` int(11) NOT NULL,
  `nik` char(5) DEFAULT NULL,
  `tgl_izin` date DEFAULT NULL,
  `status` char(1) DEFAULT NULL COMMENT 'i = izin, s = sakit',
  `keterangan` varchar(255) DEFAULT NULL,
  `status_approved` char(1) DEFAULT '0' COMMENT '0 = Pending, 1 = Disetujui, 2 = Ditolak'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_izin`
--

INSERT INTO `pengajuan_izin` (`id`, `nik`, `tgl_izin`, `status`, `keterangan`, `status_approved`) VALUES
(1, '29070', '2025-02-07', 'i', 'Menjenguk orang sakit', '2'),
(2, '29070', '2025-02-08', 's', 'meriang', '1'),
(3, '29070', '2025-02-10', 'i', 'Lamaran', '0'),
(4, '19191', '2025-05-09', 'i', 'Khitanan anak', '1'),
(5, '19191', '2025-05-10', 's', 'Demam', '0'),
(6, '19191', '2025-05-10', 'i', 'Anak lahir', '0'),
(7, '19191', '2025-05-11', 's', 'Demam', '0');

-- --------------------------------------------------------

--
-- Struktur dari tabel `presensi`
--

CREATE TABLE `presensi` (
  `id` int(11) NOT NULL,
  `nik` char(5) NOT NULL,
  `tgl_presensi` date NOT NULL,
  `jam_in` time NOT NULL,
  `jam_out` time DEFAULT NULL,
  `foto_in` varchar(255) NOT NULL,
  `foto_out` varchar(255) DEFAULT NULL,
  `lokasi_in` text NOT NULL,
  `lokasi_out` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `presensi`
--

INSERT INTO `presensi` (`id`, `nik`, `tgl_presensi`, `jam_in`, `jam_out`, `foto_in`, `foto_out`, `lokasi_in`, `lokasi_out`) VALUES
(25, '29070', '2025-02-05', '06:47:15', '15:55:57', '29070-2025-02-05-in.png', '29070-2025-02-05-out.png', '-7.3616506,112.7597672', '-7.3616429,112.7597716'),
(26, '12345', '2025-02-05', '14:24:31', '15:55:15', '12345-2025-02-05-in.png', '12345-2025-02-05-out.png', '-7.3616498,112.7597678', '-7.3616379,112.7597703'),
(27, '29070', '2025-02-06', '11:27:07', '11:27:23', '29070-2025-02-06-in.png', '29070-2025-02-06-out.png', '-7.3616479,112.7597702', '-7.3616479,112.7597702'),
(28, '29070', '2025-02-07', '11:12:15', '11:12:45', '29070-2025-02-07-in.png', '29070-2025-02-07-out.png', '-7.3616406,112.7597717', '-7.3616406,112.7597717'),
(29, '29070', '2025-02-08', '09:23:23', '09:32:56', '29070-2025-02-08-in.png', '29070-2025-02-08-out.png', '-7.3616443,112.7597721', '-7.3616438,112.7597726'),
(30, '54321', '2025-02-08', '10:33:41', '10:33:57', '54321-2025-02-08-in.png', '54321-2025-02-08-out.png', '-7.3616435,112.75977', '-7.3616435,112.75977'),
(31, '31010', '2025-02-08', '10:58:58', '10:59:10', '31010-2025-02-08-in.png', '31010-2025-02-08-out.png', '-7.3616411,112.7597728', '-7.3616411,112.7597728'),
(32, '29070', '2025-02-13', '16:30:51', '16:58:56', '29070-2025-02-13-in.png', '29070-2025-02-13-out.png', '-7.3616428,112.7597767', '-7.3616385,112.7597717'),
(33, '29070', '2025-02-14', '09:28:34', '09:35:00', '29070-2025-02-14-in.png', '29070-2025-02-14-out.png', '-7.3616483,112.759772', '-7.36165,112.7597591'),
(34, '29070', '2025-02-15', '06:31:12', '10:32:29', '29070-2025-02-15-in.png', '29070-2025-02-15-out.png', '-7.3616469,112.7597741', '-7.3616478,112.7597726'),
(35, '31010', '2025-02-15', '10:34:49', '10:35:15', '31010-2025-02-15-in.png', '31010-2025-02-15-out.png', '-7.3616449,112.7597742', '-7.3616449,112.7597742'),
(36, '12345', '2025-02-15', '11:18:49', '11:19:04', '12345-2025-02-15-in.png', '12345-2025-02-15-out.png', '-7.3616428,112.7597715', '-7.3616428,112.7597715'),
(37, '29070', '2025-04-26', '06:41:13', '18:42:24', '29070-2025-04-26-in.png', '29070-2025-04-26-out.png', '-7.4682774,112.560257', '-7.4682774,112.560257'),
(38, '29070', '2025-04-28', '16:00:31', '16:00:58', '29070-2025-04-28-in.png', '29070-2025-04-28-out.png', '-7.3616154,112.7597782', '-7.3616154,112.7597782'),
(39, '29070', '2025-04-30', '16:08:01', '16:08:32', '29070-2025-04-30-in.png', '29070-2025-04-30-out.png', '-7.3616231,112.7597785', '-7.3616231,112.7597785'),
(40, '19191', '2025-04-30', '23:24:36', '23:31:02', '19191-2025-04-30-in.png', '19191-2025-04-30-out.png', '-7.405568,112.6825984', '-7.405568,112.6825984'),
(41, '12345', '2025-04-30', '23:39:24', '23:41:53', '12345-2025-04-30-in.png', '12345-2025-04-30-out.png', '-7.405568,112.6825984', '-7.405568,112.6825984'),
(42, '29070', '2025-05-01', '11:59:02', '16:45:33', '29070-2025-05-01-in.png', '29070-2025-05-01-out.png', '-7.405568,112.6825984', '-7.4682774,112.560257'),
(43, '19191', '2025-05-01', '06:19:00', '17:22:27', '19191-2025-05-01-in.png', '19191-2025-05-01-out.png', '-7.405568,112.6825984', '-7.405568,112.6825984'),
(44, '31010', '2025-05-01', '12:23:46', '12:25:39', '31010-2025-05-01-in.png', '31010-2025-05-01-out.png', '-7.405568,112.6825984', '-7.405568,112.6825984'),
(45, '54321', '2025-05-01', '12:36:53', '12:37:31', '54321-2025-05-01-in.png', '54321-2025-05-01-out.png', '-7.405568,112.6825984', '-7.405568,112.6825984'),
(46, '24080', '2025-05-01', '12:51:14', NULL, '24080-2025-05-01-in.png', NULL, '-7.2453,112.7572', NULL),
(47, '19191', '2025-05-02', '10:50:55', '10:51:22', '19191-2025-05-02-in.png', '19191-2025-05-02-out.png', '-7.3616176,112.7597806', '-7.3616249,112.7597762'),
(48, '29070', '2025-05-02', '10:52:26', '10:52:44', '29070-2025-05-02-in.png', '29070-2025-05-02-out.png', '-7.3616152,112.759781', '-7.3616152,112.759781'),
(49, '29070', '2025-05-08', '06:32:09', '15:40:32', '29070-2025-05-08-in.png', '29070-2025-05-08-out.png', '-7.3616211,112.7597799', '-7.361622,112.7597799'),
(50, '29070', '2025-05-09', '15:56:43', NULL, '29070-2025-05-09-in.png', NULL, '-7.3616199,112.7597788', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('emSaQVBAV78TaE89OqdrSe4u6GW5zKlCntZ8zTED', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiT0hJdDV2Z0duenVUUHJ1YTZxU2NSeGZVc2Nqenc0TTY0STlPb2VCciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb3Nlc2xvZ291dCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjUwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcHJlc2Vuc2kvbGFwb3Jhbj90aGVtZT1saWdodCI7fXM6NTE6ImxvZ2luX3VzZXJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NTU6ImxvZ2luX2thcnlhd2FuXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTkxOTE7fQ==', 1746962566);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Dennis Hilmansyah', 'denniez.hilmansyah29@gmail.com', NULL, '$2y$12$TfXkoSayDwVlOu0zQAPXiuLTD3zrfS297p8kpT1Otb.KBTTE4.uHC', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`kode_dept`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`nik`);

--
-- Indeks untuk tabel `konfigurasi_lokasi`
--
ALTER TABLE `konfigurasi_lokasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `konfigurasi_lokasi`
--
ALTER TABLE `konfigurasi_lokasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
