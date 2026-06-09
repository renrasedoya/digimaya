<?php

/*
|--------------------------------------------------------------------------
| Digimaya Public-Facing Data
|--------------------------------------------------------------------------
|
| Centralized config untuk data yang ditampilkan di halaman publik:
| kontak, alamat, sosmed, dan SEO defaults.
|
| Migrasi roadmap: nanti akan di-migrate ke Settings module (TD-003),
| diakses via setting('digimaya.X') helper dengan fallback ke config ini.
| Pattern access via config('digimaya.X') dari Blade tetap, jadi refactor
| nanti minimal.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Brand Identity
    |--------------------------------------------------------------------------
    */
    'brand' => [
        'name'        => 'Digimaya',
        'legal_name'  => 'Digimaya',
        'tagline'     => 'Google Ads Agency untuk Bisnis Indonesia',
        'description' => 'Digimaya adalah Google Ads Agency Premier Partner di Indonesia. Strategi terukur, tracking presisi, transparansi penuh.',
        'theme_color' => '#165DFF',
        'logo'        => 'images/logo/logo-blue.png',
        'og_image'    => 'images/logo/logo-blue.png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Contact Info
    |--------------------------------------------------------------------------
    */
    'contact' => [
        'whatsapp'         => '+6285213228692',
        'whatsapp_display' => '+62 852-1322-8692',
        'whatsapp_wa_url'  => 'https://wa.me/6285213228692',
        'email'            => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Address (PostalAddress for Schema)
    |--------------------------------------------------------------------------
    */
    /*
    | Digimaya is a full-remote agency — no physical PostalAddress.
    | areaServed: "ID" on Organization.contactPoint covers location signal.
    | If a physical office opens later, fill these values to re-enable
    | PostalAddress rendering in Schema (component handles it conditionally).
    */
    'address' => [
        'locality' => null,
        'region'   => null,
        'country'  => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media URLs
    |--------------------------------------------------------------------------
    | Used in Schema sameAs, footer, share buttons. Null = not active.
    */
    'social' => [
        'instagram' => 'https://www.instagram.com/digimaya.agency/',
        'tiktok'    => 'https://www.tiktok.com/@digimaya',
        'youtube'   => 'https://www.youtube.com/@digimaya',
        'twitter'   => 'https://x.com/digimaya_agency',
        'threads'   => 'https://www.threads.com/@digimaya.agency',
        'facebook'  => null,
        'linkedin'  => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO Defaults
    |--------------------------------------------------------------------------
    */
    'seo' => [
        'site_name'   => 'Digimaya',
        'locale'      => 'id_ID',
        'og_image_w'  => 1200,
        'og_image_h'  => 630,
    ],

];
