<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'models/OrderManager.php';

// Keamanan: Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// 1. Ambil order_id dari URL dan pastikan aman (hanya angka)
$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($orderId <= 0) {
    // Jika order_id tidak valid, arahkan ke halaman riwayat pesanan
    header('Location: orders.php');
    exit();
}

// 2. Ambil data pesanan dari database
$orderManager = new OrderManager();
$order = $orderManager->getOrderById($orderId);

// Keamanan: Pastikan pesanan ada dan milik user yang sedang login
if (!$order || $order['user_id'] != $_SESSION['user_id']) {
    // Jika tidak, jangan tampilkan apa-apa dan arahkan pergi
    header('Location: orders.php');
    exit();
}

require_once 'views/header.php';
?>

<div style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; background: linear-gradient(to bottom, #FDFBF7, white);">
    <div style="max-width: 36rem; width: 100%; text-align: center;">
        
        <!-- Success Icon with Animation -->
        <div style="margin-bottom: 1.5rem; animation: successBounce 0.6s ease-out;">
            <div style="width: 5rem; height: 5rem; background: linear-gradient(135deg, #D4AF37, #FFD77A); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);">
                <i class="bi bi-check-lg" style="font-size: 2.5rem; color: white; font-weight: bold;"></i>
            </div>
        </div>

        <!-- Title -->
        <h1 style="font-size: 2rem; font-weight: 600; color: #1A1A1A; margin-bottom: 0.5rem; font-family: Georgia, serif;">
            Pesanan Berhasil!
        </h1>

        <?php
            // Tampilkan nomor_pesanan jika tersedia, jika tidak fallback ke #ID
            $nomorPesanan = !empty($order['nomor_pesanan'] ?? null)
                ? $order['nomor_pesanan']
                : '#' . $order['id'];
        ?>
        
        <!-- Subtitle -->
        <p style="font-size: 1rem; color: #6B7280; margin-bottom: 2rem;">
            Terima kasih telah berbelanja. Pesanan Anda dengan nomor <strong style="color: #D4AF37;"><?php echo htmlspecialchars($nomorPesanan); ?></strong> telah kami terima.
        </p>

        <!-- Order Details Card -->
        <div style="background: white; border-radius: 1rem; padding: 1.75rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); margin-bottom: 2rem; text-align: left; border: 1px solid #F3F4F6;">
            
            <h2 style="font-size: 1.125rem; font-weight: 600; color: #1A1A1A; margin-bottom: 1.25rem; text-align: center; font-family: Georgia, serif;">
                Detail Pesanan
            </h2>

            <!-- Order Info -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #F3F4F6;">
                <span style="color: #6B7280; font-size: 0.9375rem;">Nomor Pesanan</span>
                <strong style="color: #1A1A1A; font-size: 0.9375rem;"><?php echo htmlspecialchars($nomorPesanan); ?></strong>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #F3F4F6;">
                <span style="color: #6B7280; font-size: 0.9375rem;">Metode Pembayaran</span>
                <strong style="color: #1A1A1A; font-size: 0.9375rem;"><?php echo htmlspecialchars($order['metode_pembayaran']); ?></strong>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0;">
                <span style="color: #6B7280; font-size: 1rem; font-weight: 500;">Total Pembayaran</span>
                <strong style="color: #D4AF37; font-size: 1.5rem; font-weight: 600;">
                    Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?>
                </strong>
            </div>

            <?php if ($order['metode_pembayaran'] == 'Bank Transfer'): ?>
                <!-- Payment Instructions -->
                <div style="background-color: #FEF3C7; border-left: 4px solid #D4AF37; padding: 1.25rem; border-radius: 0.5rem; margin-top: 1rem;">
                    <p style="margin: 0 0 1rem 0; color: #78350F; font-size: 0.875rem; line-height: 1.5; font-weight: 600;">
                        <i class="bi bi-info-circle-fill" style="margin-right: 0.375rem; color: #D4AF37;"></i>
                        Silakan transfer sebesar <strong>Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></strong> ke rekening berikut:
                    </p>
                    
                    <div style="background: white; border-radius: 0.5rem; padding: 1rem; margin-top: 0.75rem;">
                        <div style="margin-bottom: 0.75rem;">
                            <p style="margin: 0; font-size: 0.75rem; color: #78350F; font-weight: 500;">Bank</p>
                            <p style="margin: 0; font-size: 0.9375rem; color: #1A1A1A; font-weight: 600;">Bank Mandiri</p>
                        </div>
                        
                        <div style="margin-bottom: 0.75rem;">
                            <p style="margin: 0; font-size: 0.75rem; color: #78350F; font-weight: 500;">Nomor Rekening</p>
                            <p style="margin: 0; font-size: 1.125rem; color: #1A1A1A; font-weight: 700; font-family: 'Courier New', monospace; letter-spacing: 1px;">1234-5678-9012-3456</p>
                        </div>
                        
                        <div>
                            <p style="margin: 0; font-size: 0.75rem; color: #78350F; font-weight: 500;">Atas Nama</p>
                            <p style="margin: 0; font-size: 0.9375rem; color: #1A1A1A; font-weight: 600;">PT ParfumMY Indonesia</p>
                        </div>
                    </div>
                    
                    <p style="margin: 0.75rem 0 0 0; color: #92400E; font-size: 0.75rem; line-height: 1.5;">
                        <i class="bi bi-clock-history" style="margin-right: 0.25rem;"></i>
                        Konfirmasi pembayaran Anda maksimal 1x24 jam setelah transfer.
                    </p>
                </div>
            <?php elseif ($order['metode_pembayaran'] == 'COD'): ?>
                <div style="background-color: #DBEAFE; border-left: 4px solid #3B82F6; padding: 1rem; border-radius: 0.5rem; margin-top: 1rem;">
                    <p style="margin: 0; color: #1E3A8A; font-size: 0.875rem; line-height: 1.5;">
                        <i class="bi bi-cash-coin" style="margin-right: 0.375rem; color: #3B82F6;"></i>
                        Siapkan uang tunai sebesar <strong>Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></strong> untuk diserahkan kepada kurir saat pesanan tiba.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="products.php" style="flex: 1; min-width: 10rem; background-color: white; color: #1A1A1A; border: 1.5px solid #E5E7EB; font-weight: 500; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; transition: all 0.2s; display: inline-block; text-align: center;"
               onmouseover="this.style.backgroundColor='#F9FAFB'; this.style.borderColor='#D1D5DB';" 
               onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#E5E7EB';">
                <i class="bi bi-arrow-left" style="margin-right: 0.375rem;"></i> Lanjut Belanja
            </a>
            <a href="orders.php" style="flex: 1; min-width: 10rem; background-color: #D4AF37; color: #1A1A1A; font-weight: 500; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; transition: all 0.2s; display: inline-block; text-align: center;"
               onmouseover="this.style.backgroundColor='#B5952F';" 
               onmouseout="this.style.backgroundColor='#D4AF37';">
                <i class="bi bi-clock-history" style="margin-right: 0.375rem;"></i> Riwayat Pesanan
            </a>
        </div>

    </div>
</div>

<style>
@keyframes successBounce {
    0% {
        transform: scale(0) rotate(-180deg);
        opacity: 0;
    }
    60% {
        transform: scale(1.1) rotate(10deg);
        opacity: 1;
    }
    100% {
        transform: scale(1) rotate(0deg);
        opacity: 1;
    }
}
</style>

<?php require_once 'views/footer.php'; ?>
