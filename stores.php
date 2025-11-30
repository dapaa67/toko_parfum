<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'views/header.php';
?>

<div class="container my-section-spacing" id="stores">
  <div class="row">
    <div class="col-12">
      <h2 class="text-center mb-4">Lokasi Store Kami</h2>
      <p class="text-center">Temukan kami di kota-kota berikut. Klik tautan peta untuk petunjuk arah.</p>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-100 border-0 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Jakarta - Plaza Indonesia</h5>
          <ul class="list-unstyled mb-2">
            <li><strong>Alamat:</strong> Jl. M.H. Thamrin No. 28, Lantai 3, Jakarta Pusat</li>
            <li><strong>Jam Buka:</strong> Setiap hari 10:00 - 22:00 WIB</li>
            <li><strong>Kontak:</strong> +62 21 555 1234</li>
          </ul>
          <a href="https://maps.google.com/?q=Plaza+Indonesia+Jakarta" target="_blank" class="btn btn-primary btn-sm">Lihat di Peta</a>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-100 border-0 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Bandung - Paris Van Java</h5>
          <ul class="list-unstyled mb-2">
            <li><strong>Alamat:</strong> Jl. Sukajadi No. 137-139, Bandung</li>
            <li><strong>Jam Buka:</strong> Setiap hari 10:00 - 22:00 WIB</li>
            <li><strong>Kontak:</strong> +62 22 777 2345</li>
          </ul>
          <a href="https://maps.google.com/?q=Paris+Van+Java+Bandung" target="_blank" class="btn btn-primary btn-sm">Lihat di Peta</a>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-100 border-0 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Surabaya - Tunjungan Plaza</h5>
          <ul class="list-unstyled mb-2">
            <li><strong>Alamat:</strong> Jl. Jend. Basuki Rachmat No.8-12, Surabaya</li>
            <li><strong>Jam Buka:</strong> Setiap hari 10:00 - 22:00 WIB</li>
            <li><strong>Kontak:</strong> +62 31 888 3456</li>
          </ul>
          <a href="https://maps.google.com/?q=Tunjungan+Plaza+Surabaya" target="_blank" class="btn btn-primary btn-sm">Lihat di Peta</a>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-100 border-0 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Yogyakarta - Malioboro</h5>
          <ul class="list-unstyled mb-2">
            <li><strong>Alamat:</strong> Jl. Malioboro No. 60, Yogyakarta</li>
            <li><strong>Jam Buka:</strong> Setiap hari 10:00 - 22:00 WIB</li>
            <li><strong>Kontak:</strong> +62 274 999 4567</li>
          </ul>
          <a href="https://maps.google.com/?q=Malioboro+Yogyakarta" target="_blank" class="btn btn-primary btn-sm">Lihat di Peta</a>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-100 border-0 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Bali - Beachwalk Kuta</h5>
          <ul class="list-unstyled mb-2">
            <li><strong>Alamat:</strong> Jl. Pantai Kuta, Kuta, Bali</li>
            <li><strong>Jam Buka:</strong> Setiap hari 10:00 - 22:00 WITA</li>
            <li><strong>Kontak:</strong> +62 361 123 5678</li>
          </ul>
          <a href="https://maps.google.com/?q=Beachwalk+Kuta+Bali" target="_blank" class="btn btn-primary btn-sm">Lihat di Peta</a>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-100 border-0 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Medan - Sun Plaza</h5>
          <ul class="list-unstyled mb-2">
            <li><strong>Alamat:</strong> Jl. Kh. Zainul Arifin No. 7, Medan</li>
            <li><strong>Jam Buka:</strong> Setiap hari 10:00 - 22:00 WIB</li>
            <li><strong>Kontak:</strong> +62 61 234 6789</li>
          </ul>
          <a href="https://maps.google.com/?q=Sun+Plaza+Medan" target="_blank" class="btn btn-primary btn-sm">Lihat di Peta</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once 'views/footer.php'; ?>
