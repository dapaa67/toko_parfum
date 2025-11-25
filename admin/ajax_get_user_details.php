<?php
require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');

require_once '../models/UserManager.php';
require_once '../models/OrderManager.php';

$userId = $_GET['user_id'] ?? 0;

if (!$userId) {
    echo '<div class="alert alert-danger">ID User tidak valid.</div>';
    exit;
}

$userManager = new UserManager();
$orderManager = new OrderManager();

$user = $userManager->getUserById($userId);
$orders = $orderManager->getOrdersByUserId($userId);

if (!$user) {
    echo '<div class="alert alert-danger">User tidak ditemukan.</div>';
    exit;
}
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h6 class="fw-bold text-uppercase text-muted small mb-3">Informasi Pengguna</h6>
        <table class="table table-borderless table-sm">
            <tr>
                <td style="width: 150px;" class="text-muted">Username</td>
                <td class="fw-medium">: <?php echo htmlspecialchars($user['username']); ?></td>
            </tr>
            <tr>
                <td class="text-muted">Nama Lengkap</td>
                <td class="fw-medium">: <?php echo htmlspecialchars($user['full_name'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="text-muted">Email</td>
                <td class="fw-medium">: <?php echo htmlspecialchars($user['email'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="text-muted">No. Telepon</td>
                <td class="fw-medium">: <?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="text-muted">Alamat</td>
                <td class="fw-medium">: <?php echo htmlspecialchars($user['address'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="text-muted">Status</td>
                <td>
                    : 
                    <?php if (isset($user['is_active']) && $user['is_active'] == 0): ?>
                        <span class="badge bg-danger">Banned</span>
                    <?php else: ?>
                        <span class="badge bg-success">Active</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<h6 class="fw-bold text-uppercase text-muted small mb-3">Riwayat Pesanan</h6>
<div class="table-responsive">
    <table class="table table-hover table-sm align-middle">
        <thead class="table-light">
            <tr>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">Belum ada riwayat pesanan.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <?php
                        $displayNo = !empty($order['nomor_pesanan'] ?? null)
                            ? $order['nomor_pesanan']
                            : '#' . $order['id'];
                            
                        $statusClass = 'bg-secondary';
                        if ($order['status'] === 'Pending') $statusClass = 'bg-warning text-dark';
                        elseif ($order['status'] === 'Selesai') $statusClass = 'bg-success';
                        elseif ($order['status'] === 'Dibatalkan') $statusClass = 'bg-danger';
                        elseif ($order['status'] === 'Menunggu Konfirmasi') $statusClass = 'bg-info text-dark';
                    ?>
                    <tr>
                        <td><span class="font-monospace small"><?php echo htmlspecialchars($displayNo); ?></span></td>
                        <td><?php echo date('d M Y', strtotime($order['tanggal_pesanan'])); ?></td>
                        <td>Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></td>
                        <td><span class="badge <?php echo $statusClass; ?> rounded-pill"><?php echo htmlspecialchars($order['status']); ?></span></td>
                        <td class="text-end">
                            <button class="btn btn-xs btn-outline-primary view-details-btn" data-order-id="<?php echo $order['id']; ?>" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
