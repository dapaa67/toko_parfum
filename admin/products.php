<?php
require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');

require_once '../models/ParfumManager.php';

$parfumManager = new ParfumManager();
$pageTitle = "Kelola Produk";

$message = '';
$message_type = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? 'info';
    unset($_SESSION['message'], $_SESSION['message_type']);
}

// Fetch distinct categories for filter dropdown
$availableCategories = $parfumManager->getDistinctKategori();

// Logika untuk Filter dan Pagination
$q        = trim($_GET['q'] ?? '');
$kategori = $_GET['kategori'] ?? '';
$gender   = $_GET['gender'] ?? '';
$best     = isset($_GET['best']) ? '1' : '';
$sort_by  = $_GET['sort_by'] ?? '';
$perPage  = $_GET['per_page'] ?? 10;
$page     = max(1, (int)($_GET['page'] ?? 1));

// Ambil total produk yang cocok dengan filter
$totalFiltered = $parfumManager->countWithFilters($q, $kategori, $gender, '', $best);

// Hitung total halaman
$totalPages = ($perPage === 'all') ? 1 : max(1, (int)ceil($totalFiltered / (int)$perPage));
if ($page > $totalPages) {
    $page = $totalPages;
}

// Ambil data produk untuk halaman saat ini
$limit = ($perPage === 'all') ? $totalFiltered : (int)$perPage;
$parfums = $parfumManager->readPaginated($page, $limit, $q, $kategori, $gender, '', $best, $sort_by);

// Bangun query string untuk link pagination agar filter tidak hilang
$baseParams = http_build_query(array_filter([
    'q' => $q, 'kategori' => $kategori, 'gender' => $gender, 
    'best' => $best, 'sort_by' => $sort_by, 'per_page' => $perPage
]));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Admin ParfumMy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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
                        <i class="bi bi-person-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
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

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-1"><?php echo $pageTitle; ?></h1>
                    <p class="text-muted">Manage your product inventory, prices, and stock.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="product_form.php" class="btn btn-primary shadow-sm">
                        <i class="bi bi-plus-lg me-2"></i> Tambah Produk
                    </a>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show mb-4 shadow-sm" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i> <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Filter Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <form action="products.php" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="q" class="form-label fw-semibold text-muted small text-uppercase">Cari Produk</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 bg-light" id="q" name="q" placeholder="Nama, merek..." value="<?php echo htmlspecialchars($q); ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="kategori" class="form-label fw-semibold text-muted small text-uppercase">Kategori</label>
                                <select name="kategori" id="kategori" class="form-select bg-light">
                                    <option value="">Semua</option>
                                    <?php foreach ($availableCategories as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($kategori === $cat) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="gender" class="form-label fw-semibold text-muted small text-uppercase">Gender</label>
                                <select name="gender" id="gender" class="form-select bg-light">
                                    <option value="">Semua</option>
                                    <option value="Male" <?php echo ($gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="Unisex" <?php echo ($gender === 'Unisex') ? 'selected' : ''; ?>>Unisex</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="sort_by" class="form-label fw-semibold text-muted small text-uppercase">Urutkan Harga</label>
                                <select name="sort_by" id="sort_by" class="form-select bg-light">
                                    <option value="">Terbaru</option>
                                    <option value="price_asc" <?php echo ($sort_by === 'price_asc') ? 'selected' : ''; ?>>Murah ke Mahal</option>
                                    <option value="price_desc" <?php echo ($sort_by === 'price_desc') ? 'selected' : ''; ?>>Mahal ke Murah</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-center mb-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="best" name="best" value="1" <?php echo $best ? 'checked' : ''; ?>>
                                    <label class="form-check-label fw-semibold" for="best">Best Seller</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-dark w-100">Filter</button>
                                    <a href="products.php" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Table -->
            <div class="card table-card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Daftar Produk</h5>
                        <span class="badge bg-light text-dark border"><?php echo $totalFiltered; ?> Items</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th>Kategori</th>
                                <th>Gender</th>
                                <th style="min-width: 200px;">Harga & Stok</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($parfums)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <div class="py-4">
                                            <i class="bi bi-box-seam display-1 text-light mb-3"></i>
                                            <p class="mb-0">Tidak ada produk yang ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($parfums as $parfum): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-md bg-light rounded me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; flex-shrink: 0;">
                                                    <?php if ($parfum->getImagePath()): ?>
                                                        <img src="../<?php echo htmlspecialchars($parfum->getImagePath()); ?>" alt="" class="rounded" style="width: 100%; height: 100%; object-fit: cover;">
                                                    <?php else: ?>
                                                        <i class="bi bi-image text-muted"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($parfum->getNama()); ?></div>
                                                    <div class="small text-muted"><?php echo htmlspecialchars($parfum->getMerek()); ?></div>
                                                    <?php if ($parfum->getIsBestSeller()): ?>
                                                        <span class="badge bg-warning text-dark mt-1" style="font-size: 0.65rem;"><i class="bi bi-star-fill me-1"></i>Best Seller</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-secondary border"><?php echo htmlspecialchars($parfum->getKategori() ?: '-'); ?></span>
                                        </td>
                                        <td>
                                            <?php 
                                                $g = $parfum->getGender();
                                                $badgeClass = 'bg-secondary';
                                                if($g == 'Male') $badgeClass = 'bg-info';
                                                elseif($g == 'Female') $badgeClass = 'bg-danger';
                                                elseif($g == 'Unisex') $badgeClass = 'bg-success';
                                            ?>
                                            <span class="badge <?php echo $badgeClass; ?> bg-opacity-75 rounded-pill px-2"><?php echo htmlspecialchars($g); ?></span>
                                        </td>
                                        <td>
                                            <form action="process_product.php" method="POST" class="quick-update-form d-flex align-items-center gap-2">
                                                <input type="hidden" name="action" value="quick_update">
                                                <input type="hidden" name="id" value="<?php echo $parfum->getId(); ?>">
                                                <input type="hidden" name="harga" class="harga-value" value="<?php echo (int)$parfum->getHarga(); ?>">
                                                <input type="hidden" name="stok" class="stok-value" value="<?php echo (int)$parfum->getStok(); ?>">
                                                
                                                <div class="d-flex flex-column gap-2">
                                                    <!-- Harga Display -->
                                                    <div class="price-display-wrapper">
                                                        <div class="price-display px-2 py-1 bg-light rounded border" style="min-width: 120px; cursor: pointer;" title="Klik untuk edit">
                                                            <small class="text-muted d-block" style="font-size: 0.7rem;">Harga</small>
                                                            <div class="fw-medium">Rp <?php echo number_format($parfum->getHarga(), 0, ',', '.'); ?></div>
                                                        </div>
                                                        <input type="number" class="price-edit form-control form-control-sm d-none" placeholder="Harga">
                                                    </div>
                                                    
                                                    <!-- Stok Display -->
                                                    <div class="stock-display-wrapper">
                                                        <div class="stock-display px-2 py-1 bg-light rounded border" style="min-width: 120px; cursor: pointer;" title="Klik untuk edit">
                                                            <small class="text-muted d-block" style="font-size: 0.7rem;">Stok</small>
                                                            <div class="fw-medium"><?php echo number_format($parfum->getStok(), 0, ',', '.'); ?> pcs</div>
                                                        </div>
                                                        <input type="number" class="stock-edit form-control form-control-sm d-none" placeholder="Stok">
                                                    </div>
                                                </div>
                                                
                                                <button type="submit" class="btn btn-sm btn-success border shadow-sm submit-update d-none" title="Simpan Perubahan">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="product_form.php?id=<?php echo $parfum->getId(); ?>" class="btn btn-sm btn-light text-primary border hover-shadow" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-light text-danger border hover-shadow" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-id="<?php echo $parfum->getId(); ?>" data-delete-name="<?php echo htmlspecialchars($parfum->getNama()); ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="card-footer bg-white border-top-0 py-3">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link border-0" href="?page=<?php echo $page - 1; ?>&<?php echo $baseParams; ?>"><i class="bi bi-chevron-left"></i></a>
                            </li>
                            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link border-0 rounded-circle mx-1 <?php echo ($page == $i) ? 'bg-primary text-white shadow-sm' : 'text-muted'; ?>" href="?page=<?php echo $i; ?>&<?php echo $baseParams; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link border-0" href="?page=<?php echo $page + 1; ?>&<?php echo $baseParams; ?>"><i class="bi bi-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>Konfirmasi Hapus Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <p class="mb-2">Apakah Anda yakin ingin menghapus produk:</p>
                <p class="fw-bold text-dark mb-2" id="deleteProductName"></p>
                <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i> Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="../sidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Click-to-edit functionality for price and stock
    document.querySelectorAll('.quick-update-form').forEach(function(form) {
        const priceDisplay = form.querySelector('.price-display');
        const priceEdit = form.querySelector('.price-edit');
        const priceValue = form.querySelector('.harga-value');
        
        const stockDisplay = form.querySelector('.stock-display');
        const stockEdit = form.querySelector('.stock-edit');
        const stockValue = form.querySelector('.stok-value');
        
        const submitBtn = form.querySelector('.submit-update');
        
        // Price click to edit
        if (priceDisplay && priceEdit) {
            priceDisplay.addEventListener('click', function() {
                priceEdit.value = priceValue.value;
                priceDisplay.classList.add('d-none');
                priceEdit.classList.remove('d-none');
                priceEdit.focus();
                submitBtn.classList.remove('d-none');
            });
            
            priceEdit.addEventListener('blur', function() {
                setTimeout(function() {
                    const newValue = parseInt(priceEdit.value) || 0;
                    priceValue.value = newValue;
                    priceDisplay.querySelector('.fw-medium').textContent = 'Rp ' + newValue.toLocaleString('id-ID');
                    priceEdit.classList.add('d-none');
                    priceDisplay.classList.remove('d-none');
                }, 200);
            });
        }
        
        // Stock click to edit
        if (stockDisplay && stockEdit) {
            stockDisplay.addEventListener('click', function() {
                stockEdit.value = stockValue.value;
                stockDisplay.classList.add('d-none');
                stockEdit.classList.remove('d-none');
                stockEdit.focus();
                submitBtn.classList.remove('d-none');
            });
            
            stockEdit.addEventListener('blur', function() {
                setTimeout(function() {
                    const newValue = parseInt(stockEdit.value) || 0;
                    stockValue.value = newValue;
                    stockDisplay.querySelector('.fw-medium').textContent = newValue.toLocaleString('id-ID') + ' pcs';
                    stockEdit.classList.add('d-none');
                    stockDisplay.classList.remove('d-none');
                }, 200);
            });
        }
        
        // Hide submit button if both are back to display mode
        form.addEventListener('click', function(e) {
            if (!priceEdit.classList.contains('d-none') || !stockEdit.classList.contains('d-none')) {
                return;
            }
            if (e.target !== submitBtn && !submitBtn.contains(e.target)) {
                submitBtn.classList.add('d-none');
            }
        });
    });
    
    
    // Delete confirmation modal
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const deleteId = button.getAttribute('data-delete-id');
            const deleteName = button.getAttribute('data-delete-name');
            
            document.getElementById('deleteProductName').textContent = deleteName;
            document.getElementById('confirmDeleteBtn').href = `process_product.php?action=delete&id=${deleteId}`;
        });
    }
});
</script>
</body>
</html>

