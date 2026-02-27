<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Act\ActivityScore;

class ActivityScoreSeeder extends Seeder
{
    public function run(): void
    {
        $activityScores = [
            ['activity_participant_id' => 1, 'pre_test_score' => 60, 'post_test_score' => 85, 'practice_score' => 80],
            ['activity_participant_id' => 2, 'pre_test_score' => 70, 'post_test_score' => 90, 'practice_score' => 88],
            ['activity_participant_id' => 3, 'pre_test_score' => 50, 'post_test_score' => 75, 'practice_score' => 70],
            ['activity_participant_id' => 4, 'pre_test_score' => 80, 'post_test_score' => 95, 'practice_score' => 92],
            ['activity_participant_id' => 5, 'pre_test_score' => 55, 'post_test_score' => 65, 'practice_score' => 60],
        ];

        foreach ($activityScores as $activityScore) {
            ActivityScore::create($activityScore);
        }
    }
}
