<?php

namespace Database\Seeders;

use App\Models\PricingTier;
use Illuminate\Database\Seeder;

class PricingTierSeeder extends Seeder
{
    public function run(): void
    {
        $lower = [
            [4000000, 950000],
            [5000000, 1250000],
            [6000000, 1500000],
            [7000000, 1750000],
            [8000000, 1950000],
            [9000000, 2250000],
            [10000000, 2500000],
        ];

        $upper = [
            [11000000, 2750000],
            [12000000, 2950000],
            [13000000, 3250000],
            [14000000, 3500000],
            [15000000, 3750000],
            [16000000, 3950000],
            [50000000, 4950000],
            [100000000, 6950000],
        ];

        $sort = 0;

        foreach ($lower as [$budget, $fee]) {
            PricingTier::updateOrCreate(
                ['budget' => $budget],
                ['agency_fee' => $fee, 'zone' => PricingTier::ZONE_LOWER, 'sort_order' => $sort++, 'is_active' => true]
            );
        }

        foreach ($upper as [$budget, $fee]) {
            PricingTier::updateOrCreate(
                ['budget' => $budget],
                ['agency_fee' => $fee, 'zone' => PricingTier::ZONE_UPPER, 'sort_order' => $sort++, 'is_active' => true]
            );
        }
    }
}
