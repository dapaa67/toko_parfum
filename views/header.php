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
    <!-- Tailwind CSS -->
    <link href="css/output.css" rel="stylesheet">
    <!-- Bootstrap Icons (keeping for icons) -->
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome (keeping for icons) -->
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <!-- Alpine.js for interactivity -->
    <script defer src="js/alpine.min.js"></script>
</head>
<body>


<nav class="bg-white/90 backdrop-blur-md fixed top-0 w-full z-50 shadow-sm border-b border-gray-100" x-data="{ mobileMenuOpen: false, userMenuOpen: false }">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo/Brand -->
            <a class="text-dark text-2xl font-bold no-underline tracking-tight flex items-center gap-2" href="index.php">
                <i class="bi bi-flower2 text-primary"></i>
                <span>ParfumMy</span>
            </a>

            <!-- Mobile menu button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-dark p-2 hover:text-primary transition">
                <i class="bi bi-list text-2xl"></i>
            </button>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-8">
                <a class="text-dark hover:text-primary font-medium transition no-underline" href="index.php">Home</a>
                <a class="text-dark hover:text-primary font-medium transition no-underline" href="products.php">Produk</a>
                <a class="text-dark hover:text-primary font-medium transition no-underline" href="stores.php">Toko</a>
                <a class="text-dark hover:text-primary font-medium transition no-underline" href="company.php">Perusahaan</a>
                <a class="text-dark hover:text-primary font-medium transition no-underline" href="contact.php">Kontak</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Cart Icon with Badge -->
                    <a href="cart.php" class="text-dark hover:text-primary transition relative no-underline">
                        <i class="bi bi-cart-fill text-lg"></i>
                        <?php if ($cartItemCount > 0): ?>
                            <span id="cart-badge" class="absolute -top-2 -right-2 bg-primary text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                <?php echo $cartItemCount; ?>
                            </span>
                        <?php else: ?>
                            <span id="cart-badge" class="absolute -top-2 -right-2 bg-primary text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center" style="display: none;">
                                0
                            </span>
                        <?php endif; ?>
                    </a>

                    <!-- User Dropdown -->
                    <div class="relative" @click.away="userMenuOpen = false">
                        <button @click="userMenuOpen = !userMenuOpen" class="text-dark hover:text-primary transition flex items-center space-x-2 font-medium">
                            <div class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-primary">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        
                        <div x-show="userMenuOpen" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-100">
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a class="block px-4 py-2 text-gray-700 hover:bg-secondary hover:text-dark no-underline transition" href="admin/dashboard.php">
                                    <i class="bi bi-speedometer2 mr-2"></i>Dashboard
                                </a>
                            <?php else: ?>
                                <a class="block px-4 py-2 text-gray-700 hover:bg-secondary hover:text-dark no-underline transition" href="profile.php">
                                    <i class="bi bi-person mr-2"></i>Profil Saya
                                </a>
                                <a class="block px-4 py-2 text-gray-700 hover:bg-secondary hover:text-dark no-underline transition" href="orders.php">
                                    <i class="bi bi-bag mr-2"></i>Pesanan
                                </a>
                            <?php endif; ?>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a class="block px-4 py-2 text-red-600 hover:bg-red-50 no-underline transition" href="logout.php">
                                <i class="bi bi-box-arrow-right mr-2"></i>Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="px-6 py-2.5 bg-primary text-dark rounded-lg hover:bg-dark hover:text-primary transition-all duration-300 no-underline font-bold shadow-sm hover:shadow-md">Login</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" 
             x-transition
             class="lg:hidden pb-4 border-t border-gray-100 mt-2">
            <a class="block py-3 text-dark hover:text-primary transition no-underline font-medium border-b border-gray-50" href="index.php">Home</a>
            <a class="block py-3 text-dark hover:text-primary transition no-underline font-medium border-b border-gray-50" href="products.php">Produk</a>
            <a class="block py-3 text-dark hover:text-primary transition no-underline font-medium border-b border-gray-50" href="stores.php">Toko</a>
            <a class="block py-3 text-dark hover:text-primary transition no-underline font-medium border-b border-gray-50" href="company.php">Perusahaan</a>
            <a class="block py-3 text-dark hover:text-primary transition no-underline font-medium border-b border-gray-50" href="contact.php">Kontak</a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="block py-3 text-dark hover:text-primary transition no-underline font-medium border-b border-gray-50" href="cart.php">
                    <i class="bi bi-cart-fill mr-2"></i> Keranjang (<?php echo $cartItemCount; ?>)
                </a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a class="block py-3 text-dark hover:text-primary transition no-underline font-medium border-b border-gray-50" href="admin/dashboard.php">Dashboard Admin</a>
                <?php else: ?>
                    <a class="block py-3 text-dark hover:text-primary transition no-underline font-medium border-b border-gray-50" href="profile.php">Profil Saya</a>
                    <a class="block py-3 text-dark hover:text-primary transition no-underline font-medium border-b border-gray-50" href="orders.php">Riwayat Pesanan</a>
                <?php endif; ?>
                <a class="block py-3 text-red-600 hover:text-red-700 transition no-underline font-medium" href="logout.php">Logout</a>
            <?php else: ?>
                <div class="pt-4">
                    <a href="login.php" class="block w-full text-center py-3 bg-primary text-dark rounded-lg hover:bg-dark hover:text-primary transition no-underline font-bold">Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

