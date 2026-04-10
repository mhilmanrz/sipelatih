<?php

namespace Database\Seeders;

use App\Models\Act\MaterialType;
use Illuminate\Database\Seeder;

class MaterialTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materialTypes = [
            ['name' => 'Bahan Habis Pakai'],
            ['name' => 'Bahan Tidak Habis Pakai'],
        ];

        foreach ($materialTypes as $materialType) {
            MaterialType::create($materialType);
        }
    }
}
