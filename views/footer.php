<?php
// views/footer.php
?>

<footer class="bg-dark text-light pt-16 pb-8 mt-auto border-t border-gray-100">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap -mx-4">
            <div class="w-full lg:w-5/12 px-4 mb-10 lg:mb-0">
                <h5 class="text-primary text-xl font-bold mb-6 tracking-wide uppercase">ParfumMy</h5>
                <p class="text-gray-400 text-sm mb-6 leading-relaxed max-w-sm">Temukan aroma khas yang mendefinisikan diri Anda. Kami hadir dengan produk 100% original, dikurasi untuk memberikan pengalaman kemewahan yang tak terlupakan.</p>
                <div class="mt-6">
                    <a href="https://wa.me/628986236509" target="_blank" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-full transition shadow-lg shadow-green-900/20 no-underline">
                        <i class="fab fa-whatsapp mr-2 text-lg"></i> Chat Kami di WhatsApp
                    </a>
                </div>
            </div>

            <div class="w-full md:w-1/2 lg:w-3/12 px-4 mb-10 lg:mb-0">
                <h5 class="text-white text-lg font-bold mb-6">Jelajahi</h5>
                <ul class="list-none space-y-4">
                    <li><a href="company.php" class="text-gray-400 hover:text-primary transition no-underline text-sm flex items-center"><i class="bi bi-chevron-right text-xs mr-2 opacity-50"></i>Tentang Kami</a></li>
                    <li><a href="stores.php" class="text-gray-400 hover:text-primary transition no-underline text-sm flex items-center"><i class="bi bi-chevron-right text-xs mr-2 opacity-50"></i>Lokasi Toko</a></li>
                    <li><a href="products.php" class="text-gray-400 hover:text-primary transition no-underline text-sm flex items-center"><i class="bi bi-chevron-right text-xs mr-2 opacity-50"></i>Semua Produk</a></li>
                    <li><a href="contact.php" class="text-gray-400 hover:text-primary transition no-underline text-sm flex items-center"><i class="bi bi-chevron-right text-xs mr-2 opacity-50"></i>Hubungi Kami</a></li>
                    <li><a href="index.php#faq" class="text-gray-400 hover:text-primary transition no-underline text-sm flex items-center"><i class="bi bi-chevron-right text-xs mr-2 opacity-50"></i>FAQ</a></li>
                </ul>
            </div>

            <div class="w-full md:w-1/2 lg:w-4/12 px-4 mb-10 lg:mb-0">
                <h5 class="text-white text-lg font-bold mb-6">Ikuti Kami</h5>
                <div class="flex space-x-4 mb-6">
                    <a href="https://www.instagram.com/" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-dark transition no-underline">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    <a href="https://www.tiktok.com/" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-dark transition no-underline">
                        <i class="fab fa-tiktok text-lg"></i>
                    </a>
                </div>
                <h5 class="text-white text-lg font-bold mb-4">Marketplace</h5>
                <ul class="list-none space-y-3">
                    <li>
                        <a href="https://shopee.co.id/" target="_blank" class="text-gray-400 hover:text-primary transition no-underline text-sm flex items-center group">
                            <span class="w-8 h-8 rounded bg-gray-800 flex items-center justify-center mr-3 group-hover:bg-primary group-hover:text-dark transition"><i class="fas fa-store"></i></span>
                            Shopee
                        </a>
                    </li>
                    <li>
                        <a href="https://www.tokopedia.com/" target="_blank" class="text-gray-400 hover:text-primary transition no-underline text-sm flex items-center group">
                            <span class="w-8 h-8 rounded bg-gray-800 flex items-center justify-center mr-3 group-hover:bg-primary group-hover:text-dark transition"><i class="fas fa-shopping-bag"></i></span>
                            Tokopedia
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="my-8 border-gray-800">

        <div class="flex flex-wrap items-center justify-between">
            <div class="w-full md:w-auto text-center md:text-left mb-4 md:mb-0">
                <p class="mb-0 text-gray-500 text-xs">
                    &copy; <?php echo date('Y'); ?> ParfumMy. 
                </p>
            </div>
            <div class="w-full md:w-auto text-center md:text-right">
                <p class="mb-0 text-gray-600 text-xs">
                    Muhammad Daffa - 221011400800 - 07tplp020
                </p>
            </div>
        </div>
    </div>
</footer>

