<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Act\MaterialType;

class MaterialTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materialTypes = [
            ['name' => 'Kurikulum'],
            ['name' => 'Non Kurikulum'],
        ];

        foreach ($materialTypes as $materialType) {
            MaterialType::firstOrCreate($materialType);
        }
    }
}
