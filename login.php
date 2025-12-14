<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'models/AuthManager.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth = new AuthManager();
    $role = $auth->login($_POST['username'], $_POST['password']);
    
    if ($role === 'admin') {
        header('Location: admin/dashboard.php');
        exit();
    } else if ($role === 'user') {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ParfumMY</title>
    <link href="css/output.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <script defer src="js/alpine.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #FDFBF7 0%, #F5F1E8 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-container {
            animation: fadeIn 0.6s ease-out;
        }
        
        .brand-side {
            background: linear-gradient(135deg, #D4AF37 0%, #B5952F 100%);
            position: relative;
            overflow: hidden;
        }
        
        .brand-side::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.1); opacity: 0.5; }
        }
        
        .form-input:focus {
            border-color: #D4AF37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }
    </style>
</head>
<body class="flex justify-center items-center min-h-screen p-4" x-data="{ showPassword: false }">
    
    <div class="login-container w-full max-w-5xl">
        <div style="background: white; border-radius: 1.5rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); overflow: hidden;">
            <div class="flex flex-wrap">
                
                <!-- Left Side - Brand -->
                <div class="w-full md:w-2/5 brand-side p-12 flex flex-col justify-center text-white min-h-[400px] relative z-10">
                    <div style="margin-bottom: 2rem;">
                        <div style="width: 4rem; height: 4rem; background: rgba(255,255,255,0.2); border-radius: 1rem; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; backdrop-filter: blur(10px);">
                            <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                        </div>
                        <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.75rem; line-height: 1.2;">ParfumMY</h1>
                        <p style="font-size: 1.125rem; opacity: 0.95; line-height: 1.6;">
                            Temukan aroma kepribadian Anda dengan koleksi parfum eksklusif kami
                        </p>
                    </div>
                    
                    <div style="margin-top: auto; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.2);">
                        <div style="display: flex; gap: 1.5rem; opacity: 0.9;">
                            <div>
                                <i class="bi bi-shield-check" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                                <span style="font-size: 0.875rem;">100% Original</span>
                            </div>
                            <div>
                                <i class="bi bi-truck" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                                <span style="font-size: 0.875rem;">Gratis Ongkir</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Login Form -->
                <div class="w-full md:w-3/5 p-8 md:p-12">
                    <div style="max-width: 24rem; margin: 0 auto;">
                        
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <h2 style="font-size: 1.875rem; font-weight: 700; color: #1A1A1A; margin-bottom: 0.5rem;">Selamat Datang Kembali</h2>
                            <p style="color: #6B7280; font-size: 0.9375rem;">Masuk untuk melanjutkan belanja Anda</p>
                        </div>
                        
                        <?php if ($error): ?>
                            <div style="background-color: #FEE2E2; border-left: 4px solid #DC2626; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                                <p style="margin: 0; color: #991B1B; font-size: 0.875rem;">
                                    <i class="bi bi-exclamation-circle-fill" style="margin-right: 0.5rem;"></i>
                                    <?php echo htmlspecialchars($error); ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" style="margin-bottom: 1.5rem;">
                            <!-- Username -->
                            <div style="margin-bottom: 1.25rem;">
                                <label for="username" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                                    <i class="bi bi-person" style="margin-right: 0.375rem; color: #D4AF37;"></i> Username
                                </label>
                                <input type="text" 
                                       class="form-input"
                                       style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #E5E7EB; border-radius: 0.5rem; font-size: 0.9375rem; transition: all 0.2s; outline: none;"
                                       id="username" 
                                       name="username" 
                                       placeholder="Masukkan username Anda"
                                       required>
                            </div>
                            
                            <!-- Password -->
                            <div style="margin-bottom: 1.5rem; position: relative;">
                                <label for="password" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                                    <i class="bi bi-lock" style="margin-right: 0.375rem; color: #D4AF37;"></i> Password
                                </label>
                                <input :type="showPassword ? 'text' : 'password'" 
                                       class="form-input"
                                       style="width: 100%; padding: 0.75rem 1rem; padding-right: 3rem; border: 2px solid #E5E7EB; border-radius: 0.5rem; font-size: 0.9375rem; transition: all 0.2s; outline: none;"
                                       id="password" 
                                       name="password" 
                                       placeholder="Masukkan password Anda"
                                       required>
                                <button type="button" 
                                        @click="showPassword = !showPassword"
                                        style="position: absolute; right: 1rem; top: 2.375rem; color: #9CA3AF; background: none; border: none; cursor: pointer; transition: color 0.2s;"
                                        onmouseover="this.style.color='#6B7280';"
                                        onmouseout="this.style.color='#9CA3AF';">
                                    <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'" style="font-size: 1.125rem;"></i>
                                </button>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" 
                                    style="width: 100%; background-color: #D4AF37; color: #1A1A1A; font-weight: 600; padding: 0.875rem 1.5rem; border-radius: 0.5rem; border: none; cursor: pointer; transition: all 0.2s; font-size: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"
                                    onmouseover="this.style.backgroundColor='#B5952F'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px rgba(0,0,0,0.15)';"
                                    onmouseout="this.style.backgroundColor='#D4AF37'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';">
                                <i class="bi bi-box-arrow-in-right" style="margin-right: 0.5rem;"></i> Masuk
                            </button>
                        </form>
                        
                        <!-- Links -->
                        <div style="text-align: center; padding-top: 1.5rem; border-top: 1px solid #E5E7EB;">
                            <p style="margin: 0 0 0.75rem 0; color: #6B7280; font-size: 0.875rem;">
                                <i class="bi bi-arrow-left" style="margin-right: 0.25rem;"></i>
                                <a href="index.php" style="color: #D4AF37; text-decoration: none; font-weight: 500; transition: color 0.2s;"
                                   onmouseover="this.style.color='#B5952F';"
                                   onmouseout="this.style.color='#D4AF37';">
                                    Kembali ke Homepage
                                </a>
                            </p>
                            <p style="margin: 0; color: #6B7280; font-size: 0.875rem;">
                                Belum punya akun? 
                                <a href="register.php" style="color: #D4AF37; text-decoration: none; font-weight: 500; transition: color 0.2s;"
                                   onmouseover="this.style.color='#B5952F';"
                                   onmouseout="this.style.color='#D4AF37';">
                                    Daftar Sekarang
                                </a>
                            </p>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
</body>
</html>
