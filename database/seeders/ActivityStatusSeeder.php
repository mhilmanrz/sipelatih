<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Act\ActivityStatus;

class ActivityStatusSeeder extends Seeder
{
    public function run(): void
    {
        $activityStatuses = [
            ['activity_id' => 1, 'date' => '2025-12-29', 'status' => 'ditolak', 'note' => null],
            ['activity_id' => 1, 'date' => '2026-01-01', 'status' => 'butuh_perbaikan', 'note' => 'Lengkapi RAB'],
            ['activity_id' => 1, 'date' => '2026-01-05', 'status' => 'disetujui', 'note' => null],
        ];

        foreach ($activityStatuses as $activityStatus) {
            ActivityStatus::create($activityStatus);
        }
    }
}
