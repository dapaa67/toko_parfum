-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Okt 2025 pada 09.31
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
-- Database: `toko_parfum`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_us_content`
--

CREATE TABLE `about_us_content` (
  `id` int(11) NOT NULL,
  `main_title` varchar(255) NOT NULL,
  `lead_paragraph` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `about_us_content`
--

INSERT INTO `about_us_content` (`id`, `main_title`, `lead_paragraph`, `created_at`, `updated_at`) VALUES
(1, 'Mengapa Fragrance Shop?', 'Kami menawarkan pengalaman berbelanja parfum yang tak tertandingi dengan koleksi eksklusif dan layanan pelanggan terbaik.', '2025-10-23 15:28:13', '2025-10-23 15:28:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_us_list_items`
--

CREATE TABLE `about_us_list_items` (
  `id` int(11) NOT NULL,
  `about_us_id` int(11) NOT NULL,
  `icon_class` varchar(255) NOT NULL,
  `item_text` varchar(255) NOT NULL,
  `item_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `about_us_list_items`
--

INSERT INTO `about_us_list_items` (`id`, `about_us_id`, `icon_class`, `item_text`, `item_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'fas fa-gem', 'Pilihan Aroma Terlengkap', 1, '2025-10-23 15:28:13', '2025-10-23 15:28:13'),
(2, 1, 'fas fa-shipping-fast', 'Pengiriman Cepat & Aman', 2, '2025-10-23 15:28:13', '2025-10-23 15:28:13'),
(3, 1, 'fas fa-award', 'Produk Original 100%', 3, '2025-10-23 15:28:13', '2025-10-23 15:28:13'),
(4, 1, 'fas fa-headset', 'Layanan Pelanggan Responsif', 4, '2025-10-23 15:28:13', '2025-10-23 15:28:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `carousel_items`
--

CREATE TABLE `carousel_items` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `item_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `carousel_items`
--

INSERT INTO `carousel_items` (`id`, `image_path`, `title`, `description`, `link`, `item_order`, `created_at`, `updated_at`) VALUES
(1, 'img/carousel/68f9d38d7a9d6_Banner Retail Promo Produk Parfum Baru Modern Minimalis Cokelat Krem (1).png', '', '', '', 0, '2025-10-23 13:34:45', '2025-10-23 13:34:45'),
(2, 'img/carousel/68fc76010e2ee_Banner Retail Promo Produk Parfum Baru Modern Minimalis Cokelat Krem.png', '', '', '', 1, '2025-10-23 13:34:58', '2025-10-25 14:02:25'),
(3, 'img/carousel/68fc75e154065_Perfume Advertising Designs - Yazan Dyab.jpg', '', '', '', 2, '2025-10-23 13:35:07', '2025-10-25 14:01:53'),
(5, 'img/carousel/68fc770f299d5_Gemini_Generated_Image_2iorxi2iorxi2ior.png', '', '', '', 3, '2025-10-25 14:06:55', '2025-10-25 14:06:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `parfum`
--

CREATE TABLE `parfum` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `merek` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `parfum`
--

INSERT INTO `parfum` (`id`, `nama`, `merek`, `kategori`, `harga`, `stok`, `deskripsi`) VALUES
(1, 'Alaska', 'DParfume', 'Man', 5000000, 123123, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `parfums`
--

CREATE TABLE `parfums` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `merek` varchar(100) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female','Unisex') NOT NULL,
  `ukuran` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_best_seller` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `parfums`
--

INSERT INTO `parfums` (`id`, `nama`, `merek`, `kategori`, `gender`, `ukuran`, `harga`, `stok`, `deskripsi`, `image_path`, `is_best_seller`) VALUES
(1, 'Azure Coast', 'Koda Essence', 'Fresh', 'Male', 100, 100000000, 12, 'Aroma citrus yang tajam dengan sentuhan air laut yang menyegarkan, sempurna untuk pria yang aktif dan dinamis sepanjang hari.', 'img/products/68fa1d4ccde60_d.png', 1),
(2, 'Terra Mystique', 'Bleu Majestic', 'Woody', 'Female', 50, 0, 0, 'Keharuman hutan setelah hujan, memadukan patchouli dan lumut dengan sedikit vanilla untuk menciptakan aura misterius dan elegan.', 'img/products/68fa261ebaa58_Beauty_product_PSD__High_Quality_Free_PSD_Templates_for_Download___Freepik-removebg-preview.png', 1),
(3, 'Bloom Night', 'Ethereal Essence', 'Floral', 'Male', 50, 812501, 26, 'Parfum floral yang berani untuk pria. Perpaduan lavender gelap dan patchouli, unik dan memikat, cocok untuk acara malam.', 'img/products/68fa3b207c61e_68415c74e4b35.jpg', 0),
(4, 'Aqua Vert', 'Ethereal Essence', 'Fresh', 'Male', 100, 424843, 18, 'Kombinasi sea salt dan grapefruit yang sangat jernih. Aroma fresh klasik yang tak lekang oleh waktu, memberikan kesegaran maksimal.', 'img/products/68fa3c746db00_Blue_Perfume_Bottle_Transparent_Background__Perfume_Bottles__Perfume__Bottle_PNG_Transparent_Image_and_Clipart_for_Free_Download-removebg-preview.png', 0),
(5, 'Artisan Bouquet', 'Creazione Atelier', 'Floral', 'Male', 200, 555862, 12, 'Sentuhan artisanal dengan inti bunga iris dan violet. Memberikan kesan powdery yang lembut dan dewasa. Ukuran jumbo untuk pemakaian harian.', 'img/products/68fa3b6f13afa_aromatic.png', 0),
(6, 'Sugar Coma', 'Ethereal Essence', 'Gourmand', 'Female', 50, 819334, 34, 'Manisnya caramel yang lembut dan vanilla Madagaskar. Aroma hangat yang adiktif, sangat feminin dan cocok untuk kencan.', 'img/products/68fa3b799ed3a_chypre.png', 0),
(7, 'Soleil Blanc', 'Bleu Majestic', 'Citrus', 'Female', 100, 592717, 29, 'Ledakan jeruk Sisilia dan bergamot yang cerah. Aroma citrus yang mencerahkan suasana dan tahan lama.', 'img/products/68fa3b8270240_citrusy.png', 0),
(8, 'Morning Dew', 'Aura Maison', 'Fresh', 'Female', 200, 319101, 30, 'Kesegaran embun pagi yang bertemu dengan white tea dan musk. Sangat ringan, bersih, dan memancarkan keanggunan minimalis.', 'img/products/68fa3b8b3a977_floral.png', 0),
(9, 'Nomad Spice', 'Désert Studio', 'Oriental', 'Unisex', 50, 748399, 34, 'Rempah-rempah gurun yang kaya, dikombinasikan dengan amber hangat dan sedikit leather. Aroma intens dan memikat yang tidak mengenal gender.', 'img/products/68fa3b973f80d_68447d5547d06.jpg', 1),
(10, 'Oud Odyssey', 'Désert Studio', 'Oriental', 'Unisex', 100, 840590, 36, 'Inti oud murni yang mewah dengan lapisan incense dan frankincense. Aroma legendaris untuk jiwa petualang yang mencari kemewahan.', 'img/products/68fa3ba4c3044_fruity.png', 0),
(11, 'Zen Garden', 'Creazione Atelier', 'Fresh', 'Unisex', 200, 486269, 37, 'Keseimbangan sempurna antara bamboo segar dan moss basah. Aroma tenang, clean, dan sangat versatile untuk siapa saja.', 'img/products/68fa3badb1416_gourmand.png', 0),
(12, 'Midnight Treat', 'Fable & Co.', 'Gourmand', 'Unisex', 100, 376093, 32, 'Aroma dark chocolate dan roasted coffee bean yang manis dan pahit. Manisnya terasa mahal, tidak lengket, cocok untuk suasana santai.', 'img/products/68fa3bb6bcca8_green.png', 0),
(13, 'Electric Zest', 'Ethereal Essence', 'Citrus', 'Unisex', 200, 455616, 40, 'Asam manis dari kulit lime dan mandarin yang diberi sentuhan musk putih. Populer untuk cuaca panas dan aktivitas luar ruangan.', 'img/products/68fa3bc0464f9_marine.png', 0),
(14, 'Velvet Charm', 'Aura Maison', 'Gourmand', 'Female', 50, 749753, 18, 'Perpaduan lembut almond panggang dan tonka bean. Memberikan kesan nyaman, mewah, dan hangat. Pas untuk dibawa bepergian.', 'img/products/68fa3bc7e2fd8_oriental.png', 0),
(15, 'Cloud Nine', 'Fable & Co.', 'Fresh', 'Female', 200, 746516, 20, 'Aroma sejuk cotton dan lily of the valley. Kesegaran yang ringan seperti mengenakan pakaian bersih, sangat feminin.', 'img/products/68fa3bcfccc12_oriental2.png', 0),
(16, 'Forest Reserve', 'Aura Maison', 'Woody', 'Male', 200, 368996, 27, 'Komposisi oakmoss dan vetiver yang kuat. Aroma gentleman klasik yang memancarkan kekuatan alami dan dominasi', 'img/products/68fa3bd8e137b_spicy.png', 0),
(17, 'Gentleman Rose', 'Aura Maison', 'Floral', 'Male', 100, 238201, 18, 'Mawar Bulgaria yang elegan dicampur dengan black pepper dan agarwood. Bunga untuk pria modern yang percaya diri dan berkelas.', 'img/products/68fa3be30aeec_woody.png', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `role`) VALUES
(3, 'admin', '$2y$10$rh11him9CAJj94FtYMHtVebwE2v6ugP05FoK36YD..5PwtvYNj7iy', 'admin'),
(4, 'daffa', '$2y$10$Dl04tImu9EZ1.X/YmNW32eKZ9Hrsr4B26guxef6UbPwHCVHd/m2UO', 'user');

-- --------------------------------------------------------

--
-- Struktur dari tabel `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `parfum_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `about_us_content`
--
ALTER TABLE `about_us_content`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_us_list_items`
--
ALTER TABLE `about_us_list_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `about_us_id` (`about_us_id`);

--
-- Indeks untuk tabel `carousel_items`
--
ALTER TABLE `carousel_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `parfum`
--
ALTER TABLE `parfum`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `parfums`
--
ALTER TABLE `parfums`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parfum_id` (`parfum_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `about_us_content`
--
ALTER TABLE `about_us_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `about_us_list_items`
--
ALTER TABLE `about_us_list_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `carousel_items`
--
ALTER TABLE `carousel_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `parfum`
--
ALTER TABLE `parfum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `parfums`
--
ALTER TABLE `parfums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `about_us_list_items`
--
ALTER TABLE `about_us_list_items`
  ADD CONSTRAINT `about_us_list_items_ibfk_1` FOREIGN KEY (`about_us_id`) REFERENCES `about_us_content` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`parfum_id`) REFERENCES `parfum` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
