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
    <title>Admin Dashboard</title>
    <!-- Bootstrap 5.3.3 and Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../sidebar.css">
</head>
<body>
<nav class="navbar navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">ParfumMy Admin</span>
        <div class="d-flex align-items-center">
            <a href="../index.php" class="btn btn-sm btn-outline-light me-2">
                <i class="bi bi-box-arrow-up-right me-1"></i> View Site
            </a>
            <a href="../logout.php" class="btn btn-sm btn-warning text-dark">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main content -->
    <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <?php if (!empty($message)): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="bi bi-check-circle me-1"></i> <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

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

      <div class="d-flex flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3 mb-0">Welcome, Admin</h1>
      </div>

      <!-- Stat cards -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 bg-primary-subtle text-primary p-3 me-3">
                                <i class="bi bi-collection fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Total Products</div>
                                <div class="fs-4 fw-semibold"><?php echo number_format($totalProducts); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 bg-info-subtle text-info p-3 me-3">
                                <i class="bi bi-gender-male fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Male</div>
                                <div class="fs-4 fw-semibold"><?php echo number_format($men); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 bg-pink p-3 me-3" style="background-color:#ffe3ec!important;color:#d63384;">
                                <i class="bi bi-gender-female fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Female</div>
                                <div class="fs-4 fw-semibold"><?php echo number_format($women); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 bg-success-subtle text-success p-3 me-3">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Unisex</div>
                                <div class="fs-4 fw-semibold"><?php echo number_format($unisex); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <!-- Quick actions -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Quick Actions</h5>
                <a href="products.php" class="btn btn-primary me-2 mb-2">
                    <i class="bi bi-box-seam me-1"></i> Manage Products
                </a>
                <a href="carousel.php" class="btn btn-outline-secondary me-2 mb-2">
                    <i class="bi bi-images me-1"></i> Manage Carousel
                </a>
                <a href="../index.php" class="btn btn-outline-dark mb-2">
                    <i class="bi bi-shop-window me-1"></i> View Storefront
                </a>
            </div>
        </div>

      <!-- Latest products -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Latest Products</h5>
                    <a href="products.php" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-right-circle me-1"></i> View All
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Gender</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($latest)): $i = 1;
                            foreach ($latest as $p): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($p->getNama()); ?></td>
                                    <td><?php echo htmlspecialchars($p->getUkuran()); ?> ml</td>
                                    <td>
                                        <span class="badge text-bg-secondary"><?php echo htmlspecialchars($p->getGender()); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No products found.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
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