<?php
require_once 'models/CartManager.php';
require_once 'models/ParfumManager.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set header untuk JSON response
header('Content-Type: application/json');

// Hanya user yang sudah login yang bisa menambah ke keranjang
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Anda harus login terlebih dahulu',
        'cart_count' => 0
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $productId = $_POST['product_id'] ?? 0;
    $quantity = $_POST['quantity'] ?? 1;

    $cartManager = new CartManager();

    try {
        switch ($action) {
            case 'add':
                $parfumManager = new ParfumManager();
                $parfum = $parfumManager->readById($productId);
                
                if ($parfum) {
                    $productDetails = [
                        'nama' => $parfum->getNama(),
                        'harga' => $parfum->getHarga(),
                        'image_path' => $parfum->getImagePath()
                    ];
                    $cartManager->add($productId, $quantity, $productDetails);
                    
                    $cartCount = $cartManager->getTotalItemCount();
                    
                    echo json_encode([
                        'success' => true,
                        'message' => 'Produk berhasil ditambahkan ke keranjang',
                        'cart_count' => $cartCount,
                        'product_name' => $parfum->getNama()
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Produk tidak ditemukan',
                        'cart_count' => $cartManager->getTotalItemCount()
                    ]);
                }
                break;

            case 'get_count':
                // Endpoint untuk mendapatkan cart count saja
                echo json_encode([
                    'success' => true,
                    'cart_count' => $cartManager->getTotalItemCount()
                ]);
                break;

            default:
                echo json_encode([
                    'success' => false,
                    'message' => 'Aksi tidak valid',
                    'cart_count' => $cartManager->getTotalItemCount()
                ]);
                break;
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            'cart_count' => 0
        ]);
    }
    exit();
}

// Jika bukan POST request
echo json_encode([
    'success' => false,
    'message' => 'Method tidak diizinkan',
    'cart_count' => 0
]);
exit();
