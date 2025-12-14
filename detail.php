<?php
require_once 'models/ParfumManager.php';
session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];
$parfumManager = new ParfumManager();
$parfum = $parfumManager->readById($id);

if (!$parfum) {
    echo "<p>Parfum tidak ditemukan.</p>";
    exit();
}

require_once 'views/header.php';
?>

<div class="detail-page font-sans text-dark bg-white">

    <!-- Breadcrumb (Optional, but good for UX) -->
    <div class="container mx-auto px-4 py-6">
        <a href="index.php" class="text-gray-500 hover:text-primary transition text-sm">Home</a>
        <span class="text-gray-400 mx-2">/</span>
        <a href="products.php" class="text-gray-500 hover:text-primary transition text-sm">Koleksi</a>
        <span class="text-gray-400 mx-2">/</span>
        <span class="text-primary font-medium text-sm"><?php echo htmlspecialchars($parfum->getNama()); ?></span>
    </div>

    <!-- Main Product Section -->
    <section class="pb-20 pt-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap -mx-4 items-center">
                <!-- Image Column -->
                <div class="w-full md:w-1/2 px-4 mb-12 md:mb-0">
                    <?php
                    $imgSrc = $parfum->getImagePath();
                    if (!$imgSrc || !file_exists($imgSrc)) {
                        $dirs = ['img/product/', 'img/products/'];
                        $exts = ['.png','.jpg','.jpeg','.webp'];
                        $imgSrc = null;
                        foreach ($dirs as $d) {
                            foreach ($exts as $e) {
                                $candidate = $d . $parfum->getId() . $e;
                                if (file_exists($candidate)) { $imgSrc = $candidate; break 2; }
                            }
                        }
                        if (!$imgSrc) { $imgSrc = 'img/parfum_placeholder.png'; }
                    }
                    ?>
                    <div class="bg-secondary rounded-3xl p-12 flex items-center justify-center relative overflow-hidden group">
                        <!-- Decorative Background Blob -->
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-primary/10 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition duration-700"></div>
                        
                        <img src="<?php echo htmlspecialchars($imgSrc); ?>" 
                             class="w-full max-w-sm h-auto object-contain drop-shadow-2xl transform group-hover:scale-105 transition duration-500 relative z-10" 
                             alt="<?php echo htmlspecialchars($parfum->getNama()); ?>">
                    </div>
                </div>
                
                <!-- Details Column -->
                <div class="w-full md:w-1/2 px-4 pl-8">
                    <span class="inline-block px-3 py-1 bg-secondary text-primary text-xs font-bold tracking-widest uppercase rounded-full mb-4 border border-primary/20">
                        <?php echo htmlspecialchars($parfum->getKategori()); ?>
                    </span>
                    
                    <h1 class="text-4xl md:text-5xl font-bold text-dark mb-2 font-heading leading-tight">
                        <?php echo htmlspecialchars($parfum->getNama()); ?>
                    </h1>
                    <p class="text-xl text-gray-500 mb-6 font-light"><?php echo htmlspecialchars($parfum->getMerek()); ?></p>
                    
                    <div class="flex items-end mb-8">
                        <span class="text-3xl md:text-4xl font-bold text-primary mr-2">
                            Rp <?php echo number_format($parfum->getHarga(), 0, ',', '.'); ?>
                        </span>
                        <span class="text-gray-400 text-lg mb-1">/ <?php echo htmlspecialchars($parfum->getUkuran()); ?>ml</span>
                    </div>
                    
                    <div class="prose prose-lg text-gray-600 mb-10 leading-relaxed">
                        <p><?php echo $parfum->getDeskripsi() ? nl2br(htmlspecialchars($parfum->getDeskripsi())) : 'Deskripsi produk belum tersedia saat ini.'; ?></p>
                    </div>
                    
                    <!-- Add to Cart Section -->
                    <div class="border-t pt-8" style="border-color: #e5e7eb;">
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'user'): ?>
                            <!-- Success Modal Popup -->
                            <div id="cart-success-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; align-items: center; justify-content: center;">
                                <!-- Backdrop -->
                                <div id="modal-backdrop" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(2px);"></div>
                                
                                <!-- Modal Content -->
                                <div id="modal-content" style="position: relative; background: white; border-radius: 1rem; padding: 1.25rem; max-width: 22rem; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); animation: modalSlideUp 0.25s ease-out;">
                                    <!-- Close Button -->
                                    <button id="modal-close-btn" style="position: absolute; top: 0.75rem; right: 0.75rem; background: none; border: none; cursor: pointer; color: #9CA3AF; font-size: 1.125rem; transition: color 0.2s; line-height: 1;">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    
                                    <!-- Success Icon -->
                                    <div style="text-align: center; margin-bottom: 1rem;">
                                        <div style="width: 2.5rem; height: 2.5rem; background-color: #D4AF37; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; animation: successPulse 0.5s ease-out;">
                                            <i class="bi bi-check-lg" style="font-size: 1.25rem; color: white; font-weight: bold;"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Title -->
                                    <h3 style="text-align: center; font-size: 1.125rem; font-weight: 600; color: #1A1A1A; margin-bottom: 0.75rem;">
                                        Berhasil Ditambahkan
                                    </h3>
                                    
                                    <!-- Product Info -->
                                    <div id="modal-product-info" style="background-color: #FDFBF7; border-radius: 0.5rem; padding: 0.75rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                                        <img id="modal-product-image" src="" alt="" style="width: 2.5rem; height: 2.5rem; object-fit: contain; border-radius: 0.375rem;">
                                        <div style="flex: 1; min-width: 0;">
                                            <p id="modal-product-name" style="font-weight: 500; color: #1A1A1A; margin-bottom: 0.125rem; font-size: 0.875rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></p>
                                            <p id="modal-product-quantity" style="color: #6B7280; font-size: 0.75rem; margin: 0;"></p>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button id="modal-continue-btn" style="flex: 1; background-color: white; color: #1A1A1A; border: 1.5px solid #E5E7EB; font-weight: 500; padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;">
                                            Lanjut Belanja
                                        </button>
                                        <a href="cart.php" style="flex: 1; background-color: #D4AF37; color: #1A1A1A; font-weight: 500; padding: 0.5rem 1rem; border-radius: 0.5rem; text-align: center; text-decoration: none; transition: all 0.2s; display: flex; align-items: center; justify-content: center; font-size: 0.875rem;">
                                            <i class="bi bi-cart-fill" style="margin-right: 0.375rem; font-size: 0.875rem;"></i> Keranjang
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <form id="add-to-cart-form" action="cart_action.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $parfum->getId(); ?>">
                                
                                <div style="width: 6rem;">
                                    <label for="quantity" class="sr-only">Jumlah</label>
                                    <div style="position: relative;">
                                        <input type="number" name="quantity" id="quantity" 
                                               style="width: 100%; padding: 0.625rem; border: 1.5px solid #e5e7eb; border-radius: 0.5rem; text-align: center; font-weight: 500; font-size: 0.9375rem; color: #1A1A1A;" 
                                               value="1" min="1" max="<?php echo $parfum->getStok(); ?>">
                                    </div>
                                </div>
                                
                                <button type="submit" id="add-to-cart-btn" 
                                        style="background-color: #D4AF37; color: #1A1A1A; font-weight: 600; font-size: 0.9375rem; padding: 0.625rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; border: none; white-space: nowrap;" 
                                        onmouseover="this.style.backgroundColor='#B5952F'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgb(0 0 0 / 0.1)';" 
                                        onmouseout="this.style.backgroundColor='#D4AF37'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgb(0 0 0 / 0.1)';">
                                    <i class="bi bi-bag-plus-fill" style="margin-right: 0.5rem; font-size: 0.875rem;"></i> Tambah ke Keranjang
                                </button>
                            </form>
                            
                            <script>
                            // Modal elements
                            const modal = document.getElementById('cart-success-modal');
                            const modalBackdrop = document.getElementById('modal-backdrop');
                            const modalCloseBtn = document.getElementById('modal-close-btn');
                            const modalContinueBtn = document.getElementById('modal-continue-btn');
                            
                            // Function to show modal
                            function showModal(productName, productImage, quantity) {
                                document.getElementById('modal-product-name').textContent = productName;
                                document.getElementById('modal-product-image').src = productImage;
                                document.getElementById('modal-product-quantity').textContent = `Jumlah: ${quantity}`;
                                modal.style.display = 'flex';
                                document.body.style.overflow = 'hidden'; // Prevent background scroll
                            }
                            
                            // Function to hide modal
                            function hideModal() {
                                modal.style.display = 'none';
                                document.body.style.overflow = ''; // Restore scroll
                            }
                            
                            // Close modal handlers
                            modalCloseBtn.addEventListener('click', hideModal);
                            modalContinueBtn.addEventListener('click', hideModal);
                            modalBackdrop.addEventListener('click', hideModal);
                            
                            // Close on Escape key
                            document.addEventListener('keydown', function(e) {
                                if (e.key === 'Escape' && modal.style.display === 'flex') {
                                    hideModal();
                                }
                            });
                            
                            // Form submission
                            document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
                                e.preventDefault();
                                
                                const form = this;
                                const formData = new FormData(form);
                                const btn = document.getElementById('add-to-cart-btn');
                                const btnOriginalText = btn.innerHTML;
                                const quantity = document.getElementById('quantity').value;
                                
                                // Disable button and show loading
                                btn.disabled = true;
                                btn.innerHTML = '<i class="bi bi-hourglass-split" style="margin-right: 0.75rem;"></i> Menambahkan...';
                                
                                // Send AJAX request
                                fetch('api_cart.php', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Update cart badge
                                        const cartBadge = document.getElementById('cart-badge');
                                        if (cartBadge) {
                                            cartBadge.textContent = data.cart_count;
                                            cartBadge.style.display = 'flex';
                                            
                                            // Add bounce animation
                                            cartBadge.style.animation = 'bounce 0.5s ease';
                                            setTimeout(() => {
                                                cartBadge.style.animation = '';
                                            }, 500);
                                        }
                                        
                                        // Show modal with product info
                                        const productImage = '<?php echo htmlspecialchars($imgSrc); ?>';
                                        showModal(data.product_name, productImage, quantity);
                                        
                                        // Reset quantity to 1
                                        document.getElementById('quantity').value = 1;
                                    } else {
                                        alert(data.message || 'Gagal menambahkan produk ke keranjang');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Terjadi kesalahan saat menambahkan produk ke keranjang');
                                })
                                .finally(() => {
                                    // Re-enable button
                                    btn.disabled = false;
                                    btn.innerHTML = btnOriginalText;
                                });
                            });
                            </script>
                            
                            <style>
                            @keyframes modalSlideUp {
                                from {
                                    opacity: 0;
                                    transform: translateY(2rem) scale(0.95);
                                }
                                to {
                                    opacity: 1;
                                    transform: translateY(0) scale(1);
                                }
                            }
                            
                            @keyframes successPulse {
                                0% {
                                    transform: scale(0);
                                    opacity: 0;
                                }
                                50% {
                                    transform: scale(1.1);
                                }
                                100% {
                                    transform: scale(1);
                                    opacity: 1;
                                }
                            }
                            
                            @keyframes bounce {
                                0%, 100% { transform: scale(1); }
                                50% { transform: scale(1.3); }
                            }
                            
                            #modal-close-btn:hover {
                                color: #1A1A1A;
                            }
                            
                            #modal-continue-btn:hover {
                                background-color: #F9FAFB;
                                border-color: #D1D5DB;
                            }
                            </style>
                        <?php else: ?>
                            <div style="background-color: rgba(239, 232, 217, 0.5); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 0.75rem; padding: 1.5rem; text-align: center;">
                                <p style="color: #1A1A1A; margin-bottom: 0.75rem;">Ingin membeli produk ini?</p>
                                <a href="login.php" style="display: inline-block; padding: 0.5rem 1.5rem; background-color: #D4AF37; color: #1A1A1A; font-weight: bold; border-radius: 0.5rem; text-decoration: none; transition: all 0.3s ease;"
                                   onmouseover="this.style.backgroundColor='#1A1A1A'; this.style.color='white';" 
                                   onmouseout="this.style.backgroundColor='#D4AF37'; this.style.color='#1A1A1A';">
                                    Login untuk Belanja
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div class="flex items-center text-gray-500 text-sm">
                            <i class="bi bi-shield-check text-primary mr-2 text-lg"></i> 100% Original
                        </div>
                        <div class="flex items-center text-gray-500 text-sm">
                            <i class="bi bi-truck text-primary mr-2 text-lg"></i> Pengiriman Cepat
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Related Products -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark font-heading mb-2">Anda Mungkin Juga Suka</h2>
                <div class="w-16 h-1 bg-primary mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                $relatedParfums = $parfumManager->readRandom(4);
                if (count($relatedParfums) > 0):
                    foreach ($relatedParfums as $p):
                        // Image logic for related products
                        $rImgSrc = $p->getImagePath();
                        if (!$rImgSrc || !file_exists($rImgSrc)) {
                            $rImgSrc = 'img/parfum_placeholder.png'; // Fallback
                            // Try to find image (simplified for related)
                            $dirs = ['img/product/', 'img/products/'];
                            foreach ($dirs as $d) {
                                if (file_exists($d . $p->getId() . '.png')) { $rImgSrc = $d . $p->getId() . '.png'; break; }
                                if (file_exists($d . $p->getId() . '.jpg')) { $rImgSrc = $d . $p->getId() . '.jpg'; break; }
                            }
                        }
                ?>
                        <a href="detail.php?id=<?php echo $p->getId(); ?>" class="group block bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="relative bg-secondary/30 pt-[100%] overflow-hidden">
                                <img src="<?php echo htmlspecialchars($rImgSrc); ?>" 
                                     class="absolute top-0 left-0 w-full h-full object-contain p-6 transform group-hover:scale-110 transition duration-500" 
                                     alt="<?php echo htmlspecialchars($p->getNama()); ?>">
                            </div>
                            <div class="p-6">
                                <p class="text-xs text-gray-500 mb-1 uppercase tracking-wider"><?php echo htmlspecialchars($p->getMerek()); ?></p>
                                <h3 class="font-bold text-dark text-lg mb-2 group-hover:text-primary transition"><?php echo htmlspecialchars($p->getNama()); ?></h3>
                                <p class="text-primary font-bold">Rp <?php echo number_format($p->getHarga(), 0, ',', '.'); ?></p>
                            </div>
                        </a>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>
</div>
    
<?php require_once 'views/footer.php'; ?>
