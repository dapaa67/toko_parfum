<?php
require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');

require_once '../models/OrderManager.php';

$orderId = $_GET['order_id'] ?? 0;

if (!$orderId) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">ID Pesanan tidak valid.</div>';
    exit;
}

$orderManager = new OrderManager();
$items = $orderManager->getOrderItems($orderId);

if (empty($items)) {
    echo '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">Tidak ada item ditemukan untuk pesanan ini.</div>';
    exit;
}
?>

<div class="overflow-x-auto border border-gray-200 rounded-lg mb-6">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php 
            $grandTotal = 0;
            foreach ($items as $item): 
                $subtotal = $item['jumlah'] * $item['harga_saat_beli'];
                $grandTotal += $subtotal;
            ?>
                <tr>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <img class="h-10 w-10 rounded object-cover" src="../<?php echo htmlspecialchars($item['image_path']); ?>" alt="">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($item['nama']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-gray-500"><?php echo $item['jumlah']; ?></td>
                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-500">Rp <?php echo number_format($item['harga_saat_beli'], 0, ',', '.'); ?></td>
                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-semibold text-gray-900">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="bg-gray-50">
            <tr>
                <td colspan="3" class="px-4 py-3 text-right text-sm font-bold text-gray-900">Total Pesanan</td>
                <td class="px-4 py-3 text-right text-sm font-bold text-blue-600">Rp <?php echo number_format($grandTotal, 0, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php
// Ambil detail order untuk cek status dan bukti bayar
$order = $orderManager->getOrderById($orderId);
?>

<?php if ($order['payment_proof']): ?>
    <div class="mt-6">
        <h6 class="font-bold text-gray-900 mb-3">Bukti Pembayaran</h6>
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4 flex justify-center">
            <img src="../<?php echo htmlspecialchars($order['payment_proof']); ?>" class="max-h-64 rounded shadow-sm" alt="Bukti Pembayaran">
        </div>
        
        <?php if ($order['status'] === 'Menunggu Konfirmasi'): ?>
            <div class="flex justify-end gap-3">
                <button type="button" 
                        onclick="showRejectModal(<?php echo $orderId; ?>)"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold flex items-center">
                    <i class="bi bi-x-circle mr-2"></i> Tolak Pembayaran
                </button>
                <button type="button" 
                        onclick="showApproveModal(<?php echo $orderId; ?>)"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold flex items-center">
                    <i class="bi bi-check-circle mr-2"></i> Setujui Pembayaran
                </button>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php 
// Logika untuk tombol "Selesaikan Pesanan (COD)"
if ($order['metode_pembayaran'] === 'Cash on Delivery (COD)' && $order['status'] === 'Pending'): 
?>
    <div class="mt-6 text-right">
        <button type="button" 
                onclick="showCodModal(<?php echo $orderId; ?>)"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold flex items-center inline-flex">
            <i class="bi bi-check2-circle mr-2"></i> Selesaikan Pesanan (COD)
        </button>
    </div>
<?php endif; ?>
