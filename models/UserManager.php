<?php
require_once __DIR__ . '/DB.php';

class UserManager {
    private $conn;

    public function __construct() {
        $db = new DB();
        $this->conn = $db->getConnection();
    }

    public function getUserById($id) {
        $sql = "SELECT id, username, full_name, email, phone, address, role FROM user WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($id, $data) {
        $sql = "UPDATE user SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['full_name'],
            $data['email'],
            $data['phone'],
            $data['address'],
            $id
        ]);
    }

    public function changePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE user SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$hashedPassword, $id]);
    }

    public function verifyPassword($id, $password) {
        $sql = "SELECT password FROM user WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        return false;
    }

    public function getAllUsers() {
        $sql = "SELECT * FROM user WHERE role != 'admin' ORDER BY created_at DESC";
        // Note: created_at might not exist, fallback to id if needed, but let's assume id desc for now if created_at missing
        // Checking schema, user table usually has id. Let's use id desc.
        $sql = "SELECT * FROM user WHERE role != 'admin' ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserStatus($id, $status) {
        $sql = "UPDATE user SET is_active = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $id]);
    }
}
?>
