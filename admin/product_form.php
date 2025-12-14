<?php
require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');

require_once '../models/ParfumManager.php';
require_once '../models/Parfum.php';

$parfumManager = new ParfumManager();
$parfum = new Parfum();
$pageTitle = "Tambah Produk Baru";
$isEditMode = false;

if (isset($_GET['id'])) {
    $parfum = $parfumManager->readById((int)$_GET['id']);
    if ($parfum) {
        $pageTitle = "Edit Produk: " . htmlspecialchars($parfum->getNama());
        $isEditMode = true;
    } else {
        $_SESSION['message'] = 'Produk tidak ditemukan.';
        $_SESSION['message_type'] = 'danger';
        header('Location: products.php');
        exit();
    }
}

// Fetch distinct categories for datalist
$existingCategories = $parfumManager->getDistinctKategori();

require_once 'includes/header.php';
?>

<main class="flex-1 px-2 pb-2">
    <div class="pt-0 mt-0 pb-4 mb-1 border-b border-gray-200 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900"><?php echo $pageTitle; ?></h1>
        <a href="products.php" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; border: 2px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; text-decoration: none; transition: all 0.2s;"
           onmouseover="this.style.borderColor='#D1D5DB'; this.style.backgroundColor='#F9FAFB'; this.style.transform='translateY(-1px)';"
           onmouseout="this.style.borderColor='#E5E7EB'; this.style.backgroundColor='transparent'; this.style.transform='translateY(0)';">
            <i class="bi bi-arrow-left mr-2"></i> Kembali ke Daftar Produk
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md mb-4">
        <div class="p-5">
            <form action="process_product.php" method="POST" enctype="multipart/form-data">
                <?php if ($isEditMode): ?>
                    <input type="hidden" name="id" value="<?php echo $parfum->getId(); ?>">
                <?php endif; ?>
                <input type="hidden" name="action" value="<?php echo $isEditMode ? 'update' : 'create'; ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="nama" name="nama" value="<?php echo htmlspecialchars($parfum->getNama() ?? ''); ?>" required>
                    </div>
                    <div>
                        <label for="merek" class="block text-sm font-medium text-gray-700 mb-1">Merek</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="merek" name="merek" value="<?php echo htmlspecialchars($parfum->getMerek() ?? ''); ?>" required>
                    </div>
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="kategori" name="kategori" 
                               list="kategori-suggestions" 
                               placeholder="Pilih atau ketik kategori"
                               value="<?php echo htmlspecialchars($parfum->getKategori() ?? ''); ?>">
                        <datalist id="kategori-suggestions">
                            <?php foreach ($existingCategories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>">
                            <?php endforeach; ?>
                        </datalist>
                        <p class="text-xs text-gray-500 mt-1">Pilih dari daftar atau ketik kategori baru</p>
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="gender" name="gender" required>
                            <option value="Male" <?php echo ($parfum->getGender() ?? '') == 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($parfum->getGender() ?? '') == 'Female' ? 'selected' : ''; ?>>Female</option>
                            <option value="Unisex" <?php echo ($parfum->getGender() ?? '') == 'Unisex' ? 'selected' : ''; ?>>Unisex</option>
                        </select>
                    </div>
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="harga" name="harga" value="<?php echo (int)($parfum->getHarga() ?? 0); ?>" required>
                        </div>
                    </div>
                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="stok" name="stok" value="<?php echo (int)($parfum->getStok() ?? 0); ?>" required>
                    </div>
                    <div>
                        <label for="ukuran" class="block text-sm font-medium text-gray-700 mb-1">Ukuran (ml)</label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="number" class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="ukuran" name="ukuran" value="<?php echo (int)($parfum->getUkuran() ?? 0); ?>" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">ml</span>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="deskripsi" name="deskripsi" rows="4"><?php echo htmlspecialchars($parfum->getDeskripsi() ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>
                        <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" id="image" name="image" accept="image/*">
                        <?php if ($isEditMode && $parfum->getImagePath()): ?>
                            <div class="mt-2 p-2 border border-gray-200 rounded bg-gray-50 inline-block">
                                <small class="text-gray-500 block mb-1">Gambar Saat Ini:</small>
                                <img src="../<?php echo htmlspecialchars($parfum->getImagePath()); ?>" alt="Current Image" class="rounded max-w-[150px] max-h-[150px] object-cover">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="flex items-center mt-8">
                            <input type="checkbox" id="is_best_seller" name="is_best_seller" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" <?php echo ($parfum->getIsBestSeller() ?? 0) ? 'checked' : ''; ?>>
                            <label class="ml-2 block text-sm font-medium text-gray-700" for="is_best_seller">Jadikan Best Seller</label>
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                <div class="flex justify-end gap-3">
                    <a href="products.php" style="display: flex; align-items: center; padding: 0.5rem 1rem; border: 2px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; text-decoration: none; transition: all 0.2s;"
                       onmouseover="this.style.borderColor='#D1D5DB'; this.style.backgroundColor='#F9FAFB'; this.style.transform='translateY(-1px)';"
                       onmouseout="this.style.borderColor='#E5E7EB'; this.style.backgroundColor='transparent'; this.style.transform='translateY(0)';">Batal</a>
                    <button type="submit" style="display: flex; align-items: center; padding: 0.5rem 1.5rem; background-color: #D4AF37; color: #1A1A1A; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
                            onmouseover="this.style.backgroundColor='#B5952F'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';"
                            onmouseout="this.style.backgroundColor='#D4AF37'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';">
                        <i class="bi bi-save mr-2"></i> Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
