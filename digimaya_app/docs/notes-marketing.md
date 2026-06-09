# Digimaya CRM — Marketing Module Notes

> **Pair with**: `notes-general.md`
> **Scope**: Blog, Case Studies, Testimonials, FAQ, Logo Wall, Marketing Overview
> **Audience access**: super_admin, admin, marketing
> **Purpose**: Content management for public-facing website (digimaya.com)

> **VERIFICATION STATUS (22 May 2026)**
> The **Blog** sections below were rewritten against live ground truth (DB `SHOW COLUMNS`,
> actual model/controller/views). They are accurate as of this date.
> The **other modules** (Case Studies, Testimonials, FAQ, Logo Wall, Homepage rendering)
> were NOT re-verified this session and may have drifted from the live code — treat them
> as historical/aspirational until checked against the DB. Sections that are unverified
> are marked `[UNVERIFIED]`.

---

## 1. PHILOSOPHY

Marketing module manages all **public-facing content** — what visitors see on the website. This is content-as-data: structured records that render dynamically on public pages, NOT hardcoded HTML.

**Principle**: Update content via admin panel without touching code. Public pages query DB and render.

---

## 2. ROLE STAKEHOLDERS

| Role | Marketing Access |
|---|---|
| `super_admin` | Full |
| `admin` | Full |
| `marketing` | Full (this is their primary module) |
| `account_manager`, `advertiser` | NO ACCESS |

---

## 3. SUB-MODULES OVERVIEW

| Sub-module | Purpose | Public Page Render |
|---|---|---|
| Blog Posts | Long-form articles | `/blog`, `/blog/{public_id}/{slug}` |
| Blog Categories | Blog taxonomy | Used as filter on `/blog` |
| Case Studies | Client success stories | Section on homepage / dedicated page |
| Case Study Results | Metrics per case study | Inside case study detail |
| Testimonials | Client quotes | Homepage section |
| FAQ | Frequently asked questions | Homepage section / FAQ page |
| Logo Wall | Client logos + Awards | Homepage logo bar (split: clients vs awards) |

---

## 4. DATA MODEL

### `blog_posts` — VERIFIED 22 May 2026 (live `SHOW COLUMNS`)

Actual columns:
```
id              bigint unsigned
public_id       varchar(3)      — 3-char alphanumeric lowercase, auto-generated, unique
title           varchar(200)
slug            varchar(220)    — auto from title
content         longtext        — HTML, sanitized via mews/purifier ('blog' profile)
youtube_video_id varchar(20)    — YouTube video ID only (not full URL); nullable
meta_title      varchar(70)     — SEO; nullable; also used as text-thumbnail text
meta_description varchar(160)   — SEO; nullable
status          enum('draft','scheduled','published')
published_at    datetime        — nullable
created_by      bigint unsigned — FK users (note: created_by, NOT author_id)
created_at, updated_at, deleted_at  — timestamps + SoftDeletes
```

**IMPORTANT — differs from older notes:**
- NO `excerpt` column (never existed in live schema).
- NO `featured_image` column. Thumbnail column `thumbnail` was DROPPED 22 May 2026.
- NO `view_count` column.
- FK is `created_by`, not `author_id`.
- Permalink format is `/blog/{public_id}/{slug}` (public_id + slug), not `/blog/{slug}`.
- Uses **SoftDeletes** (has `deleted_at`). Older notes said hard delete — that is outdated.

**Relations**: pivot `blog_post_category` → `blog_categories`.

### Thumbnail strategy (zero-storage) — VERIFIED 22 May 2026

Decision: blog posts no longer store thumbnail image files at all (saves storage as posts grow).
Logic lives entirely in model accessors (single source of truth):

- If post HAS `youtube_video_id` → thumbnail = hotlinked YouTube cover
  `https://img.youtube.com/vi/{id}/maxresdefault.jpg` (zero storage). Rendered at 16:9.
  JS `onerror` falls back to `hqdefault.jpg` (maxres can 404 on low-res/old videos).
- If post has NO video → generated **text-card**: a colored `<div>` with the post text,
  no image file. Background color is deterministic from the title; text is `meta_title ?: title`.

All public thumbnails forced to `aspect-video` (16:9) for a uniform grid.

OG image (`og_image`) is only set for video posts (text-cards are not real image files).

### `blog_categories` — [UNVERIFIED]
```
id, name, slug (unique), description, display_order, timestamps
```
Note: live table also has `deleted_at` (SoftDeletes).

### `blog_post_category` (pivot)
```
blog_post_id, blog_category_id
```
No SoftDeletes on pivot — detaching on post delete is permanent (restore won't bring categories back).

### `case_studies` — [UNVERIFIED]
```
id, title, slug (unique), client_name, industry,
challenge (text), solution (text), summary (text),
featured_image, gallery (JSON array of image paths, nullable),
status enum('draft','published') default 'draft',
display_order, timestamps
```

### `case_study_results` — [UNVERIFIED]
```
id, case_study_id (FK case_studies, cascadeOnDelete),
metric_label, before_value, after_value (string — flexible like '120%', 'Rp 5.2M'),
display_order, timestamps
```

### `testimonials` — [UNVERIFIED]
```
id, client_name, client_company, client_role,
quote (text), avatar_path (nullable),
is_featured (boolean default false),
display_order, timestamps
```

### `faqs` — [PARTIALLY VERIFIED]
```
id, question, answer (text, supports HTML via purifier),
category (string, nullable), display_order, is_active (boolean default true),
timestamps
```
**Verified 22 May 2026**: `faqs` is **HARD DELETE** (no SoftDeletes trait on model), but the
table DOES have a `deleted_at` column (vestigial — see notes-general anomaly note). Live test
confirmed delete works and removes the row entirely.

### `logo_wall_items` — [UNVERIFIED]
```
id, name, image_path, link_url (nullable),
group enum('clients','awards') — DUAL-PURPOSE table,
description (text, nullable), is_active (boolean default true),
display_order, timestamps
```

**Critical**: `group` field separates clients vs awards. Same table, different render contexts.

---

## 5. MODELS

### `BlogPost` — VERIFIED 22 May 2026
```php
class BlogPost extends Model {
    use HasFactory, SoftDeletes, LogsActivity;

    // STATUS_DRAFT / STATUS_SCHEDULED / STATUS_PUBLISHED const + STATUSES array

    protected $fillable = [
        'public_id', 'title', 'slug', 'content',
        'youtube_video_id', 'meta_title', 'meta_description',
        'status', 'published_at', 'created_by',
    ];
    protected $casts = ['published_at' => 'datetime'];

    // booted(): auto public_id on creating, auto slug from title on saving
    // generateUniquePublicId(): 3-char, retries on collision

    // Relations
    categories(): BelongsToMany BlogCategory (pivot blog_post_category)
    author(): BelongsTo User (FK created_by)

    // Scopes: draft(), scheduled(), published(), byStatus(), recent()

    // Accessors
    effective_status   // promotes scheduled→published once published_at passes
    status_label
    permalink          // route public.blog.show {public_id, slug}

    // Thumbnail accessors (zero-storage) — single source of truth:
    thumbnail_type          // 'video' | 'text'
    thumbnail_url           // youtube maxresdefault.jpg, or null
    thumbnail_fallback_url  // youtube hqdefault.jpg (JS onerror), or null
    thumbnail_text          // meta_title ?: title
    thumbnail_color         // crc32(title) % palette → on-brand hex, inline style

    // Permissions
    canEditBy(?User): bool  // super_admin any; admin/marketing own only
}
```

Thumbnail color palette (rendered as inline `style="background:#..."`, NOT Tailwind class —
avoids needing the class compiled into the build):
`#165DFF, #1E40AF, #0F766E, #334155, #7C3AED, #0E7490`

### `BlogCategory` / `CaseStudy` / `CaseStudyResult` / `Testimonial` / `Faq` / `LogoWallItem` — [UNVERIFIED]
(Structure below is from older notes; not re-checked this session.)
- `BlogCategory`: posts (BelongsToMany), ordered() scope
- `CaseStudy`: results (HasMany, ordered), published()/ordered() scopes, gallery=>array cast
- `CaseStudyResult`: caseStudy (BelongsTo), ordered()
- `Testimonial`: featured(), ordered()
- `Faq`: active(), ordered() — HARD delete
- `LogoWallItem`: GROUPS const ('clients','awards'); active(), group(), ordered() scopes

---

## 6. CONTROLLERS

### Admin Controllers (in `Admin/`)

| Controller | Purpose |
|---|---|
| `BlogPostController` | Resource CRUD. Slug auto from title. Categories pivot sync on store/update. SEO fields validated (meta_title max:70, meta_description max:160). YouTube URL/ID parsed to 11-char ID via `extractYoutubeId()`. NO thumbnail upload handling (removed 22 May 2026). |
| `BlogCategoryController` | Resource CRUD. Slug auto. [UNVERIFIED] |
| `CaseStudyController` | Resource CRUD. Nested results via Alpine. [UNVERIFIED] |
| `TestimonialController` | Resource CRUD. [UNVERIFIED] |
| `FaqController` | Resource CRUD. [UNVERIFIED] |
| `LogoWallController` | Resource CRUD. Group filter. [UNVERIFIED] |
| `MarketingOverviewController` | Dashboard at `/admin/marketing` [UNVERIFIED] |

### Public Controllers (in `Http/Controllers/`)

| Controller | Routes | Purpose |
|---|---|---|
| `HomeController` | `GET /` | Homepage — pulls content sections. [UNVERIFIED] |
| `PublicBlogController` | `GET /blog`, `GET /blog/{public_id}/{slug}` | Listing (hero + grid + category/search filter) + detail. VERIFIED. |
| `AboutController` | `GET /about` | About page. [UNVERIFIED] |

**`PublicBlogController` — VERIFIED notes:**
- `index()`: hybrid layout. No filter → hero = latest published, grid = rest paginated (PER_PAGE=9). Filter active (category slug or `q` search) → hero hidden, all matches in grid.
- `show($publicId, $slug)`: resolves by public_id, 404 if not published, 301 redirect if slug stale, sanitizes content (`clean(..., 'blog')`), loads ≤3 related posts.
- `buildMetaDescription()`: prefers admin `meta_description`, falls back to ~160-char plain-text content slice.

---

## 7. ROUTES

### Admin Routes
```php
Route::resource('blog-posts', Admin\BlogPostController::class);
Route::resource('blog-categories', Admin\BlogCategoryController::class);
Route::resource('case-studies', Admin\CaseStudyController::class);
Route::resource('testimonials', Admin\TestimonialController::class);
Route::resource('faqs', Admin\FaqController::class);
Route::resource('logo-wall', Admin\LogoWallController::class);
// Marketing overview route [UNVERIFIED]
```

**HTTP verb note (VERIFIED 22 May 2026):** `destroy` routes for the CMS modules
(blog-categories, case-studies, faqs, logo-wall, testimonials) use the real `DELETE` verb
and work fine on DomaiNesia (FAQ delete test → 302). The old "ModSecurity blocks DELETE/PUT/PATCH,
must use flat POST" rule is OUTDATED — see notes-general. Do not migrate working routes.

### Public Routes
```php
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/blog', [PublicBlogController::class, 'index'])->name('public.blog.index');
Route::get('/blog/{public_id}/{slug}', [PublicBlogController::class, 'show'])->name('public.blog.show');
Route::get('/about', [AboutController::class, 'index'])->name('public.about'); // [UNVERIFIED]
```

---

## 8. VIEWS STRUCTURE

```
resources/views/
├── admin/
│   ├── blog/
│   │   ├── posts/{index, create, edit, show, _form}.blade.php   ← VERIFIED
│   │   └── categories/{index, ...}.blade.php
│   ├── case-studies/      [UNVERIFIED]
│   ├── testimonials/      [UNVERIFIED]
│   ├── faqs/              [UNVERIFIED]
│   └── logo-wall/         [UNVERIFIED]
└── public/
    ├── home/index.blade.php          [UNVERIFIED]
    ├── about/index.blade.php         [UNVERIFIED]
    └── blog/{index, show, _post_card}.blade.php   ← VERIFIED
```

Note: blog admin views live under `admin/blog/posts/` (nested), and the form is a shared
partial `_form.blade.php` used by both create & edit.

---

## 9. KEY UI PATTERNS

### Blog Post Form — VERIFIED 22 May 2026
- Editor is **Quill 2.0.3** (NOT CKEditor). Hidden textarea `#content-input` receives Quill
  HTML on submit; server sanitizes via purifier 'blog' profile.
- Sidebar cards: Publish (status + scheduled datetime), Categories (checkbox, max 10), YouTube Video.
- SEO card (Meta Title + Meta Description) sits **full-width below the Content card** (Yoast-style
  placement), with live character counters (70 / 160).
- NO featured-image upload (removed). Thumbnail is derived automatically (see thumbnail strategy).
- YouTube field accepts full URL (youtu.be / watch?v= / embed / shorts) or 11-char ID.

### Case Study / Logo Wall / FAQ forms — [UNVERIFIED]
(See older descriptions; not re-checked. Case study uses repeatable Alpine results section;
logo wall has group filter + drag-drop reorder; FAQ is a simple ordered list.)

---

## 10. PUBLIC PAGE RENDERING LOGIC

### Homepage (`/`) — [UNVERIFIED]
Section order (per older notes): Hero (+ award card), Logo Bar (clients), Services, How It Works,
Why Us, Case Study, Testimonials, Stats, FAQ, CTA Banner. Reference design: zatomarketing.com.

### Blog Index (`/blog`) — VERIFIED
- Hybrid: hero (latest published) + grid. PER_PAGE = 9.
- Category filter (slug) + `q` search (title + content LIKE).
- Only published + `published_at <= now()` (via published scope / effective_status).
- Hero and cards both use the thumbnail accessors; cards via partial `_post_card`.

### Blog Show (`/blog/{public_id}/{slug}`) — VERIFIED
- Sanitized HTML content, category links, author info, related posts (≤3).
- YouTube embed shown inline if `youtube_video_id` present.
- 301 redirect to canonical slug if URL slug is stale.
- NO view_count (column doesn't exist).
- `<title>` = `(meta_title ?: title) . ' | Blog Digimaya'`; meta description from controller.

---

## 11. CONTENT WORKFLOWS

### Publish Blog Post — VERIFIED
1. Admin → Marketing → Posts → New
2. Fill title, content (Quill).
3. Optionally paste a YouTube URL/ID (becomes the thumbnail + inline embed).
4. Select categories (max 10).
5. Fill SEO (meta_title / meta_description) — optional; falls back to title/content.
6. Status: draft / scheduled (requires published_at) / published.
7. Save.

### Case Study / Logo Wall / Testimonial workflows — [UNVERIFIED]
(See older notes; not re-checked this session.)

---

## 12. MARKETING OVERVIEW DASHBOARD — [UNVERIFIED]

**Path**: `/admin/marketing` · **Access**: super_admin, admin, marketing
Suggested: content stats, recent activity, quick actions. Not verified against live code.

---

## 13. KEY VALIDATIONS

### Blog Post — VERIFIED 22 May 2026
- `title`: required, string, max 200
- `content`: nullable, string, max 65535
- `categories`: nullable array max 10; each integer + exists:blog_categories,id
- `youtube_input`: nullable, string, max 500 (parsed to ID server-side)
- `meta_title`: nullable, string, max 70
- `meta_description`: nullable, string, max 160
- `status`: Rule::in(STATUSES)
- `published_at`: nullable, date, required_if status=scheduled
- (NO thumbnail validation — upload removed)

### Case Study / Testimonial / FAQ / Logo Wall — [UNVERIFIED]
(See older notes.)

---

## 14. IMAGE HANDLING

### Blog thumbnails — VERIFIED
Blog posts store NO image files. Thumbnails are either hotlinked YouTube covers or
generated text-cards (CSS). No upload, no storage, no symlink dependency for blog.

### Other modules — [UNVERIFIED]
Older notes: uploads to `storage/app/public/{module}/`, served via `storage:link`,
path stored relative, displayed via `asset('storage/'.$path)`. Max 2MB blog (obsolete),
1MB logos/avatars. Allowed jpg/png/webp. Not re-checked.

---

## 15. SEO CONSIDERATIONS

### Per-Post Meta — VERIFIED
- `meta_title` max 70 (also used as text-thumbnail text); `meta_description` max 160.
- Detail page renders `<title>` and meta description; OG image only for video posts.
- meta_description falls back to plain-text content slice when empty.

### URL Structure — VERIFIED
- Blog: `/blog/{public_id}/{slug}`. Slug auto from title; stale slug → 301 to canonical.

### Sitemap — [UNVERIFIED / TODO]
Dynamic sitemap.xml not confirmed implemented.

---

## 16. KNOWN GOTCHAS

### HTML Sanitization
Blog `content` (and FAQ `answer`) sanitized via `mews/purifier`. Blog uses the 'blog' profile
(`clean($content, 'blog')`). Quill toolbar already restricts input; purifier is defense-in-depth.

### Slug / public_id Uniqueness with SoftDeletes — VERIFIED, IMPORTANT
`blog_posts` DOES use SoftDeletes. Any unique-value generator (slug, public_id) must account for
soft-deleted rows that still occupy the value. This is the SoftDeletes bug family (see notes-general).

### Pivot Table for Categories
`blog_post_category` — use `sync()` not `attach()`. Pivot rows are hard-deleted on detach;
restoring a soft-deleted post does NOT restore its category links.

### Status Filter on Public Pages
Published visibility requires BOTH `status='published'` AND `published_at <= now()`.
Handled via `effective_status` accessor / published scope. Status alone leaks scheduled posts.

### Logo Wall Dual-Purpose Table — [UNVERIFIED]
Single table, two render contexts (clients = grayscale bar, awards = hero card). Always filter by `group`.

### Thumbnail YouTube fallback — VERIFIED
`maxresdefault.jpg` doesn't exist for every video (low-res/old). Public img tags use
`onerror` to swap to `hqdefault.jpg`. Don't remove the onerror handler.