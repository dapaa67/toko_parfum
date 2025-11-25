<?php
session_start();
require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');

require_once '../models/ParfumManager.php';
require_once '../models/Parfum.php';

$parfumManager = new ParfumManager();
$action = $_REQUEST['action'] ?? '';

function handleImageUpload($currentImagePath = null) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../img/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $imageFileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $imageFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            // Hapus gambar lama jika ada dan sedang update
            if ($currentImagePath && file_exists('../' . $currentImagePath)) {
                unlink('../' . $currentImagePath);
            }
            return 'img/products/' . $imageFileName; // Path untuk disimpan di DB
        }
    }
    return $currentImagePath; // Kembalikan path lama jika tidak ada upload baru
}

switch ($action) {
    case 'create':
    case 'update':
        $parfum = new Parfum();
        if ($action === 'update') {
            $id = (int)$_POST['id'];
            $existingParfum = $parfumManager->readById($id);
            $parfum->setId($id);
            $currentImagePath = $existingParfum ? $existingParfum->getImagePath() : null;
        } else {
            $currentImagePath = null;
        }

        $parfum->setNama($_POST['nama']);
        $parfum->setMerek($_POST['merek']);
        $parfum->setKategori($_POST['kategori']);
        $parfum->setGender($_POST['gender']);
        $parfum->setUkuran((int)$_POST['ukuran']);
        $parfum->setHarga((int)$_POST['harga']);
        $parfum->setStok((int)$_POST['stok']);
        $parfum->setDeskripsi($_POST['deskripsi']);
        $parfum->setIsBestSeller(isset($_POST['is_best_seller']) ? 1 : 0);

        // Handle image upload
        $newImagePath = handleImageUpload($currentImagePath);
        $parfum->setImagePath($newImagePath);

        if ($action === 'create') {
            $result = $parfumManager->create($parfum);
            $_SESSION['message'] = $result ? 'Produk berhasil ditambahkan.' : 'Gagal menambahkan produk.';
        } else {
            $result = $parfumManager->update($parfum);
            $_SESSION['message'] = $result ? 'Produk berhasil diperbarui.' : 'Gagal memperbarui produk.';
        }
        $_SESSION['message_type'] = $result ? 'success' : 'danger';
        break;

    case 'quick_update':
        $id = (int)$_POST['id'];
        $harga = (int)$_POST['harga'];
        $stok = (int)$_POST['stok'];

        // Kita akan buat metode baru di ParfumManager untuk ini
        $result = $parfumManager->quickUpdate($id, $harga, $stok);
        $_SESSION['message'] = $result ? "Stok & harga untuk produk #{$id} berhasil diperbarui." : "Gagal memperbarui produk #{$id}.";
        $_SESSION['message_type'] = $result ? 'success' : 'info';
        // Tidak ada break, biarkan redirect di akhir
        break;

    case 'delete':
        $id = (int)$_GET['id'];
        $parfum = $parfumManager->readById($id);
        if ($parfum) {
            // Hapus gambar terkait jika ada
            $imagePath = $parfum->getImagePath();
            if ($imagePath && file_exists('../' . $imagePath)) {
                unlink('../' . $imagePath);
            }
        }
        $result = $parfumManager->delete($id);
        $_SESSION['message'] = $result ? 'Produk berhasil dihapus.' : 'Gagal menghapus produk.';
        $_SESSION['message_type'] = $result ? 'success' : 'danger';
        break;

    default:
        $_SESSION['message'] = 'Aksi tidak valid.';
        $_SESSION['message_type'] = 'warning';
        break;
}

header('Location: products.php');
exit();
