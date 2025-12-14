<?php
require_once 'models/OrderManager.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$orderManager = new OrderManager();
$orders = $orderManager->getOrdersByUserId($_SESSION['user_id']);

require_once 'views/header.php';
?>

<div style="min-height: 80vh; background: linear-gradient(to bottom, #FDFBF7, white); padding: 3rem 1rem;">
    <div style="max-width: 60rem; margin: 0 auto;">
        
        <!-- Page Title -->
        <h1 style="font-size: 2rem; font-weight: 600; color: #1A1A1A; margin-bottom: 2rem; font-family: Georgia, serif; display: flex; align-items: center;">
            <i class="bi bi-clock-history" style="color: #D4AF37; margin-right: 0.75rem;"></i>
            Riwayat Pesanan Saya
        </h1>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['success'])): ?>
            <div style="background-color: #D1FAE5; border-left: 4px solid #10B981; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <p style="margin: 0; color: #065F46; font-size: 0.875rem;">
                    <i class="bi bi-check-circle-fill" style="margin-right: 0.5rem; color: #10B981;"></i>
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div style="background-color: #FEE2E2; border-left: 4px solid #DC2626; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <p style="margin: 0; color: #991B1B; font-size: 0.875rem;">
                    <i class="bi bi-exclamation-triangle-fill" style="margin-right: 0.5rem; color: #DC2626;"></i>
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Empty State -->
        <?php if (empty($orders)): ?>
            <div style="background: white; border-radius: 1rem; padding: 4rem 2rem; text-align: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #F3F4F6;">
                <div style="width: 5rem; height: 5rem; background: linear-gradient(135deg, #FEF3C7, #FDE68A); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                    <i class="bi bi-bag-x" style="font-size: 2rem; color: #D4AF37;"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #1A1A1A; margin-bottom: 0.5rem;">Belum Ada Pesanan</h3>
                <p style="color: #6B7280; margin-bottom: 2rem; font-size: 0.9375rem;">Anda belum memiliki riwayat pesanan. Mulai belanja sekarang!</p>
                <a href="products.php" style="display: inline-block; background-color: #D4AF37; color: #1A1A1A; font-weight: 600; padding: 0.75rem 2rem; border-radius: 0.5rem; text-decoration: none; transition: all 0.2s;"
                   onmouseover="this.style.backgroundColor='#B5952F';" 
                   onmouseout="this.style.backgroundColor='#D4AF37';">
                    <i class="bi bi-cart-plus" style="margin-right: 0.5rem;"></i> Mulai Belanja
                </a>
            </div>
        <?php else: ?>
            
            <!-- Orders List -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <?php foreach ($orders as $order): ?>
                    <?php
                        $nomorPesanan = !empty($order['nomor_pesanan'] ?? null)
                            ? $order['nomor_pesanan']
                            : '#' . $order['id'];
                        
                        // Status styling
                        $statusColors = [
                            'Pending' => ['bg' => '#FEF3C7', 'text' => '#92400E', 'border' => '#D4AF37'],
                            'Menunggu Konfirmasi' => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'border' => '#3B82F6'],
                            'Diproses' => ['bg' => '#E0E7FF', 'text' => '#3730A3', 'border' => '#6366F1'],
                            'Dikirim' => ['bg' => '#DDD6FE', 'text' => '#5B21B6', 'border' => '#8B5CF6'],
                            'Selesai' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'border' => '#10B981'],
                            'Dibatalkan' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'border' => '#DC2626']
                        ];
                        
                        $statusStyle = $statusColors[$order['status']] ?? ['bg' => '#F3F4F6', 'text' => '#1F2937', 'border' => '#9CA3AF'];
                    ?>
                    
                    <details style="background: white; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #F3F4F6; overflow: hidden; transition: all 0.2s;">
                        <summary style="padding: 1.25rem 1.5rem; cursor: pointer; list-style: none; display: flex; justify-content: space-between; align-items: center; transition: all 0.2s;"
                                 onmouseover="this.style.backgroundColor='#FDFBF7';"
                                 onmouseout="this.style.backgroundColor='white';">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 0.5rem;">
                                    <span style="font-weight: 600; font-size: 1.0625rem; color: #1A1A1A;">
                                        Pesanan <?php echo htmlspecialchars($nomorPesanan); ?>
                                    </span>
                                    <span style="background-color: <?php echo $statusStyle['bg']; ?>; color: <?php echo $statusStyle['text']; ?>; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.75rem; border-radius: 9999px; border: 1px solid <?php echo $statusStyle['border']; ?>;">
                                        <?php echo htmlspecialchars($order['status']); ?>
                                    </span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; font-size: 0.875rem; color: #6B7280;">
                                    <span>
                                        <i class="bi bi-calendar3" style="margin-right: 0.25rem;"></i>
                                        <?php echo date('d M Y', strtotime($order['tanggal_pesanan'])); ?>
                                    </span>
                                    <span>
                                        <i class="bi bi-clock" style="margin-right: 0.25rem;"></i>
                                        <?php echo date('H:i', strtotime($order['tanggal_pesanan'])); ?>
                                    </span>
                                    <span style="font-weight: 600; color: #D4AF37;">
                                        Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?>
                                    </span>
                                </div>
                            </div>
                            <i class="bi bi-chevron-down" style="font-size: 1.25rem; color: #9CA3AF; transition: transform 0.2s;"></i>
                        </summary>

                        <!-- Order Details (Expanded) -->
                        <div style="border-top: 1px solid #F3F4F6; padding: 1.5rem;">
                            
                            <!-- Info Grid -->
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                                
                                <!-- Shipping Address -->
                                <div style="background: #FDFBF7; padding: 1rem; border-radius: 0.5rem; border: 1px solid #F3F4F6;">
                                    <h4 style="font-size: 0.875rem; font-weight: 600; color: #6B7280; margin-bottom: 0.75rem; display: flex; align-items: center;">
                                        <i class="bi bi-geo-alt-fill" style="color: #D4AF37; margin-right: 0.375rem;"></i>
                                        Alamat Pengiriman
                                    </h4>
                                    <p style="margin: 0 0 0.25rem 0; font-weight: 500; color: #1A1A1A; font-size: 0.9375rem;"><?php echo htmlspecialchars($order['nama_penerima']); ?></p>
                                    <p style="margin: 0 0 0.25rem 0; color: #6B7280; font-size: 0.875rem;"><?php echo htmlspecialchars($order['telepon']); ?></p>
                                    <p style="margin: 0; color: #6B7280; font-size: 0.875rem; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($order['alamat_pengiriman'])); ?></p>
                                </div>

                                <!-- Payment Info -->
                                <div style="background: #FDFBF7; padding: 1rem; border-radius: 0.5rem; border: 1px solid #F3F4F6;">
                                    <h4 style="font-size: 0.875rem; font-weight: 600; color: #6B7280; margin-bottom: 0.75rem; display: flex; align-items: center;">
                                        <i class="bi bi-credit-card-fill" style="color: #D4AF37; margin-right: 0.375rem;"></i>
                                        Pembayaran
                                    </h4>
                                    <p style="margin: 0 0 0.5rem 0; color: #6B7280; font-size: 0.875rem;">
                                        Metode: <strong style="color: #1A1A1A;"><?php echo htmlspecialchars($order['metode_pembayaran']); ?></strong>
                                    </p>
                                    <p style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #D4AF37;">
                                        Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?>
                                    </p>
                                    
                                    <?php if ($order['status'] === 'Pending' && $order['metode_pembayaran'] === 'Bank Transfer'): ?>
                                        <button type="button" 
                                                data-order-id="<?php echo $order['id']; ?>"
                                                class="upload-payment-btn"
                                                style="margin-top: 0.75rem; width: 100%; background-color: #D4AF37; color: #1A1A1A; font-weight: 500; padding: 0.5rem 1rem; border-radius: 0.375rem; border: none; cursor: pointer; font-size: 0.875rem; transition: all 0.2s;"
                                                onmouseover="this.style.backgroundColor='#B5952F';"
                                                onmouseout="this.style.backgroundColor='#D4AF37';">
                                            <i class="bi bi-upload"></i> Konfirmasi Pembayaran
                                        </button>
                                    <?php elseif ($order['status'] === 'Menunggu Konfirmasi'): ?>
                                        <div style="margin-top: 0.75rem; padding: 0.5rem; background: #DBEAFE; border-radius: 0.375rem; text-align: center;">
                                            <span style="color: #1E40AF; font-size:  0.75rem; font-weight: 500;">
                                                <i class="bi bi-clock-history"></i> Menunggu Konfirmasi Admin
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div>
                                <h4 style="font-size: 0.875rem; font-weight: 600; color: #6B7280; margin-bottom: 1rem; display: flex; align-items: center;">
                                    <i class="bi bi-bag-check-fill" style="color: #D4AF37; margin-right: 0.375rem;"></i>
                                    Item Pesanan
                                </h4>
                                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                    <?php
                                    $items = $orderManager->getOrderItems($order['id']);
                                    foreach ($items as $item):
                                    ?>
                                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #FDFBF7; border-radius: 0.5rem; border: 1px solid #F3F4F6;">
                                            <img src="<?php echo htmlspecialchars($item['image_path']); ?>" 
                                                 alt="<?php echo htmlspecialchars($item['nama']); ?>" 
                                                 style="width: 3.5rem; height: 3.5rem; object-fit: contain; border-radius: 0.375rem; background: white; padding: 0.25rem;">
                                            <div style="flex: 1; min-width: 0;">
                                                <p style="margin: 0 0 0.25rem 0; font-weight: 500; font-size: 0.9375rem; color: #1A1A1A;"><?php echo htmlspecialchars($item['nama']); ?></p>
                                                <p style="margin: 0; font-size: 0.8125rem; color: #6B7280;">
                                                    <?php echo $item['jumlah']; ?> × Rp <?php echo number_format($item['harga_saat_beli'], 0, ',', '.'); ?>
                                                </p>
                                            </div>
                                            <p style="margin: 0; font-weight: 600; font-size: 0.9375rem; color: #1A1A1A; white-space: nowrap;">
                                                Rp <?php echo number_format($item['harga_saat_beli'] * $item['jumlah'], 0, ',', '.'); ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </div>
                    </details>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>
</div>

<style>
/* Rotate chevron when details is open */
details[open] summary i.bi-chevron-down {
    transform: rotate(180deg);
}

/* Smooth transition for details */
details summary::-webkit-details-marker {
    display: none;
}

/* Modal animations */
@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes modalSlideUp {
    from { 
        opacity: 0;
        transform: translateY(20px); 
    }
    to { 
        opacity: 1;
        transform: translateY(0); 
    }
}
</style>

<!-- Payment Proof Upload Modal -->
<div id="payment-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; align-items: center; justify-content: center; animation: modalFadeIn 0.2s ease-out;">
    <!-- Backdrop -->
    <div id="payment-modal-backdrop" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(2px);" onclick="closePaymentModal()"></div>
    
    <!-- Modal Content -->
    <div id="payment-modal-content" style="position: relative; background: white; border-radius: 1rem; padding: 1.5rem; max-width: 28rem; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); animation: modalSlideUp 0.3s ease-out;">
        
        <!-- Close Button -->
        <button onclick="closePaymentModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; cursor: pointer; color: #9CA3AF; font-size: 1.125rem; transition: color 0.2s; line-height: 1;">
            <i class="bi bi-x-lg"></i>
        </button>
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="width: 3.5rem; height: 3.5rem; background: linear-gradient(135deg, #D4AF37, #FFD77A); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                <i class="bi bi-upload" style="font-size: 1.5rem; color: white;"></i>
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1A1A1A; margin: 0;">Upload Bukti Pembayaran</h3>
        </div>

        <!-- Bank Account Info -->
        <div style="background-color: #FEF3C7; border-left: 4px solid #D4AF37; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <p style="margin: 0 0 0.75rem 0; color: #78350F; font-size: 0.875rem; font-weight: 600;">
                <i class="bi bi-info-circle-fill" style="margin-right: 0.375rem; color: #D4AF37;"></i>
                Silakan transfer ke rekening berikut:
            </p>
            
            <div style="background: white; border-radius: 0.375rem; padding: 0.75rem; margin-bottom: 0.75rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.75rem; color: #78350F;">Bank</span>
                    <strong style="color: #1A1A1A; font-size: 0.875rem;">Bank Mandiri</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.75rem; color: #78350F;">No. Rekening</span>
                    <strong style="color: #1A1A1A; font-size: 0.875rem; font-family: 'Courier New', monospace;">1234-5678-9012-3456</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="font-size: 0.75rem; color: #78350F;">Atas Nama</span>
                    <strong style="color: #1A1A1A; font-size: 0.875rem;">PT ParfumMY Indonesia</strong>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <form id="upload-payment-form">
            <input type="hidden" id="modal-order-id" name="order_id">
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Pilih File Bukti Transfer <span style="color: #DC2626;">*</span>
                </label>
                <input type="file" id="payment-proof-input" name="payment_proof" accept="image/*" required
                       style="width: 100%; padding: 0.5rem; border: 2px dashed #D1D5DB; border-radius: 0.5rem; font-size: 0.875rem; cursor: pointer;"
                       onchange="previewImage(this)">
                <p style="margin: 0.375rem 0 0 0; font-size: 0.75rem; color: #6B7280;">Format: JPG, JPEG, PNG (Max 5MB)</p>
            </div>

            <!-- Image Preview -->
            <div id="image-preview-container" style="display: none; margin-bottom: 1rem;">
                <p style="font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Preview:</p>
                <img id="image-preview" src="" alt="Preview" style="width: 100%; border-radius: 0.5rem; border: 1px solid #E5E7EB;">
            </div>

            <!-- Error Message -->
            <div id="upload-error" style="display: none; background-color: #FEE2E2; border-left: 4px solid #DC2626; padding: 0.75rem; border-radius: 0.375rem; margin-bottom: 1rem;">
                <p id="upload-error-message" style="margin: 0; color: #991B1B; font-size: 0.875rem;"></p>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="upload-submit-btn"
                    style="width: 100%; background-color: #D4AF37; color: #1A1A1A; font-weight: 600; padding: 0.75rem; border-radius: 0.5rem; border: none; cursor: pointer; transition: all 0.2s; font-size: 0.9375rem;"
                    onmouseover="this.style.backgroundColor='#B5952F';"
                    onmouseout="this.style.backgroundColor='#D4AF37';">
                <i class="bi bi-upload"></i> Upload Bukti Pembayaran
            </button>
        </form>

    </div>
</div>

<script>
let currentOrderId = null;

// Open modal
document.querySelectorAll('.upload-payment-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        currentOrderId = this.getAttribute('data-order-id');
        document.getElementById('modal-order-id').value = currentOrderId;
        document.getElementById('payment-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });
});

// Close modal
function closePaymentModal() {
    document.getElementById('payment-modal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('upload-payment-form').reset();
    document.getElementById('image-preview-container').style.display = 'none';
    document.getElementById('upload-error').style.display = 'none';
    currentOrderId = null;
}

// ESC key to close
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('payment-modal').style.display === 'flex') {
        closePaymentModal();
    }
});

// Preview image
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const container = document.getElementById('image-preview-container');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        container.style.display = 'none';
    }
}

// Form submission
document.getElementById('upload-payment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('upload-submit-btn');
    const errorDiv = document.getElementById('upload-error');
    const errorMsg = document.getElementById('upload-error-message');
    const fileInput = document.getElementById('payment-proof-input');
    
    // Hide previous errors
    errorDiv.style.display = 'none';
    
    // Validate file
    if (!fileInput.files || !fileInput.files[0]) {
        errorMsg.textContent = 'Silakan pilih file bukti pembayaran';
        errorDiv.style.display = 'block';
        return;
    }
    
    const file = fileInput.files[0];
    const maxSize = 5 * 1024 * 1024; // 5MB
    
    if (file.size > maxSize) {
        errorMsg.textContent = 'Ukuran file terlalu besar. Maksimal 5MB';
        errorDiv.style.display = 'block';
        return;
    }
    
    if (!file.type.match('image.*')) {
        errorMsg.textContent = 'Format file tidak valid. Hanya JPG, JPEG, dan PNG';
        errorDiv.style.display = 'block';
        return;
    }
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengupload...';
    
    // Create FormData
    const formData = new FormData(this);
    
    // Send AJAX request
    fetch('upload_payment_proof.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            closePaymentModal();
            
            // Reload page with success message
            window.location.href = 'orders.php?success=' + encodeURIComponent(data.message);
        } else {
            errorMsg.textContent = data.message || 'Terjadi kesalahan saat upload';
            errorDiv.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-upload"></i> Upload Bukti Pembayaran';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorMsg.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
        errorDiv.style.display = 'block';
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-upload"></i> Upload Bukti Pembayaran';
    });
});
</script>

<?php require_once 'views/footer.php'; ?>
