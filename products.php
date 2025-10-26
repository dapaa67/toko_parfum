<?php
// products.php (Di folder root)

// HANYA START SESSION JIKA BELUM ADA SESSION AKTIF
// Ini mencegah Notice jika sesi sudah diaktifkan di file lain yang di-require/include.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'models/ParfumManager.php';

$parfumManager = new ParfumManager();

// Cek dan ambil nilai filter dari GET
$genders = $_GET['gender'] ?? [];
$sizes = $_GET['size'] ?? []; 

// Check for filters (Menggunakan $genders dan $sizes yang sudah didefinisikan di atas)
if (!empty($genders) || !empty($sizes)) {
    // Lu menggunakan 'gender' dan 'size' di filter form.
    // ParfumManager diasumsikan sudah diupdate agar 'size' merujuk ke field 'ukuran' (int) 
    // dan 'gender' merujuk ke field 'gender' di database.
    $parfums = $parfumManager->readWithFilters($genders, $sizes);
} else {
    $parfums = $parfumManager->readAll();
}

require_once 'views/header.php';
?>

<div class="container my-section-spacing" id="products">
    
    <div class="row">
        <!-- Filter Sidebar -->
        <div class="col-lg-3 products-filter-col">
            <div class="filter-sidebar sticky-top">
                <h5>Filter</h5>
                <hr>
                <form action="products.php" method="get">
                    <!-- Gender Filter -->
                    <h6>Gender</h6>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="gender[]" value="Male" id="genderMale" 
                            <?php echo in_array('Male', $genders ?? []) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="genderMale">Male</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="gender[]" value="Female" id="genderFemale"
                            <?php echo in_array('Female', $genders ?? []) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="genderFemale">Female</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="gender[]" value="Unisex" id="genderUnisex"
                            <?php echo in_array('Unisex', $genders ?? []) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="genderUnisex">Unisex</label>
                    </div>
                    <hr>
                    <!-- Size Filter -->
                    <h6>Size (ml)</h6>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="size[]" value="200" id="size200"
                            <?php echo in_array('200', $sizes ?? []) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="size200">200ml</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="size[]" value="100" id="size100"
                            <?php echo in_array('100', $sizes ?? []) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="size100">100ml</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="size[]" value="50" id="size50"
                            <?php echo in_array('50', $sizes ?? []) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="size50">50ml</label>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </form>
            </div>
        </div>

        <!-- Products -->
        <div class="col-lg-9">
            <h2 class="text-center mb-4">Semua Produk</h2>
            <div class="row justify-content-center">
                <?php if (count($parfums) > 0): ?>
                    <?php foreach ($parfums as $p): ?>
                        <div class="col-lg-4 col-md-4 col-sm-6 mb-4">
                            <a href="detail.php?id=<?php echo htmlspecialchars($p->getId()); ?>" class="card-link">
                                <div class="card h-100 border-0">
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
                    <p class="text-center">Tidak ada parfum yang sesuai dengan filter.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/footer.php'; ?>

<!-- Sticky/fixed sidebar fallback via JS to ensure ikut scroll -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  var sidebar = document.querySelector('.filter-sidebar');
  var col = document.querySelector('.products-filter-col');
  if (!sidebar || !col) return;

  var navHeight = 80; // samakan dengan top di CSS/sticky-top agar tidak ketutup navbar

  function setWidth() {
    // Kunci lebar sidebar saat fixed supaya layout tidak lompat
    sidebar.style.width = col.clientWidth + 'px';
  }

  function onScroll() {
    var rect = col.getBoundingClientRect();
    var containerTop = rect.top;
    var containerBottom = rect.bottom;

    // Hanya aktifkan di layar desktop (sesuai col-lg)
    var isDesktop = window.matchMedia('(min-width: 992px)').matches;

    if (!isDesktop) {
      // Reset jika mobile
      sidebar.style.position = '';
      sidebar.style.top = '';
      sidebar.style.width = '';
      sidebar.style.zIndex = '';
      return;
    }

    // Saat bagian atas kolom melewati navHeight dan masih ada ruang sampai bawah kolom
    if (containerTop <= navHeight && (containerBottom - navHeight) > sidebar.offsetHeight) {
      sidebar.style.position = 'fixed';
      sidebar.style.top = navHeight + 'px';
      sidebar.style.zIndex = 2;
      setWidth();
    } else {
      // Reset posisi default
      sidebar.style.position = '';
      sidebar.style.top = '';
      sidebar.style.width = '';
      sidebar.style.zIndex = '';
    }
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', function () {
    setWidth();
    onScroll();
  });

  // Inisialisasi
  setWidth();
  onScroll();
});
</script>
