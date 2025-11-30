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

            // Kurangi waktu tunggu lock agar tidak menggantung sampai 120 detik
            // Default InnoDB biasanya 50s; di sini kita turunkan jadi 5s per query.
            $this->pdo->exec("SET SESSION innodb_lock_wait_timeout = 5");
        } catch (PDOException $e) {
            die("Koneksi Database Gagal: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>
