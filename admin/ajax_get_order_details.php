<?php
require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');

require_once '../models/OrderManager.php';

$orderId = $_GET['order_id'] ?? 0;

if (!$orderId) {
    echo '<div class="alert alert-danger">ID Pesanan tidak valid.</div>';
    exit;
}

$orderManager = new OrderManager();
$items = $orderManager->getOrderItems($orderId);

if (empty($items)) {
    echo '<div class="alert alert-warning">Tidak ada item ditemukan untuk pesanan ini.</div>';
    exit;
}
?>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Produk</th>
                <th class="text-center">Jumlah</th>
                <th class="text-end">Harga Satuan</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $grandTotal = 0;
            foreach ($items as $item): 
                $subtotal = $item['jumlah'] * $item['harga_saat_beli'];
                $grandTotal += $subtotal;
            ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="../<?php echo htmlspecialchars($item['image_path']); ?>" alt="" width="50" height="50" class="rounded me-3" style="object-fit: cover;">
                            <span class="fw-medium"><?php echo htmlspecialchars($item['nama']); ?></span>
                        </div>
                    </td>
                    <td class="text-center"><?php echo $item['jumlah']; ?></td>
                    <td class="text-end">Rp <?php echo number_format($item['harga_saat_beli'], 0, ',', '.'); ?></td>
                    <td class="text-end fw-semibold">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="table-light">
            <tr>
                <td colspan="3" class="text-end fw-bold">Total Pesanan</td>
                <td class="text-end fw-bold text-primary fs-5">Rp <?php echo number_format($grandTotal, 0, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php
// Ambil detail order untuk cek status dan bukti bayar
$order = $orderManager->getOrderById($orderId);
?>

<?php if ($order['payment_proof']): ?>
    <div class="mt-4">
        <h6 class="fw-bold">Bukti Pembayaran</h6>
        <div class="card mb-3">
            <div class="card-body text-center">
                <img src="../<?php echo htmlspecialchars($order['payment_proof']); ?>" class="img-fluid rounded" style="max-height: 300px;" alt="Bukti Pembayaran">
            </div>
        </div>
        
        <?php if ($order['status'] === 'Menunggu Konfirmasi'): ?>
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-danger" onclick="showRejectModal(<?php echo $orderId; ?>)">
                    <i class="bi bi-x-circle me-1"></i> Tolak Pembayaran
                </button>
                <button type="button" class="btn btn-success" onclick="showApproveModal(<?php echo $orderId; ?>)">
                    <i class="bi bi-check-circle me-1"></i> Setujui Pembayaran
                </button>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php 
// Logika untuk tombol "Selesaikan Pesanan (COD)"
if ($order['metode_pembayaran'] === 'Cash on Delivery (COD)' && $order['status'] === 'Pending'): 
?>
    <div class="mt-4 text-end">
        <button type="button" class="btn btn-success" onclick="showCompleteCodModal(<?php echo $orderId; ?>)">
            <i class="bi bi-check2-circle me-1"></i> Selesaikan Pesanan (COD)
        </button>
    </div>
<?php endif; ?>
