<?php
session_start();
require_once '../models/CarouselManager.php';
require_once '../models/AuthManager.php'; // Include AuthManager for checkRole

// Check if user is logged in and is admin
AuthManager::checkRole('admin');

$carouselManager = new CarouselManager();
$message = '';
$message_type = 'info'; // Default: 'info', bisa diubah jadi 'success' atau 'danger'

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $link = $_POST['link'] ?? null;
    $item_order = $_POST['item_order'] ?? 0;
    $image_path = $_POST['current_image_path'] ?? null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../img/carousel/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $imageFileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $imageFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            // Delete old image if updating
            if ($id && $image_path && file_exists('../' . $image_path)) { // Adjusted path for unlink
                unlink('../' . $image_path);
            }
            $image_path = 'img/carousel/' . $imageFileName; // Path to store in DB
        } else {
            $message = "Gagal mengunggah gambar.";
            $message_type = 'danger';
        }
    }

    if ($id) {
        // Update existing item
        if ($carouselManager->update($id, $image_path, $title, $description, $link, $item_order)) {
            $message = "Item carousel berhasil diubah!";
            $message_type = 'success';
        } else {
            $message = "Gagal mengubah item carousel.";
            $message_type = 'danger';
        }
    } else {
        // Add new item
        // Ensure an image is provided for new items
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

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $idToDelete = $_GET['id'];
    $item = $carouselManager->readById($idToDelete);
    if ($item) {
        // Delete image file
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

$carouselItems = $carouselManager->readAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Carousel - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../sidebar.css">
    </head>
<body>
<nav class="navbar navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">ParfumMy Admin</span>
        <div class="d-flex align-items-center">
            <a href="../index.php" class="btn btn-sm btn-outline-light me-2">
                <i class="bi bi-box-arrow-up-right me-1"></i> View Site
            </a>
            <a href="../logout.php" class="btn btn-sm btn-warning text-dark">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </a>
        </div>
    </div>
</nav>
    <div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>
        <!-- Main content -->
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h3 mb-0">Manage Carousel Items</h1>
            </div>
            <?php if ($message): ?>
                <?php
                    // Tentukan ikon dan kelas alert berdasarkan tipe pesan
                    $alert_class = 'alert-info'; // default
                    if ($message_type === 'success') $alert_class = 'alert-success';
                    if ($message_type === 'danger') $alert_class = 'alert-danger';
                    $icon_class = ($message_type === 'danger') ? 'bi-exclamation-triangle-fill' : 'bi-info-circle-fill';
                ?>
                <div class="alert <?php echo $alert_class; ?> alert-dismissible fade show" role="alert">
                    <i class="bi <?php echo $icon_class; ?> me-2"></i> <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Add/Edit Form -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-body">
                    <h5 class="mb-0"><?php echo isset($_GET['action']) && $_GET['action'] === 'edit' ? 'Edit Carousel Item' : 'Add New Carousel Item'; ?></h5>
                </div>
                <div class="card-body">
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
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="<?php echo htmlspecialchars($editItem->title ?? ''); ?>">
                                    <label for="title">Title</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="url" class="form-control" id="link" name="link" placeholder="https://example.com" value="<?php echo htmlspecialchars($editItem->link ?? ''); ?>">
                                    <label for="link">Link (optional)</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Description (optional)</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Short description..."><?php echo htmlspecialchars($editItem->description ?? ''); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="image" class="form-label">Image (Upload New)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="mt-2 d-flex align-items-start gap-3">
                                    <?php if ($editItem && !empty($editItem->image_path)): ?>
                                        <div>
                                            <small class="text-muted d-block">Current:</small>
                                            <img src="../<?php echo htmlspecialchars($editItem->image_path); ?>" class="img-thumbnail rounded" style="max-width: 240px;" alt="Current">
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <small class="text-muted d-block">Preview (new):</small>
                                        <img id="new_image_preview" class="img-thumbnail rounded d-none" style="max-width: 240px;" alt="Preview">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-2">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="item_order" name="item_order" value="<?php echo isset($editItem) ? (int)$editItem->item_order : 0; ?>" placeholder="0">
                                    <label for="item_order">Order</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> <?php echo isset($editItem) ? 'Update Item' : 'Tambah Item'; ?>
                            </button>
                            <?php if ($editItem): ?>
                                <a href="carousel.php" class="btn btn-outline-secondary ms-2">Batal Edit</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Carousel Items List -->
            <h3>Existing Carousel Items</h3>
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width:60px;">ID</th>
                                <th style="width:180px;">Image</th>
                                <th>Title</th>
                                <th>Order</th>
                                <th style="width:160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($carouselItems) > 0): ?>
                                <?php foreach ($carouselItems as $item): ?>
                                    <tr>
                                        <td><?php echo (int)$item->id; ?></td>
                                        <td>
                                            <img src="../<?php echo htmlspecialchars($item->image_path); ?>" class="img-thumbnail rounded" style="max-width:160px;" alt="Carousel">
                                        </td>
                                        <td><?php echo htmlspecialchars($item->title ?? ''); ?></td>
                                        <td><?php echo (int)$item->item_order; ?></td>
                                        <td>
                                            <a href="carousel.php?action=edit&id=<?php echo (int)$item->id; ?>" class="btn btn-sm btn-outline-primary me-1">
                                                <i class="bi bi-pencil-square me-1"></i>Edit
                                            </a>
                                            <a href="carousel.php?action=delete&id=<?php echo (int)$item->id; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus item ini?');">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No carousel items found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
        </main>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
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
});
</script>
<script src="../sidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>