<?php
require_once 'models/DB.php';

try {
    $db = new DB();
    $conn = $db->getConnection();

    // Add payment_proof column if it doesn't exist
    $sql = "ALTER TABLE orders 
            ADD COLUMN payment_proof VARCHAR(255) DEFAULT NULL AFTER status";

    $conn->exec($sql);
    echo "Database updated successfully: Added payment_proof to orders table.\n";

} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Column payment_proof already exists. No changes made.\n";
    } else {
        echo "Error updating database: " . $e->getMessage() . "\n";
    }
}
?>
