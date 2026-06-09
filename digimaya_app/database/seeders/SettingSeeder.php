<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed the settings table with default values.
     *
     * Uses firstOrCreate keyed by 'key' column to ensure idempotency:
     * existing settings are not overwritten on re-run.
     */
    public function run(): void
    {
        $settings = [
            // Company Info
            ['key' => 'company_name',           'value' => 'Digimaya', 'group' => 'company', 'type' => 'string', 'sort_order' => 1, 'description' => 'Company display name'],
            ['key' => 'company_address_line_1', 'value' => '',         'group' => 'company', 'type' => 'string', 'sort_order' => 2, 'description' => 'Address line 1'],
            ['key' => 'company_address_line_2', 'value' => '',         'group' => 'company', 'type' => 'string', 'sort_order' => 3, 'description' => 'Address line 2 (optional)'],
            ['key' => 'company_email',          'value' => '',         'group' => 'company', 'type' => 'string', 'sort_order' => 4, 'description' => 'Invoicing email address'],
            ['key' => 'company_phone',          'value' => '',         'group' => 'company', 'type' => 'string', 'sort_order' => 5, 'description' => 'Contact phone number'],
            ['key' => 'company_npwp',           'value' => '',         'group' => 'company', 'type' => 'string', 'sort_order' => 6, 'description' => 'Tax ID (optional)'],

            // Banking
            ['key' => 'bank_name',           'value' => '', 'group' => 'banking', 'type' => 'string', 'sort_order' => 1, 'description' => 'Bank name'],
            ['key' => 'bank_account_number', 'value' => '', 'group' => 'banking', 'type' => 'string', 'sort_order' => 2, 'description' => 'Account number'],
            ['key' => 'bank_account_holder', 'value' => '', 'group' => 'banking', 'type' => 'string', 'sort_order' => 3, 'description' => 'Account holder name'],

            // Invoice Settings
            ['key' => 'invoice_number_prefix',    'value' => 'DGMY',                          'group' => 'invoice', 'type' => 'string',  'sort_order' => 1, 'description' => 'Invoice number prefix'],
            ['key' => 'invoice_due_offset_days',  'value' => '14',                            'group' => 'invoice', 'type' => 'integer', 'sort_order' => 2, 'description' => 'Default days from issue date to due date'],
            ['key' => 'invoice_default_tax_rate', 'value' => '0',                             'group' => 'invoice', 'type' => 'decimal', 'sort_order' => 3, 'description' => 'Default tax rate percentage (0 = no tax)'],
            ['key' => 'invoice_footer_notes',     'value' => 'Thank you for your business!', 'group' => 'invoice', 'type' => 'text',    'sort_order' => 4, 'description' => 'Footer notes shown on invoice PDF'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Seeded ' . count($settings) . ' settings (idempotent: existing not overwritten).');
    }
}
