<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Ads Management',
                'category' => 'agency',
                'description' => 'Google Ads, Meta Ads, TikTok Ads management service',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Next Gen Workshop',
                'category' => 'academy',
                'description' => 'Workshop training for new generation marketers',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Corporate Training',
                'category' => 'academy',
                'description' => 'Customized corporate training programs',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}
