<?php
class DB {
    private $host = 'localhost';
    private $user = 'root'; // Ganti dengan username DB lo
    private $pass = '';     // Ganti dengan password DB lo
    private $dbname = 'toko_parfum';
    private $pdo; // Properti untuk menyimpan objek koneksi

    public function __construct() {
        // Konstruktor untuk membuat koneksi saat objek dibuat
        $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Koneksi Database Gagal: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>