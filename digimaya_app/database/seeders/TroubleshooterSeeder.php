<?php

namespace Database\Seeders;

use App\Models\TroubleshooterNode;
use Illuminate\Database\Seeder;

class TroubleshooterSeeder extends Seeder
{
    public function run(): void
    {
        $rootProblems = [
            'Iklan tidak tayang',
            'Tagihan dan pembayaran',
            'Masalah kebijakan',
            'Masalah performa',
            'Masalah akun',
            'Masalah data dan laporan',
            'Masalah tracking',
        ];

        foreach ($rootProblems as $index => $label) {
            TroubleshooterNode::firstOrCreate(
                ['parent_id' => null, 'label' => $label],
                [
                    'type' => 'question',
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Seeded ' . count($rootProblems) . ' root problems.');
    }
}
