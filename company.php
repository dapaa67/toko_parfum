<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'views/header.php';
?>

<div class="company-page font-sans text-dark">

    <!-- Hero Section -->
    <section class="py-24 bg-secondary relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">Tentang Kami</span>
            <h1 class="text-4xl lg:text-6xl font-bold text-dark mb-6 font-heading">
                Meningkatkan <span class="text-primary">Aura</span> Anda
            </h1>
            <p class="text-xl text-gray-600 mb-10 max-w-3xl mx-auto leading-relaxed">
                Fragrance Shop hadir untuk mendefinisikan ulang pengalaman wewangian Anda. 
                Kami memadukan kreativitas dan kualitas untuk menciptakan aroma yang tak terlupakan.
            </p>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center -mx-4">
                <div class="w-full lg:w-1/2 px-4 mb-12 lg:mb-0">
                    <div class="relative">
                        <div class="absolute inset-0 bg-primary/10 transform translate-x-4 translate-y-4 rounded-2xl"></div>
                        <img src="img/ceo.jpg" alt="Fragrance Shop CEO" class="relative rounded-2xl shadow-lg w-full max-w-md mx-auto object-cover">
                    </div>
                </div>
                <div class="w-full lg:w-1/2 px-4">
                    <h3 class="text-3xl font-bold mb-6 text-dark font-heading">Siapa Kami</h3>
                    <p class="mb-6 text-gray-600 leading-relaxed text-lg">
                        Kami adalah rumah bagi beragam koleksi parfum yang dikurasi untuk memenuhi preferensi berbagai pelanggan di Indonesia. 
                        Sejak 2018, kami berkomitmen untuk menghadirkan wewangian yang tidak hanya harum, tetapi juga bercerita.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-center text-gray-700">
                            <span class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-primary mr-4">
                                <i class="bi bi-check-lg"></i>
                            </span>
                            <span class="font-medium">Koleksi Eksklusif & Terkurasi</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <span class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-primary mr-4">
                                <i class="bi bi-check-lg"></i>
                            </span>
                            <span class="font-medium">Layanan Pelanggan Premium</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <span class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-primary mr-4">
                                <i class="bi bi-check-lg"></i>
                            </span>
                            <span class="font-medium">Pengiriman Cepat & Aman</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-24 bg-secondary/30">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-dark font-heading mb-4">Nilai-Nilai Perusahaan</h2>
                <div class="w-20 h-1 bg-primary mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Value 1 -->
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition duration-300 border-t-4 border-primary">
                    <div class="text-primary text-3xl mb-4"><i class="bi bi-shield-check"></i></div>
                    <h5 class="text-xl font-bold text-dark mb-3">Integritas</h5>
                    <p class="text-gray-600 text-sm leading-relaxed">Kejujuran dalam setiap langkah, membangun kepercayaan yang kokoh dengan pelanggan kami.</p>
                </div>
                <!-- Value 2 -->
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition duration-300 border-t-4 border-primary">
                    <div class="text-primary text-3xl mb-4"><i class="bi bi-gem"></i></div>
                    <h5 class="text-xl font-bold text-dark mb-3">Kualitas</h5>
                    <p class="text-gray-600 text-sm leading-relaxed">Standar tinggi tanpa kompromi, dari pemilihan bahan hingga produk sampai di tangan Anda.</p>
                </div>
                <!-- Value 3 -->
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition duration-300 border-t-4 border-primary">
                    <div class="text-primary text-3xl mb-4"><i class="bi bi-lightbulb"></i></div>
                    <h5 class="text-xl font-bold text-dark mb-3">Inovasi</h5>
                    <p class="text-gray-600 text-sm leading-relaxed">Selalu berkreasi untuk menghadirkan aroma baru yang relevan dengan perkembangan zaman.</p>
                </div>
                <!-- Value 4 -->
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition duration-300 border-t-4 border-primary">
                    <div class="text-primary text-3xl mb-4"><i class="bi bi-heart"></i></div>
                    <h5 class="text-xl font-bold text-dark mb-3">Kepedulian</h5>
                    <p class="text-gray-600 text-sm leading-relaxed">Memberikan dampak positif bagi komunitas dan lingkungan di sekitar kami.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-16 text-dark font-heading">Perjalanan Kami</h3>
            <div class="max-w-4xl mx-auto relative">
                <!-- Vertical Line -->
                <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-0.5 bg-gray-200"></div>
                
                <!-- Item 1 -->
                <div class="relative mb-12">
                    <div class="flex items-center justify-between w-full">
                        <div class="w-5/12 text-right pr-8">
                            <h4 class="text-xl font-bold text-primary">2018</h4>
                            <p class="text-gray-600 mt-2">Lahirnya Fragrance Shop dengan store pertama kami.</p>
                        </div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-primary rounded-full border-4 border-white shadow"></div>
                        <div class="w-5/12 pl-8"></div>
                    </div>
                </div>
                
                <!-- Item 2 -->
                <div class="relative mb-12">
                    <div class="flex items-center justify-between w-full flex-row-reverse">
                        <div class="w-5/12 text-left pl-8">
                            <h4 class="text-xl font-bold text-primary">2020</h4>
                            <p class="text-gray-600 mt-2">Peluncuran toko online untuk menjangkau seluruh Indonesia.</p>
                        </div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-primary rounded-full border-4 border-white shadow"></div>
                        <div class="w-5/12 pr-8"></div>
                    </div>
                </div>
                
                <!-- Item 3 -->
                <div class="relative mb-12">
                    <div class="flex items-center justify-between w-full">
                        <div class="w-5/12 text-right pr-8">
                            <h4 class="text-xl font-bold text-primary">2022</h4>
                            <p class="text-gray-600 mt-2">Ekspansi ke 5 kota besar dan kolaborasi dengan perfumer internasional.</p>
                        </div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-primary rounded-full border-4 border-white shadow"></div>
                        <div class="w-5/12 pl-8"></div>
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="relative">
                    <div class="flex items-center justify-between w-full flex-row-reverse">
                        <div class="w-5/12 text-left pl-8">
                            <h4 class="text-xl font-bold text-primary">2024</h4>
                            <p class="text-gray-600 mt-2">Memperkenalkan layanan Omnichannel dan Personal Scent.</p>
                        </div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-primary rounded-full border-4 border-white shadow"></div>
                        <div class="w-5/12 pr-8"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Minimal Text Divider CTA -->
    <section class="py-24 text-center bg-secondary/30">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl md:text-5xl font-heading text-dark mb-6 tracking-tight">
                Temukan Signature Anda
            </h2>
            <a href="products.php" class="inline-flex items-center text-primary font-bold tracking-widest uppercase text-sm hover:text-dark transition-colors duration-300 border-b border-primary pb-1 hover:border-dark">
                Belanja Sekarang
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </section>

</div>

<?php require_once 'views/footer.php'; ?>
