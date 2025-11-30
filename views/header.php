<?php
// views/header.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/CartManager.php'; // Path relatif dari views/ ke models/

$cartManager = new CartManager();
$cartItemCount = $cartManager->getTotalItemCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Parfum</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom-footer-style fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <p>ParfumMy</p>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="stores.php">Toko</a></li>
                <li class="nav-item"><a class="nav-link" href="company.php">Perusahaan</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Kontak</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a href="cart.php" class="nav-link position-relative">
                            <i class="bi bi-cart-fill"></i>
                            <?php if ($cartItemCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo $cartItemCount; ?>
                                    <span class="visually-hidden">items in cart</span>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <li><a class="dropdown-item" href="admin/dashboard.php">Dashboard Admin</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="profile.php">Profil Saya</a></li>
                                <li><a class="dropdown-item" href="orders.php">Riwayat Pesanan</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="login.php" class="btn btn-outline-light">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
