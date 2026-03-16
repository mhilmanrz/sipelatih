<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Act\ActivityMaterial;

class ActivityMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $activityMaterials = [
            ['activity_id' => 1, 'name' => 'Pelatihan Aseptic Dispensing', 'jpl' => 2],
            ['activity_id' => 1, 'name' => 'Bantuan Hidup Dasar (BHD)', 'jpl' => 1],
            ['activity_id' => 1, 'name' => 'Manajemen Fasilitas dan Keamanan (MFK)', 'jpl' => 0.3],
        ];

        foreach ($activityMaterials as $activityMaterial) {
            ActivityMaterial::create($activityMaterial);
        }
    }
}
