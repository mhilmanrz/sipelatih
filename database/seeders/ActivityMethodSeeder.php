<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['name' => 'Luring'],
            ['name' => 'Daring'],
            ['name' => 'Blanded'],
        ];

        foreach ($activityMethods as $activityMethod) {
            ActivityMethod::create($activityMethod);
        }
    }
}
