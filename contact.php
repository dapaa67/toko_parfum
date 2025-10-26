<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$errors = [];
$success = null;
$old = ['name'=>'', 'inquiry_type'=>'', 'email'=>'', 'phone'=>'', 'message'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name'] = trim($_POST['name'] ?? '');
    $old['inquiry_type'] = trim($_POST['inquiry_type'] ?? '');
    $old['email'] = trim($_POST['email'] ?? '');
    $old['phone'] = trim($_POST['phone'] ?? '');
    $old['message'] = trim($_POST['message'] ?? '');

    if ($old['name'] === '') $errors['name'] = 'Name is required.';
    if ($old['inquiry_type'] === '') $errors['inquiry_type'] = 'Inquiry type is required.';
    if ($old['email'] === '' || !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required.';
    if ($old['phone'] === '') $errors['phone'] = 'Phone number is required.';
    if ($old['message'] === '') $errors['message'] = 'Message is required.';

    if (!$errors) {
        $success = 'Your message has been sent. We will contact you soon.';
        // You can implement email sending or database storage here.
        // Reset old values:
        $old = ['name'=>'', 'inquiry_type'=>'', 'email'=>'', 'phone'=>'', 'message'=>''];
    }
}
require_once 'views/header.php';
?>

<div class="contact-page">

<section class="py-5 bg-light">
  <div class="container">
    <div class="row g-4 align-items-center justify-content-center"> 
      
      <div class="col-lg-3">
        <h1 class="display-6 fw-bold mb-3">Kontak Kami</h1>
        <ul class="list-unstyled mb-4">
          <li class="mb-2"><i class="fas fa-phone-alt text-primary me-2"></i>+62 812 3456 7890</li>
          <li class="mb-2"><i class="fas fa-envelope text-primary me-2"></i>info@ParfumMy.co.id</li>
          <li class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i>Tangerang Selatan, Banten</li>
        </ul>
      </div>
      
      <div class="col-lg-3"> 
        <img src="img/g.png" alt="Fragrance Shop" class="img-fluid " style="max-width: 500px; height: auto;">
      </div>
      
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-7">
        <h2 class="fw-semibold mb-3">Beritahu kami lebih lanjut tentang kebutuhan Anda</h2>
        <div class="card shadow-sm">
          <div class="card-body">
            <?php if ($success): ?>
              <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <?php if ($errors): ?>
              <div class="alert alert-danger" role="alert">Mohon perbaiki kesalahan di bawah ini dan coba lagi.</div>
            <?php endif; ?>
            <form method="post" action="contact.php" novalidate>
              <div class="row g-3">
                <div class="col-12">
                  <div class="form-floating">
                    <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" placeholder="E.g. John Doe" value="<?php echo htmlspecialchars($old['name']); ?>" required>
                    <label for="name">Nama</label>
                    <?php if (isset($errors['name'])): ?>
                      <div class="invalid-feedback"><?php echo htmlspecialchars($errors['name']); ?></div>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-floating">
                    <select class="form-select <?php echo isset($errors['inquiry_type']) ? 'is-invalid' : ''; ?>" id="inquiry_type" name="inquiry_type" required>
                      <option value="" <?php echo $old['inquiry_type']==='' ? 'selected' : ''; ?>>Pilih salah satu opsi</option>
                      <option value="Sales" <?php echo $old['inquiry_type']==='Sales' ? 'selected' : ''; ?>>Penjualan</option>
                      <option value="Product" <?php echo $old['inquiry_type']==='Product' ? 'selected' : ''; ?>>Produk</option>
                      <option value="Partnership" <?php echo $old['inquiry_type']==='Partnership' ? 'selected' : ''; ?>>Kemitraan</option>
                      <option value="General" <?php echo $old['inquiry_type']==='General' ? 'selected' : ''; ?>>Umum</option>
                    </select>
                    <label for="inquiry_type">Tipe Pertanyaan</label>
                    <?php if (isset($errors['inquiry_type'])): ?>
                      <div class="invalid-feedback"><?php echo htmlspecialchars($errors['inquiry_type']); ?></div>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-floating">
                    <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="E.g. nama@gmail.com" value="<?php echo htmlspecialchars($old['email']); ?>" required>
                    <label for="email">Email</label> 
                    <?php if (isset($errors['email'])): ?>
                      <div class="invalid-feedback"><?php echo htmlspecialchars($errors['email']); ?></div>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-floating">
                    <input type="tel" class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" id="phone" name="phone" placeholder="E.g. +62 812 3456 7890" value="<?php echo htmlspecialchars($old['phone']); ?>" required>
                    <label for="phone">Nomor Telepon</label>
                    <?php if (isset($errors['phone'])): ?>
                      <div class="invalid-feedback"><?php echo htmlspecialchars($errors['phone']); ?></div>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-floating">
                    <textarea class="form-control <?php echo isset($errors['message']) ? 'is-invalid' : ''; ?>" placeholder="Pesan Anda" id="message" name="message" style="min-height: 140px; height: 140px;" required><?php echo htmlspecialchars($old['message']); ?></textarea>
                    <label for="message">Pesan</label>
                    <?php if (isset($errors['message'])): ?>
                      <div class="invalid-feedback"><?php echo htmlspecialchars($errors['message']); ?></div>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="col-12">
                  <button type="submit" class="btn btn-primary btn-lg">Kirim Pesan</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <h2 class="fw-semibold mb-3">Temukan jawaban untuk pertanyaan yang sering diajukan di sini.</h2>
            <p class="text-muted mb-3">Jelajahi FAQ kami untuk menemukan jawaban cepat tentang produk, pesanan, dan lainnya.</p>
            <a class="btn btn-outline-primary" href="index.php#faq">Lihat FAQ Kami</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

</div>

<?php require_once 'views/footer.php'; ?>