<?php
require_once 'DB.php';

class CarouselManager {
    private $db;
    private $pdo;

    public function __construct() {
        $this->db = new DB();
        $this->pdo = $this->db->getConnection();
    }

    public function create($image_path, $title = null, $description = null, $link = null, $item_order = 0) {
        $stmt = $this->pdo->prepare("INSERT INTO carousel_items (image_path, title, description, link, item_order) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$image_path, $title, $description, $link, $item_order]);
    }

    public function readAll() {
        $stmt = $this->pdo->query("SELECT * FROM carousel_items ORDER BY item_order ASC, id ASC");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function readById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM carousel_items WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function update($id, $image_path, $title = null, $description = null, $link = null, $item_order = 0) {
        $stmt = $this->pdo->prepare("UPDATE carousel_items SET image_path = ?, title = ?, description = ?, link = ?, item_order = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$image_path, $title, $description, $link, $item_order, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM carousel_items WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>