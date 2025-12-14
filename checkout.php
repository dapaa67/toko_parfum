<?php
require_once 'models/CartManager.php';
require_once 'models/UserManager.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$cartManager = new CartManager();
$cartItems = $cartManager->getItems();

if (empty($cartItems)) {
    header('Location: cart.php');
    exit();
}

// Get user profile data
$userManager = new UserManager();
$user = $userManager->getUserById($_SESSION['user_id']);

$totalPrice = $cartManager->getTotalPrice();

require_once 'views/header.php';
?>

<div style="min-height: 80vh; background: linear-gradient(to bottom, #FDFBF7, white); padding: 3rem 1rem;">
    <div style="max-width: 72rem; margin: 0 auto;">
        
        <!-- Page Title -->
        <h1 style="font-size: 2rem; font-weight: 600; color: #1A1A1A; margin-bottom: 2rem; font-family: Georgia, serif;">
            Checkout
        </h1>

        <?php if (isset($_SESSION['error_message'])): ?>
            <?php
                $rawError = $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                $friendlyError = $rawError;
                if (strpos($rawError, 'Lock wait timeout exceeded') !== false) {
                    $friendlyError = 'Server sedang sibuk saat memproses pesanan. Silakan klik "Buat Pesanan" lagi dalam beberapa detik.';
                }
            ?>
            <div style="background-color: #FEE2E2; border-left: 4px solid #DC2626; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <p style="margin: 0; color: #991B1B; font-size: 0.875rem;">
                    <i class="bi bi-exclamation-triangle-fill" style="margin-right: 0.5rem;"></i>
                    <?php echo htmlspecialchars($friendlyError, ENT_QUOTES, 'UTF-8'); ?>
                </p>
            </div>
        <?php endif; ?>

        <form action="place_order.php" method="POST" id="checkout-form">
            
            <!-- 1. Alamat Pengiriman Section -->
            <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-bottom: 1.5rem; border: 1px solid #F3F4F6;">
                <h2 style="font-size: 1.25rem; font-weight: 600; color: #1A1A1A; margin-bottom: 1.25rem; font-family: Georgia, serif; display: flex; align-items: center;">
                    <i class="bi bi-geo-alt-fill" style="color: #D4AF37; margin-right: 0.5rem;"></i>
                    Alamat Pengiriman
                </h2>

                <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                    <!-- Full Name -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <div>
                            <label for="nama_penerima" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                                Nama Penerima <span style="color: #DC2626;">*</span>
                            </label>
                            <input type="text" id="nama_penerima" name="nama_penerima" 
                                   value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>"
                                   style="width: 100%; padding: 0.625rem 0.75rem; border: 1.5px solid #E5E7EB; border-radius: 0.5rem; font-size: 0.9375rem; transition: all 0.2s;" 
                                   required
                                   onfocus="this.style.borderColor='#D4AF37'; this.style.boxShadow='0 0 0 3px rgba(212, 175, 55, 0.1)';"
                                   onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';">
                        </div>

                        <div>
                            <label for="telepon" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                                Nomor Telepon <span style="color: #DC2626;">*</span>
                            </label>
                            <input type="tel" id="telepon" name="telepon" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                   placeholder="08XXXXXXXXXX"
                                   style="width: 100%; padding: 0.625rem 0.75rem; border: 1.5px solid #E5E7EB; border-radius: 0.5rem; font-size: 0.9375rem; transition: all 0.2s;" 
                                   required
                                   onfocus="this.style.borderColor='#D4AF37'; this.style.boxShadow='0 0 0 3px rgba(212, 175, 55, 0.1)';"
                                   onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';">
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="alamat_pengiriman" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                            Alamat Lengkap <span style="color: #DC2626;">*</span>
                        </label>
                        <textarea id="alamat_pengiriman" name="alamat_pengiriman" rows="3" 
                                  style="width: 100%; padding: 0.625rem 0.75rem; border: 1.5px solid #E5E7EB; border-radius: 0.5rem; font-size: 0.9375rem; resize: vertical; transition: all 0.2s;" 
                                  required
                                  onfocus="this.style.borderColor='#D4AF37'; this.style.boxShadow='0 0 0 3px rgba(212, 175, 55, 0.1)';"
                                  onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- 2 & 3. Detail Barang + Metode Pembayaran -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    
                    <!-- 2. Detail Barang Belanjaan -->
                    <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #F3F4F6;">
                        <h2 style="font-size: 1.25rem; font-weight: 600; color: #1A1A1A; margin-bottom: 1.25rem; font-family: Georgia, serif; display: flex; align-items: center; justify-content: space-between;">
                            <span style="display: flex; align-items: center;">
                                <i class="bi bi-bag-fill" style="color: #D4AF37; margin-right: 0.5rem;"></i>
                                Ringkasan Belanja
                            </span>
                            <span style="background-color: #D4AF37; color: #1A1A1A; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.75rem; border-radius: 9999px;">
                                <?php echo $cartManager->getTotalItemCount(); ?> Item
                            </span>
                        </h2>

                        <div style="max-height: 20rem; overflow-y: auto; margin-bottom: 1rem;">
                            <?php foreach ($cartItems as $item): ?>
                                <div style="display: flex; gap: 0.75rem; padding: 0.75rem; border-bottom: 1px solid #F3F4F6; align-items: center;">
                                    <?php
                                    $imgSrc = $item['image_path'] ?? 'img/parfum_placeholder.png';
                                    if (!file_exists($imgSrc)) {
                                        $imgSrc = 'img/parfum_placeholder.png';
                                    }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($item['nama']); ?>" 
                                         style="width: 3rem; height: 3rem; object-fit: contain; border-radius: 0.375rem; background: #FDFBF7; padding: 0.25rem;">
                                    <div style="flex: 1; min-width: 0;">
                                        <p style="font-weight: 500; font-size: 0.875rem; color: #1A1A1A; margin: 0 0 0.25rem 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            <?php echo htmlspecialchars($item['nama']); ?>
                                        </p>
                                        <p style="font-size: 0.75rem; color: #6B7280; margin: 0;">
                                            Qty: <?php echo $item['quantity']; ?> × Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?>
                                        </p>
                                    </div>
                                    <p style="font-weight: 600; font-size: 0.875rem; color: #1A1A1A; margin: 0; white-space: nowrap;">
                                        Rp <?php echo number_format($item['harga'] * $item['quantity'], 0, ',', '.'); ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- 3. Metode Pembayaran & Total -->
                    <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #F3F4F6; height: fit-content;">
                        <h2 style="font-size: 1.25rem; font-weight: 600; color: #1A1A1A; margin-bottom: 1.25rem; font-family: Georgia, serif; display: flex; align-items: center;">
                            <i class="bi bi-credit-card-fill" style="color: #D4AF37; margin-right: 0.5rem;"></i>
                            Pembayaran
                        </h2>

                        <!-- Payment Methods -->
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: center; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 0.5rem; cursor: pointer; margin-bottom: 0.75rem; transition: all 0.2s;"
                                   onclick="this.style.borderColor='#D4AF37'; this.style.backgroundColor='#FEF3C7';"
                                   onmouseover="if(!this.querySelector('input').checked) this.style.borderColor='#D4AF37';"
                                   onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='#E5E7EB';">
                                <input type="radio" id="cod" name="metode_pembayaran" value="COD" 
                                       style="width: 1.125rem; height: 1.125rem; margin-right: 0.75rem; accent-color: #D4AF37;" 
                                       required checked
                                       onchange="document.querySelectorAll('label').forEach(l => {l.style.borderColor='#E5E7EB'; l.style.backgroundColor='white';}); this.parentElement.style.borderColor='#D4AF37'; this.parentElement.style.backgroundColor='#FEF3C7';">
                                <div style="flex: 1;">
                                    <span style="font-weight: 500; font-size: 0.9375rem; color: #1A1A1A; display: block;">Cash on Delivery (COD)</span>
                                    <span style="font-size: 0.75rem; color: #6B7280;">Bayar tunai saat barang diterima</span>
                                </div>
                                <i class="bi bi-cash-coin" style="font-size: 1.25rem; color: #D4AF37;"></i>
                            </label>

                            <label style="display: flex; align-items: center; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;"
                                   onclick="this.style.borderColor='#D4AF37'; this.style.backgroundColor='#FEF3C7';"
                                   onmouseover="if(!this.querySelector('input').checked) this.style.borderColor='#D4AF37';"
                                   onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='#E5E7EB';">
                                <input type="radio" id="transfer" name="metode_pembayaran" value="Bank Transfer" 
                                       style="width: 1.125rem; height: 1.125rem; margin-right: 0.75rem; accent-color: #D4AF37;" 
                                       required
                                       onchange="document.querySelectorAll('label').forEach(l => {l.style.borderColor='#E5E7EB'; l.style.backgroundColor='white';}); this.parentElement.style.borderColor='#D4AF37'; this.parentElement.style.backgroundColor='#FEF3C7';">
                                <div style="flex: 1;">
                                    <span style="font-weight: 500; font-size: 0.9375rem; color: #1A1A1A; display: block;">Bank Transfer</span>
                                    <span style="font-size: 0.75rem; color: #6B7280;">Transfer ke rekening toko</span>
                                </div>
                                <i class="bi bi-bank" style="font-size: 1.25rem; color: #D4AF37;"></i>
                            </label>
                        </div>

                        <!-- Total -->
                        <div style="border-top: 2px solid #F3F4F6; padding-top: 1rem; margin-bottom: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span style="color: #6B7280; font-size: 0.9375rem;">Subtotal</span>
                                <span style="color: #1A1A1A; font-size: 0.9375rem;">Rp <?php echo number_format($totalPrice, 0, ',', '.'); ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <span style="color: #6B7280; font-size: 0.9375rem;">Ongkir</span>
                                <span style="color: #22C55E; font-size: 0.9375rem; font-weight: 500;">GRATIS</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 2px solid #F3F4F6;">
                                <span style="font-weight: 600; font-size: 1.125rem; color: #1A1A1A;">Total</span>
                                <span style="font-weight: 700; font-size: 1.5rem; color: #D4AF37;">Rp <?php echo number_format($totalPrice, 0, ',', '.'); ?></span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submit-btn"
                                style="width: 100%; background-color: #D4AF37; color: #1A1A1A; font-weight: 600; padding: 0.875rem 1.5rem; border-radius: 0.5rem; border: none; cursor: pointer; transition: all 0.2s; font-size: 1rem; display: flex; align-items: center; justify-content: center;"
                                onmouseover="this.style.backgroundColor='#B5952F'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';"
                                onmouseout="this.style.backgroundColor='#D4AF37'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <i class="bi bi-check-circle-fill" style="margin-right: 0.5rem;"></i> Buat Pesanan
                        </button>
                    </div>

                </div>
            </div>

        </form>

    </div>
</div>

<script>
//Form submission handler
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submit-btn');
    if (!submitBtn.disabled) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split" style="margin-right: 0.5rem;"></i> Memproses pesanan...';
    }
});

// Pre-select COD and apply styling
window.addEventListener('DOMContentLoaded', function() {
    const codRadio = document.getElementById('cod');
    if (codRadio && codRadio.checked) {
        const label = codRadio.parentElement;
        label.style.borderColor = '#D4AF37';
        label.style.backgroundColor = '#FEF3C7';
    }
});
</script>

<?php require_once 'views/footer.php'; ?>
