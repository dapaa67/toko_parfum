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

$pageTitle = "Admin Dashboard";
require_once 'includes/header.php';
?>

        <!-- Main content -->
        <main class="flex-1 px-2 pb-2">
            <?php if (!empty($message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow-sm flex items-center">
                    <i class="bi bi-check-circle-fill mr-2"></i> <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Hero Section -->
            <div class="pt-0 mt-0 pb-4 mb-1 border-b border-gray-200">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-1">Dashboard</h1>
                    <p class="text-gray-600">Welcome back, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>!</p>
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
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-gray-500 text-xs uppercase font-bold mb-1">Total Produk</h6>
                            <h2 class="text-4xl font-bold"><?php echo number_format($totalProducts); ?></h2>
                        </div>
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-collection text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gold h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-gray-500 text-xs uppercase font-bold mb-1">Parfum Pria</h6>
                            <h2 class="text-4xl font-bold"><?php echo number_format($men); ?></h2>
                        </div>
                        <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-gender-male text-cyan-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-cyan-500 h-2 rounded-full" style="width: <?php echo $totalProducts > 0 ? ($men/$totalProducts)*100 : 0; ?>%"></div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-gray-500 text-xs uppercase font-bold mb-1">Parfum Wanita</h6>
                            <h2 class="text-4xl font-bold"><?php echo number_format($women); ?></h2>
                        </div>
                        <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-gender-female text-pink-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-pink-500 h-2 rounded-full" style="width: <?php echo $totalProducts > 0 ? ($women/$totalProducts)*100 : 0; ?>%"></div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-gray-500 text-xs uppercase font-bold mb-1">Parfum Unisex</h6>
                            <h2 class="text-4xl font-bold"><?php echo number_format($unisex); ?></h2>
                        </div>
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-people text-green-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: <?php echo $totalProducts > 0 ? ($unisex/$totalProducts)*100 : 0; ?>%"></div>
                    </div>
                </div>
            </div>

            <!-- Quick actions & Latest Products -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-lg shadow-md h-full">
                        <div class="p-6 border-b border-gray-200">
                            <h5 class="font-bold text-lg">Aksi Cepat</h5>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4">
                                <a href="products.php" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 transition-all duration-200 group">
                                    <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                                        <i class="bi bi-box-seam text-2xl text-[#D4AF37]"></i>
                                    </div>
                                    <span class="font-semibold text-gray-700 group-hover:text-[#D4AF37] transition-colors">Produk</span>
                                </a>
                                <a href="carousel.php" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 transition-all duration-200 group">
                                    <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                                        <i class="bi bi-images text-2xl text-[#D4AF37]"></i>
                                    </div>
                                    <span class="font-semibold text-gray-700 group-hover:text-[#D4AF37] transition-colors">Carousel</span>
                                </a>
                                <a href="sales_report.php" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 transition-all duration-200 group">
                                    <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                                        <i class="bi bi-bar-chart-line text-2xl text-[#D4AF37]"></i>
                                    </div>
                                    <span class="font-semibold text-gray-700 group-hover:text-[#D4AF37] transition-colors">Laporan</span>
                                </a>
                                <a href="../index.php" target="_blank" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 transition-all duration-200 group">
                                    <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                                        <i class="bi bi-shop-window text-2xl text-[#D4AF37]"></i>
                                    </div>
                                    <span class="font-semibold text-gray-700 group-hover:text-[#D4AF37] transition-colors">Toko</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-lg shadow-md h-full">
                        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                            <h5 class="font-bold text-lg">Produk Terbaru</h5>
                            <a href="products.php" style="padding: 0.5rem 1rem; background-color: #D4AF37; color: #1A1A1A; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem; transition: all 0.2s; text-decoration: none; display: inline-block;"
                               onmouseover="this.style.backgroundColor='#B5952F'; this.style.transform='translateY(-1px)';"
                               onmouseout="this.style.backgroundColor='#D4AF37'; this.style.transform='translateY(0)';">Lihat Semua</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (!empty($latest)): $i = 1;
                                    foreach ($latest as $p): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $i++; ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gray-100 rounded mr-3 flex items-center justify-center overflow-hidden">
                                                        <?php if ($p->getImagePath()): ?>
                                                            <img src="../<?php echo htmlspecialchars($p->getImagePath()); ?>" alt="" class="w-full h-full object-cover">
                                                        <?php else: ?>
                                                            <i class="bi bi-image text-gray-400"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <span class="font-semibold text-gray-900"><?php echo htmlspecialchars($p->getNama()); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($p->getUkuran()); ?> ml</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php 
                                                    $g = $p->getGender();
                                                    $badgeClass = 'bg-gray-100 text-gray-800';
                                                    if($g == 'Male') $badgeClass = 'bg-cyan-100 text-cyan-800';
                                                    elseif($g == 'Female') $badgeClass = 'bg-pink-100 text-pink-800';
                                                    elseif($g == 'Unisex') $badgeClass = 'bg-green-100 text-green-800';
                                                ?>
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($g); ?></span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="product_form.php?id=<?php echo $p->getId(); ?>" class="inline-flex items-center px-3 py-2 bg-gray-100 text-blue-600 rounded hover:bg-gray-200 transition">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <i class="bi bi-box-seam text-6xl block mb-4 opacity-50"></i>
                                            Tidak ada produk.
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

<?php include 'includes/footer.php'; ?>
