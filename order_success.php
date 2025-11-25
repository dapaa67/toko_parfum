<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'models/OrderManager.php';

// Keamanan: Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// 1. Ambil order_id dari URL dan pastikan aman (hanya angka)
$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($orderId <= 0) {
    // Jika order_id tidak valid, arahkan ke halaman riwayat pesanan
    header('Location: orders.php');
    exit();
}

// 2. Ambil data pesanan dari database
$orderManager = new OrderManager();
$order = $orderManager->getOrderById($orderId);

// Keamanan: Pastikan pesanan ada dan milik user yang sedang login
if (!$order || $order['user_id'] != $_SESSION['user_id']) {
    // Jika tidak, jangan tampilkan apa-apa dan arahkan pergi
    header('Location: orders.php');
    exit();
}

require_once 'views/header.php';
?>

<div class="container my-5 text-center">
    <div class="py-5 px-md-5">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
        <h1 class="display-5 mt-3">Pesanan Berhasil!</h1>
        <?php
            // Tampilkan nomor_pesanan jika tersedia, jika tidak fallback ke #ID
            $nomorPesanan = !empty($order['nomor_pesanan'] ?? null)
                ? $order['nomor_pesanan']
                : '#' . $order['id'];
        ?>
        <p class="lead">
            Terima kasih telah berbelanja. Pesanan Anda dengan nomor <strong><?php echo htmlspecialchars($nomorPesanan); ?></strong> telah kami terima.
        </p>

        <!-- 3. Tampilan Halaman dengan Logika Kondisional -->
        <div class="card border-0 shadow-sm mt-4 mx-auto" style="max-width: 600px;">
            <div class="card-body text-start p-4">
                <h5 class="card-title text-center mb-3">Ringkasan & Instruksi</h5>
                <div class="d-flex justify-content-between">
                    <span>Total Pembayaran:</span>
                    <strong class="fs-5 text-primary">Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Metode Pembayaran:</span>
                    <strong><?php echo htmlspecialchars($order['metode_pembayaran']); ?></strong>
                </div>

                <hr>

                <?php if ($order['metode_pembayaran'] == 'Bank Transfer'): ?>
                    <div class="alert alert-info">
                            <strong>Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></strong> untuk diserahkan kepada kurir saat pesanan tiba.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <hr>
        <!-- 4. Tombol Aksi -->
        <p class="mb-0">
            <a href="products.php" class="btn btn-primary">Lanjut Belanja</a>
            <a href="orders.php" class="btn btn-secondary">Lihat Riwayat Pesanan</a>
        </p>
    </div>
</div>

<?php require_once 'views/footer.php'; ?>