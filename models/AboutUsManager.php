<?php
require_once 'DB.php';

class AboutUsManager {
    private $db;
    private $pdo;

    public function __construct() {
        $this->db = new DB();
        $this->pdo = $this->db->getConnection();
    }

    // Manages the main About Us content (title, lead paragraph)
    public function getMainContent() {
        $stmt = $this->pdo->query("SELECT id, main_title, lead_paragraph FROM about_us_content LIMIT 1");
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function updateMainContent($main_title, $lead_paragraph) {
        $existingContent = $this->getMainContent();
        if ($existingContent) {
            $stmt = $this->pdo->prepare("UPDATE about_us_content SET main_title = ?, lead_paragraph = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            return $stmt->execute([$main_title, $lead_paragraph, $existingContent->id]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO about_us_content (main_title, lead_paragraph) VALUES (?, ?)");
            return $stmt->execute([$main_title, $lead_paragraph]);
        }
    }

    // Manages the list items
    public function getListItems($about_us_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM about_us_list_items WHERE about_us_id = ? ORDER BY item_order ASC, id ASC");
        $stmt->execute([$about_us_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function createListItem($about_us_id, $icon_class, $item_text, $item_order) {
        $stmt = $this->pdo->prepare("INSERT INTO about_us_list_items (about_us_id, icon_class, item_text, item_order) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$about_us_id, $icon_class, $item_text, $item_order]);
    }

    public function updateListItem($id, $icon_class, $item_text, $item_order) {
        $stmt = $this->pdo->prepare("UPDATE about_us_list_items SET icon_class = ?, item_text = ?, item_order = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$icon_class, $item_text, $item_order, $id]);
    }

    public function deleteListItem($id) {
        $stmt = $this->pdo->prepare("DELETE FROM about_us_list_items WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
