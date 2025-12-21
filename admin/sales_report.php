<?php
require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');
require_once '../models/OrderManager.php';
$orderManager = new OrderManager();
// Ambil filter tanggal (optional)
$startDate = isset($_GET['start_date']) && $_GET['start_date'] !== '' ? $_GET['start_date'] : null;
$endDate   = isset($_GET['end_date']) && $_GET['end_date'] !== '' ? $_GET['end_date'] : null;
// Pagination untuk Recent Orders
$ordersPerPage = isset($_GET['orders_per_page']) ? intval($_GET['orders_per_page']) : 10;
$ordersPage = isset($_GET['orders_page']) ? max(1, intval($_GET['orders_page'])) : 1;
// Filter status untuk Recent Orders
$orderStatus = $_GET['order_status'] ?? '';
// Pagination untuk Top Selling Products
$productsPerPage = isset($_GET['products_per_page']) ? intval($_GET['products_per_page']) : 5;
$productsPage = isset($_GET['products_page']) ? max(1, intval($_GET['products_page'])) : 1;
// Ambil statistik & daftar pesanan dengan filter tanggal (jika ada)
$stats = $orderManager->getSalesStatistics($startDate, $endDate);
$allOrdersFull = $orderManager->getAllOrdersWithUserDetails($startDate, $endDate);
// Filter berdasarkan status jika dipilih
if ($orderStatus !== '') {
    $allOrdersFull = array_filter($allOrdersFull, function($order) use ($orderStatus) {
        return $order['status'] === $orderStatus;
    });
    $allOrdersFull = array_values($allOrdersFull); // Re-index array
}
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

require_once 'includes/header.php';
?>

<main class="flex-1 px-2 pb-2" x-data="{
    showOrderDetailsModal: false,
    showApproveModal: false,
    showRejectModal: false,
    showCodModal: false,
    
    selectedOrderId: null,
    approveOrderId: null,
    rejectOrderId: null,
    codOrderId: null,
    
    orderDetailsContent: '<div class=\'text-center py-5\'><div class=\'animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto\'></div></div>',
    
    openOrderDetails(orderId) {
        this.selectedOrderId = orderId;
        this.showOrderDetailsModal = true;
        this.orderDetailsContent = '<div class=\'text-center py-5\'><div class=\'animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto\'></div></div>';
        
        fetch(`ajax_get_order_details.php?order_id=${orderId}`)
            .then(response => response.text())
            .then(data => {
                this.orderDetailsContent = data;
            })
            .catch(error => {
                this.orderDetailsContent = '<div class=\'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded\'>Gagal memuat detail pesanan.</div>';
                console.error('Error:', error);
            });
    }
}"
@open-approve-modal.window="showApproveModal = true; approveOrderId = $event.detail.orderId"
@open-reject-modal.window="showRejectModal = true; rejectOrderId = $event.detail.orderId"
@open-cod-modal.window="showCodModal = true; codOrderId = $event.detail.orderId"
>
    
    <?php if ($successMsg): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center shadow-sm">
            <i class="bi bi-check-circle-fill mr-2"></i> <?php echo htmlspecialchars($successMsg); ?>
        </div>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center shadow-sm">
            <i class="bi bi-exclamation-triangle-fill mr-2"></i> <?php echo htmlspecialchars($errorMsg); ?>
        </div>
    <?php endif; ?>

    <div class="pt-0 mt-0 pb-4 mb-1 border-b border-gray-200 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1"><?php echo $pageTitle; ?></h1>
            <p class="text-gray-600">Pantau kinerja penjualan dan kelola pesanan.</p>
        </div>
        <div>
            <?php
            $exportParams = [];
            if ($startDate) $exportParams['start_date'] = $startDate;
            if ($endDate) $exportParams['end_date'] = $endDate;
            $exportUrl = 'export_sales_report.php' . (!empty($exportParams) ? '?' . http_build_query($exportParams) : '');
            ?>
            <a href="<?php echo $exportUrl; ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold bg-white shadow-sm">
                <i class="bi bi-download mr-2"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md mb-6 p-6">
        <form method="get" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            <div>
                <label for="start_date" class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       value="<?php echo htmlspecialchars($startDate ?? ''); ?>">
            </div>
            <div>
                <label for="end_date" class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       value="<?php echo htmlspecialchars($endDate ?? ''); ?>">
            </div>
            <div class="flex gap-2">
                <button type="submit" style="flex: 1; padding: 0.5rem 1rem; background-color: #D4AF37; color: #1A1A1A; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center;"
                        onmouseover="this.style.backgroundColor='#B5952F';"
                        onmouseout="this.style.backgroundColor='#D4AF37';">
                    <i class="bi bi-funnel mr-2"></i> Filter
                </button>
                <a href="sales_report.php" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h6 class="text-gray-500 text-xs font-bold uppercase mb-1">Total Pendapatan</h6>
                    <h2 class="text-3xl font-bold text-green-600">Rp <?php echo number_format($stats['total_revenue'], 0, ',', '.'); ?></h2>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="bi bi-wallet2 text-2xl text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h6 class="text-gray-500 text-xs font-bold uppercase mb-1">Total Pesanan</h6>
                    <h2 class="text-3xl font-bold text-blue-600"><?php echo number_format($stats['total_orders']); ?></h2>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="bi bi-receipt text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Top Selling Products -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-lg shadow-md overflow-hidden h-full flex flex-col">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-2">
                    <h5 class="font-bold text-lg">Produk Terlaris</h5>
                    <div class="flex items-center gap-2">
                        <label for="productsPerPageSelect" class="text-sm text-gray-600">Tampilkan:</label>
                        <select id="productsPerPageSelect" class="text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 py-1" onchange="changeProductsPerPage(this.value)">
                            <option value="5" <?php echo ($productsPerPage == 5) ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?php echo ($productsPerPage == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="20" <?php echo ($productsPerPage == 20) ? 'selected' : ''; ?>>20</option>
                            <option value="50" <?php echo ($productsPerPage == 50) ? 'selected' : ''; ?>>50</option>
                        </select>
                    </div>
                </div>
                <div class="flex-1 overflow-auto">
                    <?php if (empty($topProducts)): ?>
                        <div class="p-8 text-center text-gray-500">
                            <i class="bi bi-box-seam text-4xl block mb-3 opacity-50"></i>
                            Tidak ada data penjualan untuk periode ini.
                        </div>
                    <?php else: ?>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider pl-6">Produk</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Terjual</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider pr-6">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($topProducts as $prod): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap pl-6">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0 bg-gray-100 rounded mr-3 flex items-center justify-center overflow-hidden">
                                                    <?php if (!empty($prod['image_path'])): ?>
                                                        <img src="../<?php echo htmlspecialchars($prod['image_path']); ?>" alt="" class="h-full w-full object-cover">
                                                    <?php else: ?>
                                                        <i class="bi bi-image text-gray-400"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-sm font-medium text-gray-900 truncate max-w-[150px]" title="<?php echo htmlspecialchars($prod['nama']); ?>">
                                                    <?php echo htmlspecialchars($prod['nama']); ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900 font-medium"><?php echo number_format($prod['total_quantity']); ?></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900 font-medium pr-6">Rp <?php echo number_format($prod['total_revenue'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                <?php if ($totalProductsPages > 1): ?>
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                    <div class="flex justify-center">
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <?php if ($productsPage > 1): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['products_page' => $productsPage - 1])); ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            <?php else: ?>
                                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                    <span class="sr-only">Previous</span>
                                    <i class="bi bi-chevron-left"></i>
                                </span>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalProductsPages; $i++): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['products_page' => $i])); ?>" class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo $i === $productsPage ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'text-gray-500 hover:bg-gray-50'; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($productsPage < $totalProductsPages): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['products_page' => $productsPage + 1])); ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Next</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            <?php else: ?>
                                <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                    <span class="sr-only">Next</span>
                                    <i class="bi bi-chevron-right"></i>
                                </span>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-lg shadow-md overflow-hidden h-full flex flex-col">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-2">
                    <h5 class="font-bold text-lg">Pesanan Terbaru</h5>
                    <div class="flex items-center gap-2 flex-wrap">
                        <div class="flex items-center gap-2">
                            <label for="orderStatusFilter" class="text-sm text-gray-600">Status:</label>
                            <select id="orderStatusFilter" class="text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 py-1" onchange="changeOrderStatus(this.value)">
                                <option value="" <?php echo ($orderStatus === '') ? 'selected' : ''; ?>>Semua</option>
                                <option value="Pending" <?php echo ($orderStatus === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Menunggu Konfirmasi" <?php echo ($orderStatus === 'Menunggu Konfirmasi') ? 'selected' : ''; ?>>Menunggu Konfirmasi</option>
                                <option value="Selesai" <?php echo ($orderStatus === 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                                <option value="Dibatalkan" <?php echo ($orderStatus === 'Dibatalkan') ? 'selected' : ''; ?>>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="ordersPerPageSelect" class="text-sm text-gray-600">Tampilkan:</label>
                            <select id="ordersPerPageSelect" class="text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 py-1" onchange="changeOrdersPerPage(this.value)">
                                <option value="5" <?php echo ($ordersPerPage == 5) ? 'selected' : ''; ?>>5</option>
                                <option value="10" <?php echo ($ordersPerPage == 10) ? 'selected' : ''; ?>>10</option>
                                <option value="20" <?php echo ($ordersPerPage == 20) ? 'selected' : ''; ?>>20</option>
                                <option value="50" <?php echo ($ordersPerPage == 50) ? 'selected' : ''; ?>>50</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex-1 overflow-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider pl-6">ID Pesanan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider pr-6">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($allOrders)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <i class="bi bi-inbox text-4xl block mb-3 opacity-50"></i>
                                        Tidak ada pesanan ditemukan.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($allOrders as $order): ?>
                                    <?php
                                        $displayNo = !empty($order['nomor_pesanan'] ?? null)
                                            ? $order['nomor_pesanan']
                                            : '#' . $order['id'];
                                        
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                        if ($order['status'] === 'Pending') $statusClass = 'bg-yellow-100 text-yellow-800';
                                        elseif ($order['status'] === 'Selesai') $statusClass = 'bg-green-100 text-green-800';
                                        elseif ($order['status'] === 'Dibatalkan') $statusClass = 'bg-red-100 text-red-800';
                                        elseif ($order['status'] === 'Menunggu Konfirmasi') $statusClass = 'bg-blue-100 text-blue-800';
                                    ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap pl-6">
                                            <span class="font-mono font-bold text-blue-600 text-xs"><?php echo htmlspecialchars($displayNo); ?></span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center mr-2">
                                                    <i class="bi bi-person text-gray-500 text-xs"></i>
                                                </div>
                                                <span class="text-sm text-gray-900"><?php echo htmlspecialchars($order['username']); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500"><?php echo date('d M, H:i', strtotime($order['tanggal_pesanan'])); ?></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                                <?php echo htmlspecialchars($order['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium pr-6">
                                            <button onclick="showOrderDetailsModal(<?php echo $order['id']; ?>)" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded transition">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($totalOrdersPages > 1): ?>
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                    <div class="flex justify-center">
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <?php if ($ordersPage > 1): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['orders_page' => $ordersPage - 1])); ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            <?php else: ?>
                                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                    <span class="sr-only">Previous</span>
                                    <i class="bi bi-chevron-left"></i>
                                </span>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalOrdersPages; $i++): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['orders_page' => $i])); ?>" class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo $i === $ordersPage ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'text-gray-500 hover:bg-gray-50'; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($ordersPage < $totalOrdersPages): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['orders_page' => $ordersPage + 1])); ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Next</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            <?php else: ?>
                                <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                    <span class="sr-only">Next</span>
                                    <i class="bi bi-chevron-right"></i>
                                </span>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pesanan -->
    <div x-show="showOrderDetailsModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         aria-labelledby="order-details-modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showOrderDetailsModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 @click="showOrderDetailsModal = false"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showOrderDetailsModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="order-details-modal-title">Detail Pesanan</h3>
                        <button @click="showOrderDetailsModal = false" class="text-gray-400 hover:text-gray-500">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="bg-white px-4 py-4 sm:p-6" x-html="orderDetailsContent">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showOrderDetailsModal = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/payment_modals.php'; ?>

</main>

<?php include 'includes/footer.php'; ?>

<script>
// Per-page selector functions
window.changeProductsPerPage = function(perPage) {
    const url = new URL(window.location.href);
    url.searchParams.set('products_per_page', perPage);
    url.searchParams.set('products_page', '1'); // Reset to page 1
    window.location.href = url.toString();
};

window.changeOrdersPerPage = function(perPage) {
    const url = new URL(window.location.href);
    url.searchParams.set('orders_per_page', perPage);
    url.searchParams.set('orders_page', '1'); // Reset to page 1
    window.location.href = url.toString();
};

window.changeOrderStatus = function(status) {
    const url = new URL(window.location.href);
    if (status) {
        url.searchParams.set('order_status', status);
    } else {
        url.searchParams.delete('order_status');
    }
    url.searchParams.set('orders_page', '1'); // Reset to page 1
    window.location.href = url.toString();
};

// Order Details Modal
window.showOrderDetailsModal = function(orderId) {
    const modal = document.getElementById('orderDetailsModal');
    const content = document.getElementById('orderDetailsContent');
    
    // Show modal with loading
    content.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div><p class="mt-4 text-gray-600">Memuat detail pesanan...</p></div>';
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.modal-content').classList.add('show');
    }, 10);
    
    // Fetch order details
    fetch(`ajax_get_order_details.php?order_id=${orderId}`)
        .then(response => response.text())
        .then(data => {
            content.innerHTML = data;
        })
        .catch(error => {
            content.innerHTML = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">Gagal memuat detail pesanan.</div>';
            console.error('Error:', error);
        });
};

window.hideOrderDetailsModal = function() {
    const modal = document.getElementById('orderDetailsModal');
    modal.querySelector('.modal-content').classList.remove('show');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
};

// Approve Modal
window.showApproveModal = function(orderId) {
    const modal = document.getElementById('approveModal');
    document.getElementById('approveOrderId').value = orderId;
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.modal-content').classList.add('show');
    }, 10);
};

window.hideApproveModal = function() {
    const modal = document.getElementById('approveModal');
    modal.querySelector('.modal-content').classList.remove('show');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
};

// Reject Modal
window.showRejectModal = function(orderId) {
    const modal = document.getElementById('rejectModal');
    document.getElementById('rejectOrderId').value = orderId;
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.modal-content').classList.add('show');
    }, 10);
};

window.hideRejectModal = function() {
    const modal = document.getElementById('rejectModal');
    modal.querySelector('.modal-content').classList.remove('show');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
};

// COD Modal
window.showCodModal = function(orderId) {
    const modal = document.getElementById('codModal');
    document.getElementById('codOrderId').value = orderId;
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.modal-content').classList.add('show');
    }, 10);
};

window.hideCodModal = function() {
    const modal = document.getElementById('codModal');
    modal.querySelector('.modal-content').classList.remove('show');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
};
</script>

<!-- Order Details Modal -->
<div id="orderDetailsModal" class="hidden" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;" onclick="hideOrderDetailsModal()">
    <div class="modal-content" style="background: white; border-radius: 0.75rem; max-width: 48rem; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out;" onclick="event.stopPropagation()">
        <div style="padding: 1.5rem; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin: 0;">Detail Pesanan</h3>
            <button onclick="hideOrderDetailsModal()" style="padding: 0.5rem; border-radius: 0.375rem; background: none; border: none; cursor: pointer; color: #6B7280; transition: all 0.2s;"
                    onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='#111827';"
                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='#6B7280';">
                <i class="bi bi-x-lg" style="font-size: 1.25rem;"></i>
            </button>
        </div>
        <div id="orderDetailsContent" style="padding: 1.5rem;">
            <!-- Content akan di-load via AJAX -->
        </div>
    </div>
</div>

<style>
.modal-content.show {
    transform: scale(1) !important;
    opacity: 1 !important;
}
#orderDetailsModal.hidden {
    display: none !important;
}
#orderDetailsModal {
    display: flex !important;
}
</style>
