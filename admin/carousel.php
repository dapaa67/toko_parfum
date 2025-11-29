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
            $message = "Item carousel berhasil dihapus!";
            $message_type = 'success';
        } else {
            $message = "Gagal menghapus item carousel.";
            $message_type = 'danger';
        }
    }
}

$itemsPerPage = 5;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$allCarouselItems = $carouselManager->readAll();
$totalItems = count($allCarouselItems);
$totalPages = ceil($totalItems / $itemsPerPage);
$offset = ($currentPage - 1) * $itemsPerPage;
$carouselItems = array_slice($allCarouselItems, $offset, $itemsPerPage);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Carousel - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <i class="bi bi-flower2 me-2 text-warning"></i>
            <span class="fw-bold">ParfumMy Admin</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i> Admin
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../index.php" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i>Lihat Situs</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-1">Kelola Item Carousel</h1>
                    <p class="text-muted">Atur gambar carousel untuk halaman utama website.</p>
                </div>
            </div>
            <?php if ($message): ?>
                <?php
                    $alert_class = 'alert-info';
                    if ($message_type === 'success') $alert_class = 'alert-success';
                    if ($message_type === 'danger') $alert_class = 'alert-danger';
                    $icon_class = ($message_type === 'danger') ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill';
                ?>
                <div class="alert <?php echo $alert_class; ?> alert-dismissible fade show shadow-sm mb-4" role="alert">
                    <i class="bi <?php echo $icon_class; ?> me-2"></i> <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><?php echo isset($_GET['action']) && $_GET['action'] === 'edit' ? 'Edit Item Carousel' : 'Tambah Item Carousel Baru'; ?></h5>
                </div>
                <div class="card-body p-4">
                    <?php
                    $editItem = null;
                    if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
                        $editItem = $carouselManager->readById($_GET['id']);
                    }
                    ?>
                    <form action="carousel.php" method="POST" enctype="multipart/form-data">
                        <?php if ($editItem): ?>
                            <input type="hidden" name="id" value="<?php echo (int)$editItem->id; ?>">
                            <input type="hidden" name="current_image_path" value="<?php echo htmlspecialchars($editItem->image_path); ?>">
                        <?php endif; ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Judul" value="<?php echo htmlspecialchars($editItem->title ?? ''); ?>">
                                    <label for="title">Judul</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="url" class="form-control" id="link" name="link" placeholder="https://example.com" value="<?php echo htmlspecialchars($editItem->link ?? ''); ?>">
                                    <label for="link">Link (opsional)</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Deskripsi (opsional)</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Deskripsi singkat..."><?php echo htmlspecialchars($editItem->description ?? ''); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="image" class="form-label">Gambar (Unggah Baru)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="mt-2 d-flex align-items-start gap-3">
                                    <?php if ($editItem && !empty($editItem->image_path)): ?>
                                        <div>
                                            <small class="text-muted d-block">Saat ini:</small>
                                            <img src="../<?php echo htmlspecialchars($editItem->image_path); ?>" class="img-thumbnail rounded" style="max-width: 240px;" alt="Current">
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <small class="text-muted d-block">Pratinjau (baru):</small>
                                        <img id="new_image_preview" class="img-thumbnail rounded d-none" style="max-width: 240px;" alt="Preview">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="item_order" class="form-label">Urutan</label>
                                <input type="number" class="form-control" id="item_order" name="item_order" value="<?php echo isset($editItem) ? (int)$editItem->item_order : 0; ?>" placeholder="0">
                                <small class="text-muted">Nomor urutan untuk menentukan posisi carousel (semakin kecil semakin awal)</small>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> <?php echo isset($editItem) ? 'Perbarui Item' : 'Tambah Item'; ?>
                            </button>
                            <?php if ($editItem): ?>
                                <a href="carousel.php" class="btn btn-outline-secondary ms-2">Batal Edit</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">Daftar Item Carousel</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width:80px;">No</th>
                                    <th style="width:200px;">Gambar</th>
                                    <th>Judul</th>
                                    <th style="width:100px;">Urutan</th>
                                    <th class="text-end pe-4" style="width:200px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($allCarouselItems) > 0): ?>
                                    <?php 
                                    $number = $offset + 1;
                                    foreach ($carouselItems as $item): 
                                    ?>
                                        <tr>
                                            <td class="ps-4 text-center fw-medium"><?php echo $number++; ?></td>
                                            <td>
                                                <img src="../<?php echo htmlspecialchars($item->image_path); ?>" class="img-thumbnail rounded shadow-sm" style="max-width:180px; height:100px; object-fit:cover;" alt="Carousel">
                                            </td>
                                            <td class="fw-medium"><?php echo htmlspecialchars($item->title ?? '-'); ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary bg-opacity-75 rounded-pill px-3"><?php echo (int)$item->item_order; ?></span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="btn-group" role="group">
                                                    <a href="carousel.php?action=edit&id=<?php echo (int)$item->id; ?>&page=<?php echo $currentPage; ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-id="<?php echo (int)$item->id; ?>" data-delete-page="<?php echo $currentPage; ?>">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="bi bi-image display-4 d-block mb-3 opacity-50"></i>
                                            Belum ada item carousel.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($totalPages > 1): ?>
                        <div class="card-footer bg-white border-top py-3">
                            <nav aria-label="Navigasi halaman carousel">
                                <ul class="pagination pagination-sm justify-content-center mb-0">
                                    <?php if ($currentPage > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($currentPage < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <p class="mb-0">Apakah Anda yakin ingin menghapus item carousel ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i> Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('image');
  const preview = document.getElementById('new_image_preview');
  if (input && preview) {
    input.addEventListener('change', function () {
      const file = this.files && this.files[0] ? this.files[0] : null;
      if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
      } else {
        preview.src = '';
        preview.classList.add('d-none');
      }
    });
  }
  
  const deleteModal = document.getElementById('deleteModal');
  if (deleteModal) {
    deleteModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const deleteId = button.getAttribute('data-delete-id');
      const deletePage = button.getAttribute('data-delete-page');
      
      const confirmBtn = document.getElementById('confirmDeleteBtn');
      confirmBtn.href = `carousel.php?action=delete&id=${deleteId}&page=${deletePage}`;
    });
  }
});
</script>
</body>
</html>