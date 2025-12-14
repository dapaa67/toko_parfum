<?php
// products.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'models/ParfumManager.php';

$parfumManager = new ParfumManager();

// Filter dari GET
$genders = $_GET['gender'] ?? [];
$sizes = $_GET['size'] ?? []; 
$kategoris = $_GET['kategori'] ?? [];

if (!empty($genders) || !empty($sizes) || !empty($kategoris)) {
    $parfums = $parfumManager->readWithFilters($genders, $sizes, $kategoris);
} else {
    $parfums = $parfumManager->readAll();
}

require_once 'views/header.php';
?>

<div class="container mx-auto px-4 my-16 mb-40" id="products">
    
    <div class="flex flex-wrap -mx-4">
        <!-- Filter Sidebar -->
        <div class="w-full lg:w-1/4 px-4 mb-8 lg:mb-0">
            <div class="bg-gray-50 p-6 rounded-lg sticky top-20">
                <h5 class="text-xl font-bold text-gold mb-4">Filter</h5>
                <hr class="my-4 border-gray-300">
                <form action="products.php" method="get">
                    <!-- Aroma Filter -->
                    <h6 class="font-semibold mb-3">Aroma</h6>
                    <div class="space-y-2 mb-6">
                        <?php 
                        $allKategoris = $parfumManager->getDistinctKategori();
                        foreach ($allKategoris as $kategori): 
                        ?>
                            <div class="flex items-center">
                                <input class="w-4 h-4 text-gold border-gray-300 rounded focus:ring-gold mr-2" type="checkbox" name="kategori[]" value="<?php echo htmlspecialchars($kategori); ?>" id="kategori<?php echo htmlspecialchars($kategori); ?>"
                                    <?php echo in_array($kategori, $kategoris ?? []) ? 'checked' : ''; ?>>
                                <label class="text-sm" for="kategori<?php echo htmlspecialchars($kategori); ?>"><?php echo htmlspecialchars($kategori); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <hr class="my-4 border-gray-300">

                    <!-- Gender Filter -->
                    <h6 class="font-semibold mb-3">Gender</h6>
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-gold border-gray-300 rounded focus:ring-gold mr-2" type="checkbox" name="gender[]" value="Male" id="genderMale" 
                                <?php echo in_array('Male', $genders ?? []) ? 'checked' : ''; ?>>
                            <label class="text-sm" for="genderMale">Male</label>
                        </div>
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-gold border-gray-300 rounded focus:ring-gold mr-2" type="checkbox" name="gender[]" value="Female" id="genderFemale"
                                <?php echo in_array('Female', $genders ?? []) ? 'checked' : ''; ?>>
                            <label class="text-sm" for="genderFemale">Female</label>
                        </div>
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-gold border-gray-300 rounded focus:ring-gold mr-2" type="checkbox" name="gender[]" value="Unisex" id="genderUnisex"
                                <?php echo in_array('Unisex', $genders ?? []) ? 'checked' : ''; ?>>
                            <label class="text-sm" for="genderUnisex">Unisex</label>
                        </div>
                    </div>
                    <hr class="my-4 border-gray-300">
                    <!-- Size Filter -->
                    <h6 class="font-semibold mb-3">Size (ml)</h6>
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-gold border-gray-300 rounded focus:ring-gold mr-2" type="checkbox" name="size[]" value="200" id="size200"
                                <?php echo in_array('200', $sizes ?? []) ? 'checked' : ''; ?>>
                            <label class="text-sm" for="size200">200ml</label>
                        </div>
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-gold border-gray-300 rounded focus:ring-gold mr-2" type="checkbox" name="size[]" value="100" id="size100"
                                <?php echo in_array('100', $sizes ?? []) ? 'checked' : ''; ?>>
                            <label class="text-sm" for="size100">100ml</label>
                        </div>
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-gold border-gray-300 rounded focus:ring-gold mr-2" type="checkbox" name="size[]" value="50" id="size50"
                                <?php echo in_array('50', $sizes ?? []) ? 'checked' : ''; ?>>
                            <label class="text-sm" for="size50">50ml</label>
                        </div>
                    </div>
                    <hr class="my-4 border-gray-300">
                    <button type="submit" 
                            style="width: 100%; background-color: #D4AF37; color: #1A1A1A; font-weight: 600; padding: 0.75rem 1rem; border-radius: 0.5rem; border: none; cursor: pointer; transition: all 0.2s; font-size: 0.9375rem;"
                            onmouseover="this.style.backgroundColor='#B5952F';"
                            onmouseout="this.style.backgroundColor='#D4AF37';">
                        <i class="bi bi-funnel-fill" style="margin-right: 0.375rem;"></i> Terapkan Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="w-full lg:w-3/4 px-4">
            <h2 class="text-center text-4xl font-bold mb-12 text-gold">Semua Produk</h2>
            <div class="flex flex-wrap justify-center -mx-2">
                <?php if (count($parfums) > 0): ?>
                    <?php foreach ($parfums as $p): ?>
                        <div class="w-full sm:w-1/2 md:w-1/3 px-2 mb-8">
                            <a href="detail.php?id=<?php echo htmlspecialchars($p->getId()); ?>" class="block group no-underline">
                                <div class="bg-white rounded-lg shadow-md h-full border-0 transition transform hover:scale-105 hover:shadow-xl">
                                    <?php
                                    $imgSrc = $p->getImagePath();
                                    if (!$imgSrc || !file_exists($imgSrc)) {
                                        $dirs = ['img/product/', 'img/products/'];
                                        $exts = ['.png','.jpg','.jpeg','.webp'];
                                        $imgSrc = null;
                                        foreach ($dirs as $d) {
                                            foreach ($exts as $e) {
                                                $candidate = $d . $p->getId() . $e;
                                                if (file_exists($candidate)) { $imgSrc = $candidate; break 2; }
                                            }
                                        }
                                        if (!$imgSrc) { $imgSrc = 'img/parfum_placeholder.png'; }
                                    }
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
                    <p class="text-center text-gray-600 w-full">Tidak ada parfum yang sesuai dengan filter.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/footer.php'; ?>
