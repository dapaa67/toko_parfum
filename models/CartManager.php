<?php
require_once __DIR__ . '/DB.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class CartManager {
    private $conn;
    private $userId;

    public function __construct() {
        // Inisialisasi koneksi DB
        $db = new DB();
        $this->conn = $db->getConnection();
        $this->userId = $_SESSION['user_id'] ?? null;

        // Jika user login dan keranjang di session belum ada, coba muat dari DB
        if ($this->userId && !isset($_SESSION['cart'])) {
            $this->loadCartFromDb();
        } 
        // Jika keranjang di session tidak ada (untuk guest atau user baru)
        else if (!isset($_SESSION['cart'])) {
             $_SESSION['cart'] = [];
        }
    }

    /**
     * Memuat keranjang dari database ke session.
     */
    private function loadCartFromDb() {
        if (!$this->userId) {
            $_SESSION['cart'] = [];
            return;
        }
        $stmt = $this->conn->prepare("SELECT cart_data FROM user_carts WHERE user_id = ?");
        $stmt->execute([$this->userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['cart_data']) {
            $_SESSION['cart'] = json_decode($result['cart_data'], true) ?? [];
        } else {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Menyimpan keranjang dari session ke database.
     */
    private function saveCartToDb() {
        if (!$this->userId) {
            return; // Jangan simpan jika user tidak login
        }

        $cartDataJson = json_encode($_SESSION['cart']);

        // Gunakan INSERT ... ON DUPLICATE KEY UPDATE untuk efisiensi
        $sql = "INSERT INTO user_carts (user_id, cart_data) VALUES (?, ?)
                ON DUPLICATE KEY UPDATE cart_data = VALUES(cart_data)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$this->userId, $cartDataJson]);
    }

    /**
     * Menambahkan produk ke keranjang atau mengupdate kuantitas jika sudah ada.
     */
    public function add($productId, $quantity = 1, $productDetails = []) {
        $productId = (int)$productId;
        $quantity = (int)$quantity;

        if ($quantity <= 0) return;

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'nama' => $productDetails['nama'] ?? 'Nama Produk Tidak Diketahui',
                'harga' => $productDetails['harga'] ?? 0,
                'image_path' => $productDetails['image_path'] ?? 'img/parfum_placeholder.png'
            ];
        }
        $this->saveCartToDb();
    }

    /**
     * Mengupdate kuantitas produk di keranjang.
     */
    public function update($productId, $quantity) {
        $productId = (int)$productId;
        $quantity = (int)$quantity;

        if (isset($_SESSION['cart'][$productId])) {
            if ($quantity > 0) {
                $_SESSION['cart'][$productId]['quantity'] = $quantity;
            } else {
                $this->remove($productId);
            }
            $this->saveCartToDb();
        }
    }

    /**
     * Menghapus produk dari keranjang.
     */
    public function remove($productId) {
        $productId = (int)$productId;
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            $this->saveCartToDb();
        }
    }

    /**
     * Mengambil semua item di keranjang.
     */
    public function getItems() {
        return $_SESSION['cart'] ?? [];
    }

    /**
     * Menghitung total jumlah item di keranjang.
     */
    public function getTotalItemCount() {
        $total = 0;
        foreach ($_SESSION['cart'] ?? [] as $item) {
            $total += $item['quantity'];
        }
        return $total;
    }

    /**
     * Menghitung total harga dari semua item.
     */
    public function getTotalPrice() {
        $total = 0;
        foreach ($_SESSION['cart'] ?? [] as $item) {
            $total += $item['harga'] * $item['quantity'];
        }
        return $total;
    }

    /**
     * Mengosongkan keranjang di session dan database.
     */
    public function clear() {
        $_SESSION['cart'] = [];
        $this->saveCartToDb();
    }
}
?>
