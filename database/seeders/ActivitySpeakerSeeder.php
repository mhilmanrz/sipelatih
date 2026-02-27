<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Act\ActivitySpeaker;

class ActivitySpeakerSeeder extends Seeder
{
    public function run(): void
    {
        $activitySpeakers = [
            ['activity_material_id' => 1, 'user_id' => 1],
            ['activity_material_id' => 2, 'user_id' => 1],
            ['activity_material_id' => 3, 'user_id' => 1],
            ['activity_material_id' => 4, 'user_id' => 1],
            ['activity_material_id' => 5, 'user_id' => 1],
        ];

        foreach ($activitySpeakers as $activitySpeaker) {
            ActivitySpeaker::create($activitySpeaker);
        }
    }
}
