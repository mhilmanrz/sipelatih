<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Act\ActivityParticipant;

class ActivityParticipantSeeder extends Seeder
{
    public function run(): void
    {
        // Use different user IDs to satisfy the unique(activity_id, user_id) constraint
        $activityParticipants = [
            ['activity_id' => 1, 'user_id' => 1, 'is_passed' => false],
            ['activity_id' => 1, 'user_id' => 2, 'is_passed' => false],
            ['activity_id' => 1, 'user_id' => 3, 'is_passed' => false],
        ];

        foreach ($activityParticipants as $activityParticipant) {
            ActivityParticipant::create($activityParticipant);
        }
    }
}
