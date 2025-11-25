<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Pastikan path ke models/AuthManager.php sudah benar
require_once 'models/AuthManager.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth = new AuthManager();
    // Panggil method login dari Class AuthManager (OOP)
    $role = $auth->login($_POST['username'], $_POST['password']);
    
    if ($role === 'admin') {
        // Arahkan Admin ke Dashboard CRUD
        header('Location: admin/dashboard.php');
        exit();
    } else if ($role === 'user') {
        // Arahkan User ke Halaman Utama
        header('Location: index.php');
        exit();
    } else {
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin Toko Parfum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --black: #000000;
            --white: #ffffff;
            --gray-light: #f8f9fa;
        }
        body {
            /* Background gradien gelap seperti di contoh */
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
        
        /* Kolom gambar di kiri dengan text overlay */
        .login-img-col {
            /* Ganti URL gambar sesuai keinginanmu */
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), 
                        url('https://images.unsplash.com/photo-1585399001834-d29f7036b134?auto=format&fit=crop&w=600&q=80') center/cover;
            color: var(--white);
            min-height: 250px;
        }
        
        /* Kolom form di kanan */
        .form-col {
            background-color: var(--white);
            color: #1a1a1a;
        }
        
        .btn-dark {
            font-weight: 600;
        }
        
        /* Hilangkan style focus gold */
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
        }
        
        .toggle-password-btn {
            border-color: #ced4da;
            color: #6c757d;
        }
        .toggle-password-btn:hover {
            color: #212529;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="card">
                    <div class="row g-0">
                        
                        <div class="col-md-6 login-img-col d-flex flex-column justify-content-center p-4 p-md-5">
                            <h2 class="fw-bold">Selamat Datang</h2>
                            <p class="lead mb-0">Masuk untuk melanjutkan belanja, melihat riwayat pesanan, dan mengelola akun Anda.</p>
                        </div>
                        
                        <div class="col-md-6 form-col">
                            <div class="card-body p-4 p-md-5">
                                
                                <h3 class="fw-bold mb-4 text-center">Login Akun</h3>
                                
                                <?php if ($error): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                
                                <form method="POST" novalidate>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                       
                                                 <div class="mb-3 position-relative">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <button type="button" class="btn btn-sm btn-outline-secondary toggle-password-btn position-absolute top-50 end-0 translate-middle-y me-2" id="togglePassword" aria-label="Show password" style="margin-top: 19px;"> <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-dark w-100 mt-3">Login</button>
                                </form>
                                
                                <div class="mt-3 text-center">
                                    <small>Kembali ke <a class="text-decoration-none" href="index.php">Halaman Utama</a></small>
                                    <small class="d-block mt-1">Belum punya akun? <a href="register.php" class="text-decoration-none">Daftar di sini</a></small>
                                </div>
                            </div>
                        </div> </div> </div> </div> </div> </div> <script>
document.addEventListener('DOMContentLoaded', function () {
  const toggle = document.getElementById('togglePassword');
  const pwd = document.getElementById('password');
  const user = document.getElementById('username');
  if (user) user.focus();
  if (toggle && pwd) {
    toggle.addEventListener('click', function () {
      const isText = pwd.getAttribute('type') === 'text';
      pwd.setAttribute('type', isText ? 'password' : 'text');
      this.innerHTML = isText ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
      this.setAttribute('aria-label', isText ? 'Show password' : 'Hide password');
    });
  }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>