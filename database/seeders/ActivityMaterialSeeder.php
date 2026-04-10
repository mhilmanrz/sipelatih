<?php

namespace Database\Seeders;

use App\Models\Act\ActivityMaterial;
use Illuminate\Database\Seeder;

class ActivityMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $activityMaterials = [
            ['activity_id' => 1, 'name' => 'Modul Pelatihan', 'value' => 100],
            ['activity_id' => 1, 'name' => 'Buku Panduan', 'value' => 50],
            ['activity_id' => 1, 'name' => 'Slide Presentasi', 'value' => 75],
            ['activity_id' => 1, 'name' => 'Video Tutorial', 'value' => 30],
            ['activity_id' => 1, 'name' => 'Lembar Kerja', 'value' => 200],
        ];

        foreach ($activityMaterials as $activityMaterial) {
            ActivityMaterial::create($activityMaterial);
        }
    }
}
