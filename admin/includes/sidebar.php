<?php
// Determine active page for sidebar highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar (Bootstrap 5) -->
<nav class="col-md-3 col-lg-2 d-none d-md-block admin-sidebar">
  <div class="position-sticky pt-3" style="top: 56px;">
    <!-- Header with brand and collapsible toggle -->
    <div class="sidebar-header d-flex align-items-center justify-content-between px-3 pb-2">
      <a class="brand d-flex align-items-center gap-2 text-decoration-none" href="dashboard.php">
        <i class="bi bi-flower2"></i>
        <span class="brand-text">Admin</span>
      </a>
      <button type="button" class="btn btn-sm sidebar-toggle" id="sidebarToggle" data-sidebar-toggle aria-label="Toggle sidebar">
        <i class="bi bi-chevron-double-left"></i>
      </button>
    </div>

    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center gap-2 <?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php" aria-current="<?php echo ($currentPage == 'dashboard.php') ? 'page' : 'false'; ?>">
          <i class="bi bi-speedometer2"></i><span class="item-text">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center gap-2 <?php echo ($currentPage == 'carousel.php') ? 'active' : ''; ?>" href="carousel.php" aria-current="<?php echo ($currentPage == 'carousel.php') ? 'page' : 'false'; ?>">
          <i class="bi bi-images"></i><span class="item-text">Banner</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center gap-2 <?php echo ($currentPage == 'products.php') ? 'active' : ''; ?>" href="products.php" aria-current="<?php echo ($currentPage == 'products.php') ? 'page' : 'false'; ?>">
          <i class="bi bi-box-seam"></i><span class="item-text">Produk</span>
        </a>
      </li>
      <li class="nav-item mt-2">
        <a class="nav-link d-flex align-items-center gap-2" href="../index.php">
          <i class="bi bi-shop-window"></i><span class="item-text">Lihat Toko</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center gap-2" href="../logout.php">
          <i class="bi bi-box-arrow-right"></i><span class="item-text">Logout</span>
        </a>
      </li>
    </ul>
  </div>
</nav>