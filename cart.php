<?php
require_once 'models/CartManager.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$cartManager = new CartManager();
$cartItems = $cartManager->getItems();
$totalPrice = $cartManager->getTotalPrice();

require_once 'views/header.php';
?>

<div class="container mx-auto px-4 my-20">
    <h2 class="text-4xl font-bold mb-8 text-gold">Keranjang Belanja Anda</h2>

    <?php if (empty($cartItems)): ?>
        <div class="bg-blue-50 border border-blue-200 text-blue-800 px-6 py-8 rounded-lg text-center">
            <p class="text-lg mb-4">Keranjang belanja Anda masih kosong.</p>
            <a href="products.php" 
               style="display: inline-block; background-color: #D4AF37; color: #1A1A1A; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; transition: all 0.2s;"
               onmouseover="this.style.backgroundColor='#B5952F';" 
               onmouseout="this.style.backgroundColor='#D4AF37';">
                <i class="bi bi-cart-plus" style="margin-right: 0.5rem;"></i> Mulai Belanja
            </a>
        </div>
    <?php else: ?>
        <div class="flex flex-wrap -mx-4">
            <!-- Cart Items -->
            <div class="w-full lg:w-2/3 px-4 mb-8 lg:mb-0">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="flex flex-wrap items-center mb-6 pb-6 border-b border-gray-200 last:border-0">
                            <div class="w-full md:w-1/6 mb-4 md:mb-0">
                                <img src="<?php echo htmlspecialchars($item['image_path']); ?>" class="w-full rounded" alt="<?php echo htmlspecialchars($item['nama']); ?>">
                            </div>
                            <div class="w-full md:w-1/3 px-4">
                                <h5 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($item['nama']); ?></h5>
                                <small class="text-gray-600">Harga: Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></small>
                            </div>
                            <div class="w-full md:w-1/4 px-4 my-4 md:my-0">
                                <form action="cart_action.php" method="POST" class="flex items-center">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                    <input type="number" name="quantity" class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-sm mr-2" value="<?php echo $item['quantity']; ?>" min="1">
                                    <button type="submit" 
                                            style="padding: 0.5rem 0.75rem; background: white; border: 1.5px solid #E5E7EB; color: #6B7280; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                                            title="Update"
                                            onmouseover="this.style.backgroundColor='#F9FAFB'; this.style.borderColor='#D1D5DB';"
                                            onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#E5E7EB';">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="w-full md:w-1/6 px-4 text-right">
                                <strong class="text-lg">Rp <?php echo number_format($item['harga'] * $item['quantity'], 0, ',', '.'); ?></strong>
                            </div>
                            <div class="w-full md:w-auto px-4 text-right mt-4 md:mt-0">
                                <form action="cart_action.php" method="POST">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                    <button type="submit" 
                                            style="padding: 0.5rem 0.75rem; background: white; border: 1.5px solid #FCA5A5; color: #DC2626; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                                            title="Hapus"
                                            onmouseover="this.style.backgroundColor='#FEE2E2';"
                                            onmouseout="this.style.backgroundColor='white';">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-1/3 px-4">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h4 class="text-2xl font-bold mb-6">Ringkasan Pesanan</h4>
                    <hr class="mb-6 border-gray-200">
                    <div class="flex justify-between mb-4">
                        <span class="text-gray-600">Subtotal</span>
                        <strong>Rp <?php echo number_format($totalPrice, 0, ',', '.'); ?></strong>
                    </div>
                    <div class="flex justify-between mb-4">
                        <span class="text-gray-600">Biaya Pengiriman</span>
                        <strong>Akan dihitung</strong>
                    </div>
                    <hr class="my-6 border-gray-200">
                    <div class="flex justify-between font-bold text-xl mb-6">
                        <span>Total</span>
                        <span>Rp <?php echo number_format($totalPrice, 0, ',', '.'); ?></span>
                    </div>
                    <a href="checkout.php" 
                       style="display: block; width: 100%; background-color: #D4AF37; color: #1A1A1A; text-align: center; font-weight: 600; padding: 1rem 1.5rem; border-radius: 0.5rem; text-decoration: none; transition: all 0.2s; font-size: 1.0625rem; margin-bottom: 1rem;"
                       onmouseover="this.style.backgroundColor='#B5952F'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';"
                       onmouseout="this.style.backgroundColor='#D4AF37'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <i class="bi bi-check-circle-fill" style="margin-right: 0.5rem;"></i> Lanjut ke Checkout
                    </a>
                    <div class="text-center">
                        <form action="cart_action.php" method="POST" onsubmit="return confirm('Anda yakin ingin mengosongkan keranjang?');">
                            <input type="hidden" name="action" value="clear">
                            <button type="submit" 
                                    style="background: none; border: none; color: #DC2626; font-weight: 500; cursor: pointer; transition: color 0.2s; font-size: 0.875rem; text-decoration: underline;"
                                    onmouseover="this.style.color='#991B1B';"
                                    onmouseout="this.style.color='#DC2626';">
                                <i class="bi bi-trash3" style="margin-right: 0.25rem;"></i> Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/footer.php'; ?>
