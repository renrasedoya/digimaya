@extends('layouts.public')

@section('meta_title', 'Privacy Policy | Digimaya')
@section('meta_description', 'Privacy Policy Digimaya — bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi pengunjung dan klien sesuai UU PDP Indonesia.')

@section('content')

{{-- ============== HERO HEADER ============== --}}
<section class="bg-gradient-to-b from-brand-50/40 to-white border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold text-brand uppercase tracking-wide mb-3">Legal</p>
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight mb-4">
                Privacy Policy
            </h1>
            <p class="text-sm text-gray-500 mb-6">
                Terakhir diperbarui: 4 Mei 2026
            </p>
            <p class="text-base lg:text-lg text-gray-600 leading-relaxed">
                Privacy Policy ini menjelaskan bagaimana <strong>PT Digital Maya Group</strong> ("Digimaya", "kami") mengumpulkan, menggunakan, menyimpan, dan melindungi data pribadi Anda saat menggunakan website digimaya.com dan layanan kami.
            </p>
        </div>
    </div>
</section>

{{-- ============== MAIN CONTENT (TOC + Body) ============== --}}
<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        {{-- Mobile TOC dropdown (collapsed by default) --}}
        <div class="lg:hidden mb-8" x-data="{ openToc: false }">
            <button type="button"
                    @click="openToc = !openToc"
                    class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-900">
                <span>Daftar Isi</span>
                <svg class="w-4 h-4 transition-transform" :class="openToc && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="openToc" x-cloak class="mt-2 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <ol class="space-y-2 text-sm text-gray-700 list-decimal list-inside">
                    <li><a href="#pengantar" class="hover:text-brand">Pengantar</a></li>
                    <li><a href="#definisi" class="hover:text-brand">Definisi</a></li>
                    <li><a href="#data-yang-dikumpulkan" class="hover:text-brand">Data yang Kami Kumpulkan</a></li>
                    <li><a href="#cara-mengumpulkan" class="hover:text-brand">Cara Kami Mengumpulkan Data</a></li>
                    <li><a href="#tujuan-penggunaan" class="hover:text-brand">Tujuan Penggunaan Data</a></li>
                    <li><a href="#dasar-hukum" class="hover:text-brand">Dasar Hukum Pemrosesan</a></li>
                    <li><a href="#pembagian-data" class="hover:text-brand">Pembagian Data dengan Pihak Ketiga</a></li>
                    <li><a href="#cookies" class="hover:text-brand">Cookies dan Tracking</a></li>
                    <li><a href="#retensi" class="hover:text-brand">Penyimpanan dan Retensi Data</a></li>
                    <li><a href="#keamanan" class="hover:text-brand">Keamanan Data</a></li>
                    <li><a href="#hak-subjek-data" class="hover:text-brand">Hak Anda sebagai Subjek Data</a></li>
                    <li><a href="#transfer-internasional" class="hover:text-brand">Transfer Data Lintas Negara</a></li>
                    <li><a href="#privasi-anak" class="hover:text-brand">Privasi Anak</a></li>
                    <li><a href="#perubahan" class="hover:text-brand">Perubahan Privacy Policy</a></li>
                    <li><a href="#hubungi-kami" class="hover:text-brand">Hubungi Kami</a></li>
                </ol>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

            {{-- Side TOC (desktop sticky) --}}
            <aside class="hidden lg:block lg:col-span-3">
                <div class="sticky top-24">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">
                        Daftar Isi
                    </h3>
                    <ol class="space-y-2 text-sm text-gray-700 list-decimal list-inside marker:text-gray-400">
                        <li><a href="#pengantar" class="hover:text-brand transition">Pengantar</a></li>
                        <li><a href="#definisi" class="hover:text-brand transition">Definisi</a></li>
                        <li><a href="#data-yang-dikumpulkan" class="hover:text-brand transition">Data yang Kami Kumpulkan</a></li>
                        <li><a href="#cara-mengumpulkan" class="hover:text-brand transition">Cara Kami Mengumpulkan Data</a></li>
                        <li><a href="#tujuan-penggunaan" class="hover:text-brand transition">Tujuan Penggunaan Data</a></li>
                        <li><a href="#dasar-hukum" class="hover:text-brand transition">Dasar Hukum Pemrosesan</a></li>
                        <li><a href="#pembagian-data" class="hover:text-brand transition">Pembagian Data dengan Pihak Ketiga</a></li>
                        <li><a href="#cookies" class="hover:text-brand transition">Cookies dan Tracking</a></li>
                        <li><a href="#retensi" class="hover:text-brand transition">Penyimpanan dan Retensi Data</a></li>
                        <li><a href="#keamanan" class="hover:text-brand transition">Keamanan Data</a></li>
                        <li><a href="#hak-subjek-data" class="hover:text-brand transition">Hak Anda sebagai Subjek Data</a></li>
                        <li><a href="#transfer-internasional" class="hover:text-brand transition">Transfer Data Lintas Negara</a></li>
                        <li><a href="#privasi-anak" class="hover:text-brand transition">Privasi Anak</a></li>
                        <li><a href="#perubahan" class="hover:text-brand transition">Perubahan Privacy Policy</a></li>
                        <li><a href="#hubungi-kami" class="hover:text-brand transition">Hubungi Kami</a></li>
                    </ol>
                </div>
            </aside>

            {{-- Content body --}}
            <article class="lg:col-span-9">

                <section id="pengantar" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Pengantar</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>
                            Digimaya berkomitmen melindungi privasi dan data pribadi setiap orang yang berinteraksi dengan kami. Privacy Policy ini menjelaskan praktik kami dalam mengumpulkan, menggunakan, dan melindungi data, sesuai dengan ketentuan <strong>Undang-Undang No. 27 Tahun 2022 tentang Perlindungan Data Pribadi</strong> ("UU PDP") dan peraturan pelaksanaannya.
                        </p>
                        <p>
                            Dengan mengakses atau menggunakan layanan kami, Anda menyatakan telah membaca, memahami, dan menyetujui ketentuan dalam Privacy Policy ini.
                        </p>
                    </div>
                </section>

                <section id="definisi" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Definisi</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Untuk keperluan Privacy Policy ini:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li><strong>Data Pribadi</strong> berarti setiap data tentang seseorang baik yang teridentifikasi dan/atau dapat diidentifikasi secara tersendiri atau dikombinasi dengan informasi lainnya.</li>
                            <li><strong>Subjek Data</strong> berarti orang perseorangan yang Data Pribadinya diproses.</li>
                            <li><strong>Pengendali Data</strong> berarti pihak yang menentukan tujuan dan melakukan kendali pemrosesan Data Pribadi. Dalam hal ini, Digimaya bertindak sebagai Pengendali Data.</li>
                            <li><strong>Pemroses Data</strong> berarti pihak yang melakukan pemrosesan Data Pribadi atas nama Pengendali Data.</li>
                            <li><strong>Cookies</strong> berarti file teks kecil yang disimpan di perangkat Anda saat mengakses website kami.</li>
                        </ul>
                    </div>
                </section>

                <section id="data-yang-dikumpulkan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Data yang Kami Kumpulkan</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Kami mengumpulkan beberapa jenis Data Pribadi sebagai berikut:</p>

                        <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-2">3.1 Data Identitas</h3>
                        <p>Nama lengkap, alamat email, nomor telepon, nama perusahaan, jabatan, dan informasi kontak lain yang Anda berikan saat mengisi formulir di website kami.</p>

                        <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-2">3.2 Data Bisnis</h3>
                        <p>Informasi tentang bisnis Anda yang relevan untuk layanan kami, seperti industri, ukuran bisnis, target pasar, anggaran iklan, dan tujuan kampanye.</p>

                        <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-2">3.3 Data Teknis</h3>
                        <p>Alamat IP, jenis browser, sistem operasi, jenis perangkat, halaman yang dikunjungi, durasi kunjungan, dan referrer URL.</p>

                        <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-2">3.4 Data Tracking dan Perilaku</h3>
                        <p>Data yang dikumpulkan melalui Cookies, pixel tracking, dan teknologi serupa terkait interaksi Anda dengan website dan kampanye iklan kami.</p>

                        <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-2">3.5 Data Akun Layanan Klien</h3>
                        <p>Untuk klien aktif, kami dapat memperoleh akses ke akun Google Ads, Google Analytics, atau platform iklan lain milik klien sesuai persetujuan tertulis.</p>
                    </div>
                </section>

                <section id="cara-mengumpulkan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Cara Kami Mengumpulkan Data</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Kami mengumpulkan data melalui beberapa cara:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li><strong>Pengumpulan langsung</strong> — saat Anda mengisi formulir kontak, mendaftar konsultasi, berlangganan newsletter, atau berkomunikasi dengan kami melalui email atau WhatsApp.</li>
                            <li><strong>Pengumpulan otomatis</strong> — melalui Cookies dan teknologi tracking saat Anda mengakses website kami.</li>
                            <li><strong>Pengumpulan dari pihak ketiga</strong> — dari platform analitik (Google Analytics, Meta Pixel) atau saat klien memberikan akses ke akun iklan mereka.</li>
                        </ul>
                    </div>
                </section>

                <section id="tujuan-penggunaan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Tujuan Penggunaan Data</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Data Pribadi yang kami kumpulkan digunakan untuk tujuan sebagai berikut:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Menyediakan layanan Google Ads management, konsultasi, dan layanan terkait kepada klien.</li>
                            <li>Berkomunikasi dengan Anda terkait pertanyaan, permintaan informasi, atau layanan yang sedang berjalan.</li>
                            <li>Mengirimkan informasi marketing, newsletter, atau konten edukasi (hanya jika Anda telah memberikan persetujuan).</li>
                            <li>Menganalisis dan meningkatkan kualitas layanan, website, dan kampanye iklan kami.</li>
                            <li>Memenuhi kewajiban hukum, perpajakan, dan regulasi yang berlaku.</li>
                            <li>Mencegah penipuan, penyalahgunaan layanan, atau aktivitas ilegal lainnya.</li>
                        </ul>
                    </div>
                </section>

                <section id="dasar-hukum" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Dasar Hukum Pemrosesan</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Kami memproses Data Pribadi Anda berdasarkan satu atau lebih dasar hukum berikut, sesuai Pasal 20 UU PDP:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li><strong>Persetujuan (consent)</strong> — Anda telah memberikan persetujuan secara eksplisit untuk pemrosesan data dengan tujuan tertentu.</li>
                            <li><strong>Pelaksanaan kontrak</strong> — pemrosesan diperlukan untuk pelaksanaan layanan yang Anda kontrakkan dengan kami.</li>
                            <li><strong>Kewajiban hukum</strong> — pemrosesan diperlukan untuk memenuhi kewajiban hukum yang berlaku bagi Digimaya.</li>
                            <li><strong>Kepentingan sah (legitimate interest)</strong> — pemrosesan diperlukan untuk kepentingan sah Digimaya, dengan tetap memperhatikan hak dan kebebasan Anda.</li>
                        </ul>
                    </div>
                </section>

                <section id="pembagian-data" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Pembagian Data dengan Pihak Ketiga</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Kami tidak menjual Data Pribadi Anda. Namun, kami dapat membagikan data kepada pihak ketiga dalam keadaan tertentu:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li><strong>Penyedia layanan teknologi</strong> — Google (Ads, Analytics, Tag Manager), Meta (Facebook Ads, Pixel), penyedia hosting, dan layanan email yang membantu operasional kami.</li>
                            <li><strong>Penyedia jasa profesional</strong> — akuntan, konsultan hukum, atau auditor yang membantu Digimaya menjalankan bisnis.</li>
                            <li><strong>Otoritas hukum</strong> — apabila diwajibkan oleh hukum, perintah pengadilan, atau otoritas yang berwenang.</li>
                            <li><strong>Transaksi bisnis</strong> — dalam hal penggabungan, akuisisi, atau penjualan aset bisnis, dengan tetap melindungi hak Subjek Data.</li>
                        </ul>
                        <p>Setiap pihak ketiga yang menerima data dari kami terikat kewajiban menjaga kerahasiaan data sesuai standar yang setara dengan Privacy Policy ini.</p>
                    </div>
                </section>

                <section id="cookies" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Cookies dan Tracking</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Website kami menggunakan Cookies dan teknologi tracking untuk berbagai tujuan:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li><strong>Cookies wajib (necessary)</strong> — diperlukan agar website berfungsi dengan baik (misalnya session login).</li>
                            <li><strong>Cookies performa</strong> — mengumpulkan data anonim tentang penggunaan website untuk analisis.</li>
                            <li><strong>Cookies analitik</strong> — Google Analytics dan tools serupa untuk memahami perilaku pengunjung.</li>
                            <li><strong>Cookies marketing</strong> — pixel tracking untuk remarketing dan pengukuran efektivitas iklan.</li>
                        </ul>
                        <p>Anda dapat mengatur preferensi Cookies melalui pengaturan browser. Memblokir Cookies tertentu dapat memengaruhi fungsionalitas website.</p>
                    </div>
                </section>

                <section id="retensi" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Penyimpanan dan Retensi Data</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Kami menyimpan Data Pribadi hanya selama diperlukan untuk tujuan pemrosesan, dengan periode retensi sebagai berikut:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li><strong>Data klien aktif</strong> — disimpan selama hubungan kerjasama berlangsung.</li>
                            <li><strong>Data pasca-kerjasama</strong> — disimpan selama 5 (lima) tahun setelah berakhirnya hubungan kerjasama, sesuai kewajiban perpajakan dan akuntansi.</li>
                            <li><strong>Data lead atau prospek</strong> — disimpan selama 24 (dua puluh empat) bulan sejak interaksi terakhir, kecuali Anda meminta penghapusan.</li>
                            <li><strong>Data marketing</strong> — disimpan selama Anda memberikan persetujuan dan tidak menarik diri dari komunikasi marketing.</li>
                        </ul>
                    </div>
                </section>

                <section id="keamanan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Keamanan Data</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Kami menerapkan langkah-langkah keamanan teknis dan organisasi yang wajar untuk melindungi Data Pribadi Anda dari akses tidak sah, kebocoran, perubahan, atau penghapusan, termasuk:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Enkripsi data dalam transmisi (HTTPS/SSL).</li>
                            <li>Kontrol akses berbasis peran untuk tim internal.</li>
                            <li>Penyimpanan data pada penyedia hosting yang menerapkan standar keamanan industri.</li>
                            <li>Audit dan review keamanan secara berkala.</li>
                            <li>Pelatihan privasi dan keamanan data untuk seluruh tim Digimaya.</li>
                        </ul>
                        <p>Meskipun demikian, tidak ada metode transmisi atau penyimpanan elektronik yang 100% aman. Kami tidak dapat menjamin keamanan absolut.</p>
                    </div>
                </section>

                <section id="hak-subjek-data" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Hak Anda sebagai Subjek Data</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Sesuai UU PDP, Anda memiliki hak-hak berikut atas Data Pribadi Anda:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li><strong>Hak untuk memperoleh informasi</strong> tentang identitas Pengendali Data, dasar hukum, tujuan, dan akuntabilitas pemrosesan.</li>
                            <li><strong>Hak untuk melengkapi, memperbarui, atau memperbaiki</strong> Data Pribadi Anda.</li>
                            <li><strong>Hak untuk mengakses dan memperoleh salinan</strong> Data Pribadi Anda.</li>
                            <li><strong>Hak untuk menghapus dan/atau memusnahkan</strong> Data Pribadi sesuai ketentuan hukum.</li>
                            <li><strong>Hak untuk menarik kembali persetujuan</strong> kapan saja.</li>
                            <li><strong>Hak untuk menunda atau membatasi pemrosesan</strong> Data Pribadi.</li>
                            <li><strong>Hak untuk mengajukan keberatan</strong> atas tindakan pengambilan keputusan otomatis.</li>
                            <li><strong>Hak untuk mendapatkan dan menggunakan Data Pribadi</strong> dalam format yang dapat dibaca mesin (portabilitas data).</li>
                            <li><strong>Hak untuk menggugat</strong> jika terjadi pelanggaran terhadap pemrosesan Data Pribadi Anda.</li>
                        </ul>
                        <p>Untuk menggunakan hak-hak tersebut, silakan hubungi kami melalui kontak yang tertera pada bagian "Hubungi Kami" di bawah. Kami akan merespons permintaan Anda dalam waktu paling lambat 3x24 jam dan menyelesaikannya dalam waktu yang wajar sesuai kompleksitas permintaan.</p>
                    </div>
                </section>

                <section id="transfer-internasional" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Transfer Data Lintas Negara</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Beberapa penyedia layanan yang kami gunakan (misalnya Google, Meta) memiliki server di luar wilayah Indonesia. Dalam pelaksanaan layanan, Data Pribadi Anda dapat ditransfer ke yurisdiksi lain.</p>
                        <p>Untuk transfer data lintas negara, kami memastikan:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Tingkat perlindungan Data Pribadi di negara penerima setara atau lebih tinggi dari yang diatur UU PDP, atau</li>
                            <li>Adanya perlindungan Data Pribadi yang memadai dan mengikat melalui klausul kontraktual atau mekanisme lain yang sah.</li>
                        </ul>
                    </div>
                </section>

                <section id="privasi-anak" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">13. Privasi Anak</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Layanan Digimaya tidak ditujukan untuk anak di bawah usia 18 (delapan belas) tahun. Kami tidak secara sadar mengumpulkan Data Pribadi dari anak-anak.</p>
                        <p>Apabila kami mengetahui adanya pengumpulan Data Pribadi dari anak tanpa persetujuan orang tua atau wali yang sah, kami akan menghapus data tersebut sesegera mungkin. Jika Anda mengetahui hal demikian, mohon hubungi kami.</p>
                    </div>
                </section>

                <section id="perubahan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">14. Perubahan Privacy Policy</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Kami dapat memperbarui Privacy Policy ini dari waktu ke waktu untuk mencerminkan perubahan praktik kami atau persyaratan hukum. Setiap perubahan material akan kami beritahukan melalui:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Pemberitahuan di website kami minimal 14 (empat belas) hari sebelum berlaku.</li>
                            <li>Email langsung kepada klien aktif untuk perubahan yang berdampak signifikan.</li>
                            <li>Pembaruan tanggal "Terakhir diperbarui" di bagian atas halaman ini.</li>
                        </ul>
                        <p>Kami menyarankan Anda untuk meninjau Privacy Policy ini secara berkala.</p>
                    </div>
                </section>

                <section id="hubungi-kami" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">15. Hubungi Kami</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Untuk pertanyaan, permintaan, atau keluhan terkait Privacy Policy ini atau pemrosesan Data Pribadi Anda, silakan hubungi:</p>
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mt-4">
                            <p class="font-semibold text-gray-900 mb-3">PT Digital Maya Group (Digimaya)</p>
                            <ul class="space-y-2 text-sm text-gray-700">
                                <li><strong>Email:</strong> <a href="mailto:renra@digimaya.com" class="text-brand hover:underline">renra@digimaya.com</a></li>
                                <li><strong>Alamat:</strong> Kota Wisata, Bogor, Jawa Barat, Indonesia</li>
                                <li><strong>Website:</strong> <a href="{{ route('home') }}" class="text-brand hover:underline">digimaya.com</a></li>
                            </ul>
                        </div>
                        <p>Kami berkomitmen merespons setiap permintaan dalam waktu paling lambat 3x24 jam pada hari kerja.</p>
                    </div>
                </section>

            </article>

        </div>
    </div>
</section>

@endsection
