<?php
require_once 'models/CartManager.php';
require_once 'models/ParfumManager.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hanya user yang sudah login yang bisa menambah ke keranjang
if (!isset($_SESSION['user_id'])) {
    // Redirect ke halaman login jika belum login
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $productId = $_POST['product_id'] ?? 0;
    $quantity = $_POST['quantity'] ?? 1;

    $cartManager = new CartManager();

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
            }
            break;

        case 'update':
            $cartManager->update($productId, $quantity);
            break;

        case 'remove':
            $cartManager->remove($productId);
            break;

        case 'clear':
            $cartManager->clear();
            break;
    }

    // Logika Redirect: Jika aksi dari halaman keranjang, kembali ke keranjang.
    if ($action === 'add') {
        $redirect_url = $_SERVER['HTTP_REFERER'] ?? 'products.php';
    } else {
        $redirect_url = 'cart.php';
    }
    header("Location: " . $redirect_url);
    exit();
}

// Jika diakses langsung, redirect ke halaman utama
header('Location: index.php');
exit();