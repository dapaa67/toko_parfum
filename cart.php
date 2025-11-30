<?php
require_once 'models/CartManager.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hanya user yang sudah login yang bisa mengakses keranjang
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$cartManager = new CartManager();
$cartItems = $cartManager->getItems();
$totalPrice = $cartManager->getTotalPrice();

require_once 'views/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4">Keranjang Belanja Anda</h2>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info text-center">
            <p class="mb-0">Keranjang belanja Anda masih kosong.</p>
            <a href="products.php" class="btn btn-primary mt-3">Mulai Belanja</a>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Daftar Item Keranjang -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="row mb-4 align-items-center">
                                <div class="col-md-2">
                                    <img src="<?php echo htmlspecialchars($item['image_path']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['nama']); ?>">
                                </div>
                                <div class="col-md-4">
                                    <h5 class="mb-0"><?php echo htmlspecialchars($item['nama']); ?></h5>
                                    <small class="text-muted">Harga: Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></small>
                                </div>
                                <div class="col-md-3">
                                    <form action="cart_action.php" method="POST" class="d-flex">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                        <input type="number" name="quantity" class="form-control form-control-sm" value="<?php echo $item['quantity']; ?>" min="1">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary ms-2" title="Update"><i class="bi bi-arrow-repeat"></i></button>
                                    </form>
                                </div>
                                <div class="col-md-2 text-end">
                                    <strong>Rp <?php echo number_format($item['harga'] * $item['quantity'], 0, ',', '.'); ?></strong>
                                </div>
                                <div class="col-md-1 text-end">
                                    <form action="cart_action.php" method="POST">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                            <?php if (next($cartItems)): ?><hr><?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pesanan -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 100px;">
                    <div class="card-body">
                        <h4 class="card-title">Ringkasan Pesanan</h4>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <strong>Rp <?php echo number_format($totalPrice, 0, ',', '.'); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Biaya Pengiriman</span>
                            <strong>Akan dihitung</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total</span>
                            <span>Rp <?php echo number_format($totalPrice, 0, ',', '.'); ?></span>
                        </div>
                        <div class="d-grid mt-4">
                            <a href="checkout.php" class="btn btn-primary btn-lg">Lanjut ke Checkout</a>
                        </div>
                        <div class="text-center mt-2">
                            <form action="cart_action.php" method="POST" onsubmit="return confirm('Anda yakin ingin mengosongkan keranjang?');">
                                <input type="hidden" name="action" value="clear">
                                <button type="submit" class="btn btn-link text-danger">Kosongkan Keranjang</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/footer.php'; ?>
