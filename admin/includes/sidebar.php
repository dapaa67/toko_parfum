<?php
// Determine active page for sidebar highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar (Bootstrap 5) -->
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse">
  <div class="position-sticky pt-3">
    <div class="sidebar-header px-3 pb-3 mb-3 border-bottom border-secondary">
      <span class="text-uppercase text-muted small fw-bold">Menu</span>
    </div>

    <ul class="nav flex-column px-2">
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
          <i class="bi bi-speedometer2"></i> Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'products.php' || $currentPage == 'product_form.php') ? 'active' : ''; ?>" href="products.php">
          <i class="bi bi-box-seam"></i> Products
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'carousel.php') ? 'active' : ''; ?>" href="carousel.php">
          <i class="bi bi-images"></i> Banner / Carousel
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'sales_report.php') ? 'active' : ''; ?>" href="sales_report.php">
          <i class="bi bi-bar-chart-line"></i> Sales Report
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'users.php') ? 'active' : ''; ?>" href="users.php">
          <i class="bi bi-people"></i> Users
        </a>
      </li>
    </ul>

    <div class="sidebar-heading px-3 mt-4 mb-2 text-uppercase text-muted small fw-bold">
      <span>Links</span>
    </div>
    <ul class="nav flex-column px-2 mb-auto">
      <li class="nav-item">
        <a class="nav-link" href="../index.php" target="_blank">
          <i class="bi bi-shop-window"></i> View Store
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-danger" href="../logout.php">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      </li>
    </ul>
  </div>
</nav>