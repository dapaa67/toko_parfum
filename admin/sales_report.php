<?php
require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');
require_once '../models/OrderManager.php';
$orderManager = new OrderManager();
// Ambil filter tanggal (optional)
$startDate = isset($_GET['start_date']) && $_GET['start_date'] !== '' ? $_GET['start_date'] : null;
$endDate   = isset($_GET['end_date']) && $_GET['end_date'] !== '' ? $_GET['end_date'] : null;
// Pagination untuk Recent Orders
$ordersPerPage = 10;
$ordersPage = isset($_GET['orders_page']) ? max(1, intval($_GET['orders_page'])) : 1;
// Pagination untuk Top Selling Products
$productsPerPage = 5;
$productsPage = isset($_GET['products_page']) ? max(1, intval($_GET['products_page'])) : 1;
// Ambil statistik & daftar pesanan dengan filter tanggal (jika ada)
$stats = $orderManager->getSalesStatistics($startDate, $endDate);
$allOrdersFull = $orderManager->getAllOrdersWithUserDetails($startDate, $endDate);
// Hitung total orders dan pagination
$totalOrders = count($allOrdersFull);
$totalOrdersPages = ceil($totalOrders / $ordersPerPage);
$ordersOffset = ($ordersPage - 1) * $ordersPerPage;
$allOrders = array_slice($allOrdersFull, $ordersOffset, $ordersPerPage);
// Ambil produk terlaris (semua)
$topProductsFull = $orderManager->getTopSellingProducts($startDate, $endDate);
// Hitung total products dan pagination
$totalProducts = count($topProductsFull);
$totalProductsPages = ceil($totalProducts / $productsPerPage);
$productsOffset = ($productsPage - 1) * $productsPerPage;
$topProducts = array_slice($topProductsFull, $productsOffset, $productsPerPage);
$pageTitle = "Laporan Penjualan";
// Handle alerts
$successMsg = $_GET['success'] ?? '';
$errorMsg = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Admin ParfumMy</title>
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
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <?php if ($successMsg): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($successMsg); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if ($errorMsg): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($errorMsg); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-1"><?php echo $pageTitle; ?></h1>
                    <p class="text-muted">Pantau kinerja penjualan dan kelola pesanan.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <?php
                    $exportParams = [];
                    if ($startDate) $exportParams['start_date'] = $startDate;
                    if ($endDate) $exportParams['end_date'] = $endDate;
                    $exportUrl = 'export_sales_report.php' . (!empty($exportParams) ? '?' . http_build_query($exportParams) : '');
                    ?>
                    <a href="<?php echo $exportUrl; ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-download me-1"></i> Export CSV
                    </a>
                </div>
            </div>
            <!-- Filter Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <form method="get" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label fw-medium text-muted small text-uppercase">Tanggal Mulai</label>
                            <input type="date" id="start_date" name="start_date" class="form-control"
                                   value="<?php echo htmlspecialchars($startDate ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label fw-medium text-muted small text-uppercase">Tanggal Akhir</label>
                            <input type="date" id="end_date" name="end_date" class="form-control"
                                   value="<?php echo htmlspecialchars($endDate ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-funnel me-1"></i> Filter
                                </button>
                                <a href="sales_report.php" class="btn btn-outline-secondary">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Stat Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="stat-card h-100 p-4 bg-white rounded shadow-sm border-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1 text-uppercase small fw-bold">Total Pendapatan</h6>
                                <h2 class="mb-0 fw-bold display-6 text-success">Rp <?php echo number_format($stats['total_revenue'], 0, ',', '.'); ?></h2>
                            </div>
                            <div class="icon-box bg-success-soft rounded-circle p-3">
                                <i class="bi bi-wallet2 fs-3 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card h-100 p-4 bg-white rounded shadow-sm border-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1 text-uppercase small fw-bold">Total Pesanan</h6>
                                <h2 class="mb-0 fw-bold display-6 text-primary"><?php echo number_format($stats['total_orders']); ?></h2>
                            </div>
                            <div class="icon-box bg-primary-soft rounded-circle p-3">
                                <i class="bi bi-receipt fs-3 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <!-- Top Selling Products -->
                <div class="col-lg-5">
                    <div class="card table-card h-100 border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0 fw-bold">Produk Terlaris</h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($topProducts)): ?>
                                <div class="p-4 text-center text-muted">
                                    <i class="bi bi-box-seam display-4 d-block mb-3 opacity-50"></i>
                                    Tidak ada data penjualan untuk periode ini.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">Produk</th>
                                                <th class="text-end">Terjual</th>
                                                <th class="text-end pe-4">Pendapatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($topProducts as $prod): ?>
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-light rounded me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                                                <?php if (!empty($prod['image_path'])): ?>
                                                                    <img src="../<?php echo htmlspecialchars($prod['image_path']); ?>" alt="" class="rounded" style="width: 100%; height: 100%; object-fit: cover;">
                                                                <?php else: ?>
                                                                    <i class="bi bi-image text-muted"></i>
                                                                <?php endif; ?>
                                                            </div>
                                                            <span class="fw-semibold text-dark"><?php echo htmlspecialchars($prod['nama']); ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="text-end fw-medium"><?php echo number_format($prod['total_quantity']); ?></td>
                                                    <td class="text-end pe-4 fw-medium">Rp <?php echo number_format($prod['total_revenue'], 0, ',', '.'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php if ($totalProductsPages > 1): ?>
                                    <div class="card-footer bg-white border-top py-3">
                                        <nav aria-label="Navigasi halaman produk">
                                            <ul class="pagination pagination-sm justify-content-center mb-0">
                                                <?php if ($productsPage > 1): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['products_page' => $productsPage - 1])); ?>">
                                                            <i class="bi bi-chevron-left"></i>
                                                        </a>
                                                    </li>
                                                <?php else: ?>
                                                    <li class="page-item disabled">
                                                        <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                                                    </li>
                                                <?php endif; ?>
                                                
                                                <?php for ($i = 1; $i <= $totalProductsPages; $i++): ?>
                                                    <li class="page-item <?php echo $i === $productsPage ? 'active' : ''; ?>">
                                                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['products_page' => $i])); ?>"><?php echo $i; ?></a>
                                                    </li>
                                                <?php endfor; ?>
                                                
                                                <?php if ($productsPage < $totalProductsPages): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['products_page' => $productsPage + 1])); ?>">
                                                            <i class="bi bi-chevron-right"></i>
                                                        </a>
                                                    </li>
                                                <?php else: ?>
                                                    <li class="page-item disabled">
                                                        <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </nav>
                                    </div>
                                <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- Orders Table -->
                <div class="col-lg-7">
                    <div class="card table-card h-100 border-0 shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold">Pesanan Terbaru</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">ID Pesanan</th>
                                            <th>Pelanggan</th>
                                            <th>Tanggal</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th class="text-end pe-4">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($allOrders)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-5">
                                                    <i class="bi bi-inbox display-4 d-block mb-3 opacity-50"></i>
                                                    Tidak ada pesanan ditemukan.
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($allOrders as $order): ?>
                                                <?php
                                                    $displayNo = !empty($order['nomor_pesanan'] ?? null)
                                                        ? $order['nomor_pesanan']
                                                        : '#' . $order['id'];
                                                    
                                                    $statusClass = 'bg-secondary';
                                                    if ($order['status'] === 'Pending') $statusClass = 'bg-warning text-dark';
                                                    elseif ($order['status'] === 'Selesai') $statusClass = 'bg-success';
                                                    elseif ($order['status'] === 'Dibatalkan') $statusClass = 'bg-danger';
                                                    elseif ($order['status'] === 'Menunggu Konfirmasi') $statusClass = 'bg-info text-dark';
                                                ?>
                                                <tr>
                                                    <td class="ps-4"><span class="font-monospace fw-bold text-primary small"><?php echo htmlspecialchars($displayNo); ?></span></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs bg-light rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                                                <i class="bi bi-person text-muted" style="font-size: 0.8rem;"></i>
                                                            </div>
                                                            <span><?php echo htmlspecialchars($order['username']); ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="text-muted small"><?php echo date('d M, H:i', strtotime($order['tanggal_pesanan'])); ?></td>
                                                    <td class="fw-medium">Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></td>
                                                    <td>
                                                        <span class="badge <?php echo $statusClass; ?> bg-opacity-75 rounded-pill px-2"><?php echo htmlspecialchars($order['status']); ?></span>
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <button class="btn btn-sm btn-light text-primary hover-shadow view-details-btn" data-order-id="<?php echo $order['id']; ?>" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <?php if ($totalOrdersPages > 1): ?>
                                <div class="card-footer bg-white border-top py-3">
                                    <nav aria-label="Navigasi halaman pesanan">
                                        <ul class="pagination pagination-sm justify-content-center mb-0">
                                            <?php if ($ordersPage > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['orders_page' => $ordersPage - 1])); ?>">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                                                </li>
                                            <?php endif; ?>
                                            
                                            <?php for ($i = 1; $i <= $totalOrdersPages; $i++): ?>
                                                <li class="page-item <?php echo $i === $ordersPage ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['orders_page' => $i])); ?>"><?php echo $i; ?></a>
                                                </li>
                                            <?php endfor; ?>
                                            
                                            <?php if ($ordersPage < $totalOrdersPages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['orders_page' => $ordersPage + 1])); ?>">
                                                        <i class="bi bi-chevron-right"></i>
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                </div>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<!-- Modal untuk Detail Pesanan -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="orderDetailsModalLabel">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-4" id="modal-body-content">
                <!-- Konten detail akan dimuat di sini via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../sidebar.js"></script>
<script src="../js/sales_report.js"></script>
<?php include 'includes/payment_modals.php'; ?>
</body>
</html>
