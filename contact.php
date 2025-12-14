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
        $old = ['name'=>'', 'inquiry_type'=>'', 'email'=>'', 'phone'=>'', 'message'=>''];
    }
}
require_once 'views/header.php';
?>

<div class="contact-page font-sans text-dark">

    <!-- Hero Section -->
    <section class="py-24 bg-secondary relative overflow-hidden">
        <div class="absolute top-0 left-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">Hubungi Kami</span>
            <h1 class="text-4xl lg:text-5xl font-bold text-dark font-heading mb-6">
                Kami Siap <span class="text-primary">Membantu</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Punya pertanyaan tentang produk atau layanan kami? Tim kami siap memberikan jawaban terbaik untuk Anda.
            </p>
        </div>
    </section>

    <!-- Contact Info Grid -->
    <section class="py-12 -mt-16 container mx-auto px-4 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            <!-- Phone Card -->
            <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition duration-300 text-center border-t-4 border-primary">
                <div class="text-primary text-3xl mb-4"><i class="bi bi-telephone"></i></div>
                <h3 class="font-bold text-dark mb-2 text-lg">Telepon</h3>
                <p class="text-gray-600">+62 812 3456 7890</p>
            </div>
            <!-- Email Card -->
            <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition duration-300 text-center border-t-4 border-primary">
                <div class="text-primary text-3xl mb-4"><i class="bi bi-envelope"></i></div>
                <h3 class="font-bold text-dark mb-2 text-lg">Email</h3>
                <p class="text-gray-600">info@ParfumMy.co.id</p>
            </div>
            <!-- Location Card -->
            <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition duration-300 text-center border-t-4 border-primary">
                <div class="text-primary text-3xl mb-4"><i class="bi bi-geo-alt"></i></div>
                <h3 class="font-bold text-dark mb-2 text-lg">Lokasi</h3>
                <p class="text-gray-600">Tangerang Selatan, Banten</p>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-4 max-w-3xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark font-heading mb-4">Kirim Pesan</h2>
                <div class="w-16 h-1 bg-primary mx-auto rounded-full"></div>
            </div>

            <div class="bg-white rounded-2xl p-8 md:p-10 shadow-lg border border-gray-100">
                <?php if ($success): ?>
                    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-lg mb-8 flex items-center">
                        <i class="bi bi-check-circle-fill mr-3 text-xl"></i>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                <?php if ($errors): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg mb-8 flex items-center">
                        <i class="bi bi-exclamation-circle-fill mr-3 text-xl"></i>
                        <div>Mohon perbaiki kesalahan di bawah ini.</div>
                    </div>
                <?php endif; ?>
                
                <form method="post" action="contact.php" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-bold text-dark mb-2">Nama Lengkap</label>
                            <input type="text" 
                                   class="w-full px-4 py-3 bg-gray-50 border <?php echo isset($errors['name']) ? 'border-red-500' : 'border-gray-200'; ?> rounded-lg focus:bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition duration-200" 
                                   id="name" 
                                   name="name" 
                                   value="<?php echo htmlspecialchars($old['name']); ?>" 
                                   placeholder="Nama Anda"
                                   required>
                            <?php if (isset($errors['name'])): ?>
                                <p class="text-red-500 text-xs mt-1 font-medium"><?php echo htmlspecialchars($errors['name']); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-bold text-dark mb-2">Email Address</label>
                            <input type="email" 
                                   class="w-full px-4 py-3 bg-gray-50 border <?php echo isset($errors['email']) ? 'border-red-500' : 'border-gray-200'; ?> rounded-lg focus:bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition duration-200" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo htmlspecialchars($old['email']); ?>" 
                                   placeholder="email@example.com"
                                   required>
                            <?php if (isset($errors['email'])): ?>
                                <p class="text-red-500 text-xs mt-1 font-medium"><?php echo htmlspecialchars($errors['email']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-bold text-dark mb-2">Nomor Telepon</label>
                            <input type="tel" 
                                   class="w-full px-4 py-3 bg-gray-50 border <?php echo isset($errors['phone']) ? 'border-red-500' : 'border-gray-200'; ?> rounded-lg focus:bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition duration-200" 
                                   id="phone" 
                                   name="phone" 
                                   value="<?php echo htmlspecialchars($old['phone']); ?>" 
                                   placeholder="+62..."
                                   required>
                            <?php if (isset($errors['phone'])): ?>
                                <p class="text-red-500 text-xs mt-1 font-medium"><?php echo htmlspecialchars($errors['phone']); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Inquiry Type -->
                        <div>
                            <label for="inquiry_type" class="block text-sm font-bold text-dark mb-2">Topik</label>
                            <select class="w-full px-4 py-3 bg-gray-50 border <?php echo isset($errors['inquiry_type']) ? 'border-red-500' : 'border-gray-200'; ?> rounded-lg focus:bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition duration-200" 
                                    id="inquiry_type" 
                                    name="inquiry_type" 
                                    required>
                                <option value="" <?php echo $old['inquiry_type']==='' ? 'selected' : ''; ?>>Pilih Topik</option>
                                <option value="Sales" <?php echo $old['inquiry_type']==='Sales' ? 'selected' : ''; ?>>Penjualan & Pesanan</option>
                                <option value="Product" <?php echo $old['inquiry_type']==='Product' ? 'selected' : ''; ?>>Informasi Produk</option>
                                <option value="Partnership" <?php echo $old['inquiry_type']==='Partnership' ? 'selected' : ''; ?>>Kemitraan / Reseller</option>
                                <option value="General" <?php echo $old['inquiry_type']==='General' ? 'selected' : ''; ?>>Pertanyaan Umum</option>
                            </select>
                            <?php if (isset($errors['inquiry_type'])): ?>
                                <p class="text-red-500 text-xs mt-1 font-medium"><?php echo htmlspecialchars($errors['inquiry_type']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-bold text-dark mb-2">Pesan Anda</label>
                        <textarea class="w-full px-4 py-3 bg-gray-50 border <?php echo isset($errors['message']) ? 'border-red-500' : 'border-gray-200'; ?> rounded-lg focus:bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition duration-200" 
                                  rows="5"
                                  id="message" 
                                  name="message" 
                                  placeholder="Tuliskan pesan Anda di sini..."
                                  required><?php echo htmlspecialchars($old['message']); ?></textarea>
                        <?php if (isset($errors['message'])): ?>
                            <p class="text-red-500 text-xs mt-1 font-medium"><?php echo htmlspecialchars($errors['message']); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                style="width: 100%; background-color: #D4AF37; color: #1A1A1A; font-weight: 600; padding: 1rem 2rem; border-radius: 0.5rem; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"
                                onmouseover="this.style.backgroundColor='#B5952F'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px rgba(0,0,0,0.15)';"
                                onmouseout="this.style.backgroundColor='#D4AF37'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';">
                            <i class="bi bi-send-fill" style="margin-right: 0.5rem;"></i> Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- FAQ Link -->
            <div class="text-center mt-12">
                <p class="text-gray-600 mb-4">Mencari jawaban cepat?</p>
                <a href="index.php#faq" class="inline-flex items-center text-primary font-bold hover:text-dark transition-colors border-b border-primary pb-0.5 hover:border-dark">
                    Lihat Pertanyaan Umum (FAQ)
                    <i class="bi bi-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

</div>

<?php require_once 'views/footer.php'; ?>
