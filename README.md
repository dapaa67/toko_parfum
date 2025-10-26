## Muhammad Daffa 221011400800 07TPLP020
# Toko Parfum — E-Commerce Sederhana (PHP + MySQL)

Proyek ini adalah aplikasi katalog dan penjualan parfum sederhana berbasis PHP Native dan MySQL. Fokusnya pada kecepatan, kemudahan deploy di XAMPP, dan panel admin untuk kelola produk, banner, serta konten dasar.

Bagian ini menyertakan pratinjau hasil akhir yang tersimpan di folder gambar proyek.


Fitur Utama

- Katalog parfum lengkap dengan kategori dan halaman detail produk.
- Banner carousel dinamis di beranda.
- Halaman informasi perusahaan dan lokasi toko.
- Halaman kontak sederhana.
- Autentikasi dan area admin untuk mengelola konten.
- Manajemen produk dari panel admin.
- Antarmuka responsif dengan CSS kustom.

Teknologi & Prasyarat

- PHP 8.x atau lebih baru
- MySQL 5.7+ atau MariaDB
- XAMPP (Apache + MySQL) di Windows
- Browser modern

Struktur Proyek

- [index.php](index.php) beranda dan carousel.
- [products.php](products.php) daftar produk.
- [detail.php](detail.php) halaman detail produk.
- [stores.php](stores.php) lokasi atau daftar toko.
- [company.php](company.php) profil perusahaan.
- [contact.php](contact.php) halaman kontak.
- [login.php](login.php) halaman login.
- [logout.php](logout.php) keluar sesi.
- [admin/dashboard.php](admin/dashboard.php) dashboard admin.
- [admin/products.php](admin/products.php) kelola produk.
- [admin/carousel.php](admin/carousel.php) kelola banner carousel.
- [admin/includes/sidebar.php](admin/includes/sidebar.php) dan [admin/includes/footer.php](admin/includes/footer.php) komponen admin.
- [views/header.php](views/header.php) dan [views/footer.php](views/footer.php) komponen frontend.
- [css/style.css](css/style.css) gaya utama, [sidebar.css](sidebar.css) gaya sidebar, [sidebar.js](sidebar.js) interaksi sidebar.
- [models/DB.php](models/DB.php) koneksi database.
- [models/Parfum.php](models/Parfum.php) entitas produk parfum.
- [models/ParfumManager.php](models/ParfumManager.php) operasi data parfum.
- [models/AuthManager.php](models/AuthManager.php) autentikasi pengguna.
- [models/CarouselManager.php](models/CarouselManager.php) data carousel.
- [models/AboutUsManager.php](models/AboutUsManager.php) data profil perusahaan.
- [toko_parfum.sql](toko_parfum.sql) skema dan data awal database.
- [seed_products.php](seed_products.php) skrip seeding opsional.
- Folder aset: [img/](img/), [img/products/](img/products/), [img/carousel/](img/carousel/), [img project/](img%20project/), [css/](css/).

Instalasi Cepat (XAMPP)

1. Pastikan Apache dan MySQL aktif di XAMPP.
2. Salin folder proyek ini ke C:\xampp\htdocs dengan nama toko_parfum sehingga dapat diakses di http://localhost/toko_parfum/.
3. Buat database baru bernama toko_parfum di phpMyAdmin.
4. Import berkas [toko_parfum.sql](toko_parfum.sql) ke database tersebut.
5. Buka berkas [models/DB.php](models/DB.php) lalu sesuaikan konfigurasi koneksi database (host, nama database, username, dan password).
6. Opsional: jalankan seeder produk dengan membuka URL http://localhost/toko_parfum/seed_products.php untuk mengisi data contoh.
7. Akses aplikasi di browser melalui http://localhost/toko_parfum/.

Akses Admin

- Halaman login: [login.php](login.php).
- Setelah berhasil login, panel admin tersedia di [admin/dashboard.php](admin/dashboard.php), kelola produk di [admin/products.php](admin/products.php), dan kelola banner di [admin/carousel.php](admin/carousel.php).
- Kredensial default tidak disertakan. Jika Anda perlu membuat pengguna admin:
  - Gunakan [get_hash.php](get_hash.php) untuk menghasilkan hash kata sandi lalu simpan ke tabel pengguna melalui phpMyAdmin.
  - Atau tambahkan user langsung via SQL sesuai struktur pada [toko_parfum.sql](toko_parfum.sql).

Navigasi Halaman Utama

- Beranda: [index.php](index.php)
- Produk: [products.php](products.php)
- Detail Produk: [detail.php](detail.php)
- Toko: [stores.php](stores.php)
- Tentang Perusahaan: [company.php](company.php)
- Kontak: [contact.php](contact.php)

Pratinjau UI (Screenshots)

[![Home](img%20project/home.png)](img%20project/home.png)

[![Toko](img%20project/toko.png)](img%20project/toko.png)

[![Kontak](img%20project/kontak.png)](img%20project/kontak.png)

[![Login Admin](img%20project/login%20admin.png)](img%20project/login%20admin.png)

[![Dashboard Admin](img%20project/dashboard%20admin.png)](img%20project/dashboard%20admin.png)

[![Produk Admin](img%20project/produk%20admin.png)](img%20project/produk%20admin.png)

[![Banner Admin](img%20project/banner%20admin.png)](img%20project/banner%20admin.png)

Tips Produksi dan Keamanan

- Selalu gunakan password yang kuat dan simpan hash, bukan teks asli. Lihat [get_hash.php](get_hash.php) untuk membantu membuat hash.
- Lindungi folder admin dengan autentikasi yang ketat di sisi server.
- Validasi dan sanitasi semua input pengguna sebelum disimpan ke database.
- Konfigurasikan pengunggahan gambar agar hanya menerima tipe file aman dan ukuran wajar.
- Matikan display_errors di produksi dan catat error ke log server.

Troubleshooting

- Koneksi database gagal: cek kredensial di [models/DB.php](models/DB.php) dan pastikan layanan MySQL aktif.
- Halaman kosong atau error 500: aktifkan log error Apache dan periksa konfigurasi PHP.
- Aset (CSS/JS/Gambar) 404: pastikan jalur relatif benar dan file tersalin lengkap.
- Gambar tidak muncul: periksa nama file, ekstensi, serta izin baca pada folder [img/](img/).

Lisensi

Proyek ini tidak menyertakan lisensi terbuka. Gunakan untuk pembelajaran atau kembangkan secara internal sesuai kebutuhan.

Kredit

UI, konten, dan aset gambar sesuai dengan berkas yang ada di folder proyek ini. Terima kasih kepada semua pihak yang berkontribusi.
