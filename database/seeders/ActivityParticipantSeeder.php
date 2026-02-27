<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Act\ActivityParticipant;

class ActivityParticipantSeeder extends Seeder
{
    public function run(): void
    {
        $activityParticipants = [
            ['activity_id' => 1, 'user_id' => 1, 'certificate_number' => 'CERT-2026-001', 'is_passed' => true],
            ['activity_id' => 1, 'user_id' => 1, 'certificate_number' => 'CERT-2026-002', 'is_passed' => true],
            ['activity_id' => 1, 'user_id' => 1, 'certificate_number' => 'CERT-2026-003', 'is_passed' => false],
            ['activity_id' => 1, 'user_id' => 1, 'certificate_number' => 'CERT-2026-004', 'is_passed' => true],
            ['activity_id' => 1, 'user_id' => 1, 'certificate_number' => 'CERT-2026-005', 'is_passed' => false],
        ];

        foreach ($activityParticipants as $activityParticipant) {
            ActivityParticipant::create($activityParticipant);
        }
    }
}
