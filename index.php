<?php
// index.php
session_start(); // Mulai session

// 1. Logika PHP
require_once 'models/ParfumManager.php';
require_once 'models/CarouselManager.php';
require_once 'models/AboutUsManager.php';

$parfumManager = new ParfumManager();
$parfums = $parfumManager->readAll();
$carouselManager = new CarouselManager();
$carouselItems = $carouselManager->readAll();
$aboutUsManager = new AboutUsManager();
$aboutUsContent = $aboutUsManager->getMainContent(); // Changed to getMainContent()

// Set default values if no main content exists yet
if (!$aboutUsContent) {
    $aboutUsContent = (object)[
        'main_title' => 'Mengapa Fragrance Shop?',
        'lead_paragraph' => 'Kami menawarkan pengalaman berbelanja parfum yang tak tertandingi dengan koleksi eksklusif dan layanan pelanggan terbaik.',
    ];
    // Also create a default entry in the database if it doesn't exist
    $aboutUsManager->updateMainContent($aboutUsContent->main_title, $aboutUsContent->lead_paragraph);
    $aboutUsContent = $aboutUsManager->getMainContent(); // Re-fetch after creating
}

$aboutUsListItems = [];
if ($aboutUsContent) {
    $aboutUsListItems = $aboutUsManager->getListItems($aboutUsContent->id);
}

// 2. Panggil Header (membuka tag <html>, <head>, <body>, dan menampilkan Navbar)
require_once 'views/header.php';
?>


<!-- New Hero Section -->
<section class="relative bg-secondary pt-10 pb-20 lg:pt-20 lg:pb-28 overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center -mx-4">
            <!-- Text Content -->
            <div class="w-full lg:w-1/2 px-4 mb-12 lg:mb-0">
                <span class="block text-primary font-bold text-lg mb-4 tracking-wide uppercase">
                    Eksklusif & Elegan
                </span>
                <h1 class="text-5xl lg:text-6xl font-bold text-dark mb-6 leading-tight">
                    Temukan Aroma <br>
                    <span class="text-primary">Kepribadianmu</span>
                </h1>
                <p class="text-lg text-dark/80 mb-8 leading-relaxed max-w-lg">
                    Koleksi parfum pilihan dengan wangi tahan lama yang dirancang untuk memancarkan pesona dan kepercayaan diri Anda setiap hari.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#products" 
                       style="display: inline-block; background-color: #D4AF37; color: #1A1A1A; font-weight: 600; padding: 1rem 2rem; border-radius: 0.5rem; text-decoration: none; transition: all 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"
                       onmouseover="this.style.backgroundColor='#B5952F'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px rgba(0,0,0,0.15)';"
                       onmouseout="this.style.backgroundColor='#D4AF37'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';">
                        <i class="bi bi-cart-plus" style="margin-right: 0.5rem;"></i> Belanja Sekarang
                    </a>
                    <a href="#faq" 
                       style="display: inline-block; background: white; color: #1A1A1A; font-weight: 600; padding: 1rem 2rem; border: 2px solid #1A1A1A; border-radius: 0.5rem; text-decoration: none; transition: all 0.2s;"
                       onmouseover="this.style.backgroundColor='#1A1A1A'; this.style.color='white';"
                       onmouseout="this.style.backgroundColor='white'; this.style.color='#1A1A1A';">
                        <i class="bi bi-info-circle" style="margin-right: 0.5rem;"></i> Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
            
            <!-- Carousel Image Content -->
            <div class="w-full lg:w-1/2 px-4 relative">
                <div class="relative z-10">
                    <!-- Carousel Container -->
                    <div id="hero-carousel" style="position: relative; border-radius: 1rem; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
                        <?php if (!empty($carouselItems)): ?>
                            <?php foreach ($carouselItems as $index => $item): ?>
                                <div class="carousel-slide" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>; animation: fadeIn 0.5s ease-in-out;">
                                    <img src="<?php echo htmlspecialchars($item->image_path); ?>" 
                                         alt="Hero Banner <?php echo $index + 1; ?>" 
                                         style="width: 100%; height: auto; object-fit: cover; border-radius: 1rem;">
                                </div>
                            <?php endforeach; ?>
                            
                            <!-- Navigation Dots -->
                            <div style="position: absolute; bottom: 1.5rem; left: 50%; transform: translateX(-50%); display: flex; gap: 0.5rem; z-index: 10;">
                                <?php foreach ($carouselItems as $index => $item): ?>
                                    <button onclick="goToSlide(<?php echo $index; ?>)" 
                                            class="carousel-dot"
                                            style="width: 0.75rem; height: 0.75rem; border-radius: 50%; border: 2px solid white; background-color: <?php echo $index === 0 ? '#D4AF37' : 'rgba(255,255,255,0.5)'; ?>; cursor: pointer; transition: all 0.3s;"
                                            onmouseover="this.style.transform='scale(1.2)';"
                                            onmouseout="this.style.transform='scale(1)';"></button>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <!-- Fallback if no carousel items -->
                            <div class="carousel-slide">
                                <img src="img/c.png" alt="Parfum Mewah" style="width: 100%; height: auto; object-fit: cover; border-radius: 1rem;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Decorative Circle -->
                <div class="absolute -top-10 -right-10 w-64 h-64 bg-primary/20 rounded-full blur-3xl -z-10"></div>
                <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-primary/20 rounded-full blur-3xl -z-10"></div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>

<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-slide');
const dots = document.querySelectorAll('.carousel-dot');
const totalSlides = slides.length;

function showSlide(index) {
    // Hide all slides
    slides.forEach(slide => {
        slide.style.display = 'none';
    });
    
    // Update dots
    dots.forEach((dot, i) => {
        dot.style.backgroundColor = i === index ? '#D4AF37' : 'rgba(255,255,255,0.5)';
    });
    
    // Show current slide
    if (slides[index]) {
        slides[index].style.display = 'block';
        slides[index].style.animation = 'fadeIn 0.5s ease-in-out';
    }
    
    currentSlide = index;
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
}

function goToSlide(index) {
    showSlide(index);
}

// Auto-slide every 5 seconds
if (totalSlides > 1) {
    setInterval(nextSlide, 5000);
}
</script>


<!-- Why Us Section -->
<div class="container mx-auto px-4 my-16">
    <div class="flex flex-wrap items-center">
        <div class="w-full md:w-5/12 mb-8 md:mb-0">
            <img src="img/d.png" class="max-w-full h-auto rounded-lg" style="height: 700px; object-fit: cover;" alt="Parfum Promo">
        </div>

        <div class="w-full md:w-7/12 md:pl-12">
            <h2 class="text-4xl font-bold mb-6 text-gold"><?php echo htmlspecialchars($aboutUsContent->main_title); ?></h2>
            <p class="text-xl font-light mb-6 text-gray-800"><?php echo htmlspecialchars($aboutUsContent->lead_paragraph); ?></p>
            <ul class="list-none mt-8 space-y-4">
                <?php if (!empty($aboutUsListItems)): ?>
                    <?php foreach ($aboutUsListItems as $item): ?>
                        <li class="flex items-start">
                            <i class="<?php echo htmlspecialchars($item->icon_class); ?> text-gold text-2xl w-10"></i>
                            <span class="text-gray-800"><?php echo htmlspecialchars($item->item_text); ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No list items found. Please add them from the admin panel.</li>
                <?php endif; ?>
            </ul>
            <a href="#products" 
               style="display: inline-block; background-color: #D4AF37; color: #1A1A1A; font-weight: 600; padding: 1rem 2rem; border-radius: 0.5rem; text-decoration: none; transition: all 0.2s; font-size: 1.0625rem; margin-top: 1.5rem;"
               onmouseover="this.style.backgroundColor='#B5952F'; this.style.transform='translateY(-2px)';"
               onmouseout="this.style.backgroundColor='#D4AF37'; this.style.transform='translateY(0)';">
                <i class="bi bi-box-seam" style="margin-right: 0.5rem;"></i> Jelajahi Koleksi Kami
            </a>
        </div>
    </div>
</div>

<!-- Best Seller Section -->
<div class="container mx-auto px-4 my-16 mb-40">
    <h2 class="text-center text-4xl font-bold mb-12 text-gold">Best Seller Parfum</h2>
    <div class="flex flex-wrap justify-center -mx-2">
        <?php
        $bestSellers = $parfumManager->readBestSellers(4); // Ambil 4 best sellers dari DB (is_best_seller = 1)
        if (count($bestSellers) > 0):
            foreach ($bestSellers as $p):
        ?>
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 px-2 mb-8">
                    <a href="detail.php?id=<?php echo $p->getId(); ?>" class="block group no-underline">
                        <div class="bg-white rounded-lg shadow-md h-full border-0 transition transform hover:scale-105 hover:shadow-xl">
                            <?php
                            $imgSrc = $p->getImagePath();
                            $resolved = ($imgSrc && file_exists($imgSrc)) ? $imgSrc : null;
                            if (!$resolved) {
                                $dirs = ['img/product/', 'img/products/'];
                                $exts = ['.png', '.jpg', '.jpeg', '.webp'];
                                $nameSlug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '_', $p->getNama()), '_'));
                                $baseNames = [(string)$p->getId(), $nameSlug];
                                foreach ($dirs as $d) {
                                    foreach ($baseNames as $bn) {
                                        foreach ($exts as $e) {
                                            $candidate = $d . $bn . $e;
                                            if (file_exists($candidate)) {
                                                $resolved = $candidate;
                                                break 3;
                                            }
                                        }
                                    }
                                }
                            }
                            if (!$resolved) {
                                $resolved = 'img/parfum_placeholder.png';
                            }
                            $imgSrc = $resolved;
                            ?>
                            <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="w-full rounded-t-lg h-64 object-contain p-4 bg-white" alt="<?php echo htmlspecialchars($p->getNama()); ?>">
                            <div class="p-6 text-center">
                                <h6 class="text-lg font-semibold text-gold mb-2"><?php echo htmlspecialchars($p->getNama()); ?></h6>
                                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($p->getUkuran()); ?>ml | <?php echo htmlspecialchars($p->getGender()); ?></p>
                            </div>
                        </div>
                    </a>
                </div>
        <?php
            endforeach;
        else:
        ?>
            <p class="text-center">Belum ada best seller parfum tersedia.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Products Section -->
<div class="container mx-auto px-4 my-16 mb-40" id="products">
    <h2 class="text-center text-4xl font-bold mb-12 text-gold">Produk Parfum Kami</h2>
    <div class="flex flex-wrap justify-center -mx-2">
        <?php if (count($parfums) > 0): ?>
            <?php $produkKami = array_slice($parfums, 0, 7);
            foreach ($produkKami as $p): ?>
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 px-2 mb-8">
                    <a href="detail.php?id=<?php echo $p->getId(); ?>" class="block group no-underline">
                        <div class="bg-white rounded-lg shadow-md h-full border-0 transition transform hover:scale-105 hover:shadow-xl">
                            <?php
                            $imgSrc = $p->getImagePath();
                            $resolved = ($imgSrc && file_exists($imgSrc)) ? $imgSrc : null;
                            if (!$resolved) {
                                $dirs = ['img/product/', 'img/products/'];
                                $exts = ['.png', '.jpg', '.jpeg', '.webp'];
                                $nameSlug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '_', $p->getNama()), '_'));
                                $baseNames = [(string)$p->getId(), $nameSlug];
                                foreach ($dirs as $d) {
                                    foreach ($baseNames as $bn) {
                                        foreach ($exts as $e) {
                                            $candidate = $d . $bn . $e;
                                            if (file_exists($candidate)) {
                                                $resolved = $candidate;
                                                break 3;
                                            }
                                        }
                                    }
                                }
                            }
                            if (!$resolved) {
                                $resolved = 'img/parfum_placeholder.png';
                            }
                            $imgSrc = $resolved;
                            ?>
                            <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="w-full rounded-t-lg h-64 object-contain p-4 bg-white" alt="<?php echo htmlspecialchars($p->getNama()); ?>">
                            <div class="p-6 text-center">
                                <h6 class="text-lg font-semibold text-gold mb-2"><?php echo htmlspecialchars($p->getNama()); ?></h6>
                                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($p->getUkuran()); ?>ml | <?php echo htmlspecialchars($p->getGender()); ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Belum ada parfum tersedia.</p>
        <?php endif; ?>
    </div>
    <div class="text-center mt-8">
        <a href="products.php" 
           style="display: inline-block; background-color: #D4AF37; color: #1A1A1A; font-weight: 600; padding: 0.75rem 2rem; border-radius: 0.5rem; text-decoration: none; transition: all 0.2s;"
           onmouseover="this.style.backgroundColor='#B5952F'; this.style.transform='translateY(-2px)';"
           onmouseout="this.style.backgroundColor='#D4AF37'; this.style.transform='translateY(0)';">
            <i class="bi bi-grid-3x3" style="margin-right: 0.5rem;"></i> Lihat Semua Produk
        </a>
    </div>
</div>

<!-- FAQ Section -->
<div class="container mx-auto px-4 my-16 mb-40" id="faq">
    <h2 class="text-center text-4xl font-bold mb-12 text-gold">FAQ</h2>
    <div class="max-w-3xl mx-auto" x-data="{ openFaq: null }">

        <!-- FAQ Item 1 -->
        <div class="border-b border-gray-200 mb-4">
            <button @click="openFaq = openFaq === 1 ? null : 1" 
                    class="w-full text-left py-4 px-6 flex justify-between items-center hover:bg-gray-50 rounded-t-lg transition">
                <span class="font-semibold text-gray-800">Bagaimana cara memilih aroma parfum yang cocok untuk aktivitas harian?</span>
                <span class="text-gold text-xl" x-text="openFaq === 1 ? '−' : '+'"></span>
            </button>
            <div x-show="openFaq === 1" 
                 x-collapse
                 class="px-6 pb-4 text-gray-600">
                Untuk aktivitas harian, kami sarankan memilih aroma yang ringan dan segar seperti *citrus* atau floral lembut. Aroma seperti ini tidak terlalu menyengat dan cocok untuk digunakan di kantor atau kegiatan luar ruangan. Untuk malam hari, Anda bisa mencoba aroma yang lebih berat dan hangat seperti *woody* atau *spicy*.
            </div>
        </div>

        <!-- FAQ Item 2 -->
        <div class="border-b border-gray-200 mb-4">
            <button @click="openFaq = openFaq === 2 ? null : 2" 
                    class="w-full text-left py-4 px-6 flex justify-between items-center hover:bg-gray-50 transition">
                <span class="font-semibold text-gray-800">Apa kebijakan pengembalian dan penukaran produk?</span>
                <span class="text-gold text-xl" x-text="openFaq === 2 ? '−' : '+'"></span>
            </button>
            <div x-show="openFaq === 2" 
                 x-collapse
                 class="px-6 pb-4 text-gray-600">
                Kami menerima pengembalian atau penukaran produk jika terjadi kerusakan saat pengiriman atau kesalahan pengiriman barang, maksimal 7 hari setelah barang diterima. Parfum harus dalam kondisi segel utuh (belum dibuka) dan disertai bukti pembelian.
            </div>
        </div>

        <!-- FAQ Item 3 -->
        <div class="border-b border-gray-200 mb-4">
            <button @click="openFaq = openFaq === 3 ? null : 3" 
                    class="w-full text-left py-4 px-6 flex justify-between items-center hover:bg-gray-50 transition">
                <span class="font-semibold text-gray-800">Bagaimana cara menyemprotkan parfum agar wanginya lebih tahan lama?</span>
                <span class="text-gold text-xl" x-text="openFaq === 3 ? '−' : '+'"></span>
            </button>
            <div x-show="openFaq === 3" 
                 x-collapse
                 class="px-6 pb-4 text-gray-600">
                Semprotkan parfum pada titik-titik nadi Anda (pergelangan tangan, belakang telinga, leher, dan siku). Pastikan kulit Anda dalam keadaan lembap (setelah mandi atau menggunakan *lotion*) sebelum menyemprotkan parfum. Jangan menggosok parfum setelah disemprotkan karena dapat memecah molekul aroma.
            </div>
        </div>

        <!-- FAQ Item 4 -->
        <div class="border-b border-gray-200 mb-4">
            <button @click="openFaq = openFaq === 4 ? null : 4" 
                    class="w-full text-left py-4 px-6 flex justify-between items-center hover:bg-gray-50 transition">
                <span class="font-semibold text-gray-800">Berapa lama waktu pengiriman?</span>
                <span class="text-gold text-xl" x-text="openFaq === 4 ? '−' : '+'"></span>
            </button>
            <div x-show="openFaq === 4" 
                 x-collapse
                 class="px-6 pb-4 text-gray-600">
                Waktu pengiriman bervariasi tergantung pada lokasi Anda. Untuk wilayah Jabodetabek, pengiriman biasanya memakan waktu 1-3 hari kerja. Untuk kota-kota lain di Indonesia, estimasi waktu pengiriman adalah 3-7 hari kerja.
            </div>
        </div>

    </div>
</div>

<!-- Minimal Text Divider -->
<section class="py-24 text-center">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl md:text-5xl font-heading text-dark mb-6 tracking-tight">
            Elevate Your Senses
        </h2>
        <a href="#products" class="inline-flex items-center text-primary font-bold tracking-widest uppercase text-sm hover:text-dark transition-colors duration-300 border-b border-primary pb-1 hover:border-dark">
            Shop Collection
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </a>
    </div>
</section>

<?php require_once 'views/footer.php'; ?>

</body>
</html>
