# BLUEPRINT — Internal Performance & Reporting Tool Digimaya

> **Dokumen ini adalah single source of truth proyek.** Jika dibuka di chat/sesi baru: baca dari atas ke bawah untuk memahami 100% konteks, keputusan, dan alasannya. Bagian **STATUS TERKINI** (bagian 11) selalu mencerminkan posisi terakhir. Bagian **RIWAYAT KEPUTUSAN** (bagian 12) mencatat keputusan yang pernah berubah beserta alasannya — supaya tidak ada kebingungan "kok beda dari sebelumnya".

- **Versi dokumen:** 2.0 (final untuk MVP)
- **Menggantikan:** v1.0 (24 Mei 2026)
- **Tanggal:** 25 Mei 2026
- **Pemilik proyek:** Renra Sedoya — Founder, Digimaya (Google Premier Partner, Indonesia)
- **Model kerja:** Claude generate kode, Renra orkestrasi & eksekusi (paste ke terminal, verifikasi). Setiap langkah diverifikasi sebelum lanjut.

---

## 1. KONTEKS PROYEK

### 1.1 Apa yang dibangun
Aplikasi web internal untuk tim Digimaya yang terhubung ke akun Google Ads klien via **satu MCC** (Google Ads API). Tool ini melakukan tiga hal yang **tidak tersedia / tidak praktis di Google Ads native**:
1. Pandangan lintas-akun yang difilter per Account Manager (tiap AM hanya melihat portofolionya).
2. Health scoring berbasis KPI internal Digimaya (actual vs target per klien).
3. Reporting mingguan ber-narasi AI yang siap dikirim ke klien.

### 1.2 Prinsip penentu (the north star)
**Tool ini WAJIB memberi nilai yang tidak ada di dashboard Google Ads langsung.** Setiap fitur harus lulus tes: "Apakah ini sudah ada/mudah di Google Ads? Kalau ya, kenapa dibangun ulang?" Tool ini bukan "Google Ads dashboard kedua".

### 1.3 Siapa Digimaya
Agency Google Ads berbasis Indonesia (Google Premier Partner). Menangani lead generation, scaling revenue, optimasi performa untuk bisnis Indonesia. Website: www.digimaya.com

### 1.4 Skala
- MCC berisi **lebih dari 100 akun**, tetapi **tidak semuanya dikelola Digimaya**.
- Hanya **akun terkelola** yang ditarik datanya (lihat bagian 6).
- Granularity: **account-level dengan drill-down ke campaign**.

---

## 2. STRUKTUR ORGANISASI & ROLE

### 2.1 Pengguna tool (final: 3 orang, untuk jangka panjang)
| Role | Orang | Hak akses |
|------|-------|-----------|
| **Super Admin** | Renra (pemilik) | Lihat semua akun; tarik daftar akun MCC; pilih akun terkelola; assign akun ke AM; semua fitur. |
| **Account Manager** | 2 orang | **Hanya** melihat akun yang di-assign ke dirinya. AM 1 tidak bisa melihat portofolio AM 2. |

> Peran "Admin" (bisa assign) disebutkan dalam struktur, namun untuk MVP fungsinya melekat pada Super Admin. Tidak perlu role terpisah dulu.

### 2.2 Struktur perusahaan (konteks, bukan pengguna tool)
- 1 AM menaungi 4 advertiser.
- Klien dibagi & di-assign ke masing-masing AM (mis. AM 1 pegang 40 klien, AM 2 pegang 40 klien berbeda).
- **Advertiser TIDAK mengakses tool ini.** Optimasi teknis dikerjakan advertiser; AM berkomunikasi dengan advertiser secara terpisah di luar tool.
- Tugas AM = memastikan klien berjalan baik (monitoring & insight), bukan optimasi teknis langsung.

### 2.3 Implikasi arsitektur
Karena ada role + kepemilikan data (AM hanya lihat akunnya), tool **wajib** punya sistem autentikasi & otorisasi sejak awal. Ini salah satu alasan utama memilih Django (auth & permission bawaan). **Bukan** login bersama.

---

## 3. FITUR MVP (VERSI 1) — EMPAT PILAR

Dari sisi AM, tool harus membantu dalam:

1. **Monitoring performa tiap klien di level campaign.**
2. **Melihat akun & campaign berkategori: Critical dan Need Attention** (health scoring).
3. **Melihat insight + suggestion** (narasi dibantu Claude API).
4. **Download reporting mingguan yang 100% siap kirim ke klien** (narasi dibantu Claude API).

> Jika empat poin ini terpenuhi, MVP sudah sangat bernilai.

---

## 4. SUGGESTION ENGINE — TIGA PILAR DETEKSI

Prinsip pembagian peran (PENTING): **Logika deterministik yang MENDETEKSI & MENGHITUNG. Claude API yang MENARASIKAN & MENYARANKAN.** AI duduk di lapisan paling akhir, di atas data yang sudah matang. AI TIDAK memutuskan status (mis. "Critical") — itu tugas logika, supaya konsisten & tepercaya.

### 4.1 Budget Monitoring
Memastikan actual spend sesuai budget yang di-set. Deteksi over-pacing (spend terlalu cepat) & under-pacing (budget tidak terpakai).

### 4.2 Significant Change Detection
Memonitor fluktuasi performa (% naik/turun), membandingkan periode berjalan vs sebelumnya, menentukan normal vs anomali. **Membutuhkan data historis** (lihat bagian 6.3).

### 4.3 Health Scoring (Critical / Need Attention / Healthy)
Klasifikasi akun & campaign berdasarkan actual vs KPI per klien.
- Contoh aturan (Renra): Target CPA Rp50.000, actual Rp100.000 → **Critical**.
- Tiap klien punya KPI sendiri (target CPA, ROAS, budget) — disimpan di DB.
- **Threshold Critical/Need Attention/Healthy = keputusan domain Renra**, didefinisikan di Fase 3. Logika kodenya mudah; nilai ambangnya berharga & harus dari pengalaman praktisi.

---

## 5. ARSITEKTUR REPORTING (ber-AI)

Alur: **Data dari DB → Logika menghitung metrik & perubahan (compare last 7 days) → Claude API menarasikan key point per metrik per campaign + membuat suggestion untuk 1 minggu ke depan → Render jadi laporan siap kirim.**

- Reporting mingguan menampilkan metrik performa mingguan (compare last 7 days).
- Claude API membuat: (a) key point dari tiap metrik per campaign, (b) suggestion untuk minggu depan.
- **Catatan (untuk Fase reporting, belum diputuskan):** Laporan "siap kirim klien" perlu mekanisme **review oleh AM sebelum dikirim** — bukan auto-kirim mentah — karena narasi AI akan dibaca klien. Format output (PDF, dll) ditentukan di fase tsb.

---

## 6. MANAJEMEN DATA

### 6.1 Akun terkelola (managed accounts) — JANGAN tarik semua
- Tarik **daftar nama + ID semua akun MCC** sekali (ringan, bukan metrics).
- Super Admin **memilih manual** mana akun yang dikelola Digimaya.
- Hanya akun terkelola yang ditarik metrics-nya & di-assign ke AM.
- Akun yang berhenti dikelola → ditandai **nonaktif** (stop tarik data, data lama tetap disimpan). Bukan dihapus mentah.

### 6.2 Penugasan (assignment)
- Super Admin assign tiap akun terkelola ke AM tertentu.
- Ini sekaligus filter portofolio per AM (lihat bagian 2).

### 6.3 Retensi data — SIMPAN HISTORIS PANJANG, tidak ada auto-cleanup (untuk sekarang)
- **Keputusan: TIDAK membuat auto-delete sekarang.**
- Alasan: volume sangat kecil untuk PostgreSQL. Estimasi ~100 akun × ~10 campaign × harian ≈ 365 ribu baris/tahun = hitungan megabyte. Postgres menangani jutaan baris dengan ringan di CPX22.
- Auto-delete 2 bulan akan **merusak** fitur Significant Change & perbandingan year-over-year yang justru diinginkan.
- **Simpan minimal 13–14 bulan** (untuk banding tahunan); realistis bisa bertahun-tahun tanpa masalah.
- Jika suatu hari data membengkak: solusinya **rollup/agregasi** (padatkan data harian lama jadi ringkasan), BUKAN hapus. Ini optimasi masa depan, mungkin tak pernah diperlukan.

---

## 7. TECH STACK FINAL

> Semua sudah dipertimbangkan matang & terkunci. Lihat bagian 12 untuk riwayat perubahan keputusan.

| Lapisan | Pilihan | Alasan ringkas |
|---------|---------|----------------|
| **Bahasa** | Python | Library `google-ads` paling matang; ekosistem AI/analisis kaya. |
| **Framework** | **Django** | Auth + role/permission + admin panel BAWAAN & aman by default — persis kebutuhan role & assign. Mirip Laravel (yang sudah dikuasai Renra via orkestrasi AI), jadi model mentalnya sudah dikenal. |
| **Database** | PostgreSQL | Kuat untuk time-series & query agregat/window function. |
| **Visualisasi** | **Chart.js** | Ringan, gratis, dokumentasi melimpah, mudah di-generate konsisten, menempel mulus di template Django. (ApexCharts = alternatif setara bila perlu.) |
| **AI / Narasi** | **Claude API** | Menarasikan metrik & membuat suggestion di laporan (lapisan akhir, di atas data matang). |
| **Scheduling/ETL** | cron (awal) | Tarik data 1x/hari di jam sepi. Celery+Redis hanya jika perlu nanti. |
| **Hosting** | **VPS Hetzner CPX22** | 2 vCPU AMD / 4 GB RAM / 80 GB SSD, ~€8.49/bln, bayar bulanan. NVMe cepat & jaringan konsisten (krusial untuk ETL & Postgres). Ubuntu 24.04 LTS. |

---

## 8. ARSITEKTUR SISTEM

### 8.1 Alur data
```
Google Ads API (MCC, >100 akun)
   │  (a) tarik daftar akun (ringan) → Super Admin pilih managed accounts
   │  (b) ETL via cron 1x/hari → hanya managed accounts
   ▼
PostgreSQL  ── accounts, campaigns, daily_metrics, client_kpi/targets, users, assignments
   │
   ├──► Logika deteksi (budget / change / health scoring) — DETERMINISTIK
   │
   ├──► Django web app (role-based) ── Super Admin lihat semua; AM lihat portofolionya
   │         └── Chart.js untuk visualisasi
   │
   └──► Reporting: logika hitung → Claude API narasi → laporan siap kirim
```

### 8.2 Prinsip kunci
- **Dashboard & reporting baca dari PostgreSQL, BUKAN tembak Google Ads API langsung** (kecepatan + hemat quota + hindari rate limit).
- **ETL hemat request:** batch, manfaatkan query Google Ads API yang ambil banyak data sekaligus.
- **AI di lapisan akhir**, di atas data & status yang sudah dihitung logika.

### 8.3 Skema DB (draft — didetailkan di Fase 1)
- `users` — 3 user + role (Super Admin / AM).
- `accounts` — semua akun MCC; flag `is_managed`, `is_active`, `assigned_am`.
- `campaigns` — campaign per akun terkelola.
- `daily_metrics` — metrics harian per campaign (impressions, clicks, cost, conversions, dst).
- `client_kpi` / `targets` — target per klien (target CPA, ROAS, budget).

---

## 9. RENCANA FASE PENGERJAAN

> Prinsip: kerjakan yang **paling berisiko dulu** (akses API & ETL), bukan yang paling menarik (chart cantik). Tiap fase menghasilkan sesuatu yang bisa diverifikasi.

- **Fase 0 — Akses & Kredensial** *(jalur kritis, bergantung pihak luar)*: ajukan developer token **Basic access** di MCC; setup Google Cloud project, OAuth2, refresh token; tes script kecil menarik daftar akun MCC.
- **Fase 0b — Setup VPS** *(paralel)*: provision Hetzner CPX22 (Ubuntu 24.04) → login pertama via Terminal Mac → **amankan server dulu** (user non-root, SSH key, firewall) → install Python, PostgreSQL, Django, dependensi.
- **Fase 1 — ETL & Database**: skema DB; tarik daftar akun → pilih managed; script ETL metrics harian → Postgres; jalankan manual lalu cron.
- **Fase 2 — Auth & Role**: sistem login Django; role Super Admin/AM; halaman assign akun ke AM; filter portofolio per AM.
- **Fase 3 — Dashboard + Suggestion Engine**: overview + drill-down campaign; health scoring (definisikan threshold bersama Renra); budget & change detection; Chart.js.
- **Fase 4 — Reporting ber-AI**: compare last 7 days; integrasi Claude API untuk narasi & suggestion; mekanisme review AM; output siap kirim.
- **Fase 5 — Polish**: UX, domain, keamanan akhir.

---

## 10. KEPUTUSAN YANG MASIH TERBUKA

1. **Threshold health scoring** (Critical/Need Attention/Healthy) — nilai dari Renra, di Fase 3.
2. **Daftar metrik harian pasti** yang dipantau (cost, conv, CPA, ROAS, CTR, impression share, metrik internal?) — didetailkan Fase 1.
3. **Frekuensi update** — 1x/hari (default) atau perlu beberapa kali sehari? — memengaruhi desain ETL.
4. **Rentang historis untuk perbandingan di UI** (7 hari / bulan / tahun lalu) — default simpan panjang (bagian 6.3).
5. **Format & mekanisme review laporan** sebelum kirim klien — Fase 4.
6. **Akses tool via domain atau IP** + HTTPS — diputuskan saat setup web (Fase 2/5).
7. **Cara render chart di laporan** (statis vs interaktif) — Fase 4.

---

## 11. STATUS TERKINI

> **Update bagian ini setiap selesai satu langkah.**

- **Tanggal update terakhir:** 25 Mei 2026
- **Fase aktif:** Fase 0 (akses) + Fase 0b (VPS), paralel.
- **Sudah dikunci:** seluruh stack (bagian 7), arsitektur (bagian 8), role (bagian 2), fitur MVP (bagian 3), manajemen data (bagian 6).
- **Sedang berjalan:**
  - Email ke Pak Ivan (Google Indonesia, support Premier Partner) untuk bantuan pengajuan developer token Basic access — **dijadwalkan terkirim pagi ini.**
- **Langkah berikutnya yang konkret:**
  1. (Renra) Kirim & tindak lanjuti email Pak Ivan → urus developer token Basic access.
  2. (Renra) Buat akun Hetzner Cloud + provision **CPX22** (Ubuntu 24.04). Siapkan: kartu kredit/debit internasional, dokumen identitas (kemungkinan verifikasi anti-fraud). Catat: IP address, kredensial root, konfirmasi status running.
  3. (Bersama) Login pertama VPS via Terminal Mac → amankan server → install software.
- **Catatan penting yang tidak boleh hilang:**
  - Ke Google: minta **Basic access**, framing **internal monitoring tool, read-only**.
  - Jangan login VPS tanpa urutan pengamanan (risiko terkunci di luar).
  - Dashboard/reporting baca dari DB, jangan tembak API langsung.
  - AI menarasikan, logika mendeteksi (AI tidak menentukan status).
  - Jangan tarik semua akun MCC — hanya managed accounts.
  - Tidak ada auto-cleanup; simpan historis panjang.

---

## 12. RIWAYAT KEPUTUSAN (yang pernah berubah & alasannya)

> Bagian ini mencegah kebingungan "kok beda dari diskusi awal". Perubahan = menyesuaikan informasi baru, bukan keraguan.

- **Hosting: shared hosting Domainesia → ditolak → VPS.** Google Ads API butuh background job panjang, OAuth/refresh token, instalasi dependensi bebas, ETL berat — tidak cocok shared hosting.
- **Provider VPS: Domainesia → Hetzner CPX22.** Prioritas Renra = harga. Dibanding Contabo (lebih murah, RAM/CPU lebih besar): Contabo lemah di konsistensi jaringan & curam untuk pemula; Hetzner menang di NVMe cepat + jaringan konsisten + ramah pemula. Lokasi Singapore Contabo berdampak kecil untuk use case ini (ETL background 1x/hari).
- **Paket: "CX22" → CPX22.** Yang tersedia di UI Hetzner adalah lini CPX (AMD). CPX22 = 2 vCPU/4GB/80GB, padanan terbaik.
- **DB: SQLite (sempat dipertimbangkan untuk MVP) → PostgreSQL.** Skala & kebutuhan agregat/historis.
- **Frontend/framework: Streamlit → (sempat condong) → Django (FINAL).** Awalnya Streamlit untuk kecepatan MVP read-only. Lalu muncul kebutuhan role + reporting → condong Django. Sempat ragu Django karena kurva belajar untuk "pemula ngoding sendiri". **Diputuskan Django final** setelah tahu Renra sudah menyelesaikan 3 proyek Laravel dengan model "AI generate, Renra paste" — model mental framework MVC sudah dikenal, jadi kurva belajar bukan penghalang. Django unggul untuk auth/role/admin-panel & reporting.
- **Cleanup data: rencana auto-delete 2 bulan → dibatalkan.** Volume sangat kecil untuk Postgres; auto-delete merusak fitur historis (significant change, year-over-year).

---

## 13. PROFIL RENRA (untuk kalibrasi panduan)
- Performance marketing strategist, Google Ads specialist, founder Digimaya (Premier Partner).
- Sangat kuat di domain Google Ads / performance marketing → sumber heuristik suggestion engine & threshold health scoring.
- **Model kerja: orkestrasi AI** — "Claude generate kode, Renra paste & eksekusi". Sudah selesaikan 3 proyek Laravel dengan cara ini tanpa menguasai PHP.
- **Pengalaman sebelumnya: shared hosting** (upload file langsung jalan). **VPS = medan baru** (harus setup panggung sendiri: install, web server, keamanan). Perlu dipandu dengan pola generate→paste.
- AI sebagai partner berpikir strategis, bukan sekadar generator.
- Komputer: Mac (akses VPS via Terminal + SSH).

---

*Akhir blueprint v2.0. Perbarui nomor versi & tanggal saat ada perubahan signifikan. Simpan dokumen ini & upload di awal tiap sesi baru dengan Claude untuk kontinuitas penuh.*
