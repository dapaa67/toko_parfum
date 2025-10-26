<?php
// views/header.php

// Variabel $_SESSION harus sudah tersedia sebelum file ini dipanggil.
// Logika PHP untuk session_start() dan ParfumManager::readAll() HARUS ADA DI index.php sebelum require_once 'views/header.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Toko Parfum Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">ParfumMy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="stores.php">Toko</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="company.php">Perusahaan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Kontak</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['role'])) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Keluar (<?php echo $_SESSION['username']; ?>)</a>
                        </li>
                        <?php if ($_SESSION['role'] == 'admin') { ?>
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-primary text-white" href="admin/dashboard.php">Dasbor Admin</a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>