<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'views/header.php';
?>

<!-- Stores Page -->
<div class="container mx-auto px-4 my-16 mb-40" id="stores">
  <div class="text-center mb-12">
    <h2 class="text-4xl font-bold mb-4 text-gold">Lokasi Store Kami</h2>
    <p class="text-gray-600">Temukan kami di kota-kota berikut. Klik tautan peta untuk petunjuk arah.</p>
  </div>
  
  <div class="flex flex-wrap justify-center -mx-4">
    <!-- Jakarta -->
    <div class="w-full lg:w-1/3 md:w-1/2 px-4 mb-8">
      <div class="bg-white rounded-lg shadow-md h-full p-6">
        <h5 class="text-xl font-semibold text-gold mb-4">Jakarta - Plaza Indonesia</h5>
        <ul class="list-none mb-4 space-y-2 text-gray-700">
          <li><strong>Alamat:</strong> Jl. M.H. Thamrin No. 28, Lantai 3, Jakarta Pusat</li>
          <li><strong>Jam Buka:</strong> Setiap hari 10:00 - 22:00 WIB</li>
          <li><strong>Kontak:</strong> +62 21 555 1234</li>
        </ul>
        <a href="https://maps.google.com/?q=Plaza+Indonesia+Jakarta" target="_blank" class="inline-block px-4 py-2 bg-gold text-black rounded hover:bg-gold-dark transition text-sm font-semibold">
          Lihat di Peta
        </a>
      </div>
    </div>

    <!-- Bandung -->
    <div class="w-full lg:w-1/3 md:w-1/2 px-4 mb-8">
      <div class="bg-white rounded-lg shadow-md h-full p-6">
        <h5 class="text-xl font-semibold text-gold mb-4">Bandung - Paris Van Java</h5>
        <ul class="list-none mb-4 space-y-2 text-gray-700">
          <li><strong>Alamat:</strong> Jl. Sukajadi No. 137-139, Bandung</li>
          <li><strong>Jam Buka:</strong> Setiap hari 10:00 - 22:00 WIB</li>
          <li><strong>Kontak:</strong> +62 22 777 2345</li>
        </ul>
        <a href="https://maps.google.com/?q=Paris+Van+Java+Bandung" target="_blank" class="inline-block px-4 py-2 bg-gold text-black rounded hover:bg-gold-dark transition text-sm font-semibold">
          Lihat di Peta
        </a>
      </div>
    </div>

    <!-- Surabaya -->
    <div class="w-full lg:w-1/3 md:w-1/2 px-4 mb-8">
      <div class="bg-white rounded-lg shadow-md h-full p-6">
        <h5 class="text-xl font-semibold text-gold mb-4">Surabaya - Tunjungan Plaza</h5>
        <ul class="list-none mb-4 space-y-2 text-gray-700">
          <li><strong>Alamat:</strong> Jl. Jend. Basuki Rachmat No.8-12, Surabaya</li>
          <li><strong>Jam Buka:</strong> Setiap hari 10:00 - 22:00 WIB</li>
          <li><strong>Kontak:</strong> +62 31 888 3456</li>
        </ul>
        <a href="https://maps.google.com/?q=Tunjungan+Plaza+Surabaya" target="_blank" class="inline-block px-4 py-2 bg-gold text-black rounded hover:bg-gold-dark transition text-sm font-semibold">
          Lihat di Peta
        </a>
      </div>
    </div>

    <!-- Yogyakarta -->
    <div class="w-full lg:w-1/3 md:w-1/2 px-4 mb-8">
      <div class="bg-white rounded-lg shadow-md h-full p-6">
        <h5 class="text-xl font-semibold text-gold mb-4">Yogyakarta - Malioboro</h5>
        <ul class="list-none mb-4 space-y-2 text-gray-700">
          <li><strong>Alamat:</strong> Jl. Malioboro No. 60, Yogyakarta</li>
          <li><strong>Jam Buka:</strong> Setiap hari 10:00 - 22:00 WIB</li>
          <li><strong>Kontak:</strong> +62 274 999 4567</li>
        </ul>
        <a href="https://maps.google.com/?q=Malioboro+Yogyakarta" target="_blank" class="inline-block px-4 py-2 bg-gold text-black rounded hover:bg-gold-dark transition text-sm font-semibold">
          Lihat di Peta
        </a>
      </div>
    </div>

    <!-- Bali -->
    <div class="w-full lg:w-1/3 md:w-1/2 px-4 mb-8">
      <div class="bg-white rounded-lg shadow-md h-full p-6">
        <h5 class="text-xl font-semibold text-gold mb-4">Bali - Beachwalk Kuta</h5>
        <ul class="list-none mb-4 space-y-2 text-gray-700">
          <li><strong>Alamat:</strong> Jl. Pantai Kuta, Kuta, Bali</li>
          <li><strong>Jam Buka:</strong> Setiap hari 10:00 - 22:00 WITA</li>
          <li><strong>Kontak:</strong> +62 361 123 5678</li>
        </ul>
        <a href="https://maps.google.com/?q=Beachwalk+Kuta+Bali" target="_blank" class="inline-block px-4 py-2 bg-gold text-black rounded hover:bg-gold-dark transition text-sm font-semibold">
          Lihat di Peta
        </a>
      </div>
    </div>

    <!-- Medan -->
    <div class="w-full lg:w-1/3 md:w-1/2 px-4 mb-8">
      <div class="bg-white rounded-lg shadow-md h-full p-6">
        <h5 class="text-xl font-semibold text-gold mb-4">Medan - Sun Plaza</h5>
        <ul class="list-none mb-4 space-y-2 text-gray-700">
          <li><strong>Alamat:</strong> Jl. Kh. Zainul Arifin No. 7, Medan</li>
          <li><strong>Jam Buka:</strong> Setiap hari 10:00 - 22:00 WIB</li>
          <li><strong>Kontak:</strong> +62 61 234 6789</li>
        </ul>
        <a href="https://maps.google.com/?q=Sun+Plaza+Medan" target="_blank" class="inline-block px-4 py-2 bg-gold text-black rounded hover:bg-gold-dark transition text-sm font-semibold">
          Lihat di Peta
        </a>
      </div>
    </div>
  </div>
</div>

<?php require_once 'views/footer.php'; ?>
