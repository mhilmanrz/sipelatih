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
            ['name' => 'Bahan Habis Pakai'],
            ['name' => 'Bahan Tidak Habis Pakai'],
        ];

        foreach ($materialTypes as $materialType) {
            MaterialType::create($materialType);
        }
    }
}
