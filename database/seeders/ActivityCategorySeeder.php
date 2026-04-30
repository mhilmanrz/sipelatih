<?php

namespace Database\Seeders;

use App\Models\Act\ActivityCategory;
use Illuminate\Database\Seeder;

class ActivityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Teknis'],
            ['name' => 'Manajerial'],
            ['name' => 'Sosial Kultural'],
        ];

        foreach ($categories as $category) {
            ActivityCategory::firstOrCreate(['name' => $category['name']]);
        }
    }
}
