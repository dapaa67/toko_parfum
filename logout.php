<?php
require_once 'models/AuthManager.php';
AuthManager::logout(); // Panggil static method logout
header('Location: index.php'); // Redirect ke halaman utama
exit();
?>
