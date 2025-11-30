<?php
require_once 'models/OrderManager.php';
require_once 'models/CartManager.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect jika diakses langsung atau bukan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

// Redirect jika belum login atau keranjang kosong
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$cartManager = new CartManager();
$cartItems = $cartManager->getItems();

if (empty($cartItems)) {
    header('Location: cart.php');
    exit();
}

// Ambil dan validasi data dari form
$nama_penerima = trim($_POST['nama_penerima'] ?? '');
$alamat_pengiriman = trim($_POST['alamat_pengiriman'] ?? '');
$telepon = trim($_POST['telepon'] ?? '');
$metode_pembayaran = $_POST['metode_pembayaran'] ?? '';

if (empty($nama_penerima) || empty($alamat_pengiriman) || empty($telepon) || empty($metode_pembayaran)) {
    $_SESSION['error_message'] = 'Semua field wajib diisi.';
    header('Location: checkout.php');
    exit();
}

$orderDetails = [
    'nama_penerima' => $nama_penerima,
    'alamat_pengiriman' => $alamat_pengiriman,
    'telepon' => $telepon,
    'metode_pembayaran' => $metode_pembayaran,
    'total_harga' => $cartManager->getTotalPrice()
];

$orderManager = new OrderManager();
$orderId = $orderManager->createOrder($_SESSION['user_id'], $orderDetails, $cartItems);

if ($orderId) {
    // Pesanan berhasil, kosongkan keranjang dan redirect ke halaman sukses
    $cartManager->clear();
    header("Location: order_success.php?order_id=" . $orderId);
    exit();
} else {
    // Pesanan gagal
    // Jika OrderManager sudah mengatur pesan error (misalnya stok tidak mencukupi),
    // jangan timpa pesan tersebut agar user tahu penyebab pastinya.
    if (!isset($_SESSION['error_message']) || empty($_SESSION['error_message'])) {
        $_SESSION['error_message'] = 'Gagal membuat pesanan. Stok produk mungkin tidak mencukupi atau terjadi kesalahan sistem.';
    }
    header('Location: checkout.php');
    exit();
}
