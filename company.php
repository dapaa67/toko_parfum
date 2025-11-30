<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'views/header.php';
?>

<div class="company-page">

<section class="company-hero">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-10">
        <h1 class="fw-semibold mb-3 gold-text-gradient">
          Tingkatkan Aromamu dengan Ekosistem Fragrance Shop
        </h1>
        <p class="lead mb-2 text-white-50">
          Fragrance Shop adalah brand parfum lokal yang menghadirkan koleksi wewangian dan <em>personal care</em> premium
          sejak 2018 melalui kreativitas, teknologi, dan inovasi.
        </p>
        <p class="lead text-white-50">
          Saatnya <strong><em>Tingkatkan Aromamu (Level Up Your Fragrance)</em></strong> — wujudkan aroma signature versi terbaikmu!
        </p>
        <a href="products.php" class="btn btn-gold btn-lg mt-2">Jelajahi Koleksi</a>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-white">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-6">
        <img src="img/ceo.jpg" alt="Fragrance Shop" class="img-fluid rounded-3 shadow-sm mx-auto d-block" style="max-width: 360px; height: auto;">
      </div>
      <div class="col-lg-6">
        <h3 class="mb-3 text-primary">Siapa Kami</h3>
        <p class="mb-2">Kami adalah rumah bagi beragam koleksi parfum yang dikurasi untuk memenuhi preferensi berbagai pelanggan di Indonesia.</p>
        <p class="mb-3">Dengan jaringan yang berkembang dan komitmen pelayanan, kami memastikan setiap pelanggan menemukan aroma signature mereka.</p>
        <ul class="list-unstyled">
          <li class="mb-2"><span class="icon-dot"></span>Koleksi Eksklusif</li>
          <li class="mb-2"><span class="icon-dot"></span>Layanan Pelanggan Berkelas</li>
          <li class="mb-2"><span class="icon-dot"></span>Pengiriman Cepat & Aman</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="py-5 ">
  <div class="container">
    <h2 class="text-center mb-4">Nilai-Nilai Perusahaan</h2>
    <div class="row g-4">
      <div class="col-md-3 col-sm-6">
        <div class="card card-dark border-top-gold h-100">
          <div class="card-body">
            <h5 class="card-title">Integritas</h5>
            <p class="mb-0 text-white-50">Kejujuran dalam proses dan komunikasi, membangun kepercayaan jangka panjang.</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card card-dark border-top-gold h-100">
          <div class="card-body">
            <h5 class="card-title">Kualitas</h5>
            <p class="mb-0 text-white-50">Standar mutu tinggi pada bahan, produksi, dan pengalaman pelanggan.</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card card-dark border-top-gold h-100">
          <div class="card-body">
            <h5 class="card-title">Inovasi</h5>
            <p class="mb-0 text-white-50">Terus meningkatkan produk dan layanan melalui kreativitas.</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card card-dark border-top-gold h-100">
          <div class="card-body">
            <h5 class="card-title">Kepedulian</h5>
            <p class="mb-0 text-white-50">Dampak positif bagi pelanggan, karyawan, dan lingkungan.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-white">
  <div class="container">
    <h3 class="mb-4 text-center" style="color:var(--gold);">Sejarah Singkat</h3>
    <div class="timeline mx-auto" style="max-width:720px;">
      <div class="timeline-item">
        <h6 class="mb-1">2018</h6>
        <p class="mb-0 text-muted">Perusahaan didirikan dan membuka store pertama.</p>
      </div>
      <div class="timeline-item">
        <h6 class="mb-1">2020</h6>
        <p class="mb-0 text-muted">Peluncuran toko online dan perluasan katalog.</p>
      </div>
      <div class="timeline-item">
        <h6 class="mb-1">2022</h6>
        <p class="mb-0 text-muted">Ekspansi ke beberapa kota besar di Indonesia.</p>
      </div>
      <div class="timeline-item">
        <h6 class="mb-1">2024</h6>
        <p class="mb-0 text-muted">Peningkatan layanan omnichannel dan program loyalti.</p>
      </div>
    </div>
  </div>
</section>

<section class="section-dark py-5">
  <div class="container text-center">
    <h3 class="mb-3">Temukan Aroma Favoritmu Sekarang!</h3> 
    <p class="text-white-50 mb-4">Koleksi terkurasi kami siap menemani setiap momen pentingmu.</p> 
    <a href="products.php" class="btn btn-gold btn-lg">Belanja Sekarang</a> 
  </div>
</section>

</div>

<?php require_once 'views/footer.php'; ?>
