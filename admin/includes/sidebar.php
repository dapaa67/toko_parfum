<?php
// Determine active page for sidebar highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar (Bootstrap 5) -->
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse">
  <div class="d-flex flex-column h-100">
    <!-- Main Menu -->
    <div class="flex-grow-1">
      <ul class="nav flex-column px-2 pt-2">
        <li class="nav-item">
          <a class="nav-link <?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($currentPage == 'products.php' || $currentPage == 'product_form.php') ? 'active' : ''; ?>" href="products.php">
            <i class="bi bi-box-seam"></i> Produk
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($currentPage == 'carousel.php') ? 'active' : ''; ?>" href="carousel.php">
            <i class="bi bi-images"></i> Banner
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($currentPage == 'sales_report.php') ? 'active' : ''; ?>" href="sales_report.php">
            <i class="bi bi-bar-chart-line"></i> Penjualan
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($currentPage == 'users.php') ? 'active' : ''; ?>" href="users.php">
            <i class="bi bi-people"></i> User
          </a>
        </li>
      </ul>
    </div>
    
    <!-- Bottom Menu -->
    <div class="mt-auto">
      <ul class="nav flex-column px-2 pb-3">
        <li class="nav-item">
          <a class="nav-link" href="../index.php" target="_blank">
            <i class="bi bi-shop-window"></i> Lihat Toko
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="../logout.php">
            <i class="bi bi-box-arrow-right"></i> Keluar
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
