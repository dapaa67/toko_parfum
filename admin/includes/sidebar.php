<?php
// sidebar.php - Admin sidebar navigation
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="w-64 bg-white border-r border-gray-200 min-h-screen fixed left-0 top-0 hidden md:flex flex-col z-40 overflow-y-auto shadow-sm">
    <!-- Brand -->
    <div class="p-6 border-b border-gray-100" style="background: linear-gradient(135deg, rgba(212,175,55,0.05) 0%, rgba(212,175,55,0.02) 100%);">
        <a href="dashboard.php" class="flex items-center gap-3 group no-underline">
            <div style="width: 2.5rem; height: 2.5rem; background: linear-gradient(135deg, #D4AF37 0%, #B5952F 100%); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px rgba(212,175,55,0.2); transition: all 0.2s;"
                 onmouseover="this.style.transform='scale(1.05)';"
                 onmouseout="this.style.transform='scale(1)';">
                <i class="bi bi-box-seam" style="color: white; font-size: 1.125rem;"></i>
            </div>
            <div>
                <h2 style="font-family: Georgia, serif; font-weight: 700; font-size: 1.125rem; color: #1A1A1A; line-height: 1.2; margin: 0;">ParfumMY</h2>
                <p style="font-size: 0.625rem; color: #9CA3AF; line-height: 1.2; margin: 0;">Admin Panel</p>
            </div>
        </a>
    </div>
    
    <!-- Main Menu -->
    <div class="flex-1 py-4 overflow-y-auto">
        <div class="px-3 mb-2">
            <p style="font-size: 0.625rem; text-transform: uppercase; color: #9CA3AF; font-weight: 700; letter-spacing: 0.05em; padding: 0 0.5rem;">Menu Utama</p>
        </div>
        <ul class="space-y-1 px-2">
            <li>
                <a href="dashboard.php" 
                   style="display: flex; align-items: center; padding: 0.625rem 0.75rem; border-radius: 0.5rem; transition: all 0.2s; text-decoration: none; <?php echo $current_page == 'dashboard.php' ? 'background-color: rgba(212,175,55,0.1); color: #D4AF37; font-weight: 600;' : 'color: #6B7280;'; ?>"
                   onmouseover="if(this.style.backgroundColor !== 'rgba(212, 175, 55, 0.1)') { this.style.backgroundColor='#F9FAFB'; this.style.color='#D4AF37'; }"
                   onmouseout="if(this.style.backgroundColor !== 'rgba(212, 175, 55, 0.1)') { this.style.backgroundColor='transparent'; this.style.color='#6B7280'; }">
                    <i class="bi bi-speedometer2" style="margin-right: 0.625rem; font-size: 1rem; color: <?php echo $current_page == 'dashboard.php' ? '#D4AF37' : '#9CA3AF'; ?>;"></i>
                    <span style="font-size: 0.875rem;">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="products.php" 
                   style="display: flex; align-items: center; padding: 0.625rem 0.75rem; border-radius: 0.5rem; transition: all 0.2s; text-decoration: none; <?php echo $current_page == 'products.php' || $current_page == 'product_form.php' ? 'background-color: rgba(212,175,55,0.1); color: #D4AF37; font-weight: 600;' : 'color: #6B7280;'; ?>"
                   onmouseover="if(this.style.backgroundColor !== 'rgba(212, 175, 55, 0.1)') { this.style.backgroundColor='#F9FAFB'; this.style.color='#D4AF37'; }"
                   onmouseout="if(this.style.backgroundColor !== 'rgba(212, 175, 55, 0.1)') { this.style.backgroundColor='transparent'; this.style.color='#6B7280'; }">
                    <i class="bi bi-box-seam" style="margin-right: 0.625rem; font-size: 1rem; color: <?php echo $current_page == 'products.php' || $current_page == 'product_form.php' ? '#D4AF37' : '#9CA3AF'; ?>;"></i>
                    <span style="font-size: 0.875rem;">Produk</span>
                </a>
            </li>
            <li>
                <a href="users.php" 
                   style="display: flex; align-items: center; padding: 0.625rem 0.75rem; border-radius: 0.5rem; transition: all 0.2s; text-decoration: none; <?php echo $current_page == 'users.php' ? 'background-color: rgba(212,175,55,0.1); color: #D4AF37; font-weight: 600;' : 'color: #6B7280;'; ?>"
                   onmouseover="if(this.style.backgroundColor !== 'rgba(212, 175, 55, 0.1)') { this.style.backgroundColor='#F9FAFB'; this.style.color='#D4AF37'; }"
                   onmouseout="if(this.style.backgroundColor !== 'rgba(212, 175, 55, 0.1)') { this.style.backgroundColor='transparent'; this.style.color='#6B7280'; }">
                    <i class="bi bi-people" style="margin-right: 0.625rem; font-size: 1rem; color: <?php echo $current_page == 'users.php' ? '#D4AF37' : '#9CA3AF'; ?>;"></i>
                    <span style="font-size: 0.875rem;">Pengguna</span>
                </a>
            </li>
            <li>
                <a href="sales_report.php" 
                   style="display: flex; align-items: center; padding: 0.625rem 0.75rem; border-radius: 0.5rem; transition: all 0.2s; text-decoration: none; <?php echo $current_page == 'sales_report.php' ? 'background-color: rgba(212,175,55,0.1); color: #D4AF37; font-weight: 600;' : 'color: #6B7280;'; ?>"
                   onmouseover="if(this.style.backgroundColor !== 'rgba(212, 175, 55, 0.1)') { this.style.backgroundColor='#F9FAFB'; this.style.color='#D4AF37'; }"
                   onmouseout="if(this.style.backgroundColor !== 'rgba(212, 175, 55, 0.1)') { this.style.backgroundColor='transparent'; this.style.color='#6B7280'; }">
                    <i class="bi bi-bar-chart-line" style="margin-right: 0.625rem; font-size: 1rem; color: <?php echo $current_page == 'sales_report.php' ? '#D4AF37' : '#9CA3AF'; ?>;"></i>
                    <span style="font-size: 0.875rem;">Laporan Penjualan</span>
                </a>
            </li>
        </ul>
        
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="px-3 mb-2">
                <p style="font-size: 0.625rem; text-transform: uppercase; color: #9CA3AF; font-weight: 700; letter-spacing: 0.05em; padding: 0 0.5rem;">Konten</p>
            </div>
            <ul class="space-y-1 px-2">
                <li>
                    <a href="carousel.php" 
                       style="display: flex; align-items: center; padding: 0.625rem 0.75rem; border-radius: 0.5rem; transition: all 0.2s; text-decoration: none; <?php echo $current_page == 'carousel.php' ? 'background-color: rgba(212,175,55,0.1); color: #D4AF37; font-weight: 600;' : 'color: #6B7280;'; ?>"
                       onmouseover="if(this.style.backgroundColor !== 'rgba(212, 175, 55, 0.1)') { this.style.backgroundColor='#F9FAFB'; this.style.color='#D4AF37'; }"
                       onmouseout="if(this.style.backgroundColor !== 'rgba(212, 175, 55, 0.1)') { this.style.backgroundColor='transparent'; this.style.color='#6B7280'; }">
                        <i class="bi bi-images" style="margin-right: 0.625rem; font-size: 1rem; color: <?php echo $current_page == 'carousel.php' ? '#D4AF37' : '#9CA3AF'; ?>;"></i>
                        <span style="font-size: 0.875rem;">Carousel</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="px-3 mb-2">
                <p style="font-size: 0.625rem; text-transform: uppercase; color: #9CA3AF; font-weight: 700; letter-spacing: 0.05em; padding: 0 0.5rem;">Tautan Cepat</p>
            </div>
            <ul class="space-y-1 px-2">
                <li>
                    <a href="../index.php" target="_blank" 
                       style="display: flex; align-items: center; padding: 0.625rem 0.75rem; border-radius: 0.5rem; transition: all 0.2s; text-decoration: none; color: #6B7280;"
                       onmouseover="this.style.backgroundColor='#F9FAFB'; this.style.color='#D4AF37';"
                       onmouseout="this.style.backgroundColor='transparent'; this.style.color='#6B7280';">
                        <i class="bi bi-shop" style="margin-right: 0.625rem; font-size: 1rem; color: #9CA3AF;"></i>
                        <span style="font-size: 0.875rem;">Lihat Toko</span>
                        <i class="bi bi-box-arrow-up-right" style="margin-left: auto; font-size: 0.75rem; color: #D1D5DB;"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- User Info & Logout (Bottom) -->
    <div class="mt-auto border-t border-gray-100" style="background: linear-gradient(135deg, rgba(212,175,55,0.02) 0%, rgba(212,175,55,0.05) 100%);">
        <div class="p-3">
            <div style="display: flex; align-items: center; gap: 0.625rem; padding: 0.5rem; background: white; border-radius: 0.5rem; margin-bottom: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, #D4AF37, #FFD77A); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-person-fill" style="color: white; font-size: 0.875rem;"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <p style="font-size: 0.75rem; font-weight: 700; color: #1A1A1A; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></p>
                    <p style="font-size: 0.625rem; color: #9CA3AF; margin: 0;">Administrator</p>
                </div>
            </div>
            <a href="../logout.php" 
               style="display: flex; align-items: center; justify-content: center; padding: 0.625rem 0.75rem; border-radius: 0.5rem; transition: all 0.2s; text-decoration: none; color: #DC2626; background: white; border: 1px solid #FCA5A5;"
               onmouseover="this.style.backgroundColor='#FEE2E2';"
               onmouseout="this.style.backgroundColor='white';">
                <i class="bi bi-box-arrow-right" style="margin-right: 0.5rem; font-size: 0.875rem;"></i>
                <span style="font-size: 0.875rem; font-weight: 600;">Keluar</span>
            </a>
        </div>
    </div>
</nav>
