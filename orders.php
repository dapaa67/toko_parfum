<?php
require_once 'models/OrderManager.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hanya user yang sudah login yang bisa mengakses halaman ini
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$orderManager = new OrderManager();
$orders = $orderManager->getOrdersByUserId($_SESSION['user_id']);

require_once 'views/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4">Riwayat Pesanan Saya</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($_GET['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info text-center">
            <p class="mb-0">Anda belum memiliki riwayat pesanan.</p>
            <a href="products.php" class="btn btn-primary mt-3">Mulai Belanja Sekarang</a>
        </div>
    <?php else: ?>
        <div class="accordion" id="ordersAccordion">
            <?php foreach ($orders as $order): ?>
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="heading-<?php echo $order['id']; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $order['id']; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $order['id']; ?>">
                            <div class="d-flex justify-content-between w-100 pe-3">
                                <?php
                                    // Tampilkan nomor_pesanan jika tersedia, jika tidak fallback ke #ID
                                    $nomorPesanan = !empty($order['nomor_pesanan'] ?? null)
                                        ? $order['nomor_pesanan']
                                        : '#' . $order['id'];
                                ?>
                                <span>
                                    Pesanan <?php echo htmlspecialchars($nomorPesanan); ?>
                                    <small class="d-block text-muted">
                                        <?php echo date('d F Y, H:i', strtotime($order['tanggal_pesanan'])); ?>
                                    </small>
                                </span>
                                <span class="badge bg-<?php echo $order['status'] === 'Pending' ? 'warning text-dark' : 'success'; ?> align-self-center">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse-<?php echo $order['id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $order['id']; ?>" data-bs-parent="#ordersAccordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Alamat Pengiriman:</strong>
                                    <p class="mb-1"><?php echo htmlspecialchars($order['nama_penerima']); ?></p>
                                    <p class="mb-1"><?php echo htmlspecialchars($order['telepon']); ?></p>
                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($order['alamat_pengiriman'])); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Ringkasan Pembayaran:</strong>
                                    <p class="mb-1">Metode: <?php echo htmlspecialchars($order['metode_pembayaran']); ?></p>
                                    <p class="mb-0 fw-bold">Total: Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></p>
                                    
                                    <?php if ($order['status'] === 'Pending' && $order['metode_pembayaran'] === 'Bank Transfer'): ?>
                                        <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#paymentModal" data-order-id="<?php echo $order['id']; ?>">
                                            <i class="bi bi-upload"></i> Konfirmasi Pembayaran
                                        </button>
                                    <?php elseif ($order['status'] === 'Menunggu Konfirmasi'): ?>
                                        <div class="mt-2 text-info">
                                            <i class="bi bi-clock-history"></i> Menunggu Konfirmasi Admin
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>
                            <strong>Item yang Dipesan:</strong>
                            <?php
                            $items = $orderManager->getOrderItems($order['id']);
                            foreach ($items as $item):
                            ?>
                                <div class="d-flex align-items-center my-2">
                                    <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['nama']); ?>" style="width: 50px; height: 50px; object-fit: cover;" class="rounded me-3">
                                    <div>
                                        <?php echo htmlspecialchars($item['nama']); ?>
                                        <small class="d-block text-muted">
                                            <?php echo $item['jumlah']; ?> x Rp <?php echo number_format($item['harga_saat_beli'], 0, ',', '.'); ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Konfirmasi Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Upload Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="payment_confirm.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading fw-bold"><i class="bi bi-bank"></i> Informasi Pembayaran</h6>
                        <p class="mb-0 small">Silakan transfer ke rekening berikut:</p>
                        <p class="mb-0 fw-bold">BCA 1234567890 a.n. Toko Parfum</p>
                        <hr>
                        <p class="mb-0 small">Total yang harus dibayar sesuai dengan total pesanan Anda.</p>
                    </div>
                    <input type="hidden" name="order_id" id="modalOrderId">
                    <div class="mb-3">
                        <label for="payment_proof" class="form-label">Pilih Foto Bukti Transfer</label>
                        <input type="file" class="form-control" id="payment_proof" name="payment_proof" required accept="image/*">
                        <div class="form-text">Format: JPG, PNG. Maksimal 2MB.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Bukti</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/footer.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var paymentModal = document.getElementById('paymentModal');
    paymentModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var orderId = button.getAttribute('data-order-id');
        var modalOrderIdInput = document.getElementById('modalOrderId');
        modalOrderIdInput.value = orderId;
    });
});
</script>