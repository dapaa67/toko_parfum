<?php
require_once 'models/UserManager.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek login
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
            // Refresh data user
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

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="mb-4 fw-bold text-center">Profil Saya</h2>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Informasi Pribadi</h5>
                </div>
                <div class="card-body p-4">
                    <form action="profile.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control bg-light" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                                <div class="form-text">Username tidak dapat diubah.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="full_name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" name="update_profile" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-shield-lock me-2 text-danger"></i>Ganti Password</h5>
                </div>
                <div class="card-body p-4">
                    <form action="profile.php" method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            <div class="col-md-6">
                                <label for="new_password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                            </div>
                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" name="change_password" class="btn btn-danger">
                                    <i class="bi bi-key me-1"></i> Ganti Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once 'views/footer.php'; ?>
