<?php

namespace Database\Seeders;

use App\Models\Act\ActivityFormat;
use Illuminate\Database\Seeder;

class ActivityFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activityFormats = [
            ['name' => 'Mandiri'],
            ['name' => 'Kerjasama'],
        ];

        foreach ($activityFormats as $activityFormat) {
            ActivityFormat::create($activityFormat);
        }
    }
}
