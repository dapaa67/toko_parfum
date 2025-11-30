-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Nov 2025 pada 10.10
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
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nomor_pesanan` varchar(100) NOT NULL,
  `nama_penerima` varchar(255) NOT NULL,
  `alamat_pengiriman` text NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `payment_proof` varchar(255) DEFAULT NULL,
  `tanggal_pesanan` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `nomor_pesanan`, `nama_penerima`, `alamat_pengiriman`, `telepon`, `total_harga`, `metode_pembayaran`, `status`, `payment_proof`, `tanggal_pesanan`) VALUES
(24, 4, 'INV/20251116-165751/U4/384', 'daffa', 'asdasdqweqeq', '081212312323', 99999999.99, 'Cash on Delivery (COD)', 'Selesai', NULL, '2025-11-16 22:57:51'),
(25, 4, 'INV/20251116-170307/U4/673', 'aisyah', 'dasdqweqweqeqwe', '081231239223', 1368363.00, 'Cash on Delivery (COD)', 'Selesai', NULL, '2025-11-16 23:03:07'),
(26, 4, 'INV/20251116-170332/U4/561', 'ramadhan', 'asdqwrqyhqerq', '081239123123', 1171359.00, 'Bank Transfer', 'Pending', NULL, '2025-11-16 23:03:32'),
(27, 5, 'INV/20251116-171847/U5/402', 'ramadhan', 'jatiwarining', '081231232222', 99999999.99, 'Cash on Delivery (COD)', 'Selesai', NULL, '2025-11-16 23:18:47'),
(28, 4, 'INV/20251123-151241/U4/695', 'daffa', 'asedasdadadadaasd', '081212312323', 99999999.99, 'Bank Transfer', 'Pending', NULL, '2025-11-23 21:12:41'),
(29, 5, 'INV/20251125-154606/U5/454', 'daffa', 'sdasdasd', '081212312323', 500000.00, 'Cash on Delivery (COD)', 'Selesai', NULL, '2025-11-25 21:46:06'),
(30, 5, 'INV/20251125-154627/U5/503', 'aisyah', 'sadasda', '0812123123123', 99999999.99, 'Bank Transfer', 'Selesai', 'img/payment_proofs/1764082232_WhatsApp Image 2025-11-15 at 17.48.40.jpeg', '2025-11-25 21:46:27'),
(31, 5, 'INV/20251125-154948/U5/816', 'daffa', 'asdasdasd', '081231232222', 1200000.00, 'Bank Transfer', 'Selesai', 'img/payment_proofs/1764082223_WhatsApp Image 2025-11-15 at 17.48.40.jpeg', '2025-11-25 21:49:48'),
(32, 5, 'INV/20251125-164108/U5/154', 'ramadhan', 'asdqweqweq', '081231232222', 1200000.00, 'Bank Transfer', 'Menunggu Konfirmasi', 'img/payment_proofs/1764086658_WhatsApp Image 2025-11-15 at 17.48.40.jpeg', '2025-11-25 22:41:08'),
(33, 5, 'INV/20251125-164735/U5/588', 'asdasd', 'asdqweq', '081231232222', 99999999.99, 'Bank Transfer', 'Dibatalkan', 'img/payment_proofs/1764086863_WhatsApp Image 2025-11-15 at 17.48.40.jpeg', '2025-11-25 22:47:35'),
(34, 5, 'INV/20251125-170917/U5/229', 'dasdas', 'aseqweqweq', '081239123123', 819334.00, 'Bank Transfer', 'Menunggu Konfirmasi', 'img/payment_proofs/1764088037_WhatsApp Image 2025-11-15 at 17.48.40.jpeg', '2025-11-25 23:09:17'),
(35, 5, 'INV/20251128-123733/U5/344', 'asda', 'aseasqas', '1231231', 319101.00, 'Bank Transfer', 'Menunggu Konfirmasi', 'img/payment_proofs/1764329861_WhatsApp Image 2025-11-15 at 17.48.40.jpeg', '2025-11-28 18:37:33'),
(36, 5, 'INV/20251129-014259/U5/720', 'asdasd', 'khugkjk', '0812123123123', 368996.00, 'Bank Transfer', 'Selesai', 'img/payment_proofs/1764376991_WhatsApp Image 2025-11-15 at 17.48.40.jpeg', '2025-11-29 07:42:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `parfum_id` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_saat_beli` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `parfum_id`, `jumlah`, `harga_saat_beli`) VALUES
(24, 24, 1, 1, 99999999.99),
(25, 24, 10, 1, 840590.00),
(26, 25, 3, 1, 812501.00),
(27, 25, 5, 1, 555862.00),
(28, 26, 4, 1, 424843.00),
(29, 26, 15, 1, 746516.00),
(30, 27, 1, 1, 99999999.99),
(31, 28, 1, 1, 99999999.99),
(32, 28, 4, 1, 424843.00),
(33, 29, NULL, 1, 500000.00),
(34, 30, 1, 1, 99999999.99),
(35, 31, 2, 1, 1200000.00),
(36, 32, 2, 1, 1200000.00),
(37, 33, 1, 1, 99999999.99),
(38, 34, 6, 1, 819334.00),
(39, 35, 8, 1, 319101.00),
(40, 36, 16, 1, 368996.00);

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
(1, 'Azure Coast', 'Koda Essence', 'Fresh', 'Male', 100, 100000000, 7, 'Aroma citrus yang tajam dengan sentuhan air laut yang menyegarkan, sempurna untuk pria yang aktif dan dinamis sepanjang hari.', 'img/products/68fa1d4ccde60_d.png', 1),
(2, 'Terra Mystique', 'Bleu Majestic', 'Woody', 'Female', 50, 1200000, 19, 'Keharuman hutan setelah hujan, memadukan patchouli dan lumut dengan sedikit vanilla untuk menciptakan aura misterius dan elegan.', 'img/products/68fa261ebaa58_Beauty_product_PSD__High_Quality_Free_PSD_Templates_for_Download___Freepik-removebg-preview.png', 1),
(3, 'Bloom Night', 'Ethereal Essence', 'Floral', 'Male', 50, 812501, 25, 'Parfum floral yang berani untuk pria. Perpaduan lavender gelap dan patchouli, unik dan memikat, cocok untuk acara malam.', 'img/products/68fa3b207c61e_68415c74e4b35.jpg', 0),
(4, 'Aqua Vert', 'Ethereal Essence', 'Fresh', 'Male', 100, 424843, 16, 'Kombinasi sea salt dan grapefruit yang sangat jernih. Aroma fresh klasik yang tak lekang oleh waktu, memberikan kesegaran maksimal.', 'img/products/68fa3c746db00_Blue_Perfume_Bottle_Transparent_Background__Perfume_Bottles__Perfume__Bottle_PNG_Transparent_Image_and_Clipart_for_Free_Download-removebg-preview.png', 0),
(5, 'Artisan Bouquet', 'Creazione Atelier', 'Floral', 'Male', 200, 555862, 11, 'Sentuhan artisanal dengan inti bunga iris dan violet. Memberikan kesan powdery yang lembut dan dewasa. Ukuran jumbo untuk pemakaian harian.', 'img/products/68fa3b6f13afa_aromatic.png', 0),
(6, 'Sugar Coma', 'Ethereal Essence', 'Gourmand', 'Female', 50, 819334, 33, 'Manisnya caramel yang lembut dan vanilla Madagaskar. Aroma hangat yang adiktif, sangat feminin dan cocok untuk kencan.', 'img/products/68fa3b799ed3a_chypre.png', 0),
(7, 'Soleil Blanc', 'Bleu Majestic', 'Citrus', 'Female', 100, 592717, 29, 'Ledakan jeruk Sisilia dan bergamot yang cerah. Aroma citrus yang mencerahkan suasana dan tahan lama.', 'img/products/68fa3b8270240_citrusy.png', 0),
(8, 'Morning Dew', 'Aura Maison', 'Fresh', 'Female', 200, 319101, 29, 'Kesegaran embun pagi yang bertemu dengan white tea dan musk. Sangat ringan, bersih, dan memancarkan keanggunan minimalis.', 'img/products/68fa3b8b3a977_floral.png', 0),
(9, 'Nomad Spice', 'Désert Studio', 'Oriental', 'Unisex', 50, 748399, 34, 'Rempah-rempah gurun yang kaya, dikombinasikan dengan amber hangat dan sedikit leather. Aroma intens dan memikat yang tidak mengenal gender.', 'img/products/68fa3b973f80d_68447d5547d06.jpg', 1),
(10, 'Oud Odyssey', 'Désert Studio', 'Oriental', 'Unisex', 100, 840590, 35, 'Inti oud murni yang mewah dengan lapisan incense dan frankincense. Aroma legendaris untuk jiwa petualang yang mencari kemewahan.', 'img/products/68fa3ba4c3044_fruity.png', 0),
(11, 'Zen Garden', 'Creazione Atelier', 'Fresh', 'Unisex', 200, 486269, 37, 'Keseimbangan sempurna antara bamboo segar dan moss basah. Aroma tenang, clean, dan sangat versatile untuk siapa saja.', 'img/products/68fa3badb1416_gourmand.png', 0),
(12, 'Midnight Treat', 'Fable & Co.', 'Gourmand', 'Unisex', 100, 376093, 32, 'Aroma dark chocolate dan roasted coffee bean yang manis dan pahit. Manisnya terasa mahal, tidak lengket, cocok untuk suasana santai.', 'img/products/68fa3bb6bcca8_green.png', 0),
(13, 'Electric Zest', 'Ethereal Essence', 'Citrus', 'Unisex', 200, 455616, 40, 'Asam manis dari kulit lime dan mandarin yang diberi sentuhan musk putih. Populer untuk cuaca panas dan aktivitas luar ruangan.', 'img/products/68fa3bc0464f9_marine.png', 0),
(14, 'Velvet Charm', 'Aura Maison', 'Gourmand', 'Female', 50, 749753, 18, 'Perpaduan lembut almond panggang dan tonka bean. Memberikan kesan nyaman, mewah, dan hangat. Pas untuk dibawa bepergian.', 'img/products/68fa3bc7e2fd8_oriental.png', 0),
(15, 'Cloud Nine', 'Fable & Co.', 'Fresh', 'Female', 200, 746516, 19, 'Aroma sejuk cotton dan lily of the valley. Kesegaran yang ringan seperti mengenakan pakaian bersih, sangat feminin.', 'img/products/68fa3bcfccc12_oriental2.png', 0),
(16, 'Forest Reserve', 'Aura Maison', 'Woody', 'Male', 200, 368996, 26, 'Komposisi oakmoss dan vetiver yang kuat. Aroma gentleman klasik yang memancarkan kekuatan alami dan dominasi', 'img/products/68fa3bd8e137b_spicy.png', 0),
(17, 'Gentleman Rose', 'Aura Maison', 'Floral', 'Male', 100, 238201, 18, 'Mawar Bulgaria yang elegan dicampur dengan black pepper dan agarwood. Bunga untuk pria modern yang percaya diri dan berkelas.', 'img/products/68fa3be30aeec_woody.png', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `full_name`, `email`, `phone`, `address`, `password`, `role`, `is_active`) VALUES
(3, 'admin', NULL, NULL, NULL, NULL, '$2y$10$rh11him9CAJj94FtYMHtVebwE2v6ugP05FoK36YD..5PwtvYNj7iy', 'admin', 1),
(4, 'daffa', 'Daffa Test Update', 'daffa@test.com', '081234567890', 'Jl. Test No. 123', '$2y$10$w5W3v4Ra4ObR.NIS4Pm4ruEpGEuXO/zJTnr.h3KXT5YyphbFichx.', 'user', 0),
(5, 'user1', 'daffa', 'dapa@gmail.com', '085211439743', 'pamulang barat', '$2y$10$7PD3BE5u4wHY2FN/ekfr8OhOE6jMdcT4aHewShkBBm.PmiBkCe/Ci', 'user', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_carts`
--

CREATE TABLE `user_carts` (
  `user_id` int(11) NOT NULL,
  `cart_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cart_data`)),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_carts`
--

INSERT INTO `user_carts` (`user_id`, `cart_data`, `updated_at`) VALUES
(4, '[]', '2025-11-23 21:12:42'),
(5, '[]', '2025-11-29 07:42:59');

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
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `parfum_id` (`parfum_id`);

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
-- Indeks untuk tabel `user_carts`
--
ALTER TABLE `user_carts`
  ADD PRIMARY KEY (`user_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT untuk tabel `parfums`
--
ALTER TABLE `parfums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`parfum_id`) REFERENCES `parfums` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`parfum_id`) REFERENCES `parfums` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
