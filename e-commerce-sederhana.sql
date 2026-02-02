-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Feb 2026 pada 18.25
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-commerce-sederhana`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'adminganteng', 'adminganteng');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `produk_dipesan` text DEFAULT NULL,
  `jumlah` int(11) DEFAULT 1,
  `alamat` text DEFAULT NULL,
  `kurir_layanan` varchar(100) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending',
  `no_hp` varchar(20) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `total_bayar` int(20) DEFAULT NULL,
  `tanggal_pesan` timestamp NOT NULL DEFAULT current_timestamp(),
  `no_telp` varchar(20) DEFAULT NULL,
  `kurir` varchar(50) DEFAULT NULL,
  `metode_bayar` varchar(50) DEFAULT NULL,
  `tgl_order` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `nama_pelanggan`, `produk_dipesan`, `jumlah`, `alamat`, `kurir_layanan`, `tanggal`, `status`, `no_hp`, `metode_pembayaran`, `total_bayar`, `tanggal_pesan`, `no_telp`, `kurir`, `metode_bayar`, `tgl_order`) VALUES
(13, 0, 'alfin', NULL, 1, 'KP BSML RT12 RW 13 NO 14 ', NULL, '2026-02-02 15:10:04', 'SELESAI', NULL, NULL, 1400000, '2026-02-02 15:10:04', '087766554433', 'J&T', 'E-Wallet', '2026-02-02 16:10:04'),
(14, 0, 'alfin', NULL, 1, 'kp basmol no 22 rt 14 rw 15 ', NULL, '2026-02-02 15:10:50', 'SELESAI', NULL, NULL, 1200000, '2026-02-02 15:10:50', '087765656677', 'J&T', 'Transfer Bank', '2026-02-02 16:10:50'),
(15, 0, 'admin', NULL, 1, 'Kp semanan', NULL, '2026-02-02 15:22:00', 'SELESAI', NULL, NULL, 200000, '2026-02-02 15:22:00', '0089988776655', 'JNE', 'E-Wallet', '2026-02-02 16:22:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `qty`) VALUES
(1, 13, 3, 2),
(2, 13, 4, 1),
(3, 14, 3, 1),
(4, 14, 4, 1),
(5, 15, 3, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `foto` varchar(255) DEFAULT 'default.jpg',
  `kategori` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `nama_produk`, `harga`, `deskripsi`, `stok`, `foto`, `kategori`) VALUES
(3, 'Batik Sumatera', 200000, 'Batik Sumatera merupakan batik khas dari berbagai daerah di Pulau Sumatera yang memiliki ciri motif berani, kaya warna, dan sarat makna budaya. Motif batik Sumatera banyak terinspirasi dari alam, flora dan fauna, serta simbol adat dan kehidupan masyarakat setempat.\r\n\r\nWarna yang digunakan cenderung kuat seperti merah, emas, hitam, dan cokelat, melambangkan keberanian, kemakmuran, dan kehormatan. Batik Sumatera cocok digunakan untuk berbagai kesempatan, baik acara adat, formal, maupun kegiatan sehari-hari.\r\n\r\nDengan perpaduan nilai tradisional dan sentuhan modern, Batik Sumatera tidak hanya menjadi busana, tetapi juga identitas budaya yang mencerminkan kekayaan dan keberagaman nusantara.', 0, 'batik sumatera.jpg', NULL),
(4, 'Batik lombok', 1000000, 'Batik Lombok merupakan batik khas Nusa Tenggara Barat yang menampilkan motif sederhana namun bermakna, terinspirasi dari alam, budaya, dan kehidupan masyarakat Sasak. Ciri khasnya terletak pada pola geometris dan ornamen tradisional dengan warna-warna lembut hingga natural, menciptakan kesan elegan dan etnik yang khas, sehingga cocok digunakan untuk berbagai kesempatan baik formal maupun santai.', 0, '1769797190_batik lombok.png', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `profil_toko` text DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `produk_unggulan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `logo_url`, `profil_toko`, `visi`, `misi`, `produk_unggulan_id`) VALUES
(1, '1769864245_Gemini_Generated_Image_4wchau4wchau4wch.png', 'UMKM Batik ini berdiri sebagai bentuk kecintaan terhadap warisan budaya Indonesia, khususnya seni batik. Berawal dari usaha kecil rumahan, kami memproduksi dan menjual batik dengan motif tradisional yang dipadukan dengan sentuhan modern agar tetap relevan dengan perkembangan zaman.\r\n\r\nKami bekerja sama dengan pengrajin lokal yang berpengalaman untuk menghasilkan batik yang berkualitas, nyaman digunakan, dan memiliki nilai seni tinggi. Setiap lembar batik yang kami hasilkan membawa cerita, makna, dan identitas budaya nusantara.\r\n\r\nDengan adanya UMKM ini, kami berharap dapat menjadi bagian dari upaya melestarikan batik sekaligus membuka peluang ekonomi bagi masyarakat sekitar.', 'Menjadi UMKM batik yang berkualitas, terpercaya, dan berperan aktif dalam melestarikan budaya batik Indonesia.', 'Menghasilkan produk batik berkualitas dengan desain menarik dan bahan yang nyaman.\r\n\r\nMendukung dan memberdayakan pengrajin batik lokal.\r\n\r\nMemperkenalkan batik kepada generasi muda melalui produk yang modern dan terjangkau.\r\n\r\nMemberikan pelayanan yang ramah dan profesional kepada pelanggan.\r\n\r\nMengembangkan usaha secara berkelanjutan dengan tetap menjaga nilai budaya.', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `role`) VALUES
(1, 'aflin', '$2y$10$/BN5xTP4WNlgDTedpx1A3ebXJ4KJwBQDVMXDUY8JFvCFNWUGrx4EG', '2026-01-20 13:06:45', 'user'),
(2, 'aldi', '$2y$10$OIsc/TXXNq8mQ7dJssAdyef4kawBfIILREyFebVDmK4G/CMyyik3G', '2026-01-20 13:07:18', 'user'),
(3, 'admin', 'admin123', '2026-01-30 13:32:53', 'admin'),
(4, 'leni', 'marlina', '2026-01-30 13:48:50', 'user'),
(5, 'rin', 'aldi', '2026-01-30 15:27:25', 'user'),
(6, 'alfin', 'brillian', '2026-02-02 14:20:56', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
