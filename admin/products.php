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

require_once 'includes/header.php';
?>

<main class="flex-1 px-2 pb-2">
    <div class="pt-0 mt-0 pb-4 mb-1 border-b border-gray-200 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1"><?php echo $pageTitle; ?></h1>
            <p class="text-gray-600">Kelola inventori produk, harga, dan stok Anda.</p>
        </div>
        <div class="pt-1">
            <a href="product_form.php" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #D4AF37; color: #1A1A1A; border-radius: 0.5rem; font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s; text-decoration: none;"
               onmouseover="this.style.backgroundColor='#B5952F'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';"
               onmouseout="this.style.backgroundColor='#D4AF37'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Produk
            </a>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="bg-<?php echo $message_type == 'success' ? 'green' : 'blue'; ?>-100 border border-<?php echo $message_type == 'success' ? 'green' : 'blue'; ?>-400 text-<?php echo $message_type == 'success' ? 'green' : 'blue'; ?>-700 px-4 py-3 rounded mb-6 shadow-sm flex items-center">
            <i class="bi bi-info-circle-fill mr-2"></i> <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Filter Card -->
    <div class="bg-white rounded-lg shadow-md mb-4 p-5">
        <form action="products.php" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label for="q" class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Cari Produk</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-search text-gray-400"></i>
                        </div>
                        <input type="text" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50" id="q" name="q" placeholder="Nama, merek..." value="<?php echo htmlspecialchars($q); ?>">
                    </div>
                </div>
                
                <!-- Kategori -->
                <div>
                    <label for="kategori" class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Kategori</label>
                    <select name="kategori" id="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        <option value="">Semua</option>
                        <?php foreach ($availableCategories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($kategori === $cat) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Gender</label>
                    <select name="gender" id="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        <option value="">Semua</option>
                        <option value="Male" <?php echo ($gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Unisex" <?php echo ($gender === 'Unisex') ? 'selected' : ''; ?>>Unisex</option>
                    </select>
                </div>
                
                <!-- Sort -->
                <div>
                    <label for="sort_by" class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Urutkan Harga</label>
                    <select name="sort_by" id="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        <option value="">Terbaru</option>
                        <option value="price_asc" <?php echo ($sort_by === 'price_asc') ? 'selected' : ''; ?>>Murah ke Mahal</option>
                        <option value="price_desc" <?php echo ($sort_by === 'price_desc') ? 'selected' : ''; ?>>Mahal ke Murah</option>
                    </select>
                </div>
            </div>
            
            <!-- Actions Row -->
            <div class="flex justify-between items-center">
                <!-- Best Seller Checkbox -->
                <div class="flex items-center">
                    <input type="checkbox" id="best" name="best" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" <?php echo $best ? 'checked' : ''; ?>>
                    <label class="ml-2 text-sm font-semibold text-gray-700" for="best">Best Seller</label>
                </div>
                
                <!-- Buttons -->
                <div class="flex gap-3">
                    <a href="products.php" style="display: flex; align-items: center; padding: 0.5rem 1rem; border: 2px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; text-decoration: none; transition: all 0.2s;"
                       onmouseover="this.style.borderColor='#D1D5DB'; this.style.backgroundColor='#F9FAFB'; this.style.transform='translateY(-1px)';"
                       onmouseout="this.style.borderColor='#E5E7EB'; this.style.backgroundColor='transparent'; this.style.transform='translateY(0)';"
                       title="Reset">
                        <i class="bi bi-x-circle mr-2"></i> Reset
                    </a>
                    <button type="submit" style="display: flex; align-items: center; padding: 0.5rem 1.5rem; background-color: #D4AF37; color: #1A1A1A; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
                            onmouseover="this.style.backgroundColor='#B5952F'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';"
                            onmouseout="this.style.backgroundColor='#D4AF37'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';">
                        <i class="bi bi-funnel-fill mr-2"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-2">
            <h5 class="font-bold text-lg">Daftar Produk</h5>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label for="perPageSelect" class="text-sm text-gray-600">Show:</label>
                    <select id="perPageSelect" class="text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500" onchange="changePerPage(this.value)">
                        <option value="5" <?php echo ($perPage == 5) ? 'selected' : ''; ?>>5</option>
                        <option value="10" <?php echo ($perPage == 10) ? 'selected' : ''; ?>>10</option>
                        <option value="20" <?php echo ($perPage == 20) ? 'selected' : ''; ?>>20</option>
                        <option value="50" <?php echo ($perPage == 50) ? 'selected' : ''; ?>>50</option>
                        <option value="100" <?php echo ($perPage == 100) ? 'selected' : ''; ?>>100</option>
                    </select>
                    <span class="text-sm text-gray-600">data</span>
                </div>
                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full border border-gray-200"><?php echo $totalFiltered; ?> Produk</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider pl-8">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Harga & Stok</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider pr-8 w-32"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($parfums)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="bi bi-box-seam text-6xl block mb-4 opacity-50"></i>
                                <p>Tidak ada produk yang ditemukan.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($parfums as $parfum): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap pl-8">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gray-100 rounded mr-4 flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-200">
                                            <?php if ($parfum->getImagePath()): ?>
                                                <img src="../<?php echo htmlspecialchars($parfum->getImagePath()); ?>" alt="" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <i class="bi bi-image text-gray-400"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900"><?php echo htmlspecialchars($parfum->getNama()); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($parfum->getMerek()); ?></div>
                                            <?php if ($parfum->getIsBestSeller()): ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                    <i class="bi bi-star-fill mr-1 text-xs"></i>Best Seller
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                        <?php echo htmlspecialchars($parfum->getKategori() ?: '-'); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php 
                                        $g = $parfum->getGender();
                                        $badgeClass = 'bg-gray-100 text-gray-800';
                                        if($g == 'Male') $badgeClass = 'bg-cyan-100 text-cyan-800';
                                        elseif($g == 'Female') $badgeClass = 'bg-pink-100 text-pink-800';
                                        elseif($g == 'Unisex') $badgeClass = 'bg-green-100 text-green-800';
                                    ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($g); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="process_product.php" method="POST" class="quick-update-form flex items-center gap-2">
                                        <input type="hidden" name="action" value="quick_update">
                                        <input type="hidden" name="id" value="<?php echo $parfum->getId(); ?>">
                                        <input type="hidden" name="harga" class="harga-value" value="<?php echo (int)$parfum->getHarga(); ?>">
                                        <input type="hidden" name="stok" class="stok-value" value="<?php echo (int)$parfum->getStok(); ?>">
                                        
                                        <div class="flex flex-col gap-2">
                                            <!-- Harga Display -->
                                            <div class="price-display-wrapper">
                                                <div class="price-display px-2 py-1 bg-gray-50 rounded border border-gray-200 cursor-pointer hover:bg-gray-100 transition min-w-[120px]" title="Klik untuk edit">
                                                    <small class="text-gray-500 block text-[10px] uppercase font-bold">Harga</small>
                                                    <div class="font-medium text-gray-900">Rp <?php echo number_format($parfum->getHarga(), 0, ',', '.'); ?></div>
                                                </div>
                                                <input type="number" class="price-edit w-full px-2 py-1 text-sm border border-blue-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hidden" placeholder="Harga">
                                            </div>
                                            
                                            <!-- Stok Display -->
                                            <div class="stock-display-wrapper">
                                                <div class="stock-display px-2 py-1 bg-gray-50 rounded border border-gray-200 cursor-pointer hover:bg-gray-100 transition min-w-[120px]" title="Klik untuk edit">
                                                    <small class="text-gray-500 block text-[10px] uppercase font-bold">Stok</small>
                                                    <div class="font-medium text-gray-900"><?php echo number_format($parfum->getStok(), 0, ',', '.'); ?> pcs</div>
                                                </div>
                                                <input type="number" class="stock-edit w-full px-2 py-1 text-sm border border-blue-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hidden" placeholder="Stok">
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="submit-update p-2 bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition hidden" title="Simpan Perubahan">
                                            <i class="bi bi-check-lg text-lg"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center pr-8">
                                    <div class="relative inline-block" x-data="{ open: false }">
                                        <button @click="open = !open" 
                                                @click.away="open = false"
                                                style="padding: 0.5rem; border-radius: 0.5rem; transition: all 0.2s; background-color: #F9FAFB; border: 1px solid #E5E7EB;"
                                                onmouseover="this.style.backgroundColor='#F3F4F6';"
                                                onmouseout="this.style.backgroundColor='#F9FAFB';">
                                            <i class="bi bi-three-dots-vertical" style="font-size: 1.125rem; color: #6B7280;"></i>
                                        </button>
                                        
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             style="display: none; position: absolute; right: 100%; margin-right: 0.5rem; top: 0; width: 10rem; background-color: white; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); z-index: 50; border: 1px solid #E5E7EB;"
                                             @click.away="open = false">
                                            <div style="padding: 0.25rem;">
                                                <a href="product_form.php?id=<?php echo $parfum->getId(); ?>" 
                                                   style="display: flex; align-items: center; padding: 0.625rem 0.75rem; color: #1F2937; border-radius: 0.375rem; text-decoration: none; transition: all 0.15s; font-size: 0.875rem;"
                                                   onmouseover="this.style.backgroundColor='#EFF6FF'; this.style.color='#1D4ED8';"
                                                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='#1F2937';">
                                                    <i class="bi bi-pencil-square" style="margin-right: 0.625rem; font-size: 0.875rem;"></i>
                                                    <span>Edit Produk</span>
                                                </a>
                                                <div style="height: 1px; background-color: #E5E7EB; margin: 0.25rem 0;"></div>
                                                <button type="button" 
                                                        onclick="showDeleteModal('<?php echo $parfum->getId(); ?>', '<?php echo htmlspecialchars($parfum->getNama(), ENT_QUOTES); ?>')"
                                                        style="width: 100%; display: flex; align-items: center; padding: 0.625rem 0.75rem; color: #DC2626; border-radius: 0.375rem; background: none; border: none; cursor: pointer; transition: all 0.15s; font-size: 0.875rem; text-align: left;"
                                                        onmouseover="this.style.backgroundColor='#FEE2E2'; this.style.color='#B91C1C';"
                                                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#DC2626';">
                                                    <i class="bi bi-trash" style="margin-right: 0.625rem; font-size: 0.875rem;"></i>
                                                    <span>Hapus Produk</span>
                                                </button>
                                            </div>
                                        </div>
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
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-center">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <a href="?page=<?php echo max(1, $page - 1); ?>&<?php echo $baseParams; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo ($page <= 1) ? 'pointer-events-none opacity-50' : ''; ?>">
                        <span class="sr-only">Previous</span>
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&<?php echo $baseParams; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo ($page == $i) ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'text-gray-500 hover:bg-gray-50'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    <a href="?page=<?php echo min($totalPages, $page + 1); ?>&<?php echo $baseParams; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo ($page >= $totalPages) ? 'pointer-events-none opacity-50' : ''; ?>">
                        <span class="sr-only">Next</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </nav>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Delete Confirmation Modal (Alpine.js) -->
    <!-- Delete Confirmation Modal (Vanilla JS) -->
    <div id="deleteModal" class="hidden" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;" onclick="hideDeleteModal()">
        <div class="modal-content" style="background: white; border-radius: 0.75rem; max-width: 32rem; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out;" onclick="event.stopPropagation()">
            <div style="padding: 1.5rem; background-color: #FEF2F2; border-bottom: 1px solid #FEE2E2; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; border-radius: 50%; background-color: #FEE2E2;">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5rem; color: #DC2626;"></i>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0 0 0.5rem 0;">Konfirmasi Hapus Produk</h3>
                        <p style="font-size: 0.875rem; color: #6B7280; margin: 0;">
                            Apakah Anda yakin ingin menghapus produk <span id="deleteProductName" class="font-bold text-gray-800"></span>?
                        </p>
                        <div style="margin-top: 0.5rem; padding: 0.5rem; background-color: #FEE2E2; border-radius: 0.375rem; color: #991B1B; font-size: 0.75rem;">
                            <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan.
                        </div>
                    </div>
                </div>
            </div>
            <div style="background-color: #F9FAFB; padding: 1rem 1.5rem; border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" onclick="hideDeleteModal()" 
                        style="padding: 0.5rem 1rem; border: 2px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; background: white; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                        onmouseover="this.style.borderColor='#D1D5DB'; this.style.backgroundColor='#F3F4F6';"
                        onmouseout="this.style.borderColor='#E5E7EB'; this.style.backgroundColor='white';">
                    Batal
                </button>
                <a id="confirmDeleteBtn" href="#" 
                   style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #DC2626; color: white; border-radius: 0.5rem; font-weight: 600; text-decoration: none; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 0.875rem;"
                   onmouseover="this.style.backgroundColor='#B91C1C'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';"
                   onmouseout="this.style.backgroundColor='#DC2626'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';">
                    <i class="bi bi-trash mr-2"></i> Ya, Hapus
                </a>
            </div>
        </div>
    </div>

    <style>
    .modal-content.show {
        transform: scale(1) !important;
        opacity: 1 !important;
    }
    #deleteModal.hidden {
        display: none !important;
    }
    #deleteModal {
        display: flex !important;
    }
    </style>
</main>

<?php include 'includes/footer.php'; ?>

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
                priceDisplay.classList.add('hidden');
                priceEdit.classList.remove('hidden');
                priceEdit.focus();
                submitBtn.classList.remove('hidden');
            });
            
            priceEdit.addEventListener('blur', function() {
                setTimeout(function() {
                    const newValue = parseInt(priceEdit.value) || 0;
                    priceValue.value = newValue;
                    priceDisplay.querySelector('.font-medium').textContent = 'Rp ' + newValue.toLocaleString('id-ID');
                    priceEdit.classList.add('hidden');
                    priceDisplay.classList.remove('hidden');
                }, 200);
            });
        }
        
        // Stock click to edit
        if (stockDisplay && stockEdit) {
            stockDisplay.addEventListener('click', function() {
                stockEdit.value = stockValue.value;
                stockDisplay.classList.add('hidden');
                stockEdit.classList.remove('hidden');
                stockEdit.focus();
                submitBtn.classList.remove('hidden');
            });
            
            stockEdit.addEventListener('blur', function() {
                setTimeout(function() {
                    const newValue = parseInt(stockEdit.value) || 0;
                    stockValue.value = newValue;
                    stockDisplay.querySelector('.font-medium').textContent = newValue.toLocaleString('id-ID') + ' pcs';
                    stockEdit.classList.add('hidden');
                    stockDisplay.classList.remove('hidden');
                }, 200);
            });
        }
        
        // Hide submit button if both are back to display mode
        form.addEventListener('click', function(e) {
            if (!priceEdit.classList.contains('hidden') || !stockEdit.classList.contains('hidden')) {
                return;
            }
            if (e.target !== submitBtn && !submitBtn.contains(e.target)) {
                submitBtn.classList.add('hidden');
            }
        });
    });
    
    // Per-page selector function
    window.changePerPage = function(perPage) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', '1'); // Reset to page 1 when changing per_page
        window.location.href = url.toString();
    };

    // Delete Modal Functions
    window.showDeleteModal = function(id, name) {
        const modal = document.getElementById('deleteModal');
        const nameSpan = document.getElementById('deleteProductName');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        
        nameSpan.textContent = name;
        confirmBtn.href = 'process_product.php?action=delete&id=' + id;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.modal-content').classList.add('show');
        }, 10);
    };

    window.hideDeleteModal = function() {
        const modal = document.getElementById('deleteModal');
        modal.querySelector('.modal-content').classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    };
});
</script>
