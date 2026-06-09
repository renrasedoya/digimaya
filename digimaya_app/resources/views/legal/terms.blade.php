@extends('layouts.public')

@section('meta_title', 'Terms of Service | Digimaya')
@section('meta_description', 'Terms of Service Digimaya — ketentuan penggunaan website dan layanan PT Digital Maya Group.')

@section('content')

{{-- ============== HERO HEADER ============== --}}
<section class="bg-gradient-to-b from-brand-50/40 to-white border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold text-brand uppercase tracking-wide mb-3">Legal</p>
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight mb-4">
                Terms of Service
            </h1>
            <p class="text-sm text-gray-500 mb-6">
                Terakhir diperbarui: 4 Mei 2026
            </p>
            <p class="text-base lg:text-lg text-gray-600 leading-relaxed">
                Terms of Service ini mengatur penggunaan website digimaya.com dan layanan yang disediakan oleh <strong>PT Digital Maya Group</strong> ("Digimaya", "kami"). Dengan mengakses atau menggunakan layanan kami, Anda menyetujui ketentuan ini.
            </p>
        </div>
    </div>
</section>

{{-- ============== MAIN CONTENT (TOC + Body) ============== --}}
<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        {{-- Mobile TOC dropdown --}}
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
                    <li><a href="#pengantar" class="hover:text-brand">Pengantar dan Penerimaan</a></li>
                    <li><a href="#definisi" class="hover:text-brand">Definisi</a></li>
                    <li><a href="#layanan" class="hover:text-brand">Layanan yang Kami Tawarkan</a></li>
                    <li><a href="#pendaftaran" class="hover:text-brand">Pendaftaran dan Akun</a></li>
                    <li><a href="#hak-pengguna" class="hover:text-brand">Hak dan Kewajiban Pengguna</a></li>
                    <li><a href="#hak-digimaya" class="hover:text-brand">Hak dan Kewajiban Digimaya</a></li>
                    <li><a href="#pembayaran" class="hover:text-brand">Pembayaran dan Refund</a></li>
                    <li><a href="#hki" class="hover:text-brand">Hak Kekayaan Intelektual</a></li>
                    <li><a href="#konten-pengguna" class="hover:text-brand">Konten Pengguna</a></li>
                    <li><a href="#privasi" class="hover:text-brand">Privasi</a></li>
                    <li><a href="#pembatasan" class="hover:text-brand">Pembatasan Penggunaan</a></li>
                    <li><a href="#disclaimer" class="hover:text-brand">Penolakan Tanggung Jawab</a></li>
                    <li><a href="#batasan-tanggung-jawab" class="hover:text-brand">Batasan Tanggung Jawab</a></li>
                    <li><a href="#ganti-rugi" class="hover:text-brand">Ganti Rugi</a></li>
                    <li><a href="#pemutusan" class="hover:text-brand">Pemutusan</a></li>
                    <li><a href="#hukum" class="hover:text-brand">Hukum yang Berlaku</a></li>
                    <li><a href="#perubahan" class="hover:text-brand">Perubahan Ketentuan</a></li>
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
                        <li><a href="#pengantar" class="hover:text-brand transition">Pengantar dan Penerimaan</a></li>
                        <li><a href="#definisi" class="hover:text-brand transition">Definisi</a></li>
                        <li><a href="#layanan" class="hover:text-brand transition">Layanan yang Kami Tawarkan</a></li>
                        <li><a href="#pendaftaran" class="hover:text-brand transition">Pendaftaran dan Akun</a></li>
                        <li><a href="#hak-pengguna" class="hover:text-brand transition">Hak dan Kewajiban Pengguna</a></li>
                        <li><a href="#hak-digimaya" class="hover:text-brand transition">Hak dan Kewajiban Digimaya</a></li>
                        <li><a href="#pembayaran" class="hover:text-brand transition">Pembayaran dan Refund</a></li>
                        <li><a href="#hki" class="hover:text-brand transition">Hak Kekayaan Intelektual</a></li>
                        <li><a href="#konten-pengguna" class="hover:text-brand transition">Konten Pengguna</a></li>
                        <li><a href="#privasi" class="hover:text-brand transition">Privasi</a></li>
                        <li><a href="#pembatasan" class="hover:text-brand transition">Pembatasan Penggunaan</a></li>
                        <li><a href="#disclaimer" class="hover:text-brand transition">Penolakan Tanggung Jawab</a></li>
                        <li><a href="#batasan-tanggung-jawab" class="hover:text-brand transition">Batasan Tanggung Jawab</a></li>
                        <li><a href="#ganti-rugi" class="hover:text-brand transition">Ganti Rugi</a></li>
                        <li><a href="#pemutusan" class="hover:text-brand transition">Pemutusan</a></li>
                        <li><a href="#hukum" class="hover:text-brand transition">Hukum yang Berlaku</a></li>
                        <li><a href="#perubahan" class="hover:text-brand transition">Perubahan Ketentuan</a></li>
                        <li><a href="#hubungi-kami" class="hover:text-brand transition">Hubungi Kami</a></li>
                    </ol>
                </div>
            </aside>

            {{-- Content body --}}
            <article class="lg:col-span-9">

                <section id="pengantar" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Pengantar dan Penerimaan Ketentuan</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>
                            Selamat datang di Digimaya. Terms of Service ("Ketentuan") ini merupakan perjanjian yang mengikat antara Anda ("Pengguna") dengan PT Digital Maya Group ("Digimaya", "kami"), terkait akses dan penggunaan website digimaya.com beserta seluruh layanan yang kami sediakan.
                        </p>
                        <p>
                            Dengan mengakses, mendaftar, atau menggunakan layanan kami, Anda menyatakan telah membaca, memahami, dan menyetujui untuk terikat dengan Ketentuan ini secara keseluruhan. Apabila Anda tidak menyetujui sebagian atau seluruh Ketentuan, mohon untuk tidak menggunakan layanan kami.
                        </p>
                    </div>
                </section>

                <section id="definisi" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Definisi</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li><strong>Layanan</strong> berarti seluruh layanan yang disediakan Digimaya, termasuk Google Ads management, konsultasi, training, dan layanan terkait lainnya.</li>
                            <li><strong>Pengguna</strong> berarti setiap orang atau entitas yang mengakses atau menggunakan website digimaya.com.</li>
                            <li><strong>Klien</strong> berarti Pengguna yang telah memasuki perjanjian kerjasama tertulis dengan Digimaya untuk menerima Layanan.</li>
                            <li><strong>Konten</strong> berarti seluruh teks, grafik, gambar, video, kode, atau materi lain yang ditampilkan atau dikirimkan melalui Layanan.</li>
                            <li><strong>Akun</strong> berarti akun terdaftar yang dibuat Pengguna untuk mengakses fitur tertentu pada Layanan.</li>
                        </ul>
                    </div>
                </section>

                <section id="layanan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Layanan yang Kami Tawarkan</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Digimaya menyediakan layanan-layanan berikut:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li><strong>Google Ads Management</strong> — pengelolaan kampanye iklan Google (Search, Display, Video, Shopping, Performance Max) untuk klien bisnis.</li>
                            <li><strong>Konsultasi Strategi Digital Marketing</strong> — sesi konsultasi mengenai strategi pemasaran digital, audit akun, dan rekomendasi optimasi.</li>
                            <li><strong>Training dan Edukasi</strong> — bootcamp, masterclass, mentoring, dan program pembelajaran lain di bidang Google Ads dan digital marketing.</li>
                            <li><strong>Konten Edukasi</strong> — artikel blog, ebook, dan konten edukasi yang dapat diakses secara gratis atau berbayar.</li>
                        </ul>
                        <p>Detail spesifik layanan, scope of work, dan deliverables akan diatur dalam proposal atau perjanjian tertulis terpisah antara Klien dan Digimaya.</p>
                    </div>
                </section>

                <section id="pendaftaran" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Pendaftaran dan Akun</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Untuk fitur tertentu, Pengguna mungkin diminta membuat Akun. Dengan mendaftar, Anda setuju untuk:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Memberikan informasi yang akurat, lengkap, dan terkini.</li>
                            <li>Memperbarui informasi Akun apabila terjadi perubahan.</li>
                            <li>Menjaga kerahasiaan kredensial login (username dan password).</li>
                            <li>Bertanggung jawab atas seluruh aktivitas yang terjadi di bawah Akun Anda.</li>
                            <li>Memberitahu kami segera apabila terjadi akses tidak sah atau pelanggaran keamanan Akun.</li>
                        </ul>
                    </div>
                </section>

                <section id="hak-pengguna" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Hak dan Kewajiban Pengguna</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p><strong>Hak Pengguna:</strong></p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Mengakses Layanan sesuai paket atau perjanjian yang berlaku.</li>
                            <li>Memperoleh dukungan teknis sesuai ketentuan layanan.</li>
                            <li>Mendapatkan laporan dan komunikasi sesuai standar yang disepakati.</li>
                        </ul>
                        <p class="mt-4"><strong>Kewajiban Pengguna:</strong></p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Memberikan informasi yang akurat dan dapat diverifikasi.</li>
                            <li>Mematuhi peraturan perundang-undangan yang berlaku, termasuk peraturan periklanan dan perlindungan konsumen.</li>
                            <li>Tidak menggunakan Layanan untuk tujuan ilegal, menipu, atau merugikan pihak lain.</li>
                            <li>Tidak melanggar hak kekayaan intelektual pihak ketiga.</li>
                            <li>Membayar biaya layanan sesuai kesepakatan tepat waktu.</li>
                        </ul>
                    </div>
                </section>

                <section id="hak-digimaya" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Hak dan Kewajiban Digimaya</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p><strong>Hak Digimaya:</strong></p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Mengubah, memperbarui, atau menghentikan Layanan dengan pemberitahuan terlebih dahulu kepada Pengguna.</li>
                            <li>Menolak permintaan layanan yang melanggar Ketentuan ini atau peraturan yang berlaku.</li>
                            <li>Menggunakan data Pengguna sesuai dengan Privacy Policy yang berlaku.</li>
                            <li>Menampilkan nama atau logo Klien (dengan persetujuan) sebagai bagian dari portofolio.</li>
                        </ul>
                        <p class="mt-4"><strong>Kewajiban Digimaya:</strong></p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Memberikan Layanan dengan profesionalisme dan keahlian yang wajar.</li>
                            <li>Melindungi data dan informasi Pengguna sesuai Privacy Policy.</li>
                            <li>Memenuhi komitmen yang tertuang dalam perjanjian tertulis dengan Klien.</li>
                            <li>Mematuhi peraturan perundang-undangan yang berlaku.</li>
                        </ul>
                    </div>
                </section>

                <section id="pembayaran" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Pembayaran dan Refund</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p><strong>Ketentuan Pembayaran:</strong></p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Biaya layanan, jadwal pembayaran, dan metode pembayaran diatur dalam proposal atau perjanjian tertulis terpisah.</li>
                            <li>Seluruh biaya yang tertera belum termasuk pajak, kecuali dinyatakan sebaliknya.</li>
                            <li>Keterlambatan pembayaran dapat mengakibatkan penangguhan layanan dan/atau pengenaan denda sesuai kesepakatan.</li>
                        </ul>
                        <p class="mt-4"><strong>Kebijakan Refund:</strong></p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Refund tunduk pada kebijakan yang tertuang dalam proposal atau perjanjian tertulis dengan Klien.</li>
                            <li>Untuk produk training/edukasi, kebijakan refund tertera pada halaman pendaftaran masing-masing program.</li>
                            <li>Layanan management fee yang sudah berjalan dan terealisasi tidak dapat di-refund.</li>
                        </ul>
                    </div>
                </section>

                <section id="hki" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Hak Kekayaan Intelektual</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Seluruh Konten yang ditampilkan di website dan Layanan Digimaya — termasuk logo, branding, teks, grafik, video, materi training, dan kode — adalah milik PT Digital Maya Group atau pihak ketiga yang memberikan lisensi kepada kami.</p>
                        <p>Pengguna dilarang menyalin, mendistribusikan, memodifikasi, atau menggunakan kembali Konten kami untuk kepentingan komersial tanpa izin tertulis dari Digimaya.</p>
                        <p>Akun iklan klien, data klien, dan strategi yang dibuat untuk klien tetap menjadi milik klien yang bersangkutan.</p>
                    </div>
                </section>

                <section id="konten-pengguna" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Konten Pengguna</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Apabila Anda mengirimkan konten kepada kami (misalnya gambar produk, copywriting, atau materi marketing), Anda menjamin:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Konten tersebut adalah milik Anda atau Anda memiliki hak untuk menggunakannya.</li>
                            <li>Konten tidak melanggar hak kekayaan intelektual atau hak privasi pihak ketiga.</li>
                            <li>Konten tidak mengandung unsur yang melanggar hukum, SARA, kebencian, atau pornografi.</li>
                        </ul>
                        <p>Anda memberikan kepada Digimaya lisensi non-eksklusif untuk menggunakan konten tersebut dalam rangka pelaksanaan Layanan. Kami berhak menolak atau menghapus konten yang melanggar Ketentuan ini.</p>
                    </div>
                </section>

                <section id="privasi" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Privasi</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Pengumpulan, penggunaan, dan perlindungan data pribadi Anda diatur dalam <a href="{{ route('privacy') }}" class="text-brand hover:underline font-semibold">Privacy Policy</a> kami yang merupakan bagian tidak terpisahkan dari Ketentuan ini.</p>
                        <p>Dengan menggunakan Layanan, Anda menyatakan telah membaca dan menyetujui Privacy Policy tersebut.</p>
                    </div>
                </section>

                <section id="pembatasan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Pembatasan Penggunaan</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Pengguna dilarang melakukan hal-hal berikut saat menggunakan Layanan:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Melakukan reverse engineering, dekompilasi, atau usaha untuk memperoleh source code Layanan.</li>
                            <li>Melakukan scraping, crawling, atau pengumpulan data otomatis dari website kami tanpa izin.</li>
                            <li>Menggunakan Layanan untuk mengirim spam, malware, atau konten berbahaya.</li>
                            <li>Mencoba mengakses sistem, akun, atau data yang bukan milik Anda.</li>
                            <li>Mengganggu atau membebani infrastruktur kami secara tidak wajar.</li>
                            <li>Menggunakan Layanan untuk aktivitas yang melanggar hukum atau peraturan yang berlaku.</li>
                        </ul>
                    </div>
                </section>

                <section id="disclaimer" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Penolakan Tanggung Jawab</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Layanan diberikan "sebagaimana adanya" (as-is) dan "sebagaimana tersedia" (as-available). Digimaya tidak menjamin:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Bahwa Layanan akan selalu tersedia tanpa gangguan, error, atau bug.</li>
                            <li>Hasil spesifik dari kampanye iklan, termasuk jumlah leads, konversi, atau ROAS tertentu — karena performa iklan bergantung pada banyak faktor di luar kendali kami (kualitas produk/layanan klien, kompetisi pasar, kebijakan platform, dll).</li>
                            <li>Kecocokan Layanan dengan tujuan spesifik di luar yang tertulis dalam perjanjian.</li>
                        </ul>
                        <p>Estimasi, proyeksi, atau benchmark yang kami berikan berdasarkan data historis dan industri, bukan jaminan hasil di masa depan.</p>
                    </div>
                </section>

                <section id="batasan-tanggung-jawab" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">13. Batasan Tanggung Jawab</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Sepanjang diizinkan oleh hukum yang berlaku:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Tanggung jawab Digimaya kepada Pengguna atau Klien dibatasi pada total biaya layanan yang telah dibayarkan dalam 6 (enam) bulan terakhir sebelum klaim diajukan.</li>
                            <li>Digimaya tidak bertanggung jawab atas kerugian tidak langsung, kehilangan keuntungan, kehilangan data, atau kerugian konsekuensial lain yang timbul dari penggunaan Layanan.</li>
                            <li>Pengecualian di atas tidak berlaku untuk kerugian yang timbul akibat kesengajaan atau kelalaian berat Digimaya.</li>
                        </ul>
                    </div>
                </section>

                <section id="ganti-rugi" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">14. Ganti Rugi</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Pengguna setuju untuk mengganti rugi dan membebaskan Digimaya beserta seluruh karyawan dan afiliasinya dari segala klaim, kerugian, atau tuntutan hukum yang timbul akibat:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Pelanggaran Ketentuan ini oleh Pengguna.</li>
                            <li>Pelanggaran hukum atau hak pihak ketiga oleh Pengguna.</li>
                            <li>Konten atau materi yang Pengguna kirimkan ke Digimaya.</li>
                            <li>Penyalahgunaan Layanan oleh Pengguna.</li>
                        </ul>
                    </div>
                </section>

                <section id="pemutusan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">15. Pemutusan</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p><strong>Pemutusan oleh Digimaya:</strong></p>
                        <p>Digimaya berhak menangguhkan atau mengakhiri akses Pengguna ke Layanan dengan atau tanpa pemberitahuan, apabila:</p>
                        <ul class="list-disc list-outside ml-6 space-y-2">
                            <li>Pengguna melanggar Ketentuan ini.</li>
                            <li>Pengguna gagal memenuhi kewajiban pembayaran.</li>
                            <li>Penggunaan Layanan dianggap merugikan Digimaya atau pihak lain.</li>
                            <li>Diwajibkan oleh perintah hukum atau otoritas berwenang.</li>
                        </ul>
                        <p class="mt-4"><strong>Pemutusan oleh Pengguna:</strong></p>
                        <p>Pengguna dapat mengakhiri kerjasama sesuai ketentuan dalam perjanjian tertulis. Untuk layanan management bulanan, pemutusan tunduk pada notice period sesuai kesepakatan.</p>
                    </div>
                </section>

                <section id="hukum" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">16. Hukum yang Berlaku dan Penyelesaian Sengketa</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Ketentuan ini diatur dan ditafsirkan berdasarkan hukum Republik Indonesia.</p>
                        <p>Setiap sengketa yang timbul dari atau sehubungan dengan Ketentuan ini akan diselesaikan secara musyawarah terlebih dahulu. Apabila musyawarah tidak mencapai kesepakatan dalam waktu 30 (tiga puluh) hari, sengketa akan diselesaikan melalui <strong>Pengadilan Negeri Bogor</strong>.</p>
                    </div>
                </section>

                <section id="perubahan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">17. Perubahan Ketentuan</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Digimaya berhak mengubah Ketentuan ini dari waktu ke waktu. Perubahan akan diumumkan di website kami minimal 30 (tiga puluh) hari sebelum berlaku, untuk perubahan yang bersifat material.</p>
                        <p>Penggunaan Layanan setelah tanggal berlaku perubahan dianggap sebagai persetujuan terhadap Ketentuan yang baru. Apabila Anda tidak setuju, Anda dapat menghentikan penggunaan Layanan.</p>
                    </div>
                </section>

                <section id="hubungi-kami" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">18. Hubungi Kami</h2>
                    <div class="space-y-4 text-base text-gray-700 leading-relaxed">
                        <p>Untuk pertanyaan atau klarifikasi terkait Terms of Service ini, silakan hubungi:</p>
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mt-4">
                            <p class="font-semibold text-gray-900 mb-3">PT Digital Maya Group (Digimaya)</p>
                            <ul class="space-y-2 text-sm text-gray-700">
                                <li><strong>Email:</strong> <a href="mailto:renra@digimaya.com" class="text-brand hover:underline">renra@digimaya.com</a></li>
                                <li><strong>Alamat:</strong> Kota Wisata, Bogor, Jawa Barat, Indonesia</li>
                                <li><strong>Website:</strong> <a href="{{ route('home') }}" class="text-brand hover:underline">digimaya.com</a></li>
                            </ul>
                        </div>
                    </div>
                </section>

            </article>

        </div>
    </div>
</section>

@endsection
