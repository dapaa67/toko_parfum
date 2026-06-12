# Toko Parfum Online - Web Application

Aplikasi berbasis web untuk penjualan parfum secara online. Sistem ini mencakup fitur lengkap untuk **Pelanggan** (User) dan **Administrator** (Admin), mulai dari registrasi, transaksi pembelian, hingga laporan penjualan.

---

## 📋 Fitur Utama

### 👤 Halaman Pengguna (User)
1.  **Registrasi & Login**: Keamanan akses menggunakan akun pribadi.
2.  **Katalog Produk**: Menampilkan daftar parfum dengan harga dan deskripsi.
3.  **Keranjang Belanja (Cart)**: Menampung barang sebelum checkout.
4.  **Checkout & Pembayaran**: Proses pemesanan dan instruksi pembayaran.
5.  **Konfirmasi Pembayaran**: Upload bukti transfer via modal popup.
6.  **Riwayat Pesanan**: Memantau status pesanan (Pending, Menunggu Konfirmasi, Dikirim, Selesai).

### 🛠 Halaman Admin
1.  **Dashboard**: Ringkasan statistik toko.
2.  **Manajemen Produk**: Tambah, edit, dan hapus data parfum.
3.  **Konfirmasi Pesanan**: Verifikasi bukti pembayaran dari user.
4.  **Laporan Penjualan**: Rekapan detail transaksi yang berhasil.
5.  **Manajemen User**: Mengelola akun pengguna (Ban/Unban).

---

## 📸 Dokumentasi & Alur Aplikasi

Berikut adalah dokumentasi tampilan dan alur penggunaan aplikasi:

### 1. Login & Registrasi
Alur dimulai dengan registrasi akun baru jika belum memiliki akun, kemudian login untuk mendapatkan akses penuh ke fitur belanja.
*(Pastikan user sudah terdaftar dan aktif)*

![Login Page](img_project/login.png)
![Register Page](img_project/register.png)

### 2. Halaman Utama & Katalog
Setelah berhasil login, user dapat melihat banner promosi di halaman utama dan menjelajahi berbagai katalog produk parfum yang tersedia dengan detail harga dan deskripsi.

![Home Page](img_project/home.png)
![Katalog Produk](img_project/produk.png)
![Detail Produk](img_project/detail_produk.png)

### 3. Keranjang & Checkout
User memilih produk yang diinginkan, memasukkannya ke keranjang belanja, dan melanjutkan ke proses checkout dengan mengisi data pengiriman serta memilih metode pembayaran.

![Keranjang Belanja](img_project/keranjang.png)
![Proses Checkout](img_project/checkout.png)
![Checkout Berhasil](img_project/checkout_berhasil.png)

### 4. Konfirmasi Pembayaran
Setelah melakukan checkout, user harus mengunggah bukti transfer melalui menu riwayat pesanan agar admin dapat memverifikasi pembayaran tersebut.

![Upload Bukti Pembayaran](img_project/upload_bukti_pembayaran.jpeg)

### 5. Halaman Informasi
User dapat mengakses informasi tambahan mengenai profil toko, visi misi perusahaan, serta detail kontak untuk bantuan layanan pelanggan.

![Halaman Toko](img_project/halaman_toko.png)
![Halaman Perusahaan](img_project/halaman_perusahaan.png)
![Halaman Kontak](img_project/halaman_kontak.png)

### 6. Halaman Admin - Dashboard & Produk
Admin memiliki akses ke dashboard untuk memantau statistik penjualan secara real-time dan mengelola inventaris produk (Tambah, Edit, Hapus stok).

![Admin Dashboard](img_project/dashboard_admin.png)
![Manajemen Produk](img_project/kelola_produk_admin.png)
![Tambah Produk](img_project/tambah_produk_admin.png)

### 7. Admin - Kelola Banner & User
Admin dapat mengatur konten visual seperti banner promosi di halaman depan dan mengelola status akun pengguna (Aktif atau Ban).

![Kelola Banner](img_project/kelola_carousel_admin.png)
![Kelola User](img_project/manajemen_user_admin.png)

### 8. Laporan Penjualan (Sales Report)
Admin dapat melihat rekapan seluruh transaksi yang masuk, memantau omzet, dan melakukan verifikasi (Setuju/Tolak) terhadap bukti pembayaran yang dikirimkan oleh user.

![Laporan Penjualan](img_project/laporan_penjualan_admin.png)

---

## Teknologi yang Digunakan
*   **Bahasa Pemrograman**: PHP Native
*   **Database**: MySQL (MariaDB)
*   **Frontend**: HTML, CSS, Bootstrap 5, Tailwind CSS, Alpine.js
*   **Server**: Apache (XAMPP)

---

## Admin
admin
123
