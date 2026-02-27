<?php

namespace Database\Seeders;

use App\Models\Act\ActivityType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Pelatihan Teknis'],
            ['name' => 'Pelatihan Fungsional'],
            ['name' => 'Pelatihan Manajerial'],
            ['name' => 'Pelatihan Sosial Kultural'],
            ['name' => 'Pelatihan Kepemimpinan'],
        ];

        foreach ($types as $type) {
            ActivityType::create($type);
        }
    }
}
