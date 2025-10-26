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


<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-indicators">
        <?php foreach ($carouselItems as $index => $item): ?>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>" aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $index + 1; ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
        <?php foreach ($carouselItems as $index => $item): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <img src="<?php echo $item->image_path; ?>" class="d-block w-100" alt="<?php echo $item->title; ?>">
                <?php if ($item->title || $item->description): ?>
                    <div class="carousel-caption d-none d-md-block"></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="container my-extra-margin why-us-section-reverted">
    <div class="row align-items-center">
        <div class="col-md-5">
            <img src="img/d.png" class="img-fluid rounded" alt="Parfum Promo">
        </div>

        <div class="col-md-7">
            <h2 class="mb-3"><?php echo htmlspecialchars($aboutUsContent->main_title); ?></h2>
            <p class="lead"><?php echo htmlspecialchars($aboutUsContent->lead_paragraph); ?></p>
            <ul class="list-unstyled mt-4">
                <?php if (!empty($aboutUsListItems)): ?>
                    <?php foreach ($aboutUsListItems as $item): ?>
                        <li class="mb-3"><i class="<?php echo htmlspecialchars($item->icon_class); ?> text-primary"></i> <?php echo htmlspecialchars($item->item_text); ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="mb-3">No list items found. Please add them from the admin panel.</li>
                <?php endif; ?>
            </ul>
            <a href="#products" class="btn btn-lg btn-primary mt-3">Jelajahi Koleksi Kami</a>
        </div>
    </div>
</div>

<div class="container my-section-spacing">
    <h2 class="text-center mb-4">Best Seller Parfum</h2>
    <div class="row justify-content-center">
        <?php
        $bestSellers = $parfumManager->readBestSellers(4); // Ambil 4 best sellers dari DB (is_best_seller = 1)
        if (count($bestSellers) > 0):
            foreach ($bestSellers as $p):
        ?>
                <div class="col-lg-3 col-md-4 col-sm-4 mb-4">
                    <a href="detail.php?id=<?php echo $p->getId(); ?>" class="card-link">
                        <div class="card h-100 border-0">
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
                            <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="card-img-top product-card-img" alt="<?php echo htmlspecialchars($p->getNama()); ?>">
                            <div class="card-body text-center">
                                <h6 class="card-title text-primary"><?php echo htmlspecialchars($p->getNama()); ?></h6>
                                <p class="card-text"><?php echo htmlspecialchars($p->getUkuran()); ?>ml | <?php echo htmlspecialchars($p->getGender()); ?></p>
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

<div class="container my-section-spacing" id="products">
    <h2 class="text-center mb-4">Produk Parfum Kami</h2>
    <div class="row justify-content-center">
        <?php if (count($parfums) > 0): ?>
            <?php $produkKami = array_slice($parfums, 0, 7);
            foreach ($produkKami as $p): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-6">
                    <a href="detail.php?id=<?php echo $p->getId(); ?>" class="card-link">
                        <div class="card h-100 border-0">
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
                            <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="card-img-top product-card-img" alt="<?php echo htmlspecialchars($p->getNama()); ?>">
                            <div class="card-body text-center">
                                <h6 class="card-title text-primary"><?php echo htmlspecialchars($p->getNama()); ?></h6>
                                <p class="card-text"><?php echo htmlspecialchars($p->getUkuran()); ?>ml | <?php echo htmlspecialchars($p->getGender()); ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Belum ada parfum tersedia.</p>
        <?php endif; ?>
    </div>
    <div class="text-center mt-4">
        <a href="products.php" class="btn btn-primary">Lihat Semua Produk</a>
    </div>
</div>

<div class="container my-section-spacing" id="faq">
    <h2 class="text-center mb-4">FAQ</h2>
    <div class="accordion" id="faqAccordion">

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    Bagaimana cara memilih aroma parfum yang cocok untuk aktivitas harian?
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Untuk aktivitas harian, kami sarankan memilih aroma yang ringan dan segar seperti *citrus* atau floral lembut. Aroma seperti ini tidak terlalu menyengat dan cocok untuk digunakan di kantor atau kegiatan luar ruangan. Untuk malam hari, Anda bisa mencoba aroma yang lebih berat dan hangat seperti *woody* atau *spicy*.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Apa kebijakan pengembalian dan penukaran produk?
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Kami menerima pengembalian atau penukaran produk jika terjadi kerusakan saat pengiriman atau kesalahan pengiriman barang, maksimal 7 hari setelah barang diterima. Parfum harus dalam kondisi segel utuh (belum dibuka) dan disertai bukti pembelian.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Bagaimana cara menyemprotkan parfum agar wanginya lebih tahan lama?
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Semprotkan parfum pada titik-titik nadi Anda (pergelangan tangan, belakang telinga, leher, dan siku). Pastikan kulit Anda dalam keadaan lembap (setelah mandi atau menggunakan *lotion*) sebelum menyemprotkan parfum. Jangan menggosok parfum setelah disemprotkan karena dapat memecah molekul aroma.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    Berapa lama waktu pengiriman?
                </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Waktu pengiriman bervariasi tergantung pada lokasi Anda. Untuk wilayah Jabodetabek, pengiriman biasanya memakan waktu 1-3 hari kerja. Untuk kota-kota lain di Indonesia, estimasi waktu pengiriman adalah 3-7 hari kerja.
                </div>
            </div>
        </div>

    </div>
</div>

<div class="container-fluid promo-banner text-white py-5">
    <div class="container text-end">
        <h2 class="display-7">Temukan Aroma Favoritmu Sekarang!</h2>
        <p class="lead">Penawaran terbatas untuk koleksi pilihan.</p>
        <a href="#products" class="btn btn-primary btn-lg mt-3">Belanja Sekarang</a>
    </div>
</div>

<?php require_once 'views/footer.php'; ?>

</body>
</html>
