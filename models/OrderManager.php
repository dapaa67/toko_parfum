<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/ParfumManager.php';

class OrderManager {
    private $conn;
    private $parfumManager;

    public function __construct() {
        $db = new DB();
        $this->conn = $db->getConnection();
        $this->parfumManager = new ParfumManager();
    }

    /**
     * Generate nomor pesanan unik, misalnya: INV/20251116/USER4/XYZ
     *
     * @param int $userId
     * @return string
     */
    private function generateOrderNumber($userId) {
        $datePart = date('Ymd');
        $timePart = date('His');
        $randomPart = random_int(100, 999);
        return sprintf('INV/%s-%s/U%s/%d', $datePart, $timePart, $userId, $randomPart);
    }

    /**
     * Membuat pesanan baru, menyimpan item, dan mengurangi stok produk.
     * Versi ini TANPA transaksi manual untuk menghindari masalah deadlock/lock
     * yang berlebihan pada environment sederhana (XAMPP single-user).
     *
     * @param int $userId
     * @param array $orderDetails (nama_penerima, alamat, telepon, dll)
     * @param array $cartItems
     * @return int|false Order ID jika berhasil, false jika gagal.
     */
    public function createOrder($userId, $orderDetails, $cartItems) {
        // Validasi dasar
        if (empty($userId) || empty($orderDetails) || empty($cartItems)) {
            return false;
        }

        // 1. Cek ketersediaan stok sebelum mulai proses
        foreach ($cartItems as $item) {
            $product = $this->parfumManager->readById($item['product_id']);
            if (!$product || $product->getStok() < $item['quantity']) {
                $stokTersisa = $product ? $product->getStok() : 0;
                $_SESSION['error_message'] = "Stok untuk produk '{$item['nama']}' tidak mencukupi. Diminta: {$item['quantity']}, Tersisa: {$stokTersisa}.";
                return false;
            }
        }

        try {
            // 2. Generate nomor pesanan unik
            $nomorPesanan = $this->generateOrderNumber($userId);

            // 3. Insert ke tabel 'orders' (auto-commit)
            $sqlOrder = "INSERT INTO orders (user_id, nomor_pesanan, nama_penerima, alamat_pengiriman, telepon, total_harga, metode_pembayaran, status)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtOrder = $this->conn->prepare($sqlOrder);
            $stmtOrder->execute([
                $userId,
                $nomorPesanan,
                $orderDetails['nama_penerima'],
                $orderDetails['alamat_pengiriman'],
                $orderDetails['telepon'],
                $orderDetails['total_harga'],
                $orderDetails['metode_pembayaran'],
                'Pending'
            ]);
            $orderId = $this->conn->lastInsertId();

            // 4. Insert setiap item ke 'order_items' dan update stok (auto-commit per statement)
            $sqlItem = "INSERT INTO order_items (order_id, parfum_id, jumlah, harga_saat_beli) VALUES (?, ?, ?, ?)";
            $stmtItem = $this->conn->prepare($sqlItem);

            foreach ($cartItems as $item) {
                // Insert item pesanan
                $stmtItem->execute([
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['harga']
                ]);

                // Kurangi stok produk.
                // Di environment single-user (toko sederhana), ini sudah cukup aman.
                $this->parfumManager->updateStock($item['product_id'], $item['quantity']);
            }

            return $orderId;

        } catch (\PDOException $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage();
            return false;
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Mengambil semua pesanan milik seorang user.
     * @param int $userId
     * @return array
     */
    public function getOrdersByUserId($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY tanggal_pesanan DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil detail satu pesanan berdasarkan ID.
     * @param int $orderId
     * @return array|false
     */
    public function getOrderById($orderId) {
        $sql = "SELECT * FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil semua item dari sebuah pesanan.
     * @param int $orderId
     * @return array
     */
    public function getOrderItems($orderId) {
        $sql = "SELECT oi.*, p.nama, p.image_path 
                FROM order_items oi
                JOIN parfums p ON oi.parfum_id = p.id
                WHERE oi.order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil semua pesanan dari semua user, digabung dengan nama user.
     * Bisa difilter berdasarkan rentang tanggal (optional).
     *
     * @param string|null $startDate format 'Y-m-d' atau null
     * @param string|null $endDate   format 'Y-m-d' atau null
     * @return array
     */
    public function getAllOrdersWithUserDetails($startDate = null, $endDate = null) {
        $sql = "SELECT o.*, u.username
                FROM orders o
                JOIN user u ON o.user_id = u.id
                WHERE 1=1";
        $params = [];

        if ($startDate !== null) {
            $sql .= " AND o.tanggal_pesanan >= ?";
            $params[] = $startDate . ' 00:00:00';
        }
        if ($endDate !== null) {
            $sql .= " AND o.tanggal_pesanan <= ?";
            $params[] = $endDate . ' 23:59:59';
        }

        $sql .= " ORDER BY o.tanggal_pesanan DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Menghitung statistik penjualan (total pendapatan dan jumlah pesanan).
     * Bisa difilter berdasarkan rentang tanggal (optional).
     *
     * Catatan:
     * - total_orders  dihitung dari jumlah pesanan unik (DISTINCT o.id)
     * - total_revenue dihitung dari SUM(order_items.jumlah * order_items.harga_saat_beli)
     *   supaya selalu konsisten dengan detail item & produk terlaris.
     *
     * @param string|null $startDate format 'Y-m-d' atau null
     * @param string|null $endDate   format 'Y-m-d' atau null
     * @return array
     */
    public function getSalesStatistics($startDate = null, $endDate = null) {
        $sql = "SELECT
                    COUNT(DISTINCT o.id) AS total_orders,
                    SUM(oi.jumlah * oi.harga_saat_beli) AS total_revenue
                FROM orders o
                LEFT JOIN order_items oi ON oi.order_id = o.id
                WHERE o.status != 'Dibatalkan'"; // Hanya hitung yang tidak dibatalkan

        $params = [];
        if ($startDate !== null) {
            $sql .= " AND o.tanggal_pesanan >= ?";
            $params[] = $startDate . ' 00:00:00';
        }
        if ($endDate !== null) {
            $sql .= " AND o.tanggal_pesanan <= ?";
            $params[] = $endDate . ' 23:59:59';
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $stats = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        $stats['total_orders'] = $stats['total_orders'] ?? 0;
        $stats['total_revenue'] = $stats['total_revenue'] ?? 0;
        return $stats;
    }

    /**
     * Mengambil produk terlaris berdasarkan order_items.
     * Bisa difilter per tanggal berdasarkan tanggal_pesanan di orders.
     *
     * @param string|null $startDate format 'Y-m-d' atau null
     * @param string|null $endDate   format 'Y-m-d' atau null
     * @param int $limit
     * @return array
     */
    public function getTopSellingProducts($startDate = null, $endDate = null, $limit = 5) {
        $sql = "SELECT
                    p.id,
                    p.nama,
                    p.ukuran,
                    p.image_path,
                    SUM(oi.jumlah) AS total_quantity,
                    SUM(oi.jumlah * oi.harga_saat_beli) AS total_revenue
                FROM order_items oi
                JOIN orders o ON oi.order_id = o.id
                JOIN parfums p ON oi.parfum_id = p.id
                WHERE o.status != 'Dibatalkan'";
        $params = [];

        if ($startDate !== null) {
            $sql .= " AND o.tanggal_pesanan >= ?";
            $params[] = $startDate . ' 00:00:00';
        }
        if ($endDate !== null) {
            $sql .= " AND o.tanggal_pesanan <= ?";
            $params[] = $endDate . ' 23:59:59';
        }

        // Tambahkan LIMIT langsung sebagai integer di SQL
        $sql .= " GROUP BY p.id, p.nama, p.ukuran, p.image_path
                  ORDER BY total_quantity DESC
                  LIMIT " . (int)$limit;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Mengubah status sebuah pesanan.
     * @param int $orderId
     * @param string $newStatus
     * @return bool
     */
    public function updateOrderStatus($orderId, $newStatus) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$newStatus, $orderId]);
    }

    /**
     * Mengupdate bukti pembayaran untuk pesanan.
     * @param int $orderId
     * @param string $filePath
     * @return bool
     */
    public function updatePaymentProof($orderId, $filePath) {
        $sql = "UPDATE orders SET payment_proof = ?, status = 'Menunggu Konfirmasi' WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$filePath, $orderId]);
    }
}
?>