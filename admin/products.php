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

// Logika untuk Filter dan Pagination
$q        = trim($_GET['q'] ?? '');
$kategori = $_GET['kategori'] ?? '';
$gender   = $_GET['gender'] ?? '';
$best     = isset($_GET['best']) ? '1' : '';
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
$parfums = $parfumManager->readPaginated($page, $limit, $q, $kategori, $gender, '', $best);

// Bangun query string untuk link pagination agar filter tidak hilang
$baseParams = http_build_query(array_filter([
    'q' => $q, 'kategori' => $kategori, 'gender' => $gender, 
    'best' => $best, 'per_page' => $perPage
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
                                    <?php $kategoriOptions = ['Floral','Fresh','Oriental','Woody','Citrus','Gourmand']; ?>
                                    <?php foreach ($kategoriOptions as $opt): ?>
                                        <option value="<?php echo $opt; ?>" <?php echo ($kategori === $opt) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
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
                                            <form action="process_product.php" method="POST" class="d-flex align-items-center gap-2">
                                                <input type="hidden" name="action" value="quick_update">
                                                <input type="hidden" name="id" value="<?php echo $parfum->getId(); ?>">
                                                <div class="d-flex flex-column gap-1">
                                                    <div class="input-group input-group-sm" style="width: 140px;">
                                                        <span class="input-group-text bg-light border-end-0 text-muted">Rp</span>
                                                        <input type="number" name="harga" class="form-control border-start-0" value="<?php echo (int)$parfum->getHarga(); ?>">
                                                    </div>
                                                    <div class="input-group input-group-sm" style="width: 140px;">
                                                        <input type="number" name="stok" class="form-control border-end-0" value="<?php echo (int)$parfum->getStok(); ?>">
                                                        <span class="input-group-text bg-light border-start-0 text-muted">pcs</span>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-light text-success border hover-shadow h-100" title="Simpan Perubahan">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="product_form.php?id=<?php echo $parfum->getId(); ?>" class="btn btn-sm btn-light text-primary border hover-shadow" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="process_product.php?action=delete&id=<?php echo $parfum->getId(); ?>" class="btn btn-sm btn-light text-danger border hover-shadow" title="Hapus" onclick="return confirm('Anda yakin ingin menghapus produk ini?');">
                                                    <i class="bi bi-trash"></i>
                                                </a>
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

<?php include 'includes/footer.php'; ?>
<script src="../sidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
