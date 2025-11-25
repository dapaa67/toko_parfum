<?php
require_once 'models/OrderManager.php';
session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['payment_proof'], $_POST['order_id'])) {
    $orderId = $_POST['order_id'];
    $orderManager = new OrderManager();
    $order = $orderManager->getOrderById($orderId);

    // Validasi kepemilikan order
    if (!$order || $order['user_id'] != $_SESSION['user_id']) {
        header('Location: orders.php?error=' . urlencode('Pesanan tidak ditemukan atau akses ditolak.'));
        exit();
    }

    $file = $_FILES['payment_proof'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    if (!in_array($file['type'], $allowedTypes)) {
        header('Location: orders.php?error=' . urlencode('Hanya file JPG, JPEG, dan PNG yang diperbolehkan.'));
        exit();
    } elseif ($file['size'] > $maxSize) {
        header('Location: orders.php?error=' . urlencode('Ukuran file maksimal 2MB.'));
        exit();
    } else {
        $uploadDir = 'img/payment_proofs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            if ($orderManager->updatePaymentProof($orderId, $targetPath)) {
                header('Location: orders.php?success=' . urlencode('Bukti pembayaran berhasil diupload! Mohon tunggu konfirmasi admin.'));
                exit();
            } else {
                header('Location: orders.php?error=' . urlencode('Gagal menyimpan data ke database.'));
                exit();
            }
        } else {
            header('Location: orders.php?error=' . urlencode('Gagal mengupload file.'));
            exit();
        }
    }
} else {
    header('Location: orders.php');
    exit();
}
?>
