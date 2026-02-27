<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Act\ActivityProfession;

class ActivityProfessionSeeder extends Seeder
{
    public function run(): void
    {
        $activityProfessions = [
            ['activity_id' => 1, 'profession_id' => 1],
            ['activity_id' => 1, 'profession_id' => 2],
            ['activity_id' => 1, 'profession_id' => 3],
            ['activity_id' => 1, 'profession_id' => 4],
            ['activity_id' => 1, 'profession_id' => 5],
        ];

        foreach ($activityProfessions as $activityProfession) {
            ActivityProfession::create($activityProfession);
        }
    }
}
