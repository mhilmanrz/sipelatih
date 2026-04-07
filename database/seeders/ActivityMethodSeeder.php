<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Act\ActivityMethod;

class ActivityMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activityMethods = [
            ['name' => 'Blended'],
            ['name' => 'Luring'],
            ['name' => 'Daring'],
            ['name' => 'LMS'],
        ];

        foreach ($activityMethods as $activityMethod) {
            ActivityMethod::firstOrCreate($activityMethod);
        }
    }
}
