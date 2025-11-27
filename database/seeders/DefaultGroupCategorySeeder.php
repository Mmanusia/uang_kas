<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Groups;
use App\Models\Category;

class DefaultGroupCategorySeeder extends Seeder
{
    public function run()
    {
        $groups = [
            'Living',
            'Playing',
            'Saving'
        ];

        foreach ($groups as $g) {
            $group = Groups::firstOrCreate(['name' => $g]);

            // Auto categories per group
            $defaultCategories = [
                'Living' => ['Belanja', 'Transport', 'Tagihan'],
                'Playing' => ['Hiburan', 'Game', 'Nonton'],
                'Saving' => ['Tabungan', 'Investasi']
            ];

            foreach ($defaultCategories[$g] as $cat) {
                Category::firstOrCreate([
                    'name' => $cat,
                    'type' => 'expense',
                    'group_id' => $group->id
                ]);
            }
        }

        // Tambah kategori income
        Category::firstOrCreate([
            'name' => 'Income Utama',
            'type' => 'income',
            'group_id' => Groups::where('name', 'Saving')->first()->id
        ]);
    }
}
