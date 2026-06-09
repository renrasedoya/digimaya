<?php

namespace Database\Seeders;

use App\Models\IssueCategory;
use App\Models\IssueSubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IssueCategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'Performance',
                'subs' => [
                    'Conversion drop',
                    'CPA increase',
                    'ROAS decline',
                    'CTR decline',
                    'CPC spike',
                    'Lead quality declining',
                    'Fluctuation',
                ],
            ],
            [
                'name' => 'Tracking & Measurement',
                'subs' => [
                    'Tracking issue',
                    'Tracking discrepancy',
                ],
            ],
            [
                'name' => 'Budget & Bidding',
                'subs' => [
                    'Budget over-spend',
                    'Budget under-spend',
                ],
            ],
            [
                'name' => 'Creative & Asset',
                'subs' => [
                    'Ad / Asset rejected',
                    'Creative fatigue',
                    'Asset performance low',
                    'Brand asset missing',
                ],
            ],
            [
                'name' => 'Account & Policy',
                'subs' => [
                    'Account suspended',
                    'Account restriction',
                    'Policy issue',
                    'Billing issue',
                ],
            ],
            [
                'name' => 'Audience & Targeting',
                'subs' => [
                    'Audience underperforming',
                    'Audience overlap',
                    'Targeting issue',
                ],
            ],
            [
                'name' => 'Client-side',
                'subs' => [
                    'Landing page issue',
                    'Client request strategy change',
                ],
            ],
            [
                'name' => 'Strategy & Scaling',
                'subs' => [
                    'Scaling opportunity',
                    'External context shift',
                    'Strategy review needed',
                ],
            ],
            [
                'name' => 'Other',
                'subs' => [
                    'Other (please specify in notes)',
                ],
            ],
        ];

        DB::transaction(function () use ($data) {
            foreach ($data as $catIndex => $cat) {
                $category = IssueCategory::firstOrCreate(
                    ['name' => $cat['name']],
                    [
                        'display_order' => ($catIndex + 1) * 10,
                        'is_active' => true,
                    ]
                );

                foreach ($cat['subs'] as $subIndex => $subName) {
                    IssueSubCategory::firstOrCreate(
                        [
                            'issue_category_id' => $category->id,
                            'name' => $subName,
                        ],
                        [
                            'display_order' => ($subIndex + 1) * 10,
                            'is_active' => true,
                        ]
                    );
                }
            }
        });

        $catCount = IssueCategory::count();
        $subCount = IssueSubCategory::count();
        $this->command->info("Seeded: {$catCount} categories, {$subCount} sub-categories.");
    }
}
