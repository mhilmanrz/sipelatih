<?php

namespace Database\Seeders;

use App\Models\ProfessionCategory;
use Illuminate\Database\Seeder;

class ProfessionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Tenaga Medis', 'jpl_target' => 20],
            ['name' => 'Tenaga Keperawatan', 'jpl_target' => 20],
            ['name' => 'Tenaga Kefarmasian', 'jpl_target' => 20],
            ['name' => 'Tenaga Kesehatan Masyarakat', 'jpl_target' => 20],
            ['name' => 'Tenaga Gizi', 'jpl_target' => 20],
            ['name' => 'Tenaga Keterapian Fisik', 'jpl_target' => 20],
            ['name' => 'Tenaga Keteknisian Medis', 'jpl_target' => 20],
            ['name' => 'Tenaga Non-Klinis / Administrasi', 'jpl_target' => 0],
        ];

        foreach ($categories as $category) {
            ProfessionCategory::firstOrCreate(
                ['name' => $category['name']],
                ['jpl_target' => $category['jpl_target']],
            );
        }
    }
}
