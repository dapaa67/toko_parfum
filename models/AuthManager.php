<?php
// Pastikan file DB.php sudah di-include
require_once 'DB.php';

class AuthManager {
    private $conn;

    public function __construct() {
        $db = new DB();
        $this->conn = $db->getConnection();
    }

    // Metode untuk Login
    public function login($username, $password) {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Cek apakah user aktif
            if (isset($user['is_active']) && $user['is_active'] == 0) {
                return 'Akun Anda telah dinonaktifkan oleh admin.';
            }

            // Login Berhasil!
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // SIMPAN ROLE!
            return $user['role'];
        }
        return false;
    }
    
    // Metode untuk Registrasi User
    public function register($username, $password) {
        // 1. Cek apakah username sudah ada
        $sql_check = "SELECT id FROM user WHERE username = ?";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->execute([$username]);
        if ($stmt_check->fetch()) {
            return 'Username sudah digunakan.'; // Kembalikan pesan error
        }

        // 2. Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 3. Insert user baru dengan role 'user'
        $sql_insert = "INSERT INTO user (username, password, role) VALUES (?, ?, 'user')";
        $stmt_insert = $this->conn->prepare($sql_insert);
        
        return $stmt_insert->execute([$username, $hashed_password]);
    }

    // Metode untuk Logout
    public static function logout() {
        session_start();
        session_unset();
        session_destroy();
    }

    // Metode STATIS untuk Cek Akses (Wajib ada di setiap halaman terproteksi)
    public static function checkRole($required_role) {
        // Mulai session jika belum dimulai
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Jika belum login ATAU role tidak sesuai, redirect ke login
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $required_role) {
            header('Location: /login.php'); // Ganti dengan path ke login lo
            exit();
        }
    }
}
?>