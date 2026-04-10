<?php

namespace Database\Seeders;

use App\Models\Act\ActivityMethod;
use Illuminate\Database\Seeder;

class ActivityMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activityMethods = [
            ['name' => 'Luring'],
            ['name' => 'Daring'],
            ['name' => 'Blanded'],
        ];

        foreach ($activityMethods as $activityMethod) {
            ActivityMethod::create($activityMethod);
        }
    }
}
