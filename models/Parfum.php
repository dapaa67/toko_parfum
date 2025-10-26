<?php
// models/Parfum.php (Ini adalah contoh, pastikan sesuai dengan implementasi lu)

class Parfum {
    private $id;
    private $nama;
    private $merek;
    private $kategori;
    private $gender; // <<< FIELD BARU UNTUK FILTER
    private $ukuran;
    private $harga;
    private $stok;
    private $deskripsi;
    private $image_path; // path ke gambar produk
    private $is_best_seller; // flag best seller (0/1)

    // --- Getters ---
    public function getId() { return $this->id; }
    public function getNama() { return $this->nama; }
    public function getMerek() { return $this->merek; }
    public function getKategori() { return $this->kategori; }
    public function getGender() { return $this->gender; } // <<< GETTER BARU
    public function getUkuran() { return $this->ukuran; }
    public function getHarga() { return $this->harga; }
    public function getStok() { return $this->stok; }
    public function getDeskripsi() { return $this->deskripsi; }
    public function getImagePath() { return $this->image_path; }
    public function getIsBestSeller() { return (int)$this->is_best_seller; }

    // --- Setters ---
    public function setId($id) { $this->id = $id; }
    public function setNama($nama) { $this->nama = $nama; }
    public function setMerek($merek) { $this->merek = $merek; }
    public function setKategori($kategori) { $this->kategori = $kategori; }
    public function setGender($gender) { $this->gender = $gender; } // <<< SETTER BARU
    public function setUkuran($ukuran) { $this->ukuran = $ukuran; }
    public function setHarga($harga) { $this->harga = $harga; }
    public function setStok($stok) { $this->stok = $stok; }
    public function setDeskripsi($deskripsi) { $this->deskripsi = $deskripsi; }
    public function setImagePath($image_path) { $this->image_path = $image_path; }
    public function setIsBestSeller($is_best_seller) { $this->is_best_seller = (int)$is_best_seller; }
}
?>