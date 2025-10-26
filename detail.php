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

<div class="container my-5 detail-container">
    <div class="row align-items-stretch">
        <div class="col-lg-6">
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
            <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="img-fluid rounded h-100" alt="<?php echo htmlspecialchars($parfum->getNama()); ?>">
        </div>
        <div class="col-lg-6">
            <div class="product-detail-card h-100">
                <span class="custom-badge">Recommended</span>
                <h1 class="display-4 mt-2"><?php echo $parfum->getNama(); ?></h1>
                <p class="text-muted fs-5"><?php echo $parfum->getMerek(); ?> | <?php echo $parfum->getKategori(); ?></p>
                <p><?php echo htmlspecialchars($parfum->getUkuran()); ?>ml | <?php echo htmlspecialchars($parfum->getGender()); ?></p>
                <p class="text-justify"><?php echo $parfum->getDeskripsi() ? htmlspecialchars($parfum->getDeskripsi()) : 'Deskripsi belum tersedia.'; ?></p>
                
                <div class="mt-4">
                    <h4>Beli Kuy!</h4>
                    <div class="btn-group">
                        <a href="https://shopee.co.id/" class="btn btn-shopee btn-sm shop-now-btn" target="_blank">Shopee</a>
                        
                        <a href="https://www.tokopedia.com/" class="btn btn-success btn-sm shop-now-btn" target="_blank">Tokopedia</a>
                        
                        <a href="https://www.tiktok.com/" class="btn btn-tiktok btn-sm" target="_blank">Tiktok Shop</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    
    <div class="container my-section-spacing related-scents-section">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Related Scents</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php
            $relatedParfums = $parfumManager->readRandom(4);
            if (count($relatedParfums) > 0):
                foreach ($relatedParfums as $p):
            ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <a href="detail.php?id=<?php echo $p->getId(); ?>" class="card-link">
                           <div class="card h-100 border-0">
                                <img src="<?php echo $p->getImagePath() ? htmlspecialchars($p->getImagePath()) : 'img/parfum_placeholder.png'; ?>" class="card-img-top product-card-img" alt="<?php echo htmlspecialchars($p->getNama()); ?>">
                                <div class="card-body text-center">
                                        <h6 class="card-title text-primary"><?php echo htmlspecialchars($p->getNama()); ?></h6>
                                        <p class="card-text"><?php echo htmlspecialchars($p->getUkuran()); ?>ml | <?php echo htmlspecialchars($p->getGender()); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
    
    <?php require_once 'views/footer.php'; ?>
