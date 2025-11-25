<?php
require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');

require_once '../models/OrderManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'] ?? 0;
    $action = $_POST['action'] ?? '';

    if (!$orderId || !in_array($action, ['approve', 'reject', 'complete_cod'])) {
        header('Location: sales_report.php?error=Invalid request');
        exit();
    }

    $orderManager = new OrderManager();
    
    if ($action === 'approve' || $action === 'complete_cod') {
        $newStatus = 'Selesai';
    } else {
        $newStatus = 'Dibatalkan';
    }

    // Jika reject, mungkin kita ingin menghapus bukti pembayaran atau membiarkannya sebagai history?
    // Untuk saat ini kita ubah status saja.

    if ($orderManager->updateOrderStatus($orderId, $newStatus)) {
        header('Location: sales_report.php?success=Order updated');
    } else {
        header('Location: sales_report.php?error=Failed to update order');
    }
    exit();
}
?>
