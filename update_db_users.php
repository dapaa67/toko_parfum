<?php
require_once 'models/DB.php';

try {
    $db = new DB();
    $conn = $db->getConnection();

    // Cek apakah kolom sudah ada
    $check = $conn->query("SHOW COLUMNS FROM user LIKE 'is_active'");
    if ($check->rowCount() == 0) {
        $sql = "ALTER TABLE user ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER role";
        $conn->exec($sql);
        echo "Berhasil menambahkan kolom 'is_active' ke tabel 'user'.<br>";
    } else {
        echo "Kolom 'is_active' sudah ada.<br>";
    }

    echo "Database update selesai.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
