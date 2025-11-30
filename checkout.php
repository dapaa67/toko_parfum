<?php
require_once 'models/CartManager.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect jika belum login atau keranjang kosong
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

$totalPrice = $cartManager->getTotalPrice();

require_once 'views/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4">Checkout</h2>

    <div class="row g-5">
        <!-- Form Pengiriman -->
        <div class="col-md-7 col-lg-8">
            <h4 class="mb-3">Alamat Pengiriman</h4>
            <?php if (isset($_SESSION['error_message'])): ?>
                <?php
                    $rawError = $_SESSION['error_message'];
                    unset($_SESSION['error_message']);

                    // Mapping pesan error teknis menjadi pesan yang lebih ramah user
                    $friendlyError = $rawError;
                    if (strpos($rawError, 'Lock wait timeout exceeded') !== false) {
                        $friendlyError = 'Server sedang sibuk saat memproses pesanan. Silakan klik "Buat Pesanan" lagi dalam beberapa detik. Jika masih gagal, coba muat ulang halaman.';
                    }
                ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($friendlyError, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
            <form action="place_order.php" method="POST" class="needs-validation" novalidate>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="nama_penerima" class="form-label">Nama Lengkap Penerima</label>
                        <input type="text" class="form-control" id="nama_penerima" name="nama_penerima" required>
                        <div class="invalid-feedback">
                            Nama penerima wajib diisi.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="telepon" class="form-label">Nomor Telepon</label>
                        <input type="tel" class="form-control" id="telepon" name="telepon" placeholder="0812..." required>
                        <div class="invalid-feedback">
                            Nomor telepon wajib diisi.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="alamat_pengiriman" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="alamat_pengiriman" name="alamat_pengiriman" rows="3" required></textarea>
                        <div class="invalid-feedback">
                            Alamat pengiriman wajib diisi.
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h4 class="mb-3">Metode Pembayaran</h4>

                <div class="my-3">
                    <div class="form-check">
                        <input id="cod" name="metode_pembayaran" type="radio" class="form-check-input" value="Cash on Delivery (COD)" required checked>
                        <label class="form-check-label" for="cod">Cash on Delivery (COD)</label>
                    </div>
                    <div class="form-check">
                        <input id="transfer" name="metode_pembayaran" type="radio" class="form-check-input" value="Bank Transfer" required>
                        <label class="form-check-label" for="transfer">Bank Transfer</label>
                    </div>
                </div>

                <hr class="my-4">

                <button class="w-100 btn btn-primary btn-lg" type="submit">Buat Pesanan</button>
            </form>
        </div>

        <!-- Ringkasan Pesanan -->
        <div class="col-md-5 col-lg-4 order-md-last">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Ringkasan</span>
                <span class="badge bg-primary rounded-pill"><?php echo $cartManager->getTotalItemCount(); ?></span>
            </h4>
            <ul class="list-group mb-3">
                <?php foreach ($cartItems as $item): ?>
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0"><?php echo htmlspecialchars($item['nama']); ?></h6>
                        <small class="text-muted">Jumlah: <?php echo $item['quantity']; ?></small>
                    </div>
                    <span class="text-muted">Rp <?php echo number_format($item['harga'] * $item['quantity'], 0, ',', '.'); ?></span>
                </li>
                <?php endforeach; ?>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (IDR)</span>
                    <strong>Rp <?php echo number_format($totalPrice, 0, ',', '.'); ?></strong>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
// Script validasi form Bootstrap + cegah double submit saat checkout
(function () {
  'use strict';
  var forms = document.querySelectorAll('.needs-validation');
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add('was-validated');
          return;
        }

        form.classList.add('was-validated');

        // Disable tombol submit supaya user tidak klik berkali-kali
        var submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn && !submitBtn.disabled) {
          submitBtn.disabled = true;
          submitBtn.dataset.originalText = submitBtn.textContent;
          submitBtn.textContent = 'Memproses pesanan...';
        }
      }, false);
    });
})();
</script>

<?php require_once 'views/footer.php'; ?>
