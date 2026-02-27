<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Act\ActivityModerator;

class ActivityModeratorSeeder extends Seeder
{
    public function run(): void
    {
        $activityModerators = [
            ['activity_material_id' => 1, 'user_id' => 1],
            ['activity_material_id' => 2, 'user_id' => 1],
            ['activity_material_id' => 3, 'user_id' => 1],
            ['activity_material_id' => 4, 'user_id' => 1],
            ['activity_material_id' => 5, 'user_id' => 1],
        ];

        foreach ($activityModerators as $activityModerator) {
            ActivityModerator::create($activityModerator);
        }
    }
}
