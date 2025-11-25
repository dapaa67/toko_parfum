<?php
// models/ParfumManager.php

// Pastikan file DB.php & Parfum.php di-include dengan path yang benar
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/Parfum.php';

class ParfumManager {
    private $db; // Instance DB
    private $conn; // Koneksi PDO (the actual PDO object)

    public function __construct() {
        $this->db = new DB();
        $this->conn = $this->db->getConnection(); // Pastikan getConnection() mengembalikan objek PDO
    }

    // C (Create): Menambahkan Parfum baru
    public function create(Parfum $parfum): bool {
        $sql = "INSERT INTO parfums (nama, merek, kategori, gender, ukuran, harga, stok, deskripsi, image_path, is_best_seller)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        // MENGGUNAKAN $this->conn untuk prepare
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $parfum->getNama(),
            $parfum->getMerek(),
            $parfum->getKategori(),
            $parfum->getGender(),
            $parfum->getUkuran(),
            $parfum->getHarga(),
            $parfum->getStok(),
            $parfum->getDeskripsi(),
            $parfum->getImagePath(),
            $parfum->getIsBestSeller()
        ]);
    }

    // R (Read): Mengambil semua data Parfum
    public function readAll() {
        // Ganti nama tabel: parfum -> parfums
        $sql = "SELECT * FROM parfums";
        $stmt = $this->conn->query($sql);
        $parfums = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $p = new Parfum();
            $p->setId($row['id']);
            $p->setNama($row['nama']);
            $p->setMerek($row['merek']);
            $p->setKategori($row['kategori']);
            $p->setGender($row['gender']); // Jangan lupa set Gender
            $p->setUkuran($row['ukuran']); // Jangan lupa set Ukuran
            $p->setHarga($row['harga']);
            $p->setStok($row['stok']);
            $p->setDeskripsi($row['deskripsi']);
            if (isset($row['image_path'])) {
                $p->setImagePath($row['image_path']);
            }
            if (isset($row['is_best_seller'])) {
                $p->setIsBestSeller($row['is_best_seller']);
            }
            $parfums[] = $p;
        }
        return $parfums;
    }
    
    // R (Read): Mengambil 1 data Parfum berdasarkan ID
    public function readById($id) {
        // Ganti nama tabel: parfum -> parfums
        $sql = "SELECT * FROM parfums WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $p = new Parfum();
            $p->setId($row['id']);
            $p->setNama($row['nama']);
            $p->setMerek($row['merek']);
            $p->setKategori($row['kategori']);
            $p->setGender($row['gender']); // Jangan lupa set Gender
            $p->setUkuran($row['ukuran']); // Jangan lupa set Ukuran
            $p->setHarga($row['harga']);
            $p->setStok($row['stok']);
            $p->setDeskripsi($row['deskripsi']);
            if (isset($row['image_path'])) {
                $p->setImagePath($row['image_path']);
            }
            if (isset($row['is_best_seller'])) {
                $p->setIsBestSeller($row['is_best_seller']);
            }
            return $p;
        }
        return null;
    }

    // U (Update): Mengubah data Parfum
    public function update(Parfum $parfum): bool {
        $sql = "UPDATE parfums SET nama=?, merek=?, kategori=?, gender=?, ukuran=?, harga=?, stok=?, deskripsi=?, image_path=?, is_best_seller=?
                WHERE id=?";
        // MENGGUNAKAN $this->conn untuk prepare
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $parfum->getNama(),
            $parfum->getMerek(),
            $parfum->getKategori(),
            $parfum->getGender(),
            $parfum->getUkuran(),
            $parfum->getHarga(),
            $parfum->getStok(),
            $parfum->getDeskripsi(),
            $parfum->getImagePath(),
            $parfum->getIsBestSeller(),
            $parfum->getId()
        ]);
    }

    // D (Delete): Menghapus data Parfum
    public function delete($id) {
        // Ganti nama tabel: parfum -> parfums
        $sql = "DELETE FROM parfums WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Metode untuk update cepat (hanya harga dan stok)
    public function quickUpdate($id, $harga, $stok): bool {
        $sql = "UPDATE parfums SET harga = ?, stok = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$harga, (int)$stok, (int)$id]);
    }


    // Metode untuk mengurangi stok setelah pembelian
    public function updateStock($productId, $quantitySold) {
        // Gunakan klausa WHERE untuk memastikan stok tidak menjadi negatif
        $sql = "UPDATE parfums SET stok = stok - ? WHERE id = ? AND stok >= ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$quantitySold, $productId, $quantitySold]);
        // Kembalikan true HANYA jika 1 baris terpengaruh.
        // Ini memastikan stok benar-benar berkurang dan cukup.
        return $stmt->rowCount() > 0;
    }

    // R (Read): Mengambil beberapa data Parfum secara acak
    public function readRandom($limit = 4) {
        // Ganti nama tabel: parfum -> parfums
        $sql = "SELECT * FROM parfums ORDER BY RAND() LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        $parfums = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $p = new Parfum();
            $p->setId($row['id']);
            $p->setNama($row['nama']);
            $p->setMerek($row['merek']);
            $p->setKategori($row['kategori']);
            $p->setGender($row['gender']); // Jangan lupa set Gender
            $p->setUkuran($row['ukuran']); // Jangan lupa set Ukuran
            $p->setHarga($row['harga']);
            $p->setStok($row['stok']);
            $p->setDeskripsi($row['deskripsi']);
            if (isset($row['image_path'])) {
                $p->setImagePath($row['image_path']);
            }
            if (isset($row['is_best_seller'])) {
                $p->setIsBestSeller($row['is_best_seller']);
            }
            $parfums[] = $p;
        }
        return $parfums;
    }

    // R (Read): Mengambil data Parfum dengan Filter
    public function readWithFilters($genders = [], $sizes = []) {
        // Ganti nama tabel: parfum -> parfums
        $sql = "SELECT * FROM parfums WHERE 1=1";
        $params = [];

        if (!empty($genders)) {
            // PERBAIKAN: Ganti 'kategori' menjadi 'gender' untuk sinkronisasi filter
            $sql .= " AND gender IN (" . implode(',', array_fill(0, count($genders), '?')) . ")";
            $params = array_merge($params, $genders);
        }

        if (!empty($sizes)) {
            // 'size' di filter user dipetakan ke kolom 'ukuran' di database
            $sql .= " AND ukuran IN (" . implode(',', array_fill(0, count($sizes), '?')) . ")";
            $params = array_merge($params, $sizes);
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $parfums = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $p = new Parfum();
            $p->setId($row['id']);
            $p->setNama($row['nama']);
            $p->setMerek($row['merek']);
            $p->setKategori($row['kategori']);
            $p->setGender($row['gender']); // Jangan lupa set Gender
            $p->setUkuran($row['ukuran']); // Jangan lupa set Ukuran
            $p->setHarga($row['harga']);
            $p->setStok($row['stok']);
            $p->setDeskripsi($row['deskripsi']);
            if (isset($row['image_path'])) {
                $p->setImagePath($row['image_path']);
            }
            if (isset($row['is_best_seller'])) {
                $p->setIsBestSeller($row['is_best_seller']);
            }
            $parfums[] = $p;
        }

        return $parfums;
    }
    // R (Read): Mengambil data Parfum Best Seller
    public function readBestSellers($limit = 5) {
        $parfums = [];
        try {
            $sql = "SELECT * FROM parfums WHERE is_best_seller = 1 ORDER BY id DESC LIMIT :limit";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            // Fallback jika kolom belum ada (SQLSTATE 42S22 Unknown column), atau error serupa
            if ($e->getCode() === '42S22' || stripos($e->getMessage(), 'Unknown column') !== false) {
                $sql = "SELECT * FROM parfums ORDER BY RAND() LIMIT :limit";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                throw $e; // lempar ulang jika error lain
            }
        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $p = new Parfum();
            $p->setId($row['id']);
            $p->setNama($row['nama']);
            $p->setMerek($row['merek']);
            $p->setKategori($row['kategori']);
            $p->setGender($row['gender']);
            $p->setUkuran($row['ukuran']);
            $p->setHarga($row['harga']);
            $p->setStok($row['stok']);
            $p->setDeskripsi($row['deskripsi']);
            if (isset($row['image_path'])) {
                $p->setImagePath($row['image_path']);
            }
            if (isset($row['is_best_seller'])) {
                $p->setIsBestSeller($row['is_best_seller']);
            }
            $parfums[] = $p;
        }
        return $parfums;
    }

    // Helper to map DB rows to Parfum instances
    private function mapRowToParfum(array $row): Parfum {
        $p = new Parfum();
        $p->setId($row['id']);
        $p->setNama($row['nama']);
        $p->setMerek($row['merek']);
        $p->setKategori($row['kategori']);
        $p->setGender($row['gender']);
        $p->setUkuran($row['ukuran']);
        $p->setHarga($row['harga']);
        $p->setStok($row['stok']);
        $p->setDeskripsi($row['deskripsi']);
        if (isset($row['image_path'])) {
            $p->setImagePath($row['image_path']);
        }
        if (isset($row['is_best_seller'])) {
            $p->setIsBestSeller($row['is_best_seller']);
        }
        return $p;
    }

    // Count rows with filters (for pagination)
    public function countWithFilters($q = '', $kategori = '', $gender = '', $ukuran = '', $best = ''): int {
        $sql = "SELECT COUNT(*) AS cnt FROM parfums WHERE 1=1";
        $params = [];
        if ($q !== '') {
            $sql .= " AND (LOWER(nama) LIKE :q OR LOWER(merek) LIKE :q OR LOWER(kategori) LIKE :q)";
            $params[':q'] = '%' . strtolower($q) . '%';
        }
        if ($kategori !== '') {
            $sql .= " AND kategori = :kategori";
            $params[':kategori'] = $kategori;
        }
        if ($gender !== '') {
            $sql .= " AND gender = :gender";
            $params[':gender'] = $gender;
        }
        if ($ukuran !== '') {
            $sql .= " AND ukuran = :ukuran";
            $params[':ukuran'] = (int)$ukuran;
        }
        if ($best === '1') {
            $sql .= " AND is_best_seller = 1";
        }
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) {
            if ($k === ':ukuran') {
                $stmt->bindValue($k, (int)$v, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($k, $v);
            }
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['cnt'] : 0;
    }

    // Read paginated rows with filters using LIMIT/OFFSET
    public function readPaginated(int $page, int $perPage, $q = '', $kategori = '', $gender = '', $ukuran = '', $best = ''): array {
        $offset = max(0, ($page - 1) * $perPage);
        $sql = "SELECT * FROM parfums WHERE 1=1";
        $params = [];
        if ($q !== '') {
            $sql .= " AND (LOWER(nama) LIKE :q OR LOWER(merek) LIKE :q OR LOWER(kategori) LIKE :q)";
            $params[':q'] = '%' . strtolower($q) . '%';
        }
        if ($kategori !== '') {
            $sql .= " AND kategori = :kategori";
            $params[':kategori'] = $kategori;
        }
        if ($gender !== '') {
            $sql .= " AND gender = :gender";
            $params[':gender'] = $gender;
        }
        if ($ukuran !== '') {
            $sql .= " AND ukuran = :ukuran";
            $params[':ukuran'] = (int)$ukuran;
        }
        if ($best === '1') {
            $sql .= " AND is_best_seller = 1";
        }
        $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);

        // Bind filter params
        foreach ($params as $k => $v) {
            if ($k === ':ukuran') {
                $stmt->bindValue($k, (int)$v, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($k, $v);
            }
        }
        // Bind pagination params
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        $parfums = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $parfums[] = $this->mapRowToParfum($row);
        }
        return $parfums;
    }
}
?>