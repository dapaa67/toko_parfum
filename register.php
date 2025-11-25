<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'models/AuthManager.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validasi
    if (empty($username)) {
        $errors[] = 'Username tidak boleh kosong.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password minimal harus 6 karakter.';
    }
    if ($password !== $password_confirm) {
        $errors[] = 'Konfirmasi password tidak cocok.';
    }

    if (empty($errors)) {
        $auth = new AuthManager();
        $result = $auth->register($username, $password);

        if ($result === true) {
            $success = 'Registrasi berhasil! Silakan login.';
            // Opsional: langsung login setelah registrasi
            // $auth->login($username, $password);
            // header('Location: index.php');
            // exit();
        } else {
            $errors[] = $result; // Tampilkan pesan error dari AuthManager (misal: username sudah ada)
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Toko Parfum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0a192f 0%, #040c18 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1.25rem rgba(0,0,0,0.25);
            border: none;
            overflow: hidden;
        }
        .form-col {
            background-color: #ffffff;
            color: #1a1a1a;
        }
        .btn-dark {
            font-weight: 600;
        }
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-12 form-col">
                            <div class="card-body p-4 p-md-5">
                                
                                <h3 class="fw-bold mb-4 text-center">Buat Akun Baru</h3>
                                
                                <?php if (!empty($errors)): ?>
                                    <div class="alert alert-danger">
                                        <?php foreach ($errors as $error): ?>
                                            <div><?php echo htmlspecialchars($error); ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($success): ?>
                                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                                <?php endif; ?>
                                
                                <form method="POST" action="register.php" novalidate>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirm" class="form-label">Konfirmasi Password</label>
                                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-dark w-100 mt-3">Register</button>
                                </form>
                                
                                <div class="mt-3 text-center">
                                    <small>Sudah punya akun? <a class="text-decoration-none" href="login.php">Login di sini</a></small>
                                    <small class="d-block mt-1">Kembali ke <a class="text-decoration-none" href="index.php">Halaman Utama</a></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>