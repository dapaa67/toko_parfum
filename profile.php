<?php
require_once 'models/UserManager.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userManager = new UserManager();
$userId = $_SESSION['user_id'];
$user = $userManager->getUserById($userId);

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $data = [
            'full_name' => trim($_POST['full_name']),
            'email' => trim($_POST['email']),
            'phone' => trim($_POST['phone']),
            'address' => trim($_POST['address'])
        ];
        
        if ($userManager->updateProfile($userId, $data)) {
            $message = "Profil berhasil diperbarui!";
            $messageType = "success";
            $user = $userManager->getUserById($userId);
        } else {
            $message = "Gagal memperbarui profil.";
            $messageType = "danger";
        }
    } elseif (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if (!$userManager->verifyPassword($userId, $currentPassword)) {
            $message = "Password saat ini salah.";
            $messageType = "danger";
        } elseif ($newPassword !== $confirmPassword) {
            $message = "Konfirmasi password baru tidak cocok.";
            $messageType = "danger";
        } else {
            if ($userManager->changePassword($userId, $newPassword)) {
                $message = "Password berhasil diubah!";
                $messageType = "success";
            } else {
                $message = "Gagal mengubah password.";
                $messageType = "danger";
            }
        }
    }
}

require_once 'views/header.php';
?>

<div class="container mx-auto px-4 my-20">
    <div class="flex justify-center">
        <div class="w-full lg:w-2/3">
            <h2 class="text-4xl font-bold mb-8 text-center text-gold">Profil Saya</h2>

            <?php if ($message): ?>
                <div class="bg-<?php echo $messageType === 'success' ? 'green' : 'red'; ?>-100 border border-<?php echo $messageType === 'success' ? 'green' : 'red'; ?>-400 text-<?php echo $messageType === 'success' ? 'green' : 'red'; ?>-700 px-4 py-3 rounded mb-6" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Profile Info Card -->
            <div class="bg-white rounded-lg shadow-md mb-8">
                <div class="bg-white py-4 px-6 border-b border-gray-200 rounded-t-lg">
                    <h5 class="text-xl font-bold">
                        <i class="bi bi-person-lines-fill mr-2 text-gold"></i>Informasi Pribadi
                    </h5>
                </div>
                <div class="p-8">
                    <form action="profile.php" method="POST">
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                <input type="text" class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-500" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                                <p class="text-xs text-gray-500 mt-1">Username tidak dapat diubah.</p>
                            </div>
                            <div class="w-full md:w-1/2 px-3">
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold focus:border-gold" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold focus:border-gold" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                            </div>
                            <div class="w-full md:w-1/2 px-3">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold focus:border-gold" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold focus:border-gold" id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" name="update_profile" 
                                    style="background-color: #D4AF37; color: #1A1A1A; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 0.5rem; border: none; cursor: pointer; transition: all 0.2s;"
                                    onmouseover="this.style.backgroundColor='#B5952F';"
                                    onmouseout="this.style.backgroundColor='#D4AF37';">
                                <i class="bi bi-save" style="margin-right: 0.375rem;"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="bg-white py-4 px-6 border-b border-gray-200 rounded-t-lg">
                    <h5 class="text-xl font-bold">
                        <i class="bi bi-shield-lock mr-2 text-red-600"></i>Ganti Password
                    </h5>
                </div>
                <div class="p-8">
                    <form action="profile.php" method="POST">
                        <div class="mb-6">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                            <input type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" id="current_password" name="current_password" required>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                <input type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" id="new_password" name="new_password" required minlength="6">
                            </div>
                            <div class="w-full md:w-1/2 px-3">
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                <input type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" id="confirm_password" name="confirm_password" required minlength="6">
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" name="change_password" 
                                    style="background-color: #DC2626; color: white; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 0.5rem; border: none; cursor: pointer; transition: all 0.2s;"
                                    onmouseover="this.style.backgroundColor='#B91C1C';"
                                    onmouseout="this.style.backgroundColor='#DC2626';">
                                <i class="bi bi-key" style="margin-right: 0.375rem;"></i> Ganti Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once 'views/footer.php'; ?>
