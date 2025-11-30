<?php
require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');

require_once '../models/ParfumManager.php';
require_once '../models/Parfum.php';

$parfumManager = new ParfumManager();
$parfum = new Parfum();
$pageTitle = "Tambah Produk Baru";
$isEditMode = false;

if (isset($_GET['id'])) {
    $parfum = $parfumManager->readById((int)$_GET['id']);
    if ($parfum) {
        $pageTitle = "Edit Produk: " . htmlspecialchars($parfum->getNama());
        $isEditMode = true;
    } else {
        // Jika ID tidak ditemukan, redirect atau tampilkan error
        $_SESSION['message'] = 'Produk tidak ditemukan.';
        $_SESSION['message_type'] = 'danger';
        header('Location: products.php');
        exit();
    }
}

// Fetch distinct categories for datalist
$existingCategories = $parfumManager->getDistinctKategori();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Admin</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <i class="bi bi-flower2 me-2 text-warning"></i>
            <span class="fw-bold">ParfumMy Admin</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i> Admin
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../index.php" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i>View Site</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo $pageTitle; ?></h1>
                <a href="products.php" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Produk
                </a>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form action="process_product.php" method="POST" enctype="multipart/form-data">
                        <?php if ($isEditMode): ?>
                            <input type="hidden" name="id" value="<?php echo $parfum->getId(); ?>">
                        <?php endif; ?>
                        <input type="hidden" name="action" value="<?php echo $isEditMode ? 'update' : 'create'; ?>">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($parfum->getNama() ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="merek" class="form-label">Merek</label>
                                <input type="text" class="form-control" id="merek" name="merek" value="<?php echo htmlspecialchars($parfum->getMerek() ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="kategori" class="form-label">Kategori</label>
                                <input type="text" class="form-control" id="kategori" name="kategori" 
                                       list="kategori-suggestions" 
                                       placeholder="Pilih atau ketik kategori"
                                       value="<?php echo htmlspecialchars($parfum->getKategori() ?? ''); ?>">
                                <datalist id="kategori-suggestions">
                                    <?php foreach ($existingCategories as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat); ?>">
                                    <?php endforeach; ?>
                                </datalist>
                                <small class="text-muted">Pilih dari daftar atau ketik kategori baru</small>
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="Male" <?php echo ($parfum->getGender() ?? '') == 'Male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($parfum->getGender() ?? '') == 'Female' ? 'selected' : ''; ?>>Female</option>
                                    <option value="Unisex" <?php echo ($parfum->getGender() ?? '') == 'Unisex' ? 'selected' : ''; ?>>Unisex</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="harga" class="form-label">Harga (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="harga" name="harga" value="<?php echo (int)($parfum->getHarga() ?? 0); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="stok" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="stok" name="stok" value="<?php echo (int)($parfum->getStok() ?? 0); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="ukuran" class="form-label">Ukuran (ml)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="ukuran" name="ukuran" value="<?php echo (int)($parfum->getUkuran() ?? 0); ?>" required>
                                    <span class="input-group-text">ml</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?php echo htmlspecialchars($parfum->getDeskripsi() ?? ''); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="image" class="form-label">Gambar Produk</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <?php if ($isEditMode && $parfum->getImagePath()): ?>
                                    <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                                        <small class="text-muted d-block mb-1">Gambar Saat Ini:</small>
                                        <img src="../<?php echo htmlspecialchars($parfum->getImagePath()); ?>" alt="Current Image" style="max-width: 150px; max-height: 150px; object-fit: cover;" class="rounded">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-4 pt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_best_seller" name="is_best_seller" value="1" <?php echo ($parfum->getIsBestSeller() ?? 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_best_seller">Jadikan Best Seller</label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="products.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../sidebar.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Number formatting for price and stock inputs
    const hargaInput = document.getElementById('harga');
    const stokInput = document.getElementById('stok');
    
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    
    function addNumberFormatting(input) {
        if (!input) return;
        
        const wrapper = document.createElement('div');
        wrapper.style.position = 'relative';
        wrapper.style.flex = '1';
        
        const overlay = document.createElement('div');
        overlay.className = 'form-control';
        overlay.style.position = 'absolute';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.right = '0';
        overlay.style.bottom = '0';
        overlay.style.pointerEvents = 'none';
        overlay.style.backgroundColor = 'white';
        overlay.style.zIndex = '1';
        overlay.textContent = formatNumber(parseInt(input.value) || 0);
        
        // Insert wrapper
        const parent = input.parentNode;
        parent.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        wrapper.appendChild(overlay);
        
        // Hide overlay on focus
        input.addEventListener('focus', function() {
            overlay.style.display = 'none';
        });
        
        // Show overlay on blur with updated value
        input.addEventListener('blur', function() {
            const val = parseInt(input.value) || 0;
            overlay.textContent = formatNumber(val);
            overlay.style.display = 'block';
        });
        
        // Update on change
        input.addEventListener('change', function() {
            const val = parseInt(input.value) || 0;
            overlay.textContent = formatNumber(val);
        });
    }
    
    addNumberFormatting(hargaInput);
    addNumberFormatting(stokInput);
});
</script>
</body>
</html>

