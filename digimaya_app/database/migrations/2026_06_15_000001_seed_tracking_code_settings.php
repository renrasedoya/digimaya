<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Seed the three "Insert Headers and Footers" custom code settings (group: tracking).
 * Lets a super admin paste GTM / GA / pixel / verification snippets without touching code.
 * Idempotent: inserts only missing keys so re-running never overwrites a saved value.
 */
return new class extends Migration
{
    private array $settings = [
        ['key' => 'tracking_code_head',       'sort_order' => 1, 'description' => 'Code injected into <head> on every public page (e.g. GTM, GA4, site verification).'],
        ['key' => 'tracking_code_body_open',  'sort_order' => 2, 'description' => 'Code injected immediately after the opening <body> tag (e.g. GTM noscript).'],
        ['key' => 'tracking_code_body_close', 'sort_order' => 3, 'description' => 'Code injected just before the closing </body> tag (e.g. chat widgets, deferred scripts).'],
    ];

    public function up(): void
    {
        foreach ($this->settings as $s) {
            $exists = DB::table('settings')->where('key', $s['key'])->exists();
            if ($exists) {
                continue;
            }

            DB::table('settings')->insert([
                'key'         => $s['key'],
                'value'       => '',
                'group'       => 'tracking',
                'type'        => 'text',
                'sort_order'  => $s['sort_order'],
                'description' => $s['description'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('settings')
            ->whereIn('key', array_column($this->settings, 'key'))
            ->delete();
    }
};
