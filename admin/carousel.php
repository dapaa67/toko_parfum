<?php
session_start();
require_once '../models/CarouselManager.php';
require_once '../models/AuthManager.php';

AuthManager::checkRole('admin');

$carouselManager = new CarouselManager();
$message = '';
$message_type = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $link = $_POST['link'] ?? null;
    $item_order = $_POST['item_order'] ?? 0;
    $image_path = $_POST['current_image_path'] ?? null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../img/carousel/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $imageFileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $imageFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            if ($id && $image_path && file_exists('../' . $image_path)) {
                unlink('../' . $image_path);
            }
            $image_path = 'img/carousel/' . $imageFileName;
        } else {
            $message = "Gagal mengunggah gambar.";
            $message_type = 'danger';
        }
    }

    if ($id) {
        if ($carouselManager->update($id, $image_path, $title, $description, $link, $item_order)) {
            $message = "Item carousel berhasil diubah!";
            $message_type = 'success';
        } else {
            $message = "Gagal mengubah item carousel.";
            $message_type = 'danger';
        }
    } else {
        if ($image_path) {
            if ($carouselManager->create($image_path, $title, $description, $link, $item_order)) {
                $message = "Item carousel berhasil ditambahkan!";
                $message_type = 'success';
            } else {
                $message = "Gagal menambahkan item carousel.";
                $message_type = 'danger';
            }
        } else {
            $message = "Gagal: Gambar wajib diunggah untuk menambahkan item baru.";
            $message_type = 'danger';
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $idToDelete = $_GET['id'];
    $item = $carouselManager->readById($idToDelete);
    if ($item) {
        if (file_exists('../' . $item->image_path)) {
            unlink('../' . $item->image_path);
        }
        if ($carouselManager->delete($idToDelete)) {
            $_SESSION['message'] = "Item carousel berhasil dihapus!";
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = "Gagal menghapus item carousel.";
            $_SESSION['message_type'] = 'danger';
        }
    }
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    header('Location: carousel.php?page=' . $page);
    exit();
}

$itemsPerPage = isset($_GET['per_page']) ? intval($_GET['per_page']) : 5;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$allCarouselItems = $carouselManager->readAll();
$totalItems = count($allCarouselItems);
$totalPages = ceil($totalItems / $itemsPerPage);
$offset = ($currentPage - 1) * $itemsPerPage;
$carouselItems = array_slice($allCarouselItems, $offset, $itemsPerPage);

// Read message from session if exists
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? 'info';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

$pageTitle = "Kelola Carousel";

$editItem = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $editItem = $carouselManager->readById($_GET['id']);
}

require_once 'includes/header.php';
?>

<main class="flex-1 px-2 pb-2" x-data="{ showDeleteModal: false, deleteId: null, deleteTitle: '' }">
    <div class="pt-0 mt-0 pb-4 mb-1 border-b border-gray-200">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1"><?php echo $pageTitle; ?></h1>
            <p class="text-gray-600">Atur gambar carousel untuk halaman utama website.</p>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="bg-<?php echo $message_type == 'success' ? 'green' : 'red'; ?>-100 border border-<?php echo $message_type == 'success' ? 'green' : 'red'; ?>-400 text-<?php echo $message_type == 'success' ? 'green' : 'red'; ?>-700 px-4 py-3 rounded mb-4 flex items-center shadow-sm">
            <i class="bi bi-<?php echo $message_type == 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'; ?> mr-2"></i> <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow-md mb-4">
        <div class="p-4 border-b border-gray-200">
            <h5 class="font-bold text-lg"><?php echo isset($_GET['action']) && $_GET['action'] === 'edit' ? 'Edit Item Carousel' : 'Tambah Item Carousel Baru'; ?></h5>
        </div>
        <div class="p-5">
            <form action="carousel.php" method="POST" enctype="multipart/form-data">
                <?php if ($editItem): ?>
                    <input type="hidden" name="id" value="<?php echo (int)$editItem->id; ?>">
                    <input type="hidden" name="current_image_path" value="<?php echo htmlspecialchars($editItem->image_path); ?>">
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="title" name="title" placeholder="Judul" value="<?php echo htmlspecialchars($editItem->title ?? ''); ?>">
                    </div>
                    <div>
                        <label for="link" class="block text-sm font-medium text-gray-700 mb-1">Link (opsional)</label>
                        <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="link" name="link" placeholder="https://example.com" value="<?php echo htmlspecialchars($editItem->link ?? ''); ?>">
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (opsional)</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="description" name="description" rows="3" placeholder="Deskripsi singkat..."><?php echo htmlspecialchars($editItem->description ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar (Unggah Baru)</label>
                        <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" id="image" name="image" accept="image/*">
                        <div class="mt-2 flex gap-4">
                            <?php if ($editItem && !empty($editItem->image_path)): ?>
                                <div>
                                    <small class="text-gray-500 block mb-1">Saat ini:</small>
                                    <img src="../<?php echo htmlspecialchars($editItem->image_path); ?>" class="rounded border border-gray-200" style="max-width: 150px;" alt="Current">
                                </div>
                            <?php endif; ?>
                            <div id="preview-container" class="hidden">
                                <small class="text-gray-500 block mb-1">Pratinjau (baru):</small>
                                <img id="new_image_preview" class="rounded border border-gray-200" style="max-width: 150px;" alt="Preview">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="item_order" class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="item_order" name="item_order" value="<?php echo isset($editItem) ? (int)$editItem->item_order : 0; ?>" placeholder="0">
                        <p class="text-xs text-gray-500 mt-1">Nomor urutan untuk menentukan posisi carousel (semakin kecil semakin awal)</p>
                    </div>
                </div>

                <div class="mt-6 flex gap-2">
                    <button type="submit" style="padding: 0.5rem 1rem; background-color: #D4AF37; color: #1A1A1A; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; display: flex; align-items: center;"
                            onmouseover="this.style.backgroundColor='#B5952F'; this.style.transform='translateY(-1px)';"
                            onmouseout="this.style.backgroundColor='#D4AF37'; this.style.transform='translateY(0)';">
                        <i class="bi bi-check-circle mr-2"></i> <?php echo isset($editItem) ? 'Perbarui Item' : 'Tambah Item'; ?>
                    </button>
                    <?php if ($editItem): ?>
                        <a href="carousel.php" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold">Batal Edit</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- List Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-2">
            <h5 class="font-bold text-lg">Daftar Item Carousel</h5>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label for="perPageSelect" class="text-sm text-gray-600">Tampilkan:</label>
                    <select id="perPageSelect" class="text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500" onchange="changePerPage(this.value)">
                        <option value="5" <?php echo ($itemsPerPage == 5) ? 'selected' : ''; ?>>5</option>
                        <option value="10" <?php echo ($itemsPerPage == 10) ? 'selected' : ''; ?>>10</option>
                        <option value="20" <?php echo ($itemsPerPage == 20) ? 'selected' : ''; ?>>20</option>
                        <option value="50" <?php echo ($itemsPerPage == 50) ? 'selected' : ''; ?>>50</option>
                    </select>
                    <span class="text-sm text-gray-600">data</span>
                </div>
                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full border border-gray-200"><?php echo $totalItems; ?> Items</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider pl-8 w-20">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Gambar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Urutan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider pr-8 w-32"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (count($allCarouselItems) > 0): ?>
                        <?php 
                        $number = $offset + 1;
                        foreach ($carouselItems as $item): 
                        ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 pl-8 text-center font-medium"><?php echo $number++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="../<?php echo htmlspecialchars($item->image_path); ?>" class="rounded shadow-sm object-cover h-20 w-32 border border-gray-200" alt="Carousel">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($item->title ?? '-'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"><?php echo (int)$item->item_order; ?></span>
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
                                                <a href="carousel.php?action=edit&id=<?php echo (int)$item->id; ?>&page=<?php echo $currentPage; ?>" 
                                                   style="display: flex; align-items: center; padding: 0.625rem 0.75rem; color: #1F2937; border-radius: 0.375rem; text-decoration: none; transition: all 0.15s; font-size: 0.875rem;"
                                                   onmouseover="this.style.backgroundColor='#EFF6FF'; this.style.color='#1D4ED8';"
                                                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='#1F2937';">
                                                    <i class="bi bi-pencil-square" style="margin-right: 0.625rem; font-size: 0.875rem;"></i>
                                                    <span>Edit Item</span>
                                                </a>
                                                <div style="height: 1px; background-color: #E5E7EB; margin: 0.25rem 0;"></div>
                                                <button type="button"
                                                   onclick="showDeleteConfirm(<?php echo (int)$item->id; ?>, '<?php echo htmlspecialchars($item->title ?? 'Item ini', ENT_QUOTES); ?>', <?php echo $currentPage; ?>)"
                                                   style="width: 100%; display: flex; align-items: center; padding: 0.625rem 0.75rem; color: #DC2626; border-radius: 0.375rem; background: none; border: none; cursor: pointer; transition: all 0.15s; font-size: 0.875rem; text-align: left;"
                                                   onmouseover="this.style.backgroundColor='#FEE2E2'; this.style.color='#B91C1C';"
                                                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='#DC2626';">
                                                    <i class="bi bi-trash" style="margin-right: 0.625rem; font-size: 0.875rem;"></i>
                                                    <span>Hapus Item</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="bi bi-image text-6xl block mb-4 opacity-50"></i>
                                <p>Belum ada item carousel.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-center">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <a href="?page=<?php echo max(1, $currentPage - 1); ?>&per_page=<?php echo $itemsPerPage; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo ($currentPage <= 1) ? 'pointer-events-none opacity-50' : ''; ?>">
                        <span class="sr-only">Previous</span>
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&per_page=<?php echo $itemsPerPage; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo ($currentPage == $i) ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'text-gray-500 hover:bg-gray-50'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    <a href="?page=<?php echo min($totalPages, $currentPage + 1); ?>&per_page=<?php echo $itemsPerPage; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo ($currentPage >= $totalPages) ? 'pointer-events-none opacity-50' : ''; ?>">
                        <span class="sr-only">Next</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </nav>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         aria-labelledby="delete-modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 @click="showDeleteModal = false"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="showDeleteModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="bi bi-exclamation-triangle text-red-600" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="delete-modal-title">
                                Hapus Item Carousel
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin menghapus <strong x-text="deleteTitle"></strong>? 
                                    Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <a x-bind:href="'carousel.php?action=delete&id=' + deleteId + '&page=<?php echo $currentPage; ?>'" 
                       style="display: inline-flex; justify-content: center; align-items: center; width: 100%; padding: 0.5rem 1rem; background-color: #DC2626; color: white; border-radius: 0.5rem; font-weight: 600; text-decoration: none; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
                       onmouseover="this.style.backgroundColor='#B91C1C'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';"
                       onmouseout="this.style.backgroundColor='#DC2626'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';"
                       class="sm:w-auto sm:text-sm">
                        <i class="bi bi-trash mr-2"></i> Ya, Hapus Item
                    </a>
                    <button type="button" 
                            @click="showDeleteModal = false" 
                            style="display: inline-flex; justify-content: center; align-items: center; width: 100%; margin-top: 0.75rem; padding: 0.5rem 1rem; border: 2px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; background: white; cursor: pointer; transition: all 0.2s;"
                            onmouseover="this.style.borderColor='#D1D5DB'; this.style.backgroundColor='#F9FAFB';"
                            onmouseout="this.style.borderColor='#E5E7EB'; this.style.backgroundColor='white';"
                            class="sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('image');
  const preview = document.getElementById('new_image_preview');
  const previewContainer = document.getElementById('preview-container');
  
  if (input && preview) {
    input.addEventListener('change', function () {
      const file = this.files && this.files[0] ? this.files[0] : null;
      if (file) {
        preview.src = URL.createObjectURL(file);
        previewContainer.classList.remove('hidden');
      } else {
        preview.src = '';
        previewContainer.classList.add('hidden');
      }
    });
  }
  
  window.changePerPage = function(perPage) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', perPage);
    url.searchParams.set('page', '1');
    window.location.href = url.toString();
  };
  
  // Delete confirmation modal
  window.showDeleteConfirm = function(id, title, page) {
    const modal = document.getElementById('deleteModal');
    const itemTitle = document.getElementById('deleteItemTitle');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    
    itemTitle.textContent = title;
    confirmBtn.href = 'carousel.php?action=delete&id=' + id + '&page=' + page;
    
    modal.classList.remove('hidden');
    setTimeout(() => {
      modal.querySelector('.modal-content').classList.add('show');
    }, 10);
  };
  
  window.hideDeleteConfirm = function() {
    const modal = document.getElementById('deleteModal');
    modal.querySelector('.modal-content').classList.remove('show');
    setTimeout(() => {
      modal.classList.add('hidden');
    }, 200);
  };
});
</script>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;" onclick="hideDeleteConfirm()">
    <div class="modal-content" style="background: white; border-radius: 0.75rem; max-width: 28rem; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out;" onclick="event.stopPropagation()">
        <div style="padding: 1.5rem;">
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; border-radius: 50%; background-color: #FEE2E2;">
                    <i class="bi bi-exclamation-triangle" style="font-size: 1.5rem; color: #DC2626;"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0 0 0.5rem 0;">Hapus Item Carousel</h3>
                    <p style="font-size: 0.875rem; color: #6B7280; margin: 0;">
                        Apakah Anda yakin ingin menghapus <strong id="deleteItemTitle" style="color: #111827;"></strong>? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>
        </div>
        <div style="background-color: #F9FAFB; padding: 1rem 1.5rem; border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; display: flex; gap: 0.75rem; justify-content: flex-end;">
            <button type="button" onclick="hideDeleteConfirm()" 
                    style="padding: 0.5rem 1rem; border: 2px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; background: white; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                    onmouseover="this.style.borderColor='#D1D5DB'; this.style.backgroundColor='#F3F4F6';"
                    onmouseout="this.style.borderColor='#E5E7EB'; this.style.backgroundColor='white';">
                Batal
            </button>
            <a id="confirmDeleteBtn" href="#" 
               style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #DC2626; color: white; border-radius: 0.5rem; font-weight: 600; text-decoration: none; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 0.875rem;"
               onmouseover="this.style.backgroundColor='#B91C1C'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';"
               onmouseout="this.style.backgroundColor='#DC2626'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';">
                <i class="bi bi-trash mr-2"></i> Ya, Hapus Item
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

