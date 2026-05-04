<?php
// Form Jenis Kegiatan 

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
            ['name' => 'Pelatihan'],
            ['name' => 'Workshop'],
            ['name' => 'Webinar'],
            ['name' => 'Seminar'],
            ['name' => 'MOOC'],
        ];

        foreach ($types as $type) {
            ActivityType::create($type);
        }
    }
}
