<?php
// Pastikan path ke models/AuthManager.php sudah benar
require_once '../models/AuthManager.php';
require_once '../models/ParfumManager.php';

// FUNGSI OOP PENTING: Cek apakah pengguna adalah admin. Jika tidak, redirect ke login.
AuthManager::checkRole('admin');

$parfumManager = new ParfumManager();
// Panggil method readAll() dari Class ParfumManager (R dari CRUD)
$parfums = $parfumManager->readAll(); 

// Untuk menampilkan pesan sukses/gagal dari process.php
$message = '';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ParfumMy</title>
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

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <?php if (!empty($message)): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Hero Section -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-1">Dashboard Overview</h1>
                    <p class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>! Here's what's happening today.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                    </div>
                </div>
            </div>

            <?php
            // Quick analytics
            $totalProducts = is_array($parfums) ? count($parfums) : 0;
            $men = $women = $unisex = $others = 0;
            if ($totalProducts > 0) {
                foreach ($parfums as $p) {
                    $g = trim($p->getGender());
                    if ($g === 'Male') {
                        $men++;
                    } elseif ($g === 'Female') {
                        $women++;
                    } elseif ($g === 'Unisex') {
                        $unisex++;
                    } else {
                        $others++;
                    }
                }
            }
            $latest = array_slice($parfums ?? [], 0, 6);
            ?>

            <!-- Stat cards -->
            <div class="row g-4 mb-5">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card h-100 p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="text-muted mb-1 text-uppercase small fw-bold">Total Products</h6>
                                <h2 class="mb-0 fw-bold display-6"><?php echo number_format($totalProducts); ?></h2>
                            </div>
                            <div class="icon-box bg-primary-soft">
                                <i class="bi bi-collection"></i>
                            </div>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 3px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card h-100 p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="text-muted mb-1 text-uppercase small fw-bold">Male Perfumes</h6>
                                <h2 class="mb-0 fw-bold display-6"><?php echo number_format($men); ?></h2>
                            </div>
                            <div class="icon-box bg-info-soft">
                                <i class="bi bi-gender-male"></i>
                            </div>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 3px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $totalProducts > 0 ? ($men/$totalProducts)*100 : 0; ?>%" aria-valuenow="<?php echo $men; ?>" aria-valuemin="0" aria-valuemax="<?php echo $totalProducts; ?>"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card h-100 p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="text-muted mb-1 text-uppercase small fw-bold">Female Perfumes</h6>
                                <h2 class="mb-0 fw-bold display-6"><?php echo number_format($women); ?></h2>
                            </div>
                            <div class="icon-box bg-pink-soft">
                                <i class="bi bi-gender-female"></i>
                            </div>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 3px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $totalProducts > 0 ? ($women/$totalProducts)*100 : 0; ?>%" aria-valuenow="<?php echo $women; ?>" aria-valuemin="0" aria-valuemax="<?php echo $totalProducts; ?>"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card h-100 p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="text-muted mb-1 text-uppercase small fw-bold">Unisex Perfumes</h6>
                                <h2 class="mb-0 fw-bold display-6"><?php echo number_format($unisex); ?></h2>
                            </div>
                            <div class="icon-box bg-success-soft">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 3px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $totalProducts > 0 ? ($unisex/$totalProducts)*100 : 0; ?>%" aria-valuenow="<?php echo $unisex; ?>" aria-valuemin="0" aria-valuemax="<?php echo $totalProducts; ?>"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick actions & Latest Products -->
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card table-card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0 fw-bold">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <a href="products.php" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 gap-2">
                                        <i class="bi bi-box-seam fs-3"></i>
                                        <span>Products</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="carousel.php" class="btn btn-outline-dark w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 gap-2">
                                        <i class="bi bi-images fs-3"></i>
                                        <span>Carousel</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="sales_report.php" class="btn btn-outline-dark w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 gap-2">
                                        <i class="bi bi-bar-chart-line fs-3"></i>
                                        <span>Reports</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="../index.php" target="_blank" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 gap-2">
                                        <i class="bi bi-shop-window fs-3"></i>
                                        <span>Store</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="card table-card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold">Latest Products</h5>
                            <a href="products.php" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th style="width:60px;">#</th>
                                    <th>Name</th>
                                    <th>Size</th>
                                    <th>Gender</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($latest)): $i = 1;
                                    foreach ($latest as $p): ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-light rounded me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                                        <?php if ($p->getImagePath()): ?>
                                                            <img src="../<?php echo htmlspecialchars($p->getImagePath()); ?>" alt="" class="rounded" style="width: 100%; height: 100%; object-fit: cover;">
                                                        <?php else: ?>
                                                            <i class="bi bi-image text-muted"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <span class="fw-semibold text-dark"><?php echo htmlspecialchars($p->getNama()); ?></span>
                                                </div>
                                            </td>
                                            <td><span class="text-muted"><?php echo htmlspecialchars($p->getUkuran()); ?> ml</span></td>
                                            <td>
                                                <?php 
                                                    $g = $p->getGender();
                                                    $badgeClass = 'bg-secondary';
                                                    if($g == 'Male') $badgeClass = 'bg-info';
                                                    elseif($g == 'Female') $badgeClass = 'bg-danger';
                                                    elseif($g == 'Unisex') $badgeClass = 'bg-success';
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?> bg-opacity-75 rounded-pill px-3"><?php echo htmlspecialchars($g); ?></span>
                                            </td>
                                            <td>
                                                <a href="product_form.php?id=<?php echo $p->getId(); ?>" class="btn btn-sm btn-light text-primary hover-shadow"><i class="bi bi-pencil-square"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="bi bi-box-seam display-4 d-block mb-3 opacity-50"></i>
                                            No products found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="../sidebar.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>