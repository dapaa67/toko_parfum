<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'models/OrderManager.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Validate required inputs
if (!isset($_POST['order_id']) || !isset($_FILES['payment_proof'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Order ID dan bukti pembayaran wajib diisi']);
    exit();
}

$orderId = intval($_POST['order_id']);
$userId = $_SESSION['user_id'];

// Verify order ownership
$orderManager = new OrderManager();
$order = $orderManager->getOrderById($orderId);

if (!$order) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Pesanan tidak ditemukan']);
    exit();
}

if ($order['user_id'] != $userId) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki akses ke pesanan ini']);
    exit();
}

// Validate order status and payment method
if ($order['status'] !== 'Pending') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Pesanan ini sudah diproses atau tidak dapat dikonfirmasi']);
    exit();
}

if ($order['metode_pembayaran'] !== 'Bank Transfer') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Konfirmasi pembayaran hanya untuk metode Bank Transfer']);
    exit();
}

// Validate file upload
$file = $_FILES['payment_proof'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Error saat upload file']);
    exit();
}

// Validate file type (only images)
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Format file tidak valid. Hanya JPG, JPEG, dan PNG yang diperbolehkan']);
    exit();
}

// Validate file size (max 5MB)
$maxSize = 5 * 1024 * 1024; // 5MB in bytes
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 5MB']);
    exit();
}

// Create upload directory if not exists
$uploadDir = 'uploads/payment_proofs/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'ORDER_' . $orderId . '_' . time() . '.' . $extension;
$filePath = $uploadDir . $filename;

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $filePath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan file']);
    exit();
}

// Update database
if ($orderManager->updatePaymentProof($orderId, $filePath)) {
    echo json_encode([
        'success' => true,
        'message' => 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.',
        'file_path' => $filePath
    ]);
} else {
    // Delete uploaded file if database update fails
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data ke database']);
}
