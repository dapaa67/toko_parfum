<?php
require_once 'models/DB.php';

try {
    $db = new DB();
    $conn = $db->getConnection();

    // Add columns if they don't exist
    $sql = "ALTER TABLE user 
            ADD COLUMN full_name VARCHAR(100) DEFAULT NULL AFTER username,
            ADD COLUMN email VARCHAR(100) DEFAULT NULL AFTER full_name,
            ADD COLUMN phone VARCHAR(20) DEFAULT NULL AFTER email,
            ADD COLUMN address TEXT DEFAULT NULL AFTER phone";

    $conn->exec($sql);
    echo "Database updated successfully: Added full_name, email, phone, address to user table.\n";

} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Columns already exist. No changes made.\n";
    } else {
        echo "Error updating database: " . $e->getMessage() . "\n";
    }
}
?>
