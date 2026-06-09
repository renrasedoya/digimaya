# Digimaya CRM — AI Content Engine Notes

> **Status**: BLUEPRINT / POSTPONED (belum diimplementasi). Disusun 25 Mei 2026.
> **Pair with**: `notes-general.md` (stack, konvensi, dev rules) + `notes-marketing__4_.md` (blog schema).
> **Scope**: AI-powered Google Ads content pipeline yang menghasilkan draft BlogPost.
> **Prinsip kunci**: Pipeline hidup di tabel SENDIRI. `blog_posts` TIDAK berubah. Output pipeline
> = pembuatan `BlogPost` normal lewat path yang sudah ada (Quill, thumbnail accessor, permalink).

---

## 0. KEPUTUSAN YANG SUDAH DIKUNCI (25 Mei 2026)

1. **6 section = kerangka prompt, BUKAN skema DB.** Hook / Main Insight / Practical /
   Personal Perspective / Video / Soft CTA adalah cara AI diarahkan menulis. Output tetap
   satu blob HTML → kolom `content` (longtext) yang sudah ada. `blog_posts` tidak disentuh.
2. **Humanization BUKAN step otomatis.** Diturunkan jadi (a) kerangka prompt yang baik dari
   awal + (b) sentuhan manusia di tahap review (Personal Perspective + video = selalu manual).
   Alasan: prompt "buat terdengar manusiawi" justru sering bikin output makin terasa AI.
3. **SEO optimization tetap boleh otomatis** (meta_title ≤70, meta_description ≤160, struktur
   heading) karena mekanis. Mapping ke kolom `blog_posts` yang sudah ada.
4. **Topic Selection = gerbang approval manual.** Topik nyangkut nunggu di-ACC. Yang ditolak
   tidak lanjut ke generation (hemat token).
5. **Ada lapisan filter/dedup SEBELUM AI** (antara storage dan analysis) — buang duplikat
   lintas-feed + item tak relevan, supaya tidak bayar token untuk sampah & tren tidak bias.

---

## 1. DECISION POINTS (HARUS DIPUTUSKAN SEBELUM IMPLEMENTASI)

> Dua hal ini belum terjawab. Implementasi Fase A & B/C bergantung pada keduanya.
> JANGAN mulai coding sebelum ini dikonfirmasi.

### DP-1: Cron di DomaiNesia — tersedia atau tidak?
- **Cek**: cPanel → "Cron Jobs" (Advanced). Atau SSH: `crontab -l`.
- **Jika ADA**: Feed Fetcher (Fase A) bisa scheduled via Laravel scheduler
  (`php artisan schedule:run` dipanggil cron tiap menit, lalu jadwal di `app/Console/Kernel.php`).
- **Jika TIDAK ADA / terkunci**: Fetcher jadi **trigger manual** — tombol "Fetch Now" di admin
  yang memanggil command `php artisan feeds:fetch` secara on-demand. Tetap jalan, tidak otomatis.
- **Catatan**: notes-general (Soft Delete section) menyebut keputusan MENGHINDARI auto-purge job
  karena "di shared hosting butuh cron". Default asumsi sementara: **cron dihindari → fetcher manual**.

### DP-2: Sumber panggilan AI — API mana, budget?
- Fase B (analysis/topic) & Fase C (drafting) butuh panggilan model. Tidak gratis, tidak built-in.
- Butuh **API key** (Anthropic / OpenAI). Tiap panggilan ada biaya per token.
- **Konteks**: Renra sudah pernah pakai Anthropic API (Campaign Brief Generator widget, April 2026),
  kemungkinan sudah familiar + punya key.
- **Yang perlu diputuskan**: provider mana, simpan key di `.env` (`ANTHROPIC_API_KEY`), estimasi
  biaya per artikel (analysis 1x + drafting 1-6x panggilan tergantung strategi section).

### DP-3 (turunan): Section-by-section = 1 panggilan atau N panggilan?
- **1 panggilan**: AI tulis 6 section sekaligus. Murah, cepat, tapi kontrol per-section rendah.
- **N panggilan** (mis. outline 1x + tiap section 1x): mahal & lambat, tapi tiap section fokus
  & bisa di-tune. Putuskan setelah DP-2 (budget) jelas. Rekomendasi awal: mulai 1 panggilan,
  pecah hanya kalau kualitas section tertentu kurang.

---

## 2. ARSITEKTUR — STAGING TERPISAH

```text
[Trusted RSS Feeds]
        ↓  (Fase A)
  Feed Fetcher  ──────────────→  feed_sources / feed_items   (tabel BARU, no AI)
        ↓
  Filter & Dedup  ─────────────→  buang duplikat URL + item tak relevan  (sebelum AI)
        ↓  (Fase B)
  AI Trend Analysis ──→ Topic + Angle  →  content_topics   (tabel BARU)
        ↓
  [GERBANG APPROVAL MANUAL]  →  status: suggested → approved / rejected
        ↓  (Fase C)
  Draft Generation (outline → 6-section → SEO) 
        ↓
  ════════════ BATAS: di sini baru menyentuh blog_posts ════════════
        ↓
  Buat BlogPost (status: draft / 'ai_generated')   ← lewat path normal yang sudah ada
        ↓  (Fase D — SEBAGIAN BESAR SUDAH ADA)
  Manual Review (Quill) + isi Personal Perspective + Video → Publish
```

**Inti**: tiga state pipeline (feed mentah, topik kandidat, draft AI) hidup di tabel sendiri.
Hanya ujung Fase C yang menyentuh `blog_posts`, itu pun lewat pembuatan BlogPost biasa.

---

## 3. SCHEMA BARU (tabel pipeline — TIDAK menyentuh blog_posts)

> Semua FK numeric WAJIB di-cast `'integer'` (notes-general §6 Type Cast Rule).
> Pakai SoftDeletes untuk entitas yang bernilai histori; generator nilai unik WAJIB `->withTrashed()`
> (notes-general SoftDeletes bug family). Tabel plural snake_case, model singular PascalCase.

### `feed_sources`
```
id              bigint unsigned
name            varchar(120)
url             varchar(500)     — RSS/Atom feed URL
is_active       boolean default true
last_fetched_at datetime nullable
created_at, updated_at, deleted_at   — SoftDeletes (jaga histori sumber)
```
- Model `FeedSource`: scopes `active()`, casts `is_active=>boolean`, `last_fetched_at=>datetime`.

### `feed_items`
```
id              bigint unsigned
feed_source_id  bigint unsigned  — FK feed_sources, cast 'integer'
guid            varchar(500)     — guid/link dari feed, dasar dedup (unique per source)
title           varchar(500)
link            varchar(500)
raw_summary     text nullable    — ringkasan mentah dari feed
published_at    datetime nullable
is_relevant     boolean nullable — hasil filter relevansi (null = belum difilter)
is_duplicate    boolean default false
created_at, updated_at           — TANPA SoftDeletes (data mentah, boleh hard delete saat purge)
```
- **Dedup**: unique index `(feed_source_id, guid)`. Insert pakai `updateOrCreate` atau cek `exists()`.
- Model `FeedItem`: FK cast `'integer'`, scopes `relevant()`, `notDuplicate()`, `recent()`.

### `content_topics`
```
id              bigint unsigned
title           varchar(300)     — judul topik kandidat
angle           text             — unique angle dari AI
rationale       text nullable    — kenapa AI usulkan ini (tren yang mendasari)
status          enum('suggested','approved','rejected') default 'suggested'
source_note     text nullable    — feed item yang menginspirasi (referensi, bukan FK ketat)
reviewed_by     bigint unsigned nullable  — FK users, cast 'integer' (verb_by pattern)
reviewed_at     datetime nullable
created_at, updated_at, deleted_at   — SoftDeletes
```
- Model `ContentTopic`: STATUSES const + STATUS_* const, scopes `suggested()`/`approved()`/`rejected()`,
  FK `reviewed_by` cast `'integer'`, status filter dropdown-with-count (notes-general §5).
- **Gerbang approval**: hanya `approved` yang boleh masuk Fase C.

### `content_drafts` (opsional — lihat catatan)
```
id              bigint unsigned
content_topic_id bigint unsigned — FK content_topics, cast 'integer'
generated_html  longtext        — hasil 6-section dari AI (sebelum jadi BlogPost)
suggested_meta_title       varchar(70) nullable
suggested_meta_description varchar(160) nullable
ai_model        varchar(50) nullable     — model + versi yang dipakai (audit)
blog_post_id    bigint unsigned nullable — FK blog_posts (terisi setelah dipromosikan jadi post)
created_at, updated_at, deleted_at   — SoftDeletes
```
- **Catatan keputusan**: `content_drafts` bisa DILEWATI kalau Fase C langsung bikin BlogPost
  berstatus draft. Tapi tabel ini berguna untuk: simpan output AI mentah TERPISAH dari editan
  manusia (audit "apa yang AI tulis vs apa yang Renra ubah"), dan retry generation tanpa
  mengotori blog admin. **Rekomendasi: pakai**, ringan & memberi jejak audit.

---

## 4. STATUS BARU DI blog_posts (OPSIONAL — butuh migrasi enum)

Enum `status` sekarang: `draft / scheduled / published`. Dua opsi:
- **Opsi A (no schema change)**: draft AI = status `draft` biasa, dibedakan via `content_drafts.blog_post_id`
  atau flag. PALING AMAN, tidak menyentuh enum.
- **Opsi B**: tambah nilai enum `ai_generated` untuk bedain draft AI yang belum disentuh manusia.
  Butuh migrasi `MODIFY COLUMN` enum + update `BlogPost::STATUSES` const + label + validasi
  `Rule::in(STATUSES)`. Lebih eksplisit tapi menyentuh tabel verified.
- **Rekomendasi**: **Opsi A**. Hindari mengubah enum tabel yang sudah verified kecuali benar-benar perlu.

---

## 5. PEMECAHAN FASE (urut, tiap fase verify sendiri)

> Urutan ketergantungan: A → B → C → D. TAPI C bisa dites manual duluan (ketik topik sendiri,
> skip A & B) untuk validasi kualitas output AI sebelum invest infra RSS.

### Fase A — RSS Ingestion (NO AI)
- Migrasi `feed_sources` + `feed_items`.
- Model + admin CRUD `feed_sources` (tambah/nonaktif feed). Akses: super_admin, admin, marketing
  (samakan dengan modul Marketing, notes-marketing §2).
- Command `feeds:fetch` (pakai paket fetch RSS, mis. `willvincent/feeds` atau SimplePie) →
  parse → `updateOrCreate` ke `feed_items` (dedup by guid).
- **Trigger**: tombol "Fetch Now" (jika DP-1 = no cron) ATAU scheduler (jika cron ada).
- **Verify**: tambah 1 feed → fetch → item muncul di admin, tidak ada duplikat saat fetch 2x.

### Fase B — Trend Analysis & Topic Selection
- Service `TrendAnalysisService` (di `app/Services/`, ikut pola `LeadPromotionService`).
- Input: `feed_items` relevant + notDuplicate dalam periode (mis. 7-14 hari).
- Filter relevansi: bisa rule sederhana (keyword Google Ads) dulu, AI klasifikasi belakangan.
- AI call → output JSON list `{title, angle, rationale}` → simpan `content_topics` status `suggested`.
- Admin: list topik + tombol Approve / Reject (verb_by → `reviewed_by` + `reviewed_at`).
- **Verify**: dari kumpulan feed dapat daftar topik masuk akal, bisa ACC/tolak, hemat token
  (item tak relevan tidak ikut dikirim ke AI).

### Fase C — Draft Generation
- Service `ContentDraftService`. Input: 1 `content_topic` status `approved`.
- AI call dengan kerangka 6-section (Hook/Main Insight/Practical/Personal Perspective/Video/Soft CTA).
  Personal Perspective & Video → AI HANYA kasih placeholder/prompt, diisi manusia di Fase D.
- Output: `generated_html` + `suggested_meta_title` + `suggested_meta_description` → `content_drafts`.
- Tombol "Promote to Draft" → buat `BlogPost` (status draft, content = generated_html,
  meta dari suggested_*, created_by = user) lewat path normal. Set `content_drafts.blog_post_id`.
- **Verify**: pilih topik approved → generate → draft muncul di blog admin siap di-edit di Quill.

### Fase D — Review & Publish (SEBAGIAN BESAR SUDAH ADA)
- Blog form Quill existing sudah bisa: edit content, isi `youtube_video_id`, set status published.
- Tambahan kecil (opsional): banner "draft ini dari AI — isi Personal Perspective + Video
  sebelum publish". Bisa via `content_drafts.blog_post_id` not null check.
- **Verify**: edit draft AI → tambah opini + video ID → publish → muncul di `/blog`.

---

## 6. KEPATUHAN KONVENSI (cross-check notes-general)

- **Dev workflow**: edit file PHP/Blade via cPanel File Manager / file-based heredoc + Python
  `count == 1` verify. JANGAN paste PHP/Blade ke bash. JANGAN `sed` untuk file ber-`$`/`{{ }}`.
- **Type cast**: semua FK (`feed_source_id`, `content_topic_id`, `reviewed_by`, `blog_post_id`)
  cast `'integer'` di `$casts`.
- **SoftDeletes bug family**: tidak ada generator nilai unik baru di pipeline ini (feed_items
  pakai guid dari sumber, bukan generated). Jika nanti ada, WAJIB `->withTrashed()`.
- **HTTP verbs**: DELETE/PUT/PATCH AMAN di DomaiNesia (verified 22 Mei). Resource route normal OK.
- **UI**: filter month+year + status dropdown-with-count (pola seragam index). Pagination 15/page.
  Inline-expand / single-modal-dispatch untuk aksi. Tailwind: grep dulu class sebelum pakai
  (aman: gray/red/indigo; hindari yellow-600+, lg:grid-cols-5+).
- **Copy Indonesia**: kamu (bukan Anda), Title Case label, tanpa em dash/panah/emoji.
- **Akses role**: pipeline ini bagian dari domain Marketing → super_admin, admin, marketing.
  account_manager & advertiser TIDAK akses.
- **Activity log**: model utama (`ContentTopic`, `ContentDraft`) pakai `LogsActivity` (Spatie)
  seperti `BlogPost`.

---

## 7. RISIKO & CATATAN

- **Biaya token**: filter/dedup sebelum AI itu wajib, bukan opsional — tanpa itu biaya membengkak
  & analisa tren bias. (Lihat §0 poin 5.)
- **Kualitas output**: validasi Fase C secara manual DULU (ketik topik sendiri) sebelum bangun
  A & B. Jangan invest infra RSS sebelum yakin draft AI layak.
- **Humanization trap**: jangan tergoda bikin "humanization pass" otomatis. Nilai paling manusiawi
  (opini, campaign insight) datang dari Renra di review, bukan dari prompt.
- **Shared hosting limits**: RSS fetch banyak feed sekaligus bisa kena timeout / memory limit
  DomaiNesia. Batasi jumlah feed per fetch, atau fetch per-source bergiliran.
- **Soft-delete volume**: `feed_items` bisa numpuk cepat. Sudah didesain TANPA SoftDeletes →
  aman di-purge berkala (notes-general: pembersihan manual, no auto-purge job).

---

## 8. NEXT ACTION SAAT RESUME

1. Jawab DP-1 (cron?) + DP-2 (API key & provider?) + DP-3 (1 vs N panggilan).
2. Pilih Opsi A/B untuk status (rekomendasi: A, no enum change).
3. Mulai dari Fase A (paling fondasional & aman) ATAU tes Fase C manual dulu (validasi kualitas AI).
