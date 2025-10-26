<?php
// scripts seeding 20 produk parfums dengan cakupan gender dan ukuran
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/models/DB.php';

try {
    $db = new DB();
    $pdo = $db->getConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil daftar kolom yang ada pada tabel parfums
    $stmt = $pdo->query('DESCRIBE parfums');
    $existingCols = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    // Kolom yang diinginkan (akan dipakai jika ada di tabel)
    $desiredCols = ['nama','merek','kategori','gender','ukuran','harga','stok','deskripsi','image_path','is_best_seller'];
    $cols = array_values(array_intersect($desiredCols, $existingCols));
    if (empty($cols)) {
        throw new Exception('Tabel parfums tidak memiliki kolom yang sesuai untuk seeding.');
    }

    // Siapkan statement insert dinamis
    $placeholders = '(' . implode(',', array_fill(0, count($cols), '?')) . ')';
    $sql = 'INSERT INTO parfums (' . implode(',', $cols) . ') VALUES ' . $placeholders;
    $insertStmt = $pdo->prepare($sql);

    // Data referensi
    $genders = ['Male','Female','Unisex'];
    $sizes = [50, 100, 200];
    $categories = ['Floral','Fresh','Oriental','Woody','Citrus','Gourmand'];
    $brands = ['Maison A','Maison B','Atelier C','Studio D','Brand E','Label F'];
    $baseDesc = 'Produk parfum unggulan dengan aroma khas.';

    $products = [];
    $i = 1;
    // Cakupan: minimal 1 produk untuk tiap kombinasi gender x ukuran (3x3 = 9)
    foreach ($genders as $g) {
        foreach ($sizes as $s) {
            $products[] = [
                'nama' => "Parfum {$g} {$s}ml {$i}",
                'merek' => $brands[array_rand($brands)],
                'kategori' => $categories[array_rand($categories)],
                'gender' => $g,
                'ukuran' => $s,
                'harga' => rand(120000, 850000),
                'stok' => rand(5, 40),
                'deskripsi' => $baseDesc,
                'image_path' => 'img/parfum_placeholder.png',
                'is_best_seller' => 0,
            ];
            $i++;
        }
    }

    // Tambahan hingga total 20 produk
    $needed = 20 - count($products);
    for ($k = 0; $k < $needed; $k++) {
        $g = $genders[array_rand($genders)];
        $s = $sizes[array_rand($sizes)];
        $products[] = [
            'nama' => "Parfum {$g} {$s}ml " . ($i + $k),
            'merek' => $brands[array_rand($brands)],
            'kategori' => $categories[array_rand($categories)],
            'gender' => $g,
            'ukuran' => $s,
            'harga' => rand(120000, 850000),
            'stok' => rand(5, 40),
            'deskripsi' => $baseDesc,
            'image_path' => 'img/parfum_placeholder.png',
            'is_best_seller' => 0,
        ];
    }

    // Eksekusi insert
    $inserted = 0;
    foreach ($products as $p) {
        $values = [];
        foreach ($cols as $c) {
            $values[] = $p[$c] ?? null;
        }
        $insertStmt->execute($values);
        $inserted++;
    }

    header('Content-Type: text/plain; charset=utf-8');
    echo "Seed selesai. Inserted: {$inserted} rows.\n";
    echo 'Kolom dipakai: ' . implode(', ', $cols) . "\n";
    echo 'Contoh produk: ' . ($products[0]['nama'] ?? '-') . "\n";
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Seeder error: ' . $e->getMessage();
}

?>