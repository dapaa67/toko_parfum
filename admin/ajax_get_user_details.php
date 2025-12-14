<?php
require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');

require_once '../models/UserManager.php';
require_once '../models/OrderManager.php';

$userId = $_GET['user_id'] ?? 0;

if (!$userId) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">ID User tidak valid.</div>';
    exit;
}

$userManager = new UserManager();
$orderManager = new OrderManager();

$user = $userManager->getUserById($userId);
$orders = $orderManager->getOrdersByUserId($userId);

if (!$user) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">User tidak ditemukan.</div>';
    exit;
}
?>

<div class="mb-6">
    <h6 class="font-bold text-xs uppercase text-gray-500 mb-3">Informasi Pengguna</h6>
    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
        <table class="w-full text-sm">
            <tr>
                <td class="w-32 text-gray-500 py-1">Username</td>
                <td class="font-medium text-gray-900">: <?php echo htmlspecialchars($user['username']); ?></td>
            </tr>
            <tr>
                <td class="text-gray-500 py-1">Nama Lengkap</td>
                <td class="font-medium text-gray-900">: <?php echo htmlspecialchars($user['full_name'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="text-gray-500 py-1">Email</td>
                <td class="font-medium text-gray-900">: <?php echo htmlspecialchars($user['email'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="text-gray-500 py-1">No. Telepon</td>
                <td class="font-medium text-gray-900">: <?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="text-gray-500 py-1">Alamat</td>
                <td class="font-medium text-gray-900">: <?php echo htmlspecialchars($user['address'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="text-gray-500 py-1">Status</td>
                <td class="py-1">
                    : 
                    <?php if (isset($user['is_active']) && $user['is_active'] == 0): ?>
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Banned</span>
                    <?php else: ?>
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<h6 class="font-bold text-xs uppercase text-gray-500 mb-3">Riwayat Pesanan</h6>
<div class="overflow-x-auto border border-gray-200 rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500 text-sm">Belum ada riwayat pesanan.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <?php
                        $displayNo = !empty($order['nomor_pesanan'] ?? null)
                            ? $order['nomor_pesanan']
                            : '#' . $order['id'];
                            
                        $statusClass = 'bg-gray-100 text-gray-800';
                        if ($order['status'] === 'Pending') $statusClass = 'bg-yellow-100 text-yellow-800';
                        elseif ($order['status'] === 'Selesai') $statusClass = 'bg-green-100 text-green-800';
                        elseif ($order['status'] === 'Dibatalkan') $statusClass = 'bg-red-100 text-red-800';
                        elseif ($order['status'] === 'Menunggu Konfirmasi') $statusClass = 'bg-blue-100 text-blue-800';
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-mono text-gray-600"><?php echo htmlspecialchars($displayNo); ?></td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900"><?php echo date('d M Y', strtotime($order['tanggal_pesanan'])); ?></td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($order['status']); ?>
                            </span>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium">
                            <button type="button" 
                                    onclick="showOrderDetailsModal(<?php echo $order['id']; ?>)"
                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded transition">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
