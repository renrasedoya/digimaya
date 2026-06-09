<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Salary',
                'description' => 'Employee salaries and wages',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Rent',
                'description' => 'Office rent and related fees',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Utility',
                'description' => 'Electricity, water, internet bills',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Software Tools',
                'description' => 'SaaS subscriptions, licenses, software tools',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Marketing',
                'description' => 'Marketing expenses, ad spend, promotional costs',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Office Supply',
                'description' => 'Office supplies and consumables',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Travel',
                'description' => 'Travel, transportation, accommodation expenses',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Other',
                'description' => 'Other miscellaneous expenses',
                'is_active' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
