<?php
require_once 'models/UserManager.php';
require_once 'models/DB.php';

$userManager = new UserManager();
$db = new DB();
$conn = $db->getConnection();

// 1. Setup: Ensure user exists (ID 4)
$userId = 4;
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$userId]);
if (!$stmt->fetch()) {
    die("User ID $userId not found. Please check database.\n");
}

echo "Testing User ID: $userId\n";

// 2. Test Update Profile
$newProfile = [
    'full_name' => 'Daffa Test Update',
    'email' => 'daffa@test.com',
    'phone' => '081234567890',
    'address' => 'Jl. Test No. 123'
];

if ($userManager->updateProfile($userId, $newProfile)) {
    echo "[PASS] Update Profile successful.\n";
} else {
    echo "[FAIL] Update Profile failed.\n";
}

// Verify Update
$user = $userManager->getUserById($userId);
if ($user['full_name'] === $newProfile['full_name'] && 
    $user['email'] === $newProfile['email'] &&
    $user['phone'] === $newProfile['phone'] &&
    $user['address'] === $newProfile['address']) {
    echo "[PASS] Profile data verification successful.\n";
} else {
    echo "[FAIL] Profile data verification failed.\n";
    print_r($user);
}

// 3. Test Change Password
$newPassword = 'newpassword123';
if ($userManager->changePassword($userId, $newPassword)) {
    echo "[PASS] Change Password successful.\n";
} else {
    echo "[FAIL] Change Password failed.\n";
}

// Verify Password
if ($userManager->verifyPassword($userId, $newPassword)) {
    echo "[PASS] Password verification successful.\n";
} else {
    echo "[FAIL] Password verification failed.\n";
}

// Restore Password (optional, to keep it usable)
// $userManager->changePassword($userId, 'password_lama'); 
?>
