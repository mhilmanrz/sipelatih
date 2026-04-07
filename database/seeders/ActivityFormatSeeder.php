<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Act\ActivityFormat;

class ActivityFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activityFormats = [
            ['name' => 'Mandiri'], //gbsa isi institusi Kerjasama
            ['name' => 'Kerjasama'],
        ];

        foreach ($activityFormats as $activityFormat) {
            ActivityFormat::firstOrCreate($activityFormat);
        }
    }
}
