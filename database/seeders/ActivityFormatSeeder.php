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
            ['name' => 'Klasikal'],
            ['name' => 'Non Klasikal'],
            ['name' => 'Workshop'],
            ['name' => 'Seminar'],
            ['name' => 'Webinar'],
        ];

        foreach ($activityFormats as $activityFormat) {
            ActivityFormat::create($activityFormat);
        }
    }
}
