<?php

namespace Database\Seeders;

use App\Models\Act\ActivityType;
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
            ['name' => 'Pelatihan Manajerial'],
            ['name' => 'Pelatihan Sosial Kultural'],
        ];

        foreach ($types as $type) {
            ActivityType::create($type);
        }
    }
}
