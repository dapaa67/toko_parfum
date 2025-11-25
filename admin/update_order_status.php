<?php
require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');

require_once '../models/OrderManager.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: sales_report.php');
    exit();
}

$orderId = $_POST['order_id'] ?? 0;
$newStatus = $_POST['status'] ?? '';
$allowedStatuses = ['Pending', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'];

if ($orderId && in_array($newStatus, $allowedStatuses)) {
    $orderManager = new OrderManager();
    $result = $orderManager->updateOrderStatus($orderId, $newStatus);

    if ($result) {
        $_SESSION['message'] = "Status pesanan #{$orderId} berhasil diubah menjadi '{$newStatus}'.";
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = "Gagal mengubah status pesanan #{$orderId}.";
        $_SESSION['message_type'] = 'danger';
    }
} else {
    $_SESSION['message'] = 'Permintaan tidak valid.';
    $_SESSION['message_type'] = 'warning';
}

header('Location: sales_report.php');
exit();