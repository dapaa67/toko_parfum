<?php
// Cek status sesi sebelum memulai. Ini mencegah Notice jika sesi sudah aktif.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');

require_once '../models/ParfumManager.php';
require_once '../models/Parfum.php';

$parfumManager = new ParfumManager();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // SEMUA PENGAMBILAN DATA DARI $_POST
    $id = $_POST['id'] ?? null;
    $nama = $_POST['nama'] ?? null;
    $merek = $_POST['merek'] ?? null;
    $kategori = $_POST['kategori'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $ukuran = (int)($_POST['ukuran'] ?? 0);
    $harga = (int)($_POST['harga'] ?? 0);
    $stok = (int)($_POST['stok'] ?? 0);
    $deskripsi = $_POST['deskripsi'] ?? null;
    $image_path = $_POST['current_image_path'] ?? null; // path gambar saat ini (untuk edit)
    $is_best_seller = isset($_POST['is_best_seller']) ? 1 : 0; // flag best seller

    // Preserve harga & stok ketika edit (karena field dihapus dari form)
    if ($id) {
        $existingForHS = $parfumManager->readById((int)$id);
        if ($existingForHS) {
            $harga = (int)$existingForHS->getHarga();
            $stok = (int)$existingForHS->getStok();
        }
    }
 
    // Handle image upload (optional)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../img/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $imageFileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $imageFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            // Hapus gambar lama jika update dan ada gambar lama
            if ($id && $image_path && file_exists('../' . $image_path)) {
                @unlink('../' . $image_path);
            }
            // Simpan path relatif untuk DB
            $image_path = 'img/products/' . $imageFileName;
        } else {
            $message = "Error uploading image.";
        }
    }

    $parfum = new Parfum();
    
    // Validasi dasar
    if ($nama && $merek && $gender && $ukuran > 0) {
        
        if ($id) {
            $parfum->setId($id);
        }
        $parfum->setNama($nama);
        $parfum->setMerek($merek);
        $parfum->setKategori($kategori);
        $parfum->setGender($gender);
        $parfum->setUkuran($ukuran);
        $parfum->setHarga($harga);
        $parfum->setStok($stok);
        $parfum->setDeskripsi($deskripsi);
        $parfum->setImagePath($image_path); // simpan path gambar ke model
        $parfum->setIsBestSeller($is_best_seller); // simpan flag best seller

        $success = false;
        if ($id) {
            $success = $parfumManager->update($parfum);
            $message = $success ? 'Parfum berhasil diubah!' : 'Gagal mengubah parfum.';
        } else {
            $success = $parfumManager->create($parfum);
            $message = $success ? 'Parfum berhasil ditambahkan!' : 'Gagal menambahkan parfum.';
        }
    } else {
        $message = "Gagal: Pastikan field Nama, Merek, Gender, dan Ukuran terisi dengan benar.";
        $success = false;
    }

    $_SESSION['message'] = $message;
    header('Location: products.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $idToDelete = (int)$_GET['id'];

    // Hapus file gambar jika ada
    $existing = $parfumManager->readById($idToDelete);
    if ($existing && $existing->getImagePath() && file_exists('../' . $existing->getImagePath())) {
        @unlink('../' . $existing->getImagePath());
    }

    $success = $parfumManager->delete($idToDelete);
    $message = $success ? 'Parfum berhasil dihapus!' : 'Gagal menghapus parfum.';
    $_SESSION['message'] = $message;
    header('Location: products.php');
    exit();
}

$parfums = $parfumManager->readAll();

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - Admin</title>
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
            <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    
                </div> 

                <?php if ($message): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-1"></i> <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['action']) && ($_GET['action'] === 'add' || $_GET['action'] === 'edit')): ?>
                    <?php
                    $editParfum = null;
                    if ($_GET['action'] === 'edit' && isset($_GET['id'])) {
                        $idToEdit = (int)$_GET['id'];
                        // Pastikan ParfumManager memiliki metode readById yang benar
                        $editParfum = $parfumManager->readById($idToEdit);
                    }
                    ?>
                    <!-- BLOK FORM TAMBAH/EDIT PARFUM LENGKAP -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-body">
                            <?php echo $_GET['action'] === 'edit' ? 'Ubah Parfum' : 'Tambah Parfum Baru'; ?>
                        </div>
                        <div class="card-body">
                            <form action="products.php" method="POST" enctype="multipart/form-data">
                                <?php if ($editParfum): ?>
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editParfum->getId()); ?>">
                                <?php endif; ?>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Parfum" value="<?php echo $editParfum ? htmlspecialchars($editParfum->getNama()) : ''; ?>" required>
                                    <label for="nama">Nama Parfum</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="merek" name="merek" placeholder="Merek" value="<?php echo $editParfum ? htmlspecialchars($editParfum->getMerek()) : ''; ?>" required>
                                    <label for="merek">Merek</label>
                                </div>

                                <div class="mb-3">
                                    <label for="kategori" class="form-label">Kategori</label>
                                    <?php 
                                        $kategoriOptions = ['Floral','Fresh','Oriental','Woody','Citrus','Gourmand'];
                                        $selectedKategori = $editParfum ? $editParfum->getKategori() : '';
                                        if ($selectedKategori && !in_array($selectedKategori, $kategoriOptions)) {
                                            array_unshift($kategoriOptions, $selectedKategori);
                                        }
                                    ?>
                                    <select class="form-select" id="kategori" name="kategori" required>
                                        <?php foreach ($kategoriOptions as $opt): ?>
                                            <option value="<?php echo htmlspecialchars($opt); ?>" <?php echo ($selectedKategori === $opt) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($opt); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <?php $selectedGender = $editParfum ? $editParfum->getGender() : ''; ?>
                                        <option value="Male" <?php echo $selectedGender === 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo $selectedGender === 'Female' ? 'selected' : ''; ?>>Female</option>
                                        <option value="Unisex" <?php echo $selectedGender === 'Unisex' ? 'selected' : ''; ?>>Unisex</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="ukuran" class="form-label">Ukuran (ml)</label>
                                    <?php 
                                        $sizeOptions = [50, 100, 200];
                                        $selectedUkuran = $editParfum ? (int)$editParfum->getUkuran() : 0;
                                        if ($selectedUkuran && !in_array($selectedUkuran, $sizeOptions)) {
                                            array_unshift($sizeOptions, $selectedUkuran);
                                        }
                                    ?>
                                    <select class="form-select" id="ukuran" name="ukuran" required>
                                        <?php foreach ($sizeOptions as $opt): ?>
                                            <option value="<?php echo (int)$opt; ?>" <?php echo ($selectedUkuran === (int)$opt) ? 'selected' : ''; ?>>
                                                <?php echo (int)$opt; ?> ml
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Harga field removed as per request -->

                                <!-- Stok field removed as per request -->

                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo $editParfum ? htmlspecialchars($editParfum->getDeskripsi()) : ''; ?></textarea>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_best_seller" name="is_best_seller" value="1" <?php echo ($editParfum && $editParfum->getIsBestSeller()) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_best_seller">Tandai sebagai Best Seller</label>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Gambar Produk</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <div class="mt-2 d-flex align-items-start gap-3">
                                        <?php if ($editParfum && $editParfum->getImagePath()): ?>
                                            <div>
                                                <small class="text-muted d-block">Gambar saat ini:</small>
                                                <img src="../<?php echo htmlspecialchars($editParfum->getImagePath()); ?>" class="img-thumbnail rounded" style="max-width: 240px;" alt="Gambar Saat Ini">
                                            </div>
                                            <input type="hidden" name="current_image_path" value="<?php echo htmlspecialchars($editParfum->getImagePath()); ?>">
                                        <?php elseif ($editParfum): ?>
                                            <input type="hidden" name="current_image_path" value="">
                                        <?php endif; ?>
                                        <div>
                                            <small class="text-muted d-block">Preview (baru):</small>
                                            <img id="new_image_preview" class="img-thumbnail rounded d-none" style="max-width: 240px;" alt="Preview">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check2-circle me-1"></i> <?php echo $_GET['action'] === 'edit' ? 'Update Parfum' : 'Simpan Parfum'; ?>
                                </button>
                                <a href="products.php" class="btn btn-outline-secondary ms-2">Batal</a>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- BLOK TABEL DAFTAR PRODUK -->
                    <h3>Daftar Produk</h3>
                    <form method="get" action="products.php" class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="row g-2 align-items-end">
                                <div class="col-12 col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="q" name="q" placeholder="Cari nama/merek/kategori" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                                        <label for="q">Cari (nama/merek/kategori)</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="form-floating">
                                        <select class="form-select" id="filter_kategori" name="kategori">
                                            <option value="">Semua</option>
                                            <?php
                                            $kategoriOptions = ['Floral','Fresh','Oriental','Woody','Citrus','Gourmand'];
                                            $selectedKategoriFilter = $_GET['kategori'] ?? '';
                                            foreach ($kategoriOptions as $opt) {
                                                $selected = ($selectedKategoriFilter === $opt) ? 'selected' : '';
                                                echo '<option value="'.htmlspecialchars($opt).'" '.$selected.'>'.htmlspecialchars($opt).'</option>';
                                            }
                                            ?>
                                        </select>
                                        <label for="filter_kategori">Kategori</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="form-floating">
                                        <select class="form-select" id="filter_gender" name="gender">
                                            <?php $selectedGenderFilter = $_GET['gender'] ?? ''; ?>
                                            <option value="" <?php echo $selectedGenderFilter==='' ? 'selected' : ''; ?>>Semua</option>
                                            <option value="Male" <?php echo $selectedGenderFilter==='Male' ? 'selected' : ''; ?>>Male</option>
                                            <option value="Female" <?php echo $selectedGenderFilter==='Female' ? 'selected' : ''; ?>>Female</option>
                                            <option value="Unisex" <?php echo $selectedGenderFilter==='Unisex' ? 'selected' : ''; ?>>Unisex</option>
                                        </select>
                                        <label for="filter_gender">Gender</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="form-floating">
                                        <select class="form-select" id="filter_ukuran" name="ukuran">
                                            <?php $selectedUkuranFilter = $_GET['ukuran'] ?? ''; ?>
                                            <option value="" <?php echo $selectedUkuranFilter === '' ? 'selected' : ''; ?>>Semua</option>
                                            <?php foreach ([50,100,200] as $opt): ?>
                                                <option value="<?php echo (int)$opt; ?>" <?php echo ((string)$selectedUkuranFilter === (string)$opt) ? 'selected' : ''; ?>><?php echo (int)$opt; ?> ml</option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label for="filter_ukuran">Ukuran</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="form-check mb-1">
                                        <?php $bestFilter = isset($_GET['best']) ? '1' : ''; ?>
                                        <input class="form-check-input" type="checkbox" value="1" id="filter_best" name="best" <?php echo $bestFilter === '1' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="filter_best">Best Seller</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <?php $perPageSel = $_GET['per_page'] ?? '5'; ?>
                                    <div class="form-floating">
                                        <select class="form-select" id="per_page" name="per_page">
                                            <option value="5" <?php echo ($perPageSel === '5') ? 'selected' : ''; ?>>5</option>
                                            <option value="10" <?php echo ($perPageSel === '10') ? 'selected' : ''; ?>>10</option>
                                            <option value="20" <?php echo ($perPageSel === '20') ? 'selected' : ''; ?>>20</option>
                                            <option value="25" <?php echo ($perPageSel === '25') ? 'selected' : ''; ?>>25</option>
                                            <option value="50" <?php echo ($perPageSel === '50') ? 'selected' : ''; ?>>50</option>
                                            <option value="100" <?php echo ($perPageSel === '100') ? 'selected' : ''; ?>>100</option>
                                            <option value="all" <?php echo ($perPageSel === 'all') ? 'selected' : ''; ?>>All</option>
                                        </select>
                                        <label for="per_page">Tampilkan</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2">
                                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
                                </div>
                                <div class="col-12 col-md-2">
                                    <a href="products.php" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
                                </div>
                                <div class="col-12 col-md-2">
                                    <a href="products.php?action=add" class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    // DB-backed pagination and filters
                    $q  = isset($_GET['q']) ? trim($_GET['q']) : '';
                    $fk = $_GET['kategori'] ?? '';
                    $fg = $_GET['gender'] ?? '';
                    $fu = $_GET['ukuran'] ?? '';
                    $fb = isset($_GET['best']) ? '1' : '';
                    $perPageSel = $_GET['per_page'] ?? '5';
                    $page = max(1, (int)($_GET['page'] ?? 1));

                    // Normalize allowed per-page values
                    $allowedPer = ['5','10','20','25','50','100','all'];
                    if (!in_array($perPageSel, $allowedPer, true)) {
                        $perPageSel = '5';
                    }

                    // Count total matching rows
                    $totalFiltered = $parfumManager->countWithFilters($q, $fk, $fg, $fu, $fb);

                    // Read the current page
                    $pagedParfums = [];
                    $totalPages = 1;
                    if ($perPageSel === 'all') {
                        $perAll = max(1, (int)$totalFiltered);
                        $pagedParfums = $parfumManager->readPaginated(1, $perAll, $q, $fk, $fg, $fu, $fb);
                        $totalPages = 1;
                        $page = 1;
                    } else {
                        $perPage = max(1, (int)$perPageSel);
                        $totalPages = max(1, (int)ceil($totalFiltered / $perPage));
                        if ($page > $totalPages) {
                            $page = $totalPages;
                        }
                        $pagedParfums = $parfumManager->readPaginated($page, $perPage, $q, $fk, $fg, $fu, $fb);
                    }
                    $showingCount = is_array($pagedParfums) ? count($pagedParfums) : 0;

                    // Build query strings for pagination links while preserving filters
                    $baseParams = [
                        'q' => $q,
                        'kategori' => $fk,
                        'gender' => $fg,
                        'ukuran' => $fu,
                        'per_page' => $perPageSel
                    ];
                    if ($fb === '1') {
                        $baseParams['best'] = '1';
                    }
                    $prevPage = max(1, $page - 1);
                    $nextPage = min($totalPages, $page + 1);
                    $qsPrev = http_build_query(array_merge($baseParams, ['page' => $prevPage]));
                    $qsNext = http_build_query(array_merge($baseParams, ['page' => $nextPage]));
                    $isPrevDisabled = ($page <= 1);
                    $isNextDisabled = ($page >= $totalPages);
                    ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Menampilkan <?php echo (int)$showingCount; ?> dari <?php echo (int)$totalFiltered; ?> data</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Gambar</th>
                                    <th>Merek</th>
                                    <th>Kategori</th>
                                    <th>Gender</th>
                                    <th>Ukuran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pagedParfums) && count($pagedParfums) > 0): ?>
                                    <?php foreach ($pagedParfums as $p): ?>
                                        <tr>
                                            <td>
                                                <?php echo htmlspecialchars($p->getId()); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($p->getNama()); ?> <?php if ($p->getIsBestSeller()) { ?><span class="badge text-bg-success">Best</span><?php } ?></td>
                                            <td>
                                                <?php $img = $p->getImagePath() ? '../' . $p->getImagePath() : '../img/parfum_placeholder.png'; ?>
                                                <img src="<?php echo htmlspecialchars($img); ?>" class="img-thumbnail rounded" style="max-width: 120px;" alt="Gambar">
                                            </td>
                                            <td><?php echo htmlspecialchars($p->getMerek()); ?></td>
                                            <td><span class="badge text-bg-light"><?php echo htmlspecialchars($p->getKategori()); ?></span></td>
                                            <td><span class="badge text-bg-secondary"><?php echo htmlspecialchars($p->getGender()); ?></span></td>
                                            <td><?php echo htmlspecialchars($p->getUkuran()); ?> ml</td>
                                            <td>
                                                <a href="products.php?action=edit&id=<?php echo htmlspecialchars($p->getId()); ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                                </a>
                                                <a href="products.php?action=delete&id=<?php echo htmlspecialchars($p->getId()); ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin menghapus parfum ini?');">
                                                    <i class="bi bi-trash me-1"></i>Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Data tidak ditemukan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($perPageSel !== 'all' && $totalFiltered > 0): ?>
                    <nav aria-label="Pagination">
                        <ul class="pagination justify-content-end mt-3">
                            <li class="page-item <?php echo $isPrevDisabled ? 'disabled' : ''; ?>">
                                <a class="page-link" href="products.php?<?php echo htmlspecialchars($qsPrev); ?>" tabindex="<?php echo $isPrevDisabled ? '-1' : '0'; ?>">Previous</a>
                            </li>
                            <li class="page-item disabled">
                                <span class="page-link">Page <?php echo (int)$page; ?> / <?php echo (int)$totalPages; ?></span>
                            </li>
                            <li class="page-item <?php echo $isNextDisabled ? 'disabled' : ''; ?>">
                                <a class="page-link" href="products.php?<?php echo htmlspecialchars($qsNext); ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <!-- Image preview -->
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
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
